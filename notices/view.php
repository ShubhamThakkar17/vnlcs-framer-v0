<?php
if (!defined('ROOT') || !function_exists('cfg')) {
    require __DIR__ . '/../inc/bootstrap.php';
}
$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['slug'])) : '';
$n = $slug !== '' ? find_notice($slug) : null;
if (!$n) {
    http_response_code(404);
    require ROOT . '/404.php';
    exit;
}

$url = abs_url($n['url']);
$crumbs = [['Home', '/'], ['Notice Board', '/notices/'], [$n['title'], $n['url']]];
$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => mb_substr($n['title'], 0, 110),
    'description' => $n['summary'],
    'datePublished' => $n['date'],
    'dateModified' => $n['date'],
    'mainEntityOfPage' => $url,
    'url' => $url,
    'image' => [abs_url('/assets/img/og-default.png')],
    'articleSection' => $n['category_label'],
    'author' => ['@type' => 'Organization', 'name' => cfg('site_name'), 'url' => abs_url('/')],
    'publisher' => [
        '@type' => 'Organization',
        'name' => cfg('site_name'),
        'logo' => ['@type' => 'ImageObject', 'url' => abs_url('/assets/img/icon-512.png')],
    ],
];
$page = [
    'title' => $n['title'] . ' | Notice Board',
    'description' => $n['summary'] !== '' ? $n['summary'] : $n['category_label'] . ' from Veer New Look Central School, Halol.',
    'path' => $n['url'],
    'crumbs' => $crumbs,
    'jsonld' => [$schema],
    'og_type' => 'article',
    'og_title' => $n['title'],
    'body_class' => 'page-notice',
];
require ROOT . '/partials/head.php';
$shareText = $n['title'] . ' | Veer New Look Central School, Halol';
?>
<main id="main">
  <article class="section-tight notice-page">
    <div class="container notice-page__inner">
      <nav class="crumbs" aria-label="Breadcrumb">
        <ol role="list">
          <li><a href="/">Home</a></li>
          <li><a href="/notices/">Notice Board</a></li>
          <li><span aria-current="page"><?= e($n['category_label']) ?></span></li>
        </ol>
      </nav>

      <div class="notice-sheet paper">
        <div class="note__head">
          <span class="note__cat"><?= e($n['category_label']) ?></span>
          <time class="note__date" datetime="<?= e($n['date']) ?>"><?= e(nice_date($n['date'], $n['category'] === 'newsletter' ? 'F Y' : 'l, j F Y')) ?></time>
          <?php if (is_new($n['date'])): ?><span class="tag tag--new">New</span><?php endif; ?>
        </div>
        <h1 class="notice-sheet__title"><?= e($n['title']) ?></h1>
<?php if ($n['expired']): ?>
        <p class="notice-sheet__expired">This notice has expired. It is kept in the archive for reference.</p>
<?php endif; ?>
<?php if ($n['summary']): ?>
        <p class="notice-sheet__summary"><?= e($n['summary']) ?></p>
<?php endif; ?>
<?php if (!empty($n['body'])): ?>
        <div class="prose"><?= portable_text($n['body']) ?></div>
<?php endif; ?>

<?php if ($n['attachment'] || $n['link']): ?>
        <div class="notice-sheet__actions">
          <?php if ($n['attachment']): ?>
          <a class="btn btn--primary btn--small notice-btn" href="<?= e($n['attachment']) ?>?dl="><?= icon('file') ?><?= $n['category'] === 'newsletter' ? 'Download the newsletter (PDF)' : 'Download the PDF' ?></a>
          <?php endif; ?>
          <?php if ($n['link']): ?>
          <a class="btn btn--secondary btn--small notice-btn" href="<?= e($n['link']) ?>" rel="noopener"><?= icon('external') ?><?= $n['category'] === 'cbse' ? 'Read the official CBSE circular' : 'Open the link' ?></a>
          <?php endif; ?>
        </div>
<?php endif; ?>
      </div>

      <div class="share" aria-label="Share this notice" role="group">
        <span class="share__label">Share:</span>
        <a class="btn btn--small share__wa" href="<?= e(whatsapp_share_url($shareText, $url)) ?>" rel="noopener"><?= icon('whatsapp') ?>WhatsApp</a>
        <button type="button" class="btn btn--small" data-copy="<?= e($url) ?>"><?= icon('link') ?><span data-copy-status>Copy link</span></button>
      </div>

      <p class="notice-page__back"><a href="/notices/">← Back to the Notice Board</a></p>
    </div>
  </article>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
