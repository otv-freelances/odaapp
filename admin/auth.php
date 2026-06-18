<?php
/**
 * admin/auth.php
 * Shared authentication, security helpers, and data access functions.
 * Included at the top of every admin page.
 */

require_once dirname(__DIR__) . '/config.php';

// â”€â”€ Session hardening â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', 1);
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// â”€â”€ Rate Limiting â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function _rate_limit_file(): string {
    return DATA_DIR . '/rate_limit.json';
}

function _load_rate_data(): array {
    $f = _rate_limit_file();
    if (!file_exists($f)) return [];
    $d = json_decode(file_get_contents($f), true);
    return is_array($d) ? $d : [];
}

function _save_rate_data(array $data): void {
    $dir = DATA_DIR;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents(_rate_limit_file(), json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
}

function get_client_ip(): string {
    // Use only REMOTE_ADDR â€” never trust X-Forwarded-For without a trusted proxy
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function is_rate_limited(): bool {
    $ip   = get_client_ip();
    $data = _load_rate_data();
    if (!isset($data[$ip])) return false;
    $r = $data[$ip];
    if (!empty($r['locked_until'])) {
        if ($r['locked_until'] > time()) return true;
        // Lockout expired â€” clean up
        unset($data[$ip]);
        _save_rate_data($data);
    }
    return false;
}

function lockout_seconds_remaining(): int {
    $ip   = get_client_ip();
    $data = _load_rate_data();
    if (isset($data[$ip]['locked_until']) && $data[$ip]['locked_until'] > time()) {
        return $data[$ip]['locked_until'] - time();
    }
    return 0;
}

function record_failed_login(): void {
    $ip   = get_client_ip();
    $data = _load_rate_data();
    $now  = time();
    if (!isset($data[$ip])) {
        $data[$ip] = ['attempts' => 0, 'first_attempt' => $now];
    }
    $data[$ip]['attempts']++;
    $data[$ip]['last_attempt'] = $now;
    if ($data[$ip]['attempts'] >= MAX_LOGIN_ATTEMPTS) {
        // Add small random jitter to make timing attacks harder
        $data[$ip]['locked_until'] = $now + LOCKOUT_DURATION + random_int(0, 60);
    }
    _save_rate_data($data);
}

function clear_failed_logins(): void {
    $ip   = get_client_ip();
    $data = _load_rate_data();
    unset($data[$ip]);
    _save_rate_data($data);
}

function attempts_remaining(): int {
    $ip   = get_client_ip();
    $data = _load_rate_data();
    $used = $data[$ip]['attempts'] ?? 0;
    return max(0, MAX_LOGIN_ATTEMPTS - $used);
}

// â”€â”€ CSRF Protection â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Invalid request token. Please go back and try again.');
    }
}

// â”€â”€ Auth â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function require_auth(): void {
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/login.php');
        exit;
    }
    // Idle timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_LIFETIME) {
        session_unset();
        session_destroy();
        header('Location: /admin/login.php?expired=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

function require_setup(): void {
    $users = ADMIN_USERS;
    if (empty($users) || empty($users[0]['username']) || empty($users[0]['password'])) {
        http_response_code(503);
        exit('Admin credentials are not configured. Set ADMIN_USERS in config.php.');
    }
}

// â”€â”€ Slugify / ID generation â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function slugify(string $text): string {
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^\pL\pN\s-]/u', '', $text);
    $text = preg_replace('/[\s_]+/', '-', $text);
    $text = preg_replace('/-{2,}/', '-', $text);
    return trim($text, '-');
}

function make_id(string $date, string $title): string {
    $slug = slugify($title);
    $slug = $slug ?: bin2hex(random_bytes(4));
    // Keep slug under 50 chars, then append a 6-hex random suffix for uniqueness
    $slug = mb_substr($slug, 0, 50);
    return $date . '-' . $slug . '-' . bin2hex(random_bytes(3));
}

// â”€â”€ Post URL slugs â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

/**
 * Converts a post title to an underscore-based URL slug.
 * e.g. "Toledo Concrete Classic 2026" â†’ "toledo_concrete_classic_2026"
 */
function post_slug(string $title): string {
    $text = mb_strtolower(trim($title));
    $text = preg_replace('/[^\pL\pN\s]/u', '', $text);
    $text = preg_replace('/\s+/', '_', $text);
    $text = preg_replace('/_+/', '_', $text);
    return trim($text, '_');
}

/**
 * Finds a post/event across all content directories by its URL slug.
 */
function find_item_by_slug(string $slug): ?array {
    foreach (get_all_latest() as $item) {
        if (post_slug($item['title'] ?? '') === $slug) {
            return $item;
        }
    }
    return null;
}

// â”€â”€ Posts â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function get_all_posts(): array {
    $dir = DATA_DIR . '/posts';
    if (!is_dir($dir)) return [];
    $posts = [];
    foreach (glob($dir . '/*.json') ?: [] as $f) {
        $d = json_decode(file_get_contents($f), true);
        if ($d) $posts[] = $d;
    }
    usort($posts, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $posts;
}

function get_post(string $id): ?array {
    $f = DATA_DIR . '/posts/' . basename($id) . '.json';
    if (!file_exists($f)) return null;
    return json_decode(file_get_contents($f), true) ?: null;
}

function save_post(array $post): bool {
    $dir = DATA_DIR . '/posts';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return file_put_contents(
        $dir . '/' . basename($post['id']) . '.json',
        json_encode($post, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    ) !== false;
}

function delete_post(string $id): bool {
    $f = DATA_DIR . '/posts/' . basename($id) . '.json';
    return file_exists($f) && unlink($f);
}

// â”€â”€ Events â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function get_all_events(): array {
    $dir = DATA_DIR . '/events';
    if (!is_dir($dir)) return [];
    $events = [];
    foreach (glob($dir . '/*.json') ?: [] as $f) {
        $d = json_decode(file_get_contents($f), true);
        if ($d) $events[] = $d;
    }
    usort($events, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $events;
}

function get_event(string $id): ?array {
    $f = DATA_DIR . '/events/' . basename($id) . '.json';
    if (!file_exists($f)) return null;
    return json_decode(file_get_contents($f), true) ?: null;
}

function save_event(array $event): bool {
    $dir = DATA_DIR . '/events';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return file_put_contents(
        $dir . '/' . basename($event['id']) . '.json',
        json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    ) !== false;
}

function delete_event(string $id): bool {
    $f = DATA_DIR . '/events/' . basename($id) . '.json';
    return file_exists($f) && unlink($f);
}

// â”€â”€ Subscribers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function get_all_subscribers(): array {
    $f = DATA_DIR . '/subscribers.json';
    if (!file_exists($f)) return [];
    return json_decode(file_get_contents($f), true) ?: [];
}

function add_subscriber(string $email, string $name = ''): bool {
    $email = strtolower(trim($email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    $subs = get_all_subscribers();
    foreach ($subs as $s) {
        if (strtolower($s['email']) === $email) return false; // already subscribed
    }
    $subs[] = ['email' => $email, 'name' => trim($name), 'subscribed_at' => date('c')];
    $dir = DATA_DIR;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return file_put_contents(
        $dir . '/subscribers.json',
        json_encode($subs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    ) !== false;
}

function delete_subscriber(string $email): bool {
    $subs = get_all_subscribers();
    $new  = array_values(array_filter($subs, fn($s) => $s['email'] !== $email));
    if (count($new) === count($subs)) return false;
    return file_put_contents(
        DATA_DIR . '/subscribers.json',
        json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    ) !== false;
}

// â”€â”€ Image Upload â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

/**
 * Handles a file upload input.
 * Returns ['ok' => bool, 'path' => string (web-relative), 'error' => string]
 */
function handle_image_upload(string $field, string $folder = 'general'): array {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'path' => '', 'error' => ''];
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'path' => '', 'error' => 'Upload error code ' . $file['error']];
    }
    if ($file['size'] > 8 * 1024 * 1024) {
        return ['ok' => false, 'path' => '', 'error' => 'Image must be under 8 MB.'];
    }
    // Validate real MIME type (not just extension)
    $finfo  = new finfo(FILEINFO_MIME_TYPE);
    $mime   = $finfo->file($file['tmp_name']);
    $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!array_key_exists($mime, $extMap)) {
        return ['ok' => false, 'path' => '', 'error' => 'Only JPEG, PNG, GIF, and WebP are allowed.'];
    }
    $ext     = $extMap[$mime];
    $dir     = UPLOADS_DIR . '/' . $folder;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name    = date('Y-m-d') . '-' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dest    = $dir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['ok' => false, 'path' => '', 'error' => 'Could not save image.'];
    }
    return ['ok' => true, 'path' => 'uploads/' . $folder . '/' . $name, 'error' => ''];
}

// â”€â”€ Latest (unified: updates, events, blog posts) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

/**
 * Reads items from data/latest/ (primary store) plus legacy data/posts/ and
 * data/events/ so existing content stays visible without a migration step.
 * Items have a '_dir' key for internal routing â€” never stored in JSON files.
 */
function get_all_latest(): array {
    $items = [];
    foreach ([
        'latest' => null,   // type stored in the file
        'posts'  => 'update',
        'events' => 'event',
    ] as $subdir => $defaultType) {
        $dir = DATA_DIR . '/' . $subdir;
        foreach (glob($dir . '/*.json') ?: [] as $f) {
            $d = json_decode(file_get_contents($f), true);
            if (!$d) continue;
            if ($defaultType !== null) $d['type'] = $d['type'] ?? $defaultType;
            $d['_dir'] = $subdir;
            $items[] = normalize_latest_item($d);
        }
    }
    usort($items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $items;
}

function get_latest_item(string $id): ?array {
    foreach (['latest', 'posts', 'events'] as $subdir) {
        $f = DATA_DIR . '/' . $subdir . '/' . basename($id) . '.json';
        if (file_exists($f)) {
            $d = json_decode(file_get_contents($f), true);
            if ($d) { $d['_dir'] = $subdir; return normalize_latest_item($d); }
        }
    }
    return null;
}

/**
 * Always saves to data/latest/. If the item came from a legacy directory,
 * the old file is removed (migrating it on first edit).
 */
function save_latest_item(array $item): bool {
    $oldDir = $item['_dir'] ?? 'latest';
    unset($item['_dir']);
    if ($oldDir !== 'latest') {
        $legacy = DATA_DIR . '/' . $oldDir . '/' . basename($item['id']) . '.json';
        if (file_exists($legacy)) @unlink($legacy);
    }
    $dir = DATA_DIR . '/latest';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return file_put_contents(
        $dir . '/' . basename($item['id']) . '.json',
        json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    ) !== false;
}

function delete_latest_item(string $id): bool {
    foreach (['latest', 'posts', 'events'] as $subdir) {
        $f = DATA_DIR . '/' . $subdir . '/' . basename($id) . '.json';
        if (file_exists($f)) return unlink($f);
    }
    return false;
}

// â”€â”€ Donations â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function get_all_donations(): array {
    $f = DATA_DIR . '/donations.json';
    if (!file_exists($f)) return [];
    return json_decode(file_get_contents($f), true) ?: [];
}

function save_donation(array $donation): bool {
    $donations = get_all_donations();
    $donations[] = $donation;
    $dir = DATA_DIR;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return file_put_contents(
        $dir . '/donations.json',
        json_encode($donations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    ) !== false;
}

function delete_donation(string $paypalId): bool {
    $donations = get_all_donations();
    $new = array_values(array_filter($donations, fn($d) => $d['id'] !== $paypalId));
    if (count($new) === count($donations)) return false;
    return file_put_contents(
        DATA_DIR . '/donations.json',
        json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    ) !== false;
}

function donations_total(): float {
    return array_sum(array_column(get_all_donations(), 'amount'));
}

// â”€â”€ Misc helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function normalize_latest_item(array $d): array {
    foreach (['title', 'body', 'location', 'time'] as $k) {
        if (isset($d[$k]) && is_string($d[$k])) $d[$k] = normalize_mojibake($d[$k]);
    }
    return $d;
}

function normalize_mojibake(string $s): string {
    if ($s === "") return $s;
    return strtr($s, [
        "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9D" => "\u{2014}",
        "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C" => "\u{2013}",
        "\xC3\xA2\xE2\x82\xAC\xC2\xA6"     => "\u{2026}",
        "\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "\u{2019}",
        "\xC3\xA2\xE2\x82\xAC\xC5\x93"     => "\u{201C}",
        "\xC3\xA2\xE2\x82\xAC\xC2\x9D"     => "\u{201D}",
        "\xC3\x82\xC2\xB7"                 => "\u{00B7}",

        "\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\xA2\xE2\x82\xAC\xC2\x9D" => "\u{2014}",
        "\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\xA2\xE2\x82\xAC\xC5\x93" => "\u{2013}",
        "\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x82\xC2\xA6"             => "\u{2026}",
        "\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\xA2\xE2\x80\x9E\xC2\xA2" => "\u{2019}",
        "\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x85\xE2\x80\x9C"         => "\u{201C}",
        "\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x82\xC2\x9D"             => "\u{201D}",
        "\xC3\x83\xE2\x80\x9A\xC3\x82\xC2\xB7"                                     => "\u{00B7}",
    ]);
}

function flash(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function get_flash(): ?array {
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}
