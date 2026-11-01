<?php
require __DIR__ . '/../inc/bootstrap.php';

$tabs = ['all' => 'All'] + array_map(fn($c) => $c['tab'], array_intersect_key(NOTICE_CATEGORIES, array_flip(['cbse', 'school', 'newsletter', 'event', 'result'])));
$cat = isset($_GET['cat'], NOTICE_CATEGORIES[$_GET['cat']]) ? (string) $_GET['cat'] : 'all';
$archive = isset($_GET['view']) && $_GET['view'] === 'archive';
$month = isset($_GET['month']) && preg_match('/^\d{4}-\d{2}$/', (string) $_GET['month']) ? (string) $_GET['month'] : '';

$all = all_notices();
if ($archive) {
    $list = array_values(array_filter($all, fn($n) => ($cat === 'all' || $n['category'] === $cat) && ($month === '' || strpos($n['date'], $month) === 0)));
} else {
    $list = board_notices($cat === 'all' ? null : $cat);
}
$months = [];
foreach ($all as $n) {
    if ($n['date']) {
        $months[substr($n['date'], 0, 7)] = nice_date($n['date'], 'F Y');
    }
}
krsort($months);

$crumbs = [['Home', '/'], ['Notice Board', '/notices/']];
$qs = function (array $over) use ($cat, $archive, $month): string {
    $q = array_filter(array_merge(['cat' => $cat === 'all' ? null : $cat, 'view' => $archive ? 'archive' : null, 'month' => $month ?: null], $over), fn($v) => $v !== null && $v !== '');
    return '/notices/' . ($q ? '?' . http_build_query($q) : '');
};
$page = [
    'title' => $archive ? 'Notice archive' : 'Notice Board',
    'description' => 'Notice Board of Veer New Look Central School, Halol: CBSE circulars, school notices, newsletters, events and results.',
    'path' => $qs([]),
    'crumbs' => $crumbs,
    'scripts' => ['notices.js'],
    'body_class' => 'page-notices',
];
if ($month !== '' || ($archive && $cat !== 'all')) {
    $page['robots'] = 'noindex, follow';
}
require ROOT . '/partials/head.php';
?>
<main id="main">
  <?= page_hero('Notice board', ['Notice', '<span class="hl">Board</span>'],
      'CBSE circulars, school notices, newsletters, events and results. Every notice has its own link to share.',
      $crumbs, 'abc') ?>

  <section class="section-tight" aria-labelledby="board-title">
    <div class="container board-layout">
      <div class="board-main">
        <h2 id="board-title" class="visually-hidden"><?= $archive ? 'Archive' : 'Current notices' ?></h2>
        <nav class="tabs" aria-label="Notice categories">
          <ul role="list">
<?php foreach ($tabs as $key => $label): ?>
            <li><a href="<?= e($qs(['cat' => $key === 'all' ? null : $key])) ?>"<?= $cat === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
<?php endforeach; ?>
          </ul>
        </nav>

        <div class="board-view">
          <a href="<?= e($qs(['view' => null, 'month' => null])) ?>"<?= !$archive ? ' aria-current="page"' : '' ?>>On the board now</a>
          <a href="<?= e($qs(['view' => 'archive'])) ?>"<?= $archive ? ' aria-current="page"' : '' ?>>Archive (all notices)</a>
        </div>

<?php if ($archive): ?>
        <form class="month-filter" method="get" action="/notices/" data-autosubmit>
          <input type="hidden" name="view" value="archive">
          <?php if ($cat !== 'all'): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
          <label for="month">Month</label>
          <select id="month" name="month">
            <option value="">All months</option>
<?php foreach ($months as $k => $label): ?>
            <option value="<?= e($k) ?>"<?= $k === $month ? ' selected' : '' ?>><?= e($label) ?></option>
<?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn--small">Show</button>
        </form>
<?php endif; ?>

        <p class="board-count" role="status"><?= count($list) ?> <?= count($list) === 1 ? 'notice' : 'notices' ?></p>
<?php if ($list): ?>
        <ul class="notes notes--board" role="list">
<?php foreach ($list as $n): ?>
          <li class="note-wrap">
            <article class="note paper<?= $n['pinned'] && !$archive ? ' note--pinned' : '' ?>">
              <div class="note__head">
                <span class="note__cat"><?= e($n['category_label']) ?></span>
                <time class="note__date" datetime="<?= e($n['date']) ?>"><?= e(nice_date($n['date'], $n['category'] === 'newsletter' ? 'F Y' : 'j M Y')) ?></time>
                <?php if (is_new($n['date'])): ?><span class="tag tag--new">New</span><?php endif; ?>
                <?php if ($n['pinned'] && !$archive): ?><span class="note__pin">Pinned</span><?php endif; ?>
                <?php if ($n['expired']): ?><span class="note__pin">Archived</span><?php endif; ?>
              </div>
              <h3><a class="note__title" href="<?= e($n['url']) ?>"><?= e($n['title']) ?></a></h3>
              <?php if ($n['summary']): ?><p><?= e($n['summary']) ?></p><?php endif; ?>
              <?php if ($n['attachment']): ?><p class="note__has"><?= icon('file') ?> PDF attached</p><?php endif; ?>
            </article>
          </li>
<?php endforeach; ?>
        </ul>
<?php else: ?>
        <div class="empty-board">
          <?= doodle('star', 'empty-board__star') ?>
          <p>No notices here right now. <a href="<?= e($qs(['view' => 'archive', 'cat' => null, 'month' => null])) ?>">Browse the archive</a>.</p>
        </div>
<?php endif; ?>
      </div>

      <aside class="board-side">
        <section class="official-links" id="official-links" aria-labelledby="official-title">
          <h2 id="official-title" class="disclosure__sub">Official CBSE links</h2>
          <p class="small muted">Always check circulars on the official websites.</p>
          <ul role="list">
            <li><a href="https://www.cbse.gov.in/" rel="noopener"><?= icon('external') ?>cbse.gov.in</a></li>
            <li><a href="https://cbseacademic.nic.in/" rel="noopener"><?= icon('external') ?>cbseacademic.nic.in</a></li>
          </ul>
        </section>
        <section class="official-links" aria-labelledby="follow-title">
          <h2 id="follow-title" class="disclosure__sub">Get updates</h2>
          <p class="small">Notices are also shared on Instagram.</p>
          <ul role="list">
<?php foreach (cfg('instagram') as $ig): ?>
            <li><a href="<?= e($ig['url']) ?>" rel="noopener"><?= icon('instagram') ?>@<?= e($ig['handle']) ?></a></li>
<?php endforeach; ?>
          </ul>
        </section>
<?php if (content_is_sample()): ?>
        <p class="sample-note">These are sample notices. Real notices appear once the school's Sanity project is connected.</p>
<?php endif; ?>
      </aside>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
