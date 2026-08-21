# Content Model

Principle from the brief: use normal WordPress Pages for major
service/landing pages; create structured content types only where they
earn their complexity. Do not create a CPT merely because WordPress
supports it.

## Custom Post Types

### 1. `cabinet_collection` (Cabinet Collections / Styles)

**Why a CPT:** four collections (Brooklyn, Shaker, Oslo, Euro/Flat Panel),
each with a repeating internal structure (finishes, gallery, description,
related countertops) that benefits from consistent fields and a shared
template, and each needs to be queried/related-to from Portfolio projects.

Fields (via ACF field-registration-in-code — see `TECHNICAL-ARCHITECTURE.md`):
- `intro_description` (rich text)
- `finishes` — relationship to `finish` taxonomy terms, each rendered with
  a swatch image
- `gallery` — array of attachment IDs (native media library, custom field,
  no ACF Pro dependency)
- `hero_image`
- `related_countertops` — relationship field to Countertop pages
- `seo_notes` (internal, not rendered) — target query, e.g. "walnut
  kitchen cabinets" for Oslo/Walnut

Taxonomy: `finish` (Fawn, Gray, Midnight, White, Pearl, Slate, Sand,
Kodiak, Moss, Oak, Walnut) — shared across collections since some finish
names could theoretically recur; each term stores a swatch image and a
back-reference to which collection(s) use it.

### 2. `project` (Portfolio)

**Why a CPT:** many structured, repeating fields, needs archive
filtering, and is the primary SEO/citability asset (indexable case
studies) — a CPT with taxonomies is the correct fit.

Fields:
- `location` (text, e.g. "Windermere, FL") — paired with `service_area`
  taxonomy for filtering/schema
- `project_description` (rich text)
- `design_decisions` (rich text — not a repeater; freeform prose reads
  better and avoids an ACF Pro dependency)
- `gallery` (array of attachment IDs, native media library)
- `before_after` (optional — two attachment IDs, before/after, only shown
  if both are present)
- `featured_image` (uses WP's native featured image)
- `project_status` — **required** select: `completed` or `concept`
  (3D design). Template must visibly label concepts as "3D Design
  Concept" — never render a concept as if it were a finished project.
  This is a hard content rule, not a style choice.
- `cta_variant` — optional override of the default consultation CTA copy

Taxonomies:
- `project_type` (Kitchen, Bathroom, Closet, Laundry & Pantry, Home
  Office) — maps to Custom Spaces / Cabinets nav sections
- `service_area` (Orlando, Windermere, Winter Garden, Horizon West,
  Clermont, Dr. Phillips) — drives local relevance and internal linking
- `cabinetry_style` — maps 1:1 to `cabinet_collection` CPT entries (kept
  as a taxonomy rather than a direct post relationship so projects can be
  archive-filtered by style natively)
- `countertop_material` — Quartz, Granite, Porcelain, Marble; links a
  project back to the corresponding Countertop page

Related services/collections and CTA render from the taxonomy
relationships above — no separate manual "related" field needed.

## Countertop Materials — decision: Pages, not a CPT

Only four materials (Quartz, Granite, Porcelain, Marble), each needs rich,
mostly unique long-form content (durability, maintenance, look, use
cases, comparison to the other three) rather than a repeating field
schema. A CPT would add taxonomy/template overhead for four items that
will each be hand-written and rarely added to. Standard Pages under
`/countertops/` with a shared template part for structural consistency
(comparison table, FAQ block) is the cleaner architecture. The
`countertop_material` taxonomy (used on `project`) is what links projects
back to these pages — the pages themselves stay simple Pages. Logged in
`DECISIONS.md`.

## Blog

Standard WordPress Posts. Standard `category`/`post_tag` taxonomies are
sufficient; no custom taxonomy needed unless a real content need appears.

## Reviews

No CPT for reviews at this stage. Reviews will be pulled from a verified
third-party source (Google) via a lightweight, honest integration — never
hand-authored fake review content, and never self-serving
`AggregateRating` markup that isn't backed by a real, verifiable feed. See
`SEO-STRATEGY.md`.
