# Reference notes: Bright Path (base) + Kidora (accents)

Captured 1 November 2026 with `node tools/reference-shots.js`. The screenshots in this folder are gitignored and never uploaded; this file is our own analysis.

- `brightpath-375.png`, `brightpath-1440.png`, `kidora-375.png`, `kidora-1440.png`: full-page captures
- `frames/`: viewport-by-viewport captures. Use these for Kidora, because some of its scroll-reveal sections reset when the full-page capture resizes the viewport.

Measurements come from computed styles in the live pages, so they are exact, not guessed from pixels.

**Ground rule:** we borrow layout, spacing and rhythm only. No code, images, illustrations, icons, fonts or copy from either template. Every graphic gets redrawn as chalk/marker.

---

## 1. Bright Path: global system

| Property | 1440 px | 375 px |
|---|---|---|
| Content width | 1380 px (30 px side gutters); the stats section narrows to 1080 px | 335 px (20 px gutters) |
| Section padding (top/bottom) | 150 / 150 px | 80 / 80 px |
| Gap inside sections | 30 px | 30 px |
| H1 | 103 / 103 px, weight 700, tracking about -6% | 50 / 50 px |
| H2 (section titles) | 82 / 90 px, weight 600, tracking about -8% | 40 / 44 px |
| Closing CTA H2 | 98 / 108 px | 42 / 46 px |
| Card titles (H3) | 30 / 36 px | 22 / 26 px |
| Small feature titles | 20 / 24 px | 18 / 22 px |
| Body | 16 px sans | 16 px |

Visual habits worth noting:

- **Word-split headings.** Each H1/H2 is broken word by word, so words can wrap onto short stacked lines and animate in one at a time. One key word per heading is set in a second colour with a hand-drawn swash underline and three little "spark" ticks at its top right.
- **Section colour blocking.** Sections alternate between the page base and a full-bleed tinted band. Two bands have a **scalloped "cloud" top edge**.
- **Tilted cards.** Step cards and quote cards sit at roughly ±2–4° rotation, so the page feels hand-placed.
- **Pill buttons with a solid offset shadow** below them (a "pressable" look). Primary is filled, secondary is outlined.
- **Header:** logo left, 5 links centred (active link underlined in the accent colour), filled pill CTA right. On mobile: logo plus a round menu button.
- **Footer:** dark band, 3 columns (brand and contact / page links in 2 sub-columns / newsletter), a bar below with credits, socials and legal, and a scalloped bottom edge.

## 2. Bright Path: sections in order

| # | Section | Layout at 1440 | Layout at 375 | Interactions |
|---|---|---|---|---|
| 1 | **Hero** (h 1200) | Centred: small pill badge, then a 2-line H1 with the swash word, then 2 CTAs side by side. Below, 3 photos at different sizes and tilts (small left, large centre, small right) threaded by **one wavy ribbon carrying scrolling keywords**. Small sticker-like shapes overlap photo corners. | Same stack. H1 wraps to 3 lines, CTAs stack full-width-ish, the collage crops to 1 big photo plus 2 small ones, and the ribbon runs off the edges. | Text on the ribbon scrolls continuously along the curve. Photos fade/slide in. |
| 2 | **Pick a program** (tinted band, cloud top edge) | Title left, pill button right on the same baseline. Below, a 4-column card row (about 330 px each, 30 px gap). Each card has a pastel fill, an icon tile, a title, 2 lines of text and a photo in the bottom half. | Title, then a button, then cards stacked one per row. | Hover lift on cards. |
| 3 | **Stats / Counter** | Centred 3-line H3 statement (last line in accent colour) and a small outline button. Below, a **dark stats card overlapping a tilted photo** (card left, photo right, about 60 px overlap). The card holds 3 rows: big number + label, split by hairlines, with a pill footer line. | Statement, then a stats card at full width, then the photo under it. | Numbers count up on view. |
| 4 | **How it works** | 2 columns. Left: H2, a 2-line intro, a photo. Right: 3 stacked step cards, each with a numbered square tile (01/02/03), a title, an italic timing note and 2 lines of text, slightly rotated. Below the columns, a full-width pastel strip of **4 checkmark features** (4 columns). | Single column: H2, intro, photo, steps, then the 4 features stacked. | Step cards tilt and settle on scroll. |
| 5 | **Meet the mentors** (blue band) | Centred H2. 4 portrait cards in a row: each a coloured card with name, subject, photo and a 2-line bio. A text link below. | Cards stacked. | None notable. |
| 6 | **Levels** | Eyebrow pill + H2 left, short paragraph right. A 2×2 grid of wide row-cards: icon tile, title + 1 line, round arrow button at the far right. | Single column. | Arrow buttons hover. |
| 7 | **Learn where you learn best** (green band) | 2 columns: photo left (about 40%), right side has an eyebrow pill, a 3-line H2, a paragraph, a **row of pill tags**, and a CTA. | Photo, then text. | None notable. |
| 8 | Testimonials | 3 tilted quote cards with a star sticker on top and a rating pill. | Stacked. | **Dropped for our site.** |
| 9 | Pricing | 3 plan cards, the middle one dark with a "Popular" badge. | Stacked. | **Dropped for our site.** |
| 10 | **FAQ** | Centred H2. A narrow centred column (about 700 px) of accordion rows: white rounded rows, question left, square +/− icon right. The first row is open. | Full width. | Accordion opens/closes one row. |
| 11 | **Closing CTA** (tinted band) | Centred pill badge, 2-line H2 with swash word, 2-line paragraph, 2 CTAs. Cut-out people images either side and decorative blob shapes behind. | Centred stack, side images hidden or cropped. | None notable. |
| 12 | Footer | See global. | Columns stack. | Newsletter signup **dropped**. |

**Marquee note:** Bright Path has **one** curved keyword ribbon inside the hero, not a two-row straight marquee. The brief's "two-row keyword marquee" will be our own design: two straight rows of chalk-written keywords moving in opposite directions, placed as its own section after the notice ticker. The curved ribbon idea is not used.

## 3. Kidora: global system

| Property | 1440 px | 375 px |
|---|---|---|
| Content width | 1300 px (70 px gutters) | 335 px (20 px gutters) |
| Section padding | 120 / 120 px (60 px where 2 sections join) | 80 / 80 px (40 px at joins) |
| H1 | 72 / 79 px, weight 700 | 48 / 53 px |
| H2 | 56 / 64 px, weight 700 | 38 / 44 px |
| Ticker text | 30 / 40 px, weight 600 | 22 / 30 px |
| Card titles | 30 px / 24 px | 22 px |
| Body | 16 px | 16 px |

Visual habits:

- **Eyebrow labels:** a small 4-point star glyph followed by an UPPERCASE 12–13 px label above every H2 ("ABOUT US", "WHY CHOOSE US", "OUR PROGRAMS").
- **Hand-drawn loop:** one word per heading is circled by a thin, loose oval stroke (in place of Bright Path's underline swash).
- **Ticker strip:** full-bleed mint band, 81 px tall (70 px on mobile), with one repeated phrase and asterisk separators. It scrolls continuously. It appears **twice** on the page, both times as a divider straight after a full-bleed photo section.
- A thin curved decorative line meanders behind several sections and links cards together.
- Dark pill CTAs with a round arrow chip on the right.

## 4. Kidora: sections in order

| # | Section | Layout | Interactions |
|---|---|---|---|
| 1 | Header | Transparent over the hero (30% black). Logo left, links centred, pill CTA right. Mobile: a solid cream bar with a hamburger. | None notable. |
| 2 | Hero (h 900, top padding 400) | Full-bleed photo with a dark overlay. H1 and short text bottom-left, one CTA. | None notable. |
| 3 | **Ticker strip** | See above. | Infinite horizontal scroll. |
| 4 | About | Eyebrow left, big 4-line statement right (with small icon chips inline). A **4-column stats row with vertical dividers** (number over label). Below, 3 overlapping cards: pastel card, photo, pastel card. | Counters animate. |
| 5 | Why choose us | Photo left (about 45%), right side has eyebrow, H2, paragraph, then 2 mini-features split by a divider with a small asterisk node, and a CTA. Below, a "Guided by care" block: text left, a horizontally scrolling row of 3 pastel cards on the right. | Horizontal card scroll. |
| 6 | Presentation | Full-bleed photo with a centred eyebrow and H2, followed by the ticker again. | None notable. |
| 7 | **Programs** | Eyebrow + H2 left, CTA right. Then **sticky stacking cards**: each wide card (number badge 01/02/03, title, 3-line text, **age-band pill** such as "1–2 YEARS OLD", "View details" link, photo on the right) pins, and the next card slides up over it. | Scroll-driven stacking. |
| 8 | **Little moments that shine** | Left column: eyebrow, H2, paragraph, CTA. Right side: 4 small cards (asterisk icon, title, 4 lines) scattered in a staggered 2-column layout linked by a curved line, with one photo. | Cards fade in on scroll. |
| 9 | Testimonials | Horizontal carousel of photo + quote cards. | **Dropped for our site.** |
| 10 | Pricing | Monthly/Yearly toggle with 2 plans. | **Dropped for our site.** |
| 11 | Blog | Centred eyebrow + H2, 3 image cards with author/date and title. | (Pattern noted for the Notice Board preview, but we use taped notes, not cards.) |
| 12 | Closing CTA | Rounded pastel panel with a centred H2, text and CTA, small circular avatar photos floating around the edges. | Avatars bob gently. |
| 13 | Footer | Black. Brand + text + email signup left, socials centre, 2 link columns right, credit line. | Signup **dropped**. |

**Image-strip note:** Kidora has no literal image strip under its closing CTA. The nearest things are the floating avatar photos around the CTA panel and the 3-card row just above it. Our "gallery strip under the closing CTA" will be our own: a single row of 5–6 taped photos at alternating tilts, overlapping the bottom edge of the CTA board.

---

## 5. What we take, and how it becomes chalk

| Pattern | From | Our chalkboard version |
|---|---|---|
| Word-split stacked headings, one highlighted word | BP | Cabin Sketch H1/H2 on 2–3 short lines; the key word in `--accent-1` with a Rough Notation underline (or Kidora-style circle) drawn once on scroll, plus our own chalk tick marks. |
| Section rhythm: 150/80 px padding, 30 px gaps | BP | `--space-section: clamp(80px, 10vw, 144px)`, `--gap: 30px` (24 px on mobile). Container 1280 px max, 20 px mobile gutters. Wider than Kidora, a little narrower than BP, because Kalam lines get long at 1380. |
| Tinted full-bleed bands with scalloped edges | BP | Bands become a **smudged eraser area** (`--smudge` fill) with a rough, chalk-drawn wavy edge in SVG instead of clouds. |
| Tilted cards ±2–4° | BP | Taped notices and photos at -3° to +3°, with a tape strip SVG. Static tilt stays under reduced motion, because it is not animation. |
| Offset-shadow pill buttons | BP | Chalk-outlined pill; a second rough stroke offset 3 px replaces the shadow; it thickens on hover and drops dust. |
| Hero collage with 2 CTAs | BP | 3 photos taped to the board (big centre, 2 small), our own doodles at corners (star, sun, ABC). CTAs: "Enquire for Admission", "Take a Campus Tour". |
| 4-col programme cards with photo | BP | 5 stage cards (Pre-primary to Senior Secondary): 5 columns at 1440 or 3+2, with a scroll-snap row on mobile. Chalk-outlined, no pastel fills. |
| Stats card over tilted photo | BP | Kept, but **hidden until figures are confirmed** (`data-confirmed="false"`). Without stats, the slot shows the about statement + photo only. |
| 3 numbered steps + 4-item strip | BP | "Admission in 3 steps" (Enquire, Visit & Interact, Enrol) as chalk-numbered notes; the 4-item check strip becomes the "Why Veer New Look" grid. |
| Portrait cards | BP | Leadership (Director, Principal, Trust) in chalk-drawn frames with `[PHOTO]` and `[NAME]` placeholders. |
| Pill tags beside a photo | BP | Facilities pills written in chalk boxes beside one taped photo. |
| FAQ accordion, narrow centred column | BP | Chalk Q and A, about 760 px wide. "Q." and "A." in Kalam, +/− drawn as a chalk cross. |
| Ticker strip between sections | Kidora | "Play, Learn & Grow Together!" written in chalk on a wooden-rail strip (`--frame`), between full-width sections. Pause button; stops under reduced motion. |
| Eyebrow labels | Kidora | Handwritten Kalam labels with a tiny chalk star: "OUR SCHOOL", "NOTICE BOARD", "LIVE FROM INSTAGRAM". |
| Programme cards with age bands | Kidora | Pre-primary only (Nursery, LKG, UKG) with age-band chalk pills. **Plain stacked cards, no sticky stacking**: it is heavy on slow phones and awkward for keyboard users. |
| 4 small feature cards linked by a curved line | Kidora | "Little moments that shine": play-based learning, phonics, splash pool and sandpit, art and craft. Linked by a dashed chalk arrow path. |
| Image strip under closing CTA | Kidora (loosely) | Taped gallery strip; see note above. |

**Dropped:** pricing, testimonials, ratings, newsletter signup, "Popular" badges, template credit badges, full-bleed dark photo heroes (they would hide the board), sticky stacking cards.

## 6. Type scale proposal (for Phase 1 to confirm)

Cabin Sketch is wider than both templates' fonts, so sizes come down about 10%:

| Role | Desktop | Mobile |
|---|---|---|
| H1 (Cabin Sketch) | 92 / 96 px | 46 / 50 px |
| H2 (Cabin Sketch) | 68 / 76 px | 38 / 44 px |
| H3 (Kalam 700) | 28 / 34 px | 22 / 28 px |
| Body (Kalam 400) | 20 / 32 px | 18 / 29 px (brief minimum) |
| Eyebrow (Kalam) | 16 px, letter-spaced | 15 px |
| Data (Nunito) | 16 px | 16 px |

## 7. Open questions for you

1. **Programme stacking:** OK to skip Kidora's sticky stacking cards in favour of a plain row (reasons above)?
2. **Marquee position:** the brief lists both a notice ticker (home item 2) and a keyword marquee (item 3) back to back, plus the Kidora tagline strip "between sections". I plan to make the tagline strip and the keyword marquee look clearly different (wood rail vs. two chalk rows) so three moving strips don't blur together. Alternatively, should the tagline strip appear only once, before the closing CTA?
3. **Page format:** I chose all-`.php` pages with shared includes (see CLAUDE.md). Clean URLs stay `/about/` etc.
