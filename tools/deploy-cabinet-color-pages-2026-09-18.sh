#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS controlled production deploy — 2026-09-18 (rev. 3, seo.php split)
# Deploys ONLY the reviewed Cabinet Style -> Color -> Gallery feature
# (Brooklyn White/Pearl/Fawn/Gray/Slate/Midnight detail pages).
# Source is pinned to one reviewed commit so later branch changes cannot
# silently alter what reaches production. Same pattern/conventions as
# tools/deploy-production-hotfix-2026-09-11.sh, plus:
#   - a media preflight that verifies every Brooklyn attachment ID this
#     feature depends on before touching any file,
#   - a general transactional-rollback trap (not just two hand-picked
#     failure points), armed only after the backup is complete,
#   - a rollback script written to disk before the first file is replaced,
#   - live verification that never rolls back already-installed,
#     already-validated files just because an HTTP check has a hiccup.
#
# IMPORTANT, EXPLICIT SPECIAL CASE -- theme/zeus/inc/seo.php:
# A forensic hash/history investigation this session found that
# production's live seo.php does NOT match the SOURCE_REF/BASELINE_REF
# lineage -- it is running commit cad233d5828b80d417663a021426fc25a742037b
# byte-for-byte (verified via SHA-256), one commit behind BASELINE_REF.
# The one commit in between (9652e75, "add project structured data")
# adds an unrelated CreativeWork schema for Portfolio pages that was
# never asked for in this deploy. So seo.php is NOT downloaded from
# SOURCE_REF like the other 9 files -- it is downloaded from a separate,
# purpose-built artifact (SEO_ADAPTED_REF, below) that starts from the
# exact verified live content and adds ONLY the three Brooklyn additions
# on top, with the CreativeWork addition deliberately left out. See
# tools/deploy-assets/cabinet-color-seo-production.php's own commit
# message for the full derivation, and docs/DECISIONS.md.
#
# Does NOT touch: wp-config.php, .env, uploads, the database, any other
# plugin/theme, or any collection besides Brooklyn. Shaker/Oslo/Euro are
# intentionally left exactly as they are in production today.

ROOT="/home/zeusiwpo/public_html"
SOURCE_REF="2a7f46f7c9394bdd84febe5c4b78520497b76ee1"
BASELINE_REF="80ae8a81503bd68308ca9566f2175b14ccd848a8"
RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}"
BASELINE_RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${BASELINE_REF}"

# theme/zeus/inc/seo.php's own, separate, audited source -- see the
# header comment above. SEO_LIVE_REF is the commit verified (by hash) to
# be exactly what production runs today; SEO_ADAPTED_REF is the ops
# commit containing the production-safe adaptation (live content +
# Brooklyn additions only, no CreativeWork schema) that actually gets
# installed.
SEO_DEST_FILE="wp-content/themes/zeus/inc/seo.php"
SEO_LIVE_REF="cad233d5828b80d417663a021426fc25a742037b"
SEO_LIVE_RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SEO_LIVE_REF}"
SEO_LIVE_EXPECTED_SHA256="701efcd07398a3233fb0997f2f4acb71920a5e8f68d5350225c6738ad658b1c4"
SEO_ADAPTED_REF="f8c9d5437e220fb81da86a453103ba405ca58082"
SEO_ADAPTED_REPO_PATH="tools/deploy-assets/cabinet-color-seo-production.php"
SEO_ADAPTED_RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SEO_ADAPTED_REF}"
SEO_ADAPTED_EXPECTED_SHA256="3c434dbdefeeb3270ff5e6a10a8e0fb1650d943e818192e2b388150e7f647366"

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/$STAMP"
TMP="$(mktemp -d)"

# The 9 files sourced normally from SOURCE_REF. seo.php is deliberately
# excluded here -- see SEO_* above and the "Download the production-
# adapted seo.php" step.
REPO_FILES=(
  "plugins/zeus-core/inc/cabinet-colors.php"
  "plugins/zeus-core/zeus-core.php"
  "theme/zeus/assets/css/style.css"
  "theme/zeus/functions.php"
  "theme/zeus/inc/breadcrumbs.php"
  "theme/zeus/inc/cabinet-colors.php"
  "theme/zeus/single-cabinet-color.php"
  "theme/zeus/single-cabinet_collection.php"
  "theme/zeus/template-parts/cabinet-color-swatches.php"
)

# Destinations for the 9 REPO_FILES above, same order.
STANDARD_DEST_FILES=(
  "wp-content/plugins/zeus-core/inc/cabinet-colors.php"
  "wp-content/plugins/zeus-core/zeus-core.php"
  "wp-content/themes/zeus/assets/css/style.css"
  "wp-content/themes/zeus/functions.php"
  "wp-content/themes/zeus/inc/breadcrumbs.php"
  "wp-content/themes/zeus/inc/cabinet-colors.php"
  "wp-content/themes/zeus/single-cabinet-color.php"
  "wp-content/themes/zeus/single-cabinet_collection.php"
  "wp-content/themes/zeus/template-parts/cabinet-color-swatches.php"
)

# All 10 production destinations this deploy touches, used for backup,
# rollback, and the new-file-collision check -- unchanged in count and
# content from the previous revision. seo.php is included here even
# though it's sourced specially: it still gets backed up, restored on
# rollback, and syntax-validated exactly like the other 9.
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
# exactly on production before we touch them. seo.php is deliberately
# NOT in this list -- it has its own dedicated check against SEO_LIVE_REF
# instead, since production is known (by hash) to be one commit behind
# BASELINE_REF for this one file specifically.
BASELINE_CHECK_FILES=(
  "plugins/zeus-core/zeus-core.php"
  "theme/zeus/assets/css/style.css"
  "theme/zeus/functions.php"
  "theme/zeus/inc/breadcrumbs.php"
  "theme/zeus/single-cabinet_collection.php"
)

# Files this feature CREATED -- must not already exist on production.
NEW_FILES=(
  "wp-content/plugins/zeus-core/inc/cabinet-colors.php"
  "wp-content/themes/zeus/inc/cabinet-colors.php"
  "wp-content/themes/zeus/single-cabinet-color.php"
  "wp-content/themes/zeus/template-parts/cabinet-color-swatches.php"
)

# Every WordPress attachment ID the Brooklyn color galleries/heroes use,
# paired with the exact post_title recorded in docs/ASSET-PROVENANCE.csv
# and the approved content map (plugins/zeus-core/inc/cabinet-colors.php).
# Order matches Brooklyn's zeus_gallery postmeta.
BROOKLYN_MEDIA_IDS=(110 111 112 113 114 115 116 117 118 119 120 121 122)
BROOKLYN_MEDIA_TITLES=(
  "Brooklyn Fawn Kitchen"
  "Brooklyn Fawn Bathroom"
  "Brooklyn Gray Home Office"
  "Brooklyn Gray Bathroom"
  "Brooklyn Midnight Kitchen"
  "Brooklyn Midnight Bathroom"
  "Brooklyn White Kitchen"
  "Brooklyn White Kitchen 2"
  "Brooklyn Pearl Kitchen"
  "Brooklyn Pearl Bathroom"
  "Brooklyn Pearl Home Office"
  "Brooklyn Slate Kitchen"
  "Brooklyn Slate Kitchen 2"
)

# Per-color data for live verification, in the same order throughout.
COLOR_SLUGS=(white pearl fawn gray slate midnight)
COLOR_LABEL=(White Pearl Fawn Gray Slate Midnight)
COLOR_H1=(
  "Brooklyn White Kitchen Cabinets"
  "Brooklyn Pearl Kitchen Cabinets"
  "Brooklyn Fawn Kitchen Cabinets"
  "Brooklyn Gray Kitchen &amp; Bath Cabinets"
  "Brooklyn Slate Kitchen Cabinets"
  "Brooklyn Midnight Kitchen Cabinets"
)
COLOR_HERO_ID=(116 118 110 113 122 114)
declare -A HERO_IMAGE_URL

ROLLBACK_ARMED=0
ROLLBACK_IN_PROGRESS=0

cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

log() {
  printf '\n==> %s\n' "$*"
}

restore_files() {
  echo "Restoring deployed files from $BACKUP" >&2
  local dest_rel target backup
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

# Single rollback path used by BOTH an explicit die() after the backup
# exists AND the generic ERR trap below, so there is exactly one
# recovery routine, not several bespoke ones. Guarded against re-entrancy
# so a failure while rolling back cannot retrigger itself.
perform_rollback() {
  if [[ "$ROLLBACK_IN_PROGRESS" == "1" ]]; then
    return
  fi
  ROLLBACK_IN_PROGRESS=1
  trap - ERR
  echo "Rolling back to pre-deploy state (backup: $BACKUP)..." >&2
  restore_files
  echo "Re-running rewrite flush and cache flush against the restored code..." >&2
  if ! wp rewrite flush; then
    echo "WARNING: wp rewrite flush failed after rollback. Run manually: cd $ROOT && wp rewrite flush" >&2
  fi
  wp cache flush || true
  echo "Automatic rollback complete." >&2
  echo "A standalone rollback script also remains available: $BACKUP/ROLLBACK.sh" >&2
}

die() {
  echo "ERROR: $*" >&2
  if [[ "$ROLLBACK_ARMED" == "1" ]]; then
    perform_rollback
  fi
  exit 1
}

# Catches anything NOT already wrapped in an explicit `|| die ...` (a raw
# mkdir/cp failure, disk full, permissions error, etc.) during the
# critical installation phase. Harmless before the backup exists (nothing
# to roll back yet) and disarmed entirely before live verification, so it
# only ever covers the phase this task asked it to cover.
on_err() {
  local exit_code=$?
  echo "ERROR: unexpected failure (exit code $exit_code)." >&2
  if [[ "$ROLLBACK_ARMED" == "1" ]]; then
    perform_rollback
  fi
  exit 1
}
trap on_err ERR

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

# Map each of the 9 standard repo-relative paths to its production
# destination (REPO_FILES and STANDARD_DEST_FILES share index order).
declare -A REPO_TO_DEST
for i in "${!REPO_FILES[@]}"; do
  REPO_TO_DEST["${REPO_FILES[$i]}"]="${STANDARD_DEST_FILES[$i]}"
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
    die "Production copy of $dest_rel does not match expected baseline commit $BASELINE_REF. It has drifted (an uncommitted hotfix or manual edit, OR this script already ran successfully once) -- stopping without changing anything. Compare $target against $BASELINE_RAW_BASE/$repo_rel manually before re-running."
  fi
  echo "Baseline confirmed: $dest_rel"
done

log "Confirm production seo.php matches the exact known-live commit ($SEO_LIVE_REF)"
# seo.php is checked separately from BASELINE_CHECK_FILES/BASELINE_REF --
# see the header comment. This also self-checks the reference commit's
# own content against the hash recorded during this session's forensic
# analysis, so a compromised/wrong URL would be caught too, not just a
# production mismatch.
seo_target="$ROOT/$SEO_DEST_FILE"
[[ -f "$seo_target" ]] || die "Expected existing file missing on production: $SEO_DEST_FILE (unexpected state, stopping)"

seo_live_baseline_tmp="$TMP/seo-live-baseline.php"
curl -fsSL --retry 3 --connect-timeout 15 "$SEO_LIVE_RAW_BASE/theme/zeus/inc/seo.php" -o "$seo_live_baseline_tmp" \
  || die "Could not fetch the known-live reference for seo.php from commit $SEO_LIVE_REF"

seo_live_baseline_hash="$(sha256sum "$seo_live_baseline_tmp" | awk '{print $1}')"
[[ "$seo_live_baseline_hash" == "$SEO_LIVE_EXPECTED_SHA256" ]] \
  || die "Internal check failed: commit $SEO_LIVE_REF's seo.php hashes to $seo_live_baseline_hash, not the expected $SEO_LIVE_EXPECTED_SHA256. Do not trust this reference -- stopping."

seo_live_hash="$(sha256sum "$seo_target" | awk '{print $1}')"
if [[ "$seo_live_hash" != "$SEO_LIVE_EXPECTED_SHA256" ]]; then
  die "Production copy of $SEO_DEST_FILE ($seo_live_hash) does not match the known-live commit $SEO_LIVE_REF ($SEO_LIVE_EXPECTED_SHA256). It has drifted further since this was last verified (an uncommitted hotfix, a manual edit, OR this script already ran successfully once) -- stopping without changing anything. Compare $seo_target against $SEO_LIVE_RAW_BASE/theme/zeus/inc/seo.php manually before re-running."
fi
echo "Baseline confirmed: $SEO_DEST_FILE matches known-live commit $SEO_LIVE_REF exactly."

log "Confirm new feature files do not already exist on production"
for dest_rel in "${NEW_FILES[@]}"; do
  target="$ROOT/$dest_rel"
  if [[ -e "$target" ]]; then
    die "Unexpected: $dest_rel already exists on production. Stopping without changing anything."
  fi
done
echo "No naming collisions with new files."

log "Verify Brooklyn media assets exist in the production database"
for i in "${!BROOKLYN_MEDIA_IDS[@]}"; do
  id="${BROOKLYN_MEDIA_IDS[$i]}"
  expected_title="${BROOKLYN_MEDIA_TITLES[$i]}"

  post_type="$(wp post get "$id" --field=post_type 2>/dev/null || true)"
  [[ "$post_type" == "attachment" ]] \
    || die "Brooklyn media preflight failed: attachment $id not found or is not an attachment (post_type='$post_type'). Expected: '$expected_title'. Stopping before any production file is touched. Do not guess -- verify the media library manually."

  actual_title="$(wp post get "$id" --field=post_title 2>/dev/null || true)"
  [[ "$actual_title" == "$expected_title" ]] \
    || die "Brooklyn media preflight failed: attachment $id title is '$actual_title', expected '$expected_title' per docs/ASSET-PROVENANCE.csv. This looks like a different image than the one this feature was reviewed against. Stopping."

  attachment_url="$(wp eval "echo wp_get_attachment_url( $id );" 2>/dev/null || true)"
  [[ -n "$attachment_url" ]] \
    || die "Brooklyn media preflight failed: attachment $id ('$expected_title') has no resolvable attachment URL. Stopping."

  attached_file="$(wp eval "echo get_attached_file( $id );" 2>/dev/null || true)"
  [[ -n "$attached_file" && -f "$attached_file" ]] \
    || die "Brooklyn media preflight failed: attachment $id ('$expected_title') file does not exist on disk ($attached_file). Stopping."

  echo "Verified: attachment $id ($expected_title)"
done
echo "All ${#BROOKLYN_MEDIA_IDS[@]} Brooklyn media assets verified present, valid, and correctly identified."

log "Resolve hero image URLs for live verification"
for i in "${!COLOR_SLUGS[@]}"; do
  slug="${COLOR_SLUGS[$i]}"
  hero_id="${COLOR_HERO_ID[$i]}"
  hero_url="$(wp eval "echo wp_get_attachment_image_url( $hero_id, 'zeus-hero' );" 2>/dev/null || true)"
  [[ -n "$hero_url" ]] || die "Could not resolve hero image URL for Brooklyn $slug (attachment $hero_id). Stopping."
  HERO_IMAGE_URL["$slug"]="$(basename "$hero_url")"
  echo "Hero image for $slug: ${HERO_IMAGE_URL[$slug]}"
done

log "Download and validate the 9 standard files (source: $SOURCE_REF)"
for i in "${!REPO_FILES[@]}"; do
  repo="${REPO_FILES[$i]}"
  tmp="$TMP/new/$repo"
  mkdir -p "$(dirname "$tmp")"
  echo "Fetching $repo"
  curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$repo" -o "$tmp"
  [[ -s "$tmp" ]] || die "Downloaded file is empty: $repo"
  validate_source_file "$tmp" || die "Syntax validation failed BEFORE deploy: $repo"
done
echo "All ${#REPO_FILES[@]} standard source files downloaded and validated."

log "Download and validate the production-adapted seo.php (separate, audited source)"
echo "NOT sourced from $SOURCE_REF -- see the header comment for why."
echo "Source: $SEO_ADAPTED_RAW_BASE/$SEO_ADAPTED_REPO_PATH"
seo_new_tmp="$TMP/new/$SEO_DEST_FILE"
mkdir -p "$(dirname "$seo_new_tmp")"
curl -fsSL --retry 3 --connect-timeout 15 "$SEO_ADAPTED_RAW_BASE/$SEO_ADAPTED_REPO_PATH" -o "$seo_new_tmp"
[[ -s "$seo_new_tmp" ]] || die "Downloaded production-adapted seo.php is empty"
validate_source_file "$seo_new_tmp" || die "Syntax validation failed BEFORE deploy: production-adapted seo.php"
seo_new_hash="$(sha256sum "$seo_new_tmp" | awk '{print $1}')"
[[ "$seo_new_hash" == "$SEO_ADAPTED_EXPECTED_SHA256" ]] \
  || die "Downloaded production-adapted seo.php hash ($seo_new_hash) does not match the expected, reviewed hash ($SEO_ADAPTED_EXPECTED_SHA256). Stopping -- refusing to install an unverified file."
echo "Production-adapted seo.php downloaded and hash-verified against the reviewed value."

log "Create timestamped production backup"
mkdir -p "$BACKUP/files"
{
  printf 'source_ref=%s\n' "$SOURCE_REF"
  printf 'baseline_ref=%s\n' "$BASELINE_REF"
  printf 'seo_live_ref=%s\n' "$SEO_LIVE_REF"
  printf 'seo_adapted_ref=%s\n' "$SEO_ADAPTED_REF"
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

log "Generate standalone rollback script (before any production file is replaced)"
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
echo "Rollback script ready: $BACKUP/ROLLBACK.sh"
echo "(This path is valid even if the session dies before the deploy finishes.)"

# From this point on, an automatic rollback restores everything just
# backed up on ANY unexpected failure -- explicit die() calls below, or
# the generic ERR trap catching something nobody anticipated (a mkdir/cp
# failure, disk full, permissions error, etc.).
ROLLBACK_ARMED=1

log "Install the 9 standard files"
for i in "${!REPO_FILES[@]}"; do
  repo="${REPO_FILES[$i]}"
  dest_rel="${STANDARD_DEST_FILES[$i]}"
  target="$ROOT/$dest_rel"
  mkdir -p "$(dirname "$target")"
  cp "$TMP/new/$repo" "$target"
done

log "Install production-adapted seo.php"
seo_install_target="$ROOT/$SEO_DEST_FILE"
mkdir -p "$(dirname "$seo_install_target")"
cp "$TMP/new/$SEO_DEST_FILE" "$seo_install_target"
echo "Installed: $SEO_DEST_FILE (from $SEO_ADAPTED_REF -- NOT from $SOURCE_REF)"

log "Validate exact production copies"
for dest_rel in "${DEST_FILES[@]}"; do
  target="$ROOT/$dest_rel"
  validate_source_file "$target" || die "Syntax validation failed AFTER copy: $dest_rel."
done
echo "Production copies passed syntax validation."

log "Refresh rewrite rules (Brooklyn color URLs use a new rewrite rule)"
# The plugin also self-heals this on the next normal page request (see
# zeus_maybe_flush_cabinet_color_rewrite() in cabinet-colors.php), but
# flushing explicitly here makes the deploy deterministic rather than
# relying on that happening implicitly. A failure here rolls back via
# die() -> perform_rollback(), which itself re-flushes against the
# restored code -- see perform_rollback() above.
wp rewrite flush || die "wp rewrite flush failed after installing new files."
echo "Rewrite rules flushed."

log "Flush WordPress object cache"
wp cache flush || true

# Verification below must never roll back already-installed, already-
# syntax-validated files just because an HTTP check has a transient
# hiccup -- disarm rollback AND remove the ERR trap entirely so a
# network/DNS/TLS failure during curl cannot cause an uncontrolled exit.
ROLLBACK_ARMED=0
trap - ERR

log "Live verification using cache-busting URLs"
CHECK="zeus_deploy_check=$STAMP"
FAILS=0
CHECK_SEQ=0

fetch_body() {
  local url="$1" body
  CHECK_SEQ=$((CHECK_SEQ+1))
  body="$TMP/live-check-${CHECK_SEQ}.html"
  if curl -fsSL --retry 2 --connect-timeout 15 "${url}?${CHECK}" -o "$body" 2>/dev/null; then
    printf '%s' "$body"
  fi
}

check_contains() {
  local url="$1" needle="$2" label="$3" body
  body="$(fetch_body "$url")"
  if [[ -n "$body" ]] && grep -Fq -- "$needle" "$body"; then
    echo "PASS: $label"
  else
    echo "WARN: $label not visible yet (fetch failed or content not found)"
    FAILS=$((FAILS+1))
  fi
}

check_status() {
  local url="$1" expected="$2" label="$3" code
  code="$(curl -s -o /dev/null -w '%{http_code}' --retry 2 --connect-timeout 15 "${url}?${CHECK}" 2>/dev/null || true)"
  if [[ "$code" == "$expected" ]]; then
    echo "PASS: $label (HTTP $code)"
  else
    echo "WARN: $label expected HTTP $expected, got '${code:-no response}'"
    FAILS=$((FAILS+1))
  fi
}

check_status 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/' '200' 'Brooklyn parent page loads'
for slug in "${COLOR_SLUGS[@]}"; do
  check_contains 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/' "cabinet-styles/brooklyn/${slug}/" "Brooklyn parent links to ${slug} color page"
done

for i in "${!COLOR_SLUGS[@]}"; do
  slug="${COLOR_SLUGS[$i]}"
  h1="${COLOR_H1[$i]}"
  label_name="${COLOR_LABEL[$i]}"
  url="https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/${slug}/"

  check_status "$url" '200' "Brooklyn ${label_name} page loads"
  check_contains "$url" "<h1>${h1}</h1>" "Brooklyn ${label_name} H1"
  check_contains "$url" "<link rel=\"canonical\" href=\"${url}\"" "Brooklyn ${label_name} self-referencing canonical"
  check_contains "$url" "${HERO_IMAGE_URL[$slug]}" "Brooklyn ${label_name} gallery image present"
  check_contains "$url" "zeus_submit_consultation" "Brooklyn ${label_name} Request Free Consultation CTA present"
  check_contains "$url" "zeus-breadcrumbs" "Brooklyn ${label_name} breadcrumb present"
  check_contains "$url" ">${label_name}<" "Brooklyn ${label_name} breadcrumb/color label present"
done

check_status 'https://zeuscabinetsflorida.com/cabinet-styles/brooklyn/not-a-real-color/' '404' 'Invalid color returns 404'
check_status 'https://zeuscabinetsflorida.com/cabinet-styles/shaker/white/' '404' 'Unpublished Shaker/White combination returns 404'

printf '\nFiles installed and passed syntax validation.\nBackup: %s\nPinned source (9 standard files): %s\nBaseline (9 standard files): %s\nseo.php live baseline: %s\nseo.php installed from (production-adapted, no CreativeWork schema): %s\nRollback script: %s\n' \
  "$BACKUP" "$SOURCE_REF" "$BASELINE_REF" "$SEO_LIVE_REF" "$SEO_ADAPTED_REF" "$BACKUP/ROLLBACK.sh"

if (( FAILS > 0 )); then
  echo ""
  echo "$FAILS live check(s) did not pass."
  echo "DEPLOYED BUT NOT VERIFIED"
  echo "Files are installed and syntax-valid, but live verification did not fully confirm the result -- commonly a host/page cache still serving the old page, or a transient network issue during this check, not necessarily a broken deploy. Purge any separate host/page cache in cPanel, wait a minute, and re-run the read-only verification commands manually before treating this deploy as confirmed."
  echo "Do not re-run this script to 'retry' a verification failure: it will now correctly refuse to run again, since production's files no longer match the pre-deploy baseline it checks for."
  exit 2
fi

echo ""
echo "All live checks passed."
