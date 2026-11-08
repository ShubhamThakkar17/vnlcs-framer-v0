# Placeholders: what we still need from the school

Every gap on the site shows as a visible `[PLACEHOLDER]` in square brackets. Nothing has been invented.
When the school sends an item, replace the placeholder in the file listed and tick it off here.

## 1. People and messages

| Placeholder | Where it shows | File | Status |
|---|---|---|---|
| Director's name, photo and message | About: Leadership, Director's Desk | `about/index.php` | Open |
| Principal's name, photo, qualification and message | About; CBSE Disclosure A and D | `about/index.php`, `cbse-disclosure/index.php` | Open |
| A few lines about the Manibhadra Veer Education Trust | About: Our trust | `about/index.php` | Open |
| Trustee names (only if the school wants them listed) | About: Leadership | `about/index.php` | Open |
| Vision statement and mission statement | About: Vision and mission | `about/index.php` | Open |
| History milestones with years | About: Our journey | `about/index.php` | Open |
| School Management Committee: names and positions | About: Committee | `about/index.php` | Open |
| Staff by role (PGT, TGT, PRT, NTT, Librarian, PTI): names and subjects | About: Staff | `about/index.php` | Open |

## 2. Admissions and academics

| Placeholder | Where it shows | File | Status |
|---|---|---|---|
| Admission session, e.g. 2027-28 | Home hero banner, closing CTA, Admissions | **Sanity: Site settings** (no code change) | Open |
| Age criteria for Nursery, LKG, UKG, Class 1; criteria for Class 2-12 | Admissions: Eligibility; Academics age bands; FAQ | `admissions/index.php`, `academics/index.php`, `partials/faq.php` | Open |
| Documents required at admission | Admissions: What to bring | `admissions/index.php` | Open |
| Office hours | Admissions: Contact; FAQ | `admissions/index.php`, `partials/faq.php` | Open |
| Class 11 and 12 streams confirmed (Science PCM, PCB, Commerce) and optional subjects | Home stages card, Academics, FAQ, About timeline | `index.php`, `academics/index.php`, `partials/faq.php`, `about/index.php` | Open |
| Subjects and languages for Classes 1-5, 6-8, 9-10 | Academics | `academics/index.php` | Open |
| Textbooks / syllabus notes | Academics: Curriculum | `academics/index.php` | Open |
| Assessment pattern | Academics: Assessment | `academics/index.php` | Open |
| Academic calendar PDF | Academics; CBSE Disclosure C | upload `docs/academic-calendar.pdf` | Open |

## 3. Facilities and services (hidden or flagged until confirmed)

| Item | Current state | File | Status |
|---|---|---|---|
| Transport: routes, timings, fees | The school's bus photo shows buses exist, so a "School buses" facility card, the Why grid and the FAQ now say so. Routes, timings and fees stay as placeholders. **Please confirm the buses are Veer New Look's own** (they read "New Look Central School") | `facilities/index.php`, `partials/why.php`, `partials/faq.php` | Partly done |
| Hostel / boarding | Hidden (`data-confirmed="false"`) | `facilities/index.php` | Open |
| Swimming pool | Hidden (`data-confirmed="false"`) | `facilities/index.php` | Open |
| Public-listing figures: about 1,340 students, 59 teachers, 46 classrooms, 8,130 m² campus | Hidden. Set `'stats_confirmed' => true` once confirmed in writing | `inc/config.php`, `index.php` | Open |
| WhatsApp: is +91 80002 01153 on WhatsApp? | Used by the WhatsApp button and links | `inc/config.php` (`whatsapp`) | Open |
| YouTube channel | Icon shown only if set | `inc/config.php` (`youtube`) | Open |

## 4. CBSE Mandatory Public Disclosure

| Placeholder | File | Status |
|---|---|---|
| Affiliation number, school code | `cbse-disclosure/index.php` (`$general`) | Open |
| "Last updated" date | `cbse-disclosure/index.php` (`$lastUpdated`) | Open |
| 13 Appendix IX documents as PDFs (see names in `REDIRECTS.md`) | upload to `docs/` | Open |
| The brief mentions **16** disclosure PDFs; Appendix IX names 13 document rows. Please send the old site's full list so any extra documents get their own rows | `cbse-disclosure/index.php` | Open |
| Board results table (last three years, Class 10 and 12) | `cbse-disclosure/index.php` | Open |
| Staff numbers, teacher-section ratio, special educator, counsellor | `cbse-disclosure/index.php` (`$staff`) | Open |
| Infrastructure: campus area, classroom and lab sizes, internet, toilets, inspection video link | `cbse-disclosure/index.php` (`$infra`) | Open |
| Transfer certificate information | `cbse-disclosure/index.php` | Open |

## 5. Photos and media

| Placeholder | Where | How to fill | Status |
|---|---|---|---|
| Hero collage | Home | Done: school building, Raksha Bandhan, sports ground | Done |
| About strip, steps, facilities strip, gallery strip | Home | Done: library, school entrance, computer lab, 6 event and daily-life photos | Done |
| Facility photos | Facilities | Done for library, computer labs, science labs, sports ground, school buses. **Still needed:** AV room, air-conditioned classroom, Maths lab, Fine Arts lab, activity room, play area (splash pool and sandpit) | Partly done |
| Stage photos | Academics | Done for pre-primary, primary, middle, secondary, senior secondary. Class-specific photos for Nursery, LKG and UKG would go on the programme cards | Partly done |
| Leadership portraits | About | Director and Principal photos | Open |
| Staff photos | About | Done: two group photos of teachers at Business Junoon 2.0 | Done |
| School photo albums | Gallery | Done: "Business Junoon 2.0" (12), "Raksha Bandhan" (3), "Around the school" (10), from `inc/photos.php` | Done |
| Unused photo: architectural render of a two-storey glass building | Not used | Is this a planned building? Tell us where it belongs (it looks like a design render, so it is not shown as a real facility) | Open |
| Event albums and YouTube links | Gallery | **Sanity: Photo albums** (staff) | Open |
| Instagram reels | Home, Gallery | Sections are **commented out for now**. Re-enable in `index.php` and `gallery/index.php` once both accounts are Professional and tokens are in `config/secrets.php` | Hidden |

## 6. Accounts and keys (KeshavCo)

| Item | Where | Status |
|---|---|---|
| Sanity project ID | `inc/config.php` + `sanity/.env` | Open (sample notices shown until then) |
| Web3Forms access keys (one per school inbox) | `config/secrets.php` | Open (form offers email instead until then) |
| Instagram long-lived tokens for both accounts | `config/secrets.php` | Open |
| Alert email for token failures | `config/secrets.php` | Open |
| KeshavCo support phone / email for staff | `STAFF-GUIDE.md` | Open |

## 7. Sample content to remove at launch

- `data/content.sample.json`: used only while `sanity.project_id` is empty. Harmless to keep.
- `data/instagram.sample.json`: used only until the first cron run writes `data/instagram.json`.
- `styletile.html`: design reference; `noindex` and disallowed in `robots.txt`. Excluded from the deploy zip.
