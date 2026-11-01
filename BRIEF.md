# Claude Code Build Prompt: Veer New Look Central School Website

> How to use: create an empty folder, save this file in it as `BRIEF.md`, open Claude Code in that folder and say:
> "Read BRIEF.md fully, then start Phase 0. Stop at the end of every phase for my review."

---

## 0. Your role and the rules of this project

You are building a production website in this folder. Work in phases (section 9). At the end of each phase: commit to git, start a local preview, list what you built, and stop for my review.

Hard rules:

1. Plain HTML, CSS and vanilla JavaScript, plus small PHP 8 files where this brief says so. No React, no Next.js, no Tailwind, no jQuery, no build step that Hostinger cannot serve. The output must run by uploading the folder to Hostinger shared hosting.
2. The two Framer templates in section 3 are **visual references only**. Study their layout, spacing and rhythm. Never copy their code, images, illustrations, icons or text. Redraw everything in our chalkboard style.
3. Never invent facts: no made-up statistics, testimonials, ratings, awards, results, staff or quotes. Where content is missing, put a visible placeholder in square brackets, such as `[DIRECTOR'S MESSAGE]` or `[PHOTO: science lab]`, and log it in `PLACEHOLDERS.md`.
4. Never write secrets (API tokens, passwords) into committed files. Use `config/secrets.php` (gitignored) with a committed `config/secrets.example.php`.
5. Ask me before adding any dependency over 15 KB, any paid service, or any third-party script.
6. At the start, create `CLAUDE.md` in this folder with these rules plus the design tokens from section 4, so they survive across sessions.

---

## 1. The client

- **Name:** Veer New Look Central School, Halol. Use "Veer New Look" after first mention.
- **Board:** CBSE-affiliated, English medium, co-educational. Nursery to Class 12, with Science (PCM, PCB) and Commerce. Founded 2005.
- **Trust:** Manibhadra Veer Education Trust. Part of the New Look Group of Institutions (https://newlookschool.com/). Credit this in the footer.
- **Address:** Godhra Road, beside HDFC Bank, Halol, Panchmahal, Gujarat 389350
- **Phones:** 02676 225955, +91 8000201153
- **Emails:** veer.newlook@gmail.com, veernewlookoffice@gmail.com
- **Instagram:** https://www.instagram.com/vnlcs_halol/ and https://www.instagram.com/veernewlookschoolhalol/
- **Domain:** veernewlookschool.com (hosted on Hostinger)
- **Tagline:** "Play, Learn & Grow Together!"

**Audiences, in priority order:** parents choosing a school (mobile, slow 4G), current parents checking notices, students, CBSE inspectors.

**The site's three jobs:**

1. Turn a visiting parent into an admission enquiry within two taps from any page.
2. Let school staff post CBSE circulars, school notices and the newsletter without a developer.
3. Show school life live, with reels from both Instagram accounts playing on the site.

---

## 2. Folder structure

```
/
├── index.html
├── about/index.html
├── academics/index.html
├── facilities/index.html
├── gallery/index.html
├── notices/index.php          (list, filters)
├── notices/view.php           (single notice, pretty URL via .htaccess)
├── admissions/index.html
├── cbse-disclosure/index.html
├── 404.html
├── partials/                  (header.php, footer.php for the PHP pages; keep HTML pages in sync with a small build script or duplicate carefully)
├── assets/css/                (tokens.css, base.css, components.css, pages.css)
├── assets/js/                 (theme.js, chalk.js, marquee.js, reels.js, notices.js, form.js)
├── assets/fonts/              (self-hosted woff2)
├── assets/img/                (optimised AVIF/WebP + fallbacks)
├── assets/doodles/            (hand-drawn chalk SVGs you create)
├── docs/                      (CBSE disclosure PDFs)
├── data/                      (instagram.json, cache files; not web-browsable except instagram.json)
├── media/ig/                  (cached Instagram thumbnails and videos)
├── cron/                      (instagram-fetch.php, token-refresh.php; deny web access via .htaccess)
├── config/                    (secrets.php gitignored, secrets.example.php)
├── sanity/                    (Sanity Studio project, deployed separately, not uploaded to Hostinger)
├── reference/                 (screenshots of the templates; gitignored, never uploaded)
├── content/                   (raw content from the school and the old site; gitignored)
├── .htaccess                  (HTTPS, redirects, pretty URLs, caching, security headers)
├── sitemap.xml, robots.txt
├── README.md, PLACEHOLDERS.md, REDIRECTS.md, STAFF-GUIDE.md
└── CLAUDE.md
```

If the header and footer must be shared by HTML and PHP pages, you may make every page `.php` with includes instead. Tell me which you chose and why.

---

## 3. Visual references: Bright Path (base) + Kidora (accents)

- **Primary:** https://brightpath-wbs.framer.website/
- **Accents:** https://kidora.framer.website/

**Phase 0 task:** use Playwright (`npx playwright`) to screenshot both homepages full-page at 375 px and 1440 px into `/reference/`. Then write `reference/NOTES.md` listing each section, its layout grid, spacing and interactions. Mixing Kidora's pieces in keeps the result from looking like one copied template.

| Pattern | Source | Our version |
|---|---|---|
| Headings broken into short stacked lines | Bright Path | Every H1/H2: "Play, / Learn & / Grow Together" |
| Hero: overlapping photo collage, doodles, two CTAs | Bright Path | Photos taped to the board. CTAs: "Enquire for Admission", "Take a Campus Tour" |
| Two-row scrolling keyword marquee | Bright Path | CBSE Affiliated · Nursery to Class 12 · Science & Commerce · Smart Classrooms · Labs · Transport · Sports · Since 2005 |
| "Pick a program" cards | Bright Path | Stages: Pre-primary, Primary, Middle, Secondary, Senior Secondary |
| Stats counters next to a photo stack | Bright Path | Only school-confirmed figures, hidden until then |
| "How it works" numbered steps | Bright Path | Admission in 3 steps: Enquire, Visit & Interact, Enrol |
| 4-item feature grid | Bright Path | Why Veer New Look: qualified teachers, labs and computer rooms, safe campus and transport, activities and sports |
| Mentor profile cards | Bright Path | Leadership: Director, Principal, Trust, in chalk portrait frames |
| Pill tags block | Bright Path | Facilities: Library, AV Room, Computer Labs, Science Labs, Activity Room, Play Area |
| FAQ accordion | Bright Path | Admissions FAQ as chalk Q and A |
| Final CTA with collage | Bright Path | "Admissions open for [SESSION]" |
| Looping text strip | Kidora | "Play, Learn & Grow Together!" between sections |
| Small eyebrow labels above headings | Kidora | Handwritten: "OUR SCHOOL", "NOTICE BOARD", "LIVE FROM INSTAGRAM" |
| Programme cards with age bands | Kidora | Pre-primary only: Nursery, LKG, UKG |
| "Little moments that shine" icon row | Kidora | Play-based learning, phonics, splash pool and sandpit, art and craft |
| Image strip under the closing CTA | Kidora | Gallery preview strip |

**Drop entirely:** pricing plans, testimonials and star ratings, email newsletter signup, "Popular" badges, template credit badges.

---

## 4. Design system: the whole site is a school board

Two themes, one set of components:

- **Blackboard (default on first visit):** slate board, chalk writing.
- **Whiteboard:** white board, dry-erase marker writing (chalk does not show on white, so strokes become marker).

The toggle sits in the header, drawn as a tiny board labelled "Blackboard / Whiteboard". Store the choice in `localStorage` inside try/catch. Set `data-theme` on `<html>` before first paint (inline script in `<head>`) to avoid a flash.

### Tokens (`assets/css/tokens.css`)

| Token | Blackboard | Whiteboard | Use |
|---|---|---|---|
| `--board` | `#1E2B25` + chalk-dust noise | `#FAFAF7` + faint erased-marker ghosts | Page background |
| `--frame` | `#6B4A2E` wood | `#B8BCC2` aluminium | Board frame, header and footer edges |
| `--ink` | `#F3F0E8` chalk white | `#1F2937` marker black | Body text |
| `--accent-1` | `#F6E27F` chalk yellow | `#1D4ED8` marker blue | Headings, primary CTA |
| `--accent-2` | `#F4A6B8` chalk pink | `#C62828` marker red | Highlights, "New" tags |
| `--accent-3` | `#9FD3F0` chalk blue | `#2E7D32` marker green | Links, secondary CTA |
| `--accent-4` | `#B5E3A1` chalk green | `#EA580C` marker orange | Icons, doodles |
| `--smudge` | `rgba(243,240,232,.08)` | `rgba(31,41,55,.06)` | Card fills, eraser smudges |

Check every text and background pair against WCAG AA with a script (for example a small Node contrast check in `/tools`), not by eye. Adjust shades if any pair fails.

### Type (self-hosted woff2, Latin subset, `font-display: swap`, preload the two above-the-fold files)

- **Display, H1/H2:** Cabin Sketch
- **Handwriting, H3, labels, buttons, captions, body:** Kalam, 18 px minimum on mobile
- **Dense data, tables, dates, circular numbers, form inputs:** Nunito

### Chalk and marker effects

- Rough edges on display headings only: SVG filter (`feTurbulence` + `feDisplacementMap`), low strength.
- Hand-drawn underlines, circles and boxes around key words and CTAs: Rough Notation (about 3.8 KB), drawn once on scroll.
- Chalk-outlined cards with uneven corners: Rough.js or pre-drawn SVG borders.
- Notices look like paper notes taped to the board at slight angles.
- Chalk doodles you draw as SVG: stars, arrows, sun, ABC, 1+1=2, beaker, globe, cricket bat, placed around section headings.
- Footer chalk tray with a duster and three chalk sticks. Back-to-top click plays a quick "wipe" of the board.
- Buttons are chalk-drawn pills. On hover the chalk thickens and a little dust drifts down.
- Photos are pinned or taped, with a thin chalk frame and a handwritten caption.

### Rules

- One noise texture under 30 KB, tiled. No heavy background images.
- All effects are decorative and `aria-hidden`. Text stays real text.
- `prefers-reduced-motion`: no drawing animations, no dust, marquee stops.
- Playful energy for pre-primary sections, calmer for senior school, CBSE and disclosure.

---

## 5. Pages

**Global:** a thin wooden top strip holds both phones, email, both Instagram icons and YouTube only if the school has a channel. The header menu holds Home, About, Academics, Facilities, Gallery, Notice Board, Admissions and an "Enquire Now" chalk button. On mobile there's a floating WhatsApp button and a sticky bottom bar with "Call" and "Enquire". The footer holds contact details, a map link, quick links, CBSE Disclosure, both Instagram accounts, the chalk tray, "Part of the New Look Group of Institutions" and a small "Website by KeshavCo" credit linking to https://keshavco.com.

**Home, in order:**

1. Hero
2. Notice ticker (latest 3, "New" tag for 7 days)
3. Keyword marquee
4. Stages cards
5. About strip with confirmed stats
6. Notice Board preview (3 taped notes: CBSE circular, school notice, latest newsletter)
7. Live from Instagram reels row
8. Admission in 3 steps
9. Why Veer New Look grid
10. Facilities pills with one photo
11. FAQ
12. Closing CTA with gallery strip
13. Footer

| Page | Contents |
|---|---|
| About | Trust, Director's message, Principal's message, Vision and Mission, history since 2005, staff list by role (PGT, TGT, PRT, NTT, Librarian, PTI) |
| Academics | Stages and classes, Science (PCM, PCB) and Commerce, CBSE curriculum, assessment, academic calendar PDF |
| Facilities | Library, AV room, AC classrooms, 2 computer labs, Physics/Chemistry/Biology/Maths/Fine Arts labs, activity room, play area with splash pool and sandpit. Hostel, swimming pool, transport only once confirmed |
| Gallery | Event albums, YouTube embeds loaded on click, full Instagram reels wall with a filter per account |
| Notice Board | Tabs: All, CBSE Circulars, School Notices, Newsletter, Events, Results. Each notice has its own URL, WhatsApp share and copy-link. "Official CBSE links" box: https://www.cbse.gov.in/ and https://cbseacademic.nic.in/ |
| Admissions & Contact | Process, eligibility, documents, enquiry form, Google Map loaded on click, office hours, phones, emails, WhatsApp |
| CBSE Disclosure | Appendix IX as a clean table of the 16 documents (PDF links), with "Last updated" date |
| 404 | A wiped board with a doodled arrow home |

---

## 6. Dynamic features

### 6a. Notices and newsletter: Sanity (free plan) + PHP

- Create a Sanity project in `/sanity` with these schemas:
  - `notice`: title, slug, category (CBSE Circular, School Notice, Event, Result, Holiday), date, summary (max 200), body (portable text), attachment (file, PDF), externalLink, pinned (boolean), expiryDate
  - `newsletter`: title, month, cover, intro, pdf, body (optional)
  - `album`: name, date, cover, photos (max 40), youtubeUrls
  - `siteSettings`: admissionBannerText, sessionYear, tickerEnabled, emergencyBanner
- Dataset is public (read-only CDN, no token in the browser).
- `notices/index.php` and `notices/view.php` fetch from the Sanity CDN with a GROQ query, cache the JSON to `/data/cache/` for 10 minutes, and render server-side so notices are indexable. If Sanity fails, serve the last cache.
- Home's ticker and preview read the same cache through a tiny `api/notices.php?limit=3` endpoint.
- Expired notices leave the board but stay in the archive, filterable by month and category.
- Do not scrape CBSE websites. Staff paste the circular link.
- Write `STAFF-GUIDE.md`: "How to post a notice in 2 minutes", with placeholders for screenshots.

### 6b. Instagram reels from both accounts

Both accounts must be Professional (Business or Creator). Personal accounts can no longer be read by any API.

- `cron/instagram-fetch.php` (hourly on Hostinger cron):
  - For each account, call the Instagram API with Instagram Login (`graph.instagram.com/me/media`). Fields: `id,caption,media_type,media_product_type,media_url,thumbnail_url,permalink,timestamp`. Limit 12.
  - Merge, sort by date, and tag each item with its account handle.
  - Download thumbnails, and videos for the latest 6 reels, to `/media/ig/`, because Instagram media URLs expire.
  - Write `/data/instagram.json` atomically (write to a temp file, then rename). Delete media older than 30 days.
- `cron/token-refresh.php` (daily): refresh any token older than 30 days (tokens last 60), and email KeshavCo on failure.
- Tokens live in `config/secrets.php`.
- `assets/js/reels.js`:
  - Home shows a row of 6 chalk-drawn phone frames. Each video is muted, looped and `playsinline`, plays when scrolled into view and pauses when it leaves (IntersectionObserver). Tap to unmute. Use `preload="none"` with the poster set to the thumbnail.
  - Show the caption's first line in Kalam and a small handle tag. Each item links to its Instagram post.
  - If the JSON is missing or stale, show the cached items and "Follow us on Instagram" buttons. Never show an empty box.
  - Make the data source switchable in one config line between our JSON and a Behold JSON feed (fallback: Behold Starter, $10/month), and normalise both shapes.
- Until real tokens exist, ship `data/instagram.sample.json` with clearly marked placeholder items.

### 6c. Enquiry form

- POST to Web3Forms (https://web3forms.com/), sending to both school emails. The access key goes in config, injected at deploy.
- Fields: parent name, phone (required, Indian mobile format), email, child's name, class applying for (Nursery to Class 12), message.
- Add a honeypot field, client-side validation with clear errors, and a chalk "Thank you" state.

---

## 7. Performance, accessibility, SEO

- **Lighthouse mobile:** 90 or more in all four categories on Home, Notice Board and Gallery. Run it with `npx lighthouse` against the local preview and save reports in `/reports/` (gitignored).
- LCP under 2.5 s, CLS under 0.1, under 500 KB on Home before reels load.
- Images: AVIF/WebP with `srcset` and explicit width/height, lazy below the fold. Write a `tools/optimise-images` script using sharp.
- Google Map and YouTube are click-to-load facades.
- **Accessibility:** WCAG 2.1 AA. Run `npx @axe-core/cli` on every page and fix all violations. Visible chalk focus rings, full keyboard use, skip link, alt text, form labels, a pause control for the marquee and reels.
- **SEO:**
  - Unique titles and meta descriptions, Open Graph images in the chalk style.
  - JSON-LD: `School` (address, phone, `parentOrganization` New Look Group), `BreadcrumbList`, `NewsArticle` per notice.
  - `sitemap.xml` (include notices, regenerated by a PHP script or cron), `robots.txt`.
- **`.htaccess`:**
  - Force HTTPS and the bare domain. Pretty URLs for notices. Long cache headers for `/assets/`.
  - Block web access to `/cron`, `/config`, `/data/cache`. Basic security headers.
  - 301 redirects from every old URL: `index.html`, `page3.html`, `aboutus.html`, `profile.html`, `whynewlook.html`, `directorsdesk.html`, `principalsdesk.html`, `committee.html`, `staff.html`, `facilities.html`, `academyoverview.html`, `syllabus.html`, `examination_timetable.html`, `transfercertificate.html`, `importantlinks21.html`, `activities.html`, `contactus.html`. Keep old PDF paths such as `/2026_pdf/...` and `/2021-pdf/...` working, or redirect them to `/docs/`. Document the map in `REDIRECTS.md`.

---

## 8. Content rules

- Indian English with British spelling (programme, colour, organised). Warm, simple, parent-first. Short sentences. No em dashes.
- Public-listing figures (about 1,340 students, 59 teachers, 46 classrooms, 8,130 m² campus) stay hidden behind a `data-confirmed="false"` flag until the school confirms them.
- Not confirmed yet, so placeholder only: boarding/hostel, swimming pool, transport routes, Class 11 and 12 streams, Director and Principal names.
- No children's full names beside photos. No stock photos of children. No pop-ups on load. No autoplay sound.
- Fix the old site's typos (Committee, Director's Desk).
- Raw content from the old site and the school goes in `/content/`. Read it from there. Don't fetch from the old site at build time.

---

## 9. Phases (stop after each)

| Phase | Build | Done when |
|---|---|---|
| 0 | `CLAUDE.md`, git init, folder skeleton, reference screenshots and `reference/NOTES.md` | I approve the notes |
| 1 | Style tile page (`/styletile.html`) in both themes: tokens, type, button, card, taped notice, reel frame, 6 doodles, toggle | Contrast script passes; I approve the look |
| 2 | Home in both themes, at 375 px and 1440 px, with placeholder content and sample JSON | Screenshots at both widths in both themes; Lighthouse ≥ 90 |
| 3 | About, Academics, Facilities, Gallery, Admissions, CBSE Disclosure, 404 | axe clean on every page |
| 4 | Sanity schemas and Studio, PHP notice pages and cache, enquiry form | A test notice shows on the site |
| 5 | Instagram cron, token refresh, reels component, Behold switch | Sample JSON and a real-token dry run work |
| 6 | `.htaccess`, redirects, sitemap, JSON-LD, image optimisation, full QA | Acceptance checklist below all ticked |
| 7 | Deploy pack: `dist/` zip for Hostinger, README with upload steps, cron commands, Sanity deploy, secrets setup | I can deploy by following the README alone |

For local preview, run `php -S localhost:8000` from the project root and give me the URL. Take Playwright screenshots of what you built at 375 px and 1440 px in both themes, and show them to me at each stop.

---

## 10. Acceptance checklist

- [ ] Both themes on every page, the choice is remembered, and the first visit opens on Blackboard, with no flash
- [ ] WCAG AA contrast passes in both themes, checked by script
- [ ] Lighthouse mobile ≥ 90 on Home, Notice Board, Gallery
- [ ] axe reports zero violations on every page
- [ ] Enquiry form reaches both school inboxes, and the honeypot blocks bots
- [ ] A notice posted in Sanity appears within 10 minutes, with its own URL and WhatsApp share
- [ ] Reels from both accounts play muted inline and fall back gracefully when the feed fails
- [ ] All 16 disclosure PDFs open from the CBSE page
- [ ] Every old URL 301-redirects correctly
- [ ] No invented content, and every gap is listed in `PLACEHOLDERS.md`
- [ ] Nothing copied from Bright Path or Kidora: no code, images, icons, illustrations or text
- [ ] Reduced-motion mode removes all animation
- [ ] Tested on Chrome Android, Safari iOS, desktop Chrome and Edge
