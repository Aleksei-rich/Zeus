# ZEUS minimal production deploy — 2026-09-10

This supersedes the Sep 9 deployment checklist and earlier Sep 10 drafts. It contains only the remaining production work.

## Verified production state before this deploy

Do NOT redo work that is already live:

- `inc/visual-polish.php` is already active on production.
- Rejected attachment 77 is no longer rendered in the homepage Real ZEUS Work strip.
- `visual-polish.php` text changes such as `Floating Shelf — White Oak / Maple / Walnut` are live, confirming the module itself is loaded.
- Homepage Countertop material-card excerpts have already updated from WordPress DB.
- All 15 published guides were audited on 2026-09-10 and now have zero on-page SEO issues.
- Project 411 and Marble Care Guide post 415 are live and tracked.
- Euro / Flat Panel, Shaker, Oslo and Brooklyn singular collection pages were cleaned up live and re-audited with zero issues.
- Oslo's old internal link `/cabinets/shaker/` was corrected live to `/cabinet-styles/shaker/`.
- 34 previously empty ALT fields across six verified Portfolio project galleries were filled in WordPress. Cache-busting audits confirmed zero missing ALT on all six pages; ordinary cached URLs may continue showing the pre-edit HTML until cache purge/expiry.
- Legacy `/contacts/`, `/online-order/`, and `/about-company/` already return one-hop 301 redirects to `/contact/`, `/consultation/`, and `/about/` respectively.
- Legacy `/feedback/` and `/faq/` return 404, have no GSC impressions in the checked Jun 1–Sep 8 window, and are absent from the current sitemap. Leave them 404 unless future backlink data gives a reason to map them to an equivalent page.

## Remaining theme files to deploy

From branch `rebuild/v2`, deploy ONLY these seven files to the active production theme:

1. `theme/zeus/functions.php`
   - loads the remaining SEO/content modules including category SEO.

2. `theme/zeus/inc/seo-cluster-links.php`
   - adds server-rendered Planning Resources to Kitchen, Bathroom, Home Office and Closets commercial pages.

3. `theme/zeus/inc/content-safety.php`
   - corrects the internally inconsistent homepage stock wording;
   - narrows the overly broad custom-cabinetry statement;
   - removes the absolute `no surprises on the final invoice` claim;
   - corrects Cabinets page stock wording so Euro / Flat Panel is not presented as an in-stock collection;
   - trims the Cabinet Styles archive title/description for search snippets.

4. `theme/zeus/page-granite.php`
   - adds two-way support links to Granite Care and Countertop Material Comparison.

5. `theme/zeus/category.php`
   - gives editorial category archives a real H1, term introduction and normal guide-card loop.

6. `theme/zeus/inc/category-seo.php`
   - gives category archives their term-specific meta description, self-canonical and Open Graph URL/description instead of the generic site fallback.

7. `theme/zeus/inc/breadcrumbs.php`
   - adds `Home → Blog → Category` breadcrumb handling so category pages also receive BreadcrumbList data.

Do NOT redeploy `inc/visual-polish.php` unless a production checksum comparison unexpectedly proves it differs from current `rebuild/v2` source.

## Production paths

WordPress root:
`/home/zeusiwpo/public_html`

Theme root:
`/home/zeusiwpo/public_html/wp-content/themes/zeus`

## Required safe sequence

1. `cd /home/zeusiwpo/public_html`
2. Confirm the active theme and destination paths before writing.
3. Create timestamped backups of every existing destination file. For the two new files (`category.php`, `inc/category-seo.php`), record whether a production file already exists before overwriting.
4. Copy only the seven source files listed above from `rebuild/v2` into matching production paths.
5. Run `php -l` on all seven PHP files. The newly created category files were already linted successfully in the working environment before commit.
6. If any lint fails, restore backups and stop.
7. Run the WP-CLI SEO-meta corrections below.
8. Purge WordPress/server/page cache. This is required not only for the theme files but also so the already-updated Portfolio ALT values replace stale cached HTML.
9. Verify all URLs and expected strings below.

## WP-CLI SEO metadata corrections

The connected WordPress content API cannot write the registered `zeus_seo_title` / `zeus_seo_description` fields on normal Pages. Live audits identified only these remaining commercial metadata length issues.

Run from `/home/zeusiwpo/public_html`:

```bash
wp post meta update 11 zeus_seo_title 'Bathroom Cabinets Orlando, FL | Vanities | ZEUS'
wp post meta update 11 zeus_seo_description 'Bathroom cabinets and vanities in Orlando, FL, with in-stock and custom options, storage planning, countertops, delivery, and installation from ZEUS.'
wp post meta update 12 zeus_seo_title 'Countertops Orlando, FL | Quartz, Granite & More | ZEUS'
wp post meta update 15 zeus_seo_title 'Porcelain Countertops Orlando, FL | ZEUS'
```

Confirm:

```bash
wp post meta get 11 zeus_seo_title
wp post meta get 11 zeus_seo_description
wp post meta get 12 zeus_seo_title
wp post meta get 15 zeus_seo_title
```

Do not alter URLs, H1s, slugs or page body content as part of these metadata corrections.

## Post-deploy verification

### Homepage `/`
- HTTP 200.
- Attachment 77 is still absent from Real ZEUS Work.
- Heading is `Popular Cabinet Styles & Finishes` rather than `Popular Styles, Ready to Move`.
- Collection copy no longer implies every displayed collection is stocked.
- `custom cabinetry covers everything else` is gone.
- `no surprises on the final invoice` is gone.

### Cabinets `/cabinets/`
- HTTP 200.
- The in-stock card names Shaker / Slim Shaker Oslo / Brooklyn and explicitly states Euro / Flat Panel is not kept in stock.
- The four-collection section is labelled `Popular Cabinet Collections`, not `In-Stock Cabinet Collections`.
- Its intro says stock availability varies by collection and does not state that Euro / Flat Panel is stocked.

### Kitchen `/cabinets/kitchen-cabinets/`
- HTTP 200.
- Server-rendered Planning Resources exists and guide links return 200.

### Bathroom `/cabinets/bathroom-cabinets-vanities/`
- HTTP 200.
- Planning Resources exists.
- Browser title: `Bathroom Cabinets Orlando, FL | Vanities | ZEUS`.
- Meta description is the new <=160-character version.
- H1 and URL unchanged.

### Home Office `/custom-spaces/home-office/`
- HTTP 200 and Planning Resources exists.

### Closets `/custom-spaces/closets/`
- HTTP 200 and Planning Resources exists.

### Countertops `/countertops/`
- HTTP 200.
- Browser title: `Countertops Orlando, FL | Quartz, Granite & More | ZEUS`.
- H1 and URL unchanged.

### Porcelain `/countertops/porcelain/`
- HTTP 200.
- Browser title: `Porcelain Countertops Orlando, FL | ZEUS`.
- H1 and URL unchanged.

### Granite `/countertops/granite/`
- HTTP 200.
- Direct links to `/how-to-care-for-granite-countertops/` and `/quartz-vs-granite-vs-porcelain-vs-marble-countertops/` are present.

### Cabinet Styles `/cabinet-styles/`
- HTTP 200/indexable.
- Shortened archive title/meta are live.
- Individual Euro / Shaker / Oslo / Brooklyn pages retain their already-cleaned live SEO fields.

### Editorial categories
Verify both:
- `/category/kitchen-cabinet-guides/`
- `/category/countertop-guides/`

Each must have:
- HTTP 200 and indexable status;
- exactly one H1;
- visible category description;
- self-canonical;
- term-specific meta description;
- normal article cards;
- breadcrumb `Home → Blog → [Category]`;
- no duplicate meta-description tags.

### Portfolio `/portfolio/`
- HTTP 200/indexable and existing published projects render normally.
- Re-audit the six service-area project pages that previously showed missing ALT. After cache purge, each must show `imagesMissingAlt: 0` on its ordinary canonical URL, matching the already-confirmed cache-busting audit.

## Live WordPress DB work that must NOT be reverted

- All 15 published guides have cleaned-up titles/meta and passed a 15-page audit with zero issues on 2026-09-10.
- Bathroom guide includes in-stock-vs-custom vanity guidance.
- Closets guide includes whole-home-storage context.
- Home Office guide title is `Orlando Home Office Guide`.
- Project 371 contains the factual Windermere → Countertops internal link without asserting material.
- Project 411 is published from verified media 302–304.
- Marble Care Guide post 415 is published and linked from the Marble commercial page.
- Marble commercial page links back to the care guide.
- Quartz/Granite/Porcelain/Marble excerpts are updated.
- Category 34 description is the Orlando/Central Florida Kitchen & Cabinet Guides introduction.
- Category 35 description is the Orlando/Central Florida Countertop Guides introduction.
- Euro / Flat Panel SEO title/description, Shaker SEO title, Oslo SEO description and Brooklyn SEO description were shortened live; all four collection pages re-audited with zero issues.
- Oslo content now links to `/cabinet-styles/shaker/`, not the obsolete `/cabinets/shaker/` path.
- Portfolio media ALT values updated for IDs 269–272, 274–277, 279–286, 291–297, 313–316 and 347–353.

## Do not do

- Do not deploy unrelated repository files.
- Do not publish draft Project 218.
- Do not reintroduce attachment 77 as real ZEUS work.
- Do not change slugs/URLs.
- Do not mass-request Google URL inspections afterward; Indexing Tracker hourly checks are active.
- Do not create redirects for `/feedback/` or `/faq/` just to eliminate a 404; no equivalent live page or search demand currently justifies it.
- Do not undo the live WordPress DB edits listed above.
- Do not create more near-duplicate city/service-area Portfolio pages until remaining real photography is visually classified and each page can be differentiated by actual project content.

## Local SEO item outside this server deploy

Public Google Business Profile data does not currently match the approved site data for phone/hours. The website consistently uses `(689) 222-3077` and Monday–Friday 9 AM–7 PM. Current public GBP data still shows `407-577-8996`, Monday–Friday roughly 9 AM–6 PM and Saturday hours. A Google Business Profile connection should be used to verify and, if appropriate, correct GBP itself rather than changing the site back to older contact data. A recent LinkedIn post is also publicly discoverable with the older `407-577-8996` phone number and should be cleaned up separately.

## Current measurement baseline

Latest settled GSC data through 2026-09-08:
- 10 clicks / 565 impressions in the latest 7-day summary available during the audit.
- Indexing Tracker improved to 24 indexed URLs with zero errors and zero warnings during the Sep 10 pass.
- Euro / Flat Panel is already near page one (~position 13) and should not be aggressively rewritten.
- `custom home office orlando` is around position 17 and resolves only to the correct Home Office commercial page.
- `bathroom cabinets orlando` is around position 17.7 and the Bathroom commercial page is the clear intended landing page across the Orlando bathroom/vanity query family.
- `bathroom vanity orlando` is around position 17.5.
- `countertops in windermere fl` is around position 21.6.
- `shaker style cabinets in orlando` is led by the dedicated Shaker page around position 15; Kitchen appears much lower and does not require aggressive cannibalization fixes.
- The two category archives currently have zero impressions but are in the sitemap and had missing-H1/missing-canonical errors before the prepared source fix.

After deploy, run the live audits again, create a GSC annotation, and allow crawl/data maturation before judging impact.
