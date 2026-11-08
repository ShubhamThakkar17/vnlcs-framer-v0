// Builds every logo file from the school's logo (tools/brand/logo-source.png, grey line art on white).
// Usage: node tools/make-logo.js
//   assets/img/brand/logo-chalk-{64,128,192}.png         chalk-white line art on a transparent background (header, footer)
//   assets/img/brand/logo-512.png                         the original grey logo on white, square (Google's School logo)
//   assets/img/icon-{16,32,180,192,512}.png + /favicon.ico chalk logo on a blackboard square
//   tools/brand/logo-chalk-360.png                       for the Open Graph image (run tools/make-icons.js next)
const sharp = require('sharp');
const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');

const ROOT = path.join(__dirname, '..');
const SRC = path.join(__dirname, 'brand', 'logo-source.png');
const OUT = path.join(ROOT, 'assets', 'img', 'brand');
const IMG = path.join(ROOT, 'assets', 'img');
const CHALK = { r: 0xF3, g: 0xF0, b: 0xE8 };   // --ink on Blackboard
const BOARD = '#1E2B25';                        // --board

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  // 1. Trim the white margin, then turn darkness into opacity: white paper becomes transparent and the
  //    grey lines (about #707070) become solid. Anti-aliased edges keep their partial opacity.
  const trimmed = await sharp(SRC).trim({ threshold: 30 }).toBuffer();
  const { data, info } = await sharp(trimmed).greyscale().raw().toBuffer({ resolveWithObject: true });
  const { width: W, height: H } = info;
  const PAPER = 248, LINE = 120;
  const rgba = Buffer.alloc(W * H * 4);
  for (let i = 0; i < W * H; i++) {
    const a = Math.max(0, Math.min(255, Math.round((PAPER - data[i]) * 255 / (PAPER - LINE))));
    rgba[i * 4] = CHALK.r; rgba[i * 4 + 1] = CHALK.g; rgba[i * 4 + 2] = CHALK.b; rgba[i * 4 + 3] = a;
  }
  const chalk = sharp(rgba, { raw: { width: W, height: H, channels: 4 } });
  const chalkPng = await chalk.png().toBuffer();
  console.log(`logo trimmed to ${W}x${H}`);

  // 2. Header/footer sizes (by height: 1x, 2x, 3x of a 64 px mark). One colour plus transparency, so an
  //    indexed PNG is smaller than WebP or AVIF here.
  for (const h of [64, 128, 192]) {
    await sharp(chalkPng).resize({ height: h }).png({ compressionLevel: 9, palette: true, colours: 64 }).toFile(path.join(OUT, `logo-chalk-${h}.png`));
  }
  // A larger copy for the Open Graph image (tools/make-icons.js). Not deployed.
  await sharp(chalkPng).resize({ height: 360 }).png({ compressionLevel: 9 }).toFile(path.join(__dirname, 'brand', 'logo-chalk-360.png'));

  // 3. Square logo for search engines: the original colours on white.
  const sq = 512, inner = 440;
  const orig = await sharp(trimmed).resize({ width: inner, height: inner, fit: 'contain', background: '#ffffff' }).toBuffer();
  await sharp({ create: { width: sq, height: sq, channels: 3, background: '#ffffff' } })
    .composite([{ input: orig, gravity: 'center' }]).png({ compressionLevel: 9, palette: true, colours: 64 }).toFile(path.join(OUT, 'logo-512.png'));

  // 4. App and browser icons: chalk logo on a blackboard square.
  //    Small sizes get less padding so the mark stays as large as possible.
  for (const [size, pad] of [[16, .04], [32, .06], [48, .06], [180, .14], [192, .14], [512, .14]]) {
    const inner = Math.round(size * (1 - pad * 2));
    const mark = await sharp(chalkPng).resize({ width: inner, height: inner, fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } }).toBuffer();
    const file = size === 48 ? path.join(OUT, 'icon-48.png') : path.join(IMG, `icon-${size}.png`);
    await sharp({ create: { width: size, height: size, channels: 4, background: BOARD } })
      .composite([{ input: mark, gravity: 'center' }]).png({ compressionLevel: 9, palette: true, colours: 64 }).toFile(file);
  }
  // favicon.ico with 16, 32 and 48 px (ImageMagick), then drop the 48 px helper.
  execFileSync('convert', [path.join(IMG, 'icon-16.png'), path.join(IMG, 'icon-32.png'), path.join(OUT, 'icon-48.png'), path.join(ROOT, 'favicon.ico')]);
  fs.unlinkSync(path.join(OUT, 'icon-48.png'));
  console.log('logo, icons and favicon.ico written');
})();
