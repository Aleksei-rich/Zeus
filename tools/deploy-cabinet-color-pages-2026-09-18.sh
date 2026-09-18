#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS controlled production deploy — 2026-09-18
# Deploys ONLY the reviewed Cabinet Style -> Color -> Gallery feature
# (Brooklyn White/Pearl/Fawn/Gray/Slate/Midnight detail pages).
# Source is pinned to one reviewed commit so later branch changes cannot
# silently alter what reaches production. Same pattern/conventions as
# tools/deploy-production-hotfix-2026-09-11.sh.
#
# Does NOT touch: wp-config.php, .env, uploads, the database, any other
# plugin/theme, or any collection besides Brooklyn. Shaker/Oslo/Euro are
# intentionally left exactly as they are in production today.

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="2a7f46f7c9394bdd84febe5c4b78520497b76ee1"
BASELINE_REF="80ae8a81503bd68308ca9566f2175b14ccd848a8"
RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}"
BASELINE_RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${BASELINE_REF}"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"

# Files this feature actually changed. Modified files carry a known
# BASELINE_REF version (production is expected to already be running
# that exact content); new files must not already exist on production.
REPO_FILES=(
  "plugins/zeus-core/inc/cabinet-colors.php"
  "plugins/zeus-core/zeus-core.php"
  "theme/zeus/assets/css/style.css"
  "theme/zeus/functions.php"
  "theme/zeus/inc/breadcrumbs.php"
  "theme/zeus/inc/cabinet-colors.php"
  "theme/zeus/inc/seo.php"
  "theme/zeus/single-cabinet-color.php"
  "theme/zeus/single-cabinet_collection.php"
  "theme/zeus/template-parts/cabinet-color-swatches.php"
)

DEST_FILES=(
  "wp-content/plugins/zeus-core/inc/cabinet-colors.php"
  "wp-content/plugins/zeus-core/zeus-core.php"
  "wp-content/themes/zeus/assets/css/style.css"
  "wp-content/themes/zeus/functions.php"
  "wp-content/themes/zeus/inc/breadcrumbs.php"
  "wp-content/themes/zeus/inc/cabinet-colors.php"
  "wp-content/themes/zeus/inc/seo.php"
  "wp-content/themes/zeus/single-cabinet-color.php"
  "wp-content/themes/zeus/single-cabinet_collection.php"
  "wp-content/themes/zeus/template-parts/cabinet-color-swatches.php"
)

# Files this feature MODIFIED (not created) -- must match BASELINE_REF
# exactly on production before we touch them. If production drifted from
# what git recorded (an uncommitted hotfix, a manual edit), overwriting
# blind would silently discard it -- so this is a hard stop, not a warning.
BASELINE_CHECK_FILES=(
  "plugins/zeus-core/zeus-core.php"
  "theme/zeus/assets/css/style.css"
  "theme/zeus/functions.php"
  "theme/zeus/inc/breadcrumbs.php"
  "theme/zeus/inc/seo.php"
  "theme/zeus/single-cabinet_collection.php"
)

# Files this feature CREATED -- must not already exist on production.
NEW_FILES=(
  "wp-content/plugins/zeus-core/inc/cabinet-colors.php"
  "wp-content/themes/zeus/inc/cabinet-colors.php"
  "wp-content/themes/zeus/single-cabinet-color.php"
  "wp-content/themes/zeus/template-parts/cabinet-color-swatches.php"
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
  esac
}

log "Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found at $ROOT"
[[ -d "$ROOT/wp-content/themes/zeus" ]] || die "ZEUS theme directory not found"
[[ -d "$ROOT/wp-content/plugins/zeus-core" ]] || die "ZEUS Core plugin directory not found"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"
command -v sha256sum >/dev/null 2>&1 || die "sha256sum is not available"

cd "$ROOT"
ACTIVE_THEME="$(wp theme list --status=active --field=name 2>/dev/null | head -n1 || true)"
[[ "$ACTIVE_THEME" == "zeus" ]] || die "Active theme is '$ACTIVE_THEME', expected 'zeus'. Nothing changed."
wp plugin is-active zeus-core >/dev/null 2>&1 || die "ZEUS Core is not active. Nothing changed."
echo "Active theme confirmed: $ACTIVE_THEME"
echo "ZEUS Core confirmed active"
echo "Pinned source commit: $SOURCE_REF"
echo "Expected pre-deploy baseline commit: $BASELINE_REF"

# Map each repo-relative path to its production destination (arrays share
# index order).
declare -A REPO_TO_DEST
for i in "${!REPO_FILES[@]}"; do
  REPO_TO_DEST["${REPO_FILES[$i]}"]="${DEST_FILES[$i]}"
done

is_baseline_check_file() {
  local candidate="$1" f
  for f in "${BASELINE_CHECK_FILES[@]}"; do
    [[ "$f" == "$candidate" ]] && return 0
  done
  return 1
}

log "Confirm production matches the expected baseline (no undocumented drift)"
for repo_rel in "${REPO_FILES[@]}"; do
  is_baseline_check_file "$repo_rel" || continue
  dest_rel="${REPO_TO_DEST[$repo_rel]}"
  target="$ROOT/$dest_rel"
  [[ -f "$target" ]] || die "Expected existing file missing on production: $dest_rel (unexpected state, stopping)"

  baseline_tmp="$TMP/baseline-$(echo "$repo_rel" | tr '/' '_')"
  curl -fsSL --retry 3 --connect-timeout 15 "$BASELINE_RAW_BASE/$repo_rel" -o "$baseline_tmp" \
    || die "Could not fetch baseline reference for $repo_rel from commit $BASELINE_REF"

  live_hash="$(sha256sum "$target" | awk '{print $1}')"
  baseline_hash="$(sha256sum "$baseline_tmp" | awk '{print $1}')"
  if [[ "$live_hash" != "$baseline_hash" ]]; then
    die "Production copy of $dest_rel does not match expected baseline commit $BASELINE_REF. It has drifted (an uncommitted hotfix or manual edit) -- stopping without changing anything. Compare $target against $BASELINE_RAW_BASE/$repo_rel manually before re-running."
  fi
  echo "Baseline confirmed: $dest_rel"
done

log "Confirm new feature files do not already exist on production"
for dest_rel in "${NEW_FILES[@]}"; do
  target="$ROOT/$dest_rel"
  if [[ -e "$target" ]]; then
    die "Unexpected: $dest_rel already exists on production. Stopping without changing anything."
  fi
done
echo "No naming collisions with new files."

log "Download and validate approved files before touching production"
for i in "${!REPO_FILES[@]}"; do
  repo="${REPO_FILES[$i]}"
  tmp="$TMP/new/$repo"
  mkdir -p "$(dirname "$tmp")"
  echo "Fetching $repo"
  curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$repo" -o "$tmp"
  [[ -s "$tmp" ]] || die "Downloaded file is empty: $repo"
  validate_source_file "$tmp" || die "Syntax validation failed BEFORE deploy: $repo"
done
echo "All approved source files downloaded and validated."

log "Create timestamped production backup"
mkdir -p "$BACKUP/files"
{
  printf 'source_ref=%s\n' "$SOURCE_REF"
  printf 'baseline_ref=%s\n' "$BASELINE_REF"
  printf 'created=%s\n' "$(date -Is)"
} > "$BACKUP/manifest.txt"
for dest_rel in "${DEST_FILES[@]}"; do
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
echo "Backup: $BACKUP"

restore_files() {
  echo "Restoring deployed files from $BACKUP" >&2
  for dest_rel in "${DEST_FILES[@]}"; do
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
  cp "$TMP/new/$repo" "$target"
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

log "Refresh rewrite rules (Brooklyn color URLs use a new rewrite rule)"
# The plugin also self-heals this on the next normal page request (see
# zeus_maybe_flush_cabinet_color_rewrite() in cabinet-colors.php), but
# flushing explicitly here makes the deploy deterministic rather than
# relying on that happening implicitly.
if ! wp rewrite flush; then
  restore_files
  die "wp rewrite flush failed. Files restored."
fi
echo "Rewrite rules flushed."

log "Flush WordPress object cache"
wp cache flush || true

log "Live verification using cache-busting URLs"
CHECK="zeus_deploy_check=$STAMP"
FAILS=0
CHECK_SEQ=0

check_contains() {
  local url="$1" needle="$2" label="$3" body
  CHECK_SEQ=$((CHECK_SEQ+1))
  body="$TMP/live-check-${CHECK_SEQ}.html"
  if curl -fsSL --retry 2 "${url}?${CHECK}" -o "$body" && grep -Fq "$needle" "$body"; then
    echo "PASS: $label"
  else
    echo "WARN: $label not visible yet"
    FAILS=$((FAILS+1))
  fi
}

check_status() {
  local url="$1" expected="$2" label="$3" code
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 "${url}?${CHECK}")"
  if [[ "$code" == "$expected" ]]; then
    echo "PASS: $label (HTTP $code)"
  else
    echo "WARN: $label expected HTTP $expected, got $code"
    FAILS=$((FAILS+1))
  fi
}

check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/' 'cabinet-styles/brooklyn/white/' 'Brooklyn parent links to White color page'
check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/white/' '<h1>Brooklyn White Kitchen Cabinets</h1>' 'Brooklyn White H1'
check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/pearl/' '<h1>Brooklyn Pearl Kitchen Cabinets</h1>' 'Brooklyn Pearl H1'
check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/fawn/' '<h1>Brooklyn Fawn Kitchen Cabinets</h1>' 'Brooklyn Fawn H1'
check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/gray/' '<h1>Brooklyn Gray Kitchen &amp; Bath Cabinets</h1>' 'Brooklyn Gray H1'
check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/slate/' '<h1>Brooklyn Slate Kitchen Cabinets</h1>' 'Brooklyn Slate H1'
check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/midnight/' '<h1>Brooklyn Midnight Kitchen Cabinets</h1>' 'Brooklyn Midnight H1'
check_status 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/not-a-real-color/' '404' 'Invalid color returns 404'

printf '\nDeployment complete.\nBackup: %s\nPinned source: %s\nBaseline: %s\n' "$BACKUP" "$SOURCE_REF" "$BASELINE_REF"
if (( FAILS > 0 )); then
  echo "$FAILS live check(s) did not pass immediately. Files installed and passed syntax validation; purge any separate host/page cache in cPanel and re-check before declaring the deploy complete."
else
  echo "All live checks passed."
fi

printf '\nIf anything looks wrong, run this one command to roll back:\n'
cat > "$BACKUP/ROLLBACK.sh" <<ROLLBACK_EOF
#!/usr/bin/env bash
set -Eeuo pipefail
ROOT="$ROOT"
BACKUP="$BACKUP"
DEST_FILES=(
$(printf '  "%s"\n' "${DEST_FILES[@]}")
)
for dest_rel in "\${DEST_FILES[@]}"; do
  target="\$ROOT/\$dest_rel"
  backup="\$BACKUP/files/\$dest_rel"
  if [[ -f "\$backup" ]]; then
    mkdir -p "\$(dirname "\$target")"
    cp -p "\$backup" "\$target"
    echo "Restored: \$dest_rel"
  else
    rm -f "\$target"
    echo "Removed (was new in this deploy): \$dest_rel"
  fi
done
cd "\$ROOT"
wp rewrite flush || echo "WARNING: wp rewrite flush failed after rollback -- run it manually." >&2
wp cache flush || true
echo "Rollback complete."
ROLLBACK_EOF
chmod +x "$BACKUP/ROLLBACK.sh"
echo "$BACKUP/ROLLBACK.sh"
