<?php
require __DIR__ . '/../inc/bootstrap.php';
$crumbs = [['Home', '/'], ['Academics', '/academics/']];
$page = [
    'title' => 'Academics',
    'description' => 'Academics at Veer New Look Central School, Halol: CBSE curriculum from Nursery to Class 12, with Science (PCM, PCB) and Commerce in Classes 11 and 12.',
    'path' => '/academics/',
    'crumbs' => $crumbs,
    'body_class' => 'page-academics',
];
require ROOT . '/partials/head.php';
$calendar = is_file(ROOT . '/docs/academic-calendar.pdf') ? '/docs/academic-calendar.pdf' : null;
?>
<main id="main">
  <?= page_hero('Academics', ['Learning at', '<span class="hl">every stage</span>'],
      'We follow the CBSE curriculum, from playful first steps in Nursery to board exams in Class 10 and Class 12.',
      $crumbs, 'globe') ?>

  <nav class="jump" aria-label="On this page">
    <div class="container">
      <ul role="list">
        <li><a href="#pre-primary">Pre-primary</a></li>
        <li><a href="#primary">Primary</a></li>
        <li><a href="#middle">Middle</a></li>
        <li><a href="#secondary">Secondary</a></li>
        <li><a href="#senior-secondary">Senior Secondary</a></li>
        <li><a href="#curriculum">Curriculum</a></li>
        <li><a href="#assessment">Assessment</a></li>
        <li><a href="#calendar">Calendar</a></li>
      </ul>
    </div>
  </nav>

  <!-- Pre-primary: the playful stage -->
  <section class="section-tight stage-block stage-block--playful" aria-labelledby="pre-primary" id="pre-primary-section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">Nursery, LKG, UKG</p>
        <h2 id="pre-primary"><span class="line">Pre-primary:</span> <span class="line">learning <span data-annotate="circle" data-annotate-color="accent-2">through play</span></span></h2>
        <p>Little ones learn letters, numbers, colours and shapes through songs, stories, games and hands-on activities.</p>
      </div>
      <ul class="programmes" role="list">
        <li class="programme">
          <span class="programme__num" aria-hidden="true">01</span>
          <div class="programme__body">
            <h3>Nursery</h3>
            <p>First days at school: settling in, making friends, rhymes, colours and play.</p>
            <span class="pill pill--age">Age: [TO CONFIRM]</span>
          </div>
          <?= taped_photo('Nursery class', '', null, 'programme__photo') ?>
        </li>
        <li class="programme">
          <span class="programme__num" aria-hidden="true">02</span>
          <div class="programme__body">
            <h3>LKG</h3>
            <p>Phonics, early numbers, drawing and growing confidence.</p>
            <span class="pill pill--age">Age: [TO CONFIRM]</span>
          </div>
          <?= taped_photo('LKG class', '', null, 'programme__photo') ?>
        </li>
        <li class="programme">
          <span class="programme__num" aria-hidden="true">03</span>
          <div class="programme__body">
            <h3>UKG</h3>
            <p>Reading, writing and number readiness for Class 1.</p>
            <span class="pill pill--age">Age: [TO CONFIRM]</span>
          </div>
          <?= taped_photo('UKG class', '', null, 'programme__photo') ?>
        </li>
      </ul>

      <h3 class="moments__title">Little moments that shine</h3>
      <ul class="moments" role="list">
        <li class="moment"><?= doodle('star', 'moment__icon') ?><h4>Play-based learning</h4><p>Games and activities that build curiosity.</p></li>
        <li class="moment"><?= doodle('abc', 'moment__icon') ?><h4>Phonics</h4><p>Sounds first, so reading comes naturally.</p></li>
        <li class="moment"><?= doodle('sun', 'moment__icon') ?><h4>Splash pool and sandpit</h4><p>Safe outdoor play in our play area.</p></li>
        <li class="moment"><?= doodle('arrow', 'moment__icon') ?><h4>Art and craft</h4><p>Colours, clay and paper, made by small hands.</p></li>
      </ul>
    </div>
  </section>

  <section class="section-tight stage-block" aria-labelledby="primary" id="primary-section">
    <div class="container split">
      <div>
        <p class="eyebrow">Classes 1 to 5</p>
        <h2 id="primary"><span class="line">Primary</span></h2>
        <p class="lead">Strong basics in reading, writing and numbers, with environmental studies, languages, art and physical education.</p>
        <p><span class="placeholder">[SUBJECTS AND LANGUAGES OFFERED IN CLASSES 1 TO 5]</span></p>
      </div>
      <?= taped_photo('primary classroom', 'A primary class') ?>
    </div>
  </section>

  <section class="section-tight stage-block" aria-labelledby="middle" id="middle-section">
    <div class="container split split--flip">
      <div>
        <p class="eyebrow">Classes 6 to 8</p>
        <h2 id="middle"><span class="line">Middle</span></h2>
        <p class="lead">More subjects, more projects and regular time in the labs and computer rooms.</p>
        <p><span class="placeholder">[SUBJECTS AND LANGUAGES OFFERED IN CLASSES 6 TO 8]</span></p>
      </div>
      <?= taped_photo('students in a lab', 'Lab time') ?>
    </div>
  </section>

  <section class="section-tight stage-block stage-block--calm" aria-labelledby="secondary" id="secondary-section">
    <div class="container split">
      <div>
        <p class="eyebrow">Classes 9 and 10</p>
        <h2 id="secondary"><span class="line">Secondary</span></h2>
        <p class="lead">A focused two years leading to the CBSE Class 10 board examinations.</p>
        <p><span class="placeholder">[SUBJECTS OFFERED IN CLASSES 9 AND 10]</span></p>
      </div>
      <?= taped_photo('Class 10 students', 'Secondary classes') ?>
    </div>
  </section>

  <section class="section-tight stage-block stage-block--calm" aria-labelledby="senior-secondary" id="senior-secondary-section">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">Classes 11 and 12</p>
        <h2 id="senior-secondary"><span class="line">Senior</span> <span class="line">Secondary</span></h2>
        <p>Students choose a stream for the CBSE Class 12 board examinations. <span class="placeholder">[STREAMS AND SUBJECT COMBINATIONS FOR THE NEXT SESSION TO CONFIRM]</span></p>
      </div>
      <ul class="streams" role="list">
        <li class="card"><h3>Science (PCM)</h3><p>Physics, Chemistry and Mathematics.</p><p class="small"><span class="placeholder">[OPTIONAL SUBJECTS]</span></p></li>
        <li class="card"><h3>Science (PCB)</h3><p>Physics, Chemistry and Biology.</p><p class="small"><span class="placeholder">[OPTIONAL SUBJECTS]</span></p></li>
        <li class="card"><h3>Commerce</h3><p>Accountancy, Business Studies and Economics.</p><p class="small"><span class="placeholder">[OPTIONAL SUBJECTS]</span></p></li>
      </ul>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="curriculum" id="curriculum-section">
    <div class="container split">
      <div>
        <p class="eyebrow">Curriculum</p>
        <h2 id="curriculum"><span class="line">The CBSE</span> <span class="line">curriculum</span></h2>
        <p class="lead">Veer New Look follows the curriculum set by the Central Board of Secondary Education.</p>
        <p><span class="placeholder">[TEXTBOOKS AND SYLLABUS NOTES FROM THE SCHOOL]</span></p>
        <p>The latest CBSE curriculum documents are published on the <a href="https://cbseacademic.nic.in/" rel="noopener">CBSE Academic website</a>.</p>
      </div>
      <div id="assessment-section">
        <p class="eyebrow">Assessment</p>
        <h2 id="assessment"><span class="line">Tests and</span> <span class="line">exams</span></h2>
        <p><span class="placeholder">[ASSESSMENT PATTERN: unit tests, half-yearly and annual exams, as followed by the school]</span></p>
        <p>Exam timetables and results are posted on the <a href="/notices/">Notice Board</a>.</p>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="calendar" id="calendar-section">
    <div class="container">
      <div class="calendar-note">
        <?= icon('calendar', 'calendar-note__icon') ?>
        <div>
          <h2 id="calendar" class="h3-like">Academic calendar</h2>
<?php if ($calendar): ?>
          <p>Term dates, holidays and exam weeks for the year.</p>
          <a class="btn btn--primary btn--small" href="<?= e($calendar) ?>"><?= icon('file') ?>Download the calendar (PDF)</a>
<?php else: ?>
          <p><span class="placeholder">[ACADEMIC CALENDAR PDF: upload as /docs/academic-calendar.pdf]</span></p>
<?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
