<?php
// Mock Sanity CDN for tools/test-notices.php. Serves one published test notice.
header('Content-Type: application/json');
if (strpos($_SERVER['REQUEST_URI'], '/data/query/production') === false || strpos(urldecode($_SERVER['REQUEST_URI']), '"notices"') === false) {
    http_response_code(400);
    echo '{"error":"bad query"}';
    return;
}
echo json_encode(['result' => [
    'notices' => [[
        'id' => 'test-1', 'title' => 'TEST NOTICE: Sanity pipeline check', 'slug' => 'test-notice-pipeline',
        'category' => 'cbse', 'date' => date('Y-m-d'), 'summary' => 'Posted in Sanity, shown on the site.',
        'body' => [['_type' => 'block', '_key' => 'k1', 'style' => 'normal', 'markDefs' => [],
                    'children' => [['_type' => 'span', '_key' => 's1', 'text' => 'Body text from portable text.', 'marks' => ['strong']]]]],
        'attachment' => 'https://cdn.sanity.io/files/abc/production/test.pdf', 'externalLink' => 'https://www.cbse.gov.in/',
        'pinned' => false, 'expiryDate' => null,
    ]],
    'newsletters' => [], 'albums' => [],
    'settings' => ['sessionYear' => '2027-28', 'admissionBannerText' => '', 'tickerEnabled' => true, 'emergencyBanner' => ''],
]]);
