/* Chalk effects. All decorative: if this file fails, the page still reads fine.
   - Adds the second, offset chalk stroke to every .btn
   - Draws Rough Notation marks on [data-annotate] once they scroll into view
     data-annotate="underline|circle|box|highlight|bracket"  data-annotate-color="accent-2" */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.querySelectorAll('.btn').forEach(function (btn) {
    if (btn.querySelector('.btn__stroke')) return;
    var s = document.createElement('span');
    s.className = 'btn__stroke';
    s.setAttribute('aria-hidden', 'true');
    btn.appendChild(s);
  });

  var marks = document.querySelectorAll('[data-annotate]');
  if (!marks.length || !window.RoughNotation) return;

  function draw(el) {
    var type = el.getAttribute('data-annotate') || 'underline';
    var color = el.getAttribute('data-annotate-color') || 'accent-2';
    var a = window.RoughNotation.annotate(el, {
      type: type,
      color: 'currentColor',
      strokeWidth: type === 'underline' ? 3 : 2.5,
      padding: type === 'circle' ? 10 : 4,
      iterations: 2,
      animate: !reduce,
      animationDuration: 900,
      multiline: true
    });
    a.show();
    // The SVG is inserted right after the element; colour it from a token so
    // it follows theme switches without being redrawn.
    var svg = el.nextElementSibling;
    if (svg && svg.classList.contains('rough-annotation')) {
      svg.style.color = 'var(--' + color + ')';
      svg.setAttribute('aria-hidden', 'true');
    }
  }

  if (!('IntersectionObserver' in window)) { marks.forEach(draw); return; }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      io.unobserve(entry.target);
      draw(entry.target);
    });
  }, { rootMargin: '0px 0px -15% 0px' });
  marks.forEach(function (m) { io.observe(m); });
})();
