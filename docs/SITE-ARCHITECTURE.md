# Site Architecture

## Primary navigation

```
Home
Cabinets
  Kitchen Cabinets
  Bathroom Cabinets & Vanities
  Cabinet Styles
    Brooklyn
    Shaker
    Oslo
    Euro / Flat Panel
Countertops
  Quartz
  Granite
  Porcelain
  Marble
Custom Spaces
  Closets
  Laundry & Pantry
  Home Office
Portfolio
Blog
About
Contact
[Request Free Consultation] — primary CTA, always visible
```

## Cabinet collection finishes (content detail, not nav items)

- **Brooklyn:** Fawn, Gray, Midnight, White, Pearl, Slate
- **Shaker:** White, Sand, Kodiak, Moss
- **Oslo:** White, Oak, Walnut

Finishes render as a swatch/selection UI on each collection page, not as
separate URLs, to avoid thin/duplicate pages (see `SEO-STRATEGY.md`).

### Important SEO/product concept — "Slim Shaker"

**OSLO Classic Walnut Slim Shaker Kitchen Cabinets.** "Slim Shaker" is the
correct, natural English term for the Oslo profile — a narrower-rail
Shaker-style door. It is a distinct product concept from traditional
Shaker (the separate Shaker collection: White, Sand, Kodiak, Moss). Copy
and metadata must never conflate the two; the Oslo collection page and its
Walnut finish page are the primary target for this term.

## Homepage structure (in order)

1. Hero
2. Trust / key value proposition
3. Main services
4. Featured cabinet collections
5. Countertops
6. How the process works
7. Featured real projects
8. Why ZEUS
9. Real customer reviews
10. Service area
11. FAQ
12. Request Free Consultation form
13. Footer

## Page inventory (Phase 0 planning list)

| Page | Type | Notes |
|---|---|---|
| Home | Page (custom front-page template) | See structure above |
| Cabinets (hub) | Page | Links to Kitchen, Bathroom, Styles |
| Kitchen Cabinets | Page | Service page, targets "kitchen cabinets Orlando" |
| Bathroom Cabinets & Vanities | Page | Service page, targets "bathroom cabinets Orlando" |
| Cabinet Styles (hub) | Page | Archive-style hub linking to 4 collections |
| Brooklyn / Shaker / Oslo / Euro-Flat Panel | `cabinet_collection` CPT | See `CONTENT-MODEL.md` |
| Countertops (hub) | Page | Links to 4 materials |
| Quartz / Granite / Porcelain / Marble | Page | See `CONTENT-MODEL.md` for why these are Pages, not a CPT |
| Custom Spaces (hub) | Page | Links to Closets, Laundry & Pantry, Home Office |
| Closets / Laundry & Pantry / Home Office | Page | Service pages |
| Portfolio (hub) | `project` CPT archive | Filterable by type/style/area |
| Individual project | `project` CPT single | See `CONTENT-MODEL.md` |
| Blog (hub) | Post archive | Standard WP posts |
| Blog post | Post | Standard WP posts |
| About | Page | Company credibility, no fabricated claims |
| Contact | Page | Contact form + service-area info, map is illustrative only (no walk-in implication) |
| Request Free Consultation | Page (form) | Primary conversion page, also embedded on homepage |
| Thank You | Page | Conversion-tracking landing page, `noindex` |

## URL structure principles

- Clean, semantic, human-readable slugs (`/cabinets/kitchen-cabinets/`,
  `/cabinet-styles/oslo/`, `/countertops/quartz/`,
  `/portfolio/oslo-walnut-kitchen-windermere/`).
- One clear intent per URL — no near-duplicate pages targeting the same
  query.
- Breadcrumbs mirror the URL hierarchy.
- No parameter-based URLs indexed (filtering on the Portfolio archive uses
  query params but those are excluded from indexing via canonical + robots
  rules — see `SEO-STRATEGY.md`).
