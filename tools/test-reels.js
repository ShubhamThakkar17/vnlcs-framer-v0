// Browser test of the reels component. Writes a temporary data/instagram.json with real
// (generated) videos, checks playback behaviour, then restores the previous state.
// Usage: node tools/test-reels.js /path/to/test.webm   (local server running)
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const ROOT = path.join(__dirname, '..');
const BASE = process.env.BASE_URL || 'http://127.0.0.1:8000';
const webm = process.argv[2];
const json = path.join(ROOT, 'data', 'instagram.json');
const media = path.join(ROOT, 'media', 'ig');
let fail = 0;
const check = (l, ok) => { console.log((ok ? '  pass  ' : '  FAIL  ') + l); if (!ok) fail++; };

const hadJson = fs.existsSync(json);
const backup = hadJson ? fs.readFileSync(json) : null;
const items = [];
for (let i = 1; i <= 6; i++) {
  fs.copyFileSync(webm, path.join(media, `testreel${i}.webm`));
  items.push({ id: `testreel${i}`, account: i % 2 ? 'vnlcs_halol' : 'veernewlookschoolhalol', media_type: 'VIDEO', product_type: 'REELS',
    caption: `Test reel ${i}\nsecond line hidden`, permalink: `https://www.instagram.com/reel/test${i}/`, timestamp: new Date(Date.now() - i * 3600e3).toISOString(),
    thumb: null, video: `/media/ig/testreel${i}.webm` });
}
fs.writeFileSync(json, JSON.stringify({ generated_at: new Date().toISOString(), sample: false, items }));

(async () => {
  const b = await chromium.launch({ args: ['--autoplay-policy=no-user-gesture-required'] });
  try {
    const page = await b.newPage({ viewport: { width: 1440, height: 900 } });
    await page.goto(BASE + '/');
    console.log('Reels component');
    check('6 phone frames with muted, looping, inline videos', await page.$$eval('.reels video', v => v.length === 6 && v.every(x => x.muted && x.loop && x.playsInline && x.preload === 'none')));
    check('only the first caption line is shown', (await page.textContent('.reel__caption')).startsWith('Test reel 1') && !(await page.textContent('.reel__caption')).includes('second line'));
    check('videos do not play while off screen', await page.$$eval('.reels video', v => v.every(x => x.paused)));
    await page.locator('.reels').scrollIntoViewIfNeeded();
    await page.waitForTimeout(1200);
    check('videos play when scrolled into view', await page.$$eval('.reels video', v => v.some(x => !x.paused)));
    await page.click('.reel__sound >> nth=0');
    check('tap unmutes one reel', await page.$eval('.reels video', v => !v.muted) && (await page.getAttribute('.reel__sound >> nth=0', 'aria-pressed')) === 'true');
    await page.click('.reel__sound >> nth=1');
    check('unmuting another re-mutes the first', await page.$$eval('.reels video', v => v[0].muted && !v[1].muted));
    await page.click('.reels-pause');
    await page.waitForTimeout(200);
    check('"Pause reels" pauses every video', await page.$$eval('.reels video', v => v.every(x => x.paused)) && (await page.textContent('.reels-pause')) === 'Play reels');
    await page.click('.reels-pause');
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.waitForTimeout(800);
    check('videos pause when scrolled away', await page.$$eval('.reels video', v => v.every(x => x.paused)));

    // Gallery wall account filter
    await page.goto(BASE + '/gallery/');
    await page.click('[data-reel-filter="vnlcs_halol"]');
    const vis = await page.$$eval('#reels-wall .reel', li => li.filter(x => !x.hidden).map(x => x.dataset.account));
    check('gallery filter shows only the chosen account', vis.length === 3 && vis.every(a => a === 'vnlcs_halol'));

    // Reduced motion: nothing plays by itself, native controls appear
    const rm = await b.newPage({ reducedMotion: 'reduce' });
    await rm.goto(BASE + '/');
    await rm.locator('.reels').scrollIntoViewIfNeeded();
    await rm.waitForTimeout(800);
    check('reduced motion: no autoplay, native controls shown', await rm.$$eval('.reels video', v => v.every(x => x.paused && x.controls)));

    // Behold normaliser (both feed shapes)
    const norm = await page.evaluate(() => {
      const v2 = { username: 'vnlcs_halol', posts: [{ id: '1', mediaType: 'VIDEO', isReel: true, mediaUrl: 'https://x/v.mp4', thumbnailUrl: 'https://x/t.jpg', permalink: 'https://instagram.com/p/1', prunedCaption: 'Hello\nworld', timestamp: '2026-10-01' }] };
      const v1 = [{ id: '2', mediaType: 'IMAGE', mediaUrl: 'https://x/i.jpg', permalink: 'p', caption: 'Pic', username: 'veernewlookschoolhalol' }];
      return [window.VNLReels.normaliseBehold(v2)[0], window.VNLReels.normaliseBehold(v1)[0]];
    });
    check('Behold feed (posts shape) normalised', norm[0].video === 'https://x/v.mp4' && norm[0].thumb === 'https://x/t.jpg' && norm[0].caption === 'Hello' && norm[0].account === 'vnlcs_halol');
    check('Behold feed (array shape) normalised', norm[1].video === null && norm[1].thumb === 'https://x/i.jpg' && norm[1].account === 'veernewlookschoolhalol');
  } finally {
    await b.close();
    if (backup) fs.writeFileSync(json, backup); else fs.unlinkSync(json);
    for (let i = 1; i <= 6; i++) fs.rmSync(path.join(media, `testreel${i}.webm`), { force: true });
  }
  // Missing feed: the sample file keeps the row from ever being empty
  const b2 = await chromium.launch();
  const p2 = await b2.newPage();
  await p2.goto(BASE + '/');
  check('no feed file: sample reels and Follow buttons still shown', (await p2.$$('.reels .reel')).length === 6 && (await p2.$$('.follow-btns a')).length === 2);
  await b2.close();
  console.log(fail ? `\n${fail} check(s) failed.` : '\nAll reels checks passed.');
  process.exit(fail ? 1 : 0);
})();
