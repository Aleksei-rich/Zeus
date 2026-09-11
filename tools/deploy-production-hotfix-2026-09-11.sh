#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS controlled production hotfix deploy — 2026-09-11
# Deploys ONLY the approved theme files + four SEO metadata corrections.
# Source is pinned to a reviewed rebuild/v2 commit so later branch changes
# cannot silently alter what reaches production.

ROOT="/home/zeusiwpo/public_html"
THEME="$ROOT/wp-content/themes/zeus"
SOURCE_REF="7b4e19926b9fa53454b22f2a62801ecffba910c2"
RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}/theme/zeus"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"

FILES=(
  "functions.php"
  "inc/seo-cluster-links.php"
  "inc/content-safety.php"
  "page-granite.php"
  "category.php"
  "inc/category-seo.php"
  "inc/breadcrumbs.php"
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

log "Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found at $ROOT"
[[ -d "$THEME" ]] || die "ZEUS theme directory not found at $THEME"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"

cd "$ROOT"
ACTIVE_THEME="$(wp theme list --status=active --field=name 2>/dev/null | head -n1 || true)"
[[ "$ACTIVE_THEME" == "zeus" ]] || die "Active theme is '$ACTIVE_THEME', expected 'zeus'. Nothing changed."
echo "Active theme confirmed: $ACTIVE_THEME"
echo "Pinned source commit: $SOURCE_REF"

log "Download approved files to temporary directory"
for rel in "${FILES[@]}"; do
  mkdir -p "$TMP/$(dirname "$rel")"
  url="$RAW_BASE/$rel"
  echo "Fetching $rel"
  curl -fsSL --retry 3 --connect-timeout 15 "$url" -o "$TMP/$rel"
  [[ -s "$TMP/$rel" ]] || die "Downloaded file is empty: $rel"
  php -l "$TMP/$rel" >/dev/null || die "PHP lint failed BEFORE deploy: $rel"
done
echo "All seven downloaded PHP files passed php -l before deployment."

log "Create timestamped production backup"
mkdir -p "$BACKUP"
printf 'source_ref=%s\ncreated=%s\n' "$SOURCE_REF" "$(date -Is)" > "$BACKUP/manifest.txt"
for rel in "${FILES[@]}"; do
  target="$THEME/$rel"
  mkdir -p "$BACKUP/$(dirname "$rel")"
  if [[ -f "$target" ]]; then
    cp -p "$target" "$BACKUP/$rel"
    printf 'existing %s\n' "$rel" >> "$BACKUP/manifest.txt"
  else
    printf 'new %s\n' "$rel" >> "$BACKUP/manifest.txt"
  fi
done

# Preserve current page metadata before changing it.
{
  echo "11 zeus_seo_title=$(wp post meta get 11 zeus_seo_title 2>/dev/null || true)"
  echo "11 zeus_seo_description=$(wp post meta get 11 zeus_seo_description 2>/dev/null || true)"
  echo "12 zeus_seo_title=$(wp post meta get 12 zeus_seo_title 2>/dev/null || true)"
  echo "15 zeus_seo_title=$(wp post meta get 15 zeus_seo_title 2>/dev/null || true)"
} > "$BACKUP/meta-before.txt"

echo "Backup: $BACKUP"

restore_files() {
  echo "Restoring theme files from $BACKUP" >&2
  for rel in "${FILES[@]}"; do
    if [[ -f "$BACKUP/$rel" ]]; then
      mkdir -p "$THEME/$(dirname "$rel")"
      cp -p "$BACKUP/$rel" "$THEME/$rel"
    else
      rm -f "$THEME/$rel"
    fi
  done
}

log "Install approved theme files"
for rel in "${FILES[@]}"; do
  mkdir -p "$THEME/$(dirname "$rel")"
  cp "$TMP/$rel" "$THEME/$rel"
done

# A second lint checks the exact production copies. If this fails, restore files.
for rel in "${FILES[@]}"; do
  if ! php -l "$THEME/$rel" >/dev/null; then
    restore_files
    die "PHP lint failed AFTER copy: $rel. Theme files restored."
  fi
done
echo "All seven production PHP files passed php -l."

log "Apply the four approved SEO metadata corrections"
wp post meta update 11 zeus_seo_title 'Bathroom Cabinets Orlando, FL | Vanities | ZEUS'
wp post meta update 11 zeus_seo_description 'Bathroom cabinets and vanities in Orlando, FL, with in-stock and custom options, storage planning, countertops, delivery, and installation from ZEUS.'
wp post meta update 12 zeus_seo_title 'Countertops Orlando, FL | Quartz, Granite & More | ZEUS'
wp post meta update 15 zeus_seo_title 'Porcelain Countertops Orlando, FL | ZEUS'

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
check_contains 'https://zeuscabinetsflorida.com/cabinets/kitchen-cabinets/' 'Kitchen Planning Guides' 'Kitchen Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/cabinets/bathroom-cabinets-vanities/' 'Bathroom Vanity Planning Guides' 'Bathroom Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/custom-spaces/home-office/' 'Home Office Planning Guides' 'Home Office Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/custom-spaces/closets/' 'Custom Closet Planning Guides' 'Closets Planning Resources'
check_contains 'https://zeuscabinetsflorida.com/countertops/granite/' 'how-to-care-for-granite-countertops' 'Granite care-guide link'
check_contains 'https://zeuscabinetsflorida.com/category/kitchen-cabinet-guides/' 'Kitchen &amp; Cabinet Guides' 'Kitchen & Cabinet category H1'
check_contains 'https://zeuscabinetsflorida.com/category/countertop-guides/' 'Countertop Guides' 'Countertop category H1'

printf '\nDeployment complete.\nBackup: %s\nPinned source: %s\n' "$BACKUP" "$SOURCE_REF"
if (( FAILS > 0 )); then
  echo "$FAILS content verification check(s) did not render immediately. Theme PHP is valid; purge any separate host/page cache in cPanel and re-check before declaring the deploy complete."
else
  echo "All cache-busting content checks passed."
fi
