<?php
// Copy to config/secrets.php on the server and fill in. secrets.php is gitignored.
return [
    'web3forms_access_key' => '',
    'instagram' => [
        // One entry per Professional account. Tokens last 60 days; cron/token-refresh.php renews them.
        'vnlcs_halol'            => ['token' => '', 'refreshed_at' => ''],
        'veernewlookschoolhalol' => ['token' => '', 'refreshed_at' => ''],
    ],
    'alert_email' => '', // KeshavCo address for token-refresh failures
];
