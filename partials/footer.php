<?php
$year = date('Y');
?>
  <footer class="site-footer">
    <div class="chalk-tray">
      <div class="container chalk-tray__inner">
        <span class="chalk-tray__duster" aria-hidden="true"></span>
        <span class="chalk-tray__chalk chalk-tray__chalk--white" aria-hidden="true"></span>
        <span class="chalk-tray__chalk chalk-tray__chalk--pink" aria-hidden="true"></span>
        <span class="chalk-tray__chalk chalk-tray__chalk--yellow" aria-hidden="true"></span>
        <button type="button" class="back-to-top" data-back-to-top><?= icon('arrow-up') ?>Back to top</button>
      </div>
    </div>

    <div class="container site-footer__grid">
      <div class="site-footer__about">
        <p class="site-footer__name">Veer New Look Central School</p>
        <p class="muted">CBSE affiliated · English medium · Co-educational<br>Nursery to Class 12 · Since <?= (int) cfg('founded') ?></p>
        <address>
          <?= e(cfg('address.street')) ?>,<br>
          <?= e(cfg('address.locality')) ?>, <?= e(cfg('address.district')) ?>,<br>
          <?= e(cfg('address.region')) ?> <?= e(cfg('address.postcode')) ?>
        </address>
        <p><a href="<?= e(map_url()) ?>" rel="noopener"><?= icon('map') ?> Open in Google Maps</a></p>
      </div>

      <nav class="site-footer__col" aria-label="Quick links">
        <h2 class="site-footer__title">Quick links</h2>
        <ul role="list">
<?php foreach (nav_items() as $item): ?>
          <li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
<?php endforeach; ?>
          <li><a href="/cbse-disclosure/">CBSE Disclosure</a></li>
        </ul>
      </nav>

      <div class="site-footer__col">
        <h2 class="site-footer__title">Contact</h2>
        <ul role="list" class="site-footer__contact">
<?php foreach (cfg('phones') as $i => $p): ?>
          <li><a href="tel:<?= e($p['tel']) ?>"><?= icon($i === 0 ? 'phone' : 'mobile') ?><?= e($p['label']) ?></a></li>
<?php endforeach; ?>
<?php foreach (cfg('emails') as $m): ?>
          <li><a href="mailto:<?= e($m) ?>"><?= icon('mail') ?><?= e($m) ?></a></li>
<?php endforeach; ?>
          <li><a href="<?= e(whatsapp_chat_url()) ?>" rel="noopener"><?= icon('whatsapp') ?>WhatsApp the office</a></li>
        </ul>
      </div>

      <div class="site-footer__col">
        <h2 class="site-footer__title">Follow us</h2>
        <ul role="list" class="site-footer__contact">
<?php foreach (cfg('instagram') as $ig): ?>
          <li><a href="<?= e($ig['url']) ?>" rel="noopener"><?= icon('instagram') ?>@<?= e($ig['handle']) ?></a></li>
<?php endforeach; ?>
<?php if (cfg('youtube')): ?>
          <li><a href="<?= e(cfg('youtube')) ?>" rel="noopener"><?= icon('youtube') ?>YouTube</a></li>
<?php endif; ?>
        </ul>
        <a class="btn btn--primary btn--small" href="/admissions/#enquire">Enquire Now</a>
      </div>
    </div>

    <div class="container site-footer__base">
      <p>Run by the <?= e(cfg('trust')) ?>. Part of the <a href="<?= e(cfg('group.url')) ?>" rel="noopener"><?= e(cfg('group.name')) ?></a>.</p>
      <p>© <?= $year ?> Veer New Look Central School, Halol · <a href="/cbse-disclosure/">CBSE Disclosure</a> · <small>Website by <a href="https://keshavco.com" rel="noopener">KeshavCo</a></small></p>
    </div>
  </footer>

  <!-- Mobile: sticky Call / Enquire bar and WhatsApp button -->
  <div class="mobile-bar" role="navigation" aria-label="Quick contact">
    <a href="tel:<?= e(cfg('phones')[0]['tel']) ?>"><?= icon('phone') ?>Call</a>
    <a href="/admissions/#enquire" class="mobile-bar__primary"><?= icon('file') ?>Enquire</a>
  <a class="whatsapp-fab" href="<?= e(whatsapp_chat_url('Hello, I would like to know about admissions at Veer New Look Central School.')) ?>" rel="noopener">
    <?= icon('whatsapp') ?><span class="visually-hidden">Chat with the school on WhatsApp</span>
  </a>
  </div>


  <div class="wipe" aria-hidden="true"><span class="wipe__duster"></span></div>

<?php /* WHITEBOARD THEME COMMENTED OUT (Blackboard only): theme.js powers the Blackboard / Whiteboard toggle.
  <script src="<?= asset('js/theme.js') ?>" defer></script>
*/ ?>
  <script src="<?= asset('js/vendor/rough-notation.js') ?>" defer></script>
  <script src="<?= asset('js/chalk.js') ?>" defer></script>
  <script src="<?= asset('js/marquee.js') ?>" defer></script>
  <script src="<?= asset('js/site.js') ?>" defer></script>
<?php foreach ($page['scripts'] ?? [] as $js): ?>
  <script src="<?= asset('js/' . $js) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
