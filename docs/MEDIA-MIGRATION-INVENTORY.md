# Media Migration Inventory (Read-Only)

Read-only inventory of the old site's Media Library, for later
**selective** migration planning. No media has been imported into the
new site. Classification below is based on filename patterns and a
small number of directly-viewed sample images — not an exhaustive
per-image review. **Where uncertain, images are marked UNKNOWN rather
than guessed**, per instruction.

## Totals

211 attachments total, by year uploaded:

| Year | Count |
|---|---|
| 2024 | 145 |
| 2025 | 54 |
| 2026 | 12 |

## Classification framework used

Four categories, per instruction:

- **REAL COMPLETED PROJECT** — genuine photography of an actual
  finished ZEUS installation.
- **3D DESIGN / RENDER** — a computer-generated visualization, not a
  photograph.
- **PRODUCT / MANUFACTURER IMAGE** — stock, catalog, supplier, or
  licensed marketing photography representing a product line/collection
  in general, not a specific ZEUS customer project.
- **UNKNOWN** — insufficient evidence from filename or a quick visual
  check to classify confidently.

## Directly-verified samples (viewed, not just filename-guessed)

| File | Attached to | Classification | Evidence |
|---|---|---|---|
| `kitchen1-view1-1600.jpg` | `catalog` item context | **3D DESIGN / RENDER** | Clean CGI lighting/geometry, generic staged props — a rendering, not a photograph |
| `ciou-411-hall-13drp_1546.jpg` | (general uploads) | **PRODUCT / MANUFACTURER IMAGE** | Carries an "AS SEEN ON CELEBRITY IOU" watermark — licensed/syndicated marketing photography from a cabinet brand, not a ZEUS project photo |
| `kitchen-cabinet-distributors-brooklyn-gray-srva-2.jpg` | `catalog` item ("Brooklyn Grey") | **PRODUCT / MANUFACTURER IMAGE** | Filename names a third-party company ("Kitchen Cabinet Distributors") — a supplier/wholesaler's own product photography, despite looking like a real lived-in kitchen |
| `img_0260.jpg` | trashed `portfolio` post ("Builder series") | **REAL COMPLETED PROJECT** (moderate confidence) | Genuine install photo, natural staging/imperfections consistent with an actual finished job; attached to a portfolio (not catalog) entry |
| `brooklyn-grey-2.jpg` | trashed `portfolio` post ("Builder series") | **REAL COMPLETED PROJECT** (moderate confidence) | Same reasoning as above — real bathroom vanity install photo |

**Important finding:** at least two of the samples attached to
`catalog` (collection/finish) entries are demonstrably **not** ZEUS's
own work — one is a 3D render, one is watermarked third-party
manufacturer marketing photography from a named competitor/supplier
("Kitchen Cabinet Distributors"). This strongly suggests a meaningful
portion of the old site's catalog imagery is manufacturer/stock
photography used to illustrate collections generically, not real ZEUS
project photography. **This is exactly why media must not be bulk-
imported without per-image review** — doing so risks the new site
implying manufacturer stock photos are ZEUS's own completed work,
which the project's honesty requirements explicitly forbid.

## Pattern-based guidance for the remaining ~206 unreviewed files

Not individually verified — offered as a starting heuristic for whoever
does the full review, not a classification:

- Attached to a `catalog` post (collection/finish) → lean **PRODUCT /
  MANUFACTURER IMAGE**, verify each before use.
- Attached to a `portfolio` post (even trashed) → lean **REAL COMPLETED
  PROJECT**, still verify each — these are the best migration
  candidates once the owner confirms which trashed projects (if any)
  should come back as real, honestly-labeled portfolio entries.
- Filename contains "ComingSoon" (e.g. `ShakerMoss-galleryimages-
  ComingSoon.jpg`) → placeholder, not real content, skip.
- Filename matches a camera-default pattern (`IMG_####`, `photo_YYYY-
  MM-DD_...`) → plausibly a real on-site photo, subject/project still
  unconfirmed.
- Filename is a single generic word (`White.jpg`, `Oak.jpg`) or matches
  an ACF field name (`advantages_3.png`) → almost certainly a UI icon or
  finish swatch, not a project photo.
- Everything else → **UNKNOWN**.

## Recommendation

Before any real migration: have the owner (or someone with first-hand
project knowledge) do a pass over the `portfolio`-attached images
specifically — that's the highest-value, most-likely-genuine subset,
and small enough (a handful of trashed entries) to review by hand. Do
**not** bulk-import the `catalog`-attached images as project photography
under any circumstances, based on the confirmed findings above.
