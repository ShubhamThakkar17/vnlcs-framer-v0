<?php
require __DIR__ . '/../inc/bootstrap.php';
$crumbs = [['Home', '/'], ['Admissions', '/admissions/']];
$session = session_label();
$page = [
    'title' => 'Admissions and Contact',
    'description' => 'Admissions at Veer New Look Central School, Halol: process, eligibility, documents and the online enquiry form. Call 02676 225955 or visit us on Godhra Road.',
    'path' => '/admissions/',
    'crumbs' => $crumbs,
    'jsonld' => [school_schema()],
    'scripts' => ['form.js'],
    'body_class' => 'page-admissions',
];
require ROOT . '/partials/head.php';

$keys = array_values(array_filter((array) secret('web3forms_keys', [])));
$classes = ['Nursery', 'LKG', 'UKG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'Class 11', 'Class 12'];
$isVisit = isset($_GET['visit']);
$sent = isset($_GET['sent']);
?>
<main id="main">
  <?= page_hero('Admissions & Contact', ['Admissions open', 'for <span class="hl">' . e($session) . '</span>'],
      'Nursery to Class 12. Enquire online, call the office or drop in to see the campus.',
      $crumbs, 'arrow') ?>

  <section class="section-tight" aria-labelledby="process-title" id="process">
    <div class="container steps__grid">
      <div class="steps__intro">
        <p class="eyebrow">How it works</p>
        <h2 id="process-title"><span class="line">Admission</span> <span class="line">in <span class="hl">3 steps</span></span></h2>
        <p class="lead">Our office will guide you at every step.</p>
      </div>
      <?php require ROOT . '/partials/steps.php'; ?>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="eligibility-title" id="eligibility">
    <div class="container split split--top">
      <div>
        <p class="eyebrow">Eligibility</p>
        <h2 id="eligibility-title"><span class="line">Who can</span> <span class="line"><span class="hl">apply</span></span></h2>
        <div class="table-wrap" tabindex="0" role="region" aria-label="Age criteria by class (table)">
          <table class="data-table">
            <caption class="visually-hidden">Age criteria by class</caption>
            <thead><tr><th scope="col">Class</th><th scope="col">Age criteria</th></tr></thead>
            <tbody>
              <tr><td>Nursery</td><td>[TO CONFIRM]</td></tr>
              <tr><td>LKG</td><td>[TO CONFIRM]</td></tr>
              <tr><td>UKG</td><td>[TO CONFIRM]</td></tr>
              <tr><td>Class 1</td><td>[TO CONFIRM]</td></tr>
              <tr><td>Class 2 to Class 10</td><td>Transfer Certificate from the last school, and an admission test (English, Hindi, Maths and Science)</td></tr>
              <tr><td>Class 11</td><td>Based on the Class 10 board result and an interview</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div id="documents">
        <p class="eyebrow">Documents</p>
        <h2><span class="line">What to</span> <span class="line"><span class="hl">bring</span></span></h2>
        <div class="note-wrap"><div class="note paper">
          <p class="note__cat">Documents list</p>
          <ul class="checklist checklist--paper" role="list">
            <li>Birth certificate</li>
            <li>Original Transfer Certificate from the last school</li>
            <li>Original Migration Certificate, where needed</li>
            <li>Mark sheet of the last class</li>
            <li>Two photographs</li>
          </ul>
          <p class="small">From the school's admission form. <span class="placeholder">[TO CONFIRM FOR 2027-28]</span></p>
        </div></div>
      </div>
    </div>
  </section>

  <section class="section-tight enquire" aria-labelledby="enquire-title" id="enquire">
    <div class="container enquire__grid">
      <div class="enquire__intro">
        <p class="eyebrow"><?= $isVisit ? 'Campus tour' : 'Enquiry form' ?></p>
        <h2 id="enquire-title"><span class="line"><?= $isVisit ? 'Book a' : 'Enquire for' ?></span> <span class="line"><span class="hl"><?= $isVisit ? 'campus tour' : 'admission' ?></span></span></h2>
        <p class="lead">Fill in the form and the school office will call you back.</p>
        <p class="muted">Prefer to talk? Call <a href="tel:<?= e(cfg('phones')[0]['tel']) ?>"><?= e(cfg('phones')[0]['label']) ?></a> or <a href="tel:<?= e(cfg('phones')[1]['tel']) ?>"><?= e(cfg('phones')[1]['label']) ?></a>, or <a href="<?= e(whatsapp_chat_url()) ?>" rel="noopener">message us on WhatsApp</a>.</p>
        <?= doodle('arrow', 'enquire__arrow') ?>
      </div>

      <div class="form-board">
<?php if ($sent): ?>
        <div class="form-thanks" role="status" tabindex="-1">
          <?= doodle('star', 'form-thanks__star') ?>
          <h3>Thank you!</h3>
          <p>We have your enquiry. The school office will contact you soon.</p>
        </div>
<?php else: ?>
        <form class="enquiry" id="enquiry-form" method="post" action="https://api.web3forms.com/submit" novalidate
              data-keys="<?= e(json_encode($keys)) ?>" data-fallback-email="<?= e(cfg('emails')[0]) ?>">
          <input type="hidden" name="access_key" value="<?= e($keys[0] ?? '') ?>">
          <input type="hidden" name="subject" value="New admission enquiry from the website">
          <input type="hidden" name="from_name" value="Veer New Look website">
          <input type="hidden" name="redirect" value="<?= e(abs_url('/admissions/?sent=1#enquire')) ?>">
          <!-- Honeypots: humans never see or fill these -->
          <div class="hp" aria-hidden="true">
            <label>Leave this empty <input type="checkbox" name="botcheck" tabindex="-1" autocomplete="off"></label>
            <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          </div>

          <p class="form-note">Fields marked <span aria-hidden="true">*</span><span class="visually-hidden">with an asterisk</span> are required.</p>
          <div class="field">
            <label for="f-parent">Parent's name <span aria-hidden="true">*</span></label>
            <input id="f-parent" name="name" type="text" autocomplete="name" required aria-describedby="e-parent">
            <p class="field__error" id="e-parent" hidden></p>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="f-phone">Mobile number <span aria-hidden="true">*</span></label>
              <input id="f-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" required aria-describedby="h-phone e-phone" placeholder="98765 43210">
              <p class="field__hint" id="h-phone">10-digit Indian mobile number</p>
              <p class="field__error" id="e-phone" hidden></p>
            </div>
            <div class="field">
              <label for="f-email">Email</label>
              <input id="f-email" name="email" type="email" autocomplete="email" aria-describedby="e-email">
              <p class="field__error" id="e-email" hidden></p>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="f-child">Child's name</label>
              <input id="f-child" name="child_name" type="text" autocomplete="off">
            </div>
            <div class="field">
              <label for="f-class">Class applying for</label>
              <select id="f-class" name="class_applying_for">
                <option value="">Choose a class</option>
<?php foreach ($classes as $c): ?>
                <option><?= e($c) ?></option>
<?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="f-message">Message</label>
            <textarea id="f-message" name="message" rows="4"><?= $isVisit ? 'I would like to book a campus tour.' : '' ?></textarea>
          </div>
          <button type="submit" class="btn btn--primary">Send enquiry</button>
          <p class="form-status" role="status" aria-live="polite"></p>
        </form>
        <div class="form-thanks" role="status" tabindex="-1" hidden>
          <?= doodle('star', 'form-thanks__star') ?>
          <h3>Thank you!</h3>
          <p>We have your enquiry. The school office will contact you soon.</p>
        </div>
<?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section-tight" aria-labelledby="contact-title" id="contact">
    <div class="container">
      <?= section_heading('Contact', ['Visit or', '<span class="hl">get in touch</span>'], 'contact-title') ?>
      <div class="contact-grid">
        <ul class="contact-cards" role="list">
          <li class="card">
            <h3><?= icon('map') ?> Address</h3>
            <address><?= e(cfg('address.street')) ?>,<br><?= e(cfg('address.locality')) ?>, <?= e(cfg('address.district')) ?>,<br><?= e(cfg('address.region')) ?> <?= e(cfg('address.postcode')) ?></address>
            <a class="card__meta" href="<?= e(map_url()) ?>" rel="noopener">Directions in Google Maps</a>
          </li>
          <li class="card">
            <h3><?= icon('phone') ?> Phone</h3>
            <p><?php foreach (cfg('phones') as $p): ?><a href="tel:<?= e($p['tel']) ?>"><?= e($p['label']) ?></a><br><?php endforeach; ?></p>
            <a class="card__meta" href="<?= e(whatsapp_chat_url()) ?>" rel="noopener">WhatsApp the office</a>
          </li>
          <li class="card">
            <h3><?= icon('mail') ?> Email</h3>
            <p><?php foreach (cfg('emails') as $m): ?><a href="mailto:<?= e($m) ?>"><?= e($m) ?></a><br><?php endforeach; ?></p>
          </li>
          <li class="card">
            <h3><?= icon('clock') ?> Office hours</h3>
            <p><span class="placeholder">[OFFICE HOURS, e.g. Monday to Saturday]</span></p>
          </li>
        </ul>
        <div class="map-facade" data-map-src="https://www.google.com/maps?q=<?= e(rawurlencode((string) cfg('map_query'))) ?>&amp;output=embed">
          <div class="map-facade__board">
            <?= doodle('arrow', 'map-facade__arrow') ?>
            <p class="map-facade__text">Godhra Road, beside HDFC Bank, Halol</p>
            <button type="button" class="btn btn--secondary btn--small" data-map-load><?= icon('map') ?>Show the map</button>
            <p class="muted small">The map loads from Google only when you press the button.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="faq-title">
    <div class="container faq__inner">
      <?= section_heading('Questions', ['Admission', '<span class="hl">questions</span>'], 'faq-title', 'section-head--center') ?>
      <?php require ROOT . '/partials/faq.php'; ?>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
