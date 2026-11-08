<?php
/**
 * Old site URLs -> new pages (301). Mirrors the rules in /.htaccess and REDIRECTS.md.
 * Used by inc/router.php (local preview and Vercel). Apache uses .htaccess directly.
 */
return [
    '/index.html'                 => '/',
    '/page3.html'                 => '/',
    '/aboutus.html'               => '/about/',
    '/profile.html'               => '/about/',
    '/whynewlook.html'            => '/about/#why',
    '/directorsdesk.html'         => '/about/#director',
    '/principalsdesk.html'        => '/about/#principal',
    '/committee.html'             => '/about/#committee',
    '/staff.html'                 => '/about/#staff',
    '/facilities.html'            => '/facilities/',
    '/academyoverview.html'       => '/academics/',
    '/syllabus.html'              => '/academics/#curriculum',
    '/examination_timetable.html' => '/notices/?cat=school',
    '/transfercertificate.html'   => '/cbse-disclosure/#tc',
    '/importantlinks21.html'      => '/notices/#official-links',
    '/activities.html'            => '/gallery/',
    '/contactus.html'             => '/admissions/#contact',
    // Old disclosure documents, renamed in /docs/ (2026_pdf and 2021-pdf on the old site)
    '/2026_pdf/AFFILIATION LETTER.pdf' => '/docs/affiliation-letter.pdf',
    '/2026_pdf/APPENDIX -IX.pdf' => '/cbse-disclosure/',
    '/2026_pdf/B  DOCUMENTS AND INFORMATION/2.jpeg' => '/docs/trust-registration.pdf',
    '/2026_pdf/B  DOCUMENTS AND INFORMATION/3.pdf' => '/docs/noc.pdf',
    '/2026_pdf/B  DOCUMENTS AND INFORMATION/5.pdf' => '/docs/building-safety.pdf',
    '/2026_pdf/B  DOCUMENTS AND INFORMATION/6.pdf' => '/docs/fire-safety.pdf',
    '/2026_pdf/B  DOCUMENTS AND INFORMATION/7.pdf' => '/docs/deo-certificate.pdf',
    '/2026_pdf/B  DOCUMENTS AND INFORMATION/8.pdf' => '/docs/water-health-sanitation.pdf',
    '/2026_pdf/BOARD RESULT.pdf' => '/docs/board-results.pdf',
    '/2026_pdf/calendar_25_26.pdf' => '/docs/academic-calendar-2025-26.pdf',
    '/2026_pdf/fees structure_25_26.pdf' => '/docs/fee-structure-2025-26.pdf',
    '/2026_pdf/managing committee_25_26.pdf' => '/docs/smc-list.pdf',
    '/2021-pdf/BUC CERTIFICATE.pdf' => '/docs/building-safety.pdf',
    '/2021-pdf/PTA MEMBERS.pdf' => '/docs/pta-list.pdf',
    '/2021-pdf/RECOGNISED CERTIFICATE.pdf' => '/docs/rte-recognition.pdf',
];
