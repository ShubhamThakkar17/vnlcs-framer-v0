<?php
require __DIR__ . '/../inc/bootstrap.php';
$crumbs = [['Home', '/'], ['Facilities', '/facilities/']];
$page = [
    'title' => 'Facilities',
    'description' => 'Facilities at Veer New Look Central School, Halol: library, AV room, air-conditioned classrooms, two computer labs, science, maths and fine arts labs, activity room and play area.',
    'path' => '/facilities/',
    'crumbs' => $crumbs,
    'body_class' => 'page-facilities',
];
require ROOT . '/partials/head.php';

$facilities = [
    ['Library', 'Books for every age, and a quiet place to read.', 'library', 'abc'],
    ['AV room', 'Audio-visual lessons, talks and screenings.', 'AV room', 'sun'],
    ['Air-conditioned classrooms', 'Comfortable classrooms through Gujarat summers.', 'classroom', 'star'],
    ['Two computer labs', 'Computer lessons for every class.', 'computer lab', 'sum'],
    ['Physics lab', 'Experiments that make ideas real.', 'Physics lab', 'globe'],
    ['Chemistry lab', 'Safe, supervised practical work.', 'Chemistry lab', 'beaker'],
    ['Biology lab', 'Specimens, models and microscopes.', 'Biology lab', 'beaker'],
    ['Maths lab', 'Shapes, patterns and hands-on maths.', 'Maths lab', 'sum'],
    ['Fine Arts lab', 'Drawing, painting and craft.', 'Fine Arts lab', 'star'],
    ['Activity room', 'Music, dance and group activities.', 'activity room', 'sun'],
    ['Play area', 'With a splash pool and sandpit for the little ones.', 'play area with splash pool and sandpit', 'bat'],
];
// Shown only once the school confirms them.
$unconfirmed = [
    ['Hostel', '[HOSTEL / BOARDING DETAILS]'],
    ['Swimming pool', '[SWIMMING POOL DETAILS]'],
    ['Transport', '[TRANSPORT ROUTES AND CONTACT]'],
];
?>
<main id="main">
  <?= page_hero('Facilities', ['Room to learn,', '<span class="hl">room to play</span>'],
      'Classrooms, labs and play spaces for every stage, from Nursery to Class 12.',
      $crumbs, 'beaker') ?>

  <section class="section-tight" aria-labelledby="fac-title">
    <div class="container">
      <h2 id="fac-title" class="visually-hidden">Our facilities</h2>
      <ul class="facility-grid" role="list">
<?php foreach ($facilities as $i => [$name, $text, $photo, $d]): ?>
        <li class="facility">
          <?= taped_photo($photo, '', null, 'facility__photo') ?>
          <div class="facility__text">
            <h3><?= doodle($d, 'facility__icon') ?><?= e($name) ?></h3>
            <p><?= e($text) ?></p>
          </div>
        </li>
<?php endforeach; ?>
<?php foreach ($unconfirmed as [$name, $text]): ?>
        <li class="facility" data-confirmed="false" hidden>
          <div class="facility__text"><h3><?= e($name) ?></h3><p><?= e($text) ?></p></div>
        </li>
<?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="section" aria-labelledby="visit-title">
    <div class="container">
      <div class="cta__board">
        <p class="eyebrow">See it for yourself</p>
        <h2 id="visit-title"><span class="line">Take a</span> <span class="line"><span class="hl">campus tour</span></span></h2>
        <p class="lead">Walk through the classrooms, labs and play area with your child.</p>
        <div class="hero__cta">
          <a class="btn btn--primary" href="/admissions/?visit=1#enquire">Book a visit</a>
          <a class="btn btn--secondary" href="tel:<?= e(cfg('phones')[0]['tel']) ?>">Call <?= e(cfg('phones')[0]['label']) ?></a>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
