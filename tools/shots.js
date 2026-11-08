// Screenshots of local pages in both themes at 375 px and 1440 px.
// Usage: php -S 127.0.0.1:8000   (in another shell)
//        node tools/shots.js styletile.html [other/path ...]
// Output: reports/screens/<page>-<theme>-<width>.png (gitignored)
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000/';
const pages = process.argv.slice(2).length ? process.argv.slice(2) : ['styletile.html'];
const OUT = path.join(__dirname, '..', 'reports', 'screens');
fs.mkdirSync(OUT, { recursive: true });

(async () => {
  const browser = await chromium.launch();
  for (const p of pages) {
    for (const theme of ['blackboard' /* , 'whiteboard' (theme commented out, Nov 2026) */]) {
      for (const width of [375, 1440]) {
        const ctx = await browser.newContext({ viewport: { width, height: width < 600 ? 812 : 900 } });
        await ctx.addInitScript(t => { try { localStorage.setItem('vnl-theme', t); } catch (e) {} }, theme);
        const page = await ctx.newPage();
        page.on('console', m => { if (m.type() === 'error') console.log('  console error:', m.text()); });
        page.on('pageerror', e => console.log('  page error:', e.message));
        await page.goto(BASE.replace(/\/$/, '') + '/' + p.replace(/^\//, ''), { waitUntil: 'networkidle' });
        // Scroll through so on-scroll chalk marks draw, then return to top.
        await page.evaluate(async () => {
          for (let y = 0; y < document.body.scrollHeight; y += 400) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 60)); }
          window.scrollTo(0, 0);
        });
        // Load lazy photos before the full-page capture.
        await page.$$eval('img', async imgs => { for (const i of imgs) { i.loading = 'eager'; try { await i.decode(); } catch (e) {} } });
        await page.waitForTimeout(1300);
        const name = p.replace(/\/$/, '/index').replace(/[\/]/g, '_').replace(/\.(html|php)$/, '') || 'home';
        const file = path.join(OUT, `${name}-${theme}-${width}.png`);
        await page.screenshot({ path: file, fullPage: true });
        console.log('saved', path.relative(process.cwd(), file));
        await ctx.close();
      }
    }
  }
  await browser.close();
})();
