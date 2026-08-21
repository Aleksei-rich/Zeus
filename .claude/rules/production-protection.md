# Rule: Production Protection

The live site at `zeuscabinetsflorida.com` and its hosting/DNS/database
are off-limits without separate, explicit, per-action owner approval.

- Never SSH/FTP/deploy to, or modify DNS/hosting settings for,
  `zeuscabinetsflorida.com`.
- Never run a database migration, import, or destructive query against
  production.
- Never delete production files, backups, DNS records, or git history.
- A prior approval for one production action does not authorize another.
  Re-confirm scope every time.
- If a task's blast radius on production is ambiguous, stop and ask
  before executing — do not guess and proceed.
- Local development and this repository are the only things that should
  change without asking, and only within `C:\Users\aleks\Projects\zeus-
  rebuild` (or a dedicated new local WordPress environment created for
  this project — see `docs/HANDOFF.md`). Never modify the pre-existing
  `Local Sites\zeus` LocalWP environment; it mirrors the old production
  codebase and is reference-only.
