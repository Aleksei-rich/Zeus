# Content Migration Plan

Tracks what content/media moved from the old LocalWP ZEUS site into this
rebuild during Phase 4, what was deliberately left behind, and why. See
`docs/ASSET-PROVENANCE.csv` for the asset-level detail and
`docs/DECISIONS.md` for the full reasoning behind each judgment call.

All old-site access was read-only per the project's OLD SITE RULE — the
old site's files, theme, plugins, and database were never edited,
deleted, or reconfigured. See `docs/OLD-SITE-INVENTORY.md`, "How this was
gathered (safety method)" for the copy-first database inspection method
used.

## Brand identity — migrated

- Logo, favicon, and navy/gold color palette: sourced from the old site's
  own logo file and re-derived (cropped, recolored for a white footer
  variant, encoded to WebP) rather than copied byte-for-byte. See
  `docs/ASSET-PROVENANCE.csv` and `docs/DECISIONS.md`.
- The old site's own "footer logo" file was found to be unusable (near-
  black, not the white/reversed variant it appeared to be) and was not
  used — a proper white variant was synthesized from the main logo
  instead.

## Business information — migrated

- Phone, email, business hours: verified in the old site's business-info
  fields, now live in `theme/zeus/inc/template-tags.php`
  (`zeus_phone_number_display()`, `zeus_email_address()`,
  `zeus_business_hours()`) and rendered in the header, footer, and
  Contact page.
- Social links (Facebook, Instagram): verified, now live in
  `zeus_social_links()`.
- Physical address: **NOT published.** A real address was found tied to
  the old site's Google Business Profile (see
  `docs/OLD-SITE-INVENTORY.md`, "Business information"), but the Phase 4
  brief requires treating ZEUS as a service-area business unless the
  owner confirms otherwise, and specifically prohibits publishing an
  unverified walk-in-showroom address. Contact and About pages describe
  ZEUS as a service-area business with consultations by appointment.

## Real photography — migrated (generic use only)

Four real, EXIF-stripped photos were re-encoded and imported (attachment
IDs 74–77; see `docs/ASSET-PROVENANCE.csv`). All four are used
**generically** — as homepage hero art, service-page featured images,
and one About-page image — never as an attributed case study or claimed
completed-project gallery, because the source gallery they came from
could not be confidently grouped into a single, real, nameable project
(see "Portfolio — not migrated" below).

## Portfolio projects — not migrated

The old site's `portfolio` custom post type ("Premier series" / "Builder
series", 5 trashed posts) was investigated in full read-only detail:
ACF gallery fields, featured images, and image EXIF/filenames across all
groups.

**Finding:** every group's featured/marketing image was a composite
(door-swatch or branded-text overlay on a rendered kitchen, one
literally stamped "COMING SOON"), not a photo of a real completed
installation. Gallery contents within each group mixed some genuinely
real photos with candid people, protective-film-still-on-appliances
shots, and images spanning multiple distinct rooms and capture dates —
meaning a single gallery did not reliably represent one real project.

**Decision:** no Portfolio entries — public or draft — were created from
this material. The Phase 4 brief is explicit: "If project grouping
itself is uncertain, do not create a public project from it." This
reverses a Phase 3 inventory note that had tentatively read two sample
images from this same content as "moderate confidence real completed
project."

The four individually-verified-real photos found during this
investigation were salvaged for generic use instead of being discarded
outright or misused as attributed projects — see "Real photography"
above.

The `project` custom post type itself is fully built and ready (see
`theme/zeus/single-project.php`, taxonomies for Project Style/Room Type)
— it is simply empty of content until the owner can confirm real,
nameable, dateable projects to enter.

## Reviews — not migrated (architecture only)

The old site has a real, active Google Business Profile with an existing
`widget-google-reviews` feed (see `docs/OLD-SITE-INVENTORY.md`,
"Existing review integration") — a genuine review source, not a
fabricated one. No review text was copied or authored by hand. The
homepage Reviews section (`front-page.php`, section 9) currently
describes this in plain customer-facing language and points visitors to
Google directly; it is built to receive the live feed once the owner
provides the Google Business Profile connection details. No
`AggregateRating` schema has been added, per the brief.

## Cabinet collections — migrated (structure + fresh copy)

Brooklyn, Shaker, Oslo, and Euro/Flat Panel collection pages, and their
`finish` taxonomy terms (verified from `docs/CONTENT-MODEL.md`), already
existed structurally from earlier phases. Phase 4 replaced the
placeholder body copy with fresh, ZEUS-authored descriptions of each
door profile and its finishes, plus a `zeus_construction_notes` value
per collection.

Construction notes describe general, verifiable door-construction
geometry (e.g. "five-piece recessed-panel door") rather than
proprietary specifications (box material, hinge hardware, etc.), since
no such specs were confirmed in the old site or project docs — per the
brief's "do not fabricate unsupported technical material specifications"
rule. The Oslo/Shaker "Slim Shaker vs. traditional Shaker" distinction
is called out explicitly on both pages and in the homepage FAQ.

## Countertop materials — migrated (fresh copy)

Quartz, Granite, Porcelain, and Marble pages: placeholder copy replaced
with fresh, fact-based content covering appearance, typical strengths,
maintenance, where each material works well, and selection
considerations — written to avoid unsupported absolutes ("best,"
"indestructible," "maintenance-free") per the brief.

## Services — migrated (fresh copy)

Kitchen Cabinets, Bathroom Cabinets & Vanities, Custom Closets, Laundry &
Pantry, and Home Office pages, plus the Cabinets and Custom Spaces hub
pages: placeholder copy replaced with fresh, ZEUS-authored copy. The old
site's six "advantages" value-proposition entries (estimate turnaround,
custom options, fixed pricing, install timeline, communication/ethics
commitment — see `docs/OLD-SITE-INVENTORY.md`, "Value proposition copy")
were used only as background reference for tone and themes, not copied
or presented as verified claims — specific numbers/timelines from that
old copy were **not** carried over since they were not independently
confirmed for this rebuild.

## Old-site content NOT migrated (and why)

- **Portfolio/project galleries** — see above.
- **Customer reviews** — see above; no fabricated review text.
- **Certifications, licensing, years-in-business claims** — never
  confirmed in project docs or the old site; not published anywhere.
  The homepage's old placeholder line asking the owner to confirm these
  was removed rather than left visible, per the RC1 rule against
  visible development-placeholder text.
- **Blog post (ID 402)** — empty title, low value; not migrated.
- **Contact Form 7 forms** ("Consult form," "Call order," "Calculation")
  — the new site's consultation form already covers the core lead-
  capture need; the other two forms' field structure was not extracted
  and would need owner input on scope before building equivalents.
- **Old site's own theme/plugin code** — never reused; the rebuild uses
  its own first-party theme + `zeus-core` plugin architecture.

## Verification performed on this pass

- Every page/collection listed above was re-fetched via `wp post get`
  and checked for leftover `[Development placeholder...]` markers (see
  `.localenv/brand-work/sweep-placeholders.php`) — none remain as of
  this writing.
- Each edited page was reloaded via `curl` (HTTP 200, no PHP warnings)
  after its content update.
