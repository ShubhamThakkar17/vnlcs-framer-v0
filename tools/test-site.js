// Site-wide QA: titles/descriptions, JSON-LD, internal links, theme memory, no-flash, reduced motion.
// Usage: node tools/test-site.js [base-url]   (default http://127.0.0.1:8080)
const { chromium } = require('playwright');
const BASE = (process.argv[2] || 'http://127.0.0.1:8080').replace(/\/$/, '');
const PAGES = ['/', '/about/', '/academics/', '/facilities/', '/gallery/', '/notices/', '/notices/cbse-date-sheet-2027-class-10-12', '/admissions/', '/cbse-disclosure/'];
let fail = 0;
const check = (l, ok, extra = '') => { console.log((ok ? '  pass  ' : '  FAIL  ') + l + (ok || !extra ? '' : '  -> ' + extra)); if (!ok) fail++; };

(async () => {
  const b = await chromium.launch();
  const page = await b.newPage();
  const titles = new Map(), descs = new Map(), links = new Set();
  let ldOk = true, ldErr = '';

  console.log('SEO and structure');
  for (const p of PAGES) {
    const res = await page.goto(BASE + p);
    if (res.status() !== 200) { check(`${p} loads`, false, res.status()); continue; }
    const info = await page.evaluate(() => ({
      title: document.title,
      desc: document.querySelector('meta[name=description]')?.content || '',
      canonical: document.querySelector('link[rel=canonical]')?.href || '',
      og: document.querySelector('meta[property="og:image"]')?.content || '',
      h1: document.querySelectorAll('h1').length,
      ld: [...document.querySelectorAll('script[type="application/ld+json"]')].map(s => s.textContent),
      links: [...document.querySelectorAll('a[href]')].map(a => a.getAttribute('href')),
      themeScriptFirst: document.documentElement.getAttribute('data-theme') === 'blackboard',
    }));
    titles.set(info.title, (titles.get(info.title) || []).concat(p));
    descs.set(info.desc, (descs.get(info.desc) || []).concat(p));
    if (info.h1 !== 1) check(`${p} has exactly one H1`, false, info.h1);
    if (!info.canonical.startsWith('https://veernewlookschool.com/')) check(`${p} canonical`, false, info.canonical);
    if (!info.og) check(`${p} og:image`, false);
    if (!info.themeScriptFirst) check(`${p} html carries data-theme="blackboard"`, false);
    for (const s of info.ld) { try { const j = JSON.parse(s); if (!j['@type']) throw new Error('no @type'); } catch (e) { ldOk = false; ldErr = p + ' ' + e.message; } }
    if (p === '/') check('home has School JSON-LD with parentOrganization', info.ld.some(s => s.includes('"@type":"School"') && s.includes('parentOrganization')));
    if (p === '/about/') check('inner pages have BreadcrumbList JSON-LD', info.ld.some(s => s.includes('BreadcrumbList')));
    if (p.startsWith('/notices/cbse-')) check('notice page has NewsArticle JSON-LD', info.ld.some(s => s.includes('NewsArticle')));
    info.links.forEach(h => { if (h.startsWith('/') && !h.startsWith('//')) links.add(h.split('#')[0] || '/'); });
  }
  check('every page has a unique title', [...titles.values()].every(v => v.length === 1), JSON.stringify([...titles].filter(([, v]) => v.length > 1)));
  check('every page has a unique meta description', [...descs.values()].every(v => v.length === 1), JSON.stringify([...descs].filter(([, v]) => v.length > 1)));
  check('all JSON-LD blocks parse', ldOk, ldErr);
  check('every page has one H1, canonical, og:image and the Blackboard theme', true);

  console.log('Internal links');
  let broken = [];
  for (const l of links) {
    const r = await page.request.get(BASE + l, { maxRedirects: 0 });
    if (![200, 301].includes(r.status())) broken.push(`${l} (${r.status()})`);
  }
  check(`${links.size} internal links resolve`, broken.length === 0, broken.join(', '));

  console.log('Small screens');
  const wide = [];
  for (const p of PAGES) for (const w of [320, 375, 414]) {
    const sp = await b.newPage({ viewport: { width: w, height: 800 } });
    await sp.goto(BASE + p);
    await sp.waitForTimeout(300);
    const sw = await sp.evaluate(() => document.documentElement.scrollWidth);
    if (sw > w) wide.push(`${p} @${w}px = ${sw}px`);
    await sp.close();
  }
  check('no sideways scrolling at 320, 375 and 414 px', wide.length === 0, wide.join(', '));

  console.log('Theme (Blackboard only; Whiteboard is commented out)');
  const ctx = await b.newContext();
  const t = await ctx.newPage();
  await t.goto(BASE + '/');
  check('opens on Blackboard', (await t.getAttribute('html', 'data-theme')) === 'blackboard');
  check('no Blackboard / Whiteboard toggle shown', (await t.$$('[data-theme-set]')).length === 0);
  check('Whiteboard tokens are not active', await t.evaluate(() => getComputedStyle(document.documentElement).getPropertyValue('--board').trim().toUpperCase() === '#1E2B25'));
  const old = await b.newContext();
  const op = await old.newPage();
  await op.addInitScript(() => { try { localStorage.setItem('vnl-theme', 'whiteboard'); } catch (e) {} });
  await op.goto(BASE + '/about/');
  check('a visitor who chose Whiteboard before still gets Blackboard', (await op.getAttribute('html', 'data-theme')) === 'blackboard'
    && await op.evaluate(() => getComputedStyle(document.body).backgroundColor === 'rgb(30, 43, 37)'));
  const blocked = await b.newContext({ javaScriptEnabled: true });
  const bp = await blocked.newPage();
  await bp.addInitScript(() => { Object.defineProperty(window, 'localStorage', { get() { throw new Error('blocked'); } }); });
  await bp.goto(BASE + '/');
  check('works when localStorage is blocked (private mode)', (await bp.getAttribute('html', 'data-theme')) === 'blackboard');

  console.log('Notices (data/notices.json)');
  const np = await b.newPage();
  await np.goto(BASE + '/notices/');
  const board = await np.evaluate(() => ({ count: document.querySelectorAll('.notes--board .note').length, text: document.body.innerText }));
  check('notice board shows real notices, no [SAMPLE] items', board.count > 0 && !board.text.includes('[SAMPLE]') && !board.text.includes('sample notices'), board.count);
  await np.goto(BASE + '/notices/cbse-date-sheet-2027-class-10-12');
  const sheet = await np.evaluate(() => document.querySelector('.notice-sheet').innerText);
  check('date sheet notice shows Wed 17 Feb and its circular number', sheet.includes('Wed 17 Feb') && !sheet.includes('Tue 17 Feb') && sheet.includes('CBSE/Date Sheet/X-XII/2026-27'));
  for (const slug of ['childrens-day-2026', 'cbse-acad-74-2026-aryabhata-ganit-challenge']) {
    const r = await np.request.get(BASE + '/notices/' + slug, { maxRedirects: 0 });
    check(`unpublished notice /notices/${slug} is not served`, r.status() === 404, r.status());
  }
  const raw = await np.request.get(BASE + '/data/notices.json', { maxRedirects: 0 });
  check('data/notices.json is not downloadable', raw.status() === 404, raw.status());

  console.log('Photos');
  const ph = await b.newPage({ viewport: { width: 375, height: 800 } });
  const badPhotos = [];
  for (const p of ['/', '/about/', '/academics/', '/facilities/', '/gallery/']) {
    await ph.goto(BASE + p);
    const bad = await ph.$$eval('main img', async imgs => {
      const out = [];
      for (const i of imgs) {
        i.loading = 'eager';                       // load it now, as scrolling would
        try { await i.decode(); } catch (e) { /* reported below */ }
        if (i.naturalWidth === 0 || !i.alt) out.push(i.currentSrc || i.src);
      }
      return out;
    });
    badPhotos.push(...bad.map(u => p + ' ' + u));
  }
  check('every photo loads and has alt text', badPhotos.length === 0, badPhotos.join(', '));

  console.log('Reduced motion');
  const rm = await b.newPage({ reducedMotion: 'reduce' });
  await rm.goto(BASE + '/');
  const motion = await rm.evaluate(() => {
    const anim = [...document.querySelectorAll('*')].filter(el => {
      const cs = getComputedStyle(el);
      return cs.animationName !== 'none' && parseFloat(cs.animationDuration) > 0.01;
    }).map(el => el.className);
    return { anim, pauseButtons: document.querySelectorAll('.strip__pause').length, clones: document.querySelectorAll('.strip__group[aria-hidden]').length };
  });
  check('no running animations', motion.anim.length === 0, motion.anim.join(', '));
  check('marquee strips are static (not duplicated)', motion.clones === 0);
  await rm.locator('.cta').scrollIntoViewIfNeeded();
  await rm.waitForTimeout(600);
  const roughAnimated = await rm.evaluate(() => [...document.querySelectorAll('.rough-annotation path')].some(p => (p.style.animation || '').includes('rough')));
  check('chalk underlines drawn without animation', !roughAnimated);

  await b.close();
  console.log(fail ? `\n${fail} check(s) failed.` : '\nAll site checks passed.');
  process.exit(fail ? 1 : 0);
})();
