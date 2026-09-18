#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="e1b3b5f601c6b0cf5159583aac540a9d407cc8a6"
BASELINE_REF="2a7f46f7c9394bdd84febe5c4b78520497b76ee1"
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
printf 'source=%s
baseline=%s
file=%s
' "$SOURCE_REF" "$BASELINE_REF" "$DEST_REL" > "$BACKUP/manifest.txt"

cat > "$BACKUP/ROLLBACK.sh" <<ROLLBACK_EOF
#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$ROOT"
BACKUP="$BACKUP"
DEST_REL="$DEST_REL"
cp -p "$BACKUP/$DEST_REL" "$ROOT/$DEST_REL"
cd "$ROOT"
wp cache flush || true
echo "Rollback complete."
ROLLBACK_EOF
chmod +x "$BACKUP/ROLLBACK.sh"
echo "Backup: $BACKUP"
echo "Rollback: $BACKUP/ROLLBACK.sh"

ARMED=1

echo "==> Install"
cp "$TMP/target.php" "$ROOT/$DEST_REL"
php -l "$ROOT/$DEST_REL" >/dev/null || die "Production PHP lint failed"
installed_hash="$(sha256sum "$ROOT/$DEST_REL" | awk '{print $1}')"
[[ "$installed_hash" == "$target_hash" ]] || die "Installed hash mismatch"
wp cache flush || true

ARMED=0
trap - ERR

echo "==> Live sitemap verification"
CHECK="zeus_sitemap_check=$STAMP"
ROOT_MAP="https://zeuscabinetsflorida.com/wp-sitemap.xml"
COLOR_MAP="https://zeuscabinetsflorida.com/wp-sitemap-cabinetcolors-1.xml"
FAILS=0

status(){
  url="$1"; expected="$2"; label="$3"
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" == "$expected" ]]; then echo "PASS: $label (HTTP $code)"; else echo "WARN: $label expected $expected got ${code:-none}"; FAILS=$((FAILS+1)); fi
}

contains(){
  url="$1"; needle="$2"; label="$3"
  body="$(curl -fsSL --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ "$body" == *"$needle"* ]]; then echo "PASS: $label"; else echo "WARN: $label"; FAILS=$((FAILS+1)); fi
}

absent(){
  url="$1"; needle="$2"; label="$3"
  body="$(curl -fsSL --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ -n "$body" && "$body" != *"$needle"* ]]; then echo "PASS: $label"; else echo "WARN: $label"; FAILS=$((FAILS+1)); fi
}

status "$ROOT_MAP" 200 "Root sitemap loads"
contains "$ROOT_MAP" "wp-sitemap-cabinetcolors-1.xml" "Root sitemap lists cabinet-color sitemap"
status "$COLOR_MAP" 200 "Cabinet-color sitemap loads"

for slug in white pearl fawn gray slate midnight; do
  url="https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/"
  contains "$COLOR_MAP" "$url" "Sitemap contains Brooklyn ${slug}"
  status "$url" 200 "Brooklyn ${slug} remains live"
done

absent "$COLOR_MAP" "/cabinet-styles/shaker/white/" "Unpublished Shaker/White absent from sitemap"
status "https://zeuscabinetsflorida.com/cabinet-styles/shaker/white/" 404 "Unpublished Shaker/White remains 404"

echo "Source: $SOURCE_REF"
echo "Baseline: $BASELINE_REF"
echo "Backup: $BACKUP"

if (( FAILS > 0 )); then
  echo "$FAILS live check(s) failed."
  echo "DEPLOYED BUT NOT VERIFIED"
  echo "Do not re-run this script. Review cache/network output first."
  exit 2
fi

echo "All live sitemap checks passed."
