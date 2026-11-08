<?php
/**
 * Writes data/notices.json as a Sanity import file (one JSON document per line).
 * Usage: php tools/notices-to-sanity.php > sanity/import/notices.ndjson
 *        cd sanity && npx sanity dataset import import/notices.ndjson production --missing
 *
 * "published" notices are imported as published documents; "draft" and "check" ones as
 * drafts (drafts.<id>), so nothing unchecked goes live. Category keys, links and body
 * blocks match the Studio schema (sanity/schemaTypes/notice.js).
 */
declare(strict_types=1);
require __DIR__ . '/../inc/bootstrap.php';

foreach (local_notices(false) as $n) {
    $id = 'notice-' . $n['slug'];
    $doc = [
        '_id' => ($n['status'] ?? '') === 'published' ? $id : 'drafts.' . $id,
        '_type' => 'notice',
        'title' => $n['title'],
        'slug' => ['_type' => 'slug', 'current' => $n['slug']],
        'category' => $n['category'],
        'date' => $n['date'],
        'summary' => $n['summary'],
        'body' => $n['body'],
        'externalLink' => $n['externalLink'] ?: null,
        'pinned' => (bool) ($n['pinned'] ?? false),
        'expiryDate' => $n['expiryDate'] ?? null,
        'circularNo' => $n['circularNo'] ?? null,
        'issuedBy' => $n['issuedBy'] ?? null,
        'forClasses' => $n['forClasses'] ?? null,
    ];
    if ($n['category'] === 'newsletter') {
        continue;   // newsletters are their own document type in Studio
    }
    echo json_encode(array_filter($doc, fn($v) => $v !== null), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "\n";
}
