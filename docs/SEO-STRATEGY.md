# SEO Strategy

SEO is architectural, implemented as pages are built — not retrofitted.

## Target queries (non-exhaustive, guides IA and copy — never stuffed)

kitchen cabinets Orlando · kitchen cabinets near Orlando · kitchen remodel
Orlando · cabinet installation Orlando · quartz countertops Orlando ·
granite countertops Orlando · slim shaker cabinets · walnut kitchen
cabinets · bathroom cabinets Orlando · custom closets Orlando · laundry
cabinets Orlando · home office cabinets Orlando

Each target query maps to exactly one page with a clear, singular intent
(see `SITE-ARCHITECTURE.md` page inventory). No two pages compete for the
same primary query.

## On-page mechanics (required on every template)

- Clean semantic URLs (see `SITE-ARCHITECTURE.md`).
- One H1 per page, logical H2/H3 hierarchy underneath — never skipped
  levels, never H1 used for anything but the page's true subject.
- Title tag: `Primary Keyword Phrase | Service/Location Detail | ZEUS
  Cabinets & Countertops` pattern, unique per page, under ~60 chars where
  possible.
- Meta description: unique, factual, includes a reason to click — no
  duplicate/boilerplate descriptions across pages.
- Internal linking: every collection/countertop/service page links to
  relevant Portfolio projects, and every project links back to its
  collection/countertop/service pages and its service-area page. This
  project↔location↔style link graph is the core of the site's topical
  relevance.
- Breadcrumbs on every page below the top level, matching `BreadcrumbList`
  schema.
- Canonical URL on every page (self-canonical by default; filtered
  Portfolio archive views canonical to the unfiltered archive).
- XML sitemap via the SEO plugin, verified to include all indexable
  templates and exclude the thank-you page and any filtered/duplicate
  views.
- Robots: standard allow for legitimate crawlers; `noindex` only on the
  thank-you page and any internal utility views. No blanket blocking of
  well-behaved AI/search crawlers — nothing here is a security concern.
- Open Graph + Twitter card tags on every page, with a real representative
  image (never a generic placeholder used sitewide if a better image
  exists).
- Image alt strategy: descriptive, factual alt text (material, style,
  room, location where genuinely relevant) — never keyword-stuffed alt
  text.

## Structured data (only where factually accurate)

- `Organization` — sitewide.
- `LocalBusiness` (or the closest accurate `HomeAndConstructionBusiness`
  subtype) — with real service areas, never a fabricated street-address
  storefront implying walk-in retail.
- `BreadcrumbList` — every page.
- `Article`/`BlogPosting` — blog posts.
- `ImageObject` — project galleries.
- `VideoObject` — only if/when real video content exists.
- `Product` — only considered for a collection page if it can genuinely
  satisfy Google's Product structured data requirements (price/offer
  reality, etc.); default is **not** to use `Product` markup for cabinet
  collections since they are not a single-SKU purchasable product. Revisit
  only if a concrete, compliant case emerges — log any change in
  `DECISIONS.md`.
- **Never** fabricate review/rating markup. `AggregateRating` only if
  backed by a real, verifiable, disclosed review source — not
  self-authored testimonials.

## AI search / citability

Write for clear extraction, not just ranking: direct factual answers near
the top of a section, descriptive (not clever) headings, concise
explanatory paragraphs, genuine comparisons (e.g., quartz vs. granite vs.
porcelain vs. marble), FAQs that answer real questions (not
keyword-shaped filler), concrete project facts (location, style, finish,
countertop), and consistent entity naming (always "ZEUS Cabinets &
Countertops," consistent collection/finish names) so the business is
easy for both search engines and AI answer engines to cite correctly.

## Local relevance

Every service area (Orlando, Windermere, Winter Garden, Horizon West,
Clermont, Dr. Phillips) should be reachable through real content — project
case studies tagged with `service_area`, not thin
one-per-city doorway pages. The Portfolio's project↔location relationship
is the mechanism, not a separate stack of near-duplicate "cabinets in
[city]" pages.

## Migration / launch requirement

Before production launch, a full old-URL → new-URL 301 redirect map is
required (see `SECURITY-AND-DEPLOYMENT.md`). This is a launch-phase task,
not a Phase 0 task, but the URL structure being built now must be
finalized with this migration in mind — avoid designing URLs that will
need to change again right before launch.
