// axe-core accessibility check of local pages in both themes at 375 and 1440 px.
// Usage: node tools/axe.js / /about/ ...   (needs `npm i axe-core` somewhere resolvable, or AXE_PATH)
const { chromium } = require('playwright');
const fs = require('fs');
const AXE = process.env.AXE_PATH || require.resolve('axe-core/axe.min.js');
const src = fs.readFileSync(AXE, 'utf8');
const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
const pages = process.argv.slice(2);
(async () => {
  const b = await chromium.launch();
  let total = 0;
  for (const path of pages) for (const theme of ['blackboard', 'whiteboard']) for (const width of [375, 1440]) {
    const ctx = await b.newContext({ viewport: { width, height: 900 } });
    await ctx.addInitScript(t => { try { localStorage.setItem('vnl-theme', t); } catch (e) {} }, theme);
    const p = await ctx.newPage();
    await p.goto(BASE + path, { waitUntil: 'networkidle' });
    await p.addScriptTag({ content: src });
    const r = await p.evaluate(() => axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'best-practice'] }));
    total += r.violations.length;
    if (r.violations.length) {
      console.log(`${path} ${theme} ${width}: ${r.violations.length} violation(s)`);
      r.violations.forEach(v => console.log('   ', v.id, v.impact, v.nodes.length + 'x', v.nodes.slice(0, 3).map(n => n.target.join(' ')).join(' | ')));
    }
    await ctx.close();
  }
  await b.close();
  console.log(total ? `\n${total} violation group(s) found.` : `\naxe: 0 violations on ${pages.length} page(s), both themes, 375 and 1440 px.`);
  process.exit(total ? 1 : 0);
})();
