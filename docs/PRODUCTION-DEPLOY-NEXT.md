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
- maps Cabinets > Custom Cabinetry to Euro / flat-panel kitchen image ID 153;
- maps Kitchen Cabinets > In-Stock Kitchen Cabinetry to richer Brooklyn Slate image ID 121;
- centers CTA and Portfolio intro/copy instead of leaving text visually pinned left;
- adds one reviewed desktop floating `Request Free Consultation` CTA that hides near existing CTA/form/footer areas and does not duplicate the mobile conversion bar;
- makes the Portfolio archive render the published Project CPT grid instead of the old empty-placeholder presentation.

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
   c) From Real ZEUS Installations no longer shows the rejected gray image ID 77.
   d) /cabinets/ Custom Cabinetry shows the Euro/flat-panel kitchen.
   e) /cabinets/kitchen-cabinets/ In-Stock Kitchen Cabinetry shows the richer replacement kitchen.
   f) /portfolio/ shows the published real project cards instead of the old placeholder.
   g) Desktop: one floating Request Free Consultation CTA follows scroll and disappears near CTA/form/footer sections.
   h) Mobile: no extra overlapping desktop floating CTA; existing mobile conversion controls remain usable.
   i) Consultation form submits/renders exactly as before — do not edit it during verification.
   j) /wp-sitemap-posts-page-1.xml does NOT include /thank-you/.
   k) /thank-you/ still contains noindex,follow.
   l) /robots.txt points to /wp-sitemap.xml and normal public crawler rules are present.
   m) /wp-sitemap.xml returns successfully.
9. If ANY rendering, PHP, form, or navigation regression appears, restore ONLY the backed-up theme files from step 3 and purge caches again.
```

## Current production evidence before deploy
As of 2026-09-09, live checks still show the old homepage `From Real ZEUS Installations` trio including the rejected gray kitchen image, and `/portfolio/` still exposes the old placeholder copy. This confirms the branch changes have not yet reached production and the deploy remains necessary.

## Rollback
Restore the five backed-up theme files only, purge caches, and recheck homepage, `/portfolio/`, `/cabinets/`, `/cabinets/kitchen-cabinets/`, `/consultation/`, and the sitemap/robots URLs. No database rollback should be performed for this batch.
