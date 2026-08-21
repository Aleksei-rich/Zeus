# Project Specification

Source of truth for business requirements. Derived from the owner's
original project brief (2026-08-21). If requirements evolve, update this
file and log the change in `DECISIONS.md`.

## Business

- **Company:** ZEUS Cabinets & Countertops
- **Primary market:** Orlando and Central Florida
- **Key service areas:** Orlando, Windermere, Winter Garden, Horizon West,
  Clermont, Dr. Phillips
- **Business model:** Service-area business. No public showroom — never
  present the site as if customers can walk in.

## Conversion goals

1. **Primary:** Request Free Consultation
2. **Secondary:** Phone call
3. **Secondary:** Contact form

## What a first-time visitor must understand in ~10–15 seconds (homepage)

1. What ZEUS does
2. Where ZEUS works
3. Why ZEUS is credible
4. What to do next

## Mobile

Mobile-first design. Plan a persistent mobile conversion bar
(Call | Consultation) where it helps conversion without harming
accessibility or Core Web Vitals — see `DESIGN-SYSTEM.md` for the
implementation approach.

## Request Free Consultation form

Fields: Name, Phone, Email, ZIP, Project Type, Project Description,
optional photo/plan upload. Submits to a dedicated thank-you page suitable
for analytics conversion tracking (not a modal/inline swap only — a real
URL that a conversion pixel can fire on).

## Design direction

Modern, premium, clean cabinetry/interiors design. Avoid: generic
cheap-template look, visual clutter, excessive animation, oversized
sliders, heavy page builders, excessive iconography, marketing fluff.

Desired impression: premium, modern, architectural, clean, trustworthy,
light, high quality, professional.

Reference strategy (approach only — never copy visual design, copywriting,
images, or code):
- Commercial clarity similar in spirit to AllStone
- Service/CTA clarity similar in spirit to Mastery Cabinetry
- Useful cabinet collection taxonomy similar in spirit to KCD

## Old site

Do not rebuild from the old code or import it as the foundation. The old
site may be inspected **selectively** later, only for: real project
photos, useful original content, reviews, existing URLs (for the 301 map),
analytics/history, and media worth preserving. Known technical debt in the
old build must not be carried forward. See `HANDOFF.md` for where a local
copy of the old site currently lives on this machine.

## Out of scope for now

- Production deployment (separate future phase, owner-approved).
- Staging environment creation (planned, not created — see
  `SECURITY-AND-DEPLOYMENT.md`).
- Any purchase (hosting, plugins, licenses) — flag to owner, don't buy.
