#!/usr/bin/env bash
set -Eeuo pipefail

# ZEUS consultation email attachment hotfix — 2026-09-11
# Usage:
#   bash <(curl -fsSL RAW_URL)
#   bash <(curl -fsSL RAW_URL) 437   # deploy + resend existing test lead 437

ROOT="/home/zeusiwpo/public_html"
PLUGIN="$ROOT/wp-content/plugins/zeus-core"
SOURCE_REF="41f86d9e21c077e561dcacd1e513c37d2e4aa7f0"
RAW_BASE="https://raw.githubusercontent.com/Aleksei-rich/Zeus/${SOURCE_REF}/plugins/zeus-core"
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$HOME/zeus-deploy-backups/consultation-mail-$STAMP"
TMP="$(mktemp -d)"
RESEND_LEAD_ID="${1:-}"

FILES=(
  "zeus-core.php"
  "inc/consultation-mail-attachments.php"
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
[[ -d "$PLUGIN" ]] || die "ZEUS Core plugin directory not found at $PLUGIN"
command -v wp >/dev/null 2>&1 || die "WP-CLI is not available"
command -v php >/dev/null 2>&1 || die "PHP CLI is not available"
command -v curl >/dev/null 2>&1 || die "curl is not available"

cd "$ROOT"
wp plugin is-active zeus-core >/dev/null 2>&1 || die "zeus-core is not active. Nothing changed."
echo "ZEUS Core confirmed active"
echo "Pinned source commit: $SOURCE_REF"

log "Download and validate hotfix files"
for rel in "${FILES[@]}"; do
  mkdir -p "$TMP/$(dirname "$rel")"
  echo "Fetching $rel"
  curl -fsSL --retry 3 --connect-timeout 15 "$RAW_BASE/$rel" -o "$TMP/$rel"
  [[ -s "$TMP/$rel" ]] || die "Downloaded file is empty: $rel"
  php -l "$TMP/$rel" >/dev/null || die "PHP lint failed before deploy: $rel"
done
echo "Hotfix source files passed php -l."

log "Create backup"
mkdir -p "$BACKUP"
for rel in "${FILES[@]}"; do
  target="$PLUGIN/$rel"
  mkdir -p "$BACKUP/$(dirname "$rel")"
  if [[ -f "$target" ]]; then
    cp -p "$target" "$BACKUP/$rel"
  fi
done
printf 'source_ref=%s\ncreated=%s\n' "$SOURCE_REF" "$(date -Is)" > "$BACKUP/manifest.txt"
echo "Backup: $BACKUP"

restore_files() {
  echo "Restoring files from $BACKUP" >&2
  for rel in "${FILES[@]}"; do
    if [[ -f "$BACKUP/$rel" ]]; then
      mkdir -p "$PLUGIN/$(dirname "$rel")"
      cp -p "$BACKUP/$rel" "$PLUGIN/$rel"
    else
      rm -f "$PLUGIN/$rel"
    fi
  done
}

log "Install hotfix"
for rel in "${FILES[@]}"; do
  mkdir -p "$PLUGIN/$(dirname "$rel")"
  cp "$TMP/$rel" "$PLUGIN/$rel"
done

for rel in "${FILES[@]}"; do
  if ! php -l "$PLUGIN/$rel" >/dev/null; then
    restore_files
    die "PHP lint failed after copy: $rel. Files restored."
  fi
done

echo "Production hotfix files passed php -l."

log "Verify email attachment policy"
PUBLIC_MB="$(wp eval 'echo (int) ( ZEUS_LEAD_PUBLIC_MAX_TOTAL_UPLOAD_BYTES / 1024 / 1024 );')"
MAIL_MB="$(wp eval 'echo (int) ( ZEUS_LEAD_MAX_MAIL_ATTACHMENT_BYTES / 1024 / 1024 );')"
[[ "$PUBLIC_MB" == "15" ]] || { restore_files; die "Public upload total is ${PUBLIC_MB}MB, expected 15MB."; }
[[ "$MAIL_MB" == "15" ]] || { restore_files; die "Mail attachment total is ${MAIL_MB}MB, expected 15MB."; }

NEW_HOOK="$(wp eval 'echo has_action("zeus_send_consultation_notification", "zeus_send_consultation_notification_all_files") !== false ? "yes" : "no";')"
OLD_HOOK="$(wp eval 'echo has_action("zeus_send_consultation_notification", "zeus_send_consultation_notification") !== false ? "yes" : "no";')"
[[ "$NEW_HOOK" == "yes" ]] || { restore_files; die "New all-files mail hook is not active."; }
[[ "$OLD_HOOK" == "no" ]] || { restore_files; die "Old mail hook is still active."; }

echo "PASS: form accepts up to 15MB total and accepted files are routed to email attachments."
echo "PASS: old link-only large-upload mail callback is disabled."

wp cache flush >/dev/null 2>&1 || true

if [[ -n "$RESEND_LEAD_ID" ]]; then
  [[ "$RESEND_LEAD_ID" =~ ^[0-9]+$ ]] || die "Resend lead ID must be numeric."
  log "Resend existing consultation request #$RESEND_LEAD_ID"
  POST_TYPE="$(wp post get "$RESEND_LEAD_ID" --field=post_type 2>/dev/null || true)"
  [[ "$POST_TYPE" == "zeus_lead" ]] || die "Post #$RESEND_LEAD_ID is not a zeus_lead."
  FILE_COUNT="$(wp eval "echo count( zeus_get_lead_uploads_multi( $RESEND_LEAD_ID ) );")"
  echo "Stored files: $FILE_COUNT"
  [[ "$FILE_COUNT" -gt 0 ]] || die "Lead #$RESEND_LEAD_ID has no stored uploads to resend."
  echo "Sending test request with all stored files to zeus.cabinets@gmail.com ..."
  RESULT="$(wp eval "echo zeus_send_consultation_notification_all_files( $RESEND_LEAD_ID ) ? 'success' : 'failed';")"
  echo "Mail result: $RESULT"
  [[ "$RESULT" == "success" ]] || die "At least one attachment could not be handed to the mail transport. Check the ZEUS attachment-delivery error email and server mail logs."
  STATUS="$(wp post meta get "$RESEND_LEAD_ID" zeus_lead_notification_status 2>/dev/null || true)"
  ATTACHED="$(wp post meta get "$RESEND_LEAD_ID" zeus_lead_notification_attachment_count 2>/dev/null || true)"
  PARTS="$(wp post meta get "$RESEND_LEAD_ID" zeus_lead_notification_email_parts 2>/dev/null || true)"
  echo "Notification status: $STATUS"
  echo "Files handed to email: $ATTACHED / $FILE_COUNT"
  echo "Email message parts: ${PARTS:-1}"
fi

printf '\nHotfix complete.\nBackup: %s\nPinned source: %s\n' "$BACKUP" "$SOURCE_REF"
