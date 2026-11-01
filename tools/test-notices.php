<?php
/**
 * End-to-end check of the notice pipeline without a real Sanity project.
 * Usage: php tools/test-notices.php
 * Starts a mock Sanity CDN, then checks: fetch -> cache -> page render -> stale fallback.
 */
$tmp = sys_get_temp_dir() . '/vnlcs-test-' . getmypid();
define('STORAGE', $tmp);
$port = 8199;
$mock = proc_open([PHP_BINARY, '-S', "127.0.0.1:$port", __DIR__ . '/mock/sanity.php'], [['pipe', 'r'], ['file', '/dev/null', 'w'], ['file', '/dev/null', 'w']], $pipes);
usleep(400000);

require __DIR__ . '/../inc/bootstrap.php';
$GLOBALS['CONFIG']['sanity']['project_id'] = 'test1234';
$GLOBALS['CONFIG']['sanity']['api_host'] = "http://127.0.0.1:$port";

$fail = 0;
function check(string $label, bool $ok) { global $fail; echo ($ok ? "  pass  " : "  FAIL  ") . $label . "\n"; if (!$ok) $fail++; }

echo "Notice pipeline\n";
$n = find_notice('test-notice-pipeline');
check('test notice fetched from Sanity (mock CDN)', $n !== null && $n['category_label'] === 'CBSE Circular');
check('cache file written to STORAGE/cache/sanity.json', is_file(STORAGE . '/cache/sanity.json'));
check('session year read from site settings', session_label() === '2027-28');
check('"New" tag applies (dated today)', $n && is_new($n['date']));

$_GET['slug'] = 'test-notice-pipeline';
$_SERVER['REQUEST_URI'] = '/notices/test-notice-pipeline';
ob_start();
include ROOT . '/notices/view.php';
$html = ob_get_clean();
check('notice page renders the title', strpos($html, 'TEST NOTICE: Sanity pipeline check') !== false);
check('portable text body rendered with <strong>', strpos($html, '<strong>Body text from portable text.</strong>') !== false);
check('WhatsApp share link present', strpos($html, 'https://wa.me/?text=') !== false);
check('copy-link button present', strpos($html, 'data-copy="https://veernewlookschool.com/notices/test-notice-pipeline"') !== false);
check('NewsArticle JSON-LD present', strpos($html, '"@type":"NewsArticle"') !== false);
check('PDF and official link buttons present', strpos($html, 'test.pdf?dl=') !== false && strpos($html, 'Read the official CBSE circular') !== false);

// Sanity goes down: cache older than 10 minutes must still be served.
proc_terminate($mock);
$cache = json_decode(file_get_contents(STORAGE . '/cache/sanity.json'), true);
$cache['fetched_at'] = time() - 3600;
file_put_contents(STORAGE . '/cache/sanity.json', json_encode($cache));
$fresh = sanity_fetch('*');
check('Sanity unreachable is detected', $fresh === null);
$raw = content_raw(true);
check('stale cache served when Sanity is down', ($raw['notices'][0]['slug'] ?? '') === 'test-notice-pipeline');

array_map('unlink', glob(STORAGE . '/cache/*') ?: []);
@rmdir(STORAGE . '/cache');
@rmdir(STORAGE);
echo $fail ? "\n$fail check(s) failed.\n" : "\nAll notice pipeline checks passed.\n";
exit($fail ? 1 : 0);
