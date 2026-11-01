#!/usr/bin/env node
// WCAG AA contrast check for both themes, read straight from assets/css/tokens.css.
// Usage: node tools/contrast.js     (exit code 1 if any pair fails)
//
// Text pairs need 4.5:1. Non-text pairs (outlines, icons, focus rings) need 3:1.
// Translucent tokens are composited over the background they sit on, and every
// text colour is also tested on the "worst case" board: smudge fill + chalk dust.

const fs = require('fs');
const path = require('path');

const css = fs.readFileSync(path.join(__dirname, '..', 'assets', 'css', 'tokens.css'), 'utf8')
  .replace(/\/\*[\s\S]*?\*\//g, '');

function block(selectorRe) {
  const m = css.match(new RegExp(selectorRe + '\\s*\\{([^}]*)\\}'));
  if (!m) throw new Error('Theme block not found: ' + selectorRe);
  const vars = {};
  for (const [, k, v] of m[1].matchAll(/--([\w-]+)\s*:\s*([^;]+);/g)) vars[k] = v.trim();
  return vars;
}

const themes = {
  blackboard: block(':root,\\s*\\[data-theme="blackboard"\\]'),
  whiteboard: block('\\[data-theme="whiteboard"\\]'),
};

function parse(c) {
  c = c.trim();
  let m;
  if ((m = c.match(/^#([0-9a-f]{6})$/i))) {
    const n = parseInt(m[1], 16);
    return { r: n >> 16, g: (n >> 8) & 255, b: n & 255, a: 1 };
  }
  if ((m = c.match(/^#([0-9a-f]{3})$/i))) {
    const [r, g, b] = m[1].split('').map(h => parseInt(h + h, 16));
    return { r, g, b, a: 1 };
  }
  if ((m = c.match(/^rgba?\(([^)]+)\)$/i))) {
    const p = m[1].split(/[\s,\/]+/).filter(Boolean).map(Number);
    return { r: p[0], g: p[1], b: p[2], a: p[3] === undefined ? 1 : p[3] };
  }
  throw new Error('Cannot parse colour: ' + c);
}
const over = (fg, bg) => ({
  r: fg.r * fg.a + bg.r * (1 - fg.a),
  g: fg.g * fg.a + bg.g * (1 - fg.a),
  b: fg.b * fg.a + bg.b * (1 - fg.a),
  a: 1,
});
const lum = c => {
  const f = v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
  return 0.2126 * f(c.r) + 0.7152 * f(c.g) + 0.0722 * f(c.b);
};
const ratio = (a, b) => { const x = lum(a), y = lum(b); return (Math.max(x, y) + 0.05) / (Math.min(x, y) + 0.05); };
const hex = c => '#' + [c.r, c.g, c.b].map(v => Math.round(v).toString(16).padStart(2, '0')).join('');

// [foreground token, background name, minimum, note]
const TEXT = 4.5, UI = 3;
const PAIRS = [
  ['ink', 'board', TEXT, 'body text'],
  ['ink', 'card', TEXT, 'body text on card'],
  ['ink', 'worst', TEXT, 'body text, smudge + dust'],
  ['ink', 'board-deep', TEXT, 'footer text'],
  ['ink-muted', 'board', TEXT, 'secondary text'],
  ['ink-muted', 'worst', TEXT, 'secondary text, smudge + dust'],
  ['accent-1', 'board', TEXT, 'headings'],
  ['accent-1', 'worst', TEXT, 'headings on card'],
  ['accent-2', 'board', TEXT, 'highlights'],
  ['accent-2', 'worst', TEXT, 'highlights on card'],
  ['accent-3', 'board', TEXT, 'links'],
  ['accent-3', 'worst', TEXT, 'links on card'],
  ['accent-4', 'board', TEXT, 'icons/doodles (held to text level)'],
  ['accent-4', 'worst', TEXT, 'icons on card'],
  ['on-accent', 'accent-1', TEXT, 'primary button label'],
  ['on-accent', 'accent-2', TEXT, '"New" tag'],
  ['on-accent', 'accent-3', TEXT, 'secondary filled chip'],
  ['frame-ink', 'frame', TEXT, 'top strip text on wood/aluminium'],
  ['frame-ink', 'frame-light', TEXT, 'top strip hover'],
  ['paper-ink', 'paper', TEXT, 'notice text'],
  ['paper-muted', 'paper', TEXT, 'notice date'],
  ['paper-accent', 'paper', TEXT, 'notice category'],
  ['line', 'board', UI, 'chalk outlines'],
  ['line', 'worst', UI, 'chalk outlines on card'],
  ['focus', 'board', UI, 'focus ring'],
  ['focus-paper', 'paper', UI, 'focus ring on a notice'],
];

let failures = 0;
for (const [name, t] of Object.entries(themes)) {
  const col = k => {
    if (!t[k]) throw new Error(`${name}: missing --${k}`);
    return parse(t[k]);
  };
  const board = col('board');
  const card = over(col('smudge'), board);
  const backgrounds = {
    board,
    card,
    worst: over(col('dust'), card),
    'board-deep': col('board-deep'),
  };
  const bgOf = k => backgrounds[k] || over(col(k), board);

  console.log(`\n${name.toUpperCase()}`);
  for (const [fgk, bgk, min, note] of PAIRS) {
    const bg = bgOf(bgk);
    const fg = over(col(fgk), bg);
    const r = ratio(fg, bg);
    const ok = r >= min;
    if (!ok) failures++;
    console.log(`${ok ? ' pass' : ' FAIL'}  ${r.toFixed(2).padStart(5)}:1  (min ${min})  --${fgk} ${hex(fg)} on ${bgk} ${hex(bg)}  · ${note}`);
  }
}

console.log(failures ? `\n${failures} pair(s) fail WCAG AA.` : '\nAll pairs pass WCAG AA in both themes.');
process.exit(failures ? 1 : 0);
