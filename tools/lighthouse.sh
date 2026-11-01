#!/usr/bin/env bash
# Lighthouse (mobile) for the key pages. Reports go to reports/lighthouse/ (gitignored).
# Usage: tools/lighthouse.sh [base-url]   (needs `npx lighthouse` and a Chrome binary in CHROME_PATH)
set -euo pipefail
BASE="${1:-http://127.0.0.1:8000}"
LH="${LIGHTHOUSE_BIN:-npx --yes lighthouse}"
export CHROME_PATH="${CHROME_PATH:-/opt/pw-browsers/chromium}"
mkdir -p reports/lighthouse
for p in / /notices/ /gallery/; do
  name=$(echo "$p" | sed 's#^/$#home#; s#/##g')
  $LH "$BASE$p" --quiet --form-factor=mobile --chrome-flags="--headless=new --no-sandbox" \
    --output=json --output=html --output-path="reports/lighthouse/$name" >/dev/null 2>&1
  node -e "
    const r = require('./reports/lighthouse/$name.report.json');
    const s = Object.values(r.categories).map(c => c.title.split(' ')[0] + ' ' + Math.round(c.score * 100)).join('  ');
    console.log('$p'.padEnd(12), s, ' LCP', r.audits['largest-contentful-paint'].displayValue, ' CLS', r.audits['cumulative-layout-shift'].displayValue, ' size', r.audits['total-byte-weight'].displayValue);
  "
done
