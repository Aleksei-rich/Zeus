# Rule: SEO

- Every new page/template gets: one clear intent, correct H1–H3
  hierarchy, unique title + meta description, canonical URL,
  breadcrumbs, and appropriate internal links to related
  collection/countertop/service/project pages.
- No keyword stuffing. Write for the reader first; target queries guide
  topic/IA choices, not literal repeated phrasing (see
  `docs/SEO-STRATEGY.md`).
- Structured data only where factually true. Never fabricate
  `AggregateRating`/review markup. Never mark a design concept up as a
  real completed project.
- Never imply a walk-in showroom exists in copy or schema
  (`LocalBusiness` markup must reflect the real service-area business
  model, not a storefront).
- Don't block legitimate search or AI crawlers via `robots.txt`/meta
  robots unless there's an actual security reason — `noindex` is reserved
  for genuinely non-public/utility pages (e.g., the thank-you page,
  staging).
