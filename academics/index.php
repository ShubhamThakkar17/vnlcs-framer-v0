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
      <div class="split split--top stage-intro">
      <div class="section-head">
        <p class="eyebrow">Nursery, LKG, UKG</p>
        <h2 id="pre-primary"><span class="line">Pre-primary:</span> <span class="line">learning <span data-annotate="circle" data-annotate-color="accent-2">through play</span></span></h2>
        <p>Little ones learn letters, numbers, colours and shapes through songs, stories, games and hands-on activities.</p>
      </div>
      <?= taped_photo('', 'Lunch time with friends', photo('life/pre-primary-lunch', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
      </div>
      <ul class="programmes" role="list">
        <li class="programme">
          <span class="programme__num" aria-hidden="true">01</span>
          <div class="programme__body">
            <h3>Nursery</h3>
            <p>First days at school: settling in, making friends, rhymes, colours and play.</p>
            <span class="pill pill--age">Age: [TO CONFIRM]</span>
          </div>
        </li>
        <li class="programme">
          <span class="programme__num" aria-hidden="true">02</span>
          <div class="programme__body">
            <h3>LKG</h3>
            <p>Phonics, early numbers, drawing and growing confidence.</p>
            <span class="pill pill--age">Age: [TO CONFIRM]</span>
          </div>
        </li>
        <li class="programme">
          <span class="programme__num" aria-hidden="true">03</span>
          <div class="programme__body">
            <h3>UKG</h3>
            <p>Reading, writing and number readiness for Class 1.</p>
            <span class="pill pill--age">Age: [TO CONFIRM]</span>
          </div>
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
      <?= taped_photo('', 'Raksha Bandhan in primary', photo('events/rakhi-primary', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
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
      <?= taped_photo('', 'Our science lab', photo('campus/science-lab', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
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
      <?= taped_photo('', 'Business Junoon: running our own stalls', photo('events/junoon-presenting', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
    </div>
  </section>

  <section class="section-tight stage-block stage-block--calm" aria-labelledby="senior-secondary" id="senior-secondary-section">
    <div class="container">
      <div class="split split--top stage-intro">
      <div class="section-head">
        <p class="eyebrow">Classes 11 and 12</p>
        <h2 id="senior-secondary"><span class="line">Senior</span> <span class="line">Secondary</span></h2>
        <p>Students choose a stream for the CBSE Class 12 board examinations. <span class="placeholder">[SUBJECT COMBINATIONS FOR 2027-28 TO CONFIRM]</span></p>
      </div>
      <?= taped_photo('', 'Senior students at break', photo('life/senior-students', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
      </div>
      <ul class="streams" role="list">
        <li class="card"><h3>Science (PCM)</h3><p>Physics, Chemistry and Mathematics.</p><p class="small"><span class="placeholder">[OPTIONAL SUBJECTS]</span></p></li>
        <li class="card"><h3>Science (PCB)</h3><p>Physics, Chemistry and Biology.</p><p class="small"><span class="placeholder">[OPTIONAL SUBJECTS]</span></p></li>
        <li class="card"><h3>Commerce</h3><p>Accountancy, Business Studies and Economics.</p><p class="small"><span class="placeholder">[OPTIONAL SUBJECTS]</span></p></li>
      </ul>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="curriculum" id="curriculum-section">
    <div class="container split split--top">
      <div>
        <p class="eyebrow">Curriculum</p>
        <h2 id="curriculum"><span class="line">The CBSE</span> <span class="line">curriculum</span></h2>
        <p class="lead">Veer New Look follows the curriculum set by the Central Board of Secondary Education, from Nursery to Class 12.</p>
        <p>CBSE publishes the syllabus for every subject afresh each session. Our classes follow the current one.</p>
        <h3 class="curriculum__sub">New this year (2026-27)</h3>
        <ul class="checklist" role="list">
          <li>Class 9 moves to CBSE's new scheme of studies, based on the National Curriculum Framework for School Education (NCF-SE 2023). <a href="/notices/cbse-acad-70-2026-scheme-class-9">Read the CBSE circular</a>.</li>
          <li>Class 9 students study three languages, at least two of them Indian languages.</li>
          <li>Class 10 continues with the previous scheme this year.</li>
          <li>In most board subjects, the theory paper carries 80 marks and internal assessment 20. Class 12 subjects with practicals, such as Physics, Chemistry, Biology and Computer Science, carry 70 and 30. <a href="/notices/cbse-marks-bifurcation-2026-27">See the subject-wise marks</a>.</li>
        </ul>
        <p><span class="placeholder">[BOOK LIST FOR 2026-27 FROM THE SCHOOL]</span></p>
        <p>The full CBSE curriculum documents are published on the <a href="https://cbseacademic.nic.in/" rel="noopener">CBSE Academic website</a>.</p>
      </div>
      <div id="assessment-section">
        <p class="eyebrow">Assessment</p>
        <h2 id="assessment"><span class="line">Tests and</span> <span class="line">exams</span></h2>
        <p>Children are assessed through the year, not just at the end. This is how the school's 2025-26 calendar spread the tests.</p>
        <div class="table-wrap" tabindex="0" role="region" aria-label="Tests and exams by stage (table)"><table class="data-table exam-plan">
          <caption class="visually-hidden">Tests and exams by stage</caption>
          <thead><tr><th scope="col">Classes</th><th scope="col">Through the year</th><th scope="col">End of the year</th></tr></thead>
          <tbody>
            <tr><th scope="row">Nursery to UKG</th><td data-label="Through the year">First assessment (October)</td><td data-label="End of the year">Annual exams and second assessment (late February to March)</td></tr>
            <tr><th scope="row">1 to 8</th><td data-label="Through the year">Periodic Test 1 (August), half-yearly exams (October), Periodic Test 2 (January)</td><td data-label="End of the year">Annual exams (late February to mid-March)</td></tr>
            <tr><th scope="row">9 and 11</th><td data-label="Through the year">Periodic Test 1 (August), half-yearly exams (October), Periodic Test 2 (January)</td><td data-label="End of the year">Annual exams (from mid-February)</td></tr>
            <tr><th scope="row">10 and 12</th><td data-label="Through the year">Periodic Test 1 (August), half-yearly exams (October), two pre-board exams (December and January)</td><td data-label="End of the year">CBSE board exams (from 16 February 2027)</td></tr>
          </tbody>
        </table></div>
        <p class="small muted">Exact dates for 2026-27, exam timetables and results are posted on the <a href="/notices/">Notice Board</a>.</p>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="calendar" id="calendar-section">
    <div class="container">
      <div class="calendar-note">
        <?= icon('calendar', 'calendar-note__icon') ?>
        <div>
          <h2 id="calendar" class="h3-like">Academic calendar</h2>
          <p>Key CBSE dates for 2026-27:</p>
          <ul class="key-dates" role="list">
            <li><time datetime="2027-01-01">From 1 January 2027</time>: practical exams, project work and internal assessment for Classes 10 and 12. <a href="/notices/cbse-practicals-winter-bound-2026-27">CBSE circular</a></li>
            <li><time datetime="2027-02-16">16 February 2027</time>: CBSE board exams begin. Class 10 ends on 9 March, Class 12 on 9 April. <a href="/notices/cbse-date-sheet-2027-class-10-12">Date sheet</a></li>
          </ul>
<?php if ($calendar): ?>
          <a class="btn btn--primary btn--small" href="<?= e($calendar) ?>"><?= icon('file') ?>Download the 2026-27 school calendar (PDF)</a>
<?php else: ?>
          <p><span class="placeholder">[2026-27 SCHOOL CALENDAR PDF: upload as /docs/academic-calendar.pdf]</span></p>
<?php if (is_file(ROOT . '/docs/academic-calendar-2025-26.pdf')): ?>
          <p class="small"><a class="doc-link" href="/docs/academic-calendar-2025-26.pdf"><?= icon('file') ?>Last year's school calendar (2025-26, PDF)</a></p>
<?php endif; ?>
<?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
