#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="79aa2f477bc4ae8c75e54bc8f80a30970cd184e8"
BASELINE_REF="a8707b54005623f989353b82a619698dc235220a"

PLUGIN_REPO="plugins/zeus-core/inc/cabinet-colors.php"
PLUGIN_DEST="wp-content/plugins/zeus-core/inc/cabinet-colors.php"

TEMPLATE_REPO="theme/zeus/single-cabinet-color.php"
TEMPLATE_DEST="wp-content/themes/zeus/single-cabinet-color.php"

SITEMAP_REPO="content/sitemaps/cabinet-colors-sitemap.xml"
SITEMAP_DEST="cabinet-colors-sitemap.xml"

SEO_DEST="wp-content/themes/zeus/inc/seo.php"

RAW_TARGET="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}"
RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${BASELINE_REF}"

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"
ARMED=0
IN_ROLLBACK=0

cleanup(){ rm -rf "$TMP"; }
trap cleanup EXIT

purge_cache(){
  cd "$ROOT"
  wp cache flush >/dev/null 2>&1 || true
  if wp help litespeed-purge >/dev/null 2>&1; then
    wp litespeed-purge all >/dev/null 2>&1 || true
  fi
}

restore_all(){
  cp -p "$BACKUP/$PLUGIN_DEST" "$ROOT/$PLUGIN_DEST"
  cp -p "$BACKUP/$TEMPLATE_DEST" "$ROOT/$TEMPLATE_DEST"
  cp -p "$BACKUP/$SITEMAP_DEST" "$ROOT/$SITEMAP_DEST"
}

rollback(){
  [[ "$IN_ROLLBACK" == "1" ]] && return
  IN_ROLLBACK=1
  trap - ERR
  echo "Rolling back Shaker/Oslo color-page deployment..." >&2
  restore_all
  purge_cache
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
for f in "$PLUGIN_DEST" "$TEMPLATE_DEST" "$SITEMAP_DEST" "$SEO_DEST"; do
  [[ -f "$ROOT/$f" ]] || die "Production file missing: $f"
done
command -v wp >/dev/null 2>&1 || die "WP-CLI unavailable"
command -v php >/dev/null 2>&1 || die "PHP CLI unavailable"
command -v curl >/dev/null 2>&1 || die "curl unavailable"
command -v sha256sum >/dev/null 2>&1 || die "sha256sum unavailable"
command -v grep >/dev/null 2>&1 || die "grep unavailable"

cd "$ROOT"
wp plugin is-active zeus-core >/dev/null 2>&1 || die "ZEUS Core is not active"

grep -Fq "zeus_get_current_cabinet_color_content" "$ROOT/$SEO_DEST"   || die "Production SEO file lacks cabinet-color SEO support"
grep -Fq "zeus_filter_canonical_url" "$ROOT/$SEO_DEST"   || die "Production SEO file lacks cabinet-color canonical support"
echo "PASS: production SEO plumbing supports generic color pages"

echo "==> Verify exact current production baseline"
for spec in   "$PLUGIN_REPO|$PLUGIN_DEST|plugin.php"   "$TEMPLATE_REPO|$TEMPLATE_DEST|template.php"   "$SITEMAP_REPO|$SITEMAP_DEST|sitemap.xml"
do
  IFS='|' read -r repo_path dest_path tmp_name <<< "$spec"
  curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$repo_path" -o "$TMP/base-$tmp_name"     || die "Could not fetch baseline: $repo_path"
  [[ -s "$TMP/base-$tmp_name" ]] || die "Baseline download empty: $repo_path"
  live_hash="$(sha256sum "$ROOT/$dest_path" | awk '{print $1}')"
  base_hash="$(sha256sum "$TMP/base-$tmp_name" | awk '{print $1}')"
  [[ "$live_hash" == "$base_hash" ]]     || die "Production $dest_path differs from expected baseline $BASELINE_REF. Nothing changed."
done
php -l "$TMP/base-plugin.php" >/dev/null || die "Baseline plugin PHP lint failed"
php -l "$TMP/base-template.php" >/dev/null || die "Baseline template PHP lint failed"
echo "PASS: production matches expected baseline"

echo "==> Verify collections, finish taxonomy, and exact media IDs"
wp eval '
$collections = array( "shaker", "oslo" );
foreach ( $collections as $slug ) {
    $p = get_page_by_path( $slug, OBJECT, "cabinet_collection" );
    if ( ! $p || "publish" !== get_post_status( $p ) ) {
        fwrite( STDERR, "Missing published cabinet collection: {$slug}\n" );
        exit( 1 );
    }
}
$finish_slugs = array( "white", "sand", "kodiak", "moss", "oak", "walnut" );
foreach ( $finish_slugs as $slug ) {
    $t = get_term_by( "slug", $slug, "finish" );
    if ( ! $t || is_wp_error( $t ) ) {
        fwrite( STDERR, "Missing finish term: {$slug}\n" );
        exit( 1 );
    }
}
$media = array(
    123 => "Shaker White Kitchen",
    124 => "Shaker White Kitchen 2",
    125 => "Shaker Sand Kitchen",
    126 => "Shaker Sand Kitchen 2",
    127 => "Shaker Kodiak Kitchen",
    128 => "Shaker Kodiak Bathroom",
    129 => "Shaker Moss Kitchen",
    130 => "Shaker Moss Bathroom",
    131 => "Oslo White Kitchen",
    132 => "Oslo White Bathroom",
    133 => "Oslo Oak Kitchen",
    134 => "Oslo Oak Bathroom",
    136 => "Oslo Walnut Kitchen 2",
    137 => "Oslo Walnut Bathroom",
    138 => "Oslo Walnut Bar",
);
foreach ( $media as $id => $expected_title ) {
    if ( "attachment" !== get_post_type( $id ) ) {
        fwrite( STDERR, "Media ID {$id} is not an attachment\n" );
        exit( 1 );
    }
    if ( $expected_title !== get_the_title( $id ) ) {
        fwrite( STDERR, "Media ID {$id} title mismatch: " . get_the_title( $id ) . "\n" );
        exit( 1 );
    }
    $file = get_attached_file( $id );
    if ( ! $file || ! file_exists( $file ) || ! wp_get_attachment_url( $id ) ) {
        fwrite( STDERR, "Media ID {$id} file/URL missing\n" );
        exit( 1 );
    }
}
echo "ok";
' | grep -qx "ok" || die "Collection/finish/media preflight failed"
echo "PASS: Shaker/Oslo source data and media verified"

echo "==> Verify new child URLs are not already published"
for path in   "/cabinet-styles/shaker/white/"   "/cabinet-styles/shaker/sand/"   "/cabinet-styles/shaker/kodiak/"   "/cabinet-styles/shaker/moss/"   "/cabinet-styles/oslo/white/"   "/cabinet-styles/oslo/oak/"   "/cabinet-styles/oslo/walnut/"
do
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "https://zeuscabinetsflorida.com${path}?preflight=${STAMP}" 2>/dev/null || true)"
  [[ "$code" == "404" ]] || die "Expected pre-deploy 404 for $path, got ${code:-none}"
done
echo "PASS: all 7 new child URLs are currently unpublished"

echo "==> Download and validate target"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_TARGET/$PLUGIN_REPO" -o "$TMP/target-plugin.php"   || die "Could not fetch target plugin"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_TARGET/$TEMPLATE_REPO" -o "$TMP/target-template.php"   || die "Could not fetch target template"
curl -fsSL --retry 3 --connect-timeout 15 "$RAW_TARGET/$SITEMAP_REPO" -o "$TMP/target-sitemap.xml"   || die "Could not fetch target sitemap"

php -l "$TMP/target-plugin.php" >/dev/null || die "Target plugin PHP lint failed"
php -l "$TMP/target-template.php" >/dev/null || die "Target template PHP lint failed"

for path in   "/cabinet-styles/brooklyn/white/"   "/cabinet-styles/brooklyn/pearl/"   "/cabinet-styles/brooklyn/fawn/"   "/cabinet-styles/brooklyn/gray/"   "/cabinet-styles/brooklyn/slate/"   "/cabinet-styles/brooklyn/midnight/"   "/cabinet-styles/shaker/white/"   "/cabinet-styles/shaker/sand/"   "/cabinet-styles/shaker/kodiak/"   "/cabinet-styles/shaker/moss/"   "/cabinet-styles/oslo/white/"   "/cabinet-styles/oslo/oak/"   "/cabinet-styles/oslo/walnut/"
do
  grep -Fq "https://zeuscabinetsflorida.com${path}" "$TMP/target-sitemap.xml"     || die "Target sitemap missing: $path"
done
if grep -Fq "/cabinet-styles/euro-flat-panel/" "$TMP/target-sitemap.xml"; then
  die "Target sitemap unexpectedly contains Euro child URL"
fi
echo "PASS: target PHP and 13-URL sitemap validated"

echo "==> Backup"
mkdir -p "$BACKUP/$(dirname "$PLUGIN_DEST")"
mkdir -p "$BACKUP/$(dirname "$TEMPLATE_DEST")"
cp -p "$ROOT/$PLUGIN_DEST" "$BACKUP/$PLUGIN_DEST"
cp -p "$ROOT/$TEMPLATE_DEST" "$BACKUP/$TEMPLATE_DEST"
cp -p "$ROOT/$SITEMAP_DEST" "$BACKUP/$SITEMAP_DEST"
printf 'source_ref=%s\nbaseline_ref=%s\nplugin=%s\ntemplate=%s\nsitemap=%s\ncreated=%s\n'   "$SOURCE_REF" "$BASELINE_REF" "$PLUGIN_DEST" "$TEMPLATE_DEST" "$SITEMAP_DEST" "$(date -Is)" > "$BACKUP/manifest.txt"

cat > "$BACKUP/ROLLBACK.sh" <<ROLLBACK_EOF
#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$ROOT"
BACKUP="$BACKUP"
PLUGIN_DEST="$PLUGIN_DEST"
TEMPLATE_DEST="$TEMPLATE_DEST"
SITEMAP_DEST="$SITEMAP_DEST"
cp -p "\$BACKUP/\$PLUGIN_DEST" "\$ROOT/\$PLUGIN_DEST"
cp -p "\$BACKUP/\$TEMPLATE_DEST" "\$ROOT/\$TEMPLATE_DEST"
cp -p "\$BACKUP/\$SITEMAP_DEST" "\$ROOT/\$SITEMAP_DEST"
cd "\$ROOT"
wp cache flush || true
if wp help litespeed-purge >/dev/null 2>&1; then wp litespeed-purge all || true; fi
echo "Rollback complete."
ROLLBACK_EOF
chmod +x "$BACKUP/ROLLBACK.sh"
echo "Backup: $BACKUP"
echo "Rollback: $BACKUP/ROLLBACK.sh"

ARMED=1

echo "==> Install"
cp "$TMP/target-plugin.php" "$ROOT/$PLUGIN_DEST"
cp "$TMP/target-template.php" "$ROOT/$TEMPLATE_DEST"
cp "$TMP/target-sitemap.xml" "$ROOT/$SITEMAP_DEST"

php -l "$ROOT/$PLUGIN_DEST" >/dev/null || die "Installed plugin PHP lint failed"
php -l "$ROOT/$TEMPLATE_DEST" >/dev/null || die "Installed template PHP lint failed"

for spec in   "$PLUGIN_DEST|target-plugin.php"   "$TEMPLATE_DEST|target-template.php"   "$SITEMAP_DEST|target-sitemap.xml"
do
  IFS='|' read -r dest_path tmp_name <<< "$spec"
  installed_hash="$(sha256sum "$ROOT/$dest_path" | awk '{print $1}')"
  target_hash="$(sha256sum "$TMP/$tmp_name" | awk '{print $1}')"
  [[ "$installed_hash" == "$target_hash" ]] || die "Installed hash mismatch: $dest_path"
done

purge_cache
echo "PASS: files installed and caches purged"

ARMED=0
trap - ERR

echo "==> Live verification"
CHECK="zeus_color_expand_check=$STAMP"
FAILS=0

check_page(){
  path="$1"; expected_h1="$2"
  url="https://zeuscabinetsflorida.com$path"
  out="$TMP/page-$(echo "$path" | tr '/-' '__').html"
  code="$(curl -sS -L -o "$out" -w '%{http_code}' --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" != "200" ]]; then
    echo "WARN: $path expected HTTP 200 got ${code:-none}"
    FAILS=$((FAILS+1))
    return
  fi
  echo "PASS: $path HTTP 200"
  if grep -Fq "<h1>$expected_h1</h1>" "$out"; then
    echo "PASS: H1 $expected_h1"
  else
    echo "WARN: H1 missing for $path"
    FAILS=$((FAILS+1))
  fi
  if grep -Fq "rel=\"canonical\" href=\"$url\"" "$out"; then
    echo "PASS: self canonical $path"
  else
    echo "WARN: self canonical missing for $path"
    FAILS=$((FAILS+1))
  fi
  if grep -Fq "Request Free Consultation" "$out"; then
    echo "PASS: CTA present $path"
  else
    echo "WARN: CTA missing $path"
    FAILS=$((FAILS+1))
  fi
}

check_page "/cabinet-styles/shaker/white/"  "Shaker White Kitchen Cabinets"
check_page "/cabinet-styles/shaker/sand/"   "Shaker Sand Kitchen Cabinets"
check_page "/cabinet-styles/shaker/kodiak/" "Shaker Kodiak Kitchen &amp; Bath Cabinets"
check_page "/cabinet-styles/shaker/moss/"   "Shaker Moss Kitchen &amp; Bath Cabinets"
check_page "/cabinet-styles/oslo/white/"     "Oslo White Slim Shaker Cabinets"
check_page "/cabinet-styles/oslo/oak/"       "Oslo Oak Slim Shaker Cabinets"
check_page "/cabinet-styles/oslo/walnut/"    "OSLO Classic Walnut Slim Shaker Cabinets"

for slug in white sand kodiak moss; do
  parent_body="$TMP/shaker-parent.html"
  curl -fsSL --retry 2 --connect-timeout 15 "https://zeuscabinetsflorida.com/cabinet-styles/shaker/?${CHECK}" -o "$parent_body" || true
  if grep -Fq "/cabinet-styles/shaker/$slug/" "$parent_body"; then
    echo "PASS: Shaker parent links $slug"
  else
    echo "WARN: Shaker parent missing $slug link"
    FAILS=$((FAILS+1))
  fi
done

for slug in white oak walnut; do
  parent_body="$TMP/oslo-parent.html"
  curl -fsSL --retry 2 --connect-timeout 15 "https://zeuscabinetsflorida.com/cabinet-styles/oslo/?${CHECK}" -o "$parent_body" || true
  if grep -Fq "/cabinet-styles/oslo/$slug/" "$parent_body"; then
    echo "PASS: Oslo parent links $slug"
  else
    echo "WARN: Oslo parent missing $slug link"
    FAILS=$((FAILS+1))
  fi
done

echo "==> Regression checks"
for slug in white pearl fawn gray slate midnight; do
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/$slug/?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" == "200" ]]; then
    echo "PASS: Brooklyn $slug remains live"
  else
    echo "WARN: Brooklyn $slug expected 200 got ${code:-none}"
    FAILS=$((FAILS+1))
  fi
done

for path in   "/cabinet-styles/shaker/not-a-real-color/"   "/cabinet-styles/oslo/not-a-real-color/"   "/cabinet-styles/euro-flat-panel/white/"
do
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "https://zeuscabinetsflorida.com${path}?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" == "404" ]]; then
    echo "PASS: unpublished/invalid URL remains 404: $path"
  else
    echo "WARN: $path expected 404 got ${code:-none}"
    FAILS=$((FAILS+1))
  fi
done

MAP_URL="https://zeuscabinetsflorida.com/$SITEMAP_DEST"
MAP_BODY="$TMP/live-sitemap.xml"
map_code="$(curl -sS -L -o "$MAP_BODY" -w '%{http_code}' --retry 2 --connect-timeout 15 "${MAP_URL}?${CHECK}" 2>/dev/null || true)"
if [[ "$map_code" == "200" ]]; then
  echo "PASS: cabinet color sitemap HTTP 200"
else
  echo "WARN: cabinet color sitemap expected 200 got ${map_code:-none}"
  FAILS=$((FAILS+1))
fi

if [[ "$map_code" == "200" ]]; then
  for path in     "/cabinet-styles/brooklyn/white/"     "/cabinet-styles/brooklyn/pearl/"     "/cabinet-styles/brooklyn/fawn/"     "/cabinet-styles/brooklyn/gray/"     "/cabinet-styles/brooklyn/slate/"     "/cabinet-styles/brooklyn/midnight/"     "/cabinet-styles/shaker/white/"     "/cabinet-styles/shaker/sand/"     "/cabinet-styles/shaker/kodiak/"     "/cabinet-styles/shaker/moss/"     "/cabinet-styles/oslo/white/"     "/cabinet-styles/oslo/oak/"     "/cabinet-styles/oslo/walnut/"
  do
    if grep -Fq "https://zeuscabinetsflorida.com$path" "$MAP_BODY"; then
      echo "PASS: sitemap contains $path"
    else
      echo "WARN: sitemap missing $path"
      FAILS=$((FAILS+1))
    fi
  done
  if grep -Fq "/cabinet-styles/euro-flat-panel/" "$MAP_BODY"; then
    echo "WARN: sitemap unexpectedly contains Euro child URL"
    FAILS=$((FAILS+1))
  else
    echo "PASS: sitemap has no unverified Euro child URLs"
  fi
fi

echo "Source: $SOURCE_REF"
echo "Baseline: $BASELINE_REF"
echo "Backup: $BACKUP"
echo "Google sitemap remains: $MAP_URL"

if (( FAILS > 0 )); then
  echo "$FAILS live check(s) failed."
  echo "DEPLOYED BUT NOT VERIFIED"
  echo "Do not re-run this script. Review output first."
  exit 2
fi

echo "All Shaker/Oslo color-page checks passed."
