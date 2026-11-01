# CLAUDE.md: Veer New Look Central School website

Read `BRIEF.md` for the full spec. This file holds the rules and tokens that must survive across sessions.

## Workflow
- Work in phases (BRIEF.md section 9). At the end of each phase: commit, start a local preview (`php -S localhost:8000` from the repo root), take Playwright screenshots at 375 px and 1440 px in both themes, list what was built, and **stop for review**.
- Develop on the branch you were given; never push elsewhere.

## Hard rules
1. Plain HTML, CSS, vanilla JS, plus small PHP 8 files. No React/Next/Tailwind/jQuery, no build step Hostinger cannot serve. The folder must run as uploaded to Hostinger shared hosting.
2. Bright Path and Kidora (Framer templates) are **visual references only**: layout, spacing, rhythm. Never copy their code, images, illustrations, icons or text. Redraw everything in our chalkboard style.
3. Never invent facts: no made-up stats, testimonials, ratings, awards, results, staff or quotes. Missing content gets a visible placeholder in square brackets, e.g. `[DIRECTOR'S MESSAGE]`, `[PHOTO: science lab]`, and is logged in `PLACEHOLDERS.md`.
4. Never commit secrets. Use `config/secrets.php` (gitignored) with a committed `config/secrets.example.php`.
5. Ask before adding any dependency over 15 KB, any paid service, or any third-party script.
6. Drop entirely: pricing plans, testimonials, star ratings, email newsletter signup, "Popular" badges, template credit badges.

## Architecture decision (Phase 0)
- Every public page is a `.php` file in a clean-URL folder (`/about/index.php`, etc.) that includes `partials/head.php`, `partials/header.php`, `partials/footer.php`. One source of truth for header/footer, no sync script, no build step. Pages that need no data are still plain markup with includes.
- Old `.html` URLs are 301-redirected via `.htaccess` (see `REDIRECTS.md`).

## Decisions log
- **Previews on Vercel (Phase 2):** `vercel.json` + `api/vercel.php` run the site through the community `vercel-php` runtime (approved by the client). `inc/router.php` is the shared front controller for Vercel and the local server; Hostinger uses `.htaccess` instead. Vercel's filesystem is read-only, so caches go to the temp dir there (`STORAGE` in `inc/bootstrap.php`).
- **Content fallback:** while `sanity.project_id` is empty, notices/newsletters/albums/settings come from `data/content.sample.json` (every item marked [SAMPLE]). Reels fall back to `data/instagram.sample.json`.
- **Phase 0 review (approved):** no sticky stacking programme cards (plain row/grid); keep all three moving strips, each in its own material: notice ticker = paper tape, keyword marquee = two rows of chalk on the board, tagline rail = wooden ledge. Type sizes in `reference/NOTES.md` are provisional; final tuning happens once the whole site is previewable.
- **Contrast adjustments (Phase 1, by `tools/contrast.js`):** Whiteboard `--accent-3` #2E7D32 → #23652A and `--accent-4` #EA580C → #B03C0A (both failed 4.5:1 on card fills). Added `--focus-paper` for focus rings on paper notes. All other brief tokens unchanged.
- **Cabin Sketch:** only the 400 file ships (51 KB, subset). Headings request weight 700 so browsers embolden it into solid chalk/marker strokes; the raw Regular cut is hatched and too busy on phones.
- **Chalk outlines:** drawn with CSS (wobbly `border-radius` on pseudo-elements + the `#chalk-line` SVG filter), not Rough.js. Zero JS, no layout shift. Rough Notation (4 KB gz, MIT, self-hosted in `assets/js/vendor/`) handles underlines/circles.
- **Marker theme** has no edge filters (`--heading-filter` and `--line-filter` are `none`): marker strokes are clean.
- **Fonts** are subset to Basic Latin + common punctuation + ₹ with `pyftsubset`.

## Client facts (do not alter)
- Veer New Look Central School, Halol ("Veer New Look" after first mention). CBSE, English medium, co-ed, Nursery to Class 12, Science (PCM, PCB) and Commerce. Founded 2005.
- Manibhadra Veer Education Trust; part of the New Look Group of Institutions (https://newlookschool.com/), credited in the footer.
- Godhra Road, beside HDFC Bank, Halol, Panchmahal, Gujarat 389350
- 02676 225955, +91 8000201153 · veer.newlook@gmail.com, veernewlookoffice@gmail.com
- Instagram: https://www.instagram.com/vnlcs_halol/ and https://www.instagram.com/veernewlookschoolhalol/
- Domain: veernewlookschool.com (Hostinger). Tagline: "Play, Learn & Grow Together!"
- Footer credit: "Website by KeshavCo" → https://keshavco.com

## Content rules
- Indian English, British spelling (programme, colour, organised). Warm, simple, parent-first, short sentences. **No em dashes.**
- Public-listing figures (about 1,340 students, 59 teachers, 46 classrooms, 8,130 m² campus) stay hidden behind `data-confirmed="false"` until the school confirms.
- Placeholder only until confirmed: hostel/boarding, swimming pool, transport routes, Class 11/12 streams, Director and Principal names.
- No children's full names beside photos. No stock photos of children. No pop-ups on load. No autoplay sound.
- Spell correctly: Committee, Director's Desk.
- Raw content lives in `/content/` (gitignored). Never fetch the old site at build time.

## Design system: the site is a school board
Two themes, one component set. `data-theme="blackboard|whiteboard"` on `<html>`, set by an inline `<head>` script before first paint; choice in `localStorage` inside try/catch; first visit = Blackboard. Header toggle drawn as a tiny board labelled "Blackboard / Whiteboard".

| Token | Blackboard | Whiteboard | Use |
|---|---|---|---|
| `--board` | `#1E2B25` + chalk-dust noise | `#FAFAF7` + faint erased-marker ghosts | Page background |
| `--frame` | `#6B4A2E` wood | `#B8BCC2` aluminium | Board frame, header/footer edges |
| `--ink` | `#F3F0E8` chalk white | `#1F2937` marker black | Body text |
| `--accent-1` | `#F6E27F` chalk yellow | `#1D4ED8` marker blue | Headings, primary CTA |
| `--accent-2` | `#F4A6B8` chalk pink | `#C62828` marker red | Highlights, "New" tags |
| `--accent-3` | `#9FD3F0` chalk blue | `#2E7D32` marker green | Links, secondary CTA |
| `--accent-4` | `#B5E3A1` chalk green | `#EA580C` marker orange | Icons, doodles |
| `--smudge` | `rgba(243,240,232,.08)` | `rgba(31,41,55,.06)` | Card fills, eraser smudges |

Every text/background pair must pass WCAG AA, verified by script in `/tools` (not by eye). Adjust shades if any pair fails, and record the change here.

### Type (self-hosted woff2, Latin subset, `font-display: swap`, preload the two above-the-fold files)
- Display H1/H2: **Cabin Sketch**
- H3, labels, buttons, captions, body: **Kalam** (18 px minimum on mobile)
- Tables, dates, circular numbers, form inputs: **Nunito**

### Effects
- Rough edges on display headings only (SVG `feTurbulence` + `feDisplacementMap`, low strength).
- Rough Notation (~3.8 KB) for underlines/circles/boxes, drawn once on scroll.
- Chalk-outlined cards with uneven corners (Rough.js or pre-drawn SVG borders).
- Notices = paper notes taped to the board at slight angles. Photos pinned/taped with thin chalk frame and handwritten caption.
- Our own chalk doodle SVGs: stars, arrows, sun, ABC, 1+1=2, beaker, globe, cricket bat.
- Footer chalk tray (duster + three chalk sticks); back-to-top plays a quick board "wipe".
- Buttons are chalk pills; hover thickens chalk and drops a little dust.
- One tiled noise texture < 30 KB. No heavy background images.
- All effects decorative and `aria-hidden`; text stays real text.
- `prefers-reduced-motion`: no drawing animations, no dust, marquee stops.
- Playful for pre-primary, calmer for senior school, CBSE and disclosure.

## Quality bars
- Lighthouse mobile ≥ 90 (all four) on Home, Notice Board, Gallery. LCP < 2.5 s, CLS < 0.1, Home < 500 KB before reels.
- axe clean on every page. WCAG 2.1 AA: chalk focus rings, skip link, keyboard, labels, pause controls for marquee and reels.
- Google Map and YouTube are click-to-load facades.
