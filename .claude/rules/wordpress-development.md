# Rule: WordPress Development

- Custom block theme, no page builder, no unnecessary jQuery in new code.
- Escape all output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses` where
  rich content is involved) and sanitize all input
  (`sanitize_text_field`/appropriate sanitizer per field type).
- Nonce-check and capability-check every form handler and admin action.
- Use `$wpdb->prepare()` for any raw SQL; prefer core APIs
  (`WP_Query`, `get_posts`, meta/taxonomy APIs) over raw SQL entirely
  where practical.
- Register ACF fields in PHP (`acf_add_local_field_group`), not via the
  ACF UI/JSON export — see `docs/TECHNICAL-ARCHITECTURE.md`.
- No new CPT/taxonomy without checking `docs/CONTENT-MODEL.md` first and
  logging the rationale in `docs/DECISIONS.md` if it changes the model.
- No hard-coded production URLs or machine-specific absolute paths in
  theme/plugin code — use WordPress's own URL/path functions
  (`home_url()`, `get_template_directory_uri()`, etc.).
- Justify every plugin added in `docs/DECISIONS.md` before adding it;
  default to native WordPress functionality first.
- Never present a 3D rendering/design concept as a completed project in
  any template — the `project_status` field (`completed`/`concept`) must
  gate the display copy/labeling every time a project renders.
