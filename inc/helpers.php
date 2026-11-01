<?php
/**
 * Small helpers shared by every page.
 */
declare(strict_types=1);

function cfg(string $key, $default = null)
{
    $value = $GLOBALS['CONFIG'];
    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $default;
        }
        $value = $value[$part];
    }
    return $value;
}

function secret(string $key, $default = null)
{
    return $GLOBALS['SECRETS'][$key] ?? $default;
}

/** Escape for HTML text and attributes. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Root-relative asset URL with a cache-busting version from the file's mtime. */
function asset(string $path): string
{
    $file = ROOT . '/assets/' . ltrim($path, '/');
    $v = is_file($file) ? '?v=' . base_convert((string) filemtime($file), 10, 36) : '';
    return '/assets/' . ltrim($path, '/') . $v;
}

/** Absolute URL on the live domain (canonical, Open Graph, JSON-LD, share links). */
function abs_url(string $path = '/'): string
{
    return rtrim((string) cfg('site_url'), '/') . '/' . ltrim($path, '/');
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return $path;
}

function is_current(string $href): bool
{
    $path = current_path();
    if ($href === '/') {
        return $path === '/' || $path === '/index.php';
    }
    return strpos($path, $href) === 0;
}

/** Main navigation, shared by header and footer. */
function nav_items(): array
{
    return [
        ['href' => '/',            'label' => 'Home'],
        ['href' => '/about/',      'label' => 'About'],
        ['href' => '/academics/',  'label' => 'Academics'],
        ['href' => '/facilities/', 'label' => 'Facilities'],
        ['href' => '/gallery/',    'label' => 'Gallery'],
        ['href' => '/notices/',    'label' => 'Notice Board'],
        ['href' => '/admissions/', 'label' => 'Admissions'],
    ];
}

/** Format an ISO date as "28 Oct 2026". */
function nice_date(?string $iso, string $format = 'j M Y'): string
{
    if (!$iso) {
        return '';
    }
    $ts = strtotime($iso);
    return $ts ? date($format, $ts) : '';
}

/** "New" tag shows for 7 days after the notice date. */
function is_new(?string $iso, int $days = 7): bool
{
    if (!$iso) {
        return false;
    }
    $ts = strtotime($iso);
    return $ts && $ts <= time() && (time() - $ts) < $days * 86400;
}

function whatsapp_share_url(string $text, string $url): string
{
    return 'https://wa.me/?text=' . rawurlencode($text . ' ' . $url);
}

function whatsapp_chat_url(string $message = ''): string
{
    $u = 'https://wa.me/' . cfg('whatsapp');
    return $message !== '' ? $u . '?text=' . rawurlencode($message) : $u;
}

function map_url(): string
{
    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode((string) cfg('map_query'));
}

function json_ld(array $data): string
{
    $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    return '<script type="application/ld+json">' . $json . '</script>' . "\n";
}

/** schema.org School, used on the home page and the contact section. */
function school_schema(): array
{
    $a = cfg('address');
    return [
        '@context' => 'https://schema.org',
        '@type' => 'School',
        '@id' => abs_url('/#school'),
        'name' => cfg('site_name'),
        'alternateName' => cfg('short_name'),
        'url' => abs_url('/'),
        'logo' => abs_url('/assets/img/icon-512.png'),
        'image' => abs_url('/assets/img/og-default.png'),
        'slogan' => cfg('tagline'),
        'foundingDate' => (string) cfg('founded'),
        'email' => cfg('emails')[0],
        'telephone' => array_map(fn($p) => $p['tel'], cfg('phones')),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $a['street'],
            'addressLocality' => $a['locality'],
            'addressRegion' => $a['region'],
            'postalCode' => $a['postcode'],
            'addressCountry' => $a['country'],
        ],
        'sameAs' => array_map(fn($i) => $i['url'], cfg('instagram')),
        'parentOrganization' => [
            '@type' => 'EducationalOrganization',
            'name' => cfg('group.name'),
            'url' => cfg('group.url'),
        ],
    ];
}

function breadcrumb_schema(array $crumbs): array
{
    $items = [];
    foreach (array_values($crumbs) as $i => [$name, $path]) {
        $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => abs_url($path)];
    }
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
}

/** Inline SVG icons (our own simple line drawings). Always decorative. */
function icon(string $name, string $class = 'icon'): string
{
    $s = 'fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"';
    $paths = [
        'phone'     => '<path ' . $s . ' d="M6.6 3.5 9 3l1.6 4.2-2 1.3a11 11 0 0 0 6.9 6.9l1.3-2L21 15l-.5 2.4c-.2 1-1.1 1.7-2.1 1.6C10.6 18.5 5.5 13.4 5 5.6c-.1-1 .6-1.9 1.6-2.1z"/>',
        'mobile'    => '<rect ' . $s . ' x="7" y="2.5" width="10" height="19" rx="2.2"/><path ' . $s . ' d="M10.5 18.5h3"/>',
        'mail'      => '<rect ' . $s . ' x="3" y="5" width="18" height="14" rx="2"/><path ' . $s . ' d="m3.5 6 8.5 7 8.5-7"/>',
        'instagram' => '<rect ' . $s . ' x="3.5" y="3.5" width="17" height="17" rx="5"/><circle ' . $s . ' cx="12" cy="12" r="3.8"/><circle cx="17" cy="7" r="1.1" fill="currentColor"/>',
        'youtube'   => '<rect ' . $s . ' x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="m10 9 5 3-5 3z" fill="currentColor"/>',
        'whatsapp'  => '<path ' . $s . ' d="M4.5 19.5 5.6 16A8 8 0 1 1 8.3 18.6z"/><path ' . $s . ' d="M9.2 8.4c.3-.6.9-.6 1.1 0l.6 1.5c.1.3 0 .6-.2.8l-.5.5c.5 1.1 1.4 2 2.6 2.6l.5-.5c.2-.2.5-.3.8-.2l1.5.6c.6.2.6.8 0 1.1-1 .6-2.3.6-3.6-.1a8 8 0 0 1-3-3c-.6-1.2-.6-2.4.2-3.3z"/>',
        'map'       => '<path ' . $s . ' d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11z"/><circle ' . $s . ' cx="12" cy="10" r="2.4"/>',
        'clock'     => '<circle ' . $s . ' cx="12" cy="12" r="8.5"/><path ' . $s . ' d="M12 7.5V12l3 2"/>',
        'arrow'     => '<path ' . $s . ' d="M5 12h13M13 6.5l5.5 5.5-5.5 5.5"/>',
        'arrow-up'  => '<path ' . $s . ' d="M12 19V6M6.5 11.5 12 6l5.5 5.5"/>',
        'menu'      => '<path ' . $s . ' d="M4 7h16M4 12h16M4 17h16"/>',
        'close'     => '<path ' . $s . ' d="M6 6l12 12M18 6 6 18"/>',
        'pause'     => '<path d="M7 4h3.5v16H7zM13.5 4H17v16h-3.5z" fill="currentColor"/>',
        'play'      => '<path d="M7 4l13 8-13 8z" fill="currentColor"/>',
        'sound-off' => '<path ' . $s . ' d="M4 9.5h3.5L12 5.5v13l-4.5-4H4zM16 9.5l5 5M21 9.5l-5 5"/>',
        'sound-on'  => '<path ' . $s . ' d="M4 9.5h3.5L12 5.5v13l-4.5-4H4zM16 9a4.5 4.5 0 0 1 0 6M18.5 6.5a8 8 0 0 1 0 11"/>',
        'link'      => '<path ' . $s . ' d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
        'file'      => '<path ' . $s . ' d="M6 2.5h8l4 4v15H6z"/><path ' . $s . ' d="M14 2.5v4h4M9 13h6M9 17h6"/>',
        'external'  => '<path ' . $s . ' d="M14 4h6v6M20 4l-9 9M18 14v6H4V6h6"/>',
        'check'     => '<path ' . $s . ' d="m5 12.5 4.5 4.5L19 7"/>',
        'calendar'  => '<rect ' . $s . ' x="3.5" y="5" width="17" height="15.5" rx="2"/><path ' . $s . ' d="M3.5 10h17M8 3v4M16 3v4"/>',
    ];
    $inner = $paths[$name] ?? '';
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $inner . '</svg>';
}

/** Chalk doodle (CSS mask, coloured by theme). */
function doodle(string $name, string $extra = '', string $style = ''): string
{
    $wide = in_array($name, ['abc', 'sum'], true) ? ' doodle--wide' : '';
    return '<span class="doodle doodle--' . e($name) . $wide . ($extra ? ' ' . e($extra) : '') . '"'
        . ($style ? ' style="' . e($style) . '"' : '') . ' aria-hidden="true"></span>';
}

/**
 * A taped photo. Until real photos exist (no stock photos of children), it shows a
 * visible "[PHOTO: ...]" placeholder. $img = ['src' => ..., 'alt' => ..., 'w' => .., 'h' => ..].
 */
function taped_photo(string $label, string $caption = '', ?array $img = null, string $class = ''): string
{
    $html = '<figure class="photo ' . e($class) . '">';
    if ($img) {
        $html .= picture($img['src'], $img['alt'], (int) $img['w'], (int) $img['h'], $img['loading'] ?? 'lazy');
    } else {
        $html .= '<div class="photo__placeholder"><span>[PHOTO: ' . e($label) . ']</span></div>';
    }
    if ($caption !== '') {
        $html .= '<figcaption>' . e($caption) . '</figcaption>';
    }
    return $html . '</figure>';
}

/**
 * <picture> for an image optimised by tools/optimise-images.js.
 * $src is the base name in /assets/img/ without extension, e.g. "campus/library".
 * Expects {base}-{w}.avif / .webp / .jpg at widths 480, 960, 1440.
 */
function picture(string $src, string $alt, int $w, int $h, string $loading = 'lazy', string $sizes = '(min-width: 900px) 50vw, 100vw'): string
{
    $widths = [480, 960, 1440];
    $set = function (string $ext) use ($src, $widths): string {
        $parts = [];
        foreach ($widths as $wd) {
            if (is_file(ROOT . "/assets/img/{$src}-{$wd}.{$ext}")) {
                $parts[] = "/assets/img/{$src}-{$wd}.{$ext} {$wd}w";
            }
        }
        return implode(', ', $parts);
    };
    $html = '<picture>';
    foreach (['avif' => 'image/avif', 'webp' => 'image/webp'] as $ext => $type) {
        if ($s = $set($ext)) {
            $html .= '<source type="' . $type . '" srcset="' . e($s) . '" sizes="' . e($sizes) . '">';
        }
    }
    $fallback = "/assets/img/{$src}-960.jpg";
    $html .= '<img src="' . e($fallback) . '" srcset="' . e($set('jpg')) . '" sizes="' . e($sizes) . '" alt="' . e($alt)
        . '" width="' . $w . '" height="' . $h . '" loading="' . e($loading) . '" decoding="async">';
    return $html . '</picture>';
}

/** Section heading: eyebrow + stacked H2 lines. Lines may contain trusted inline HTML. */
function section_heading(string $eyebrow, array $lines, string $id, string $extra = ''): string
{
    $h = '<div class="section-head ' . e($extra) . '"><div>';
    if ($eyebrow !== '') {
        $h .= '<p class="eyebrow">' . e($eyebrow) . '</p>';
    }
    $h .= '<h2 id="' . e($id) . '">';
    foreach ($lines as $line) {
        $h .= '<span class="line">' . $line . '</span> ';
    }
    return $h . '</h2></div></div>';
}

/** The tiny-board theme toggle. Rendered twice (top strip on phones, header on wider screens); CSS shows one. */
function theme_toggle(string $class = ''): string
{
    return '<div class="theme-toggle ' . e($class) . '" role="group" aria-label="Board style">'
        . '<button type="button" data-theme-set="blackboard" aria-pressed="true">Blackboard</button>'
        . '<span class="theme-toggle__sep" aria-hidden="true">/</span>'
        . '<button type="button" data-theme-set="whiteboard" aria-pressed="false">Whiteboard</button>'
        . '</div>';
}
