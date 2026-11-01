<?php
require __DIR__ . '/inc/bootstrap.php';

$settings = site_settings();
$session = session_label();
$banner = $settings['admissionBannerText'] !== '' ? $settings['admissionBannerText'] : 'Admissions open for ' . $session;

$page = [
    'full_title' => 'Veer New Look Central School, Halol | CBSE School, Nursery to Class 12',
    'description' => 'Veer New Look Central School, Halol: a CBSE-affiliated, English-medium, co-educational school from Nursery to Class 12, on Godhra Road since 2005. Enquire for admission.',
    'path' => '/',
    'jsonld' => [school_schema()],
    'scripts' => [/* 'reels.js' */],   // reels section commented out for now (see section 7 below)
    'body_class' => 'page-home',
];
require ROOT . '/partials/head.php';

$ticker = $settings['tickerEnabled'] ? board_notices(null, 3) : [];
$previewNotes = array_values(array_filter([
    board_notices('cbse', 1)[0] ?? null,
    board_notices('school', 1)[0] ?? null,
    board_notices('newsletter', 1)[0] ?? null,
]));
// $reels = array_slice(reels_data()['items'], 0, 6);   // reels section commented out for now
?>
<main id="main">

  <!-- 1. Hero -->
  <section class="hero" aria-labelledby="hero-title">
    <div class="container hero__inner">
      <div class="hero__text">
        <p class="hero__banner"><span class="tag tag--new">Open</span> <?= e($banner) ?></p>
        <h1 id="hero-title">
          <span class="line">Play,</span>
          <span class="line">Learn &amp;</span>
          <span class="line"><span data-annotate="underline" data-annotate-color="accent-2">Grow Together</span></span>
        </h1>
        <p class="lead hero__lead">An English-medium, co-educational CBSE school in Halol. From Nursery to Class 12, since 2005.</p>
        <div class="hero__cta">
          <a class="btn btn--primary" href="/admissions/#enquire">Enquire for Admission</a>
          <a class="btn btn--secondary" href="/admissions/?visit=1#enquire">Take a Campus Tour</a>
        </div>
      </div>
      <div class="hero__collage" aria-label="Photos from school">
        <?= taped_photo('school building', 'Our campus on Godhra Road', null, 'hero__photo hero__photo--main') ?>
        <?= taped_photo('classroom', 'In class', null, 'hero__photo hero__photo--a') ?>
        <?= taped_photo('play area', 'Play time', null, 'hero__photo hero__photo--b') ?>
        <?= doodle('sun', 'hero__doodle hero__doodle--sun') ?>
        <?= doodle('star', 'hero__doodle hero__doodle--star') ?>
        <?= doodle('abc', 'hero__doodle hero__doodle--abc') ?>
        <?= doodle('arrow', 'hero__doodle hero__doodle--arrow') ?>
      </div>
    </div>
  </section>

<?php if ($ticker): ?>
  <!-- 2. Notice ticker -->
  <div class="ticker" id="ticker" data-strip-label="notice ticker">
    <a class="ticker__label" href="/notices/">Notices</a>
    <div class="strip" data-strip data-strip-controls="#ticker">
      <div class="strip__track">
        <ul class="strip__group" role="list">
<?php foreach ($ticker as $n): ?>
          <li><time datetime="<?= e($n['date']) ?>"><?= e(nice_date($n['date'], 'j M')) ?></time><a href="<?= e($n['url']) ?>"><?= e($n['title']) ?></a><?php if (is_new($n['date'])): ?><span class="tag tag--new">New</span><?php endif; ?></li>
<?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
<?php endif; ?>

  <!-- 3. Keyword marquee -->
  <div class="marquee" id="keywords" data-strip-label="keyword marquee" role="region" aria-label="At a glance">
    <div class="strip" data-strip data-strip-controls="#keywords">
      <div class="strip__track">
        <div class="strip__group"><span>CBSE Affiliated</span><span>Nursery to Class 12</span><span>Science &amp; Commerce</span><span>Smart Classrooms</span></div>
      </div>
    </div>
    <div class="strip" data-strip data-strip-controls="#keywords">
      <div class="strip__track strip__track--reverse">
        <div class="strip__group"><span>Labs</span><span>Transport</span><span>Sports</span><span>Since 2005</span></div>
      </div>
    </div>
  </div>

  <!-- 4. Stages -->
  <section class="section stages" aria-labelledby="stages-title">
    <div class="container">
      <?= section_heading('Our school', ['Pick a', '<span class="hl">stage</span>'], 'stages-title', 'section-head--split') ?>
      <ul class="stages__grid" role="list">
        <li class="card stage stage--playful">
          <?= doodle('abc', 'card__icon') ?>
          <h3>Pre-primary</h3>
          <p>Nursery, LKG and UKG. Learning through play, stories and songs.</p>
          <a class="card__meta" href="/academics/#pre-primary">Nursery to UKG <span class="visually-hidden">: pre-primary details</span>→</a>
        </li>
        <li class="card stage">
          <?= doodle('sum', 'card__icon') ?>
          <h3>Primary</h3>
          <p>Classes 1 to 5. Strong basics in reading, writing and numbers.</p>
          <a class="card__meta" href="/academics/#primary">Classes 1–5 <span class="visually-hidden">: primary details</span>→</a>
        </li>
        <li class="card stage">
          <?= doodle('globe', 'card__icon') ?>
          <h3>Middle</h3>
          <p>Classes 6 to 8. More subjects, projects and lab time.</p>
          <a class="card__meta" href="/academics/#middle">Classes 6–8 <span class="visually-hidden">: middle school details</span>→</a>
        </li>
        <li class="card stage">
          <?= doodle('beaker', 'card__icon') ?>
          <h3>Secondary</h3>
          <p>Classes 9 and 10, leading to the CBSE Class 10 board exams.</p>
          <a class="card__meta" href="/academics/#secondary">Classes 9–10 <span class="visually-hidden">: secondary details</span>→</a>
        </li>
        <li class="card stage">
          <?= doodle('star', 'card__icon') ?>
          <h3>Senior Secondary</h3>
          <p>Classes 11 and 12. Science (PCM, PCB) and Commerce. <span class="placeholder">[STREAMS TO CONFIRM]</span></p>
          <a class="card__meta" href="/academics/#senior-secondary">Classes 11–12 <span class="visually-hidden">: senior secondary details</span>→</a>
        </li>
      </ul>
    </div>
  </section>

  <div class="rail" id="rail-1" data-strip-label="tagline">
    <div class="strip" data-strip data-strip-controls="#rail-1">
      <div class="strip__track">
        <div class="strip__group"><span>Play, Learn &amp; Grow Together!</span><span>Play, Learn &amp; Grow Together!</span><span>Play, Learn &amp; Grow Together!</span></div>
      </div>
    </div>
  </div>

  <!-- 5. About strip -->
  <section class="section about-strip" aria-labelledby="about-title">
    <div class="container">
      <div class="section-head section-head--center">
        <p class="eyebrow">About us</p>
        <h2 id="about-title" class="about-strip__statement"><span class="line">A Halol school where children</span> <span class="line"><span class="hl">play, learn and grow</span> together</span></h2>
        <a class="btn btn--small" href="/about/">Our story</a>
      </div>
      <div class="about-strip__board">
        <div class="facts" role="list" aria-label="Veer New Look at a glance">
          <div class="facts__row" role="listitem"><span class="facts__big">2005</span><span class="facts__label">Founded in Halol</span></div>
          <div class="facts__row" role="listitem"><span class="facts__big">Nursery–12</span><span class="facts__label">Classes</span></div>
          <div class="facts__row" role="listitem"><span class="facts__big">CBSE</span><span class="facts__label">Affiliated board</span></div>
          <div class="facts__row" role="listitem"><span class="facts__big">English</span><span class="facts__label">Medium of teaching</span></div>
          <p class="facts__foot">Co-educational · <?= e(cfg('trust')) ?></p>
        </div>
        <?= taped_photo('students in the library', 'Library time', null, 'about-strip__photo') ?>
      </div>
      <!-- Public-listing figures: shown only once the school confirms them (inc/config.php > stats_confirmed). -->
      <dl class="stats" data-confirmed="<?= cfg('stats_confirmed') ? 'true' : 'false' ?>"<?= cfg('stats_confirmed') ? '' : ' hidden' ?>>
        <div><dt>Students</dt><dd>1,340</dd></div>
        <div><dt>Teachers</dt><dd>59</dd></div>
        <div><dt>Classrooms</dt><dd>46</dd></div>
        <div><dt>Campus</dt><dd>8,130 m²</dd></div>
      </dl>
    </div>
  </section>

  <!-- 6. Notice board preview -->
  <section class="section board-preview" aria-labelledby="notices-title">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow">Notice board</p>
          <h2 id="notices-title"><span class="line">Pinned on</span> <span class="line">the <span class="hl">board</span></span></h2>
        </div>
        <a class="btn btn--secondary btn--small" href="/notices/">All notices</a>
      </div>
<?php if ($previewNotes): ?>
      <ul class="notes" role="list">
<?php foreach ($previewNotes as $n): ?>
        <li class="note-wrap">
          <article class="note paper">
            <div class="note__head">
              <span class="note__cat"><?= e($n['category_label']) ?></span>
              <time class="note__date" datetime="<?= e($n['date']) ?>"><?= e(nice_date($n['date'], $n['category'] === 'newsletter' ? 'F Y' : 'j M Y')) ?></time>
              <?php if (is_new($n['date'])): ?><span class="tag tag--new">New</span><?php endif; ?>
            </div>
            <h3><a class="note__title" href="<?= e($n['url']) ?>"><?= e($n['title']) ?></a></h3>
            <?php if ($n['summary']): ?><p><?= e($n['summary']) ?></p><?php endif; ?>
          </article>
        </li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p class="muted">New notices will appear here. <a href="/notices/">Visit the notice board</a>.</p>
<?php endif; ?>
    </div>
  </section>

<?php
/* ------------------------------------------------------------------------
   7. Live from Instagram: COMMENTED OUT FOR NOW (client request).
   To bring it back: remove this comment wrapper, restore 'reels.js' in
   $page['scripts'] and the $reels line at the top of this file.
------------------------------------------------------------------------
  <!-- 7. Live from Instagram -->
  <section class="section reels-section" aria-labelledby="reels-title">
    <div class="container">
      <div class="section-head section-head--split">
        <div>
          <p class="eyebrow">Live from Instagram</p>
          <h2 id="reels-title"><span class="line">School life,</span> <span class="line"><span class="hl">in reels</span></span></h2>
        </div>
        <div class="follow-btns">
<?php foreach (cfg('instagram') as $ig): ?>
          <a class="btn btn--small" href="<?= e($ig['url']) ?>" rel="noopener"><?= icon('instagram') ?>@<?= e($ig['handle']) ?></a>
<?php endforeach; ?>
        </div>
      </div>
      <div class="reels-wrap" data-reels data-source="<?= e(cfg('reels_source')) ?>" data-behold="<?= e(cfg('behold_url')) ?>" data-limit="6">
        <ul class="reels" role="list" aria-label="Latest reels">
<?php foreach ($reels as $r): ?>
          <?= render_reel($r) ?>
<?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

------------------------------------------------------------------------ */
?>
  <!-- 8. Admission in 3 steps -->
  <section class="section steps" aria-labelledby="steps-title">
    <div class="container steps__grid">
      <div class="steps__intro">
        <p class="eyebrow">Admissions</p>
        <h2 id="steps-title"><span class="line">Admission</span> <span class="line">in <span data-annotate="circle" data-annotate-color="accent-4">3 steps</span></span></h2>
        <p class="lead">Simple and friendly. Our office will guide you at every step.</p>
        <?= taped_photo('school reception', 'Our office will help you', null, 'steps__photo') ?>
      </div>
      <?php require ROOT . '/partials/steps.php'; ?>
    </div>
  </section>

  <!-- 9. Why Veer New Look -->
  <section class="section why" aria-labelledby="why-title">
    <div class="container">
      <?= section_heading('Why Veer New Look', ['Four reasons', 'parents <span class="hl">choose us</span>'], 'why-title', 'section-head--center') ?>
      <?php require ROOT . '/partials/why.php'; ?>
    </div>
  </section>

  <!-- 10. Facilities -->
  <section class="section facilities-strip" aria-labelledby="facilities-title">
    <div class="container facilities-strip__grid">
      <?= taped_photo('computer lab', 'One of our two computer labs', null, 'facilities-strip__photo') ?>
      <div>
        <p class="eyebrow">Facilities</p>
        <h2 id="facilities-title"><span class="line">Room to learn</span> <span class="line">and <span class="hl">play</span></span></h2>
        <p class="lead">Air-conditioned classrooms, well-stocked rooms for every subject and space for little ones to play.</p>
        <ul class="pills" role="list">
          <li class="pill">Library</li><li class="pill">AV Room</li><li class="pill">Computer Labs</li>
          <li class="pill">Science Labs</li><li class="pill">Activity Room</li><li class="pill">Play Area</li>
        </ul>
        <a class="btn btn--secondary" href="/facilities/">See all facilities</a>
      </div>
    </div>
  </section>

  <!-- 11. FAQ -->
  <section class="section faq" aria-labelledby="faq-title">
    <div class="container faq__inner">
      <?= section_heading('Questions', ['Admission', '<span class="hl">questions</span>'], 'faq-title', 'section-head--center') ?>
      <?php require ROOT . '/partials/faq.php'; ?>
    </div>
  </section>

  <div class="rail" id="rail-2" data-strip-label="tagline">
    <div class="strip" data-strip data-strip-controls="#rail-2">
      <div class="strip__track strip__track--reverse">
        <div class="strip__group"><span>Play, Learn &amp; Grow Together!</span><span>Play, Learn &amp; Grow Together!</span><span>Play, Learn &amp; Grow Together!</span></div>
      </div>
    </div>
  </div>

  <!-- 12. Closing CTA + gallery strip -->
  <section class="section cta" aria-labelledby="cta-title">
    <div class="container">
      <div class="cta__board">
        <?= doodle('star', 'cta__doodle cta__doodle--1') ?>
        <?= doodle('sum', 'cta__doodle cta__doodle--2') ?>
        <?= doodle('sun', 'cta__doodle cta__doodle--3') ?>
        <p class="eyebrow">Admissions</p>
        <h2 id="cta-title"><span class="line">Admissions open</span> <span class="line">for <span class="hl"><?= e($session) ?></span></span></h2>
        <p class="lead">Nursery to Class 12. Visit us, meet our teachers and see the campus.</p>
        <div class="hero__cta">
          <a class="btn btn--primary" href="/admissions/#enquire">Enquire for Admission</a>
          <a class="btn btn--secondary" href="tel:<?= e(cfg('phones')[0]['tel']) ?>">Call <?= e(cfg('phones')[0]['label']) ?></a>
        </div>
      </div>
      <a class="gallery-strip" href="/gallery/"><span class="visually-hidden">See the gallery: </span>
        <?= taped_photo('annual day') ?>
        <?= taped_photo('sports day') ?>
        <?= taped_photo('science exhibition') ?>
        <?= taped_photo('art and craft') ?>
        <?= taped_photo('festival celebration') ?>
        <?= taped_photo('library') ?>
      </a>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
