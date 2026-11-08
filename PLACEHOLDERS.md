# Placeholders: what we still need from the school

Every gap on the site shows as a visible `[PLACEHOLDER]` in square brackets. Nothing has been invented.
When the school sends an item, replace the placeholder in the file listed and tick it off here.

## 1. People and messages

| Placeholder | Where it shows | File | Status |
|---|---|---|---|
| Director's name, photo and message | About: Leadership, Director's Desk | `about/index.php` | Message done (from the old website, lightly edited). **Name and photo still needed** |
| Principal's name, photo, qualification and message | About; CBSE Disclosure A and D | `about/index.php`, `cbse-disclosure/index.php` | Message done (from the old website, lightly edited). **Name and qualification held back** (CLAUDE.md: names only once confirmed). The school's own Appendix IX gives "Madhu Chhajed, M.A., M.Ed": say yes and they go in |
| A few lines about the Manibhadra Veer Education Trust | About: Our trust | `about/index.php` | Partly done: "registered public trust in Panchmahal, registration valid for life" (from the DEO certificate). History and aims still needed |
| Trustee names (only if the school wants them listed) | About: Leadership | `about/index.php` | Open |
| Vision statement and mission statement | About: Vision and mission | `about/index.php` | Done: from the old website (Profile page), lightly edited for spelling. Please confirm |
| History milestones with years | About: Our journey | `about/index.php` | Partly done: 2013 NOC extension and 2022 CBSE affiliation up to Class 12 (from the school's documents). More milestones welcome |
| School Management Committee: names and positions | About: Committee | `about/index.php` | Open |
| Staff by role (PGT, TGT, PRT, NTT, Librarian, PTI): names and subjects | About: Staff | `about/index.php` | Open |

## 2. Admissions and academics

| Placeholder | Where it shows | File | Status |
|---|---|---|---|
| Admission session, e.g. 2027-28 | Home hero banner, closing CTA, Admissions | **Sanity: Site settings** (no code change) | Open |
| Age criteria for Nursery, LKG, UKG, Class 1 | Admissions: Eligibility; Academics age bands; FAQ | `admissions/index.php`, `academics/index.php`, `partials/faq.php` | Open. Class 2 to 10 (TC + admission test in English, Hindi, Maths, Science) and Class 11 (Class 10 result + interview) filled from the school's admission form and old Profile page: confirm for 2027-28 |
| Documents required at admission | Admissions: What to bring | `admissions/index.php` | Done from the school's admission form checklist (birth certificate, TC, migration certificate, mark sheet, two photographs). Confirm for 2027-28 |
| Office hours | Admissions: Contact; FAQ | `admissions/index.php`, `partials/faq.php` | Open |
| Class 11 and 12 streams and optional subjects | Home stages card, Academics, FAQ, About timeline | `index.php`, `academics/index.php`, `partials/faq.php`, `about/index.php` | Streams confirmed by the school's 2025-26 fee structure (11th and 12th Science and Commerce). Optional subjects and 2027-28 combinations still open |
| Subjects and languages for Classes 1-5, 6-8, 9-10 | Academics | `academics/index.php` | Open |
| Book list for 2026-27 | Academics: Curriculum | `academics/index.php` | Open. Curriculum text now explains CBSE's 2026-27 changes (Class 9 NCF-SE 2023 scheme and three languages, marks split). The old site's syllabus PDFs are from 2019-20 and were not reused |
| Assessment pattern | Academics: Assessment | `academics/index.php` | Done from the school's 2025-26 calendar (periodic tests, half-yearly, pre-boards, annual exams). Confirm it holds for 2026-27 |
| Academic calendar PDF for 2026-27 | Academics; CBSE Disclosure C | upload `docs/academic-calendar.pdf` | Open. The 2025-26 calendar is linked as last year's until then |

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
| Affiliation number, school code | `cbse-disclosure/index.php` (`$general`) | Done: 430235 and 10214 (affiliation letter and Appendix IX) |
| "Last updated" date | `cbse-disclosure/index.php` (`$lastUpdated`) | Set to 8 Nov 2026. Update whenever a document changes |
| 13 Appendix IX documents as PDFs | `docs/` | Done: the school's own 2025-26 documents from the old site. Fee structure and calendar are 2025-26: upload the 2026-27 ones as `fee-structure.pdf` and `academic-calendar.pdf`. Self-certification (row B7) shows the DEO certificate; the school's sheet says "pending" |
| The brief mentions **16** disclosure PDFs; Appendix IX names 13 document rows. Please send the old site's full list so any extra documents get their own rows | `cbse-disclosure/index.php` | Open |
| Board results table (last three years, Class 10 and 12) | `cbse-disclosure/index.php` | Done from the school's sheet. **Class 12, 2023-24: 13 of 18 passed is 72.22%, but the sheet says 89%.** Please check |
| Staff numbers, teacher-section ratio, special educator, counsellor | `cbse-disclosure/index.php` (`$staff`) | Done from Appendix IX. **PGT 9 + TGT 15 + PRT 28 = 52, but total teachers is 58.** Please explain the other 6 |
| Infrastructure | `cbse-disclosure/index.php` (`$infra`) | Done from Appendix IX (8,130 m², 46.45 m² classrooms, 55.74 m² labs, internet, 20 + 20 toilets, inspection video). Number of classrooms and labs still open |
| Transfer certificate information | `cbse-disclosure/index.php` | Open |

## 4a. Notices waiting in `data/notices.json`

These are ready in `data/notices.json` but not shown yet. Change `"status"` to `"published"` once done.

| Notice | What is needed | Status |
|---|---|---|
| School newsletter, [MONTH YEAR] | The newsletter PDF and a short intro (add it in Sanity under Newsletters) | draft |
| Board Exams 2027: what Class X and XII parents need to know | Pre-board exam dates, Principal's name | draft |
| Admissions open for [SESSION 2027-28] | Session, classes with seats, form dates, last date, office hours, streams | draft |
| CBSE Science Challenge: Round 1 for Classes VIII to X | Confirm the school is registered; Round 1 date and period; teacher in charge | draft |
| Janjatiya Gaurav Divas celebration | Date, activities, classes (event ends 15 Nov) | draft |
| Children's Day celebration | Date, activities, timing, dress (event ends 15 Nov) | draft |
| Celebration of six years of Nasha Mukt Bharat Abhiyaan (NMBA) (Acad-75/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/75_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |
| Aryabhata Ganit Challenge 2026 (Acad-74/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/74_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |
| National Startup Awards 6.0: Student Innovator and Aspiring Entrepreneur Award (Acad-73/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/73_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |
| CBSE Expression Series for the academic session 2026-27 (Acad-72/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/72_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |
| Celebration of Janjatiya Gaurav Divas, 1 to 15 November 2026 (Acad-71/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/71_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |
| CBSE Science Exhibition 2026-27 (Acad-69/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/69_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |
| Observance of Vigilance Awareness Week 2026 in schools (Acad-68/2026) | Open [the circular](https://cbseacademic.nic.in/web_material/Circulars/2026/68_Circular_2026.pdf) once from India and confirm it is this circular (cbseacademic.nic.in did not open from our build machine) | check |

## 4b. Things noticed in the school's documents (please act)

- **CBSE affiliation ends on 31 March 2027** (letter dated 6 Aug 2021, period 1 Apr 2022 to 31 Mar 2027). Renewal is due.
- **Fire safety certificate is valid to 24 Dec 2026.** Renewal is due next month.
- The old admission form prints school code 13200; the affiliation letter and Appendix IX say 10214. The site uses 10214.
- The old website lists issued Transfer Certificates as images named with children's full names. These were not copied. Decide how TCs should be shown (CBSE Disclosure > Transfer certificates).
- The old Staff page (2017) shows teachers' dates of birth. It was not copied.
- Public listing figures: the school's Appendix IX confirms the 8,130 m² campus and gives **58** teachers (listings say 59). "46 classrooms" looks like a misreading of the 46.45 m² classroom size. Student numbers are not in any school document. The home stats stay hidden.
- Old pages call the school a "full time residential boarding school" and mention a splash pool and sandpit. Hostel/boarding and swimming pool stay hidden until confirmed.

## 5. Photos and media

| Placeholder | Where | How to fill | Status |
|---|---|---|---|
| Hero collage | Home | Done: school building, Raksha Bandhan, sports ground | Done |
| About strip, steps, facilities strip, gallery strip | Home | Done: library, school entrance, computer lab, 6 event and daily-life photos | Done |
| Facility photos | Facilities | Done for library, computer labs, science labs, sports ground, school buses. **Still needed:** AV room, air-conditioned classroom, Maths lab, Fine Arts lab, activity room, play area (splash pool and sandpit) | Partly done |
| Stage photos | Academics | Done for pre-primary, primary, middle, secondary, senior secondary. Class-specific photos for Nursery, LKG and UKG would go on the programme cards | Partly done |
| School logo | Header, footer, favicons, app icons, share image, Google logo | Done: the school's book-and-quill logo. A vector (SVG/PDF/AI) copy from the designer would make the small favicons sharper | Done |
| Leadership portraits | About | Director and Principal photos | Open |
| Staff photos | About | Done: two group photos of teachers at Business Junoon 2.0 | Done |
| School photo albums | Gallery | Done: "Business Junoon 2.0" (12), "Raksha Bandhan" (3), "Around the school" (10), from `inc/photos.php` | Done |
| Unused photo: architectural render of a two-storey glass building | Not used | Is this a planned building? Tell us where it belongs (it looks like a design render, so it is not shown as a real facility) | Open |
| Event albums and YouTube links | Gallery | **Sanity: Photo albums** (staff) | Open |
| Instagram reels | Home, Gallery | Sections are **commented out for now**. Re-enable in `index.php` and `gallery/index.php` once both accounts are Professional and tokens are in `config/secrets.php` | Hidden |

## 6. Accounts and keys (KeshavCo)

| Item | Where | Status |
|---|---|---|
| Sanity project ID | `inc/config.php` + `sanity/.env` | Open (the site shows the published notices in `data/notices.json` until then; import them with `tools/notices-to-sanity.php`) |
| Web3Forms access keys (one per school inbox) | `config/secrets.php` | Open (form offers email instead until then) |
| Instagram long-lived tokens for both accounts | `config/secrets.php` | Open |
| Alert email for token failures | `config/secrets.php` | Open |
| KeshavCo support phone / email for staff | `STAFF-GUIDE.md` | Open |

## 7. Sample content to remove at launch

- `data/content.sample.json`: used only if `data/notices.json` is missing and `sanity.project_id` is empty. Harmless to keep.
- `data/instagram.sample.json`: used only until the first cron run writes `data/instagram.json`.
- `styletile.html`: design reference; `noindex` and disallowed in `robots.txt`. Excluded from the deploy zip.
