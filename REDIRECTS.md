# Redirects from the old website

Every address from the old site answers with a **301 (moved permanently)** to its new page, so
Google keeps the rankings and old links in WhatsApp groups still work.

The same map lives in three places. Change all three together:

1. `.htaccess` (Apache / LiteSpeed on Hostinger: the live site)
2. `inc/redirects.php` (local preview and Vercel previews)
3. this file

Check the live rules with `tools/test-htaccess.sh https://veernewlookschool.com`.

| Old address | New address |
|---|---|
| `/index.html` | `/` |
| `/page3.html` | `/` |
| `/aboutus.html` | `/about/` |
| `/profile.html` | `/about/` |
| `/whynewlook.html` | `/about/#why` |
| `/directorsdesk.html` | `/about/#director` |
| `/principalsdesk.html` | `/about/#principal` |
| `/committee.html` | `/about/#committee` |
| `/staff.html` | `/about/#staff` |
| `/facilities.html` | `/facilities/` |
| `/academyoverview.html` | `/academics/` |
| `/syllabus.html` | `/academics/#curriculum` |
| `/examination_timetable.html` | `/notices/?cat=school` |
| `/transfercertificate.html` | `/cbse-disclosure/#tc` |
| `/importantlinks21.html` | `/notices/#official-links` |
| `/activities.html` | `/gallery/` |
| `/contactus.html` | `/admissions/#contact` |
| `/2026_pdf/AFFILIATION LETTER.pdf` | `/docs/affiliation-letter.pdf` |
| `/2026_pdf/APPENDIX -IX.pdf` | `/cbse-disclosure/` |
| `/2026_pdf/B  DOCUMENTS AND INFORMATION/2.jpeg` | `/docs/trust-registration.pdf` |
| `/2026_pdf/B  DOCUMENTS AND INFORMATION/3.pdf` | `/docs/noc.pdf` |
| `/2026_pdf/B  DOCUMENTS AND INFORMATION/5.pdf` | `/docs/building-safety.pdf` |
| `/2026_pdf/B  DOCUMENTS AND INFORMATION/6.pdf` | `/docs/fire-safety.pdf` |
| `/2026_pdf/B  DOCUMENTS AND INFORMATION/7.pdf` | `/docs/deo-certificate.pdf` |
| `/2026_pdf/B  DOCUMENTS AND INFORMATION/8.pdf` | `/docs/water-health-sanitation.pdf` |
| `/2026_pdf/BOARD RESULT.pdf` | `/docs/board-results.pdf` |
| `/2026_pdf/calendar_25_26.pdf` | `/docs/academic-calendar-2025-26.pdf` |
| `/2026_pdf/fees structure_25_26.pdf` | `/docs/fee-structure-2025-26.pdf` |
| `/2026_pdf/managing committee_25_26.pdf` | `/docs/smc-list.pdf` |
| `/2021-pdf/BUC CERTIFICATE.pdf` | `/docs/building-safety.pdf` |
| `/2021-pdf/PTA MEMBERS.pdf` | `/docs/pta-list.pdf` |
| `/2021-pdf/RECOGNISED CERTIFICATE.pdf` | `/docs/rte-recognition.pdf` |
| `/2026_pdf/<file>` (any other) | `/docs/<file>` |
| `/2021-pdf/<file>` | `/docs/<file>` |

## Old PDF links

Old PDFs were linked from folders like `/2026_pdf/...` and `/2021-pdf/...`. Copy those PDFs into
`/docs/` **with exactly the same file names** and every old link keeps working through the redirect.
The CBSE Disclosure page expects these names in `/docs/` (rename copies if needed):

`affiliation-letter.pdf`, `trust-registration.pdf`, `noc.pdf`, `rte-recognition.pdf`, `building-safety.pdf`,
`fire-safety.pdf`, `deo-certificate.pdf`, `water-health-sanitation.pdf`, `fee-structure-<session>.pdf`,
`academic-calendar-<session>.pdf`, `smc-list.pdf`, `pta-list.pdf`, `board-results.pdf`.

The documents the old site published for 2025-26 are already in `/docs/` under these names (Nov 2026),
and each old file name redirects to its new name (table above). `academic-calendar.pdf` (no session) is the
current year's calendar shown on the Academics page; upload it when the 2026-27 calendar is ready.

## Notes

- Section anchors (`#director`, `#staff` ...) point to the matching section of the new page.
- `examination_timetable.html` goes to the School Notices tab, where exam timetables are now posted.
- `transfercertificate.html` goes to the Transfer certificates section of the CBSE Disclosure page.