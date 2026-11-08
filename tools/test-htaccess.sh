#!/usr/bin/env bash
# Checks the live server rules: every old URL 301s to the right page (map in inc/redirects.php),
# private files answer 404, pretty URLs and headers work.
# Usage: tools/test-htaccess.sh [base-url]   e.g. http://127.0.0.1:8080 (Apache) or the staging site
BASE="${1:-http://127.0.0.1:8080}"
cd "$(dirname "$0")/.."
fail=0
ok()  { echo "  pass  $1"; }
bad() { echo "  FAIL  $1"; fail=$((fail+1)); }

echo "Old URLs (301)"
while IFS='|' read -r from to; do
  got=$(curl -s -o /dev/null -w "%{http_code} %{redirect_url}" "$BASE$from")
  code=${got%% *}; loc=${got#* }; loc=${loc#$BASE}
  [[ "$code" = 301 && "$loc" = "$to" ]] && ok "$from -> $to" || bad "$from: got $got, want 301 $to"
done < <(php -r '$m = require "inc/redirects.php"; foreach ($m as $f => $t) echo "$f|$t\n";')
got=$(curl -s -o /dev/null -w "%{http_code} %{redirect_url}" "$BASE/2021-pdf/Fee%20Structure.pdf")
[[ "$got" == "301 $BASE/docs/Fee%20Structure.pdf" ]] && ok "/2021-pdf/* -> /docs/*" || bad "old PDF folder: $got"
got=$(curl -s -o /dev/null -w "%{http_code} %{redirect_url}" "$BASE/2026_pdf/affiliation.pdf")
[[ "$got" == "301 $BASE/docs/affiliation.pdf" ]] && ok "/2026_pdf/* -> /docs/*" || bad "old PDF folder: $got"

echo "Private paths (404)"
for p in /config/secrets.example.php /cron/instagram-fetch.php /inc/config.php /partials/head.php /data/content.sample.json \
         /data/cache/ /data/tokens.json /sanity/package.json /tools/contrast.js /CLAUDE.md /README.md /.gitignore /.htaccess \
         /vercel.json /api/vercel.php /reference/NOTES.md /.git/config; do
  code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE$p")
  [[ "$code" = 404 ]] && ok "$p" || bad "$p answered $code"
done

echo "Public paths (200)"
for p in / /about/ /academics/ /facilities/ /gallery/ /notices/ /admissions/ /cbse-disclosure/ /notices/cbse-date-sheet-2027-class-10-12 \
         /api/notices.php?limit=3 /sitemap.xml /robots.txt /data/instagram.sample.json /assets/css/base.css /favicon.ico; do
  code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE$p")
  [[ "$code" = 200 ]] && ok "$p" || bad "$p answered $code"
done
code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/no-such-page")
[[ "$code" = 404 ]] && ok "unknown page -> 404" || bad "unknown page answered $code"
code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE/notices/no-such-notice")
[[ "$code" = 404 ]] && ok "unknown notice -> 404" || bad "unknown notice answered $code"

echo "Headers"
H=$(curl -s -D - -o /dev/null -H "Accept-Encoding: gzip" "$BASE/")
grep -qi "^x-content-type-options: nosniff" <<<"$H" && ok "X-Content-Type-Options" || bad "X-Content-Type-Options missing"
grep -qi "^content-security-policy:" <<<"$H" && ok "Content-Security-Policy" || bad "CSP missing"
grep -qi "^x-frame-options: SAMEORIGIN" <<<"$H" && ok "X-Frame-Options" || bad "X-Frame-Options missing"
grep -qi "^content-encoding: gzip" <<<"$H" && ok "HTML is compressed" || bad "HTML not compressed"
H=$(curl -s -D - -o /dev/null "$BASE/assets/css/base.css?v=1")
grep -qi "cache-control: public, max-age=31536000, immutable" <<<"$H" && ok "long cache on /assets CSS" || bad "asset cache header missing"
H=$(curl -s -D - -o /dev/null -H "Host: www.veernewlookschool.com" "$BASE/about/")
grep -qi "^location: https://veernewlookschool.com/about/" <<<"$H" && ok "www -> bare https domain" || bad "www redirect"
H=$(curl -s -D - -o /dev/null -H "Host: veernewlookschool.com" "$BASE/about/")
grep -qi "^location: https://veernewlookschool.com/about/" <<<"$H" && ok "http -> https" || bad "https redirect"

[ $fail = 0 ] && echo -e "\nAll server rule checks passed." || echo -e "\n$fail check(s) failed."
exit $fail
