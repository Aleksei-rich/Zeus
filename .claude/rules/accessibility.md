# Rule: Accessibility

Target: WCAG 2.2 AA on every template.

- Semantic HTML structure; landmark regions; correct heading order.
- Full keyboard operability, including the mobile conversion bar and any
  modal/accordion component.
- Visible focus states — never `outline: none` without a replacement
  focus style.
- Sufficient color contrast for text and interactive elements.
- Real `<label>`s (or equivalent accessible name) on every form field;
  error messages programmatically associated with their field.
- Descriptive, factual alt text on images — not decorative-keyword-
  stuffed, and empty `alt=""` for genuinely decorative images.
- Touch targets sized for comfortable use (44×44px preferred, 24×24px
  floor).
- Respect `prefers-reduced-motion` for any animation/transition.
- Any accordion/modal (e.g., FAQ) must be operable by keyboard and
  correctly exposed to assistive tech (`aria-expanded`, focus
  management on open/close).
