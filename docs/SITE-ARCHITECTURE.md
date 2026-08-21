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

## Final URL map (Phase 2)

```
/                                          Home (front-page.php)
/cabinets/                                 Page — hub
/cabinets/kitchen-cabinets/                Page — service
/cabinets/bathroom-cabinets-vanities/      Page — service
/cabinet-styles/                           cabinet_collection archive — hub
/cabinet-styles/brooklyn/                  cabinet_collection single
/cabinet-styles/shaker/                    cabinet_collection single
/cabinet-styles/oslo/                      cabinet_collection single (incl. Walnut "Slim Shaker")
/cabinet-styles/euro-flat-panel/           cabinet_collection single
/countertops/                              Page — hub
/countertops/quartz/                       Page
/countertops/granite/                      Page
/countertops/porcelain/                    Page
/countertops/marble/                       Page
/custom-spaces/                            Page — hub
/custom-spaces/closets/                    Page
/custom-spaces/laundry-pantry/             Page
/custom-spaces/home-office/                Page
/portfolio/                                project archive — hub, filterable
/portfolio/{project-slug}/                 project single
/blog/                                     Posts page (static front page + posts page pattern)
/blog/{post-slug}/                         Post
/about/                                    Page
/contact/                                  Page
/consultation/                             Page — Request Free Consultation form
/thank-you/                                Page — conversion landing, noindex
```

No collisions between top-level sections: `cabinets` (services) and
`cabinet-styles` (collections) are deliberately distinct roots so a
service page and a collection page never compete for the same URL or
query. `portfolio` and `blog` are separate roots. Future service-area
content lives inside `portfolio` project entries (via the `service_area`
taxonomy) rather than as new top-level `/areas/{city}/` pages, avoiding
future collision risk — see `SEO-STRATEGY.md` on avoiding thin
doorway pages.

Taxonomy archives (`finish`, `project_type`, `service_area`,
`cabinetry_style`, `countertop_material`) are registered as
non-public-facing (used for internal filtering/relationships only, not
separate crawlable archive URLs) — the canonical, indexable page for each
concept is the single Page/CPT entry above (e.g. `countertop_material:
quartz` relates back to `/countertops/quartz/`, not a separate taxonomy
archive URL), which avoids duplicate/thin content competing with the main
pages.
