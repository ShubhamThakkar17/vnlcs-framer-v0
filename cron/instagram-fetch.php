<?php
/**
 * Hourly: fetch the latest posts from both Instagram accounts and cache them locally.
 *
 *   php cron/instagram-fetch.php              normal run (Hostinger cron, hourly)
 *   php cron/instagram-fetch.php --dry-run    fetch and print a summary, write nothing
 *
 * - Calls graph.instagram.com/me/media for each account (fields per the brief, limit 12)
 * - Merges, sorts newest first and tags each item with its account handle
 * - Downloads thumbnails for every item and videos for the latest 6 reels to /media/ig/
 *   (Instagram media URLs expire, so the site never hot-links them)
 * - Writes /data/instagram.json atomically; keeps an account's previous items if its fetch fails
 * - Deletes cached media older than 30 days that is no longer used
 */
declare(strict_types=1);
require __DIR__ . '/../inc/bootstrap.php';
require ROOT . '/inc/instagram-api.php';
cli_only();

const IG_FIELDS = 'id,caption,media_type,media_product_type,media_url,thumbnail_url,permalink,timestamp';
const IG_LIMIT = 12;
const IG_VIDEOS = 6;
const IG_MAX_VIDEO = 60 * 1024 * 1024;
const IG_MAX_IMAGE = 8 * 1024 * 1024;
const IG_KEEP_DAYS = 30;

$dry = (bool) cli_option('dry-run');
$mediaDir = ROOT . '/media/ig';
$jsonFile = STORAGE . '/instagram.json';
$previous = is_file($jsonFile) ? (json_decode((string) file_get_contents($jsonFile), true) ?: []) : [];
$tokens = ig_tokens();

if (!$tokens) {
    fwrite(STDERR, "No Instagram tokens configured in config/secrets.php ('instagram_tokens').\n");
    exit(1);
}

$items = [];
$log = ['run_at' => date('c'), 'accounts' => []];
foreach ($tokens as $handle => $t) {
    [$json, $err] = ig_get('/me/media', ['fields' => IG_FIELDS, 'limit' => IG_LIMIT, 'access_token' => $t['token']]);
    if ($json === null) {
        $log['accounts'][$handle] = 'FAILED: ' . $err;
        fwrite(STDERR, "@$handle: $err (keeping previous items)\n");
        foreach ($previous['items'] ?? [] as $old) {
            if (($old['account'] ?? '') === $handle) {
                $items[] = $old;
            }
        }
        continue;
    }
    $count = 0;
    foreach ($json['data'] ?? [] as $m) {
        $items[] = [
            'id' => (string) $m['id'],
            'account' => $handle,
            'media_type' => (string) ($m['media_type'] ?? ''),
            'product_type' => (string) ($m['media_product_type'] ?? ''),
            'caption' => (string) ($m['caption'] ?? ''),
            'permalink' => (string) ($m['permalink'] ?? ''),
            'timestamp' => (string) ($m['timestamp'] ?? ''),
            '_media_url' => $m['media_url'] ?? null,
            '_thumb_url' => $m['thumbnail_url'] ?? (($m['media_type'] ?? '') === 'VIDEO' ? null : ($m['media_url'] ?? null)),
        ];
        $count++;
    }
    $log['accounts'][$handle] = "ok ($count items)";
}

usort($items, fn($a, $b) => strcmp($b['timestamp'], $a['timestamp']));

if (!$dry && !is_dir($mediaDir)) {
    mkdir($mediaDir, 0775, true);
}
$videosLeft = IG_VIDEOS;
foreach ($items as &$it) {
    $isReel = $it['media_type'] === 'VIDEO' || $it['product_type'] === 'REELS';
    $safeId = preg_replace('/[^0-9A-Za-z_]/', '', $it['id']);
    if (isset($it['_thumb_url'])) {
        $thumb = "$mediaDir/$safeId.jpg";
        $it['thumb'] = $dry || ($it['_thumb_url'] && ig_download($it['_thumb_url'], $thumb, IG_MAX_IMAGE)) ? "/media/ig/$safeId.jpg" : null;
    }
    if (array_key_exists('_media_url', $it)) {
        $it['video'] = null;
        if ($isReel && $videosLeft > 0 && $it['_media_url']) {
            $video = "$mediaDir/$safeId.mp4";
            if ($dry || ig_download($it['_media_url'], $video, IG_MAX_VIDEO)) {
                $it['video'] = "/media/ig/$safeId.mp4";
                $videosLeft--;
            }
        }
    }
    unset($it['_media_url'], $it['_thumb_url']);
}
unset($it);

$out = ['generated_at' => date('c'), 'sample' => false, 'items' => $items];

if ($dry) {
    echo "DRY RUN: nothing written.\n";
    foreach ($log['accounts'] as $h => $s) {
        echo "  @$h: $s\n";
    }
    echo '  ' . count($items) . " items merged; newest: " . ($items[0]['timestamp'] ?? 'none') . "\n";
    foreach (array_slice($items, 0, 6) as $it) {
        echo sprintf("  - %s @%s %s %s\n", substr($it['timestamp'], 0, 10), $it['account'], $it['media_type'], first_line($it['caption'], 50));
    }
    exit(in_array(true, array_map(fn($s) => strpos($s, 'FAILED') === 0, $log['accounts']), true) ? 1 : 0);
}

if (!$items && !empty($previous['items'])) {
    fwrite(STDERR, "Nothing fetched; keeping the existing instagram.json.\n");
    exit(1);
}
write_atomic($jsonFile, json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// Clean up media older than 30 days that the feed no longer uses.
$used = [];
foreach ($items as $it) {
    foreach (['thumb', 'video'] as $k) {
        if (!empty($it[$k])) {
            $used[basename($it[$k])] = true;
        }
    }
}
$removed = 0;
foreach (glob("$mediaDir/*") ?: [] as $f) {
    if (!isset($used[basename($f)]) && filemtime($f) < time() - IG_KEEP_DAYS * 86400) {
        unlink($f);
        $removed++;
    }
}
$log['items'] = count($items);
$log['media_removed'] = $removed;
write_atomic(STORAGE . '/fetch-log.json', json_encode($log, JSON_PRETTY_PRINT));
echo "Wrote " . count($items) . " items. " . implode('; ', array_map(fn($h, $s) => "@$h $s", array_keys($log['accounts']), $log['accounts'])) . ". Removed $removed old files.\n";
exit(in_array(true, array_map(fn($s) => strpos($s, 'FAILED') === 0, $log['accounts']), true) ? 1 : 0);
