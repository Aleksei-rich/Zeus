# Rule: Secrets

- Never commit credentials, API keys, tokens, SSH keys, database
  passwords, or license keys to this repository.
- `wp-config.php` is never committed (see `.gitignore`) — real
  credentials live only in the local environment's actual config file,
  which stays untracked.
- Before staging or committing, check `git status`/`git diff` for
  anything that looks like a secret even in an innocuous-looking file
  (config, `.json`, `.env`-like files) and stop to double-check before
  proceeding if anything looks off.
- If a secret is ever accidentally staged, unstage and remove it before
  committing — do not commit-then-fix, since that leaves it in history.
- The owner's email may be used for git commit attribution in this
  repository; it is not sent to any external service.
