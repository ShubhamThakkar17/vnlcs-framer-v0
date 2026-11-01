// Renders the favicon PNGs and the Open Graph image from our own SVG/HTML.
// Usage (local server running): node tools/make-icons.js
const { chromium } = require('playwright');
const path = require('path');
const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
const IMG = path.join(__dirname, '..', 'assets', 'img');
(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1200, height: 630 } });
  await p.goto(BASE + '/tools/templates/og.html').catch(() => {});
  await p.setContent(require('fs').readFileSync(path.join(__dirname, 'templates', 'og.html'), 'utf8').replace(/url\(\/assets/g, 'url(' + BASE + '/assets'), { waitUntil: 'networkidle' });
  await p.waitForTimeout(400);
  await p.screenshot({ path: path.join(IMG, 'og-default.png') });
  for (const size of [180, 192, 512]) {
    const q = await b.newPage({ viewport: { width: size, height: size } });
    await q.setContent(`<style>html,body{margin:0}</style><img src="${BASE}/assets/img/favicon.svg" width="${size}" height="${size}">`, { waitUntil: 'networkidle' });
    await q.screenshot({ path: path.join(IMG, `icon-${size}.png`), omitBackground: true });
  }
  await b.close();
  console.log('icons and og image written');
})();
