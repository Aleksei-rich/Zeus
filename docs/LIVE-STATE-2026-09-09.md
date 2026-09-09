# ZEUS live state — 2026-09-09

This file records the verified production/content state so later work does not rely on stale phase notes.

## Production status

- `https://zeuscabinetsflorida.com/` is live and actively being edited.
- WordPress currently has 19 published Pages and 14 published Blog posts.
- The `project` CPT is live and used for Portfolio entries.
- Old WordPress Pages `/contacts/`, `/about-company/`, and `/online-order/` are no longer published Pages. Search-engine appearances can therefore be stale index/cache rather than current WordPress duplicates.

## Portfolio — current source of truth

Published:

- Project 216 — **Completed White Kitchen Cabinet Installation** — featured/gallery attachment 75.
- Project 217 — **Completed Kitchen with Island Seating** — featured/gallery attachment 74.
- Project 371 — **Completed Kitchen Cabinetry Project in Windermere, FL** — gallery attachments 354–356; `service_area=Windermere`, `project_type=Kitchen`.
- Project 372 — **Completed Kitchen Cabinetry Project in Winter Garden, FL** — gallery attachments 357–361; `service_area=Winter Garden`, `project_type=Kitchen`.

Draft:

- Project 218 — **Completed Gray Kitchen Cabinet Installation** — must remain draft while it relies on attachment 77.

## Media safety / provenance

Attachments **77, 126, and 133 are on the project ban-list**. Do not use them as verified completed ZEUS work and do not publish a Portfolio project based on them.

Verified real-project groups currently safe for Portfolio/local-project use:

- 354–356: same completed kitchen project in Windermere, FL.
- 357–361: same kitchen project in Winter Garden, FL.
- 74 and 75: existing completed-kitchen Portfolio images already in published projects 217 and 216 respectively.

Do not infer location, cabinet collection, exact finish name, or countertop material from appearance alone. If a specification is not confirmed in the project record, omit it.

## 2026-09-09 live content improvements

- Windermere and Winter Garden pages were renamed from generic “Photo Set” titles to local kitchen-project titles.
- Their project type is now explicitly `Kitchen`.
- Their SEO titles/descriptions, CTA copy, design-decision fields, content, and image ALT text were improved using only visually verified features and confirmed locations.
- Their slugs remain:
  - `/portfolio/completed-cabinetry-project-windermere-fl/`
  - `/portfolio/completed-cabinetry-project-winter-garden-fl/`
  Keeping the slugs avoids another unnecessary URL change.
- WordPress retained redirects from the former `*-photo-set` slugs.
- Project 217 had a broken measuring-guide link corrected to `/how-to-measure-kitchen-for-cabinet-estimate/`.

## Known production theme blocker

Production still renders banned attachment 77 in the homepage **From Real ZEUS Installations** strip. The code fix already exists on `rebuild/v2` in commit `f80b4bc115a7e3a137967d31d8a2bd7d0bcbef51` (`theme/zeus/inc/visual-polish.php`):

- homepage attachment 77 → 76;
- Cabinets real-work attachment 77 → verified Windermere attachment 354;
- real-work strips expanded with verified 354/357 images.

This code has **not** been deployed to production. The connected WordPress abilities do not expose theme-file editing/deployment, and no automatic GitHub deployment workflow is present. Do not report the homepage image issue as fixed live until a hosting/theme deployment is actually completed and publicly verified.

## Working rule

For Portfolio and “Real ZEUS Work,” truthfulness beats volume. Create/index only projects whose photos and key facts are verified. Generated/design/manufacturer/lifestyle imagery may be used only in clearly non-project contexts and must never be presented as completed ZEUS work.
