# ZEUS minimal production deploy — 2026-09-10

This supersedes the Sep 9 deployment checklist for the remaining work only.

## Verified production state before this deploy

Do NOT redo work that is already live:

- `inc/visual-polish.php` is already active on production.
- Rejected attachment 77 is no longer rendered in the homepage Real ZEUS Work strip; the verified bathroom vanity image is rendered instead.
- `visual-polish.php` gettext changes are also live (for example `Floating Shelf — White Oak / Maple / Walnut`), which confirms the whole visual-polish module is loaded, not only a database/media swap.
- Homepage Countertop material-card excerpts have already updated from WordPress DB.
- All 15 published guides were audited on 2026-09-10 and now have zero on-page SEO issues.
- New Project 411 and Marble Care Guide post 415 are already live and tracked.

## Remaining theme files to deploy

From branch `rebuild/v2`, deploy ONLY these four files to the active production theme:

1. `theme/zeus/functions.php`
   - required because it loads both remaining modules below.

2. `theme/zeus/inc/seo-cluster-links.php`
   - adds server-rendered Planning Resources to Kitchen, Bathroom, Home Office and Closets commercial pages.

3. `theme/zeus/inc/content-safety.php`
   - corrects the internally inconsistent homepage stock wording;
   - narrows the overly broad custom-cabinetry statement;
   - removes the absolute `no surprises on the final invoice` claim.

4. `theme/zeus/page-granite.php`
   - adds two-way support links to Granite Care and Countertop Material Comparison.

Do NOT redeploy `inc/visual-polish.php` unless a production checksum comparison unexpectedly proves it differs from the current `rebuild/v2` source.

## Production paths

WordPress root:
`/home/zeusiwpo/public_html`

Theme root:
`/home/zeusiwpo/public_html/wp-content/themes/zeus`

## Required safe sequence

1. `cd /home/zeusiwpo/public_html`
2. Confirm the active theme and destination paths before writing.
3. Create timestamped backups of the four production destination files that already exist. For new module files, record whether they already exist before overwriting.
4. Copy only the four source files listed above from `rebuild/v2` into the matching production paths.
5. Run `php -l` on all four PHP files.
6. If any lint fails, restore backups and stop.
7. Run the WP-CLI SEO-meta corrections below.
8. Purge WordPress/server/page cache.
9. Verify all URLs and expected strings below.

## WP-CLI SEO metadata corrections

The connected WordPress content API cannot write the registered `zeus_seo_title` / `zeus_seo_description` fields on normal Pages. Live audits identified only three remaining commercial-page metadata length issues.

Run from `/home/zeusiwpo/public_html`:

```bash
wp post meta update 11 zeus_seo_title 'Bathroom Cabinets Orlando, FL | Vanities | ZEUS'
wp post meta update 11 zeus_seo_description 'Bathroom cabinets and vanities in Orlando, FL, with in-stock and custom options, storage planning, countertops, delivery, and installation from ZEUS.'
wp post meta update 12 zeus_seo_title 'Countertops Orlando, FL | Quartz, Granite & More | ZEUS'
wp post meta update 15 zeus_seo_title 'Porcelain Countertops Orlando, FL | ZEUS'
```

Then confirm:

```bash
wp post meta get 11 zeus_seo_title
wp post meta get 11 zeus_seo_description
wp post meta get 12 zeus_seo_title
wp post meta get 15 zeus_seo_title
```

Do not alter URLs, H1s, slugs, or page body content as part of these metadata corrections.

## Post-deploy verification

All of the following must be true before marking deploy complete:

### Homepage `/`
- HTTP 200.
- Still no rejected attachment 77 in Real ZEUS Work.
- Heading is `Popular Cabinet Styles & Finishes` rather than `Popular Styles, Ready to Move`.
- Collection copy says stock availability varies by collection and no longer claims all displayed collections are stocked.
- The `custom cabinetry covers everything else` sentence is gone.
- The `no surprises on the final invoice` sentence is gone.

### Kitchen `/cabinets/kitchen-cabinets/`
- HTTP 200.
- Server-rendered Planning Resources section exists.
- Relevant guide links return HTTP 200.

### Bathroom `/cabinets/bathroom-cabinets-vanities/`
- HTTP 200.
- Planning Resources section exists.
- Browser title is `Bathroom Cabinets Orlando, FL | Vanities | ZEUS`.
- Meta description is the new <=160-character description above.
- H1 and URL are unchanged.

### Home Office `/custom-spaces/home-office/`
- HTTP 200.
- Planning Resources section exists.

### Closets `/custom-spaces/closets/`
- HTTP 200.
- Planning Resources section exists.

### Countertops `/countertops/`
- HTTP 200.
- Browser title is `Countertops Orlando, FL | Quartz, Granite & More | ZEUS`.
- H1 and URL are unchanged.

### Porcelain `/countertops/porcelain/`
- HTTP 200.
- Browser title is `Porcelain Countertops Orlando, FL | ZEUS`.
- H1 and URL are unchanged.

### Granite `/countertops/granite/`
- HTTP 200.
- Direct links to `/how-to-care-for-granite-countertops/` and `/quartz-vs-granite-vs-porcelain-vs-marble-countertops/` are present.

### Portfolio `/portfolio/`
- HTTP 200 and indexable.
- Existing published projects still render normally.

## Live WordPress DB work that must NOT be reverted

- All 15 published guides have cleaned-up titles/meta and passed an all-guides audit with zero issues on 2026-09-10.
- Bathroom guide includes an in-stock-vs-custom vanity section.
- Closets guide includes whole-home storage context.
- Home Office guide title is `Orlando Home Office Guide`.
- Project 371 includes a factual Windermere -> Countertops internal link without asserting material.
- Project 411 is published from verified media 302–304.
- Marble Care Guide post 415 is published and linked from the Marble commercial page.
- Marble commercial page body includes the new care-guide link.
- Countertop excerpts for Quartz/Granite/Porcelain/Marble are already updated.

## Do not do

- Do not deploy unrelated repository files.
- Do not publish draft Project 218.
- Do not reintroduce attachment 77 as real ZEUS work.
- Do not change slugs/URLs during this deploy.
- Do not mass-request Google URL inspections afterward; Indexing Tracker hourly checks are already active.
- Do not undo the live WordPress DB edits listed above.

## Current measurement baseline

Latest settled GSC data through 2026-09-08:
- 10 clicks / 565 impressions in the current available reporting window.
- Euro / Flat Panel is already near page one (~position 13 on recent page data) and should not be aggressively rewritten.
- `custom home office orlando` is around position 17.
- `bathroom vanity orlando` is around position 17.5.
- `countertops in windermere fl` is around position 21.6.

After deploy, record a GSC annotation and wait for crawl/data maturation before judging impact.
