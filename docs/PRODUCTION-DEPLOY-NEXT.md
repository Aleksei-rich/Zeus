# Minimal Next Production Deploy

## Scope
Deploy only the updated ZEUS theme files from `rebuild/v2`. Do not merge or deploy `main`.

## Required next deploy
The immediate production gap is the sitemap filter already committed in `19512363051856fd2d23ecd5cdccc9cabfcfc0b3`:

- `theme/zeus/inc/seo.php` — excludes content with `zeus_noindex=1` from core WordPress post sitemaps.

The branch also contains the crawler-policy addition:

- `theme/zeus/inc/robots.php`
- `theme/zeus/functions.php` — loads `inc/robots.php`.

## Shortest safe manual production action
Upload/replace those three files from `rebuild/v2` in the active ZEUS theme, preserving the same relative paths. Do not upload the whole repository and do not touch WordPress core files.

## Verification immediately after deploy
1. Open `/wp-sitemap-posts-page-1.xml` and confirm `/thank-you/` is absent.
2. Open `/thank-you/` and confirm its HTML still contains `noindex,follow`.
3. Open `/robots.txt` and confirm the sitemap line points to `/wp-sitemap.xml` and public crawler rules are present.
4. Open `/wp-sitemap.xml` and confirm it returns successfully.
5. Recheck the homepage and one service page to ensure normal rendering.

## Rollback
Restore the previous copies of only the three theme files above. No database or DNS change is required.
