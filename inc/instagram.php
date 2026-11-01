<?php
/**
 * Reads the reels feed written by cron/instagram-fetch.php (data/instagram.json).
 * Falls back to the clearly marked sample file so the section is never empty.
 */
declare(strict_types=1);

function reels_data(): array
{
    static $memo = null;
    if ($memo !== null) {
        return $memo;
    }
    $live = STORAGE . '/instagram.json';
    $file = is_file($live) ? $live : ROOT . '/data/instagram.sample.json';
    $json = json_decode((string) @file_get_contents($file), true) ?: [];
    $items = [];
    foreach ($json['items'] ?? [] as $it) {
        $items[] = [
            'id' => (string) ($it['id'] ?? ''),
            'account' => (string) ($it['account'] ?? ''),
            'caption' => first_line((string) ($it['caption'] ?? '')),
            'permalink' => (string) ($it['permalink'] ?? ''),
            'thumb' => $it['thumb'] ?? null,
            'video' => $it['video'] ?? null,
            'timestamp' => (string) ($it['timestamp'] ?? ''),
            'media_type' => (string) ($it['media_type'] ?? ''),
        ];
    }
    $generated = strtotime((string) ($json['generated_at'] ?? '')) ?: 0;
    return $memo = [
        'items' => $items,
        'sample' => !empty($json['sample']),
        'stale' => $generated < time() - 3 * 86400,
        'generated_at' => $generated,
    ];
}

function first_line(string $text, int $max = 90): string
{
    $line = trim(strtok($text . "\n", "\n"));
    if (function_exists('mb_strlen') && mb_strlen($line) > $max) {
        $line = rtrim(mb_substr($line, 0, $max - 1)) . '…';
    }
    return $line;
}

function account_url(string $handle): string
{
    return 'https://www.instagram.com/' . rawurlencode($handle) . '/';
}

/** One reel in a chalk-drawn phone frame. */
function render_reel(array $r, string $loading = 'lazy'): string
{
    $handle = '@' . $r['account'];
    $link = $r['permalink'] ?: account_url($r['account']);
    $h = '<li class="reel" data-account="' . e($r['account']) . '"><div class="reel__phone"><div class="reel__screen">';
    if ($r['video']) {
        $h .= '<video muted loop playsinline preload="none"'
            . ($r['thumb'] ? ' poster="' . e($r['thumb']) . '"' : '')
            . ' src="' . e($r['video']) . '" aria-label="Reel from ' . e($handle) . '"></video>'
            . '<button type="button" class="reel__sound" aria-pressed="false" aria-label="Turn sound on">'
            . icon('sound-off', 'icon reel__off') . icon('sound-on', 'icon reel__on') . '</button>';
    } elseif ($r['thumb']) {
        $h .= '<img src="' . e($r['thumb']) . '" alt="" width="540" height="960" loading="' . e($loading) . '" decoding="async">';
    } else {
        $doodles = ['sun', 'globe', 'bat', 'star', 'beaker', 'abc'];
        $d = $doodles[abs(crc32($r['id'])) % count($doodles)];
        $h .= '<div class="reel__placeholder">' . doodle($d, '', 'margin-inline:auto') . '<span>[REEL]</span></div>';
    }
    $h .= '<span class="reel__handle">' . e($handle) . '</span></div></div>';
    $h .= '<a class="reel__caption" href="' . e($link) . '" rel="noopener">' . e($r['caption'] ?: 'Watch on Instagram')
        . '<span class="visually-hidden"> (opens Instagram)</span></a></li>';
    return $h;
}
