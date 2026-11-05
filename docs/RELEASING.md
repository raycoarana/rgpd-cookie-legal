# Releasing

## One-time WordPress.org setup

1. Sign in to WordPress.org and submit the plugin at <https://wordpress.org/plugins/developers/add/>. The form requires the installable ZIP; WordPress.org derives the plugin slug from the submitted name. Use **RGPD Cookies y Páginas Legales** (expected slug: `rgpd-cookie-legal`).
2. Disclose that this is a GPLv2-or-later maintenance fork of the abandoned `click-datos-lopd` plugin, with a distinct name and no original brand or logo. The original was closed on 2024-03-07; this fork fixes CVE-2023-46071. See the attribution in `readme.txt` and `rgpd-cookie-legal.php` (Guideline 17).
3. WordPress.org reviews the submission manually. After approval, it creates the SVN repository and grants access to the WordPress.org account.

## Prepare a version

The version is declared in **three** files and they must all agree:

1. `Version:` header in `rgpd-cookie-legal.php`
2. `Stable tag:` in `readme.txt`
3. `Stable tag:` in `readme-es.txt`

Steps:

1. Update all three to the same `X.Y.Z`.
2. Add that version and its changes at the top of the `== Changelog ==` section in `readme.txt` (and `readme-es.txt`).
3. Commit, then tag `vX.Y.Z` and push. `release.yml` refuses to build if the tag does not match all three files (see "GitHub Release").

The current version is `1.0.0` in all three files.

## Build the installable ZIP

The distribution filter and archive layout match `.github/workflows/build.yml`. From the repository root:

```sh
mkdir -p build/rgpd-cookie-legal dist
rsync -a --exclude-from=.distignore --exclude='build/' --exclude='.git/' ./ build/rgpd-cookie-legal/
(cd build && zip -qr ../dist/rgpd-cookie-legal-1.0.0.zip rgpd-cookie-legal)
unzip -l dist/rgpd-cookie-legal-1.0.0.zip
```

Change the ZIP filename when releasing another version. The archive contains one top-level `rgpd-cookie-legal/` directory. The build workflow runs on `main` pushes or manually and uploads `rgpd-cookie-legal.zip` as the `rgpd-cookie-legal` artifact; download and rename that artifact if you need the versioned filename.

## GitHub Release

Tag a finished release `vX.Y.Z` and push it. `.github/workflows/release.yml` first **validates that the tag matches the version in `rgpd-cookie-legal.php`, `readme.txt` and `readme-es.txt`** and aborts the release on any mismatch, then builds the ZIP (using the same `.distignore` recipe as `build.yml`) and attaches it to the GitHub Release as `rgpd-cookie-legal.zip`.

## WordPress.org SVN (after approval)

WordPress.org SVN is the published release repository: commit only finished releases, not development snapshots.

```sh
svn co https://plugins.svn.wordpress.org/rgpd-cookie-legal rgpd-cookie-legal-svn
cd rgpd-cookie-legal-svn
# Extract the verified install ZIP, then sync only its plugin directory to trunk.
unzip -q /path/to/dist/rgpd-cookie-legal-1.0.0.zip -d /tmp/rgpd-release
rsync -a --delete --exclude='.svn/' /tmp/rgpd-release/rgpd-cookie-legal/ trunk/
svn add --force trunk
svn cp trunk tags/1.0.0
svn commit -m "Release 1.0.0"
```

Before committing, ensure `trunk/` contains the exact contents of the verified ZIP's `rgpd-cookie-legal/` folder. For later versions, update trunk and create the matching version tag. `.github/workflows/deploy.yml` automates this with `10up/action-wordpress-plugin-deploy`, but it is **disabled by default**: it only runs when the repository variable `WPORG_SVN_READY` is set to `true`. Once approved, add repository secrets `SVN_USERNAME` and `SVN_PASSWORD`, then set `WPORG_SVN_READY=true` to enable it. Until then, deploy using SVN manually.

## WordPress.org listing assets

`.wordpress-org/` is the source directory for listing assets, not part of the plugin ZIP. Copy its listing files to the SVN repository's `/assets/` directory. Expected PNG names and dimensions are:

- `icon-128x128.png`, `icon-256x256.png`
- `banner-772x250.png`, `banner-1544x500.png`
- `screenshot-1.png`, `screenshot-2.png`, etc. (align numbering and captions with the readme Screenshots section)

The current `.wordpress-org/README.md` documents these names, but the image files are not present yet.

## Review checklist

- [ ] **Unescaped output:** review dynamic output for context-appropriate escaping; the fork addresses known output issues. CI's `PHPCS` job runs WordPress-Core/Docs standards, but is not a security audit.
- [ ] **Unsanitized input:** validate and sanitize request data at trust boundaries. CI's `PHPCS` job checks WordPress standards; PHP lint alone does not detect unsafe input.
- [ ] **Missing nonces:** verify nonces and capability checks on state-changing admin actions. The fork adds these checks; PHPCS is a static check, not proof that every action is protected.
- [ ] **Guideline breaches:** manually check current WordPress.org Plugin Guidelines, licensing/attribution, readme format, assets, and public behavior. No CI job enforces guideline compliance.

CI (`.github/workflows/ci.yml`) runs PHP syntax checks on PHP 7.4–8.3 and PHPCS on PHP 8.2. Treat passing CI as a baseline, not a replacement for this review.
