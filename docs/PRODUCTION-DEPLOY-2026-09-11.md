# ZEUS production deploy — 2026-09-11

This supersedes the Sep 9 and Sep 10 execution instructions. Use this document for the next cPanel production pass.

## Current live state

Already live — do not redo:

- `inc/visual-polish.php` is active; rejected attachment 77 is no longer shown as Real ZEUS Work.
- All 15 published guides pass the current on-page audit with zero issues.
- Euro / Flat Panel, Shaker, Oslo and Brooklyn individual collection pages pass with zero issues.
- Oslo's obsolete `/cabinets/shaker/` internal link is fixed.
- 34 empty Portfolio gallery ALT values were filled; cache-busting audits confirmed zero missing ALT on all six affected projects.
- Portfolio archive was directly inspected in Google on 2026-09-11 and now returns `PASS / Submitted and indexed`.
- Bathroom planning guide was directly inspected and returns `PASS / Submitted and indexed`.
- Current GSC Wizard tracker: 51 tracked URLs, 33 indexed, 18 not indexed, 0 errors, 0 warnings. The tracker can lag direct URL Inspection.
- Oslo and Laundry/Pantry are `Discovered - currently not indexed`; they have no technical on-page blockers and should not be rewritten just to force indexing.

## Important form issue found before deploy

The source had one inconsistent early server gate:

- form template: 5 files / 15MB total;
- main.js: 15MB total;
- reliability JS: 15MB total;
- multi-upload/reliability PHP handler: 15MB total;
- per-file maximum: 10MB;
- email attachment threshold: 10MB total, with larger accepted sets stored privately and linked from the WordPress Consultation Request;
- but `zeus-core.php` public same-origin endpoint still rejected requests above 10MB total.

This was corrected in commit `5d06005055444f1b44565bf4a38ac10c8276f454` so the customer-facing accepted total is consistently 15MB while the safer email-attachment threshold remains 10MB.

## Preferred execution: one command

The reviewed deployment is packaged as:

`tools/deploy-production-hotfix-2026-09-11.sh`

Run this exact command in the ZEUS cPanel Terminal:

```bash
bash <(curl -fsSL 'https://raw.githubusercontent.com/Aleksei-rich/Zeus/b81e8dd2c73ec030873c2196158a0758146be368/tools/deploy-production-hotfix-2026-09-11.sh')
```

The script itself pins production source files to commit:

`5d06005055444f1b44565bf4a38ac10c8276f454`

Therefore later branch changes cannot silently enter this deploy.

## What the script does

Before changing production it:

1. confirms `/home/zeusiwpo/public_html` is the WordPress root;
2. confirms the active theme is `zeus`;
3. confirms `zeus-core` is active;
4. downloads only the approved files from the pinned commit;
5. syntax-checks every PHP file before installation and JavaScript with `node --check` when Node is available;
6. creates a timestamped backup in `$HOME/zeus-deploy-backups/` and records existing SEO metadata.

It then deploys only the approved SEO/theme and consultation files, re-validates the exact production copies, and applies the four approved page SEO metadata corrections.

It verifies the consultation constants are exactly:

- public accepted total: 15MB;
- reliable handler total: 15MB;
- individual file maximum: 10MB (enforced in upload validation/client code);
- mail-attachment threshold: 10MB.

It also creates a random temporary probe inside `wp-content/uploads/zeus-private-leads`, requests that exact file from the public web, deletes the probe immediately, and reports whether direct access is blocked with HTTP 403/404. This closes the long-standing private-upload verification item with an actual network test rather than assuming `.htaccess` works.

Finally it flushes the WordPress object cache and runs cache-busting content checks for:

- corrected homepage stock/pricing wording;
- 15MB consultation-form copy;
- Kitchen/Bathroom/Home Office/Closets Planning Resources;
- Granite support links;
- Kitchen & Cabinet Guides category H1;
- Countertop Guides category H1.

## Approved files in this deploy

Theme/SEO:

- `theme/zeus/functions.php`
- `theme/zeus/inc/seo-cluster-links.php`
- `theme/zeus/inc/content-safety.php`
- `theme/zeus/page-granite.php`
- `theme/zeus/category.php`
- `theme/zeus/inc/category-seo.php`
- `theme/zeus/inc/breadcrumbs.php`

Consultation UI/pipeline:

- `theme/zeus/template-parts/consultation-form.php`
- `theme/zeus/assets/js/main.js`
- `plugins/zeus-core/zeus-core.php`
- `plugins/zeus-core/inc/consultation-form.php`
- `plugins/zeus-core/inc/consultation-multiupload.php`
- `plugins/zeus-core/inc/consultation-reliability.php`
- `plugins/zeus-core/assets/js/consultation-reliability.js`

Do not deploy unrelated repository files.

## WP metadata corrected by the script

- Bathroom title -> `Bathroom Cabinets Orlando, FL | Vanities | ZEUS`
- Bathroom meta description -> concise <=160-character version
- Countertops title -> `Countertops Orlando, FL | Quartz, Granite & More | ZEUS`
- Porcelain title -> `Porcelain Countertops Orlando, FL | ZEUS`

URLs, H1s and page body content are not changed by these metadata updates.

## After the script

If every cache-busting check is PASS, run the ordinary canonical URL audit from GSC Wizard and create one deployment annotation.

If the script reports content checks as WARN but all PHP/JS validation passed, purge the host/page cache in cPanel and repeat the public checks. Do not redeploy files blindly.

If the private-upload probe returns HTTP 200, treat that as a security blocker: do not ask customers to upload files until host-level denial is fixed. The script removes the probe immediately either way.

## Still outside this deploy

- Google Business Profile currently has a phone/hours mismatch against the website. Correct GBP itself; do not revert the site to older contact data.
- GA4 is not currently connected to GSC Wizard, so Search Console can measure search performance but not site sessions/form conversion.
- Additional Portfolio pages should be created only from visually verified real project groups and should not multiply near-duplicate service-area pages.
