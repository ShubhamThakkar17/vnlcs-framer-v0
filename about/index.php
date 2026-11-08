<?php
require __DIR__ . '/../inc/bootstrap.php';
$crumbs = [['Home', '/'], ['About', '/about/']];
$page = [
    'title' => 'About the school',
    'description' => 'About Veer New Look Central School, Halol: our trust, the Director\'s and Principal\'s messages, vision and mission, history since 2005 and our teachers.',
    'path' => '/about/',
    'crumbs' => $crumbs,
    'body_class' => 'page-about',
];
require ROOT . '/partials/head.php';
?>
<main id="main">
  <?= page_hero('About us', ['Our school,', '<span class="hl">our story</span>'],
      'Veer New Look Central School has been part of Halol since 2005. A CBSE school for girls and boys, from Nursery to Class 12, taught in English.',
      $crumbs, 'sun') ?>

  <section class="section-tight" aria-labelledby="trust-title" id="trust">
    <div class="container split">
      <div>
        <p class="eyebrow">Who runs the school</p>
        <h2 id="trust-title"><span class="line">Our trust</span> <span class="line">and <span class="hl">group</span></span></h2>
        <p class="lead">Veer New Look is run by the <?= e(cfg('trust')) ?> and is part of the <a href="<?= e(cfg('group.url')) ?>" rel="noopener"><?= e(cfg('group.name')) ?></a>.</p>
        <p>The trust is registered as a public trust in Panchmahal district, and its registration is valid for life. <span class="placeholder">[A FEW LINES ABOUT THE TRUST: when it was set up and what it stands for]</span></p>
      </div>
      <div class="facts">
        <div class="facts__row"><span class="facts__big">2005</span><span class="facts__label">Founded in Halol</span></div>
        <div class="facts__row"><span class="facts__big">CBSE</span><span class="facts__label">Affiliated board</span></div>
        <div class="facts__row"><span class="facts__big">Nursery–12</span><span class="facts__label">Classes</span></div>
        <div class="facts__row"><span class="facts__big">English</span><span class="facts__label">Medium of teaching</span></div>
        <p class="facts__foot">Co-educational · Godhra Road, Halol</p>
      </div>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="leaders-title" id="leadership">
    <div class="container">
      <?= section_heading('Leadership', ['The people', 'who <span class="hl">lead us</span>'], 'leaders-title', 'section-head--center') ?>
      <ul class="portraits" role="list">
        <?= portrait_card('Director', '[DIRECTOR\'S NAME]', 'Read the message from the <a href="#director">Director\'s Desk</a>.') ?>
        <?= portrait_card('Principal', '[PRINCIPAL\'S NAME]', 'Read the message from the <a href="#principal">Principal\'s Desk</a>.') ?>
        <?= portrait_card('Trust', e(cfg('trust')), '<span class="placeholder">[TRUSTEE NAMES, IF THE SCHOOL WISHES TO LIST THEM]</span>') ?>
      </ul>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="director-title" id="director">
    <div class="container message">
      <figure class="message__photo"><div class="portrait__frame"><span>[PHOTO: Director]</span></div></figure>
      <div class="message__text">
        <p class="eyebrow">Director's Desk</p>
        <h2 id="director-title"><span class="line">A message from</span> <span class="line">our <span class="hl">Director</span></span></h2>
        <blockquote class="message__quote">
          <p>Dear Parents,</p>
          <p>"Discipline is the bridge between goals and accomplishments."</p>
          <p>As the Director of our school, I assure you that every child here grows up with a sense of care and concern for society and the environment. Our school encourages a wide range of activities, including service to the community through creativity, action and service programmes.</p>
          <p>May every child blossom into a bright star who brings honour to their home, school, community and country.</p>
          <p>On behalf of the management, staff and students, I extend a warm welcome to you and your child.</p>
        </blockquote>
        <p class="message__sign">[DIRECTOR'S NAME], Director</p>
      </div>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="principal-title" id="principal">
    <div class="container message message--flip">
      <figure class="message__photo"><div class="portrait__frame"><span>[PHOTO: Principal]</span></div></figure>
      <div class="message__text">
        <p class="eyebrow">Principal's Desk</p>
        <h2 id="principal-title"><span class="line">A message from</span> <span class="line">our <span class="hl">Principal</span></span></h2>
        <blockquote class="message__quote">
          <p>Dear Parents,</p>
          <p>Schools have shaped the lives of people for centuries. Where school ends, real life begins. In school we are taught a lesson and then given a test. In life we are given a test that teaches us a lesson.</p>
          <p>We work to deepen our students' understanding of the world around them. Children are encouraged to aim high and are guided to do their best in everything. Activities in and out of the classroom make learning enjoyable, bringing together good teaching practice and India's rich heritage.</p>
          <p>A well-rounded education matters for every child. The school encourages students to take part in sports and games, cultural events, performances, educational trips, fairs and festival celebrations.</p>
          <p>We welcome you to join our family and explore a new way of learning.</p>
        </blockquote>
        <p class="message__sign">[PRINCIPAL'S NAME], Principal</p>
      </div>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="vision-title" id="vision">
    <div class="container">
      <?= section_heading('What guides us', ['Vision and', '<span class="hl">mission</span>'], 'vision-title', 'section-head--center') ?>
      <div class="two-notes">
        <div class="note-wrap"><article class="note paper"><p class="note__cat">Our vision</p><p>To be a value-driven learning organisation with excellence as its standard, where tradition and technology come together with the best available research to keep learning and growth going. To create an environment in which teachers grow as individuals and as educators. To encourage learning by enquiry, exploration and experience, at the cutting edge of educational technology.</p></article></div>
        <div class="note-wrap"><article class="note paper"><p class="note__cat">Our mission</p><p>Rooted in tradition, ready for the future. To nurture students who are confident with today's technologies, and to build an institution of excellence where knowledge and learning grow together, all the time.</p></article></div>
      </div>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="history-title" id="history">
    <div class="container">
      <?= section_heading('Since 2005', ['Our', '<span class="hl">journey</span>'], 'history-title') ?>
      <div class="split split--top">
      <ol class="timeline" role="list">
        <li><span class="timeline__year">2005</span><p>Veer New Look Central School opens on Godhra Road, Halol.</p></li>
        <li><span class="timeline__year">2013</span><p>The Gujarat Education Department extends the school's No Objection Certificate for CBSE affiliation.</p></li>
        <li><span class="timeline__year">2022</span><p>CBSE extends the school's affiliation up to Senior Secondary level (Class 12), from 1 April 2022 to 31 March 2027.</p></li>
        <li><span class="timeline__year">Today</span><p>Classes from Nursery to Class 12, with Science and Commerce in Classes 11 and 12.</p></li>
      </ol>
      <?= taped_photo('', 'Our school building', photo('campus/building', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
      </div>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="why-title" id="why">
    <div class="container">
      <?= section_heading('Why Veer New Look', ['Why parents', '<span class="hl">choose us</span>'], 'why-title', 'section-head--center') ?>
      <?php require ROOT . '/partials/why.php'; ?>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="committee-title" id="committee">
    <div class="container">
      <?= section_heading('School Management Committee', ['Our', '<span class="hl">committee</span>'], 'committee-title') ?>
      <div class="table-wrap" tabindex="0" role="region" aria-label="School Management Committee members (table)">
        <table class="data-table">
          <caption class="visually-hidden">School Management Committee members</caption>
          <thead><tr><th scope="col">Name</th><th scope="col">Position on the committee</th></tr></thead>
          <tbody>
            <tr><td>[NAME]</td><td>[POSITION]</td></tr>
            <tr><td>[NAME]</td><td>[POSITION]</td></tr>
            <tr><td>[NAME]</td><td>[POSITION]</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="staff-title" id="staff">
    <div class="container">
      <?= section_heading('Our teachers', ['Staff', '<span class="hl">by role</span>'], 'staff-title') ?>
      <div class="staff-photos">
        <?= taped_photo('', 'Our teachers at Business Junoon 2.0', photo('life/teachers', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
        <?= taped_photo('', 'All smiles at Business Junoon 2.0', photo('life/teachers-2', ['sizes' => '(min-width: 900px) 520px, 90vw'])) ?>
      </div>
      <p class="muted">Teacher names and qualifications will be listed here once the school shares them.</p>
      <div class="table-wrap" tabindex="0" role="region" aria-label="Teaching and support staff by role (table)">
        <table class="data-table">
          <caption class="visually-hidden">Teaching and support staff by role</caption>
          <thead><tr><th scope="col">Role</th><th scope="col">What it means</th><th scope="col">Staff</th></tr></thead>
          <tbody>
            <tr><th scope="row">PGT</th><td>Post Graduate Teachers (Classes 11 and 12)</td><td>[NAMES AND SUBJECTS]</td></tr>
            <tr><th scope="row">TGT</th><td>Trained Graduate Teachers (Classes 6 to 10)</td><td>[NAMES AND SUBJECTS]</td></tr>
            <tr><th scope="row">PRT</th><td>Primary Teachers (Classes 1 to 5)</td><td>[NAMES]</td></tr>
            <tr><th scope="row">NTT</th><td>Nursery Teachers (Nursery to UKG)</td><td>[NAMES]</td></tr>
            <tr><th scope="row">Librarian</th><td>Runs the school library</td><td>[NAME]</td></tr>
            <tr><th scope="row">PTI</th><td>Physical Training Instructor</td><td>[NAME]</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
