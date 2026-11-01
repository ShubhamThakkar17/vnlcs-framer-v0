<?php
/**
 * /sitemap.xml (rewritten here by .htaccess / the router). Always current: notices come
 * from the same 10-minute cache as the Notice Board.
 */
if (!defined('ROOT') || !function_exists('cfg')) {
    require __DIR__ . '/inc/bootstrap.php';
}
$pages = [
    ['/', 'weekly', '1.0'],
    ['/admissions/', 'monthly', '0.9'],
    ['/notices/', 'daily', '0.9'],
    ['/about/', 'monthly', '0.8'],
    ['/academics/', 'monthly', '0.8'],
    ['/facilities/', 'monthly', '0.7'],
    ['/gallery/', 'weekly', '0.7'],
    ['/cbse-disclosure/', 'monthly', '0.6'],
];
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as [$path, $freq, $prio]) {
    echo "  <url><loc>" . e(abs_url($path)) . "</loc><changefreq>$freq</changefreq><priority>$prio</priority></url>\n";
}
if (!content_is_sample()) {   // sample notices are never advertised to search engines
    foreach (all_notices() as $n) {
        echo "  <url><loc>" . e(abs_url($n['url'])) . "</loc><lastmod>" . e($n['date']) . "</lastmod><priority>" . ($n['expired'] ? '0.3' : '0.6') . "</priority></url>\n";
    }
    foreach (albums() as $a) {
        if (!empty($a['slug'])) {
            echo "  <url><loc>" . e(abs_url('/gallery/?album=' . $a['slug'])) . "</loc><lastmod>" . e($a['date'] ?? '') . "</lastmod><priority>0.4</priority></url>\n";
        }
    }
}
echo "</urlset>\n";
