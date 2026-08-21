# Rule: Git Discipline

- Work on `rebuild/v2`. Never merge to `main`/`master` without explicit
  instruction.
- Commit logical milestones with clear, specific messages — never "misc
  changes" or "wip" dumps.
- Before every commit: run `git status` and `git diff` (staged) and read
  them; confirm nothing unexpected is staged and no secrets are present.
- Never `git push --force`, never rewrite published history, never
  overwrite a remote branch, unless explicitly instructed in that exact
  session.
- No remote is connected yet as of Phase 0. When one becomes available:
  verify it's the intended repository, `fetch` before assuming branch
  state, and never overwrite existing remote work.
- Destructive git commands (`reset --hard`, `checkout --`, `clean -f`,
  `branch -D`) require explicit instruction; run `git status` first and
  prefer stashing over discarding when in doubt.
