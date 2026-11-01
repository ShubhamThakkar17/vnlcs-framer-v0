/* Site-wide behaviour: mobile menu, back-to-top wipe, copy-link buttons, campus-tour prefill. */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Mobile menu */
  var btn = document.querySelector('.menu-btn');
  var nav = document.getElementById('site-nav');
  if (btn && nav) {
    var label = btn.querySelector('.menu-btn__label');
    function setOpen(open) {
      btn.setAttribute('aria-expanded', String(open));
      nav.classList.toggle('is-open', open);
      if (label) label.textContent = open ? 'Close' : 'Menu';
    }
    btn.addEventListener('click', function () { setOpen(btn.getAttribute('aria-expanded') !== 'true'); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && btn.getAttribute('aria-expanded') === 'true') { setOpen(false); btn.focus(); }
    });
    nav.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
  }

  /* Back to top: a quick wipe of the board, then jump up */
  var top = document.querySelector('[data-back-to-top]');
  var wipe = document.querySelector('.wipe');
  if (top) {
    top.addEventListener('click', function () {
      var land = function () {
        window.scrollTo({ top: 0, behavior: 'auto' });
        var target = document.querySelector('.brand');
        if (target) target.focus({ preventScroll: true });
      };
      if (reduce || !wipe) { land(); return; }
      wipe.classList.remove('is-wiping');
      void wipe.offsetWidth;
      wipe.classList.add('is-wiping');
      setTimeout(land, 330);
      setTimeout(function () { wipe.classList.remove('is-wiping'); }, 760);
    });
  }

  /* Copy-link buttons: <button data-copy="https://...">Copy link</button> */
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-copy]');
    if (!b) return;
    var text = b.getAttribute('data-copy');
    var done = function () {
      var status = b.querySelector('[data-copy-status]') || b;
      var old = status.textContent;
      status.textContent = 'Link copied';
      setTimeout(function () { status.textContent = old; }, 2200);
    };
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(done, function () { window.prompt('Copy this link:', text); });
    } else {
      window.prompt('Copy this link:', text);
    }
  });
})();

/* Google Map facade: the iframe loads only when asked. */
(function () {
  'use strict';
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-map-load]');
    if (!b) return;
    var box = b.closest('[data-map-src]');
    var f = document.createElement('iframe');
    f.src = box.getAttribute('data-map-src');
    f.title = 'Map: Veer New Look Central School, Godhra Road, Halol';
    f.loading = 'lazy';
    f.referrerPolicy = 'no-referrer-when-downgrade';
    f.className = 'map-frame';
    box.innerHTML = '';
    box.appendChild(f);
  });
})();
