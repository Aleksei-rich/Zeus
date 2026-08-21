# Rule: Testing

- Before marking any template/feature "done": verify it actually renders
  (local browser check, not just lint), check mobile/responsive
  behavior, check keyboard navigation, and check for console errors.
- Form submissions (especially Request Free Consultation) must be tested
  end-to-end locally, including arrival at the thank-you page, before
  being called done.
- Run available local syntax/lint checks (PHP lint at minimum) before
  committing.
- If a UI check genuinely couldn't be performed (e.g., no local
  environment reachable), say so explicitly instead of claiming it was
  verified.
