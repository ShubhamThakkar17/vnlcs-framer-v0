// Renders the Open Graph image (assets/img/og-default.png) from tools/templates/og.html.
// The favicons and app icons come from the school logo: run tools/make-logo.js first.
// Usage (local server running): node tools/make-icons.js
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
const IMG = path.join(__dirname, '..', 'assets', 'img');
const LOGO = 'data:image/png;base64,' + fs.readFileSync(path.join(__dirname, 'brand', 'logo-chalk-360.png')).toString('base64');
(async () => {
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1200, height: 630 } });
  await p.goto(BASE + '/robots.txt');   // same origin as the fonts, so they load
  const html = fs.readFileSync(path.join(__dirname, 'templates', 'og.html'), 'utf8')
    .replace(/url\(\/assets/g, 'url(' + BASE + '/assets')
    .replace('{{LOGO}}', LOGO);
  await p.setContent(html, { waitUntil: 'networkidle' });
  await p.waitForTimeout(400);
  await p.screenshot({ path: path.join(IMG, 'og-default.png') });
  await b.close();
  console.log('og image written');
})();
