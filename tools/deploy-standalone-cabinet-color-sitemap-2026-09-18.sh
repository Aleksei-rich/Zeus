#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="a8707b54005623f989353b82a619698dc235220a"
BASELINE_REF="fe9d7cca42dc63fbb0f57e5b38c0d31f24af4d9d"

PLUGIN_REPO="plugins/zeus-core/inc/cabinet-colors.php"
PLUGIN_DEST="wp-content/plugins/zeus-core/inc/cabinet-colors.php"

SITEMAP_REPO="content/sitemaps/cabinet-colors-sitemap.xml"
SITEMAP_DEST="cabinet-colors-sitemap.xml"

RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}"
RAW_BASELINE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${BASELINE_REF}"

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"
ARMED=0
IN_ROLLBACK=0

cleanup(){ rm -rf "$TMP"; }
trap cleanup EXIT

restore_all(){
  if [[ -f "$BACKUP/$PLUGIN_DEST" ]]; then
    cp -p "$BACKUP/$PLUGIN_DEST" "$ROOT/$PLUGIN_DEST"
  fi
  if [[ -f "$BACKUP/$SITEMAP_DEST" ]]; then
    cp -p "$BACKUP/$SITEMAP_DEST" "$ROOT/$SITEMAP_DEST"
  else
    rm -f "$ROOT/$SITEMAP_DEST"
  fi
}

rollback(){
  [[ "$IN_ROLLBACK" == "1" ]] && return
  IN_ROLLBACK=1
  trap - ERR
  echo "Rolling back standalone sitemap deployment..." >&2
  restore_all
  cd "$ROOT"
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
[[ -f "$ROOT/$PLUGIN_DEST" ]] || die "Production plugin file missing"
command -v wp >/dev/null 2>&1 || die "WP-CLI unavailable"
command -v php >/dev/null 2>&1 || die "PHP CLI unavailable"
command -v curl >/dev/null 2>&1 || die "curl unavailable"
command -v sha256sum >/dev/null 2>&1 || die "sha256sum unavailable"

cd "$ROOT"
wp plugin is-active zeus-core >/dev/null 2>&1 || die "ZEUS Core is not active"

echo "==> Verify current production baseline"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASELINE/$PLUGIN_REPO" -o "$TMP/baseline-plugin.php" || die "Could not fetch baseline plugin file"
php -l "$TMP/baseline-plugin.php" >/dev/null || die "Baseline plugin PHP lint failed"

live_hash="$(sha256sum "$ROOT/$PLUGIN_DEST" | awk '{print $1}')"
baseline_hash="$(sha256sum "$TMP/baseline-plugin.php" | awk '{print $1}')"
[[ "$live_hash" == "$baseline_hash" ]] || die "Production plugin file does not match expected baseline $BASELINE_REF. Nothing changed."

if [[ -e "$ROOT/$SITEMAP_DEST" ]]; then
  die "$SITEMAP_DEST already exists on production. Stopping before changes."
fi

echo "Baseline confirmed."

echo "==> Download reviewed target files"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$PLUGIN_REPO" -o "$TMP/plugin.php" || die "Could not fetch target plugin file"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$SITEMAP_REPO" -o "$TMP/sitemap.xml" || die "Could not fetch sitemap file"

[[ -s "$TMP/plugin.php" ]] || die "Target plugin download is empty"
[[ -s "$TMP/sitemap.xml" ]] || die "Sitemap download is empty"
php -l "$TMP/plugin.php" >/dev/null || die "Target plugin PHP lint failed"

for slug in white pearl fawn gray slate midnight; do
  grep -Fq "https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/" "$TMP/sitemap.xml"     || die "Downloaded sitemap is missing Brooklyn ${slug}"
done

if grep -Fq "/cabinet-styles/shaker/white/" "$TMP/sitemap.xml"; then
  die "Downloaded sitemap unexpectedly contains unpublished Shaker/White"
fi

echo "Target files validated."

echo "==> Backup"
mkdir -p "$BACKUP/$(dirname "$PLUGIN_DEST")"
cp -p "$ROOT/$PLUGIN_DEST" "$BACKUP/$PLUGIN_DEST"
printf 'source_ref=%s\nbaseline_ref=%s\nplugin=%s\nsitemap=%s\ncreated=%s\n'   "$SOURCE_REF" "$BASELINE_REF" "$PLUGIN_DEST" "$SITEMAP_DEST" "$(date -Is)" > "$BACKUP/manifest.txt"

cat > "$BACKUP/ROLLBACK.sh" <<ROLLBACK_EOF
#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$ROOT"
BACKUP="$BACKUP"
PLUGIN_DEST="$PLUGIN_DEST"
SITEMAP_DEST="$SITEMAP_DEST"
cp -p "\$BACKUP/\$PLUGIN_DEST" "\$ROOT/\$PLUGIN_DEST"
rm -f "\$ROOT/\$SITEMAP_DEST"
cd "\$ROOT"
wp cache flush || true
echo "Rollback complete."
ROLLBACK_EOF
chmod +x "$BACKUP/ROLLBACK.sh"

echo "Backup: $BACKUP"
echo "Rollback: $BACKUP/ROLLBACK.sh"

ARMED=1

echo "==> Install stable plugin file and standalone sitemap"
cp "$TMP/plugin.php" "$ROOT/$PLUGIN_DEST"
cp "$TMP/sitemap.xml" "$ROOT/$SITEMAP_DEST"

php -l "$ROOT/$PLUGIN_DEST" >/dev/null || die "Production plugin PHP lint failed after copy"

installed_plugin_hash="$(sha256sum "$ROOT/$PLUGIN_DEST" | awk '{print $1}')"
target_plugin_hash="$(sha256sum "$TMP/plugin.php" | awk '{print $1}')"
[[ "$installed_plugin_hash" == "$target_plugin_hash" ]] || die "Installed plugin hash mismatch"

installed_map_hash="$(sha256sum "$ROOT/$SITEMAP_DEST" | awk '{print $1}')"
target_map_hash="$(sha256sum "$TMP/sitemap.xml" | awk '{print $1}')"
[[ "$installed_map_hash" == "$target_map_hash" ]] || die "Installed sitemap hash mismatch"

wp cache flush || true

ARMED=0
trap - ERR

echo "==> Live verification"
CHECK="zeus_static_sitemap_check=$STAMP"
MAP_URL="https://zeuscabinetsflorida.com/$SITEMAP_DEST"
FAILS=0
MAP_BODY="$TMP/live-map.xml"

map_code="$(curl -sS -L -o "$MAP_BODY" -w '%{http_code}' --retry 2 --connect-timeout 15 "${MAP_URL}?${CHECK}" 2>/dev/null || true)"
if [[ "$map_code" == "200" ]]; then
  echo "PASS: Standalone cabinet color sitemap loads (HTTP 200)"
else
  echo "WARN: Standalone cabinet color sitemap expected 200 got ${map_code:-none}"
  FAILS=$((FAILS+1))
fi

if [[ "$map_code" == "200" ]]; then
  for slug in white pearl fawn gray slate midnight; do
    if grep -Fq "https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/" "$MAP_BODY"; then
      echo "PASS: Sitemap contains Brooklyn ${slug}"
    else
      echo "WARN: Sitemap missing Brooklyn ${slug}"
      FAILS=$((FAILS+1))
    fi
  done

  if grep -Fq "/cabinet-styles/shaker/white/" "$MAP_BODY"; then
    echo "WARN: Unpublished Shaker/White unexpectedly present in sitemap"
    FAILS=$((FAILS+1))
  else
    echo "PASS: Unpublished Shaker/White absent from sitemap"
  fi
fi

for slug in white pearl fawn gray slate midnight; do
  url="https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/"
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" == "200" ]]; then
    echo "PASS: Brooklyn ${slug} remains live"
  else
    echo "WARN: Brooklyn ${slug} expected 200 got ${code:-none}"
    FAILS=$((FAILS+1))
  fi
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
echo "Submit this sitemap in Google Search Console:"
echo "$MAP_URL"

if (( FAILS > 0 )); then
  echo "$FAILS live check(s) failed."
  echo "DEPLOYED BUT NOT VERIFIED"
  echo "Do not re-run this script. Review output first."
  exit 2
fi

echo "All standalone sitemap checks passed."
