/* Notice board: change the month filter and the list updates (the Show button is the no-JS path). */
(function () {
  'use strict';
  document.querySelectorAll('form[data-autosubmit]').forEach(function (f) {
    var btn = f.querySelector('[type="submit"]');
    if (btn) btn.classList.add('visually-hidden');
    f.addEventListener('change', function () { f.submit(); });
  });
})();
