#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS homepage Real Work / Portfolio unification v2 — 2026-09-13
# Starts the server-side HTML filter before the full-page cache layer.

ROOT="/home/zeusiwpo/public_html"
THEME="$ROOT/wp-content/themes/zeus"
SOURCE_REF="b3a5f0ca6e0efefd89ad79204a01e7cf29c8c0f4"
REL="inc/content-safety.php"
URL="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}/theme/zeus/${REL}"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/home-work-unify-v2-$STAMP"
TMP="$(mktemp -d)"

cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

die() { echo "ERROR: $*" >&2; exit 1; }
log() { printf '\n==> %s\n' "$*"; }

log "Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found at $ROOT"
[[ -d "$THEME" ]] || die "ZEUS theme not found at $THEME"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"

cd "$ROOT"
ACTIVE_THEME="$(wp theme list --status=active --field=name 2>/dev/null | head -n1 || true)"
[[ "$ACTIVE_THEME" == "zeus" ]] || die "Active theme is '$ACTIVE_THEME', expected 'zeus'. Nothing changed."
echo "Active theme confirmed: $ACTIVE_THEME"
echo "Pinned source commit: $SOURCE_REF"

log "Download and validate corrected hotfix"
mkdir -p "$TMP/inc"
curl -fsSL --retry 3 --connect-timeout 15 "$URL" -o "$TMP/$REL"
[[ -s "$TMP/$REL" ]] || die "Downloaded file is empty"
php -l "$TMP/$REL" >/dev/null || die "PHP lint failed before deploy"
grep -Fq "add_action( 'wp', 'zeus_unify_home_real_work_start_buffer', 0 );" "$TMP/$REL" || die "Correct early-buffer hook is missing from source"
echo "Source passed php -l and early-buffer check."

log "Create backup"
mkdir -p "$BACKUP/inc"
cp -p "$THEME/$REL" "$BACKUP/$REL"
printf 'source_ref=%s\ncreated=%s\nfile=%s\n' "$SOURCE_REF" "$(date -Is)" "$REL" > "$BACKUP/manifest.txt"
echo "Backup: $BACKUP"

log "Install hotfix"
cp "$TMP/$REL" "$THEME/$REL"
if ! php -l "$THEME/$REL" >/dev/null; then
  cp -p "$BACKUP/$REL" "$THEME/$REL"
  die "Production php -l failed. Original file restored."
fi
echo "Production file passed php -l."

log "Flush WordPress object cache"
wp cache flush || true

log "Verify homepage with cache-busting URL"
CHECK_URL="https://zeuscabinetsflorida.com/?zeus_home_work_unify_v2=$STAMP"
HTML="$TMP/home.html"
curl -fsSL --retry 3 -H 'Cache-Control: no-cache' "$CHECK_URL" -o "$HTML"

FEATURED_COUNT="$(grep -Fo 'Featured Projects' "$HTML" | wc -l | tr -d ' ')"
REAL_LABEL_COUNT="$(grep -Fo 'Real ZEUS Work' "$HTML" | wc -l | tr -d ' ')"
LEGACY_HEADING_COUNT="$(grep -Fo 'From Real ZEUS Installations' "$HTML" | wc -l | tr -d ' ')"
LEGACY_CARD_COUNT="$(grep -Fo '>Real ZEUS Installation<' "$HTML" | wc -l | tr -d ' ')"
PORTFOLIO_BUTTON_COUNT="$(grep -Fo 'View Full Portfolio' "$HTML" | wc -l | tr -d ' ')"

[[ "$FEATURED_COUNT" -ge 1 ]] || die "Featured Projects section was not found"
[[ "$REAL_LABEL_COUNT" -eq 1 ]] || die "Expected exactly one Real ZEUS Work label, found $REAL_LABEL_COUNT"
[[ "$LEGACY_HEADING_COUNT" -eq 0 ]] || die "Legacy From Real ZEUS Installations heading is still present"
[[ "$LEGACY_CARD_COUNT" -eq 0 ]] || die "Legacy Real ZEUS Installation cards are still present"
[[ "$PORTFOLIO_BUTTON_COUNT" -ge 1 ]] || die "View Full Portfolio button was not found"

echo "PASS: exactly one Real ZEUS Work section is rendered."
echo "PASS: Featured Projects remains dynamic from Portfolio."
echo "PASS: legacy Real ZEUS Installations heading is gone."
echo "PASS: legacy static Real ZEUS Installation cards are gone."
echo "PASS: View Full Portfolio remains available."

printf '\nHotfix complete.\nBackup: %s\nPinned source: %s\n' "$BACKUP" "$SOURCE_REF"
