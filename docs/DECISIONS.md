# Decisions Log

Newest entries at the top. Each entry: date, decision, reasoning, and
whether it was an autonomous professional-default call or an
owner-directed one.

---

## 2026-08-26 — RC4C-POLISH: Laundry & Pantry page given a true pantry-specific visual

**Decision:** The Laundry & Pantry page (`page-laundry-pantry.php`, page
ID 19) used attachment 155 in the hero, the "Make the Laundry Room Work
Harder" section, and the "Pantry Storage That Uses the Space Better"
section -- but 155 visually reads primarily as a laundry room (visible
washer/dryer), so the Pantry section had no true pantry-specific image.
Imported a new generated pantry image (attachment 163, "Custom Pantry
Cabinetry" -- bright white/light butler's-pantry with open shelving,
food-storage jars, baskets, tall cabinetry, a small-appliance worktop,
and brass hardware) and used it only in the Pantry section; the hero and
Laundry section keep attachment 155 unchanged.

**Source:** The newest PNG in the owner's Downloads folder at the time
of this task (`ChatGPT Image Aug 26, 2026, 04_18_54 PM.png` -- newer
than the closet image used in the prior RC4C-POLISH pass, so not
confused with it), visually inspected before use, including a close
zoom on the stand mixer and beverage-fridge appliances specifically to
rule out visible brand markings. No text, logos, watermarks, or
manufacturer branding found; clearly reads as a pantry, not a kitchen or
laundry room. Processed via the same workflow as prior generated-media
imports: PNG->WebP re-encode via PHP GD (~90% size reduction, no
upscale), neutral public filename `zeus-custom-pantry-cabinetry.webp`,
`zeus_media_provenance=generated_category_lifestyle` postmeta flag set.
Classified in `docs/ASSET-PROVENANCE.csv` as generated category/
lifestyle media, explicitly barred from Real ZEUS Work, Portfolio,
before/after, completed-project, client-project, or attributed-
installation use.

**Reasoning:** Owner visual review after the prior RC4C-POLISH (Closets)
pass shipped, delivered as a scoped brief limited to this one image
swap; hero, H1, title, meta description, canonical, breadcrumbs, section
order, section copy, CTA, FAQ, process, service area, and every other
page were explicitly out of scope and not touched.

**Type:** Owner-directed (explicit RC4C-POLISH brief, including which
file to locate and how to process it).

---

## 2026-08-26 — RC4C-POLISH: Closets page image repetition fixed

**Decision:** The Closets page (`page-closets.php`, page ID 68) reused
attachment 154 in both the hero and the "Planned Around the Room, Not a
Kit" section. Imported a new generated closet image (attachment 162,
"Custom Walk-In Closet Design" -- gray/taupe cabinetry, brass hardware,
stone-top island, window seat, visually distinct palette from
attachment 154's lighter oak tone) and used it only in the second
section; the hero keeps attachment 154 unchanged.

**Source:** The newest PNG in the owner's Downloads folder at the time
of this task (`ChatGPT Image Aug 26, 2026, 03_46_02 PM.png`), visually
inspected before use -- no text, logos, watermarks, or manufacturer
branding, landscape format, no people, clearly a walk-in closet.
Processed via the same workflow as prior generated-media imports:
re-encoded PNG->WebP via PHP GD (~91% size reduction, no upscale),
saved under the neutral public filename
`zeus-custom-walk-in-closet-design.webp`, imported via
`wp_insert_attachment` + `wp_generate_attachment_metadata`, alt text set
at import, `zeus_media_provenance=generated_category_lifestyle` postmeta
flag set. Classified in `docs/ASSET-PROVENANCE.csv` as generated
category/lifestyle media, explicitly barred from Real ZEUS Work,
Portfolio, before/after, or attributed-project use, matching the RC3B/
RC4B rule.

**Reasoning:** Owner visual review after RC4C shipped, delivered as a
scoped "RC4C-POLISH" brief limited to this one image swap; hero, H1,
title, meta description, canonical, breadcrumbs, section order, section
copy, CTA, FAQ, process, service area, and every other page were
explicitly out of scope and not touched.

**Type:** Owner-directed (explicit RC4C-POLISH brief, including which
file to locate and how to process it).

---

## 2026-08-26 — RC4C: Custom Spaces hub + Closets/Laundry & Pantry/Home Office pages built

**Decision:** Built four new page templates -- `page-custom-spaces.php`
(hub, page ID 17), `page-closets.php` (ID 68), `page-laundry-pantry.php`
(ID 19), `page-home-office.php` (ID 20) -- auto-selected by WordPress's
`page-{slug}.php` template hierarchy, replacing the previous thin
`page.php`-rendered content on all four URLs. Unlike Kitchen/Bathroom,
these pages carry **no in-stock messaging** -- Custom Spaces is a
purely custom-cabinetry/built-in offering, so there is no "Two Ways"
in-stock-vs-custom section, no navy door-strip, and no in-stock advantage
copy anywhere on these four pages.

**No Real ZEUS section on any of the four pages:** no independently-
verified real completed-project photo exists in the approved media
library for closets, laundry/pantry, or home office (the only verified-
real photos on record are kitchen/bathroom-specific: 74/75/77/76). Per
the owner's explicit instruction, this was treated as a reason to omit
the section entirely on all four pages rather than build one using
generated (154/155) or dealer-lifestyle (120) imagery, which would have
mislabeled it.

**Media:** Hub hero and Closets hero both use attachment 154 (Custom
Closet, generated category/lifestyle, RC3B) -- chosen for the hub per
explicit instruction ("strongest broad custom-cabinetry impression").
Laundry & Pantry hero uses attachment 155 (generated category/lifestyle,
RC3B) -- this image shows a closet nook and a laundry area with visible
washer/dryer, so it reads primarily as "laundry"; no other approved
asset shows a pantry specifically, so the Pantry section on that page
reuses the same image with pantry-focused surrounding copy rather than
substituting a kitchen-context image that would visually read as a
kitchen (explicitly against the brief). Home Office hero uses attachment
120 (Brooklyn Pearl Home Office, dealer-provided lifestyle). Floating-
shelf attachments 139/140/141 were deliberately NOT used on any of these
four pages despite being suggested as candidates -- all three visually
show a kitchen scene (range, island, cane chairs) as their setting, and
using them here would have reintroduced exactly the kind of kitchen-
context leak the owner corrected in RC4B-POLISH.

**Excluded a second Home Office image over a filename risk:**
attachment 112 ("Brooklyn Gray Home Office") was initially planned as a
secondary image for the Home Office page's "Style & Function" section --
it visually shows an office, not a kitchen -- but its underlying file is
named `brooklyn-gray-kitchen-01.webp`. Given the owner's demonstrated
sensitivity to kitchen-image leaks on non-kitchen pages, this was judged
too easy to misread during review (a filename check would flag "kitchen"
on the Home Office page even though the pixels are correct), so that
section was rebuilt as text-only (three short points) instead of
forcing an image with an ambiguous name. See the file header comment in
`page-home-office.php` for the same note.

**SEO:** Titles, meta descriptions, and featured images set per page via
the existing `zeus_seo_title`/`zeus_seo_description`/`_thumbnail_id`
postmeta pattern -- no new SEO plumbing. Canonical, Open Graph, and
BreadcrumbList all verified live and correct: Home > Custom Spaces; Home
> Custom Spaces > Custom Closets; Home > Custom Spaces > Laundry &
Pantry; Home > Custom Spaces > Home Office.

**Reasoning:** Owner-directed RC4C brief, explicit about differentiating
these pages from Kitchen/Bathroom (no in-stock messaging, no forced
"Real ZEUS" section, room-appropriate imagery only) and about which
existing pages/components must not be touched.

**Type:** Owner-directed (explicit RC4C brief); the Home Office
secondary-image substitution, the Laundry vs. Pantry shared-image
handling, and the floating-shelf exclusion were autonomous professional
defaults applied within that brief's media-provenance rules.

---

## 2026-08-26 — RC4B-POLISH: Bathroom Cabinets & Vanities visual-context correction

**Decision:** Corrected two sections of `page-bathroom-cabinets-vanities.php`
that were showing kitchen-context imagery on the bathroom-specific
landing page, per explicit owner review. (1) "Explore Cabinet Styles
for Your Bathroom" previously rendered via
`get_template_part('components/card-collection', ...)`, which pulls
each `cabinet_collection` post's own global featured image -- Brooklyn/
Shaker/Oslo/Euro-Flat-Panel's featured images are all kitchen
photography (shared sitewide, e.g. on `/cabinet-styles/` and the
Kitchen Cabinets page). Rather than changing those global featured
images (which would have regressed the Kitchen Cabinets page and the
Cabinet Styles hub) or editing the shared `card-collection.php`
component, this section now uses page-specific inline markup (same
visual structure/CSS as `card-collection.php`, real collection
titles/excerpts/permalinks preserved) with a bathroom-context image
override: Brooklyn->119 (Brooklyn Pearl Bathroom), Shaker->130 (Shaker
Moss Bathroom, chosen over 128 for a stronger composition), Oslo->134
(Oslo Oak Bathroom, chosen over 137 for a stronger composition and a
clearer view of the narrow-rail Slim Shaker door profile), Euro/Flat
Panel->160 (new). (2) The countertop cross-sell image (previously
attachment 159, "Marble Countertop Kitchen" -- a kitchen island) was
replaced with new attachment 161, a bathroom vanity/countertop scene.

**New media:** Two images from owner-supplied
`ZEUS_RC4B_BATHROOM_MEDIA.zip` (Downloads folder) -- AI-generated
category/lifestyle visuals, not sourced from any manufacturer or third
party. Both visually inspected before import (no text/logo/watermark/
manufacturer branding found). Processed identically to the RC3B
workflow: re-encoded PNG->WebP via PHP GD (strips metadata, ~94% size
reduction, no upscale), imported via `wp_insert_attachment` +
`wp_generate_attachment_metadata`, alt text set at import,
`zeus_media_provenance=generated_category_lifestyle` postmeta flag set.
Attachment 160 ("Euro Flat Panel Bathroom Vanity") and attachment 161
("Bathroom Vanity Countertop") -- both classified in
`docs/ASSET-PROVENANCE.csv` as generated category/lifestyle media,
explicitly barred from Real ZEUS Work, Portfolio, before/after, or any
attributed-project context, matching the RC3B rule. The countertop
image's exact stone type is not confirmed from the generated visual, so
no specific material claim was added to on-page copy (existing "Marble
and quartz are both common vanity-top choices..." copy was left as
general guidance, not a claim about this specific image).

**Reasoning:** Owner visual review after RC4B shipped, delivered as a
scoped "RC4B-POLISH" brief limited to these two sections; hero, trust
strip, Two Ways section (which already used bathroom-context images
137/128), in-stock message, process, Real ZEUS section, Service Area,
FAQ, consultation form, and every other page were explicitly out of
scope and not touched.

**Type:** Owner-directed (explicit RC4B-POLISH brief, including the
required file names inside the media package); Shaker/Oslo image choice
between the two named alternatives, and the page-specific-override
implementation approach (vs. changing global featured images), were
autonomous professional defaults within that brief.

---

## 2026-08-26 — RC4A-POLISH: Kitchen Cabinets page visual/content polish

**Decision:** Five targeted, owner-reviewed changes to
`theme/zeus/page-kitchen-cabinets.php` only -- no other file touched, no
redesign. (1) The in-stock navy section now shows 6 representative
finishes instead of 3 (Brooklyn White, Shaker White, Oslo White,
Brooklyn Midnight, Shaker Moss, Oslo Walnut -- white finishes first),
reusing the exact same six door-sample attachments (100/103/107/99/106/
109) already approved and used in this same order on the homepage's
"Why In-Stock Matters" section, for sitewide consistency. (2) "Two Ways
to Build Your Kitchen" intro copy no longer says "Both are real ZEUS
offerings" (internal-sounding phrasing) -- replaced with "Choose the
approach that best fits your space, timeline, and design needs."
(3) The Custom Kitchen Cabinetry card image was swapped from attachment
121 (Brooklyn Slate Kitchen) to attachment 122 (Brooklyn Slate Kitchen
2, same collection/finish) -- 122 shows a wall-to-wall run with a tall,
built-in, ceiling-height appliance surround and a non-standard angled-
ceiling/stone-accent-wall composition, a visibly closer match to "custom/
tailored" than 121's more conventional L-shape layout. Both images were
already approved (dealer-provided lifestyle media, RC2 import, no
branding). (4) The "Real ZEUS Kitchen Installations" section gained a
section-level supporting line ("Actual installation photos from ZEUS
projects.") and the three repeated per-photo "Real ZEUS Installation"
labels were removed, since the heading + new supporting line already
establish provenance once rather than three times -- the underlying
photos (74/75/77) and their documented real-completed-project status are
unchanged. (5) The Service Area section was rebalanced from a single
narrow paragraph into a two-column layout: existing copy on the left, a
compact card on the right ("Serving Orlando & surrounding communities" +
phone number) using the existing `.zeus-card`/`.zeus-card__body` classes
-- no new CSS, no second competing large CTA button.

**Reasoning:** Owner visual review after RC4A/RC4B shipped, delivered as
a scoped "RC4A-POLISH" brief explicitly listing five changes and
explicitly excluding hero, trust strip, collection cards, process,
countertop cross-sell, FAQ, consultation form, footer, SEO
title/description/canonical/breadcrumbs, and any other page.

**Type:** Owner-directed (explicit RC4A-POLISH brief); exact door
selection, replacement image choice, and markup approach were autonomous
professional defaults within that brief's stated preferences.

---

## 2026-08-26 — RC4B: Bathroom Cabinets & Vanities page rebuilt, mirroring RC4A

**Decision:** Replaced the thin, `page.php`-rendered Bathroom Cabinets &
Vanities page (`/cabinets/bathroom-cabinets-vanities/`, page ID 11) with
a dedicated `page-bathroom-cabinets-vanities.php` template, picked up
automatically via WordPress's `page-{slug}.php` template hierarchy. This
was an autonomous continuation of RC4A ("Continue autonomously to RC4B")
with no new written brief, so the RC4A brief's structure and rules were
applied by analogy: same section pattern
(`theme/zeus/page-kitchen-cabinets.php`), same positioning rules
(in-stock and custom both real offerings, no "same/one team" or
guaranteed-lead-time language), same SEO-postmeta mechanism, same media
provenance discipline.

**SEO:** Title "Bathroom Cabinets & Vanities Orlando, FL | In-Stock &
Custom | ZEUS", new meta description, featured image set to attachment
76 (a real verified ZEUS bathroom photo, replacing the old dealer-
lifestyle thumbnail 134). Canonical/OG/BreadcrumbList confirmed live
(Home > Cabinets > Bathroom Cabinets & Vanities).

**Media:** Hero uses attachment 119 (Brooklyn Pearl bathroom, bright
white/gold palette) -- deliberately different from the Kitchen Cabinets
page's navy hero (114) so the two service pages don't read as repeats
of each other. Two-paths cards use 137 (Oslo Walnut) and 128 (Shaker
Kodiak); in-stock door-sample row uses 98/105/107, a different subset
than the homepage and Kitchen Cabinets page use. **Real-photo trust
section deliberately scaled down to one photo, not three:** only one
independently-verified real bathroom installation photo (attachment 76)
currently exists in the approved media library, versus three for
kitchens. Padding a 3-photo grid with unrelated real kitchen photos, or
using non-real lifestyle images labeled as real, would have violated the
project's real-vs-lifestyle labeling rule -- so the section was built as
a single restrained callout instead, an honest reflection of what media
actually exists rather than a forced template match to the kitchen page.
No new images were generated or imported.

**Reasoning:** Direct continuation of the RC4A pattern to the sibling
service page, per the owner's "Continue autonomously to RC4B" instruction.

**Type:** Owner-directed (continuation instruction naming RC4B and the
target page); section structure, exact copy, and media selection made as
autonomous professional defaults, consistent with the RC4A precedent.

---

## 2026-08-26 — RC4A: Kitchen Cabinets page rebuilt as a dedicated landing page

**Decision:** Replaced the thin, `page.php`-rendered Kitchen Cabinets
page (`/cabinets/kitchen-cabinets/`, page ID 10 -- previously a handful
of paragraphs plus an inline `<img>` gallery in `post_content`) with a
dedicated `page-kitchen-cabinets.php` template, picked up automatically
by WordPress's `page-{slug}.php` template hierarchy (no post-meta
template assignment needed, so this is a pure theme-file change). The
template follows the same section-based pattern established by
`front-page.php` (`zeus_section_start()`/`zeus_section_end()`,
`components/card-*`, the consultation form partial) rather than
introducing a new pattern.

**Content/SEO:** New SEO title "Kitchen Cabinets Orlando, FL | In-Stock
& Custom | ZEUS", meta description, and featured image (now attachment
75, a real verified ZEUS kitchen photo, replacing the old thumbnail 131
which was dealer lifestyle media) set via the existing `zeus_seo_title`
/ `zeus_seo_description` / `_thumbnail_id` postmeta -- no new SEO
plumbing needed. Canonical, Open Graph, and BreadcrumbList output all
worked automatically once the page existed, confirmed live (Home >
Cabinets > Kitchen Cabinets).

**Positioning:** Rewritten to present in-stock and custom kitchen
cabinetry as equally real offerings (matching the sitewide RC2
positioning correction), explicitly avoiding "stocked locally in
Orlando," "same/one team," "no subcontractors," guaranteed lead times,
and unsupported warranty/licensing/material claims per the owner's
brief.

**Media:** Hero uses attachment 114 (Brooklyn Midnight kitchen,
dealer-provided lifestyle media, deliberately different from the
homepage's Oslo Walnut hero). The "Real ZEUS Kitchen Installations"
section uses attachments 75/74/77, all independently verified real
completed-project photos (see docs/ASSET-PROVENANCE.csv) -- the same
standard already applied to the homepage's "Real ZEUS Work" section, not
a new rule. Collection cards reuse the four `cabinet_collection` posts'
own featured images and excerpts (the Oslo excerpt already reads "The
Oslo Slim Shaker collection...", so the brief's "must clearly
communicate Oslo Slim Shaker" requirement was satisfied without
modifying the shared `card-collection.php` component). No new images
were generated; no shared component or homepage file was modified.
`docs/ASSET-PROVENANCE.csv` usage-location fields updated for the
images newly used on this page (114, 121, 123, 75).

**Reasoning:** Owner-directed RC4A brief: turn the Kitchen Cabinets page
into a strong, commercial, image-led landing page supporting both users
and SEO for "Kitchen Cabinets Orlando" and related secondary intents,
without creating new landing pages or touching the approved homepage
baseline (commit 3151019) in this pass.

**Type:** Owner-directed (explicit RC4A brief), implementation choices
(image selection, exact section copy, template pattern reuse) made as
autonomous professional defaults within that brief.

---

## 2026-08-26 — Header nav breakpoint raised 1024px → 1280px (supersedes part of the 2026-08-22 overflow-fix decision)

**Decision:** The desktop-vs-mobile header switch (`.zeus-primary-nav`,
the header's own "Request Free Consultation" button, the hamburger
toggle, and the persistent bottom mobile conversion bar) now all flip at
`min-width: 1280px` instead of `1024px`. The `.zeus-logo__img` mid-tier
width rule (150px, added in RC3C for the 1024-1439px band) was removed
since it's no longer the tight zone; the logo now holds at 200px through
1024-1439px and steps up to 250px only at >=1440px.

**What broke:** Owner-requested QA screenshots at 1024px and 1150px
(routine follow-up after the RC3C logo-brand-correction commit) showed
the header logo rendering at 0 width, and at exactly 1024px the primary
"Request Free Consultation" CTA button was clipped off the right edge of
the viewport entirely. Verified with real `--screenshot` captures (not
`--dump-dom`, which this session re-confirmed is unreliable for
viewport-width diagnostics) and a raw pixel scan of the header row: at
1024px the full nav + phone + CTA content needs roughly 1101px but only
~992px of container width is available, an overflow that exists even
with the logo shrunk to 0 — so no amount of further logo-shrinking could
have fixed it.

**Reasoning:** This re-opens the breakpoint chosen in the 2026-08-22
"real bug found and fixed — horizontal overflow at narrow widths" entry
below, which picked `lg` (1024px) as the split and verified "zero
horizontal overflow at 320/375/768/1024/1440px." That verification
checked discrete widths, not the 1024-1279px band as a range, and predates
RC3C's wider authentic-brand logo — the combination of the wider logo and
the unchanged 1024px threshold is what recreated overflow in a new form.
Rather than trying to further compress nav/phone/CTA content to survive a
genuinely too-narrow band (which risks a worse redesign under the RC3C
"branding only" and RC3A "structural polish only" scoping this project
has otherwise followed), the fix moves the switch to a width with
verified real headroom (1280px, confirmed clean via screenshot at 1280,
1366, and 1440px) and lets the already-built, already-correct mobile
hamburger drawer (full nav + its own "Request Free Consultation" button)
cover the 1024-1279px band instead — the same pattern already used below
1024px, just extended slightly further up.

**Also reconfirmed:** headless Edge's `--window-size` remains unreliable
below ~480-500px on this machine (this session observed
`window.innerWidth` pinned at 492px regardless of a requested 390px or
500px size) — consistent with, and a refinement of, the tooling
limitation already on record in `docs/HANDOFF.md` and the 2026-08-22
entry below. No site-side change was made for this; it's a capture-tool
limitation, not a layout defect (confirmed the mobile hamburger renders
correctly once capture width matches actual layout width).

**Type:** Autonomous professional default (real functional bug —
primary conversion CTA clipped off-screen — found during a routine
owner-requested screenshot check; fixed per CLAUDE.md §15's "pick the
safest professional default... don't ask" for non-subjective defects).

---

## 2026-08-23 — Phase 5 / RC2: business-positioning correction, curated media import, logo rebuild, factual-claim correction

**Owner feedback that triggered this phase:** RC1 was technically
accepted but not visually/business-positioning approved. The owner's
brief identified three problems: (1) the site read as custom-cabinetry-
only, when ZEUS's business is actually two offerings — in-stock
collections through a central warehouse, and custom cabinetry for
non-standard spaces; (2) the visual design was "too weak" for a premium
cabinetry business; (3) the header logo looked "crooked/awkward" and the
phone number needed more visual weight.

**1. Business positioning.** Rewrote the homepage (new hero H1 "Kitchen
Cabinets & Countertops in Orlando & Central Florida," a new "Two Ways to
Work With ZEUS" section, a new "Why In-Stock Matters" section) and the
Kitchen Cabinets, Bathroom Cabinets & Vanities, and About pages so
in-stock is presented first and custom is a clearly-available second
path, not the whole story. Removed "Custom, Not Catalog" framing
throughout. See `CONTENT-MIGRATION-PLAN.md` for the page-by-page
before/after.

**2. Curated media import.** The owner supplied
`ZEUS_RC2_MEDIA_CURATED.zip` (Downloads folder) containing 45 media
files: 13 square door/finish samples, 29 collection lifestyle photos
(Brooklyn/Shaker/Oslo, kitchen+bathroom+other rooms), and 3 floating-
shelf photos, plus `ASSET_MANIFEST.csv` and `MEDIA_USE_RULES.txt`. Read
both before importing, per the rules. All 45 files were individually,
visually inspected (not just checked by filename) before use — zero
contained manufacturer branding, logos, or watermarks. Two files were
found to be mislabeled by their neutral filename (`brooklyn-gray-
kitchen-01`/`-02` actually show a home office and a bathroom
respectively, not a kitchen) — used according to actual visible content,
not the filename, with correct alt text. Imported via `wp_insert_
attachment` + `wp_generate_attachment_metadata`, factual alt text set at
import time, classified `dealer-provided lifestyle/product media` in
`ASSET-PROVENANCE.csv` (per `MEDIA_USE_RULES.txt`'s own neutral-wording
instruction — never "manufacturer-provided"). None of these images were
added to Portfolio or labeled as a completed ZEUS project — they're used
for collection/service-page presentation only, consistent with
`MEDIA_USE_RULES.txt` rule 7.

**3. Essential series — confirmed absent.** Grepped the entire repo and
the WordPress database (post titles + content) for "Essential" — zero
matches, before and after this phase's changes. Nothing to remove.

**4. Manufacturer-branding sweep.** Searched all database content
(pages, collections, projects, attachment alt text) and all theme/
plugin source for the manufacturer's name/initials (found once, pre-
existing, in `PROJECT-SPEC.md`'s original mega-brief-derived "similar in
spirit to" comparison list — an internal planning reference never
rendered publicly, left as-is since it's Phase-0 foundational
documentation, not new work) and for local machine paths (`C:\Users`,
`Downloads`) — none found in any public-facing content or code.

**5. Logo rebuild — real bug found and fixed.** The only available ZEUS
logo source (the old site's own uploaded brand file) turned out to be a
designer's presentation board with a permanent golden-ratio construction
grid, a cursive tagline, and a gradient bar baked into the pixels —
every previous crop of it (Phase 4 and earlier) inherited the grid
lines, which is what the owner saw as "crooked." Recovered a clean
wordmark via morphological opening (erode then dilate with a 3px
structuring element, which strips anything thinner than the letter
strokes — i.e. the 1-2px guide lines — while preserving the much thicker
letterforms) followed by a connected-component size filter to drop
residual fragments. Rebuilt the header/footer lockups and favicon from
this cleaned source, and moved "Cabinets + Countertops" out of the
fragile raster subtitle into real HTML text (it was getting damaged by
the same erosion pass, since its strokes are nearly as thin as the guide
lines) so it can never re-acquire artifacts. **Type:** investigate
before "fixing" — the original ask could have been answered by nudging
crop coordinates, which would not have solved the actual problem.

**6. Header phone prominence.** Per direct owner instruction, the header
phone number now uses a two-line "Call or Text" label + large bold
number treatment (up from a small icon+text utility link), sized to
visually compete with the Request Free Consultation button without
duplicating it as a second button.

**7. Collection pages made visual.** Brooklyn/Shaker/Oslo collection
pages previously showed "Available Finishes" as plain text chips with an
empty gallery (no gallery content existed). Now show a real finish-
swatch grid (each finish paired with its actual door-sample photo) and a
populated lifestyle-photo gallery using the curated import. The shared
"White" finish taxonomy term (used by all three collections) needed a
*different* swatch photo per collection, which plain term meta can't
express — solved with a new `zeus_finish_swatches` post-meta map
(term_id → attachment_id) stored per collection instead.

**8. Factual-claims correction (mid-phase owner correction).** Two
lines asserted unverified staffing/subcontracting facts: "the same team
from consultation through final walkthrough" and "handled by one team,
not separate subcontractors." Both were replaced with accurate,
unfalsifiable language ("coordinated by ZEUS") in `front-page.php`, the
About page, and the matching `zeus-core` seed source — keeping the real,
verifiable differentiator (ZEUS coordinates the whole project) without
asserting a specific personnel/subcontracting fact that was never
confirmed.

**9. Consultation form retested end-to-end** after all changes: valid
submission → redirects to `/thank-you/`; invalid submission (bad email,
bad ZIP, short description) → redirects back with all three field-level
errors displayed, no lead created; valid lead stored as `private` (404
on direct access) and correctly logged to the local mail-notification
log; test lead deleted afterward. Zero PHP warnings throughout.

**10. Headless-Edge screenshot fix found.** A second, different
headless-Edge screenshot artifact was found and root-caused this phase
(distinct from the narrow-viewport clamping found in Phase 4): large,
absolutely-positioned hero background images sometimes failed to
composite into the `--screenshot` output even though an in-page JS
diagnostic proved they were fully loaded, correctly sized, and visible
in the DOM (`complete: true`, correct `naturalWidth`, correct
`getBoundingClientRect()`). Adding `--run-all-compositor-stages-before-
draw` (a Chromium flag that forces all compositor stages to finish
before the screenshot is captured) fixed it reliably. Documented in
`HANDOFF.md` for future sessions.

**Type:** Business-positioning and visual-design changes are owner-
directed (explicit RC2 brief). The logo investigation, swatch/gallery
data-model choice, and both tooling-limitation fixes are autonomous
professional-default calls made to fulfill that brief correctly.

---

## 2026-08-22 — Phase 4 content pass: real copy for every remaining page, three real bugs found+fixed, and a local tooling limitation identified

**Content:** Wrote fresh, ZEUS-authored copy (replacing every
`[Development placeholder]` block) for the Cabinets/Countertops/Custom
Spaces hub pages, all 6 service pages, all 4 countertop-material pages,
all 4 cabinet-collection pages, About, and Contact — directly in the
database via `wp-cli`/`wp eval-file` (not template-hardcoded), since
this is editorial content the owner should be able to edit normally
afterward. Also set a unique `zeus_seo_title`/`zeus_seo_description`
per page. Full method and per-page detail: `docs/CONTENT-MIGRATION-PLAN.md`.

**Real bug #1 — duplicated site name in `<title>`:** `zeus_seo_title`
overrides are complete, already-branded strings (e.g. "Quartz
Countertops Orlando | ZEUS..."), but `zeus_filter_document_title_parts()`
only replaced the `title` key of WordPress's title-parts array, leaving
the `site` key intact — so WP's own title separator appended the site
name a second time ("...| ZEUS ... – ZEUS ..."). **Fix:** the filter now
replaces the whole parts array when an override is present
(`theme/zeus/inc/seo.php`).

**Real bug #2 — empty gray box on cards with no image:**
`components/card-collection.php` and `components/card-project.php`
always rendered a `.zeus-card__media` wrapper div even when
`has_post_thumbnail()` was false. That wrapper has a fixed 4:3
aspect-ratio and a background color, so every one of the four cabinet
collections (none have images yet) showed as a large empty gray box on
the homepage's Featured Collections section — a "bad empty state"
explicitly flagged in the brief's visual-QA checklist. **Fix:** the
wrapper div now only renders when a thumbnail actually exists.

**Real bug #3 — dev suffix leaking into every page title and schema:**
the `blogname` option was still `"ZEUS Cabinets & Countertops (Rebuild -
Local Dev)"` from initial setup, which fed directly into every page's
`<title>` tag AND the sitewide Organization/LocalBusiness JSON-LD
schema's `name` field (via `get_bloginfo('name')` in
`theme/zeus/inc/seo.php`) — meaning the structured-data business name
was literally wrong. **Fix:** `wp option update blogname "ZEUS Cabinets
& Countertops"`. (Database change, not tracked by git.)

**Also fixed:** `archive-project.php` and `home.php` (Blog listing) had
their own hardcoded `[Development placeholder]` strings in their
empty-state branches — not caught by the earlier DB-content sweep since
these are template literals, not post content. And the `zeus-core`
plugin's seed source (`inc/seeding.php`) still generated the old
placeholder copy — harmless today (seeding is create-only and
registry-guarded, so it can never overwrite today's real content), but
updated anyway so a future reseed of a wiped database doesn't
regress to placeholder text.

**Tooling finding — headless Edge narrow-width limitation:** while
investigating what first looked like a severe mobile horizontal-overflow
bug (hero/paragraph text and the header hamburger button appearing cut
off at 375–460px in `--screenshot` captures), a from-scratch
investigation (bisecting the width where clipping starts, then an
in-page JS diagnostic injected via a temporary script in
`site-footer.php`, comparing `document.documentElement.clientWidth`
against the requested `--window-size`) established that this machine's
headless Edge silently fails to honor `--window-size` below roughly
480–500px — the browser lays out at a wider internal viewport than
requested while the `--screenshot` output PNG is still saved at the
smaller requested pixel dimensions, producing a false "overflow"
artifact. At the narrowest width the tool would actually honor (~490px),
the same JS diagnostic found `document.body.scrollWidth ===
document.documentElement.clientWidth` and zero elements exceeding the
viewport — i.e., no real overflow. Manual audit of the relevant CSS
(`.zeus-cta__actions` has `flex-wrap:wrap`, all grids collapse to a
single column below their breakpoints, all images are
`max-width:100%`, no fixed pixel widths found anywhere in the
mobile-relevant rules) found nothing that would behave differently at
375px than at 490px. Logged as a known limitation in `TASKS.md` rather
than either (a) "fixing" a bug that doesn't exist, or (b) silently
leaving true sub-500px rendering unverified without explanation — real
device confirmation before public launch is still recommended.
**Type:** Autonomous professional-default call (investigate before
declaring a fix; don't ship a change for a bug that turned out not to
exist).

---

## 2026-08-21 — Real brand identity adopted; old-site "Premier series" portfolio groups found to be marketing composites, not real projects; a small set of verified-real photos used generically instead

**Brand identity:** found a genuine, current, professionally-designed
ZEUS logo in the old site's media library (`logo-e1783888738132-*.png`,
uploaded July 2026 — the most recent logo asset found). Extracted its
real navy (`#071f34`) and gold (`#dcc16e`) colors by pixel-sampling
(28k+ and 18k+ pixel averages), replacing the placeholder amber palette
from earlier phases. Renamed theme.json tokens `charcoal`→`navy`,
`accent`/`accent-dark`→`gold`/`gold-dark` throughout the theme (~25
references). **Real bug caught during this rename:** blindly swapping
the old accent color for raw gold broke button/badge text contrast
(white-on-gold = 1.77:1) — fixed by using navy text on gold backgrounds
(9.48:1) with a navy-background hover state, and a darker `gold-dark`
for focus rings (passes 3:1 against both white and navy). Synthesized a
white-on-transparent variant of the logo (navy pixels → white, gold
pixels kept) for use on the new navy footer/CTA backgrounds, since the
old site's own "footer logo" file turned out to be black, not white,
and unusable there. Generated a Z-monogram favicon from the same logo.
All derivatives generated locally via PHP GD; no external tool used.

**Portfolio content — real finding, changed the plan:** the old site's
5 trashed `portfolio` CPT entries ("Builder series," "Brooklyn/Shaker/
Oslo Premier series," "Napa Premier Series") looked promising in the
Phase 3 inventory, but a deeper look (their ACF gallery fields, ~126
images across the 5 groups) revealed their **featured images are
product-marketing composites** — a door-color swatch overlaid on a
stock/rendered kitchen photo with "Premier series" branding text; one
("Oslo White") is stamped "COMING SOON," confirming these represent a
now-defunct **product line's catalog graphics**, not documentation of
completed customer installations. This reverses the Phase 3 inventory's
tentative "moderate confidence real completed project" read on 2 sample
images from this same gallery.
**However:** the *gallery* images behind that marketing featured image
(as opposed to the featured image itself) include what are clearly
genuine, unstaged photographs — several show appliances still wrapped
in factory protective film (a strong, hard-to-fake signal of a
freshly-completed real install), real people mid-walkthrough, and
ordinary phone-camera EXIF-free files named by messaging-app export
convention (`photo_2024-09-30_20-53-13.jpg` etc.) rather than any
studio/marketing naming. But the ~41-image "Builder series" gallery
alone spans at least 4 visually distinct rooms (different colors,
different homes) across photo timestamps from April, August, and two
different dates in September 2024 — clear evidence multiple unrelated
jobs were dumped into one CMS gallery, not one project.
**Decision:** because I cannot confidently attribute any individual
photo to one specific project (location, exact collection/finish match,
and completion date are all unknown), **no Portfolio project entries
were created** from this material — public or draft — per the explicit
"if project grouping itself is uncertain, do not create a public
project from it" instruction. The Portfolio section stays in its
honest empty state.
**What was used instead:** 4 of the individually-verified-authentic
photos (no marketing overlay, no "Premier series" branding, appliance
protective-film or candid-person evidence of a real job) were selected
for **general, non-attributed use** — not as Portfolio entries making
specific claims, just as real photography replacing generic
placeholders on the homepage hero and two service pages. Each was
re-encoded through GD (strips any residual metadata; confirmed none had
GPS/camera EXIF to begin with) with a clean, descriptive filename and
factual alt text that describes only what's visible (no location, no
collection name, no date claims). Documented with full source
provenance in `docs/ASSET-PROVENANCE.csv`.
**Type:** Autonomous, cautious application of the explicit media-
classification rules — real photography used where defensible, withheld
from higher-stakes structured claims where it wasn't.

---

## 2026-08-21 — Mobile visual QA + accessibility hardening: two real bugs found and fixed

**Mobile QA method:** the Chrome extension's `resize_window` doesn't
affect the sandboxed browser window on this machine (confirmed
non-functional, not just slow). Tried headless Edge (`msedge.exe
--headless=new --window-size=...--screenshot=...`, already installed —
no new tooling added), but its screenshot capture proved unreliable at
narrow widths on this machine (consistently showed clipped content that
didn't match reality). The authoritative method that worked: an
`<iframe>` sized to the exact target width (375/768/1024/1440px, plus
320px for a safety margin) embedded in the extension's own tab, measuring
the iframe's own `document.body.scrollWidth`/`clientWidth` and element
geometry directly via JavaScript — immune to any screenshot-tool
rendering bug, and more precise than eyeballing a screenshot for the
specific things being checked (overflow, tap-target size, font size).
Documented here so a future session doesn't waste time re-discovering
the headless-screenshot unreliability on this machine.

**Real bug found and fixed — horizontal overflow at narrow widths:**
the header's logo, phone placeholder, "Request Free Consultation"
button, and hamburger toggle were all forced onto one non-wrapping flex
row with no responsive hiding, and the hero H1's static `3.25rem` font
size didn't fit long words ("Countertops") within a 375px viewport with
no `overflow-wrap`. Both combined to push the whole document wider than
the viewport. Fixed by: hiding the header's own CTA button below the
`lg` (1024px) breakpoint (the mobile conversion bar and drawer already
cover that CTA on smaller screens), giving the logo `min-width: 0` so it
can shrink, switching the `x-large`/`xx-large`/`xxx-large` font-size
tokens in `theme.json` to `clamp()` fluid values, and adding
`overflow-wrap: break-word` on headings plus `overflow-x: hidden` on
`html`/`body` as a defense-in-depth safety net. Re-verified: **zero**
horizontal overflow at 320/375/768/1024/1440px after the fix.

**Real bug found and fixed — mobile nav drawer had no focus return on
its own close (✕) button, and no focus containment:** clicking the
drawer's ✕ button called `closeDrawer()` (a zero-argument function at
the time) with no path to return focus to the hamburger toggle — only
the Escape key did. Also, nothing prevented Tab from leaving the open
drawer into the visually-hidden page content behind it. Fixed in
`assets/js/main.js`: `closeDrawer` now takes a `returnFocus` flag used
by both the ✕ button and Escape; the drawer's siblings get the native
`inert` attribute while open (blocking both focus and screen-reader
exposure of hidden content — no hand-rolled focus-trap loop needed) and
have it removed on close. Verified via direct interaction that
`inert`/`aria-expanded`/`data-open` state changes are all correct;
verifying the visual focus-ring itself was not possible in this specific
automation context because the sandboxed tab lacks real OS window focus
(`document.hasFocus()` is `false` there) — `.focus()` calls are
well-established, standard behavior on a real, foregrounded browser tab,
so this is a tooling limitation on the verification side, not a
reason to doubt the fix.

**Also fixed — color contrast:** the placeholder accent color
(`#a8672b`) gave white button text only a 4.53:1 contrast ratio against
it — technically passes WCAG AA's 4.5:1 minimum but with almost no
margin. Darkened to `#955a26` (5.57:1) for a safer margin while staying
in the same warm-amber family; still explicitly flagged as a
placeholder pending real brand colors (see `DESIGN-SYSTEM.md`). Full
contrast audit of every text/background pair used on the site passed
AA (4.53–16.4:1 range before this change, 5.57–16.4:1 after).
**Type:** Autonomous fixes of real defects found during the explicitly
requested QA passes.

## 2026-08-21 — Performance + SEO technical audit: findings and fixes

**Performance fixes applied:**
- Disabled WordPress core's emoji-detection inline script/style
  (`zeus_disable_emoji_scripts()` in `inc/enqueue.php`) — pure dead
  weight on any modern browser, one fewer script + inline block on
  every page.
- Confirmed: zero webfonts (system font stack only), single ~15KB hand-
  authored stylesheet, one small deferred script, 412 DOM elements on
  the homepage (well under the ~800 "good" / 1500 "ok" guidance),
  `.zeus-card__media` reserves `aspect-ratio: 4/3` so image slots won't
  cause layout shift once real photography is added, and WP core's
  default `loading="lazy"` / responsive `srcset` behavior is untouched
  (no code disables it).
- **Deferred, documented, not yet needed:** there is no hero image in
  the markup yet (placeholder text only — see `PROJECT-SPEC.md`). When
  one is added, it must load eager + `fetchpriority="high"` (it will be
  the LCP element), not the default lazy behavior — tracked in
  `TASKS.md`.
- **Known, accepted, not fixed:** WordPress core still emits its full
  default color/gradient preset CSS custom properties and utility
  classes (`--wp--preset--color--vivid-red` etc.) in `global-styles-
  inline-css`, despite `theme.json` setting `defaultPalette: false` /
  `customGradient: false`. Those settings hide the defaults from the
  block-editor color picker but core still outputs the underlying CSS
  as a compatibility baseline for blocks/patterns that reference those
  slugs. The byte cost is small and gzips well; stripping it further
  risks breaking core-block color classes in future editorial content,
  so this is left as-is.

**SEO fixes applied:**
- **Real bug, now fixed:** the homepage (and every other page relying
  on the site tagline fallback) had **no `<meta name="description">`
  or `og:description` at all** — the WP tagline (`blogdescription`) had
  never been set, and `zeus_output_head_meta()` silently printed nothing
  when the description string was empty. Set a real, factual tagline
  ("Custom cabinets and countertops for Orlando and Central Florida")
  and hardened the code with a guaranteed non-empty fallback so this
  can't silently regress again.
- Verified: canonical URLs present and correct (WP core default),
  Open Graph tags present, one `Organization` JSON-LD block on every
  page, `BreadcrumbList` JSON-LD on every non-front-page, 404s return a
  real 404 status, an out-of-range archive page (`/portfolio/page/2/`
  with nothing on it) correctly 404s rather than serving duplicate/
  empty content, taxonomy archives are non-public as designed (no
  duplicate-content surface), and pagination has no phantom pages.
- **Expected, not a bug:** `/wp-sitemap.xml` returns 404. This is
  WordPress core's own behavior when "discourage search engines from
  indexing this site" is on (`blog_public = 0`) — which this local
  environment deliberately has set (see `HANDOFF.md`). The sitemap will
  come back automatically the moment that setting is turned off for
  staging/production; no code change needed.
- **Recommendation on a dedicated SEO plugin (Yoast or similar):** the
  native implementation is holding up well and is not yet fragile —
  title/meta/canonical/OG/schema are all correctly generated for every
  template with no plugin. The one thing native code won't cleanly cover
  going into pre-launch is **XML sitemap curation at scale** (excluding
  specific URLs, image sitemap entries, sitemap index management) and
  **redirect management** for the eventual old-URL migration. Recommend
  deciding on a sitemap/redirect solution (which may still be native —
  WP core's sitemap plus a small custom redirect table — or Yoast/
  similar) during the pre-launch phase, once the real URL-migration
  scope from `docs/REDIRECT-MAP-DRAFT.csv` is known — not before, and
  not installed now.
**Type:** Autonomous fixes of real defects found during the explicitly
requested audit, plus one documented recommendation deferred to a later
phase rather than acted on now.

## 2026-08-21 — Theme/plugin separation: content model moved to a first-party `zeus-core` plugin

**Decision:** All content-model ownership — `cabinet_collection`/`project`/
`zeus_lead` CPT registration, the 5 taxonomies, `register_post_meta`
definitions, editorial meta boxes and their admin media pickers, initial
content seeding, and the Request Free Consultation form handler — moved
out of the theme into a new first-party plugin at `plugins/zeus-core/`
(linked into the local WordPress install the same way the theme is, via
a Windows directory junction — no admin privilege needed, see
`HANDOFF.md`). The theme keeps everything genuinely presentational:
templates, template-parts/components, `theme.json`, CSS, frontend JS,
and `wp_head` SEO *output* (the underlying `zeus_seo_title`/
`zeus_seo_description`/`zeus_noindex` meta fields are now registered by
the plugin; the theme just reads and renders them).
**Reasoning:** Explicit instruction — the content model shouldn't be
coupled to the presentation theme, since swapping the theme later
(e.g., a redesign) must never risk losing or re-registering the
business's actual data model. No CPT slugs, taxonomy names, or meta
keys changed, so this was fully backward-compatible with the existing
local database — verified directly (see below).
**Verification performed:** switched the active theme to the WordPress
default (`twentytwentyfive`) with `zeus-core` still active — all 4
`cabinet_collection` posts remained fully queryable. Then deactivated
`zeus-core` — `cabinet_collection` correctly disappeared as a
*registered post type* (proving the plugin, not the theme, owns that
registration) while the underlying `wp_posts` rows were confirmed intact
via a direct database query. Reactivated the plugin and the `zeus` theme
and confirmed the site was fully restored.
**Type:** Owner-directed (explicit instruction), executed and verified.

## 2026-08-21 — Seeding safety fix: `get_page_by_path()` silently failed for child pages, causing duplicate content on re-run

**Decision:** Replaced every `get_page_by_path( $slug )` existence check
in the seed logic (and the two theme templates that used the same
pattern for lookups — `front-page.php`, `zeus_consultation_url()`) with
a new `zeus_get_post_by_slug( $slug, $post_type )` helper that queries
by `post_name` directly via `get_posts()`, plus a permanent, append-only
"seed registry" (`plugins/zeus-core/inc/seed-registry.php`) that records
every slug/term/menu ever created so a later run can never resurrect
something the owner deliberately deleted.
**Reasoning — this was a real bug caught by the seeding safety audit,
not a hypothetical:** `get_page_by_path()` requires the *full*
hierarchical path (e.g. `cabinets/kitchen-cabinets`) for a non-top-level
page — passed just the leaf slug (`kitchen-cabinets`), it returns `null`
even when the page exists. The original Phase 2 seed code (and this
plugin's first draft) checked existence with the bare slug. On a second
run, every *child* page (9 of them), 2 of the seeded nav menus' items,
and — via the same pattern in `front-page.php` — the homepage's
service/countertop card links would have silently treated existing
content as missing. This was caught by *actually running the seed
command twice* as part of the audit (not just reading the code): the
first extra run created 9 duplicate pages (WordPress auto-suffixed them
`-2`) and duplicated every item in both nav menus. Both were deleted and
the underlying bug fixed at its root — verified with three follow-up
tests: (1) running the fixed seed command twice in a row now reports
"nothing new" both times; (2) deliberately deleting a seeded page
(`closets`) and re-running the seed command confirmed it does **not**
come back, proving the "never resurrect deleted content" requirement
actually holds; (3) the page was then restored via a deliberate registry
reset + reseed (not automatic) to leave the site in its correct state,
since the deletion had been a test, not a real owner action.
**Type:** Autonomous fix of a genuine defect found during the explicitly
requested safety audit.

## 2026-08-21 — Content seeding is never automatic — explicit trigger only

**Decision:** No seed function is hooked to `init`, plugin activation,
or any other automatic WordPress event. Seeding only runs via `wp zeus
seed` (WP-CLI) or the Tools → ZEUS Setup admin page (`manage_options`
capability + nonce) — both explicit, human-triggered actions. Plugin
activation only provisions infrastructure (the private lead-upload
directory) and flushes rewrite rules; it never creates content.
**Reasoning:** Explicit requirement — "production content creation must
be an explicit controlled migration/setup action," not something that
happens merely because a plugin/theme is active. The previous
(Phase 2) implementation hooked seeding to `init` guarded by an
option flag, which was non-destructive in practice but violated this
principle on its face and would have auto-seeded a fresh production
install the moment the plugin/theme went live there, with no owner
action or visibility into what was created.
**Type:** Owner-directed (explicit instruction).

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
