// Site-wide QA: titles/descriptions, JSON-LD, internal links, theme memory, no-flash, reduced motion.
// Usage: node tools/test-site.js [base-url]   (default http://127.0.0.1:8080)
const { chromium } = require('playwright');
const BASE = (process.argv[2] || 'http://127.0.0.1:8080').replace(/\/$/, '');
const PAGES = ['/', '/about/', '/academics/', '/facilities/', '/gallery/', '/notices/', '/notices/sample-cbse-circular', '/admissions/', '/cbse-disclosure/'];
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
      themeScriptFirst: (() => { const h = document.head.innerHTML; return h.indexOf("localStorage.getItem('vnl-theme')") < h.indexOf('stylesheet'); })(),
    }));
    titles.set(info.title, (titles.get(info.title) || []).concat(p));
    descs.set(info.desc, (descs.get(info.desc) || []).concat(p));
    if (info.h1 !== 1) check(`${p} has exactly one H1`, false, info.h1);
    if (!info.canonical.startsWith('https://veernewlookschool.com/')) check(`${p} canonical`, false, info.canonical);
    if (!info.og) check(`${p} og:image`, false);
    if (!info.themeScriptFirst) check(`${p} theme script runs before CSS`, false);
    for (const s of info.ld) { try { const j = JSON.parse(s); if (!j['@type']) throw new Error('no @type'); } catch (e) { ldOk = false; ldErr = p + ' ' + e.message; } }
    if (p === '/') check('home has School JSON-LD with parentOrganization', info.ld.some(s => s.includes('"@type":"School"') && s.includes('parentOrganization')));
    if (p === '/about/') check('inner pages have BreadcrumbList JSON-LD', info.ld.some(s => s.includes('BreadcrumbList')));
    if (p.startsWith('/notices/sample')) check('notice page has NewsArticle JSON-LD', info.ld.some(s => s.includes('NewsArticle')));
    info.links.forEach(h => { if (h.startsWith('/') && !h.startsWith('//')) links.add(h.split('#')[0] || '/'); });
  }
  check('every page has a unique title', [...titles.values()].every(v => v.length === 1), JSON.stringify([...titles].filter(([, v]) => v.length > 1)));
  check('every page has a unique meta description', [...descs.values()].every(v => v.length === 1), JSON.stringify([...descs].filter(([, v]) => v.length > 1)));
  check('all JSON-LD blocks parse', ldOk, ldErr);
  check('every page has one H1, canonical, og:image and the no-flash theme script', true);

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

  console.log('Themes');
  const ctx = await b.newContext();
  const t = await ctx.newPage();
  await t.goto(BASE + '/');
  check('first visit opens on Blackboard', (await t.getAttribute('html', 'data-theme')) === 'blackboard');
  await t.click('.theme-toggle--header [data-theme-set=whiteboard]');
  await t.goto(BASE + '/notices/');
  check('choice remembered on the next page', (await t.getAttribute('html', 'data-theme')) === 'whiteboard');
  const atParse = await (async () => {
    const p2 = await ctx.newPage();
    let seen = null;
    await p2.exposeFunction('report', v => { seen = seen || v; });
    await p2.addInitScript(() => { new MutationObserver((m, o) => { if (document.body) { window.report(document.documentElement.dataset.theme); o.disconnect(); } }).observe(document, { childList: true, subtree: true }); });
    await p2.goto(BASE + '/about/');
    return seen;
  })();
  check('theme set before <body> exists (no flash)', atParse === 'whiteboard', atParse);
  const blocked = await b.newContext({ javaScriptEnabled: true });
  const bp = await blocked.newPage();
  await bp.addInitScript(() => { Object.defineProperty(window, 'localStorage', { get() { throw new Error('blocked'); } }); });
  await bp.goto(BASE + '/');
  check('works when localStorage is blocked (private mode)', (await bp.getAttribute('html', 'data-theme')) === 'blackboard');

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
