# Decisions Log

Newest entries at the top. Each entry: date, decision, reasoning, and
whether it was an autonomous professional-default call or an
owner-directed one.

---

## 2026-08-21 — Phase 2: no plugins installed for the content-model/theme foundation; native WordPress only (supersedes the earlier ACF-in-code plan)

**Decision:** The `cabinet_collection` and `project` CPTs, their
taxonomies, and all structured fields are built using only native
WordPress APIs — `register_post_type`, `register_taxonomy`,
`register_post_meta`, and hand-built capability/nonce-checked meta boxes
for editorial input. **No plugin is installed** for this (not ACF free,
not ACF Pro). SEO plugin (Yoast or otherwise) and a forms plugin are
likewise **not installed** in Phase 2 — canonical URLs, title tags, and
basic Open Graph/JSON-LD are implemented directly in the theme via
`wp_head`; the consultation form ships as structurally correct, accessible
markup without a plugin-driven submission handler yet.
**Reasoning:** The current instruction is explicit: the custom theme and
content model are the foundation, plugins are supporting tools, and no
plugin should be installed merely because the old site used it or "to
have it just in case." ACF Pro's license status for this project is
still unverified (see the earlier 2026-08-21 ACF entry, which this
supersedes rather than deletes — the reasoning there was correct at the
time but a plugin-free approach is strictly safer and was explicitly
requested now). Native `register_post_meta` plus custom meta boxes fully
covers the fields this phase needs (scalars, taxonomy relationships,
attachment-ID arrays for galleries) without a repeater/gallery field type
dependency. This can be revisited — e.g., swapping hand-built meta boxes
for ACF Pro fields — later, only if editorial UX genuinely demands it and
a valid license is confirmed; the underlying meta keys are designed so
that swap would be non-breaking.
**Type:** Autonomous professional default, directly implementing the
owner's explicit "architecture priority" instruction.

## 2026-08-21 — Standalone local WordPress environment built from LocalWP's bundled binaries, bypassing the Local GUI entirely

**Decision:** Built a clean local WordPress dev environment using PHP
8.2.29, MariaDB 10.6.23, and WP-CLI — all found bundled inside the
LocalWP installation — run directly by full path under this project's
`.localenv/` directory, without creating or touching any LocalWP "site"
through the app's GUI or its `sites.json` registry. Fresh WordPress core
downloaded from wordpress.org (not the old site). Site runs at
`http://localhost:8890` via PHP's built-in dev server; MariaDB runs on
`127.0.0.1:3307`. See `HANDOFF.md` for full details and credential
locations.
**Reasoning:** The brief's LocalWP step says to prepare a clean local
WordPress environment "if it can safely be used without requiring owner
interaction," and to stop for exactly one GUI click only if a GUI
interaction is genuinely required. Local's standard site-creation flow
is GUI-only (no documented headless API), but its bundled PHP/MariaDB/
WP-CLI binaries can be run standalone, achieving the same result — a
clean local WP environment — with zero owner interaction and zero risk
to Local's own site registry or the existing "zeus" reference site.
**Type:** Autonomous professional default, in direct service of the
brief's explicit "minimize owner involvement" instruction.

## 2026-08-21 — Countertop Materials modeled as Pages, not a CPT

**Decision:** Quartz, Granite, Porcelain, Marble are standard WordPress
Pages under `/countertops/`, not a custom post type.
**Reasoning:** Only four items, each needing rich, mostly unique long-form
content rather than a repeating field schema; a CPT would add taxonomy/
template overhead disproportionate to four hand-written pages. A
`countertop_material` taxonomy on the `project` CPT links portfolio
projects back to these pages instead.
**Type:** Autonomous professional default (explicitly delegated by the
brief: "evaluate whether Countertop Materials should be a structured
content type or standard pages and choose the cleaner architecture").

## 2026-08-21 — ACF fields registered in code; ACF Pro license unverified

**Decision:** Structured fields (Cabinet Collections, Portfolio Projects)
are registered via ACF's PHP field-registration API, not the ACF UI. Ave
gallery/finishes needs are met without ACF Pro-only field types (native
media-library-backed gallery meta, `finish` taxonomy instead of a
repeater), so the build does not depend on an unverified license.
**Reasoning:** A copy of `advanced-custom-fields-pro` was found in the old
site's local plugin directory (see `HANDOFF.md`), which suggests the
owner has purchased ACF Pro at some point, but license validity for *this*
project has not been confirmed, and the brief explicitly says not to
assume license status. Registering fields in code means the project works
identically today on free ACF and can adopt ACF Pro field types later as a
non-breaking enhancement once confirmed.
**Type:** Autonomous professional default, deferring (not blocking on) a
fact only the owner can confirm.

## 2026-08-21 — New local WordPress environment will not reuse the existing "zeus" LocalWP site

**Decision:** The pre-existing `Local Sites\zeus` LocalWP environment on
this machine is a mirror of the **old** production codebase (its theme
folder has its own independent `.git` history, ACF Pro, Yoast, UpdraftPlus,
Contact Form 7, etc. already installed) and will not be used, modified, or
built upon for this project. A separate, clean local WordPress environment
will be created for the rebuild.
**Reasoning:** Explicit brief requirement: "Do not import the old
production site as the foundation. The new WordPress installation must be
clean." Reusing or modifying the existing Local site risks contaminating
what may be the owner's reference copy of the live site.
**Type:** Autonomous professional default, following an explicit brief
rule.

## 2026-08-21 — Git identity scoped to this repository only

**Decision:** Set `user.name`/`user.email` via local (repo-level, not
`--global`) git config for commit authorship, using the owner's known
email for attribution.
**Reasoning:** No global git identity was configured on this machine.
Setting it globally would change git behavior outside this project;
scoping it to the repo avoids that side effect while still allowing
commits.
**Type:** Autonomous professional default.

## 2026-08-21 — Phase 0 project control system initialized

**Decision:** Created local git repo on branch `rebuild/v2`, no remote
connected yet; created `CLAUDE.md`, the `docs/` set, and `.claude/rules/`.
**Reasoning:** Explicit Phase 0 instruction from the owner's brief.
**Type:** Owner-directed (explicit instruction).
