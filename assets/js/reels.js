/* Instagram reels in chalk-drawn phone frames.
 *
 * The page arrives with reels already rendered by PHP from /data/instagram.json
 * (or the sample file), so the row is never empty. This script then:
 *   - plays each video muted and looped while it is on screen, pauses it when it leaves
 *   - adds tap-to-unmute (one reel with sound at a time) and a "Pause reels" control
 *   - with source "behold", swaps in the Behold JSON feed (normalised to our shape)
 *   - filters the gallery wall by account ([data-reel-filter] buttons)
 * Data source is set in one line: inc/config.php > 'reels_source' => 'local' | 'behold'.
 */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var SOUND_ON = '<svg class="icon reel__on" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" d="M4 9.5h3.5L12 5.5v13l-4.5-4H4zM16 9a4.5 4.5 0 0 1 0 6M18.5 6.5a8 8 0 0 1 0 11"/></svg>';
  var SOUND_OFF = '<svg class="icon reel__off" viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" d="M4 9.5h3.5L12 5.5v13l-4.5-4H4zM16 9.5l5 5M21 9.5l-5 5"/></svg>';

  /* ---------- Normalise both feed shapes to { id, account, caption, permalink, thumb, video } ---------- */
  function firstLine(s) {
    s = String(s || '').split('\n')[0].trim();
    return s.length > 90 ? s.slice(0, 89).trim() + '…' : s;
  }
  function normaliseLocal(json) {
    return (json && json.items || []).map(function (it) {
      return { id: it.id, account: it.account, caption: firstLine(it.caption), permalink: it.permalink, thumb: it.thumb, video: it.video };
    });
  }
  function normaliseBehold(json) {
    var posts = Array.isArray(json) ? json : (json && json.posts) || [];
    var handle = (json && json.username) || '';
    return posts.map(function (p) {
      var isVideo = p.mediaType === 'VIDEO' || p.isReel;
      var small = p.sizes && (p.sizes.medium || p.sizes.small);
      return {
        id: p.id,
        account: p.username || handle,
        caption: firstLine(p.prunedCaption || p.caption),
        permalink: p.permalink,
        thumb: (small && small.mediaUrl) || p.thumbnailUrl || (!isVideo ? p.mediaUrl : null),
        video: isVideo ? p.mediaUrl : null
      };
    });
  }

  function el(tag, cls, text) {
    var n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text) n.textContent = text;
    return n;
  }

  function renderItem(r) {
    var li = el('li', 'reel');
    li.setAttribute('data-account', r.account || '');
    var phone = el('div', 'reel__phone');
    var screen = el('div', 'reel__screen');
    if (r.video) {
      var v = document.createElement('video');
      v.muted = true; v.loop = true; v.playsInline = true;
      v.setAttribute('muted', ''); v.setAttribute('playsinline', ''); v.setAttribute('loop', '');
      v.preload = 'none';
      if (r.thumb) v.poster = r.thumb;
      v.src = r.video;
      v.setAttribute('aria-label', 'Reel from @' + r.account);
      screen.appendChild(v);
      var b = el('button', 'reel__sound');
      b.type = 'button';
      b.setAttribute('aria-pressed', 'false');
      b.setAttribute('aria-label', 'Turn sound on');
      b.innerHTML = SOUND_OFF + SOUND_ON;
      screen.appendChild(b);
    } else if (r.thumb) {
      var img = el('img');
      img.src = r.thumb; img.alt = ''; img.width = 540; img.height = 960; img.loading = 'lazy'; img.decoding = 'async';
      screen.appendChild(img);
    }
    screen.appendChild(el('span', 'reel__handle', '@' + r.account));
    phone.appendChild(screen);
    li.appendChild(phone);
    var a = el('a', 'reel__caption', r.caption || 'Watch on Instagram');
    a.href = r.permalink || ('https://www.instagram.com/' + r.account + '/');
    a.rel = 'noopener';
    var sr = el('span', 'visually-hidden', ' (opens Instagram)');
    a.appendChild(sr);
    li.appendChild(a);
    return li;
  }

  /* ---------- Video behaviour ---------- */
  function wire(wrap) {
    var videos = Array.prototype.slice.call(wrap.querySelectorAll('video'));
    if (!videos.length) return;
    var paused = reduce;               // reduced motion: nothing plays by itself

    if (reduce) videos.forEach(function (v) { v.controls = true; });

    var visible = new Set();
    function sync() {
      videos.forEach(function (v) {
        if (!paused && visible.has(v)) {
          var p = v.play();
          if (p && p.catch) p.catch(function () { /* autoplay blocked: poster stays */ });
        } else if (!v.paused && !(reduce && v.dataset.userPlay)) {
          v.pause();
        }
      });
    }

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting && en.intersectionRatio >= 0.5) visible.add(en.target); else visible.delete(en.target);
        });
        sync();
      }, { threshold: [0, 0.5, 1] });
      videos.forEach(function (v) { io.observe(v); });
    }

    wrap.addEventListener('click', function (e) {
      var b = e.target.closest('.reel__sound');
      if (!b) return;
      var v = b.parentNode.querySelector('video');
      var turnOn = v.muted;
      videos.forEach(function (o) {
        o.muted = true;
        var ob = o.parentNode.querySelector('.reel__sound');
        if (ob) { ob.setAttribute('aria-pressed', 'false'); ob.setAttribute('aria-label', 'Turn sound on'); }
      });
      if (turnOn) {
        v.muted = false;
        b.setAttribute('aria-pressed', 'true');
        b.setAttribute('aria-label', 'Turn sound off');
        if (v.paused) { v.dataset.userPlay = '1'; v.play().catch(function () {}); }
      }
    });

    // One control to pause or play every reel (WCAG 2.2.2)
    var ctl = el('button', 'btn btn--small reels-pause');
    ctl.type = 'button';
    function label() {
      ctl.textContent = paused ? 'Play reels' : 'Pause reels';
      ctl.setAttribute('aria-pressed', String(paused));
    }
    ctl.addEventListener('click', function () { paused = !paused; label(); sync(); });
    label();
    wrap.appendChild(ctl);
  }

  /* ---------- Account filter on the gallery wall ---------- */
  function wireFilter(wrap) {
    var buttons = document.querySelectorAll('[data-reel-filter][aria-controls="' + wrap.id + '"]');
    buttons.forEach(function (b) {
      b.addEventListener('click', function () {
        var acc = b.getAttribute('data-reel-filter');
        buttons.forEach(function (o) { o.setAttribute('aria-pressed', String(o === b)); });
        wrap.querySelectorAll('.reel').forEach(function (li) {
          li.hidden = acc !== 'all' && li.getAttribute('data-account') !== acc;
        });
      });
    });
  }

  document.querySelectorAll('[data-reels]').forEach(function (wrap) {
    var source = wrap.getAttribute('data-source');
    var behold = wrap.getAttribute('data-behold');
    var limit = parseInt(wrap.getAttribute('data-limit') || '0', 10);
    var list = wrap.querySelector('.reels');
    if (wrap.id) wireFilter(wrap);

    if (source === 'behold' && behold && list) {
      fetch(behold, { credentials: 'omit' })
        .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(function (json) {
          var items = normaliseBehold(json);
          if (!items.length) throw new Error('empty');
          if (limit) items = items.slice(0, limit);
          list.innerHTML = '';
          items.forEach(function (it) { list.appendChild(renderItem(it)); });
        })
        .catch(function () { /* keep the server-rendered cached reels */ })
        .then(function () { wire(wrap); });
    } else {
      wire(wrap);
    }
  });

  // Exposed for tests
  window.VNLReels = { normaliseLocal: normaliseLocal, normaliseBehold: normaliseBehold };
})();
