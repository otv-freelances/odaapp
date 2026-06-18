<?php
/**
 * contact-process.php â€” Handles contact form submissions and newsletter signups.
 * POST-only. CSRF-protected. Rate-limited.
 */

require_once __DIR__ . '/admin/auth.php';

$redirectTarget = _safe_redirect_target($_POST['redirect'] ?? null);

function _safe_redirect_target(?string $raw): string {
    $raw = trim((string)$raw);
    if ($raw === '') return 'contact.php';
    $path = parse_url($raw, PHP_URL_PATH);
    $base = basename((string)($path ?? ''));
    $allowed = ['contact.php', 'for-schools.php', 'volunteer.php'];
    return in_array($base, $allowed, true) ? $base : 'contact.php';
}

function _redir(string $target, string $query = ''): void {
    header('Location: ' . $target . $query, true, 303);
    exit;
}

// â”€â”€ Only accept POST â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// â”€â”€ Rate limiting â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
if (is_rate_limited()) {
    _redir($redirectTarget, '?error=rate_limit');
}

// â”€â”€ CSRF verification â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
$clientToken = $_POST['csrf_token'] ?? '';
$sessToken   = $_SESSION['csrf_token'] ?? '';
if (empty($sessToken) || !hash_equals($sessToken, $clientToken)) {
    _redir($redirectTarget, '?error=csrf');
}

$action = $_POST['action'] ?? '';

// â”€â”€ Handle newsletter subscribe â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
if ($action === 'subscribe') {
    $email = trim($_POST['sub_email'] ?? '');
    $name  = trim($_POST['sub_name']  ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        _redir($redirectTarget, '?error=invalid_email');
    }

    $added = add_subscriber($email, $name);

    if ($added) {
        _redir($redirectTarget, '?success=subscribed');
    } else {
        _redir($redirectTarget, '?error=already_subscribed');
    }
}

// â”€â”€ Handle contact message â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
if ($action === 'contact') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $message = trim($_POST['message'] ?? '');
    $context    = trim($_POST['context'] ?? '');
    $schoolName = trim($_POST['school_name'] ?? '');
    $role       = trim($_POST['role'] ?? '');
    $interests   = trim($_POST['interests'] ?? '');
    $availability= trim($_POST['availability'] ?? '');

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        _redir($redirectTarget, '?error=missing_fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        _redir($redirectTarget, '?error=invalid_email');
    }

    // Length limits (already enforced client-side but re-checked server-side)
    if (
        strlen($name) > 120 ||
        strlen($email) > 254 ||
        strlen($phone) > 20 ||
        strlen($message) > 2000 ||
        strlen($context) > 50 ||
        strlen($schoolName) > 160 ||
        strlen($role) > 120 ||
        strlen($interests) > 200 ||
        strlen($availability) > 200
    ) {
        _redir($redirectTarget, '?error=too_long');
    }

    if ($context === 'for_schools' && $schoolName === '') {
        _redir($redirectTarget, '?error=missing_fields');
    }

    // Add structured metadata at the top of the message for easier review
    $metaLines = [];
    if ($context !== '') $metaLines[] = 'Context: ' . $context;
    if ($schoolName !== '') $metaLines[] = 'School/Org: ' . $schoolName;
    if ($role !== '') $metaLines[] = 'Role/Title: ' . $role;
    if ($interests !== '') $metaLines[] = 'Interests: ' . $interests;
    if ($availability !== '') $metaLines[] = 'Availability: ' . $availability;
    if (!empty($metaLines)) {
        $message = implode("\n", $metaLines) . "\n\n" . $message;
    }

    // Build contact entry
    $contact = [
        'id'          => bin2hex(random_bytes(8)),
        'name'        => $name,
        'email'       => $email,
        'phone'       => $phone,
        'message'     => $message,
        'context'     => $context,
        'school_name' => $schoolName,
        'role'        => $role,
        'interests'   => $interests,
        'availability'=> $availability,
        'submitted_at' => date('c'),
        'ip'          => hash('sha256', $_SERVER['REMOTE_ADDR'] ?? ''), // hashed for privacy
    ];

    // Save to data/contacts.json
    $filePath = DATA_DIR . '/contacts.json';
    $contacts = [];
    if (file_exists($filePath)) {
        $contacts = json_decode(file_get_contents($filePath), true) ?: [];
    }
    $contacts[] = $contact;

    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }

    $saved = file_put_contents(
        $filePath,
        json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );

    if ($saved === false) {
        _redir($redirectTarget, '?error=save_failed');
    }

    _redir($redirectTarget, '?success=message');
}

// Unknown action
_redir($redirectTarget, '?error=unknown');
