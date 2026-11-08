/* NOT LOADED: the Whiteboard theme is commented out (Blackboard only, Nov 2026).
   Kept for later. Re-enable in partials/footer.php, partials/header.php and partials/head.php. */
/* Theme toggle: Blackboard / Whiteboard.
   The inline <head> script has already set data-theme before first paint;
   this file only wires up the buttons and remembers the choice. */
(function () {
  'use strict';
  var KEY = 'vnl-theme';
  var root = document.documentElement;
  var COLORS = { blackboard: '#1E2B25', whiteboard: '#FAFAF7' };

  function current() {
    return root.getAttribute('data-theme') === 'whiteboard' ? 'whiteboard' : 'blackboard';
  }

  function apply(theme) {
    root.setAttribute('data-theme', theme);
    try { localStorage.setItem(KEY, theme); } catch (e) { /* private mode: fine, just not remembered */ }
    var meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', COLORS[theme]);
    sync();
    document.dispatchEvent(new CustomEvent('themechange', { detail: { theme: theme } }));
  }

  function sync() {
    var theme = current();
    document.querySelectorAll('[data-theme-set]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', String(btn.getAttribute('data-theme-set') === theme));
    });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-theme-set]');
    if (btn) apply(btn.getAttribute('data-theme-set'));
  });

  sync();
})();
