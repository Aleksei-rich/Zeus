# Minimal SEO production deploy

This checklist intentionally avoids DNS, cPanel configuration changes, database edits and `main`.

## Scope
Deploy only the reviewed SEO-related files from `rebuild/v2` after confirming the production theme/plugin paths match the repository package.

## Before upload
1. Confirm branch is `rebuild/v2`.
2. Record the currently deployed copies of every file being replaced so rollback is one file restore.
3. Do not deploy unrelated files.

## Current SEO package files
- `theme/zeus/inc/seo.php`
- `theme/zeus/inc/robots.php`
- `theme/zeus/functions.php`
- the sitemap exclusion implementation already committed on `rebuild/v2` (commit `19512363051856fd2d23ecd5cdccc9cabfcfc0b3`)

## Minimal production action
Upload the reviewed changed files from `rebuild/v2` to the matching active ZEUS theme/plugin paths, preserving a copy of the replaced production files.

## Verification immediately after upload
Check these public URLs in a private browser window:

1. `/` returns 200 and renders normally.
2. `/robots.txt` contains the WordPress rules, AI/search crawler policy and a sitemap line.
3. `/wp-sitemap.xml` returns 200.
4. `/thank-you/` contains `noindex` and is absent from the relevant WordPress sitemap.
5. `/blog/` contains a self-referencing canonical.
6. `/portfolio/` contains a self-referencing canonical.
7. A normal singular service page still has exactly one canonical.
8. Page source contains valid JSON-LD and no fabricated review/rating schema.

## Rollback
If any rendering or PHP error appears, restore only the backed-up files replaced in this deployment. No database rollback should be necessary for this package.
