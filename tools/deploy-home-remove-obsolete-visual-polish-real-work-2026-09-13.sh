#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS homepage cleanup — remove obsolete visual-polish real-work injection
# from the homepage while preserving the Cabinets-page enhancement.

ROOT="/home/zeusiwpo/public_html"
THEME="$ROOT/wp-content/themes/zeus"
FILE="$THEME/inc/visual-polish.php"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/home-visual-polish-cleanup-$STAMP"
TMP="$(mktemp -d)"

cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

die() { echo "ERROR: $*" >&2; exit 1; }
log() { printf '\n==> %s\n' "$*"; }

log "Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found at $ROOT"
[[ -f "$FILE" ]] || die "visual-polish.php not found at $FILE"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"

cd "$ROOT"
ACTIVE_THEME="$(wp theme list --status=active --field=name 2>/dev/null | head -n1 || true)"
[[ "$ACTIVE_THEME" == "zeus" ]] || die "Active theme is '$ACTIVE_THEME', expected 'zeus'. Nothing changed."
echo "Active theme confirmed: $ACTIVE_THEME"

log "Create backup"
mkdir -p "$BACKUP/inc"
cp -p "$FILE" "$BACKUP/inc/visual-polish.php"
printf 'created=%s\nfile=%s\n' "$(date -Is)" "$FILE" > "$BACKUP/manifest.txt"
echo "Backup: $BACKUP"

log "Disable obsolete homepage injection"
FILE="$FILE" php <<'PHP'
<?php
$file = getenv('FILE');
$code = file_get_contents($file);
if ($code === false) {
    fwrite(STDERR, "Cannot read visual-polish.php\n");
    exit(2);
}

$oldGuard = "if ( is_admin() || ( ! is_front_page() && ! is_page( 'cabinets' ) ) ) {";
$newGuard = "if ( is_admin() || is_front_page() || ! is_page( 'cabinets' ) ) {";

if (strpos($code, $oldGuard) !== false) {
    $code = str_replace($oldGuard, $newGuard, $code, $guardCount);
    if ($guardCount !== 1) {
        fwrite(STDERR, "Unexpected guard replacement count: {$guardCount}\n");
        exit(3);
    }
} elseif (strpos($code, $newGuard) === false) {
    fwrite(STDERR, "Expected real-work guard was not found\n");
    exit(4);
}

$oldHomeBlock = <<<'OLD'
	$is_home   = is_front_page();
	$extra_ids = $is_home ? array( 354, 357 ) : array( 357 );
	$heading   = $is_home ? 'From Real ZEUS Installations' : 'Real ZEUS Cabinetry Installations';
OLD;
$newHomeBlock = <<<'NEW'
	$extra_ids = array( 357 );
	$heading   = 'Real ZEUS Cabinetry Installations';
NEW;

if (strpos($code, $oldHomeBlock) !== false) {
    $code = str_replace($oldHomeBlock, $newHomeBlock, $code, $homeCount);
    if ($homeCount !== 1) {
        fwrite(STDERR, "Unexpected homepage block replacement count: {$homeCount}\n");
        exit(5);
    }
} elseif (strpos($code, "'From Real ZEUS Installations'") !== false) {
    fwrite(STDERR, "Legacy homepage heading still exists in an unexpected form\n");
    exit(6);
}

if (strpos($code, "'From Real ZEUS Installations'") !== false) {
    fwrite(STDERR, "Legacy homepage heading remains after patch\n");
    exit(7);
}
if (strpos($code, $newGuard) === false) {
    fwrite(STDERR, "Homepage exclusion guard missing after patch\n");
    exit(8);
}

if (file_put_contents($file, $code) === false) {
    fwrite(STDERR, "Cannot write visual-polish.php\n");
    exit(9);
}
PHP

if ! php -l "$FILE" >/dev/null; then
  cp -p "$BACKUP/inc/visual-polish.php" "$FILE"
  die "PHP lint failed. Original file restored."
fi

echo "Production visual-polish.php passed php -l."

log "Verify production source"
grep -Fq "if ( is_admin() || is_front_page() || ! is_page( 'cabinets' ) ) {" "$FILE" || die "Homepage exclusion guard missing"
if grep -Fq "'From Real ZEUS Installations'" "$FILE"; then die "Legacy homepage heading still exists in visual-polish.php"; fi
echo "PASS: homepage visual-polish injection is disabled; Cabinets-page behavior remains available."

log "Restart PHP and purge caches"
touch /home/zeusiwpo/.lsphp_restart.txt || true
wp cache flush || true
wp litespeed-purge all >/dev/null 2>&1 || true
wp litespeed-purge url https://zeuscabinetsflorida.com/ >/dev/null 2>&1 || true

log "Verify public homepage"
URL="https://zeuscabinetsflorida.com/?zeus_visual_cleanup=$STAMP"
HTML="$TMP/home.html"
curl -fsSL --retry 3 -H 'Cache-Control: no-cache, no-store, max-age=0' -H 'Pragma: no-cache' "$URL" -o "$HTML"

FEATURED="$(grep -Fo 'Featured Projects' "$HTML" | wc -l | tr -d ' ')"
REAL="$(grep -Fo 'Real ZEUS Work' "$HTML" | wc -l | tr -d ' ')"
LEGACY="$(grep -Fo 'From Real ZEUS Installations' "$HTML" | wc -l | tr -d ' ')"
LEGACY_CARDS="$(grep -Fo '>Real ZEUS Installation<' "$HTML" | wc -l | tr -d ' ')"
BUTTON="$(grep -Fo 'View Full Portfolio' "$HTML" | wc -l | tr -d ' ')"

printf 'Featured Projects: %s\n' "$FEATURED"
printf 'Real ZEUS Work: %s\n' "$REAL"
printf 'Legacy heading: %s\n' "$LEGACY"
printf 'Legacy cards: %s\n' "$LEGACY_CARDS"
printf 'Portfolio button: %s\n' "$BUTTON"

[[ "$FEATURED" -ge 1 ]] || die "Featured Projects missing from homepage"
[[ "$REAL" -eq 1 ]] || die "Expected exactly one Real ZEUS Work label, found $REAL"
[[ "$LEGACY" -eq 0 ]] || die "Legacy homepage heading still appears in public HTML"
[[ "$LEGACY_CARDS" -eq 0 ]] || die "Legacy visual-polish cards still appear in public HTML"
[[ "$BUTTON" -ge 1 ]] || die "View Full Portfolio button missing"

echo "PASS: homepage now contains one clean Real ZEUS Work / Featured Projects section."
printf '\nCleanup complete.\nBackup: %s\n' "$BACKUP"
