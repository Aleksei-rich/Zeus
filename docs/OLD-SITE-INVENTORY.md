# Old Site Inventory (Read-Only)

Inventory of the existing production site's content and configuration,
gathered strictly read-only for later selective migration and redirect
planning. **No old code or content was copied into the rebuild.** The
old site was never modified — see "How this was gathered" at the
bottom for the safety method used.

Source: the local mirror of the live site at `Local Sites\zeus` on this
machine (see `HANDOFF.md`). Live domain confirmed from data:
`zeuscabinetsflorida.com`. Snapshot date: 2026-08-21.

## Real business contact information found

- **Email:** sales@zeuscabinetsflorida.com
- **Phone:** +1 689 222 3077
- **Address (as stored in the old site's options / Google Reviews feed
  config):** 7742 Brofield Ave, Windermere, FL 34786
- **Hours:** Monday–Friday, 9:00 AM – 7:00 PM
- **Facebook:** facebook.com/profile.php?id=100083163667410
- **Instagram:** @zeus.cabinets

**Note for the owner:** the old site had a real street address on file
(tied to its Google Business listing — see "Review integration"
below). The new site's brief is explicit that ZEUS should not present a
walk-in showroom. An address can still be used correctly in
`LocalBusiness`-style structured data for a service-area business without
implying walk-in retail — many service businesses do this — but whether/
how to surface this address anywhere on the new site is a decision for
the owner, not made here.

## Existing pages (old site)

| Old title | Old slug | Status |
|---|---|---|
| Home | `kitchen-cabinets-orlando-zeus-cabinets-countertops` | published (front page) |
| Frequently asked Questions | `faq` | published |
| About company | `about-company` | published |
| Portfolio | `portfolio` | published (hub page; see Portfolio CPT below for actual entries) |
| Contacts | `contacts` | published |
| Online order | `online-order` | published |
| Feedback | `feedback` | published |
| Privacy Policy | `privacy-policy` | published |
| Articles | — | **draft**, not live |
| Portfolio Copy | `portfolio-copy` | **draft**, not live |

## Custom post types (old site)

### `catalog` — 14 published entries (cabinet collections/finishes)

`publicly_queryable => false` in the old theme's registration — these
have **no individual public URL**; they're an internal content type
rendered into catalog/collection display pages elsewhere in the theme.

| Title | Slug |
|---|---|
| Brooklyn midnight | `brooklyn` |
| Brooklyn white | `london` |
| Brooklyn Fawn | `brooklyn-fawn` |
| Brooklyn slate | `brooklyn-slate` |
| Brooklyn grey | `brooklyn-grey` |
| SHAKER White | `new-york` |
| SHAKER Sand | `shaker-sand` |
| SHAKER Kodiak | `shaker-kodiak` |
| SHAKER Espresso | `shaker-espresso` |
| SHAKER Moss | `shaker-moss` |
| Oslo Oak | `oslo-white` |
| Oslo white | `oslo-white-2` |
| Builder series | `builder-series` |
| Napa | `napa` |

**Note:** "SHAKER Espresso", "Builder series", and "Napa" are old-site
finishes/lines **not** in the new site's approved collection/finish list
(Brooklyn: Fawn/Gray/Midnight/White/Pearl/Slate; Shaker: White/Sand/
Kodiak/Moss; Oslo: White/Oak/Walnut; Euro/Flat Panel). Flagging for the
owner to decide whether any should be added — not added automatically.
Several old slugs are clearly mismatched to their titles (`london` for
"Brooklyn white", `new-york` for "SHAKER White", `oslo-white`/
`oslo-white-2` for "Oslo Oak"/"Oslo white") — old-site data-entry
artifacts, not a redirect-worthy pattern since these were never public
URLs in the first place.

### `portfolio` — 8 entries, **all trashed** (soft-deleted)

Registered with `has_archive`, public, rewrite slug **`projects`** (not
`portfolio` — the old public URLs were `/projects/` and
`/projects/{slug}/`, despite the CPT being internally named `portfolio`
and the admin label reading "Projects"). Since every entry is trashed,
**the live production Portfolio section currently has no published
projects** — consistent with the new site's zero-fake-projects policy;
there's no real, live portfolio content being superseded.

| Title | Old slug | Status |
|---|---|---|
| Builder series | `brooklin-1-copy-4-copy-copy` | trashed |
| Brooklyn Premier series | `brooklyn-premier-series` | trashed |
| Shaker Premier series | `shaker-premier-series` | trashed |
| Oslo Premier series | `oslo-premier-series` | trashed |
| Napa Premier Series | `napa-premier-series` | trashed |
| Test / Test2 / TestPost | — | trashed, clearly test content |

Two custom taxonomies existed for this CPT: **Project Style** and
**Room Type** (Room Type had its own rewrite under
`/projects/room-type/`).

### Blog — 1 published post

Post ID 402, **empty title**, dated 2025-09-04. Low value; not worth
migrating as-is. Not included in the redirect map as a distinct
priority beyond its own URL.

## Existing menus

One nav menu, 7 items, linking to: Home, Portfolio, Online order, About
company, Feedback, FAQ, Contacts (all standard page links; no custom/
external URLs in the primary menu).

## Existing forms

Three Contact Form 7 forms existed: **"Consult form"**, **"Call
order"**, **"Calculation"** — confirms multiple lead-capture touch-
points beyond a single consultation form. Field-level structure wasn't
extracted (CF7 form markup lives in postmeta as a template string) —
worth a closer look before finalizing the new site's lead-capture scope
if the owner wants feature parity.

## Existing review integration

A **Google Reviews widget** (`widget-google-reviews` plugin) was
configured, tied to a `grw_feed` entry literally titled with the
business's Google-listed address ("7742 Brofield Ave, Windermere, FL
34786") — confirming a real, active Google Business Profile feed, not a
fabricated review source. This is exactly the kind of **genuine**
review integration the new site's Reviews section placeholder
(`front-page.php`) is built to eventually receive — real data, not
hand-authored testimonials.

## Existing analytics/tracking references

No Google Analytics/GTM/Meta Pixel tracking IDs were found in the old
theme's PHP/JS source files via search. Analytics may have been
injected via a plugin's stored settings (database, not code) or added
directly in a hosting-level snippet — not confirmed from what was
inspected. **Do not assume no analytics existed** — this is an absence
of evidence in the searched surface, not confirmed evidence of absence;
flag for the owner before assuming a clean slate on tracking.

## Value proposition copy (reference only — not copied verbatim)

The old site had 6 real "advantages" value-proposition entries with
authored copy (estimate turnaround, custom options, project completion,
fixed pricing, ~7 business day install timeline, communication/ethics
commitment). This is useful **reference material** for writing real
"Why ZEUS" content later — noted here, not copied into the new site's
placeholder copy, since site copy should be owner-reviewed/approved
rather than carried over verbatim.

## How this was gathered (safety method)

The old site's live database (MySQL 8.4, at
`Local Sites\zeus`'s LocalWP-managed data directory) was never queried
directly — even in a nominal "read-only" mode, starting a MySQL server
against a datadir can trigger crash-recovery writes to those same files.
Instead, the entire ~340MB data directory was **copied** to this
project's own gitignored scratch space, a temporary MySQL instance was
run against **the copy only**, queried, and then shut down and deleted.
The original data directory's file modification times were verified
unchanged before and after. Theme source files were read directly
(inherently safe, no execution). No write operations were ever issued
against the original site.
