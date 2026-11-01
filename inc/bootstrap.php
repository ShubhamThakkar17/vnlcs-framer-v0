<?php
/**
 * Loaded first by every page, endpoint and cron script.
 */
declare(strict_types=1);

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

$GLOBALS['CONFIG'] = require ROOT . '/inc/config.php';
$secretsFile = ROOT . '/config/secrets.php';
$GLOBALS['SECRETS'] = is_file($secretsFile) ? (require $secretsFile) : [];

/* Writable storage. Hostinger: /data. Vercel (read-only filesystem): the temp dir. */
if (!defined('STORAGE')) {
    $storage = getenv('VERCEL') ? sys_get_temp_dir() . '/vnlcs-data' : ROOT . '/data';
    define('STORAGE', $storage);
}
if (!is_dir(STORAGE . '/cache')) {
    @mkdir(STORAGE . '/cache', 0775, true);
}

date_default_timezone_set('Asia/Kolkata');

require_once ROOT . '/inc/helpers.php';
require_once ROOT . '/inc/content.php';
require_once ROOT . '/inc/instagram.php';
