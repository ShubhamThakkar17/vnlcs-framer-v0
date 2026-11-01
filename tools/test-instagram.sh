#!/usr/bin/env bash
# Tests cron/instagram-fetch.php and cron/token-refresh.php against a mock Instagram API.
set -u
cd "$(dirname "$0")/.."
T=$(mktemp -d)
export VNL_STORAGE="$T/data" VNL_SECRETS="$T/secrets.php"
mkdir -p "$VNL_STORAGE/cache"
php -S 127.0.0.1:8198 tools/mock/instagram.php >/dev/null 2>&1 & MOCK=$!
sleep 0.5
API="--api-base=http://127.0.0.1:8198"
fail=0
check() { if eval "$2"; then echo "  pass  $1"; else echo "  FAIL  $1"; fail=$((fail+1)); fi; }

cat > "$VNL_SECRETS" <<'PHP'
<?php return ['instagram_tokens' => ['vnlcs_halol' => 'good-a', 'veernewlookschoolhalol' => 'good-b'], 'alert_email' => ''];
PHP

echo "Instagram fetch"
OUT=$(php cron/instagram-fetch.php --dry-run $API)
check "dry run reports both accounts" '[[ "$OUT" == *"@vnlcs_halol: ok (12 items)"* && "$OUT" == *"@veernewlookschoolhalol: ok (12 items)"* ]]'
check "dry run writes nothing" '[ ! -f "$VNL_STORAGE/instagram.json" ]'

php cron/instagram-fetch.php $API >/dev/null
J="$VNL_STORAGE/instagram.json"
check "instagram.json written" '[ -f "$J" ]'
check "24 items merged from both accounts" '[ "$(php -r "echo count(json_decode(file_get_contents(\"$J\"),true)[\"items\"]);")" = 24 ]'
check "sorted newest first" 'php -r "\$i=json_decode(file_get_contents(\"$J\"),true)[\"items\"]; for(\$k=1;\$k<count(\$i);\$k++) if(\$i[\$k-1][\"timestamp\"]<\$i[\$k][\"timestamp\"]) exit(1);"'
check "each item tagged with its account" 'php -r "foreach(json_decode(file_get_contents(\"$J\"),true)[\"items\"] as \$x) if(!in_array(\$x[\"account\"],[\"vnlcs_halol\",\"veernewlookschoolhalol\"])) exit(1);"'
check "exactly 6 videos downloaded" '[ "$(php -r "echo count(array_filter(json_decode(file_get_contents(\"$J\"),true)[\"items\"], fn(\$x)=>!empty(\$x[\"video\"])));")" = 6 ] && [ "$(ls media/ig/mock*.mp4 | wc -l)" = 6 ]'
check "thumbnails cached locally" '[ "$(ls media/ig/mock*.jpg | wc -l)" -ge 20 ]'
check "no expiring Instagram URLs in the JSON" '! grep -q "127.0.0.1:8198" "$J"'
check "atomic write left no temp files" '[ -z "$(ls "$VNL_STORAGE"/*.tmp 2>/dev/null)" ]'

# One account's token expires: its previous items are kept
sed -i "s/'good-b'/'bad-b'/" "$VNL_SECRETS"
php cron/instagram-fetch.php $API >/dev/null 2>&1
check "failed account keeps its previous items" '[ "$(php -r "echo count(array_filter(json_decode(file_get_contents(\"$J\"),true)[\"items\"], fn(\$x)=>\$x[\"account\"]===\"veernewlookschoolhalol\"));")" = 12 ]'

# Old unused media is cleaned up
touch -d "40 days ago" media/ig/mockstale.jpg 2>/dev/null || { echo x > media/ig/mockstale.jpg; touch -d "40 days ago" media/ig/mockstale.jpg; }
php cron/instagram-fetch.php $API >/dev/null 2>&1
check "media older than 30 days and unused is deleted" '[ ! -f media/ig/mockstale.jpg ]'

echo "Token refresh"
sed -i "s/'bad-b'/'good-b'/" "$VNL_SECRETS"
OUT=$(php cron/token-refresh.php --dry-run $API)
check "unknown-age tokens are due" '[[ "$OUT" == *"WOULD refresh"* ]]'
php cron/token-refresh.php $API >/dev/null
check "refreshed tokens saved to tokens.json" 'grep -q "refreshed-good-a" "$VNL_STORAGE/tokens.json" && grep -q "refreshed-good-b" "$VNL_STORAGE/tokens.json"'
OUT=$(php cron/token-refresh.php $API)
check "fresh tokens are not refreshed again" '[[ "$OUT" == *"not due"* ]]'
php -r '$f=getenv("VNL_STORAGE")."/tokens.json"; $t=json_decode(file_get_contents($f),true); $t["vnlcs_halol"]["refreshed_at"]=time()-31*86400; $t["vnlcs_halol"]["token"]="bad-old"; file_put_contents($f,json_encode($t));'
php cron/token-refresh.php $API >/dev/null 2>&1; CODE=$?
check "31-day-old token is refreshed; failure exits 1 (alert path)" '[ $CODE = 1 ]'
check "cron scripts refuse to run outside the CLI" 'grep -q "cli_only()" cron/instagram-fetch.php && grep -q "cli_only()" cron/token-refresh.php'

kill $MOCK 2>/dev/null
rm -f media/ig/mock*
rm -rf "$T"
[ $fail = 0 ] && echo -e "\nAll Instagram checks passed." || echo -e "\n$fail check(s) failed."
exit $fail
