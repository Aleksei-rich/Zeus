#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS controlled production deploy — 2026-09-11
# Deploys ONLY the reviewed SEO/theme hotfixes and consultation-form files.
# Source is pinned to one reviewed commit so later branch changes cannot
# silently alter what reaches production.

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="5d06005055444f1b44565bf4a38ac10c8276f454"
RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"

REPO_FILES=(
  "theme/zeus/functions.php"
  "theme/zeus/inc/seo-cluster-links.php"
  "theme/zeus/inc/content-safety.php"
  "theme/zeus/page-granite.php"
  "theme/zeus/category.php"
  "theme/zeus/inc/category-seo.php"
  "theme/zeus/inc/breadcrumbs.php"
  "theme/zeus/template-parts/consultation-form.php"
  "theme/zeus/assets/js/main.js"
  "plugins/zeus-core/zeus-core.php"
  "plugins/zeus-core/inc/consultation-form.php"
  "plugins/zeus-core/inc/consultation-multiupload.php"
  "plugins/zeus-core/inc/consultation-reliability.php"
  "plugins/zeus-core/assets/js/consultation-reliability.js"
)

DEST_FILES=(
  "wp-content/themes/zeus/functions.php"
  "wp-content/themes/zeus/inc/seo-cluster-links.php"
  "wp-content/themes/zeus/inc/content-safety.php"
  "wp-content/themes/zeus/page-granite.php"
  "wp-content/themes/zeus/category.php"
  "wp-content/themes/zeus/inc/category-seo.php"
  "wp-content/themes/zeus/inc/breadcrumbs.php"
  "wp-content/themes/zeus/template-parts/consultation-form.php"
  "wp-content/themes/zeus/assets/js/main.js"
  "wp-content/plugins/zeus-core/zeus-core.php"
  "wp-content/plugins/zeus-core/inc/consultation-form.php"
  "wp-content/plugins/zeus-core/inc/consultation-multiupload.php"
  "wp-content/plugins/zeus-core/inc/consultation-reliability.php"
  "wp-content/plugins/zeus-core/assets/js/consultation-reliability.js"
)

cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

die() {
  echo "ERROR: $*" >&2
  exit 1
}

log() {
  printf '\n==> %s\n' "$*"
}

validate_source_file() {
  local path="$1"
  case "$path" in
    *.php) php -l "$path" >/dev/null ;;
    *.js)
      if command -v node >/dev/null 2>&1; then
        node --check "$path" >/dev/null
      fi
      ;;
  esac
}

log "Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found at $ROOT"
[[ -d "$ROOT/wp-content/themes/zeus" ]] || die "ZEUS theme directory not found"
[[ -d "$ROOT/wp-content/plugins/zeus-core" ]] || die "ZEUS Core plugin directory not found"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"

cd "$ROOT"
ACTIVE_THEME="$(wp theme list --status=active --field=name 2>/dev/null | head -n1 || true)"
[[ "$ACTIVE_THEME" == "zeus" ]] || die "Active theme is '$ACTIVE_THEME', expected 'zeus'. Nothing changed."
wp plugin is-active zeus-core >/dev/null 2>&1 || die "ZEUS Core is not active. Nothing changed."
echo "Active theme confirmed: $ACTIVE_THEME"
echo "ZEUS Core confirmed active"
echo "Pinned source commit: $SOURCE_REF"

log "Download and validate approved files before touching production"
for i in "${!REPO_FILES[@]}"; do
  repo="${REPO_FILES[$i]}"
  tmp="$TMP/$repo"
  mkdir -p "$(dirname "$tmp")"
  echo "Fetching $repo"
  curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$repo" -o "$tmp"
  [[ -s "$tmp" ]] || die "Downloaded file is empty: $repo"
  validate_source_file "$tmp" || die "Syntax validation failed BEFORE deploy: $repo"
done
echo "All approved source files downloaded and validated."

log "Create timestamped production backup"
mkdir -p "$BACKUP/files"
printf 'source_ref=%s\ncreated=%s\n' "$SOURCE_REF" "$(date -Is)" > "$BACKUP/manifest.txt"
for i in "${!DEST_FILES[@]}"; do
  dest_rel="${DEST_FILES[$i]}"
  target="$ROOT/$dest_rel"
  backup="$BACKUP/files/$dest_rel"
  mkdir -p "$(dirname "$backup")"
  if [[ -f "$target" ]]; then
    cp -p "$target" "$backup"
    printf 'existing %s\n' "$dest_rel" >> "$BACKUP/manifest.txt"
  else
    printf 'new %s\n' "$dest_rel" >> "$BACKUP/manifest.txt"
  fi
done

{
  echo "11 zeus_seo_title=$(wp post meta get 11 zeus_seo_title 2>/dev/null || true)"
  echo "11 zeus_seo_description=$(wp post meta get 11 zeus_seo_description 2>/dev/null || true)"
  echo "12 zeus_seo_title=$(wp post meta get 12 zeus_seo_title 2>/dev/null || true)"
  echo "15 zeus_seo_title=$(wp post meta get 15 zeus_seo_title 2>/dev/null || true)"
} > "$BACKUP/meta-before.txt"
echo "Backup: $BACKUP"

restore_files() {
  echo "Restoring deployed files from $BACKUP" >&2
  for i in "${!DEST_FILES[@]}"; do
    dest_rel="${DEST_FILES[$i]}"
    target="$ROOT/$dest_rel"
    backup="$BACKUP/files/$dest_rel"
    if [[ -f "$backup" ]]; then
      mkdir -p "$(dirname "$target")"
      cp -p "$backup" "$target"
    else
      rm -f "$target"
    fi
  done
}

log "Install approved files"
for i in "${!REPO_FILES[@]}"; do
  repo="${REPO_FILES[$i]}"
  dest_rel="${DEST_FILES[$i]}"
  target="$ROOT/$dest_rel"
  mkdir -p "$(dirname "$target")"
  cp "$TMP/$repo" "$target"
done

log "Validate exact production copies"
for dest_rel in "${DEST_FILES[@]}"; do
  target="$ROOT/$dest_rel"
  if ! validate_source_file "$target"; then
    restore_files
    die "Syntax validation failed AFTER copy: $dest_rel. Files restored."
  fi
done
echo "Production copies passed syntax validation."

log "Apply four approved SEO metadata corrections"
wp post meta update 11 zeus_seo_title 'Bathroom Cabinets Orlando, FL | Vanities | ZEUS'
wp post meta update 11 zeus_seo_description 'Bathroom cabinets and vanities in Orlando, FL, with in-stock and custom options, storage planning, countertops, delivery, and installation from ZEUS.'
wp post meta update 12 zeus_seo_title 'Countertops Orlando, FL | Quartz, Granite & More | ZEUS'
wp post meta update 15 zeus_seo_title 'Porcelain Countertops Orlando, FL | ZEUS'

log "Confirm consultation upload limits"
PUBLIC_TOTAL="$(wp eval 'echo defined("ZEUS_LEAD_PUBLIC_MAX_TOTAL_UPLOAD_BYTES") ? ZEUS_LEAD_PUBLIC_MAX_TOTAL_UPLOAD_BYTES : 0;' 2>/dev/null)"
HANDLER_TOTAL="$(wp eval 'echo defined("ZEUS_LEAD_MAX_TOTAL_UPLOAD_BYTES") ? ZEUS_LEAD_MAX_TOTAL_UPLOAD_BYTES : 0;' 2>/dev/null)"
MAIL_TOTAL="$(wp eval 'echo defined("ZEUS_LEAD_MAX_MAIL_ATTACHMENT_BYTES") ? ZEUS_LEAD_MAX_MAIL_ATTACHMENT_BYTES : 0;' 2>/dev/null)"
EXPECTED_TOTAL=$((15 * 1024 * 1024))
EXPECTED_MAIL=$((10 * 1024 * 1024))
[[ "$PUBLIC_TOTAL" == "$EXPECTED_TOTAL" ]] || { restore_files; die "Public upload gate is $PUBLIC_TOTAL bytes, expected $EXPECTED_TOTAL. Files restored."; }
[[ "$HANDLER_TOTAL" == "$EXPECTED_TOTAL" ]] || { restore_files; die "Reliable handler limit is $HANDLER_TOTAL bytes, expected $EXPECTED_TOTAL. Files restored."; }
[[ "$MAIL_TOTAL" == "$EXPECTED_MAIL" ]] || { restore_files; die "Mail attachment limit is $MAIL_TOTAL bytes, expected $EXPECTED_MAIL. Files restored."; }
echo "Consultation limits aligned: 15MB accepted total / 10MB max per file / 10MB mail-attachment threshold."

log "Verify private consultation-upload directory protection"
PRIVATE_DIR="$(wp eval 'zeus_core_setup_private_uploads_dir(); echo zeus_lead_uploads_dir();' 2>/dev/null)"
PRIVATE_URL="$(wp eval '$u=wp_upload_dir(); echo trailingslashit($u["baseurl"])."zeus-private-leads";' 2>/dev/null)"
[[ -d "$PRIVATE_DIR" ]] || die "Private lead-upload directory does not exist"
[[ -f "$PRIVATE_DIR/.htaccess" ]] || die "Private lead-upload .htaccess is missing"
[[ -f "$PRIVATE_DIR/web.config" ]] || die "Private lead-upload web.config is missing"
[[ -f "$PRIVATE_DIR/index.php" ]] || die "Private lead-upload index.php is missing"
PROBE="zeus-access-probe-${STAMP}.txt"
printf 'private-probe-%s\n' "$STAMP" > "$PRIVATE_DIR/$PROBE"
PROBE_CODE="$(curl -sSL -o /dev/null -w '%{http_code}' --max-time 15 "$PRIVATE_URL/$PROBE?probe=$STAMP" || true)"
rm -f "$PRIVATE_DIR/$PROBE"
if [[ "$PROBE_CODE" == "403" || "$PROBE_CODE" == "404" ]]; then
  echo "PASS: private upload probe blocked from public web (HTTP $PROBE_CODE)."
else
  echo "SECURITY WARNING: private upload probe returned HTTP ${PROBE_CODE:-unknown}. Do not upload customer files until host-level access is fixed." >&2
fi

log "Flush WordPress object cache"
wp cache flush || true

log "Verify metadata"
printf 'Bathroom title: '; wp post meta get 11 zeus_seo_title
printf 'Bathroom meta: '; wp post meta get 11 zeus_seo_description
printf 'Countertops title: '; wp post meta get 12 zeus_seo_title
printf 'Porcelain title: '; wp post meta get 15 zeus_seo_title

log "Live verification using cache-busting URLs"
CHECK="zeus_deploy_check=$STAMP"
FAILS=0
CHECK_SEQ=0
check_contains() {
  local url="$1"
  local needle="$2"
  local label="$3"
  local body
  CHECK_SEQ=$((CHECK_SEQ+1))
  body="$TMP/live-check-${CHECK_SEQ}.html"
  if curl -fsSL --retry 2 "${url}?${CHECK}" -o "$body" && grep -Fq "$needle" "$body"; then
    echo "PASS: $label"
  else
    echo "WARN: $label not visible yet"
    FAILS=$((FAILS+1))
  fi
}

check_contains 'https://zeuscabinetsflorida.com/' 'Popular Cabinet Styles &amp; Finishes' 'homepage corrected collection heading'
check_contains 'https://zeuscabinetsflorida.com/' 'Maximum 5 files and 15MB total for all attachments.' 'homepage consultation 15MB total copy'
check_contains 'https://zeuscabinetsflorida.com/consultation/' 'Maximum 5 files and 15MB total for all attachments.' 'consultation page 15MB total copy'
check_contains 'https://zeuscabinetsflorida.com/cabinets/kitchen-cabinets/' 'Kitchen Planning Guides' 'Kitchen Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/cabinets/bathroom-cabinets-vanities/' 'Bathroom Vanity Planning Guides' 'Bathroom Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/custom-spaces/home-office/' 'Home Office Planning Guides' 'Home Office Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/custom-spaces/closets/' 'Custom Closet Planning Guides' 'Closets Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/countertops/granite/' 'how-to-care-for-granite-countertops' 'Granite care-guide link'
check_contains 'https://zeuscabinetsflorida.com/category/kitchen-cabinet-guides/' 'Kitchen &amp; Cabinet Guides' 'Kitchen & Cabinet category H1'
check_contains 'https://zeuscabinetsflorida.com/category/countertop-guides/' 'Countertop Guides' 'Countertop category H1'

printf '\nDeployment complete.\nBackup: %s\nPinned source: %s\n' "$BACKUP" "$SOURCE_REF"
if (( FAILS > 0 )); then
  echo "$FAILS content verification check(s) did not render immediately. PHP/JS files are valid; purge any separate host/page cache in cPanel and re-check before declaring the deploy complete."
else
  echo "All cache-busting content checks passed."
fi
