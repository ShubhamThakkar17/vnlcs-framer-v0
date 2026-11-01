<?php
/**
 * Site configuration. Safe to commit: nothing here is secret.
 * Secrets (API keys, Instagram tokens) live in config/secrets.php (gitignored).
 */
return [
    'site_name'  => 'Veer New Look Central School, Halol',
    'short_name' => 'Veer New Look',
    'site_url'   => 'https://veernewlookschool.com',
    'tagline'    => 'Play, Learn & Grow Together!',
    'founded'    => 2005,

    'address' => [
        'street'   => 'Godhra Road, beside HDFC Bank',
        'locality' => 'Halol',
        'district' => 'Panchmahal',
        'region'   => 'Gujarat',
        'postcode' => '389350',
        'country'  => 'IN',
    ],
    'phones' => [
        ['label' => '02676 225955',    'tel' => '+912676225955'],
        ['label' => '+91 80002 01153', 'tel' => '+918000201153'],
    ],
    'emails'   => ['veer.newlook@gmail.com', 'veernewlookoffice@gmail.com'],
    'whatsapp' => '918000201153',          // [TO CONFIRM] that this number is on WhatsApp
    'map_query' => 'Veer New Look Central School, Godhra Road, Halol, Gujarat 389350',
    'instagram' => [
        ['handle' => 'vnlcs_halol',            'url' => 'https://www.instagram.com/vnlcs_halol/'],
        ['handle' => 'veernewlookschoolhalol', 'url' => 'https://www.instagram.com/veernewlookschoolhalol/'],
    ],
    'youtube' => '',                        // channel URL, only if the school has one
    'trust'   => 'Manibhadra Veer Education Trust',
    'group'   => ['name' => 'New Look Group of Institutions', 'url' => 'https://newlookschool.com/'],

    // Sanity (notices, newsletters, albums, site settings). Public dataset, read-only CDN.
    // Leave project_id empty to use the clearly marked sample data in /data/*.sample.json.
    'sanity' => [
        'project_id'  => '',
        'dataset'     => 'production',
        'api_version' => '2024-06-01',
        'cache_ttl'   => 600,               // seconds (10 minutes)
    ],

    // Instagram reels source: 'local' = /data/instagram.json written by cron/instagram-fetch.php
    //                         'behold' = the Behold JSON feed below (paid fallback)
    'reels_source' => 'local',
    'behold_url'   => '',

    // Public-listing figures stay hidden until the school confirms them in writing.
    'stats_confirmed' => false,
];
