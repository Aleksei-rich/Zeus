# ZEUS production deploy package — 2026-09-09

This is the minimum controlled theme deployment required after the 2026-09-09 live SEO / media audit.

## Goal

Deploy only the source changes that cannot be applied through the connected WordPress content API:

1. remove banned attachment 77 from public Real ZEUS Work usage;
2. expand Real ZEUS Work only with verified project imagery;
3. add server-rendered topic-cluster links between commercial pages and their supporting guides;
4. strengthen the Granite commercial page's two-way internal-link cluster.

Do not bulk-deploy unrelated repository files.

## Files to deploy

From branch `rebuild/v2`:

1. `theme/zeus/functions.php`
   - loads the new `inc/seo-cluster-links.php` module.

2. `theme/zeus/inc/seo-cluster-links.php`
   - new server-rendered Planning Guides section for:
     - Kitchen Cabinets
     - Bathroom Cabinets & Vanities
     - Home Office
     - Custom Closets
   - links commercial pages to their relevant support guides without changing existing page-template structure.

3. `theme/zeus/inc/visual-polish.php`
   - homepage banned attachment 77 -> attachment 76;
   - Cabinets banned attachment 77 -> verified Windermere attachment 354;
   - adds verified project imagery 354 / 357 to Real ZEUS Work strips;
   - retains previously approved visual/UX refinements.

4. `theme/zeus/page-granite.php`
   - adds direct links to `/how-to-care-for-granite-countertops/` and `/quartz-vs-granite-vs-porcelain-vs-marble-countertops/` so the Granite commercial page and its support content form a two-way topic cluster.

## Production path

WordPress production root previously verified as:

`/home/zeusiwpo/public_html`

Theme destination:

`/home/zeusiwpo/public_html/wp-content/themes/zeus/`

## Required deployment sequence

1. Confirm the production theme path before writing.
2. Back up each current production destination file with a timestamp.
3. Put the four source files above into their matching paths only.
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
11. Confirm Portfolio archive still renders the current published project grid.
12. Verify the private lead-upload directory is not directly accessible from the public web while host access is available.

## Pre-deploy validation already completed

- Live on-page SEO audit of Home, Kitchen, Bathroom, Home Office, Closets, Granite, Shaker and Portfolio: all 8 HTTP 200, indexable, self-canonical, one H1, structured data present, zero missing image ALT, no critical/high/medium SEO issues.
- `functions.php` change linted locally: no PHP syntax errors.
- `inc/seo-cluster-links.php` linted locally: no PHP syntax errors.
- `page-granite.php` change is limited to two internal links and was committed separately.
- `visual-polish.php` is the previously reviewed attachment-77 fix already loaded by the theme bootstrap.

## Do not do during this deploy

- Do not publish Project 218 / the gray kitchen project.
- Do not re-add attachment 77 as verified real ZEUS work.
- Do not mass-rewrite Kitchen/Bathroom/Home Office/Closets templates; the live audit found their structure technically clean. The new cluster module provides the needed support links with lower deployment risk.
- Do not mass-request Google indexing after deployment. GSC Wizard hourly tracking is active and the core pages were newly/re-confirmed indexed on 2026-09-09.
- Do not deploy unrelated `rebuild/v2` files.

## Current source commits

- `f80b4bc115a7e3a137967d31d8a2bd7d0bcbef51` — banned-image / verified-project visual fix.
- `2f0ec7a11226738cd052683aca5544cfe65e4de6` — Granite internal links.
- `f18b917e4a86cb6bfaf63c97141de6a4595d62d6` — commercial SEO cluster module.
- `a8c6dea284f6b1aeffb6cd592535e0a7c56f4a89` — load cluster module from theme bootstrap.
