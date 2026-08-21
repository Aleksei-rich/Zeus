# Handoff / Environment State

Snapshot of machine/tooling state, kept current so any session (or the
owner) can pick up context fast. Update this whenever the local
environment picture changes.

## LocalWP

**Installed.** `Local.exe` found at
`%LOCALAPPDATA%\Programs\Local\Local.exe`.

Bundled inside Local's install directory (usable directly, without going
through Local's GUI):
- PHP 8.2.29 —
  `%LOCALAPPDATA%\Programs\Local\resources\extraResources\lightning-services\php-8.2.29+0\`
- MariaDB 10.6.23 and MySQL 8.4.0 —
  `...\lightning-services\mariadb-10.6.23+0\` /
  `...\lightning-services\mysql-8.4.0\`
- WP-CLI (`wp-cli.phar`) —
  `%LOCALAPPDATA%\Programs\Local\resources\extraResources\bin\wp-cli\`

No standalone/headless "Local CLI" for scripted site creation was found;
Local's own site registry (`%APPDATA%\Local\sites.json`) is managed by the
Electron app and creating a new site through it normally requires a GUI
action (Local doesn't ship a documented safe headless API for this).

### Existing "zeus" LocalWP site — do not use for this project

`C:\Users\aleks\Local Sites\zeus\` already exists on this machine. It is
**not** a clean install and **not** this project's environment — it is a
local mirror of the old/current production codebase:

- `wp-content/themes/zeus/` — has its own independent `.git` repository,
  an `acf-json/` folder, and files named `about.html`/`contacts.html`
  under `assets/`, consistent with an export/import of the live theme.
- `wp-content/plugins/` includes: `advanced-custom-fields-pro`,
  `contact-form-7`, `drag-and-drop-multiple-file-upload-contact-form-7`,
  `cf7-phone-mask-field`, `classic-editor`, `cyr2lat`, `post-duplicator`,
  `regenerate-thumbnails`, `safe-svg`, `updraftplus`,
  `widget-google-reviews`, `wordpress-seo` — a real, in-use plugin set,
  not a fresh-install default.
- `wp-config.php` DB name is `local` (LocalWP's default local DB name —
  not connected to the real production database from this machine).

**Decision (logged in `DECISIONS.md`):** this site is left completely
untouched. It's a useful **read-only reference** for the later "selective
old-site inspection" step (real project photos, reviews, existing URLs
for the 301 map) — not a base to build on, per the explicit brief
requirement that the new install be clean.

## This project's local WordPress environment — live

A clean, standalone local WordPress install now runs for this project,
built entirely from LocalWP's bundled PHP/MariaDB/WP-CLI binaries, without
touching Local's GUI or its site registry. Fresh WordPress core, no old-
site import.

- **Site:** http://localhost:8890
- **Admin:** http://localhost:8890/wp-admin — user `zeus_admin`, password
  in `.localenv/wp-admin-password.txt` (gitignored, not reproduced here)
- **Database:** MariaDB 10.6.23 on `127.0.0.1:3307`, db `zeus_rebuild`,
  user `zeus_dev`, password in `.localenv/db-password.txt` (gitignored)
- **Files:** WordPress core + `wp-content` under `.localenv/wordpress/`
  (gitignored — not vendored into the repo; only the custom theme under
  `theme/` will be version-controlled once theme work starts)
- **Config:** `.localenv/php.ini` — the bundled PHP ships with no `php.ini`
  and no extensions enabled by default; this file enables curl, openssl,
  mysqli, pdo_mysql, mbstring, gd, fileinfo, zip, exif, intl
- **Start/stop:** `tools/start-local-env.ps1` and
  `tools/stop-local-env.ps1` (PowerShell) start/stop both the MariaDB
  instance and the PHP built-in dev server. Nothing here is a permanent
  Windows service — run the start script each time local dev work begins.
- **State:** fresh install, default `twentytwentyfive` theme active
  (custom theme not built yet — Phase 2), only default core plugins
  present (Akismet, Hello — both inactive), permalinks set to
  `/%postname%/`, `blog_public` set to 0 (discourage indexing of this
  local instance).

Known quirk hit and worked around: WP-CLI's `core download` failed on
Windows because current WordPress core ships a deeply nested
`wp-includes/php-ai-client/...` path, and wp-cli's tarball extraction
routes through a long auto-generated temp folder name that pushed the
combined path over Windows' path-length limit. Worked around by
downloading `latest.zip` directly and extracting straight to the short
`.localenv/wordpress/` destination instead.

## Other dev tooling

Not found on system `PATH`: `php`, `mysql`, `wp`, `docker`, `node`,
`composer`. (Expected — PHP/MySQL/WP-CLI are used directly from LocalWP's
bundled binaries by full path, as above, rather than a global install.)

## Git

No global git identity was configured on this machine
(`git config --global user.name/user.email` were both empty). This
repository's identity is set at the repo level only (see `DECISIONS.md`)
to avoid changing global git behavior outside this project. No remote is
connected yet — connect only once a working, credential-free-to-this-
session remote is confirmed, or the owner provides one.

## Owner's real production site

`zeuscabinetsflorida.com` — untouched, not accessed, not modified this
session. No credentials for it were used or required for Phase 0.
