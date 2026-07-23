# Milestone 7 — Portfolio Field Migration — Work Log

Status: **blocked on real Portfolio content**. No template or migration code has been written yet. This file is the persistent record of investigation, decisions, and open items across sessions.

## Background

Milestone 6 (commit `81bca0b`, tag `milestone-6-complete`) added the new Portfolio ACF field group (`acf-json/group_66b1efc8113da.json`) via Local JSON, shadowing the old DB-stored field group. Per that commit's own message: "No functions.php changes. No template changes. No database writes. No postmeta deleted or migrated." Old field postmeta (`image`, `color`, `gallery`, `in_home`) remains intact in the database but is no longer editable via wp-admin.

Milestone 7's job: migrate the theme templates that still read the old field names over to the new field model, and migrate existing Portfolio content so nothing goes blank in the process.

**Important scoping note:** all Milestone 1–6 work (CPT relabeling, taxonomies, ACF field group) exists only on branch `feature/architecture-v1.1`. None of it is merged into `main`. This repo's `README.md` itself states hosting/deployment was never confirmed at the time the theme was imported into version control. **Whether any of this repository's code is actually what's running on `zeuscabinetsflorida.com` has never been verified.**

## Old → new field mapping

| Old field (Portfolio CPT) | New field | Status |
|---|---|---|
| `image` | *(no direct replacement)* | Retire. Design intent (per the new field group's own instructions on `catalog_item`): image is inherited from the linked Catalog post's own `image` field, via the `catalog_item` relationship. Not yet implemented in templates. |
| `gallery` (repeater: `image` + `youtube_link` sub-fields) | `project_gallery` (native ACF gallery field, images only) | Migration required — structural change, not a rename. **YouTube video preservation is unresolved** — deferred to real usage data (see Open Decisions). |
| `color` | *(no direct replacement)* | Retire, same as `image` — inherited from linked Catalog post's `color` field via `catalog_item`. `countertop` (new, free text) is an independent addition, not a replacement for `color`. |
| `in_home` | `featured_home` | Direct rename, same boolean semantics. Confirmed actual field name is `featured_home`, **not** `featured_on_homepage`. |

New fields with no legacy equivalent (net-new, no migration needed): `city`, `project_year`, `countertop`, `project_highlights`, `related_service_page`.

## Files that reference Portfolio fields (from initial investigation)

- `template-portfolio.php` (lines ~70–110): reads old `image`, `gallery` (repeater) for the gallery grid.
- `template-home.php` (lines ~95–98, catalog CPT — **not in scope**; lines ~209, 216, 255, 269, portfolio CPT — **in scope**): reads old `in_home` (as a `meta_key` filter), `gallery`, `image`, `color`.
- `functions.php`: CPT/taxonomy registration only, no field-level changes needed for the migration itself.
- `single.php`: reads no ACF fields at all today (generic `the_title()` + `the_content()`, no `single-portfolio.php` exists). Out of scope for migration; only relevant if new-field display is added as a separate feature decision.
- Catalog CPT's own `image`/`gallery`/`color`/`tags` fields (used in `template-home.php`'s "Catalog of Cabinets" section) are a **separate, untouched concern** — same field names, different post type, must not be confused with Portfolio's fields.

## Migration strategy decided

- **One-time data migration script, not permanent template fallback.** Templates should only ever read the new field names once migration runs; no dual-read logic left standing long-term.
- Legacy postmeta (`image`, `gallery`, `color`, `in_home`) stays in the database after migration, inert/unused — not deleted as part of Milestone 7. Deletion is a separate, later, explicitly out-of-scope cleanup task.
- `catalog_item` linking cannot be done by a purely mechanical migration for every post — old `color` text has no guaranteed match to a Catalog post title. Needs a best-effort automated match with a manual-review list for anything not confident (exact strategy still pending real match-rate data).

## Open decisions — blocked on real data

1. **YouTube gallery video preservation** — drop entirely (matches Milestone 6's broader simplification), add a small supplementary field, or decide after seeing actual usage counts.
2. **Cover image source** — always inherit from linked Catalog item's image, prefer `project_gallery[0]` with Catalog image as fallback, or decide after a visual side-by-side sample review.
3. **`catalog_item` auto-match strategy** — auto-assign only high-confidence matches, no auto-matching at all (fully manual), or decide after seeing the actual match-rate numbers.

All three require inspecting real Portfolio content, which has not yet been available (see Audit status below).

## Audit tooling

Built `_milestone7-portfolio-audit.php` — a read-only script (no `update_post_meta`/`wp_update_post`/`delete_*` calls anywhere) that reads every Portfolio post's legacy and new field values and produces a CSV + JSON report. **This file is intentionally untracked in git** and stays that way — it's temporary tooling, not part of the theme, and is deleted once the audit is complete. Six other temporary diagnostic copies (`_milestone7-portfolio-audit-DIAG*.php`, `-EXACT-DIAG.php`) were used during debugging and are also untracked/temporary.

**Known issue, fixed:** the script initially appeared to hang indefinitely under `wp eval-file` in this Windows/LocalWP WP-CLI environment. Root cause, isolated via progressive instrumentation: plain `echo` output with no explicit flush was not reliably returning control to the shell (a stdio buffering/pipe issue specific to this environment, not a logic bug). Fixed by adding two explicit `fwrite(STDOUT, ...); flush();` calls — one at script start, one after the final summary output. No audit logic was changed to fix this.

## Audit results so far

- **LocalWP database: 0 Portfolio posts.** The `portfolio` CPT is registered correctly; there is no content to audit locally.
- **Production (`zeuscabinetsflorida.com`, via SSH + WP-CLI 2.6.0): also returned `total_posts: 0`** when run with `--path=/home/zeusiwpo/public_html`.
- Investigated whether this is a post-type-name mismatch: **ruled out** — `functions.php` on both `main` and `feature/architecture-v1.1` registers the CPT under the identical slug `portfolio`. Not the cause.
- Leading hypothesis now: **unverified whether `/home/zeusiwpo/public_html` is actually the live document root for `zeuscabinetsflorida.com`** (common shared-hosting/addon-domain gotcha), and/or **unverified whether the live site's real Projects/Portfolio content was ever migrated into this CPT at all** — it may still live in a different mechanism (regular Pages, a page builder, a different plugin) that predates this custom CPT structure.

## Next step (not yet executed — requires the user's SSH access)

Run these read-only WP-CLI commands on production to disambiguate:
```
wp option get siteurl --path=/home/zeusiwpo/public_html
wp theme list --path=/home/zeusiwpo/public_html
wp post-type list --path=/home/zeusiwpo/public_html --format=table
wp plugin list --status=active --path=/home/zeusiwpo/public_html
```
This will confirm whether the path resolves to the correct site, whether the active theme matches this repo, what post types actually exist, and what's actually active — pointing at where real Portfolio content lives if not in the `portfolio` CPT.

## Explicitly not done

No template or `functions.php` code has been changed. No migration script has been written. No database has been modified, locally or on production. No content has been imported or exported. No production writes of any kind.

## Hosting reference

Production: Namecheap Stellar Plus (shared hosting), cPanel. SSH confirmed working (user `zeusiwpo`, port `21098`, WordPress path `/home/zeusiwpo/public_html`). WP-CLI 2.6.0 confirmed installed. No staging site known to exist. Git remote is GitHub (`git@github.com:Aleksei-rich/Zeus.git`) — source control only, not a deployment mechanism; no CI/CD or deploy tooling found anywhere in the repo.
