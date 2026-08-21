# Technical Architecture

## Stack

- **CMS:** WordPress, latest stable core, no core modifications.
- **PHP:** 8.2+ (matches the PHP 8.2.29 bundled with the LocalWP
  installation found on this machine — see `HANDOFF.md`).
- **Database:** MySQL/MariaDB, standard WordPress schema.
- **Theme:** custom-built block theme (`theme.json`-driven global styles +
  block templates/template parts + block patterns). Not a multipurpose
  theme, not Elementor-dependent. Rationale: full control over markup for
  performance/accessibility/SEO, and the brief explicitly calls for a
  "lightweight custom/block-based theme."
- **Structured content:** ACF (Advanced Custom Fields), fields registered
  in PHP (`acf_add_local_field_group`), not via the ACF UI/JSON export —
  this works identically on free ACF and ACF Pro, so it does not create a
  hidden dependency on a Pro license, and it's version-controllable by
  nature (it's just code). Two fields conceptually want a "repeater"
  (finishes list, gallery) — implemented instead as: `finish` taxonomy
  relationship (not a repeater) and a native-media-library-backed gallery
  meta field (custom, no ACF Pro `gallery`/`repeater` field type
  required). This keeps the project functional on free ACF; if the owner
  confirms an active ACF Pro license, the same field registrations can
  optionally switch to native ACF Pro field types later as a
  non-breaking enhancement. See `DECISIONS.md`.
- **SEO plugin:** Yoast SEO (widely supported, good sitemap/schema
  plumbing) as the base layer; page-specific structured data that Yoast
  doesn't cover is hand-built via `wp_head` hooks, output as JSON-LD.
- **Forms:** Contact Form 7 or a lightweight native/custom handler for
  "Request Free Consultation" — decision deferred to implementation phase;
  either way, submissions must nonce-check, sanitize, and redirect to a
  real thank-you URL for analytics tracking. Lean toward a small custom
  form handler over a heavy form-builder plugin to minimize dependencies,
  unless Contact Form 7 + a redirect add-on proves clearly faster to ship
  safely.
- **JS:** vanilla JS / small web components only, no framework. No jQuery
  for new code (WordPress core still loads it for wp-admin; new frontend
  code does not depend on it).
- **Images:** WordPress core responsive images (`srcset`/`sizes`) +
  WebP/AVIF via core's image editor support or a lean optimization plugin
  if core support proves insufficient — decide when real images exist.

## Environment layering

1. **Local** — this machine, for active development (see `HANDOFF.md`).
2. **Staging** — `staging.zeuscabinetsflorida.com`, password-protected,
   `noindex`, created later once hosting access is confirmed (see
   `SECURITY-AND-DEPLOYMENT.md`). Not created yet.
3. **Production** — `zeuscabinetsflorida.com`, untouched until explicit
   launch approval.

## Plugin policy

Every plugin must be justified in `DECISIONS.md` before being added.
Default toward native WordPress functionality first. Candidate plugins
identified so far: Yoast SEO, ACF, a forms solution, and an
image-optimization plugin if core proves insufficient. No page builder.
No bloat/multipurpose plugins.

## Repository layout (this repo)

```
zeus-rebuild/
  CLAUDE.md
  docs/                  ← this documentation set
  .claude/rules/         ← safety/workflow rules for Claude Code sessions
  theme/                 ← custom WordPress theme source (created once
                            theme development begins)
  tools/                 ← local dev helper scripts (wp-cli wrapper, etc.)
```

WordPress core itself is never vendored into this repository (see
`.gitignore`) — only the custom theme (and any custom plugin code, if a
small custom plugin proves necessary) is version-controlled here.
