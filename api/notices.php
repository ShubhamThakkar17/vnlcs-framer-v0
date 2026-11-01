<?php
/**
 * GET /api/notices.php?limit=3[&cat=cbse]
 * Latest notices still on the board, from the same 10-minute Sanity cache as the pages.
 */
require __DIR__ . '/../inc/bootstrap.php';

$limit = max(1, min(20, (int) ($_GET['limit'] ?? 3)));
$cat = isset($_GET['cat'], NOTICE_CATEGORIES[$_GET['cat']]) ? (string) $_GET['cat'] : null;

$items = array_map(fn($n) => [
    'title' => $n['title'],
    'url' => $n['url'],
    'date' => $n['date'],
    'category' => $n['category'],
    'category_label' => $n['category_label'],
    'summary' => $n['summary'],
    'is_new' => is_new($n['date']),
    'pinned' => $n['pinned'],
], board_notices($cat, $limit));

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');
header('X-Content-Type-Options: nosniff');
echo json_encode(['sample' => content_is_sample(), 'items' => $items], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
