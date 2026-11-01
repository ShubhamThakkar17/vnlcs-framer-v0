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
| `/2026_pdf/<file>` | `/docs/<file>` |
| `/2021-pdf/<file>` | `/docs/<file>` |

## Old PDF links

Old PDFs were linked from folders like `/2026_pdf/...` and `/2021-pdf/...`. Copy those PDFs into
`/docs/` **with exactly the same file names** and every old link keeps working through the redirect.
The CBSE Disclosure page expects these names in `/docs/` (rename copies if needed):

`affiliation-letter.pdf`, `trust-registration.pdf`, `noc.pdf`, `rte-recognition.pdf`, `building-safety.pdf`,
`fire-safety.pdf`, `deo-certificate.pdf`, `water-health-sanitation.pdf`, `fee-structure.pdf`,
`academic-calendar.pdf`, `smc-list.pdf`, `pta-list.pdf`, `board-results.pdf`.

## Notes

- Section anchors (`#director`, `#staff` ...) point to the matching section of the new page.
- `examination_timetable.html` goes to the School Notices tab, where exam timetables are now posted.
- `transfercertificate.html` goes to the Transfer certificates section of the CBSE Disclosure page.