# ZEUS production deploy package — 2026-09-09

This is the minimum controlled theme deployment required after the 2026-09-09 live SEO / media audit and the overnight follow-up pass.

## Goal

Deploy only the source changes that cannot be applied through the connected WordPress content API:

1. remove banned attachment 77 from public Real ZEUS Work usage;
2. expand Real ZEUS Work only with verified project imagery;
3. add server-rendered topic-cluster links between commercial pages and their supporting guides;
4. strengthen the Granite commercial page's two-way internal-link cluster;
5. correct a small set of homepage copy that was internally inconsistent or too absolute.

Do not bulk-deploy unrelated repository files.

## Files to deploy

From branch `rebuild/v2`:

1. `theme/zeus/functions.php`
   - loads `inc/seo-cluster-links.php`;
   - loads `inc/content-safety.php`.

2. `theme/zeus/inc/seo-cluster-links.php`
   - server-rendered Planning Guides section for:
     - Kitchen Cabinets
     - Bathroom Cabinets & Vanities
     - Home Office
     - Custom Closets
   - links commercial pages to relevant support guides without rewriting the large page templates.

3. `theme/zeus/inc/content-safety.php`
   - changes the homepage collection heading from `Popular Styles, Ready to Move` to `Popular Cabinet Styles & Finishes`;
   - removes the incorrect implication that every displayed collection is stocked, because Euro / Flat Panel is explicitly not kept in stock;
   - replaces `custom cabinetry covers everything else` with a narrower factual statement;
   - replaces the absolute `no surprises on the final invoice` wording with documented-scope/pricing wording.

4. `theme/zeus/inc/visual-polish.php`
   - homepage banned attachment 77 -> attachment 76;
   - Cabinets banned attachment 77 -> verified Windermere attachment 354;
   - adds verified project imagery 354 / 357 to Real ZEUS Work strips;
   - retains previously approved visual/UX refinements.

5. `theme/zeus/page-granite.php`
   - adds direct links to `/how-to-care-for-granite-countertops/` and `/quartz-vs-granite-vs-porcelain-vs-marble-countertops/` so the Granite commercial page and its support content form a two-way topic cluster.

## Production path

WordPress production root previously verified as:

`/home/zeusiwpo/public_html`

Theme destination:

`/home/zeusiwpo/public_html/wp-content/themes/zeus/`

## Required deployment sequence

1. Confirm the production theme path before writing.
2. Back up each current production destination file with a timestamp.
3. Put the five source files above into their matching paths only.
4. Run `php -l` on every changed PHP file before considering deployment complete.
5. If any lint check fails, restore the backups immediately and do not continue.
6. Purge WordPress/server/page cache available on the host.
7. Check HTTP 200 and visible rendering for:
   - `/`
   - `/cabinets/`
   - `/cabinets/kitchen-cabinets/`
   - `/cabinets/bathroom-cabinets-vanities/`
   - `/custom-spaces/home-office/`
   - `/custom-spaces/closets/`
   - `/countertops/granite/`
   - `/portfolio/`
8. Confirm attachment 77 no longer renders in public Real ZEUS Work sections.
9. Confirm Planning Guides renders server-side on Kitchen, Bathroom, Home Office and Closets and that every guide link returns HTTP 200.
10. Confirm Granite shows both new support-guide links.
11. Confirm homepage wording is internally consistent about stock availability and no longer contains the absolute final-invoice claim.
12. Confirm Portfolio archive still renders the current published project grid.
13. Verify the private lead-upload directory is not directly accessible from the public web while host access is available.

## Live WordPress edits already completed — do not overwrite/revert

These edits are already stored in production WordPress and do not require theme deployment:

- Windermere Project 371 now includes a factual local link to the Countertops hub without asserting the project's countertop material.
- `How to Measure Your Kitchen for a Cabinet Estimate` (post 198) now uses a contextual `kitchen cabinets in Orlando` link.
- `How Long Does Cabinet Installation Take?` (post 188) now uses a contextual `kitchen cabinets in Orlando` link.
- Quartz, Granite, Porcelain and Marble page excerpts (IDs 13–16) were rewritten to be concise and less absolute; these excerpts feed homepage material cards when cache turns over.
- Earlier live support edits remain in place for Granite, Shaker, Bathroom, Home Office and Closets.

## Pre-deploy validation already completed

- Live on-page SEO audit of Home, Kitchen, Bathroom, Home Office, Closets, Granite, Shaker and Portfolio: all 8 HTTP 200, indexable, self-canonical, one H1, structured data present, zero missing image ALT, no critical/high/medium SEO issues.
- `functions.php` syntax checked after module-loading changes.
- `inc/seo-cluster-links.php` syntax checked.
- `inc/content-safety.php` is a small gettext-only module with no layout/database writes.
- `page-granite.php` change is limited to two internal links and was committed separately.
- `visual-polish.php` is the previously reviewed attachment-77 fix already loaded by the theme bootstrap.
- Public-site check after Work limits ended confirmed the production homepage still showed the old gray attachment 77, so the theme package should be considered NOT DEPLOYED until the verification sequence above passes.

## Do not do during this deploy

- Do not publish Project 218 / the gray kitchen project.
- Do not re-add attachment 77 as verified real ZEUS work.
- Do not mass-rewrite Kitchen/Bathroom/Home Office/Closets templates; the live audit found their structure technically clean. The cluster module provides the needed support links with lower deployment risk.
- Do not mass-request Google indexing after deployment. GSC Wizard hourly tracking is active and core pages were newly/re-confirmed indexed on 2026-09-09.
- Do not deploy unrelated `rebuild/v2` files.
- Do not change WordPress DB content as part of this theme-file deploy.

## Current source commits

- `f80b4bc115a7e3a137967d31d8a2bd7d0bcbef51` — banned-image / verified-project visual fix.
- `2f0ec7a11226738cd052683aca5544cfe65e4de6` — Granite internal links.
- `f18b917e4a86cb6bfaf63c97141de6a4595d62d6` — commercial SEO cluster module.
- `f56ecd7588a40590fd19d3003601b63c47d84b85` — homepage factual-copy correction module.
- `0e819ed6d3a0131a3d056f72121388559e760b1c` — load content-safety module from theme bootstrap.
