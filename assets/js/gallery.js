/* YouTube click-to-load facades: nothing loads from YouTube until the visitor presses play. */
(function () {
  'use strict';
  document.addEventListener('click', function (e) {
    var b = e.target.closest('.yt-facade');
    if (!b) return;
    var id = b.getAttribute('data-yt');
    if (!/^[A-Za-z0-9_-]{11}$/.test(id)) return;
    var f = document.createElement('iframe');
    f.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
    f.title = b.getAttribute('data-title') || 'YouTube video';
    f.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen';
    f.allowFullscreen = true;
    f.loading = 'lazy';
    f.className = 'yt-frame';
    b.replaceWith(f);
    f.focus();
  });
})();
