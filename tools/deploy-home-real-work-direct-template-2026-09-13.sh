#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS homepage Real ZEUS Work / Portfolio direct-template fix — 2026-09-13
# This intentionally patches the production front-page.php itself instead of
# relying on an output buffer. It also removes the temporary buffer workaround.

ROOT="/home/zeusiwpo/public_html"
THEME="$ROOT/wp-content/themes/zeus"
FRONT="$THEME/front-page.php"
SAFETY="$THEME/inc/content-safety.php"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/home-work-direct-$STAMP"
TMP="$(mktemp -d)"

cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

die() { echo "ERROR: $*" >&2; exit 1; }
log() { printf '\n==> %s\n' "$*"; }

log "Preflight"
[[ -f "$ROOT/wp-config.php" ]] || die "WordPress root not found at $ROOT"
[[ -f "$FRONT" ]] || die "front-page.php not found"
[[ -f "$SAFETY" ]] || die "content-safety.php not found"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"

cd "$ROOT"
ACTIVE_THEME="$(wp theme list --status=active --field=name 2>/dev/null | head -n1 || true)"
[[ "$ACTIVE_THEME" == "zeus" ]] || die "Active theme is '$ACTIVE_THEME', expected 'zeus'. Nothing changed."
echo "Active theme confirmed: $ACTIVE_THEME"

log "Create backup"
mkdir -p "$BACKUP/inc"
cp -p "$FRONT" "$BACKUP/front-page.php"
cp -p "$SAFETY" "$BACKUP/inc/content-safety.php"
printf 'created=%s\nfront=%s\nsafety=%s\n' "$(date -Is)" "$FRONT" "$SAFETY" > "$BACKUP/manifest.txt"
echo "Backup: $BACKUP"

log "Patch front-page.php directly"
FRONT="$FRONT" SAFETY="$SAFETY" php <<'PHP'
<?php
$front = getenv('FRONT');
$safety = getenv('SAFETY');

$html = file_get_contents($front);
if ($html === false) {
    fwrite(STDERR, "Cannot read front-page.php\n");
    exit(2);
}

$oldEyebrow = "'eyebrow' => __( 'Portfolio', 'zeus' ),";
$newEyebrow = "'eyebrow' => __( 'Real ZEUS Work', 'zeus' ),";
$count = substr_count($html, $oldEyebrow);
if ($count === 1) {
    $html = str_replace($oldEyebrow, $newEyebrow, $html);
} elseif ($count === 0 && substr_count($html, $newEyebrow) === 1) {
    // Already relabeled; continue.
} else {
    fwrite(STDERR, "Unexpected Portfolio eyebrow count: {$count}\n");
    exit(3);
}

$pattern = '~\n<!-- 9b\. Real ZEUS project photography.*?\n<!-- 10\. Why ZEUS -->~s';
$legacyMatches = 0;
$html = preg_replace($pattern, "\n\n<!-- 10. Why ZEUS -->", $html, 1, $legacyMatches);
if ($legacyMatches !== 1 && strpos($html, 'From Real ZEUS Installations') !== false) {
    fwrite(STDERR, "Could not remove legacy Real ZEUS Installations section\n");
    exit(4);
}

if (strpos($html, 'From Real ZEUS Installations') !== false || strpos($html, "Real ZEUS Installation', 'zeus'") !== false) {
    fwrite(STDERR, "Legacy Real ZEUS Work markup remains after patch\n");
    exit(5);
}
if (substr_count($html, "'eyebrow' => __( 'Real ZEUS Work', 'zeus' ),") !== 1) {
    fwrite(STDERR, "Expected exactly one Real ZEUS Work eyebrow in template\n");
    exit(6);
}

if (file_put_contents($front, $html) === false) {
    fwrite(STDERR, "Cannot write front-page.php\n");
    exit(7);
}

// Remove the temporary output-buffer workaround. The source template now owns
// the structure directly, so runtime HTML surgery is unnecessary.
$code = file_get_contents($safety);
if ($code === false) {
    fwrite(STDERR, "Cannot read content-safety.php\n");
    exit(8);
}
$bufferPattern = '~\n/\*\*\n \* The homepage historically rendered two consecutive sections.*?add_action\( \'wp\', \'zeus_unify_home_real_work_start_buffer\', 0 \);\n~s';
$removed = 0;
$code = preg_replace($bufferPattern, "\n", $code, 1, $removed);
if ($removed === 0) {
    // Accept the earlier template_redirect variant too, or a file already cleaned.
    $bufferPattern2 = '~\n/\*\*\n \* The homepage historically rendered two consecutive sections.*?add_action\( \'template_redirect\', \'zeus_unify_home_real_work_start_buffer\', 99 \);\n~s';
    $code = preg_replace($bufferPattern2, "\n", $code, 1, $removed);
}
if (strpos($code, 'zeus_unify_home_real_work_start_buffer') !== false || strpos($code, 'zeus_unify_home_real_work_html') !== false) {
    fwrite(STDERR, "Temporary homepage output-buffer code still remains\n");
    exit(9);
}
if (file_put_contents($safety, $code) === false) {
    fwrite(STDERR, "Cannot write content-safety.php\n");
    exit(10);
}
PHP

if ! php -l "$FRONT" >/dev/null || ! php -l "$SAFETY" >/dev/null; then
  cp -p "$BACKUP/front-page.php" "$FRONT"
  cp -p "$BACKUP/inc/content-safety.php" "$SAFETY"
  die "PHP lint failed after patch. Original files restored."
fi

echo "Production files passed php -l."

log "Verify production source before touching cache"
grep -Fq "'eyebrow' => __( 'Real ZEUS Work', 'zeus' )," "$FRONT" || die "Real ZEUS Work eyebrow missing from production template"
if grep -Fq 'From Real ZEUS Installations' "$FRONT"; then die "Legacy heading still exists in production template"; fi
if grep -Fq "Real ZEUS Installation', 'zeus'" "$FRONT"; then die "Legacy static cards still exist in production template"; fi
if grep -Fq 'zeus_unify_home_real_work_start_buffer' "$SAFETY"; then die "Temporary output-buffer workaround still exists"; fi
echo "PASS: production template itself contains one unified project section."

log "Flush WordPress and known page caches"
wp cache flush || true
ACTIVE_PLUGINS="$(wp plugin list --status=active --field=name 2>/dev/null || true)"
if printf '%s\n' "$ACTIVE_PLUGINS" | grep -qx 'litespeed-cache'; then
  echo "LiteSpeed Cache detected; purging all."
  wp litespeed-purge all >/dev/null 2>&1 || wp eval 'do_action("litespeed_purge_all");' >/dev/null 2>&1 || true
fi
if printf '%s\n' "$ACTIVE_PLUGINS" | grep -qx 'w3-total-cache'; then
  echo "W3 Total Cache detected; purging all."
  wp w3-total-cache flush all >/dev/null 2>&1 || true
fi
if printf '%s\n' "$ACTIVE_PLUGINS" | grep -qx 'wp-super-cache'; then
  echo "WP Super Cache detected; clearing cache."
  wp eval 'if (function_exists("wp_cache_clear_cache")) { wp_cache_clear_cache(); }' >/dev/null 2>&1 || true
fi

verify_html() {
  local file="$1"
  local featured real legacy legacy_cards button
  featured="$(grep -Fo 'Featured Projects' "$file" | wc -l | tr -d ' ')"
  real="$(grep -Fo 'Real ZEUS Work' "$file" | wc -l | tr -d ' ')"
  legacy="$(grep -Fo 'From Real ZEUS Installations' "$file" | wc -l | tr -d ' ')"
  legacy_cards="$(grep -Fo '>Real ZEUS Installation<' "$file" | wc -l | tr -d ' ')"
  button="$(grep -Fo 'View Full Portfolio' "$file" | wc -l | tr -d ' ')"
  [[ "$featured" -ge 1 && "$real" -eq 1 && "$legacy" -eq 0 && "$legacy_cards" -eq 0 && "$button" -ge 1 ]]
}

log "Verify public homepage"
PUBLIC="$TMP/public.html"
CHECK_URL="https://zeuscabinetsflorida.com/?zeus_work_direct=$STAMP"
curl -fsSL --retry 3 -H 'Cache-Control: no-cache, no-store, max-age=0' -H 'Pragma: no-cache' "$CHECK_URL" -o "$PUBLIC"

if verify_html "$PUBLIC"; then
  echo "PASS: public homepage renders exactly one Real ZEUS Work / Featured Projects section."
  echo "PASS: legacy static Real ZEUS Installations block is gone."
  echo "PASS: View Full Portfolio remains available."
else
  echo "WARN: production template is correct, but the public hostname is still returning stale HTML."
  echo "Attempting direct-origin verification to distinguish host cache from template output."
  ORIGIN="$TMP/origin.html"
  if curl -kfsSL --retry 2 --resolve zeuscabinetsflorida.com:443:127.0.0.1 -H 'Cache-Control: no-cache, no-store, max-age=0' "https://zeuscabinetsflorida.com/?zeus_origin_work=$STAMP" -o "$ORIGIN" 2>/dev/null && verify_html "$ORIGIN"; then
    echo "PASS: origin renders the unified block correctly. Public hostname cache is stale."
    echo "SOURCE FIX IS COMPLETE; only host/CDN cache remains to expire or purge."
  else
    echo "Public/origin HTML still failed verification. Showing counts for diagnosis:"
    printf 'Public: Featured=%s RealZEUS=%s LegacyHeading=%s LegacyCards=%s PortfolioButton=%s\n' \
      "$(grep -Fo 'Featured Projects' "$PUBLIC" | wc -l | tr -d ' ')" \
      "$(grep -Fo 'Real ZEUS Work' "$PUBLIC" | wc -l | tr -d ' ')" \
      "$(grep -Fo 'From Real ZEUS Installations' "$PUBLIC" | wc -l | tr -d ' ')" \
      "$(grep -Fo '>Real ZEUS Installation<' "$PUBLIC" | wc -l | tr -d ' ')" \
      "$(grep -Fo 'View Full Portfolio' "$PUBLIC" | wc -l | tr -d ' ')"
    exit 2
  fi
fi

printf '\nDirect-template hotfix complete.\nBackup: %s\n' "$BACKUP"
