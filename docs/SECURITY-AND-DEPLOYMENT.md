# Security & Deployment

## Absolute production safety rules (see also `.claude/rules/production-protection.md`)

1. Never modify the live site at `zeuscabinetsflorida.com` without
   separate, explicit approval.
2. Never deploy to `zeuscabinetsflorida.com` without explicit approval.
3. Never modify a production database without explicit approval.
4. Never delete existing production files, databases, DNS records,
   backups, git history, or content.
5. Never overwrite an existing remote git branch unless explicitly
   instructed.
6. Never push directly to a production deployment automatically.
7. Never expose credentials, SSH keys, API keys, passwords, or tokens in
   git or anywhere else.
8. Prefer reversible actions; create backups before any migration/deploy
   operation.
9. If scope is ambiguous and might touch production, stop and ask first.

## Staging plan (documented now, not created yet)

Target: `staging.zeuscabinetsflorida.com`.

Requirements when created:
- Password/HTTP-auth protected.
- `X-Robots-Tag: noindex` + `noindex` meta, and `robots.txt` disallow, as
  a defense-in-depth pair.
- Never linked from, or discoverable via, any public production page.
- Same-domain production-replacement strategy: staging proves out the new
  build under a subdomain of the real domain so DNS/cert handling at
  cutover is a known, rehearsed path rather than a novel one.

Not created yet because it requires hosting/DNS access this session does
not have confirmed safe, non-interactive access to. This is a hosting
credential situation — falls under the owner-input trigger conditions,
not something to attempt speculatively.

## Pre-launch checklist (future phase — not Phase 0)

- [ ] Full production backup (files)
- [ ] Full production database backup
- [ ] Old URL inventory (crawl + analytics + Search Console export)
- [ ] 301 redirect map, old URL → new URL, verified with no redirect
      chains/loops
- [ ] Form submission testing (real end-to-end, including thank-you page)
- [ ] Analytics installed and verified firing (GA4 or successor)
- [ ] Search Console verified for the domain
- [ ] Bing Webmaster Tools / IndexNow submission where appropriate
- [ ] Structured data validated (Rich Results Test or successor tooling)
- [ ] Full SEO crawl (broken links, duplicate titles/meta, orphan pages)
- [ ] Mobile QA across real breakpoints
- [ ] Cross-browser QA
- [ ] Performance QA (Core Web Vitals field + lab data)
- [ ] Accessibility QA (WCAG 2.2 AA pass)
- [ ] Rollback procedure documented and rehearsed
- [ ] Verify the private lead-uploads directory
      (`wp-content/uploads/zeus-private-leads/`) is genuinely not
      directly downloadable on the real hosting environment (its
      `.htaccess`/`web.config` protection was only verified indirectly —
      the local dev server needed a custom router script,
      `tools/router.php`, because PHP's built-in server doesn't honor
      `.htaccess` at all; a misconfigured production host — e.g. Nginx
      without an equivalent `location` block — could have the same gap)
- [ ] If a full-page caching plugin/service is used in production,
      exclude `/consultation/` (or at least its POST handling) from the
      cache — the form's nonce and time-trap timestamp are generated
      per-request and a cached copy could serve stale ones, causing
      legitimate submissions to fail with a "session expired" error
- [ ] Decide sitemap/redirect-management approach (native vs. a plugin
      such as Yoast) once the real migration scope from
      `docs/REDIRECT-MAP-DRAFT.csv` is known — see `docs/DECISIONS.md`,
      "Performance + SEO technical audit"
- [ ] Data retention plan finalized and a real Privacy Policy page
      written — see `docs/PRIVACY-AND-DATA-RETENTION.md`
- [ ] When real hero photography is added, confirm it loads eager +
      `fetchpriority="high"` (it will be the LCP element) rather than
      the default lazy-loading behavior

Production launch requires explicit owner approval regardless of how
complete this checklist is.

## Old site handling

The old site's code/content is inspected **selectively only**, never used
as the new build's foundation. See `PROJECT-SPEC.md` §Old site and
`HANDOFF.md` for the current known local copy of the old codebase on this
machine. That copy is read-only reference material for this project — it
is not modified, and it is not where new work happens.
