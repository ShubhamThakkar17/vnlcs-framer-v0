<?php
/**
 * Daily: refresh Instagram long-lived tokens older than 30 days (they expire after 60).
 *
 *   php cron/token-refresh.php             normal run (Hostinger cron, daily)
 *   php cron/token-refresh.php --dry-run   show what would be refreshed
 *   php cron/token-refresh.php --force     refresh every token now
 *
 * New tokens are saved to data/tokens.json. Any failure is emailed to 'alert_email' (KeshavCo).
 */
declare(strict_types=1);
require __DIR__ . '/../inc/bootstrap.php';
require ROOT . '/inc/instagram-api.php';
cli_only();

const REFRESH_AFTER_DAYS = 30;

$dry = (bool) cli_option('dry-run');
$force = (bool) cli_option('force');
$failures = [];
$tokens = ig_tokens();
if (!$tokens) {
    $failures[] = "No Instagram tokens configured in config/secrets.php.";
}

foreach ($tokens as $handle => $t) {
    $age = $t['refreshed_at'] ? (time() - $t['refreshed_at']) / 86400 : null;
    $due = $force || $age === null || $age >= REFRESH_AFTER_DAYS;
    $ageText = $age === null ? 'age unknown' : sprintf('%.1f days old', $age);
    if (!$due) {
        echo "@$handle: $ageText, not due.\n";
        continue;
    }
    if ($dry) {
        echo "@$handle: $ageText, WOULD refresh.\n";
        continue;
    }
    [$json, $err] = ig_get('/refresh_access_token', ['grant_type' => 'ig_refresh_token', 'access_token' => $t['token']]);
    if ($json === null || empty($json['access_token'])) {
        $failures[] = "@$handle: " . ($err ?: 'no token in response');
        echo "@$handle: FAILED ($err)\n";
        continue;
    }
    ig_save_token($handle, (string) $json['access_token'], (int) ($json['expires_in'] ?? 5184000));
    echo "@$handle: refreshed, valid for " . round(((int) ($json['expires_in'] ?? 5184000)) / 86400) . " days.\n";
}

if ($failures && !$dry) {
    $to = (string) secret('alert_email', '');
    $body = "The daily Instagram token refresh for veernewlookschool.com failed:\n\n- " . implode("\n- ", $failures)
        . "\n\nIf a token has expired, generate a new long-lived token in the Meta developer dashboard and put it in "
        . "config/secrets.php, then delete that account's entry in data/tokens.json.\n\nTime: " . date('c') . "\n";
    if ($to !== '') {
        $sent = @mail($to, '[veernewlookschool.com] Instagram token refresh failed', $body, "From: website@veernewlookschool.com\r\nContent-Type: text/plain; charset=utf-8");
        echo $sent ? "Alert emailed to $to.\n" : "Could not send the alert email.\n";
    } else {
        echo "No alert_email configured.\n";
    }
    exit(1);
}
exit(0);
