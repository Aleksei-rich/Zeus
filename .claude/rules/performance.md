# Rule: Performance

Target: excellent Core Web Vitals on every page.

- Responsive images (`srcset`/`sizes`), correctly sized, WebP/AVIF where
  supported.
- Lazy-load below-the-fold images/media; do not lazy-load the hero/LCP
  image.
- No layout shift: reserve space for images, fonts, and the mobile
  conversion bar before they load/render.
- Minimal blocking JS; defer/async non-critical scripts.
- Minimal third-party scripts — each one is a performance and privacy
  cost; justify any addition in `docs/DECISIONS.md`.
- Deliberate font strategy: self-host, subset, `font-display: swap` (or
  system-font-first) rather than default third-party font-loading.
- Keep the DOM lean; avoid deeply nested wrapper markup from page-builder
  habits (we're not using a page builder, so this shouldn't recur, but
  watch for it in generated block markup too).
- Justify every plugin — each one can add render-blocking assets or
  unnecessary queries.
