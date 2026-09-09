# Next Production Deploy — Single Safe Batch

## Scope
Deploy only the reviewed ZEUS theme files from `rebuild/v2`. **Never merge or deploy `main`.** Do not touch WordPress core, the database, Consultation Requests, recovery tables, or form data.

This batch intentionally combines the pending SEO crawler-policy files with the overnight visual/UX corrections so production needs only one controlled theme deploy.

## Files to deploy from `rebuild/v2`

- `theme/zeus/functions.php`
- `theme/zeus/inc/seo.php`
- `theme/zeus/inc/robots.php`
- `theme/zeus/inc/visual-polish.php`
- `theme/zeus/archive-project.php`

If the production theme is missing any dependency already present on `rebuild/v2`, stop rather than improvising or copying unrelated files.

## What this batch changes

### Visual / UX
- clarifies homepage floating-shelf labels so White Oak / Maple / Walnut cannot be read as cabinet colors;
- replaces the owner-rejected homepage image ID 77 with verified real ZEUS image ID 76;
- expands homepage `From Real ZEUS Installations` with verified completed-project images 354 and 357;
- removes owner-rejected image ID 77 from Cabinets > `Real ZEUS Cabinetry Installations`, replacing it with verified project image 354 and adding verified image 357;
- maps Cabinets > Custom Cabinetry to Euro / flat-panel kitchen image ID 153;
- maps Kitchen Cabinets > In-Stock Kitchen Cabinetry to richer Brooklyn Slate image ID 121;
- centers CTA and Portfolio intro/copy instead of leaving text visually pinned left;
- adds one reviewed desktop floating `Request Free Consultation` CTA that hides near existing CTA/form/footer areas and does not duplicate the mobile conversion bar;
- replaces the current production Portfolio placeholder with the Project CPT archive grid while keeping copy limited to verified completed work.

### SEO / crawler policy
- excludes content carrying `zeus_noindex=1` from core WordPress post sitemaps;
- loads the reviewed robots/crawler policy and canonical sitemap reference.

## DO NOT deploy
- `main` branch;
- the entire repository;
- any database dump;
- `wp-config.php`;
- Consultation form/plugin changes not listed above;
- Consultation Requests or recovery-table changes;
- any media deletion.

## Exact safe cPanel deploy block

```text
1. In cPanel File Manager, open the ACTIVE ZEUS theme directory only.
2. Create ONE backup folder beside it named:
   zeus-theme-backup-2026-09-09-before-visual-seo
3. Copy these existing production files into that backup, preserving paths:
   functions.php
   inc/seo.php
   inc/robots.php
   inc/visual-polish.php        (if it already exists)
   archive-project.php
4. From GitHub branch rebuild/v2 — NOT main — download/upload these exact files:
   theme/zeus/functions.php
   theme/zeus/inc/seo.php
   theme/zeus/inc/robots.php
   theme/zeus/inc/visual-polish.php
   theme/zeus/archive-project.php
5. Preserve the same relative paths inside the active theme.
6. If cPanel provides PHP lint/syntax check, run it on all 5 deployed PHP files.
   Any syntax error = STOP and restore the backup files immediately.
7. Purge the WordPress/page cache and any server/CDN cache available in cPanel.
8. Verify production in this order:
   a) Homepage loads with HTTP 200 and normal header/footer.
   b) Homepage floating-shelf copy clearly says the labels refer to shelves/material, not cabinet color.
   c) From Real ZEUS Installations no longer shows the rejected gray image ID 77 and shows the expanded verified photo set.
   d) /cabinets/ Custom Cabinetry shows the Euro/flat-panel kitchen.
   e) /cabinets/ Real ZEUS Cabinetry Installations contains no image ID 77 and includes the additional verified project photography.
   f) /cabinets/kitchen-cabinets/ In-Stock Kitchen Cabinetry shows the richer replacement kitchen.
   g) /portfolio/ shows the currently published real Project CPT cards instead of the placeholder, with centered concise intro copy.
   h) Desktop: one floating Request Free Consultation CTA follows scroll and disappears near CTA/form/footer sections.
   i) Mobile: no extra overlapping desktop floating CTA; existing mobile conversion controls remain usable.
   j) Consultation form submits/renders exactly as before — do not edit it during verification.
   k) /wp-sitemap-posts-page-1.xml does NOT include /thank-you/.
   l) /thank-you/ still contains noindex,follow.
   m) /robots.txt points to /wp-sitemap.xml and normal public crawler rules are present.
   n) /wp-sitemap.xml returns successfully.
9. If ANY rendering, PHP, form, or navigation regression appears, restore ONLY the backed-up theme files from step 3 and purge caches again.
```

## Current production evidence before deploy
As of 2026-09-09, fresh live and WordPress checks show:
- the homepage still uses the old three-image `From Real ZEUS Installations` row including the rejected gray kitchen image;
- `/cabinets/` still uses the pre-polish template output, so the reviewed Custom Cabinetry and expanded real-work mappings have not reached production;
- `/portfolio/` still renders the production placeholder rather than the Project CPT grid;
- WordPress currently has four published Project CPT records: Windermere Cabinetry Project — Photo Set, Winter Garden Cabinetry Project — Photo Set, Completed White Kitchen Cabinet Installation, and Completed Kitchen with Island Seating;
- the rejected gray-kitchen project remains draft and must not be republished or reused merely to increase portfolio count.

This confirms the remaining branch changes have not fully reached production and the single deploy block remains necessary.

## Rollback
Restore the five backed-up theme files only, purge caches, and recheck homepage, `/portfolio/`, `/cabinets/`, `/cabinets/kitchen-cabinets/`, `/consultation/`, and the sitemap/robots URLs. No database rollback should be performed for this batch.
