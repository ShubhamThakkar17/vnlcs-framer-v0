#!/usr/bin/env bash
# Builds the Hostinger upload: dist/public_html/ and dist/veernewlookschool-site-<date>.zip
# Only files the live site needs are included. Never includes config/secrets.php,
# live data (instagram.json, tokens.json, caches) or developer folders.
# Usage: tools/build-dist.sh
set -euo pipefail
cd "$(dirname "$0")/.."
OUT=dist/public_html
rm -rf dist && mkdir -p "$OUT"

copy() { for p in "$@"; do [ -e "$p" ] && cp -R --parents "$p" "$OUT/"; done; }

# Pages, endpoints and shared code
copy .htaccess index.php 404.php sitemap.php robots.txt favicon.ico site.webmanifest
copy about academics admissions cbse-disclosure facilities gallery notices partials inc cron
copy api/notices.php
# Assets (originals in assets/img/src stay out)
copy assets
rm -rf "$OUT/assets/img/src"
# Config: only the example and the deny rule
mkdir -p "$OUT/config"; cp config/.htaccess config/secrets.example.php "$OUT/config/"
# Data: rules + sample files; live files are created on the server
mkdir -p "$OUT/data/cache" "$OUT/media/ig" "$OUT/docs"
cp data/.htaccess data/content.sample.json data/notices.json data/instagram.sample.json "$OUT/data/"
cp data/cache/.htaccess "$OUT/data/cache/"
find docs -maxdepth 1 -type f ! -name '.gitkeep' -exec cp {} "$OUT/docs/" \; 2>/dev/null || true
touch "$OUT/media/ig/.keep" "$OUT/docs/.keep"

# Never ship these, even if they slipped in
rm -f "$OUT/config/secrets.php" "$OUT/data/instagram.json" "$OUT/data/tokens.json" "$OUT/data/fetch-log.json" "$OUT/inc/router.php"
find "$OUT" -name '.gitkeep' -delete
find "$OUT" -name '*.tmp' -delete

# Safety checks
if grep -rIl --include='*.php' -E "web3forms_keys' => \['[A-Za-z0-9-]{20,}" "$OUT" >/dev/null 2>&1; then
  echo "Refusing to build: a real Web3Forms key is committed somewhere." >&2; exit 1
fi
for bad in sanity tools reference content reports vercel.json styletile.html BRIEF.md CLAUDE.md api/vercel.php; do
  [ -e "$OUT/$bad" ] && { echo "Refusing to build: $bad is in the package." >&2; exit 1; }
done
php -l "$OUT/index.php" >/dev/null

STAMP=$(date +%Y%m%d)
ZIP="dist/veernewlookschool-site-$STAMP.zip"
( cd "$OUT" && python3 - "$PWD/../../$ZIP" <<'PY'
import os, sys, zipfile
zf = zipfile.ZipFile(sys.argv[1], 'w', zipfile.ZIP_DEFLATED)
for root, dirs, files in os.walk('.'):
    for d in dirs:
        p = os.path.join(root, d)
        zf.write(p, os.path.relpath(p, '.') + '/')
    for f in files:
        p = os.path.join(root, f)
        zf.write(p, os.path.relpath(p, '.'))
zf.close()
PY
)
echo "Built $ZIP ($(du -h "$ZIP" | cut -f1)), $(find "$OUT" -type f | wc -l) files."
echo "Upload the zip to public_html on Hostinger and extract it there (see README.md)."
