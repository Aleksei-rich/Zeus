#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="fe9d7cca42dc63fbb0f57e5b38c0d31f24af4d9d"
BASELINE_REF="e1b3b5f601c6b0cf5159583aac540a9d407cc8a6"
REPO_PATH="plugins/zeus-core/inc/cabinet-colors.php"
DEST_REL="wp-content/plugins/zeus-core/inc/cabinet-colors.php"
RAW_TARGET="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}/${REPO_PATH}"
RAW_BASELINE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${BASELINE_REF}/${REPO_PATH}"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"
ARMED=0
IN_ROLLBACK=0

cleanup(){ rm -rf "$TMP"; }
trap cleanup EXIT

restore_file(){
  cp -p "$BACKUP/$DEST_REL" "$ROOT/$DEST_REL"
}

rollback(){
  [[ "$IN_ROLLBACK" == "1" ]] && return
  IN_ROLLBACK=1
  trap - ERR
  echo "Rolling back $DEST_REL ..." >&2
  restore_file
  wp cache flush >/dev/null 2>&1 || true
  echo "Rollback complete. Manual rollback remains: $BACKUP/ROLLBACK.sh" >&2
}

die(){
  echo "ERROR: $*" >&2
  [[ "$ARMED" == "1" ]] && rollback
  exit 1
}

on_err(){
  code=$?
  echo "ERROR: unexpected failure (exit $code)." >&2
  [[ "$ARMED" == "1" ]] && rollback
  exit "$code"
}
trap on_err ERR

echo "==> Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found"
[[ -f "$ROOT/$DEST_REL" ]] || die "Production file missing: $DEST_REL"
command -v wp >/dev/null 2>&1 || die "WP-CLI unavailable"
command -v php >/dev/null 2>&1 || die "PHP CLI unavailable"
command -v curl >/dev/null 2>&1 || die "curl unavailable"
command -v sha256sum >/dev/null 2>&1 || die "sha256sum unavailable"
cd "$ROOT"
wp plugin is-active zeus-core >/dev/null 2>&1 || die "ZEUS Core is not active"

echo "==> Baseline and target validation"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASELINE" -o "$TMP/base.php" || die "Could not fetch baseline"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_TARGET" -o "$TMP/target.php" || die "Could not fetch target"
[[ -s "$TMP/base.php" && -s "$TMP/target.php" ]] || die "Downloaded file is empty"
php -l "$TMP/base.php" >/dev/null || die "Baseline PHP lint failed"
php -l "$TMP/target.php" >/dev/null || die "Target PHP lint failed"

live_hash="$(sha256sum "$ROOT/$DEST_REL" | awk '{print $1}')"
base_hash="$(sha256sum "$TMP/base.php" | awk '{print $1}')"
target_hash="$(sha256sum "$TMP/target.php" | awk '{print $1}')"
[[ "$live_hash" == "$base_hash" ]] || die "Production file does not match expected baseline $BASELINE_REF. Nothing changed."
[[ "$target_hash" != "$base_hash" ]] || die "Target unexpectedly matches baseline"

echo "==> Backup"
mkdir -p "$BACKUP/$(dirname "$DEST_REL")"
cp -p "$ROOT/$DEST_REL" "$BACKUP/$DEST_REL"
printf 'source=%s\nbaseline=%s\nfile=%s\n' "$SOURCE_REF" "$BASELINE_REF" "$DEST_REL" > "$BACKUP/manifest.txt"

cat > "$BACKUP/ROLLBACK.sh" <<ROLLBACK_EOF
#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$ROOT"
BACKUP="$BACKUP"
DEST_REL="$DEST_REL"
cp -p "\$BACKUP/\$DEST_REL" "\$ROOT/\$DEST_REL"
cd "\$ROOT"
wp cache flush || true
echo "Rollback complete."
ROLLBACK_EOF
chmod +x "$BACKUP/ROLLBACK.sh"
echo "Backup: $BACKUP"
echo "Rollback: $BACKUP/ROLLBACK.sh"

ARMED=1

echo "==> Install sitemap registration timing fix"
cp "$TMP/target.php" "$ROOT/$DEST_REL"
php -l "$ROOT/$DEST_REL" >/dev/null || die "Production PHP lint failed"
installed_hash="$(sha256sum "$ROOT/$DEST_REL" | awk '{print $1}')"
[[ "$installed_hash" == "$target_hash" ]] || die "Installed hash mismatch"
wp cache flush || true

echo "==> Verify provider class can register in a loaded WordPress process"
provider_check="$(wp eval 'zeus_register_cabinet_color_sitemap_provider(); $providers = wp_get_sitemap_providers(); echo isset( $providers["cabinetcolors"] ) ? "yes" : "no";' 2>/dev/null || true)"
[[ "$provider_check" == "yes" ]] || die "cabinetcolors provider could not be registered when invoked directly inside WordPress"
echo "PASS: cabinetcolors provider registration function works"

# Do not use plain wp eval to prove the init hook fired: WP-CLI lifecycle timing can
# differ from a normal front-end request. The HTTP checks below are authoritative
# for the real web request path and verify both registration timing and routing.
ARMED=0
trap - ERR

echo "==> Live sitemap verification"
CHECK="zeus_sitemap_fix_check=$STAMP"
ROOT_MAP="https://zeuscabinetsflorida.com/wp-sitemap.xml"
COLOR_MAP="https://zeuscabinetsflorida.com/wp-sitemap-cabinetcolors-1.xml"
FAILS=0

fetch(){
  url="$1"
  out="$2"
  code="$(curl -sS -L -o "$out" -w '%{http_code}' --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  printf '%s' "$code"
}

root_body="$TMP/root-sitemap.xml"
color_body="$TMP/color-sitemap.xml"
root_code="$(fetch "$ROOT_MAP" "$root_body")"
color_code="$(fetch "$COLOR_MAP" "$color_body")"

if [[ "$root_code" == "200" ]]; then
  echo "PASS: Root sitemap loads (HTTP 200)"
else
  echo "WARN: Root sitemap expected 200 got ${root_code:-none}"
  FAILS=$((FAILS+1))
fi

if [[ "$root_code" == "200" ]] && grep -Fq "wp-sitemap-cabinetcolors-1.xml" "$root_body"; then
  echo "PASS: Root sitemap lists cabinet-color sitemap"
else
  echo "WARN: Root sitemap does not list cabinet-color sitemap"
  FAILS=$((FAILS+1))
fi

if [[ "$color_code" == "200" ]]; then
  echo "PASS: Cabinet-color sitemap loads (HTTP 200)"
else
  echo "WARN: Cabinet-color sitemap expected 200 got ${color_code:-none}"
  FAILS=$((FAILS+1))
fi

if [[ "$color_code" == "200" ]]; then
  for slug in white pearl fawn gray slate midnight; do
    url="https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/"
    if grep -Fq "$url" "$color_body"; then
      echo "PASS: Sitemap contains Brooklyn ${slug}"
    else
      echo "WARN: Sitemap missing Brooklyn ${slug}"
      FAILS=$((FAILS+1))
    fi
  done

  if grep -Fq "/cabinet-styles/shaker/white/" "$color_body"; then
    echo "WARN: Unpublished Shaker/White unexpectedly present in sitemap"
    FAILS=$((FAILS+1))
  else
    echo "PASS: Unpublished Shaker/White absent from sitemap"
  fi
else
  echo "WARN: Skipping sitemap-content assertions because cabinet-color sitemap did not return 200"
fi

for slug in white pearl fawn gray slate midnight; do
  url="https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/"
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" == "200" ]]; then echo "PASS: Brooklyn ${slug} remains live"; else echo "WARN: Brooklyn ${slug} expected 200 got ${code:-none}"; FAILS=$((FAILS+1)); fi
done

bad_code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "https://zeuscabinetsflorida.com/cabinet-styles/shaker/white/?${CHECK}" 2>/dev/null || true)"
if [[ "$bad_code" == "404" ]]; then
  echo "PASS: Unpublished Shaker/White remains 404"
else
  echo "WARN: Unpublished Shaker/White expected 404 got ${bad_code:-none}"
  FAILS=$((FAILS+1))
fi

echo "Source: $SOURCE_REF"
echo "Baseline: $BASELINE_REF"
echo "Backup: $BACKUP"

if (( FAILS > 0 )); then
  echo "$FAILS live check(s) failed."
  echo "DEPLOYED BUT NOT VERIFIED"
  echo "Do not re-run this script. Review output first."
  exit 2
fi

echo "All live sitemap checks passed."
