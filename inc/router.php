<?php
/**
 * Front controller for environments without Apache .htaccess:
 *   - local preview:  php -S localhost:8000 inc/router.php
 *   - Vercel:         api/vercel.php (vercel-php runtime) includes this file
 * On Hostinger, Apache serves the files directly and .htaccess does this job.
 */
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$query = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_QUERY);
if (strpos($uri, '//') !== false) {
    header('Location: ' . preg_replace('#/{2,}#', '/', $uri) . ($query ? '?' . $query : ''), true, 301);
    exit;
}

// Same security headers as .htaccess (Vercel previews and local preview)
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');
header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');

// 1. Old site URLs (and old PDF folders) -> 301
$redirects = require ROOT . '/inc/redirects.php';
if (isset($redirects[$uri])) {
    header('Location: ' . $redirects[$uri], true, 301);
    exit;
}
if (preg_match('#^/(2026_pdf|2021-pdf)/(.+)$#i', $uri, $m)) {
    header('Location: /docs/' . $m[2], true, 301);
    exit;
}

// 2. Private folders and files are never served.
$private = '#^/(config|cron|inc|partials|lib|sanity|tools|reference|content|reports|dist|node_modules|api/vercel\.php)(/|$)|^/data/(?!instagram(\.sample)?\.json$)|^/(vercel|package|package-lock|composer)\.json$|\.(md|lock|sh|ini|log|tmp|part|bak|sql)$|/\.#i';
if (preg_match($private, $uri)) {
    not_found();
}

$file = ROOT . $uri;
$real = realpath($file);
if ($real !== false && strpos($real, ROOT) !== 0) {
    not_found();                       // path traversal
}

// 3. Pretty URLs
if (preg_match('#^/notices/([a-z0-9][a-z0-9-]*)/?$#', $uri, $m)) {
    $_GET['slug'] = $m[1];
    run(ROOT . '/notices/view.php');
}
if ($uri === '/sitemap.xml') {
    run(ROOT . '/sitemap.php');
}

// 4. Static files
if ($real !== false && is_file($real) && substr($real, -4) !== '.php') {
    if (PHP_SAPI === 'cli-server') {
        return false;                  // let the built-in server send it
    }
    serve_static($real);
}

// 5. Directories -> index.php (add the trailing slash first)
if ($real !== false && is_dir($real)) {
    if (substr($uri, -1) !== '/') {
        header('Location: ' . $uri . '/' . ($query ? '?' . $query : ''), true, 301);
        exit;
    }
    if (is_file($real . '/index.php')) {
        run($real . '/index.php');
    }
    not_found();
}

// 6. Direct .php files (e.g. /api/notices.php)
if ($real !== false && is_file($real) && substr($real, -4) === '.php') {
    run($real);
}

not_found();

function run(string $script): void
{
    $_SERVER['SCRIPT_FILENAME'] = $script;
    $_SERVER['SCRIPT_NAME'] = substr($script, strlen(ROOT));
    $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
    chdir(dirname($script));
    require $script;
    exit;
}

function not_found(): void
{
    http_response_code(404);
    run(ROOT . '/404.php');
}

function serve_static(string $file): void
{
    $types = [
        'css' => 'text/css; charset=utf-8', 'js' => 'text/javascript; charset=utf-8', 'json' => 'application/json',
        'svg' => 'image/svg+xml', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp', 'avif' => 'image/avif', 'gif' => 'image/gif', 'ico' => 'image/x-icon',
        'woff2' => 'font/woff2', 'pdf' => 'application/pdf', 'mp4' => 'video/mp4', 'xml' => 'application/xml',
        'txt' => 'text/plain; charset=utf-8', 'html' => 'text/html; charset=utf-8', 'webmanifest' => 'application/manifest+json',
    ];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
    header('Content-Length: ' . filesize($file));
    header('Cache-Control: public, max-age=3600');
    readfile($file);
    exit;
}
