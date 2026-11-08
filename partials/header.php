<?php
$settings = site_settings();
?>
  <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <filter id="chalk-rough" x="-2%" y="-5%" width="104%" height="110%">
      <feTurbulence type="fractalNoise" baseFrequency=".85" numOctaves="2" seed="4" result="n"/>
      <feDisplacementMap in="SourceGraphic" in2="n" scale="2.2" xChannelSelector="R" yChannelSelector="G"/>
    </filter>
    <filter id="chalk-line" x="-3%" y="-3%" width="106%" height="106%">
      <feTurbulence type="fractalNoise" baseFrequency=".6" numOctaves="2" seed="9" result="n"/>
      <feDisplacementMap in="SourceGraphic" in2="n" scale="2.6" xChannelSelector="R" yChannelSelector="G" result="d"/>
      <feTurbulence type="fractalNoise" baseFrequency="1.4" numOctaves="1" seed="2" result="g"/>
      <feColorMatrix in="g" type="matrix" values="0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  3.2 0 0 0 -.75" result="m"/>
      <feComposite in="d" in2="m" operator="in"/>
    </filter>
  </svg>
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="masthead" id="top">
<?php if ($settings['emergencyBanner'] !== ''): ?>
    <div class="emergency" role="status">
      <div class="container"><strong>Important:</strong> <?= e($settings['emergencyBanner']) ?></div>
    </div>
<?php endif; ?>
    <div class="top-strip">
      <div class="container top-strip__inner">
        <ul role="list" class="top-strip__contact">
<?php foreach (cfg('phones') as $i => $p): ?>
          <li><a href="tel:<?= e($p['tel']) ?>"><?= icon($i === 0 ? 'phone' : 'mobile') ?><span><?= e($p['label']) ?></span></a></li>
<?php endforeach; ?>
          <li class="hide-sm"><a href="mailto:<?= e(cfg('emails')[0]) ?>"><?= icon('mail') ?><span><?= e(cfg('emails')[0]) ?></span></a></li>
        </ul>
        <ul role="list" class="top-strip__social">
<?php foreach (cfg('instagram') as $ig): ?>
          <li><a href="<?= e($ig['url']) ?>" rel="noopener"><?= icon('instagram') ?><span class="hide-sm">@<?= e($ig['handle']) ?></span><span class="visually-hidden show-sm-sr"> Instagram @<?= e($ig['handle']) ?></span></a></li>
<?php endforeach; ?>
<?php if (cfg('youtube')): ?>
          <li><a href="<?= e(cfg('youtube')) ?>" rel="noopener"><?= icon('youtube') ?><span class="visually-hidden">YouTube</span></a></li>
<?php endif; ?>
        </ul>
        <?php /* WHITEBOARD THEME COMMENTED OUT (Blackboard only): echo theme_toggle('theme-toggle--strip'); */ ?>
      </div>
    </div>

    <div class="site-header">
      <div class="container site-header__inner">
        <a class="brand" href="/" aria-label="Veer New Look Central School, Halol: home">
          <img class="brand__logo" src="/assets/img/brand/logo-chalk-64.png" srcset="/assets/img/brand/logo-chalk-128.png 2x, /assets/img/brand/logo-chalk-192.png 3x" width="59" height="64" alt="" decoding="async">
          <span class="brand__text">
            <span class="brand__name">Veer New Look</span>
            <span class="brand__place">Central School, Halol</span>
          </span>
        </a>

        <nav class="site-nav" id="site-nav" aria-label="Main">
          <ul role="list">
<?php foreach (nav_items() as $item): ?>
            <li><a href="<?= e($item['href']) ?>"<?= is_current($item['href']) ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a></li>
<?php endforeach; ?>
          </ul>
          <a class="btn btn--primary btn--small site-nav__cta" href="/admissions/#enquire">Enquire Now</a>
        </nav>

        <div class="site-header__tools">
          <?php /* WHITEBOARD THEME COMMENTED OUT (Blackboard only): echo theme_toggle('theme-toggle--header'); */ ?>
          <button type="button" class="menu-btn" aria-controls="site-nav" aria-expanded="false">
            <?= icon('menu', 'icon menu-btn__open') ?><?= icon('close', 'icon menu-btn__close') ?>
            <span class="menu-btn__label">Menu</span>
          </button>
        </div>
      </div>
    </div>
  </header>
