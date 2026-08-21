# Design System

## Direction

Modern, premium, architectural, clean, trustworthy, light, professional.
Explicitly avoid: generic WordPress-template look, clutter, excessive
animation, oversized sliders, heavy page builders, excessive iconography,
marketing fluff. Reference points are AllStone (commercial clarity),
Mastery Cabinetry (service/CTA clarity), and KCD (collection taxonomy) —
as strategy references only, never copied.

## Approach

Token-based design system implemented through `theme.json` (WordPress
block theme global styles) plus a small set of CSS custom properties for
anything `theme.json` doesn't reach. No CSS framework bloat (no
Bootstrap/Tailwind-as-a-dependency) — a purpose-built, small component
library scoped to this theme.

### Tokens (initial set — refine once real brand assets are supplied)

- **Color:** neutral-heavy palette (warm whites, charcoal, stone grays)
  with one confident accent color for CTAs, echoing natural materials
  (wood tones, stone). Exact hex values are a subjective brand decision —
  placeholder tokens will be used until the owner supplies or approves a
  palette; this is flagged as a decision point, not guessed permanently.
- **Type:** one serif or high-quality sans for headings (architectural
  feel), one clean sans for body/UI. System-font-adjacent choices favored
  for performance; if a webfont is used, self-hosted, subset, and loaded
  with `font-display: swap`.
- **Spacing:** 8px base scale.
- **Radius/elevation:** minimal — sharp-to-slightly-rounded corners, soft
  shadows only, consistent with "architectural/clean," not "soft SaaS."

## Components (build as reusable block patterns / template parts)

Header/nav, mobile conversion bar, hero, CTA band, collection card,
project card, finish swatch selector, countertop material card, process
step, testimonial/review card, service-area list, FAQ accordion
(accessible, native `<details>` where possible), consultation form, footer.

## Mobile conversion bar

Persistent bottom bar on mobile: **Call | Consultation**. Requirements:
- Fixed position, but must not obscure focused form fields or cover the
  final CTA on short viewports (test at common breakpoints).
- Real `<a href="tel:...">` and a real link/button to the consultation
  page — not JS-only handlers, so it still works if JS fails.
- Included in the tab order logically; does not trap focus.
- Sized for comfortable touch targets (44×44px minimum).
- No effect on CLS: reserved space, not injected after load shifting
  content.
- Respects `prefers-reduced-motion` for any show/hide transition.

## Imagery

Real project photography is the design system's real asset — not stock
photography pretending to be ZEUS's work. Until real photos are ingested
(see `HANDOFF.md` / old-site selective inspection), use clearly-neutral
placeholders, never stock photos staged to look like real ZEUS projects.

## Decision log pointer

Any concrete choice made here (exact palette, exact typefaces) gets logged
in `DECISIONS.md` with the reasoning, and flagged to the owner only if it
is a genuinely subjective brand call with materially different options —
otherwise a professional default is chosen and recorded.
