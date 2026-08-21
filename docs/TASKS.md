# Tasks

Live task list. Update continuously — do not let this drift from reality.
Status values: `todo`, `in-progress`, `done`, `blocked`.

## Phase 0 — Project control system

- [x] Initialize local git repo, branch `rebuild/v2`
- [x] Create `CLAUDE.md`
- [x] Create `docs/PROJECT-SPEC.md`
- [x] Create `docs/SITE-ARCHITECTURE.md`
- [x] Create `docs/DESIGN-SYSTEM.md`
- [x] Create `docs/SEO-STRATEGY.md`
- [x] Create `docs/CONTENT-MODEL.md`
- [x] Create `docs/TECHNICAL-ARCHITECTURE.md`
- [x] Create `docs/SECURITY-AND-DEPLOYMENT.md`
- [x] Create `docs/DECISIONS.md`
- [x] Create `docs/TASKS.md` (this file)
- [x] Create `docs/HANDOFF.md`
- [x] Create `.claude/rules/` safety rule set
- [ ] Commit Phase 0 milestone
- [ ] Local WordPress readiness assessment (LocalWP detected — see
      `HANDOFF.md`; existing "zeus" site is old-site mirror, not reusable)
- [ ] Stand up a clean local WordPress environment for this project

## Phase 1 — Local environment (next up)

- [ ] Decide + execute: bundled-PHP/WP-CLI standalone environment
      (autonomous) vs. LocalWP GUI new-site creation (needs one owner
      click) — attempt autonomous path first
- [ ] Fresh WordPress core install, no old-site import
- [ ] Install baseline plugin set (Yoast SEO, ACF, forms solution) and
      justify each in `DECISIONS.md`
- [ ] Scaffold custom block theme skeleton (`theme.json`, base templates)

## Phase 2 — Theme & content model build (not started)

- [ ] Register `cabinet_collection` CPT + `finish` taxonomy
- [ ] Register `project` CPT + `project_type`/`service_area`/
      `cabinetry_style`/`countertop_material` taxonomies
- [ ] Build homepage template (13-section structure)
- [ ] Build Cabinets hub + Kitchen/Bathroom service pages
- [ ] Build 4 Cabinet Collection pages (Brooklyn, Shaker, Oslo, Euro/Flat
      Panel), including Oslo Walnut "Slim Shaker" content
- [ ] Build Countertops hub + 4 material pages
- [ ] Build Custom Spaces hub + 3 service pages
- [ ] Build Portfolio archive + single project template
- [ ] Build Request Free Consultation form + thank-you page
- [ ] Build About, Contact
- [ ] Blog setup

## Phase 3 — SEO/perf/accessibility hardening (not started)

- [ ] Structured data implementation per `SEO-STRATEGY.md`
- [ ] Core Web Vitals pass
- [ ] WCAG 2.2 AA pass

## Phase 4 — Staging (not started, blocked on hosting access)

- [ ] Confirm hosting/DNS access path
- [ ] Create `staging.zeuscabinetsflorida.com`

## Phase 5 — Production launch (not started, owner-approval required)

- [ ] Pre-launch checklist (see `SECURITY-AND-DEPLOYMENT.md`)
- [ ] Owner approval
- [ ] Launch

## Backlog / deferred, not blocking

- [ ] Confirm ACF Pro license status with owner (deferred, not blocking —
      see `DECISIONS.md`)
- [ ] Selective old-site content harvest (photos, reviews, URLs) — later,
      read-only inspection only
