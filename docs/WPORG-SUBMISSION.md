# WordPress.org submission checklist

For the initial manual upload, sign in at <https://wordpress.org/plugins/developers/add/> and submit the ZIP below for **RGPD Cookies y Páginas Legales** (expected slug `rgpd-cookie-legal`). Disclose its status as a distinctly named GPLv2-or-later fork of the abandoned `click-datos-lopd`, with no original branding; see `readme.txt` and `RELEASING.md`.

## Before submitting

- [ ] Confirm `readme.txt` follows WordPress.org readme format and describes the fork and support channel accurately.
- [ ] Keep tags to five or fewer. Current readme has five.
- [ ] Match `Stable tag` in `readme.txt` to the `Version` header in `rgpd-cookie-legal.php` (currently `1.0.0`).
- [ ] Check public-facing plugin pages for external links and remove them where not allowed. The current admin help content includes links to external legal resources; confirm these meet directory policy before submission.
- [ ] Confirm no code, scripts, styles, fonts, or other assets are loaded from a CDN. Runtime Bootstrap assets are local; jQuery is enqueued as a WordPress core dependency.
- [ ] Confirm every bundled file's license permits GPL distribution. Bootstrap 3 is MIT-licensed and GPL-compatible; retain its license notice. The project is GPLv2-or-later.
- [ ] Run CI and inspect the built archive as described in `RELEASING.md`.

## Upload this file

`dist/rgpd-cookie-legal-1.0.0.zip` — verify it was built from the current finished release and contains the top-level `rgpd-cookie-legal/` directory. Do not upload the repository source archive or GitHub ZIP.

After approval, set up the WordPress.org SVN repository and release process using [RELEASING.md](RELEASING.md).
