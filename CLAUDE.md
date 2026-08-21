# ZEUS Cabinets & Countertops — Website Rebuild

Durable project instructions for every Claude Code session working in this
repository. Read this file first. Read the relevant `docs/` file before
making any architectural or design decision. Do not repeat questions the
owner has already answered — the answers live in `docs/DECISIONS.md`.

## 1. Project purpose

A complete, from-scratch rebuild of the ZEUS Cabinets & Countertops website
as a new, professional WordPress site. This is **not** an incremental
redesign — the old codebase is not the foundation. The live production site
at `zeuscabinetsflorida.com` is a separate, untouched system until an
explicit production-launch approval is given.

Business: ZEUS Cabinets & Countertops, a service-area cabinetry and
countertop company serving Orlando and Central Florida (Orlando,
Windermere, Winter Garden, Horizon West, Clermont, Dr. Phillips). No public
showroom — do not present the business as walk-in retail.

Primary conversion goal: **Request Free Consultation**. Secondary: phone
call, contact form.

Full requirements are the mega-brief the owner supplied at project start;
it has been decomposed into the `docs/` files below. If this file and a
`docs/` file ever conflict, `docs/DECISIONS.md` (most recent entry) wins,
because it records what was actually decided as the project evolved.

## 2. Current phase

**Phase 0 — Project control system + local environment readiness.**
See `docs/TASKS.md` for the live task list and current status. Update it
continuously; do not let it drift from reality.

## 3. Technical stack (defaults — see `docs/TECHNICAL-ARCHITECTURE.md`)

- WordPress, latest stable core, unmodified.
- PHP 8.2+ (matches the local environment's bundled PHP).
- Custom-built block theme (`theme.json` + block templates), not a
  multipurpose/page-builder theme. No Elementor. No unnecessary jQuery.
- ACF for structured content (Cabinet Collections, Portfolio Projects).
  Default to ACF free field-registration-in-code; upgrade to ACF Pro only
  once the owner confirms an active license (do not assume — see
  `docs/DECISIONS.md`).
- Yoast SEO (or equivalent) for meta/sitemap/schema plumbing, layered with
  hand-built structured data where Yoast doesn't cover it.
- Minimal JS, no framework dependency for the marketing site. Vanilla JS /
  small web components only where interactivity is genuinely needed.
- MariaDB/MySQL, standard WordPress database layer.

## 4. Architecture

See `docs/SITE-ARCHITECTURE.md` (IA, navigation, page inventory) and
`docs/CONTENT-MODEL.md` (CPTs, taxonomies, fields). Do not introduce a new
custom post type without checking `docs/CONTENT-MODEL.md` first and
recording the rationale in `docs/DECISIONS.md` — the project brief is
explicit that CPTs must be justified, not created by default.

## 5. Business requirements

Full detail in `docs/PROJECT-SPEC.md`. Non-negotiables to keep in view on
every page built:
- A first-time visitor must understand within ~10–15 seconds what ZEUS
  does, where it works, why it's credible, and what to do next.
- Never present a 3D rendering / design concept as a completed project.
  Every portfolio entry must be explicitly labeled completed vs. concept.
- Never imply a walk-in showroom exists.
- Every major page carries a clear path to "Request Free Consultation."

## 6. Coding standards

- Semantic HTML, WordPress coding standards (PHPCS/WPCS mindset even
  without the tool installed yet), meaningful names, no dead code.
- Escape and sanitize all output/input (`esc_html`, `esc_attr`, `esc_url`,
  `sanitize_text_field`, nonces on all form handlers, capability checks on
  all admin actions, `$wpdb->prepare` for any raw query).
- No secrets, API keys, or credentials committed. No hard-coded production
  URLs. No machine-specific absolute paths in theme/plugin code.
- CSS: token/component-based (see `docs/DESIGN-SYSTEM.md`), avoid
  specificity wars, avoid `!important` except documented edge cases.
- Small, reusable template parts / block patterns over duplicated markup.
- Comments only where the *why* is non-obvious (a workaround, a subtle
  constraint) — not restating what the code does.

## 7. SEO requirements

Architectural, not an afterthought — see `docs/SEO-STRATEGY.md`. Every new
template/page must ship with: one clear page intent, correct H1–H3
hierarchy, title/meta plan, internal links to relevant siblings, breadcrumbs,
canonical URL, and appropriate structured data (only where factually true —
no fabricated review/rating markup). Target commercial queries listed in
`docs/SEO-STRATEGY.md` without keyword-stuffing.

## 8. Accessibility requirements

WCAG 2.2 AA as the practical bar for every template: semantic structure,
full keyboard operability, visible focus states, sufficient contrast, real
form labels, meaningful alt text, accessible nav and any modal, touch
targets ≥24×24px (44×44 preferred), and `prefers-reduced-motion` respected
for any animation.

## 9. Performance requirements

Excellent Core Web Vitals is a hard target, not a nice-to-have: responsive
images with WebP/AVIF, correct sizing, lazy-loading below the fold,
deliberate hero-image loading (no layout shift), minimal blocking JS,
minimal third-party scripts, a considered font-loading strategy, and a
lean DOM. Justify every plugin added — each one is a performance and
security liability.

## 10. Testing rules

- Before calling any template/feature done: verify it renders correctly,
  check responsive behavior (mobile-first), check keyboard navigation,
  and check for console errors.
- Form submissions must be tested end-to-end locally (including the
  thank-you page / conversion tracking hook) before being marked done.
- Run whatever local syntax/lint checks are available (PHP lint at
  minimum) before committing.
- UI/frontend claims of "done" require an actual local browser check, not
  just passing lint — say explicitly if that check wasn't possible.

## 11. Git workflow

- Work happens on `rebuild/v2`. Never merge to `main`/`master` without
  explicit owner instruction.
- Commit logical milestones with clear messages; no "misc changes" dumps.
- Before every commit: review `git diff`, confirm no secrets, confirm no
  unexpected files staged.
- Never force-push, never rewrite published history, never overwrite a
  remote branch, unless explicitly instructed.
- No remote is connected yet. When one becomes available, verify the
  intended repo and fetch before assuming branch state — never overwrite
  existing remote work.

## 12. Staging workflow

Staging (`staging.zeuscabinetsflorida.com`) is planned in
`docs/SECURITY-AND-DEPLOYMENT.md` but not created until hosting/credential
access is available without risking the live site. Staging must be
password-protected and `noindex`, and must never be discoverable as a
second public company site.

## 13. Production safety rules

See `docs/SECURITY-AND-DEPLOYMENT.md` for the full pre-launch checklist.
In every session, regardless of task:
1. Never modify, deploy to, or touch credentials for
   `zeuscabinetsflorida.com` without separate explicit approval.
2. Never modify a production database.
3. Never delete existing production files, DB, DNS, backups, git history,
   or content.
4. Never commit secrets.
5. Treat any ambiguous "does this touch production?" situation as a stop
   condition — ask, don't guess.

## 14. Definition of done

A task is done when: it's implemented, it passes the applicable checks in
§10, it's been self-reviewed (diff read, defects fixed), `docs/TASKS.md`
reflects the new state, `docs/DECISIONS.md` has an entry if a decision was
made, and a logical commit exists. "Done" does not require asking the
owner to confirm — confirm by testing, not by asking.

## 15. Minimize owner involvement

Default to investigating, deciding, implementing, testing, and documenting
autonomously. Only stop for the owner when one of these is genuinely true:
credentials/login/2FA, purchase/payment, an irreversible or destructive
action, a production write/deploy/DB change, a subjective visual/business
call with materially different options, or information that truly cannot
be inferred or safely deferred. For everything else, pick the safest
professional default and record it in `docs/DECISIONS.md` — don't ask.
When owner input is genuinely required, ask for exactly one thing, in the
short format defined in the original brief (Reason / Exact action / What
happens afterward).

## 16. Keep TASKS.md and DECISIONS.md current

Update `docs/TASKS.md` as work progresses — not just at the end of a
session. Add an entry to `docs/DECISIONS.md` any time an architectural,
technical, or design decision gets made, including the reasoning, so a
future session (or the owner) never has to guess why something is the way
it is.

## 17. Never silently change an approved decision

If a previous decision in `docs/DECISIONS.md` needs to change, say so
explicitly, explain why, and record the change as a new dated entry — do
not quietly drift away from something already decided.

## 18. Local development environment

See `docs/HANDOFF.md` for the current state of local WordPress tooling,
including an important note: `Local Sites\zeus` (LocalWP) already exists
on this machine but is a mirror of the **old** production codebase, not
this project. Do not build on it, do not treat it as this project's dev
environment.
