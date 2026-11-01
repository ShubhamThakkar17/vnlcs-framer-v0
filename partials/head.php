<?php
/**
 * Document head + masthead. Expects $page = [
 *   'title' => 'About', 'description' => '...', 'path' => '/about/',
 *   'crumbs' => [['Home', '/'], ['About', '/about/']], 'jsonld' => [ ...schemas ],
 *   'og_image' => '/assets/img/og-default.png', 'robots' => 'index, follow',
 *   'scripts' => ['reels.js'], 'body_class' => 'page-home'
 * ]
 */
$page = $page ?? [];
$siteName = cfg('site_name');
$title = isset($page['title']) && $page['title'] !== '' ? $page['title'] . ' | ' . $siteName : $siteName;
if (!empty($page['full_title'])) {
    $title = $page['full_title'];
}
$desc = $page['description'] ?? 'Veer New Look Central School, Halol: a CBSE-affiliated, English-medium, co-educational school from Nursery to Class 12, since 2005.';
$path = $page['path'] ?? current_path();
$canonical = abs_url($path);
$ogImage = abs_url($page['og_image'] ?? '/assets/img/og-default.png');
$robots = $page['robots'] ?? 'index, follow';
?><!doctype html>
<html lang="en-IN" data-theme="blackboard">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($desc) ?>">
  <meta name="robots" content="<?= e($robots) ?>">
  <link rel="canonical" href="<?= e($canonical) ?>">
  <meta name="theme-color" content="#1E2B25">
  <script>
    /* Set the board before first paint so there is no flash. First visit: Blackboard. */
    (function () {
      var t = null;
      try { t = localStorage.getItem('vnl-theme'); } catch (e) {}
      if (t === 'whiteboard') {
        document.documentElement.setAttribute('data-theme', 'whiteboard');
        document.querySelector('meta[name="theme-color"]').setAttribute('content', '#FAFAF7');
      }
      document.documentElement.className += ' js';
    })();
  </script>
  <link rel="preload" href="/assets/fonts/cabin-sketch-400.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/assets/fonts/kalam-400.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= asset('css/tokens.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/base.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/pages.css') ?>">
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
  <link rel="icon" href="/favicon.ico" sizes="32x32">
  <link rel="apple-touch-icon" href="/assets/img/icon-180.png">
  <meta property="og:type" content="<?= e($page['og_type'] ?? 'website') ?>">
  <meta property="og:site_name" content="<?= e($siteName) ?>">
  <meta property="og:title" content="<?= e($page['og_title'] ?? $title) ?>">
  <meta property="og:description" content="<?= e($desc) ?>">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary_large_image">
<?php
foreach ($page['jsonld'] ?? [] as $schema) {
    echo '  ' . json_ld($schema);
}
if (!empty($page['crumbs']) && count($page['crumbs']) > 1) {
    echo '  ' . json_ld(breadcrumb_schema($page['crumbs']));
}
?>
</head>
<body class="<?= e($page['body_class'] ?? '') ?>">
<?php require ROOT . '/partials/header.php'; ?>
