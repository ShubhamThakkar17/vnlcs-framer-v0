/* Moving strips (notice ticker, keyword marquee, tagline rail).
   Markup: <div class="strip" data-strip><div class="strip__track"><div class="strip__group">…items…</div></div></div>
   Optional: data-strip-controls="#id" on the element that should receive the pause button
   (otherwise the button goes on the strip itself). One button can pause several strips. */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var ICON_PAUSE = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4h3.5v16H7zM13.5 4H17v16h-3.5z" fill="currentColor"/></svg>';
  var ICON_PLAY = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4l13 8-13 8z" fill="currentColor"/></svg>';

  function fill(strip) {
    var track = strip.querySelector('.strip__track');
    var group = track && track.querySelector('.strip__group');
    if (!group) return;
    // Repeat items until one group is at least as wide as the screen, then clone
    // the group so the -50% loop is seamless. Clones are hidden from assistive tech.
    var items = Array.prototype.slice.call(group.children);
    var guard = 0;
    while (group.scrollWidth < window.innerWidth && guard++ < 10) {
      items.forEach(function (it) { group.appendChild(hide(it.cloneNode(true))); });
    }
    track.appendChild(hide(group.cloneNode(true)));
    // Keep speed constant whatever the content length (~70 px per second).
    var dur = Math.max(18, Math.round(group.scrollWidth / 70));
    track.style.setProperty('--duration', dur + 's');
  }

  function hide(node) {
    node.setAttribute('aria-hidden', 'true');
    node.querySelectorAll('a, button').forEach(function (el) { el.setAttribute('tabindex', '-1'); });
    if (node.matches && node.matches('a, button')) node.setAttribute('tabindex', '-1');
    return node;
  }

  function addButton(host, strips) {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'strip__pause';
    var label = host.getAttribute('data-strip-label') || 'scrolling text';
    function render(paused) {
      btn.innerHTML = paused ? ICON_PLAY : ICON_PAUSE;
      btn.setAttribute('aria-label', (paused ? 'Play ' : 'Pause ') + label);
      btn.setAttribute('aria-pressed', String(paused));
    }
    btn.addEventListener('click', function () {
      var paused = !strips[0].classList.contains('is-paused');
      strips.forEach(function (s) { s.classList.toggle('is-paused', paused); });
      render(paused);
    });
    render(false);
    host.appendChild(btn);
  }

  var hosts = new Map();
  document.querySelectorAll('[data-strip]').forEach(function (strip) {
    if (reduce) return;                     // static under reduced motion
    fill(strip);
    var sel = strip.getAttribute('data-strip-controls');
    var host = sel ? document.querySelector(sel) : strip;
    if (!hosts.has(host)) hosts.set(host, []);
    hosts.get(host).push(strip);
  });
  hosts.forEach(function (strips, host) { addButton(host, strips); });
})();
