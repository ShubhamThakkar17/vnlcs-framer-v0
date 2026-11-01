<?php
// Mock graph.instagram.com for tools/test-instagram.sh. Tokens starting with "bad" fail like an expired token.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$token = $_GET['access_token'] ?? '';
$base = 'http://' . $_SERVER['HTTP_HOST'];
if (strpos($path, '/files/') === 0) {
    header('Content-Type: ' . (substr($path, -4) === '.mp4' ? 'video/mp4' : 'image/jpeg'));
    echo str_repeat('x', 2048);
    return;
}
header('Content-Type: application/json');
if (strpos($token, 'bad') === 0) {
    http_response_code(400);
    echo '{"error":{"message":"Error validating access token: Session has expired","type":"OAuthException","code":190}}';
    return;
}
if ($path === '/refresh_access_token') {
    echo json_encode(['access_token' => 'refreshed-' . $token, 'token_type' => 'bearer', 'expires_in' => 5184000]);
    return;
}
if ($path === '/me/media') {
    $acc = substr($token, -1);   // "good-a" / "good-b"
    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $video = $i % 3 !== 0;
        $data[] = [
            'id' => "mock{$acc}{$i}",
            'caption' => "Mock post $i from account $acc\nsecond line",
            'media_type' => $video ? 'VIDEO' : 'IMAGE',
            'media_product_type' => $video ? 'REELS' : 'FEED',
            'media_url' => $base . '/files/' . ($video ? "v$acc$i.mp4" : "i$acc$i.jpg"),
            'thumbnail_url' => $video ? $base . "/files/t$acc$i.jpg" : null,
            'permalink' => "https://www.instagram.com/reel/mock$acc$i/",
            'timestamp' => date('c', time() - ($i * 7200) - ($acc === 'a' ? 0 : 3600)),
        ];
    }
    echo json_encode(['data' => array_map(fn($d) => array_filter($d, fn($v) => $v !== null), $data)]);
    return;
}
http_response_code(404);
echo '{"error":{"message":"unknown path"}}';
