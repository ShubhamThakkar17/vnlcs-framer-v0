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
];
