<?php
require __DIR__ . '/../inc/bootstrap.php';
$crumbs = [['Home', '/'], ['Gallery', '/gallery/']];
$albums = albums();
$albumSlug = isset($_GET['album']) ? preg_replace('/[^a-z0-9-]/', '', (string) $_GET['album']) : '';
$album = null;
foreach ($albums as $a) {
    if (($a['slug'] ?? '') === $albumSlug) {
        $album = $a;
    }
}
if ($album) {
    $crumbs[] = [$album['name'], '/gallery/?album=' . $album['slug']];
}
$page = [
    'title' => $album ? $album['name'] . ' | Gallery' : 'Gallery',
    'description' => 'Photos, videos and Instagram reels from school life at Veer New Look Central School, Halol.',
    'path' => $album ? '/gallery/?album=' . $album['slug'] : '/gallery/',
    'crumbs' => $crumbs,
    'scripts' => [/* 'reels.js', */ 'gallery.js'],   // reels wall commented out for now
    'body_class' => 'page-gallery',
];
require ROOT . '/partials/head.php';

function youtube_id(string $url): ?string
{
    if (preg_match('#(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})#', $url, $m)) {
        return $m[1];
    }
    return null;
}
$videos = [];
foreach ($albums as $a) {
    foreach ($a['youtubeUrls'] ?? [] as $u) {
        if ($id = youtube_id((string) $u)) {
            $videos[] = ['id' => $id, 'title' => $a['name']];
        }
    }
}
// $reels = array_slice(reels_data()['items'], 0, 12);   // reels wall commented out for now
?>
<main id="main">
<?php if ($album): ?>
  <?= page_hero('Album', [e($album['name'])], e(nice_date($album['date'] ?? '', 'j F Y')), $crumbs, 'star') ?>
  <section class="section-tight" aria-label="Photos">
    <div class="container">
<?php if (!empty($album['photos'])): ?>
      <ul class="album-photos" role="list">
<?php foreach ($album['photos'] as $i => $p): ?>
        <li><a href="<?= e($p['url']) ?>"><figure class="photo"><img src="<?= e($p['url']) ?>?w=600&amp;h=600&amp;fit=crop&amp;auto=format" alt="<?= e($p['alt'] ?: $album['name'] . ', photo ' . ($i + 1)) ?>" width="600" height="600" loading="lazy" decoding="async"></figure></a></li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p><span class="placeholder">[PHOTOS: staff add up to 40 photos to this album in Sanity Studio]</span></p>
<?php endif; ?>
      <p><a class="btn btn--small" href="/gallery/">← All albums</a></p>
    </div>
  </section>
<?php else: ?>
  <?= page_hero('Gallery', ['Moments from', '<span class="hl">school life</span>'],
      'Events, celebrations and everyday learning. Photos are added by the school after each event.', $crumbs, 'star') ?>

  <section class="section-tight" aria-labelledby="albums-title">
    <div class="container">
      <?= section_heading('Albums', ['Event', '<span class="hl">albums</span>'], 'albums-title') ?>
<?php if ($albums): ?>
      <ul class="albums" role="list">
<?php foreach ($albums as $a): ?>
        <li class="album">
          <a href="/gallery/?album=<?= e($a['slug']) ?>" class="album__link">
            <?php if (!empty($a['cover'])): ?>
              <figure class="photo"><img src="<?= e($a['cover']) ?>?w=640&amp;h=480&amp;fit=crop&amp;auto=format" alt="" width="640" height="480" loading="lazy" decoding="async"></figure>
            <?php else: ?>
              <?= taped_photo('album cover') ?>
            <?php endif; ?>
            <span class="album__name"><?= e($a['name']) ?></span>
          </a>
          <span class="album__meta"><time datetime="<?= e($a['date'] ?? '') ?>"><?= e(nice_date($a['date'] ?? '', 'F Y')) ?></time> · <?= count($a['photos'] ?? []) ?> photos</span>
        </li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p class="muted">Albums will appear here after the next school event.</p>
<?php endif; ?>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="videos-title">
    <div class="container">
      <?= section_heading('Videos', ['Watch', '<span class="hl">videos</span>'], 'videos-title') ?>
<?php if ($videos): ?>
      <ul class="videos" role="list">
<?php foreach ($videos as $v): ?>
        <li>
          <button type="button" class="yt-facade" data-yt="<?= e($v['id']) ?>" data-title="<?= e($v['title']) ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" width="480" height="360" loading="lazy" decoding="async">
            <span class="yt-facade__play"><?= icon('play') ?></span>
            <span class="yt-facade__label">Play video: <?= e($v['title']) ?></span>
          </button>
        </li>
<?php endforeach; ?>
      </ul>
      <p class="muted small">Videos load from YouTube only when you press play.</p>
<?php else: ?>
      <p><span class="placeholder">[VIDEOS: staff paste YouTube links into an album in Sanity Studio]</span></p>
<?php endif; ?>
    </div>
  </section>

<?php
/* ------------------------------------------------------------------------
   Instagram reels wall: COMMENTED OUT FOR NOW (client request).
   To bring it back: remove this comment wrapper, restore 'reels.js' in
   $page['scripts'] and the $reels line near the top of this file.
------------------------------------------------------------------------
  <section class="section" aria-labelledby="wall-title">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow">Live from Instagram</p>
          <h2 id="wall-title"><span class="line">Reels</span> <span class="line"><span class="hl">wall</span></span></h2>
        </div>
        <div class="filter" role="group" aria-label="Show reels from">
          <button type="button" class="filter__btn" data-reel-filter="all" aria-controls="reels-wall" aria-pressed="true">All</button>
<?php foreach (cfg('instagram') as $ig): ?>
          <button type="button" class="filter__btn" data-reel-filter="<?= e($ig['handle']) ?>" aria-controls="reels-wall" aria-pressed="false">@<?= e($ig['handle']) ?></button>
<?php endforeach; ?>
        </div>
      </div>
      <div class="reels-wrap reels-wrap--wall" id="reels-wall" data-reels data-source="<?= e(cfg('reels_source')) ?>" data-behold="<?= e(cfg('behold_url')) ?>" data-limit="12">
        <ul class="reels reels--wall" role="list" aria-label="Instagram reels">
<?php foreach ($reels as $r): ?>
          <?= render_reel($r) ?>
<?php endforeach; ?>
        </ul>
      </div>
      <div class="follow-btns">
<?php foreach (cfg('instagram') as $ig): ?>
        <a class="btn btn--small" href="<?= e($ig['url']) ?>" rel="noopener"><?= icon('instagram') ?>Follow @<?= e($ig['handle']) ?></a>
<?php endforeach; ?>
      </div>
    </div>
  </section>
------------------------------------------------------------------------ */
?>
<?php endif; ?>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
