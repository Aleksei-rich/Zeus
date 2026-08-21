# Tasks

Live task list. Update continuously — do not let this drift from reality.
Status values: `todo`, `in-progress`, `done`, `blocked`.

## Phase 0 — Project control system

- [x] Initialize local git repo, branch `rebuild/v2`
- [x] Create `CLAUDE.md`
- [x] Create `docs/PROJECT-SPEC.md`
- [x] Create `docs/SITE-ARCHITECTURE.md`
- [x] Create `docs/DESIGN-SYSTEM.md`
- [x] Create `docs/SEO-STRATEGY.md`
- [x] Create `docs/CONTENT-MODEL.md`
- [x] Create `docs/TECHNICAL-ARCHITECTURE.md`
- [x] Create `docs/SECURITY-AND-DEPLOYMENT.md`
- [x] Create `docs/DECISIONS.md`
- [x] Create `docs/TASKS.md` (this file)
- [x] Create `docs/HANDOFF.md`
- [x] Create `.claude/rules/` safety rule set
- [x] Commit Phase 0 milestone
- [x] Local WordPress readiness assessment (LocalWP detected — see
      `HANDOFF.md`; existing "zeus" site is old-site mirror, not reusable)
- [ ] Stand up a clean local WordPress environment for this project
      (in progress — see Phase 1)

## Phase 1 — Local environment (in progress)

- [x] Decided: standalone environment using Local's bundled PHP 8.2.29 +
      MariaDB 10.6.23 + WP-CLI, run directly (no Local GUI, no site
      registry changes) — fully autonomous, no owner interaction needed.
      Lives under `.localenv/` (gitignored: DB data, downloaded core,
      credentials).
- [x] MariaDB instance initialized and running on 127.0.0.1:3307
      (isolated from Local's own instances/sites)
- [x] `zeus_rebuild` database + scoped `zeus_dev` user created (random
      generated password, stored only in `.localenv/db-password.txt`,
      gitignored, never committed or displayed in full)
- [x] Custom `.localenv/php.ini` written (bundled PHP ships with no ini
      and no enabled extensions by default — enabled curl, openssl,
      mysqli, pdo_mysql, mbstring, gd, fileinfo, zip, exif, intl)
- [x] WordPress core downloaded and extracted to `.localenv/wordpress/`
      (worked around a WP-CLI `core download` failure on Windows: current
      WP core ships a deeply nested `wp-includes/php-ai-client/...` path,
      and wp-cli's tarball extraction goes through a long auto-generated
      temp directory name that pushes the combined path over Windows'
      path-length limit. Fixed by downloading `latest.zip` directly and
      extracting straight to the short `.localenv/wordpress/` path.)
- [x] Fresh WordPress core install, no old-site import (`wp core install`
      completed; site title, admin user `zeus_admin`, permalinks set to
      `/%postname%/`, `blog_public` disabled)
- [x] Verified end-to-end: PHP built-in server serves the site
      (`http://localhost:8890` → HTTP 200, correct title;
      `/wp-admin/` → HTTP 302 redirect to login, as expected)
- [x] Start/stop tooling: `tools/start-local-env.ps1` /
      `tools/stop-local-env.ps1`
- [x] Theme directory symlinked (Windows junction) from
      `.localenv/wordpress/wp-content/themes/zeus` → `theme/zeus/` so
      edits to the git-tracked theme apply live with no copy step
- [x] Plugin decision: **no plugins installed** for the content-model/
      theme foundation (not ACF, not Yoast, not a forms plugin) — native
      WordPress only. See `DECISIONS.md`.
- [x] Custom theme scaffolded (`theme.json`, classic template hierarchy,
      `inc/`, `template-parts/`, `components/`, `patterns/`, `assets/`)

## Phase 2 — Theme & content model build (done)

- [x] Registered `cabinet_collection` CPT + `finish` taxonomy
- [x] Registered `project` CPT + `project_type`/`service_area`/
      `cabinetry_style`/`countertop_material` taxonomies (all
      non-public/rewrite-disabled — internal filtering only, see
      `SITE-ARCHITECTURE.md` URL map)
- [x] Structured fields implemented via native `register_post_meta` +
      hand-built, nonce/capability-checked meta boxes (no ACF) —
      `theme/zeus/inc/meta-fields.php`
- [x] Native SEO plumbing (title override, meta description, Open Graph,
      Organization/LocalBusiness/BreadcrumbList/BlogPosting JSON-LD,
      noindex support) — `theme/zeus/inc/seo.php`, no SEO plugin
- [x] Built homepage template (`front-page.php`, all 13 sections) — see
      Phase 3 below, done together
- [x] Seeded the 4 real Cabinet Collections (Brooklyn, Shaker, Oslo,
      Euro/Flat Panel) with correct finishes and "Slim Shaker"
      terminology (Oslo, incl. Classic Walnut) —
      `theme/zeus/inc/post-types.php`
- [x] Built Cabinets hub + Kitchen/Bathroom service pages (seeded Pages)
- [x] Built Countertops hub + 4 material pages (seeded Pages, factual
      general material-property content + placeholder notes)
- [x] Built Custom Spaces hub + 3 service pages (seeded Pages)
- [x] Built Portfolio archive + single project template (honest empty
      state — zero fake projects seeded, by design)
- [x] Built Request Free Consultation page + homepage section —
      structural, accessible markup only; submission handling is a
      **tracked follow-up**, not built yet (no form plugin used)
- [x] Built About, Contact (seeded Pages)
- [x] Blog set up (static front page + posts page pattern: `/` = Home,
      `/blog/` = Posts page)
- [x] Primary + Footer nav menus created and assigned to their theme
      locations, matching the site IA
- [ ] **Follow-up (not yet built):** native Request Free Consultation
      form submission handler + Thank You page conversion tracking hook
      (the Thank You page itself exists and is `noindex`, but nothing
      currently POSTs to it)

## Phase 3 — SEO/perf/accessibility/homepage foundation (homepage done; hardening pass not started)

- [x] Homepage structural foundation built and self-QA'd: semantic
      heading hierarchy (single H1, no skipped levels), skip link,
      keyboard-accessible nav incl. `:focus-within` submenus (verified
      via real Tab-key interaction in Chrome), native `<details>` FAQ
      (zero JS), mobile nav drawer verified via interaction (open/close,
      `aria-expanded`, focus/scroll-lock, Escape-to-close all confirmed),
      persistent mobile conversion bar, no console errors, no fabricated
      reviews/projects/stats/certifications anywhere
- [ ] Structured data implementation is in place for Organization/
      LocalBusiness/BreadcrumbList/BlogPosting (done); `ImageObject` /
      richer Portfolio schema deferred until real project content exists
- [ ] Core Web Vitals pass (needs real images/content first — hero
      currently has no image, so LCP/CLS tuning is premature)
- [ ] Full WCAG 2.2 AA pass (spot-checked during build; a dedicated audit
      pass is still open)
- [ ] Live responsive-viewport screenshot verification was attempted via
      Chrome automation but the sandboxed browser window would not
      resize below its default size — mobile CSS breakpoints were
      verified by code review and the mobile nav's JS was verified
      working via direct interaction, but a true narrow-viewport visual
      check is still outstanding

## Phase 4 — Staging (not started, blocked on hosting access)

- [ ] Confirm hosting/DNS access path
- [ ] Create `staging.zeuscabinetsflorida.com`

## Phase 5 — Production launch (not started, owner-approval required)

- [ ] Pre-launch checklist (see `SECURITY-AND-DEPLOYMENT.md`)
- [ ] Owner approval
- [ ] Launch

## Backlog / deferred, not blocking

- [ ] Confirm ACF Pro license status with owner (deferred, not blocking —
      see `DECISIONS.md`)
- [ ] Selective old-site content harvest (photos, reviews, URLs) — later,
      read-only inspection only
