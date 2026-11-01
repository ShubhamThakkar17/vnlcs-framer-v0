// Phase 0 helper: full-page screenshots of the two visual references.
// Usage: node tools/reference-shots.js
// Output goes to /reference (gitignored, never uploaded).
const { chromium } = require('playwright');
const path = require('path');

const SITES = {
  brightpath: 'https://brightpath-wbs.framer.website/',
  kidora: 'https://kidora.framer.website/',
};
const WIDTHS = [375, 1440];
const OUT = path.join(__dirname, '..', 'reference');
require('fs').mkdirSync(path.join(OUT, 'frames'), { recursive: true });

async function scrollThrough(page) {
  // Framer reveals sections on scroll; walk the page so everything renders.
  await page.evaluate(async () => {
    const step = window.innerHeight / 4;
    for (let y = 0; y < document.body.scrollHeight; y += step) {
      window.scrollTo(0, y);
      await new Promise(r => setTimeout(r, 350));
    }
  });
  await page.waitForTimeout(2000);
}

(async () => {
  const browser = await chromium.launch();
  for (const [name, url] of Object.entries(SITES)) {
    for (const width of WIDTHS) {
      const ctx = await browser.newContext({
        viewport: { width, height: width < 600 ? 812 : 900 },
        deviceScaleFactor: 1,
      });
      const page = await ctx.newPage();
      await page.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
      await scrollThrough(page);
      // Also keep viewport-sized frames: some reveal animations reset when
      // the full-page capture resizes the viewport.
      const total = await page.evaluate(() => document.body.scrollHeight);
      const vh = page.viewportSize().height;
      for (let y = 0, i = 0; y < total; y += vh, i++) {
        await page.evaluate(v => window.scrollTo(0, v), y);
        await page.waitForTimeout(900);
        await page.screenshot({ path: path.join(OUT, 'frames', `${name}-${width}-${String(i).padStart(2, '0')}.png`) });
      }
      await page.evaluate(() => window.scrollTo(0, 0));
      await page.waitForTimeout(600);
      const file = path.join(OUT, `${name}-${width}.png`);
      await page.screenshot({ path: file, fullPage: true });
      console.log('saved', file);
      await ctx.close();
    }
  }
  await browser.close();
})();
