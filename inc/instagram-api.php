<?php
/**
 * Instagram API with Instagram Login: shared by cron/instagram-fetch.php and cron/token-refresh.php.
 * Tokens start in config/secrets.php ('instagram_tokens'); refreshed tokens are saved to
 * STORAGE/tokens.json (web-blocked, gitignored) and take priority from then on.
 */
declare(strict_types=1);

function ig_api_base(): string
{
    return rtrim((string) (cli_option('api-base') ?: 'https://graph.instagram.com'), '/');
}

/** All accounts with their current token: ['handle' => ['token' => ..., 'refreshed_at' => int|null]] */
function ig_tokens(): array
{
    $stored = is_file(STORAGE . '/tokens.json') ? (json_decode((string) file_get_contents(STORAGE . '/tokens.json'), true) ?: []) : [];
    $out = [];
    foreach ((array) secret('instagram_tokens', []) as $handle => $token) {
        $rec = $stored[$handle] ?? null;
        if ($rec && !empty($rec['token'])) {
            $out[$handle] = ['token' => (string) $rec['token'], 'refreshed_at' => (int) ($rec['refreshed_at'] ?? 0) ?: null];
        } elseif ($token) {
            $out[$handle] = ['token' => (string) $token, 'refreshed_at' => null];
        }
    }
    return $out;
}

function ig_save_token(string $handle, string $token, int $expiresIn): void
{
    $file = STORAGE . '/tokens.json';
    $all = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    $all[$handle] = ['token' => $token, 'refreshed_at' => time(), 'expires_at' => time() + $expiresIn];
    write_atomic($file, json_encode($all, JSON_PRETTY_PRINT));
    @chmod($file, 0600);
}

/** GET JSON from the Graph API. Returns [array|null $json, string $error]. Never logs the token. */
function ig_get(string $path, array $params): array
{
    $url = ig_api_base() . $path . '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20, CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_USERAGENT => 'VeerNewLookWebsite/1.0']);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($body === false) {
        return [null, 'network error: ' . $err];
    }
    $json = json_decode((string) $body, true);
    if ($code < 200 || $code >= 300 || !is_array($json)) {
        $msg = $json['error']['message'] ?? ('HTTP ' . $code);
        return [null, (string) $msg];
    }
    return [$json, ''];
}

/** Download a remote file to $dest (atomic). Skips if it already exists. */
function ig_download(string $url, string $dest, int $maxBytes): bool
{
    if (is_file($dest) && filesize($dest) > 0) {
        touch($dest);
        return true;
    }
    $tmp = $dest . '.part';
    $fp = fopen($tmp, 'wb');
    if (!$fp) {
        return false;
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp, CURLOPT_FOLLOWLOCATION => true, CURLOPT_TIMEOUT => 120, CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_NOPROGRESS => false,
        CURLOPT_PROGRESSFUNCTION => fn($c, $dlTotal, $dl) => $dl > $maxBytes ? 1 : 0,   // abort huge files
    ]);
    $ok = curl_exec($ch) !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
    curl_close($ch);
    fclose($fp);
    if (!$ok || filesize($tmp) === 0) {
        @unlink($tmp);
        return false;
    }
    return rename($tmp, $dest);
}

/** --name=value or --flag from the command line. */
function cli_option(string $name)
{
    static $opts = null;
    if ($opts === null) {
        $opts = [];
        foreach (array_slice($GLOBALS['argv'] ?? [], 1) as $arg) {
            if (preg_match('/^--([a-z-]+)(?:=(.*))?$/', $arg, $m)) {
                $opts[$m[1]] = $m[2] ?? true;
            }
        }
    }
    return $opts[$name] ?? null;
}

function cli_only(): void
{
    if (PHP_SAPI !== 'cli') {
        http_response_code(404);
        exit;
    }
}
