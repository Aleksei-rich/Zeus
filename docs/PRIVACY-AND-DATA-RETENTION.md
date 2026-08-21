# Privacy & Data Retention — Request Free Consultation

Covers what the consultation form collects, why, where it lives, and
what should happen before production launch. This is a working plan for
Phase 3+ — it is not a substitute for a real privacy policy page or
legal review before launch.

## What is collected

Only what the form explicitly asks for, per the approved field list:

- Name, phone, email, ZIP code
- Project type (a fixed set of options)
- Project description (free text)
- An optional photo/plan upload

**Deliberately not collected:** street address, exact location beyond
ZIP, raw IP address (only a salted SHA-256 hash is used transiently, as
a rate-limiting cache key — see below), any tracking/marketing
identifiers, and no fields beyond what's needed to prepare a
consultation. This is intentional — see
`.claude/rules/wordpress-development.md` and the "no more personal
information than required" instruction.

## Where it lives

- **Lead record:** a private, non-public, non-REST-exposed
  `zeus_lead` custom post type entry (title + postmeta: name, phone,
  email, ZIP, project type, description, submission timestamp).
  Visible only to logged-in users with `edit_post` capability on that
  entry, in wp-admin under "Consultation Requests."
- **Uploaded file (if any):** stored outside the public Media Library,
  in `wp-content/uploads/zeus-private-leads/`, under a random 32-character
  filename (not the original filename, not linked publicly anywhere).
  Protected by `.htaccess`/`web.config` (Apache/IIS) and, in local dev,
  by `tools/router.php` (PHP's built-in server doesn't honor
  `.htaccess` at all — see the note in `docs/HANDOFF.md`). Downloadable
  only through an admin-gated, nonce-protected handler
  (`inc/leads.php`).
- **Rate-limiting cache:** a WordPress transient keyed by a salted hash
  of the submitter's IP address, expiring automatically after 1 hour.
  The raw IP is never written to the database.
- **Notification:** locally, submissions are logged to
  `wp-content/zeus-lead-mail.log` instead of emailed (see
  `docs/HANDOFF.md`); in production this becomes a real `wp_mail()` call
  to the site's admin email, using whatever mail/SMTP WordPress is
  configured with.

## Retention — decision needed before production

No automatic deletion/expiry is implemented yet. Before production
launch, the owner should decide and this doc should be updated with:

1. **How long lead records are kept** after a consultation is booked or
   declined (e.g., 12 months, then deleted or anonymized).
2. **Who has access** to the Consultation Requests admin screen (which
   WordPress roles/users).
3. **Whether uploaded files** should be deleted independently of (or
   alongside) their lead record, and after how long.
4. **A real privacy policy page** (WordPress ships a default "Privacy
   Policy" page — currently empty on this install) describing this
   collection/retention in plain language, linked from the site footer.
5. **Data subject requests** — a simple process for "delete my
   information" requests (currently: an admin manually deletes the
   `zeus_lead` post and its uploaded file via the admin UI).

## What's already handled

- Validation, sanitization, and file-type/size checks happen server-side
  regardless of client-side behavior (see
  `plugins/zeus-core/inc/consultation-form.php`).
- Nonce (CSRF) protection on every submission.
- A honeypot field and a submission-speed check for basic spam
  resistance without a third-party CAPTCHA service.
- Basic per-IP rate limiting (5 submissions/hour) without storing the
  IP itself.
