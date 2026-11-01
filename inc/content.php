<?php
/**
 * Content from Sanity: notices, newsletters, albums and site settings.
 *
 * One GROQ request fetches everything from the public CDN, cached to
 * STORAGE/cache/sanity.json for 10 minutes. If Sanity is down, the last cache is
 * served. If no Sanity project is configured yet, the clearly marked sample file
 * data/content.sample.json is used so the site can be previewed.
 */
declare(strict_types=1);

const NOTICE_CATEGORIES = [
    'cbse'       => ['label' => 'CBSE Circular',  'tab' => 'CBSE Circulars'],
    'school'     => ['label' => 'School Notice',  'tab' => 'School Notices'],
    'newsletter' => ['label' => 'Newsletter',     'tab' => 'Newsletter'],
    'event'      => ['label' => 'Event',          'tab' => 'Events'],
    'result'     => ['label' => 'Result',         'tab' => 'Results'],
    'holiday'    => ['label' => 'Holiday',        'tab' => 'Holidays'],
];

function sanity_query(): string
{
    return <<<'GROQ'
{
  "notices": *[_type == "notice" && defined(slug.current) && !(_id in path("drafts.**"))] | order(date desc) {
    "id": _id, title, "slug": slug.current, category, date, summary, body,
    "attachment": attachment.asset->url, externalLink, pinned, expiryDate
  },
  "newsletters": *[_type == "newsletter" && defined(slug.current) && !(_id in path("drafts.**"))] | order(month desc) {
    "id": _id, title, "slug": slug.current, month, intro, body,
    "cover": cover.asset->url, "pdf": pdf.asset->url
  },
  "albums": *[_type == "album" && !(_id in path("drafts.**"))] | order(date desc) {
    "id": _id, name, "slug": slug.current, date,
    "cover": cover.asset->url,
    "photos": photos[]{ "url": asset->url, "alt": alt, "w": asset->metadata.dimensions.width, "h": asset->metadata.dimensions.height },
    youtubeUrls
  },
  "settings": *[_type == "siteSettings"][0] {
    admissionBannerText, sessionYear, tickerEnabled, emergencyBanner
  }
}
GROQ;
}

function content_is_sample(): bool
{
    return cfg('sanity.project_id') === '' || cfg('sanity.project_id') === null;
}

/** Raw content (cached). */
function content_raw(): array
{
    static $memo = null;
    if ($memo !== null) {
        return $memo;
    }

    if (content_is_sample()) {
        $memo = json_decode((string) file_get_contents(ROOT . '/data/content.sample.json'), true) ?: [];
        return $memo;
    }

    $cacheFile = STORAGE . '/cache/sanity.json';
    $ttl = (int) cfg('sanity.cache_ttl', 600);
    $cached = is_file($cacheFile) ? json_decode((string) file_get_contents($cacheFile), true) : null;

    if ($cached && (time() - (int) ($cached['fetched_at'] ?? 0)) < $ttl) {
        return $memo = $cached['data'];
    }

    $fresh = sanity_fetch(sanity_query());
    if ($fresh !== null) {
        write_atomic($cacheFile, json_encode(['fetched_at' => time(), 'data' => $fresh], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $memo = $fresh;
    }

    // Sanity unreachable: serve the last good copy, however old.
    return $memo = $cached['data'] ?? ['notices' => [], 'newsletters' => [], 'albums' => [], 'settings' => null];
}

function sanity_fetch(string $groq): ?array
{
    $url = sprintf(
        'https://%s.apicdn.sanity.io/v%s/data/query/%s?query=%s',
        rawurlencode((string) cfg('sanity.project_id')),
        cfg('sanity.api_version'),
        rawurlencode((string) cfg('sanity.dataset')),
        rawurlencode($groq)
    );
    $body = http_get($url, 5);
    if ($body === null) {
        return null;
    }
    $json = json_decode($body, true);
    return is_array($json) && array_key_exists('result', $json) ? (array) $json['result'] : null;
}

/** Minimal HTTP GET with a timeout. Returns the body or null on any failure. */
function http_get(string $url, int $timeout = 5): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_USERAGENT => 'VeerNewLookWebsite/1.0',
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($body !== false && $code >= 200 && $code < 300) ? (string) $body : null;
    }
    $ctx = stream_context_create(['http' => ['timeout' => $timeout, 'ignore_errors' => true, 'header' => "User-Agent: VeerNewLookWebsite/1.0\r\n"]]);
    $body = @file_get_contents($url, false, $ctx);
    $ok = isset($http_response_header[0]) && preg_match('#\s2\d\d\s#', $http_response_header[0]);
    return ($body !== false && $ok) ? $body : null;
}

/** Write a file atomically: temp file, then rename. */
function write_atomic(string $file, string $contents): bool
{
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $tmp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    if (@file_put_contents($tmp, $contents, LOCK_EX) === false) {
        return false;
    }
    return @rename($tmp, $file);
}

/** Site settings with safe defaults. */
function site_settings(): array
{
    $s = content_raw()['settings'] ?? [];
    return [
        'admissionBannerText' => (string) ($s['admissionBannerText'] ?? ''),
        'sessionYear'         => (string) ($s['sessionYear'] ?? ''),
        'tickerEnabled'       => (bool) ($s['tickerEnabled'] ?? true),
        'emergencyBanner'     => (string) ($s['emergencyBanner'] ?? ''),
    ];
}

function session_label(): string
{
    $y = site_settings()['sessionYear'];
    return $y !== '' ? $y : '[SESSION]';
}

/**
 * All notices and newsletters as one list, newest first.
 * Each item: id, type, title, slug, category, category_label, date, summary, body,
 *            attachment, link, pinned, expiry, expired, url, cover
 */
function all_notices(): array
{
    static $memo = null;
    if ($memo !== null) {
        return $memo;
    }
    $raw = content_raw();
    $today = date('Y-m-d');
    $items = [];

    foreach ($raw['notices'] ?? [] as $n) {
        $cat = isset(NOTICE_CATEGORIES[$n['category'] ?? '']) ? $n['category'] : 'school';
        $expiry = $n['expiryDate'] ?? null;
        $items[] = [
            'id' => (string) $n['id'],
            'type' => 'notice',
            'title' => (string) ($n['title'] ?? ''),
            'slug' => (string) ($n['slug'] ?? ''),
            'category' => $cat,
            'category_label' => NOTICE_CATEGORIES[$cat]['label'],
            'date' => (string) ($n['date'] ?? ''),
            'summary' => (string) ($n['summary'] ?? ''),
            'body' => $n['body'] ?? [],
            'attachment' => $n['attachment'] ?? null,
            'link' => $n['externalLink'] ?? null,
            'pinned' => (bool) ($n['pinned'] ?? false),
            'expiry' => $expiry,
            'expired' => $expiry ? ($expiry < $today) : false,
            'cover' => null,
        ];
    }
    foreach ($raw['newsletters'] ?? [] as $n) {
        $items[] = [
            'id' => (string) $n['id'],
            'type' => 'newsletter',
            'title' => (string) ($n['title'] ?? ''),
            'slug' => (string) ($n['slug'] ?? ''),
            'category' => 'newsletter',
            'category_label' => 'Newsletter',
            'date' => (string) ($n['month'] ?? ''),
            'summary' => (string) ($n['intro'] ?? ''),
            'body' => $n['body'] ?? [],
            'attachment' => $n['pdf'] ?? null,
            'link' => null,
            'pinned' => false,
            'expiry' => null,
            'expired' => false,
            'cover' => $n['cover'] ?? null,
        ];
    }
    foreach ($items as &$it) {
        $it['url'] = '/notices/' . rawurlencode($it['slug']);
    }
    unset($it);
    usort($items, fn($a, $b) => strcmp($b['date'], $a['date']));
    return $memo = $items;
}

/** Notices still on the board (not expired), pinned first. */
function board_notices(?string $category = null, ?int $limit = null): array
{
    $items = array_values(array_filter(all_notices(), function ($n) use ($category) {
        return !$n['expired'] && ($category === null || $n['category'] === $category);
    }));
    usort($items, function ($a, $b) {
        return [$b['pinned'], $b['date']] <=> [$a['pinned'], $a['date']];
    });
    return $limit ? array_slice($items, 0, $limit) : $items;
}

function find_notice(string $slug): ?array
{
    foreach (all_notices() as $n) {
        if ($n['slug'] === $slug) {
            return $n;
        }
    }
    return null;
}

function albums(): array
{
    return content_raw()['albums'] ?? [];
}

/** Render Sanity portable text (blocks, lists, strong/em/underline/link marks). */
function portable_text(array $blocks): string
{
    $html = '';
    $openList = null;
    foreach ($blocks as $b) {
        if (($b['_type'] ?? '') !== 'block') {
            continue;
        }
        $list = $b['listItem'] ?? null;
        if ($list !== $openList) {
            if ($openList) {
                $html .= $openList === 'number' ? '</ol>' : '</ul>';
            }
            if ($list) {
                $html .= $list === 'number' ? '<ol>' : '<ul>';
            }
            $openList = $list;
        }
        $defs = [];
        foreach ($b['markDefs'] ?? [] as $d) {
            $defs[$d['_key']] = $d;
        }
        $inner = '';
        foreach ($b['children'] ?? [] as $span) {
            $text = nl2br(e($span['text'] ?? ''));
            foreach ($span['marks'] ?? [] as $m) {
                if ($m === 'strong') {
                    $text = "<strong>$text</strong>";
                } elseif ($m === 'em') {
                    $text = "<em>$text</em>";
                } elseif ($m === 'underline') {
                    $text = "<u>$text</u>";
                } elseif (isset($defs[$m]) && ($defs[$m]['_type'] ?? '') === 'link') {
                    $href = (string) ($defs[$m]['href'] ?? '#');
                    if (preg_match('#^(https?:|mailto:|tel:|/)#', $href)) {
                        $ext = preg_match('#^https?:#', $href) ? ' rel="noopener"' : '';
                        $text = '<a href="' . e($href) . '"' . $ext . '>' . $text . '</a>';
                    }
                }
            }
            $inner .= $text;
        }
        if ($list) {
            $html .= "<li>$inner</li>";
            continue;
        }
        $tag = ['h2' => 'h2', 'h3' => 'h3', 'h4' => 'h4', 'blockquote' => 'blockquote'][$b['style'] ?? 'normal'] ?? 'p';
        if ($tag === 'h2') {
            $tag = 'h3'; // the page H1/H2 belong to the layout
        }
        $html .= "<$tag>$inner</$tag>";
    }
    if ($openList) {
        $html .= $openList === 'number' ? '</ol>' : '</ul>';
    }
    return $html;
}
