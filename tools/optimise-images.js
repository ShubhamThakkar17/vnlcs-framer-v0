// Optimise photos for the web with sharp.
// Put originals in assets/img/src/ (any sub-folders), then run:  cd tools && npm install && npm run images
// For each photo it writes assets/img/<same path>-480|960|1440.avif/.webp/.jpg (never wider than the
// original), strips all metadata (EXIF, including GPS location), and prints the PHP to use:
//   picture('campus/library', 'Children reading in the library', 1440, 960)
const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const SRC = path.join(__dirname, '..', 'assets', 'img', 'src');
const OUT = path.join(__dirname, '..', 'assets', 'img');
const WIDTHS = [480, 960, 1440];

function walk(dir) {
  if (!fs.existsSync(dir)) return [];
  return fs.readdirSync(dir, { withFileTypes: true }).flatMap(d =>
    d.isDirectory() ? walk(path.join(dir, d.name)) : /\.(jpe?g|png|webp|heic|tiff?)$/i.test(d.name) ? [path.join(dir, d.name)] : []);
}

(async () => {
  const files = walk(SRC);
  if (!files.length) { console.log('No images in assets/img/src/. Add originals there first.'); return; }
  let before = 0, after = 0;
  for (const file of files) {
    const rel = path.relative(SRC, file).replace(/\.[^.]+$/, '').replace(/[^a-zA-Z0-9/_-]+/g, '-').toLowerCase();
    const base = path.join(OUT, rel);
    fs.mkdirSync(path.dirname(base), { recursive: true });
    const img = sharp(file).rotate();             // apply EXIF orientation, then metadata is dropped
    const meta = await img.metadata();
    before += fs.statSync(file).size;
    const widths = WIDTHS.filter(w => w <= meta.width);
    if (!widths.length) widths.push(meta.width);
    for (const w of widths) {
      const pipeline = sharp(file).rotate().resize({ width: w, withoutEnlargement: true });
      const outs = [
        [`${base}-${w}.avif`, pipeline.clone().avif({ quality: 50, effort: 6 })],
        [`${base}-${w}.webp`, pipeline.clone().webp({ quality: 72 })],
        [`${base}-${w}.jpg`, pipeline.clone().jpeg({ quality: 76, mozjpeg: true, progressive: true })],
      ];
      for (const [out, p] of outs) { await p.toFile(out); after += fs.statSync(out).size; }
    }
    // Fallback name used by picture(): -960.jpg must exist
    if (!widths.includes(960)) fs.copyFileSync(`${base}-${widths[widths.length - 1]}.jpg`, `${base}-960.jpg`);
    const w = widths[widths.length - 1], h = Math.round(meta.height * w / meta.width);
    console.log(`${rel}: ${widths.join('/')} px  ->  picture('${rel}', '[ALT TEXT]', ${w}, ${h})`);
  }
  console.log(`\n${files.length} image(s): ${(before / 1024).toFixed(0)} KB originals -> ${(after / 1024).toFixed(0)} KB for all sizes and formats.`);
})();
