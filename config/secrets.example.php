<?php
// Copy to config/secrets.php on the server and fill in. secrets.php is gitignored and blocked from the web.
return [
    // Web3Forms access keys (https://web3forms.com). Create one key per inbox so an enquiry
    // reaches both school addresses on the free plan:
    //   key 1 created with veer.newlook@gmail.com, key 2 with veernewlookoffice@gmail.com
    'web3forms_keys' => ['', ''],

    // Instagram API with Instagram Login: long-lived tokens for each Professional account.
    // cron/token-refresh.php renews them and stores the fresh token in data/tokens.json.
    'instagram_tokens' => [
        'vnlcs_halol'            => '',
        'veernewlookschoolhalol' => '',
    ],

    // Where token-refresh failures are emailed (KeshavCo).
    'alert_email' => '',
];
