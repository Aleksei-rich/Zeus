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
      hand-built, nonce/capability-checked meta boxes (no ACF) — now
      owned by the `zeus-core` plugin, see Phase 3.5
- [x] Native SEO plumbing (title override, meta description, Open Graph,
      Organization/BreadcrumbList/BlogPosting JSON-LD, noindex support)
      — `theme/zeus/inc/seo.php`, no SEO plugin
- [x] Built homepage template (`front-page.php`, all 13 sections)
- [x] Seeded the 4 real Cabinet Collections (Brooklyn, Shaker, Oslo,
      Euro/Flat Panel) with correct finishes and "Slim Shaker"
      terminology (Oslo, incl. Classic Walnut)
- [x] Built Cabinets hub + Kitchen/Bathroom service pages (seeded Pages)
- [x] Built Countertops hub + 4 material pages (seeded Pages, factual
      general material-property content + placeholder notes)
- [x] Built Custom Spaces hub + 3 service pages (seeded Pages)
- [x] Built Portfolio archive + single project template (honest empty
      state — zero fake projects seeded, by design)
- [x] Built Request Free Consultation page + homepage section, **and**
      its real backend (see Phase 3.5 — no longer a follow-up)
- [x] Built About, Contact (seeded Pages)
- [x] Blog set up (static front page + posts page pattern: `/` = Home,
      `/blog/` = Posts page)
- [x] Primary + Footer nav menus created and assigned to their theme
      locations, matching the site IA

## Phase 3 — SEO/perf/accessibility/homepage foundation (homepage done)

- [x] Homepage structural foundation built and self-QA'd
- [x] Structured data: Organization/BreadcrumbList/BlogPosting JSON-LD
      in place; `ImageObject`/richer Portfolio schema still deferred
      until real project content exists
- [x] Performance pass done (see Phase 3.5) — Core Web Vitals *field*
      data still needs real content/images first
- [x] Full WCAG 2.2 AA-oriented pass done (see Phase 3.5)
- [x] Mobile responsive QA done at true 320/375/768/1024/1440px widths
      (see Phase 3.5 for method — screenshot tooling on this machine
      proved unreliable; verified via direct DOM measurement instead)

## Phase 3.5 — Architecture hardening pass (done, 2026-08-21)

- [x] **Theme/plugin separation:** created `plugins/zeus-core/`, a
      first-party plugin now owning `cabinet_collection`/`project`/
      `zeus_lead` CPT registration, all 5 taxonomies, all
      `register_post_meta` definitions, editorial meta boxes + admin
      media pickers, content seeding, and the consultation form
      handler. Theme keeps only presentation. Linked into
      `wp-content/plugins/` via a Windows junction, same technique as
      the theme. Verified content survives a theme switch and plugin
      deactivation (raw DB rows confirmed intact either way) — see
      `DECISIONS.md`.
- [x] **Seeding safety audit — found and fixed a real bug:**
      `get_page_by_path()` silently fails for child-page slugs, which
      made the original seed-on-`init` code (and this plugin's first
      draft) duplicate 9 pages and every nav menu item on a second run.
      Fixed with a `zeus_get_post_by_slug()` helper (plain `post_name`
      lookup) and a permanent, append-only seed registry that also
      guarantees deleted content is never silently resurrected —
      verified with a real delete-then-reseed test. Seeding is no
      longer hooked to `init`/activation at all: it only runs via
      `wp zeus seed` (WP-CLI) or Tools → ZEUS Setup in wp-admin.
- [x] **Consultation form backend built and tested end-to-end:** nonce
      verification, honeypot + time-trap + per-IP rate limiting (no
      third-party CAPTCHA), server-side validation for every field,
      real MIME/extension + size checks on uploads, uploads stored in a
      private directory (not the public Media Library) with a random
      filename, admin-gated download, success redirect to `/thank-you/`,
      failure redirects preserve safe field values with accessible
      per-field error messages. Local dev logs notifications to a file
      instead of emailing (production uses `wp_mail()` unchanged — see
      `docs/PRIVACY-AND-DATA-RETENTION.md`). All 6 required test cases
      (valid, invalid email, missing field, invalid upload type,
      oversized upload, forged nonce) plus the honeypot path verified
      via real HTTP requests against the running site.
- [x] **Mobile QA + a11y fixes:** found and fixed a real horizontal-
      overflow bug (header CTA/logo/phone crammed into one non-wrapping
      row + a static hero font-size too large for 375px) — now zero
      overflow at 320–1440px, verified numerically via a true-width
      iframe (the extension's own `resize_window` and headless-Edge
      screenshots were both unreliable on this machine — documented in
      `DECISIONS.md` so this isn't re-discovered from scratch later).
      Found and fixed a real focus-management gap in the mobile nav
      drawer (✕ button didn't return focus; nothing contained Tab
      inside the open drawer) using `inert` on background content.
      Darkened the placeholder accent color for a safer contrast
      margin (4.53:1 → 5.57:1).
- [x] **Performance pass:** disabled WP core's emoji script (dead
      weight), confirmed zero webfonts / lean DOM (412 elements) / no
      third-party requests / correct native lazy-loading and
      `aspect-ratio` reservation for image slots. Hero LCP-loading
      strategy documented as a pre-launch item once a real hero image
      exists (see `SECURITY-AND-DEPLOYMENT.md`).
- [x] **SEO audit:** found and fixed a real bug — the site had **no
      meta description anywhere** (empty WP tagline + a silent-on-empty
      code path). Set a real tagline and hardened the fallback so this
      can't regress. Verified canonical/OG/schema/404/pagination/
      taxonomy-indexing all correct. `/wp-sitemap.xml` 404 confirmed as
      *expected* (WP core disables it while `blog_public=0`, which this
      local env deliberately has set). Recommendation logged in
      `DECISIONS.md` on when a dedicated SEO plugin might be worth it —
      not installed now.
- [x] Read-only old-site inventory completed — see
      `docs/OLD-SITE-INVENTORY.md`, `docs/REDIRECT-MAP-DRAFT.csv`,
      `docs/MEDIA-MIGRATION-INVENTORY.md`. Notable finding: sampled
      "catalog" images include a 3D render and third-party
      manufacturer-watermarked photography — confirms media must be
      reviewed image-by-image before any migration, never bulk-imported.
- [x] `docs/PRIVACY-AND-DATA-RETENTION.md` created for the lead-capture
      data collected by the new consultation form.

## Phase 4 — Real content, visual design & LOCAL RC1 (in progress, started 2026-08-21)

- [x] Real ZEUS brand identity adopted: logo (header/footer variants),
      favicon, navy/gold color tokens derived from the old site's own
      logo file — see `DECISIONS.md`
- [x] Real, verified contact info (phone, email, hours, social) wired
      into header/footer/Contact page, replacing dev placeholders
- [x] Physical address decision: NOT published (service-area business
      per the brief; a real address exists tied to the old Google
      Business Profile but publishing it as a showroom was out of scope
      for this pass — see `CONTENT-MIGRATION-PLAN.md`)
- [x] Old-site Portfolio ("Premier series"/"Builder series") fully
      investigated read-only; found to be marketing composites with
      unconfirmable project grouping — no Portfolio entries created
      (see `DECISIONS.md`, `CONTENT-MIGRATION-PLAN.md`)
- [x] 4 individually-verified-real photos salvaged from that same
      investigation for generic (non-attributed) use: homepage hero
      (eager-load + `fetchpriority="high"`, the LCP element), Kitchen
      Cabinets and About page featured images — see
      `ASSET-PROVENANCE.csv`
- [x] `docs/CONTENT-MIGRATION-PLAN.md` and `docs/ASSET-PROVENANCE.csv`
      created
- [x] Real, fresh copy written (replacing all `[Development
      placeholder]` text) for: homepage hero/Why-ZEUS/Reviews/Featured-
      Projects sections, Cabinets hub, Kitchen Cabinets, Bathroom
      Cabinets & Vanities, Custom Spaces hub, Custom Closets, Laundry &
      Pantry, Home Office, Countertops hub, Quartz, Granite, Porcelain,
      Marble, Brooklyn, Shaker, Oslo, Euro/Flat Panel, About, Contact
- [x] Reviews section rebuilt as honest architecture (no fabricated
      review text, no AggregateRating schema) pointing at the old
      site's real, verified Google Business Profile review feed
- [x] Unique SEO title + meta description set for all of the above pages
- [x] Real bug found+fixed: `zeus_seo_title` override was duplicating
      the site name in the `<title>` tag (document_title_parts filter
      only replaced one part) — see `DECISIONS.md`
- [x] Real bug found+fixed: collection/project cards with no image yet
      rendered a large empty gray placeholder box (fixed aspect-ratio
      wrapper always rendered) — now the wrapper only renders with an
      actual image
- [x] Real bug found+fixed: site title had a leftover
      "(Rebuild - Local Dev)" suffix bleeding into every page's
      `<title>` tag and the Organization schema's business `name` field
- [x] Portfolio archive and Blog empty-state templates, and the
      zeus-core seed source (`inc/seeding.php`), also had bracketed
      dev-placeholder text — fixed (seeding.php is create-only/registry-
      guarded so this couldn't affect the live DB, but a future reseed
      of a wiped install would have reproduced the old placeholder copy)
- [x] Full-repo sweep confirms zero remaining `[Development
      placeholder]`/TODO/lorem-ipsum markers in DB content or templates
- [ ] Cabinet Styles hub, remaining collection/service/countertop pages:
      spot-checked via curl + a sample of visual screenshots — not
      every single page individually screenshotted at all 3 breakpoints
- [ ] Full accessibility re-check with real content/images (contrast
      spot-checked during the color-token rename; not re-run as a full
      pass with a dedicated tool)
- [ ] Performance measurement (page weight, LCP) with real images now
      present — not yet run as a dedicated pass
- [x] **Known tooling limitation discovered:** the local headless Edge
      screenshot method (relied on since Phase 3 for visual QA) cannot
      reliably render viewports narrower than roughly 480–500px on this
      machine — `--window-size` below that floor is silently not
      honored for internal layout while the output PNG is still saved
      at the requested (smaller) size, producing false "content cut
      off" artifacts that are a tool bug, not a site bug. Confirmed via
      an in-page JS diagnostic (`document.body.scrollWidth ===
      document.documentElement.clientWidth` even at the smallest
      reachable width, and zero real overflowing elements). True
      375px-width visual confirmation on a real device/browser is still
      recommended before public launch even though code review found no
      structural mobile issues (flex-wrap present on CTA rows, grids
      collapse to 1 column, images are `max-width:100%`, no fixed px
      widths found). See `DECISIONS.md`.

## Phase 6 — Staging (not started, blocked on hosting access; renumbered from "Phase 4" now that Phase 4 covers real content/design)

- [ ] Confirm hosting/DNS access path
- [ ] Create `staging.zeuscabinetsflorida.com`

## Phase 7 — Production launch (not started, owner-approval required)

- [ ] Pre-launch checklist (see `SECURITY-AND-DEPLOYMENT.md`)
- [ ] Owner approval
- [ ] Launch

## Backlog / deferred, not blocking

- [ ] Confirm ACF Pro license status with owner (deferred, not blocking —
      see `DECISIONS.md`)
- [x] ~~Selective old-site content harvest~~ — read-only inventory done
      (`OLD-SITE-INVENTORY.md`, `REDIRECT-MAP-DRAFT.csv`,
      `MEDIA-MIGRATION-INVENTORY.md`); actual media/content harvest is
      still a separate future step, gated on owner decisions flagged in
      those docs (which old pages map where, which trashed portfolio
      entries — if any — should come back as real, honestly-labeled
      projects)
- [ ] Finalize data-retention policy + write a real Privacy Policy page
      before production (see `docs/PRIVACY-AND-DATA-RETENTION.md`)
- [ ] Decide sitemap/redirect-management approach (native vs. a plugin)
      once real migration scope is known (see `DECISIONS.md`)
- [x] ~~When real hero photography is added: implement eager-load +
      `fetchpriority="high"` for it (LCP element)~~ — done, Phase 4
- [ ] Pre-launch: verify the private lead-uploads directory is actually
      blocked from direct access on the real hosting environment (only
      indirectly verified locally — see `SECURITY-AND-DEPLOYMENT.md`)
- [ ] Review the 3 old Contact Form 7 forms ("Consult form", "Call
      order", "Calculation") for feature parity before considering the
      new consultation form's scope final
- [ ] Decide whether/how to use the old site's real address (7742
      Brofield Ave, Windermere, FL) in structured data, without implying
      a walk-in showroom — see `OLD-SITE-INVENTORY.md`. Phase 4 left
      this unpublished/service-area-only pending that owner decision.
- [ ] Get the owner's Google Business Profile connection details
      (Place ID / API key) to wire the real review feed into the
      homepage Reviews section (architecture is ready — see
      `CONTENT-MIGRATION-PLAN.md`)
- [ ] Real device/browser confirmation of true &lt;500px mobile
      rendering before public launch (local headless-Edge tooling can't
      reliably render below ~480–500px on this machine — see Phase 4)
