<?php
/**
 * donate-process.php — Server-side PayPal Orders API handler.
 * POST-only JSON endpoint. CSRF-protected. Rate-limited.
 * Actions: create_order | capture_order
 */

require_once __DIR__ . '/admin/auth.php';   // starts session, provides helpers

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, no-cache');

// ── Only accept POST ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

// ── Rate limiting ────────────────────────────────────────────────────────────
if (is_rate_limited()) {
    http_response_code(429);
    exit(json_encode(['error' => 'Too many requests. Please try again later.']));
}

// ── Parse JSON body ─────────────────────────────────────────────────────────
$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!is_array($input)) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid request format.']));
}

// ── CSRF verification ────────────────────────────────────────────────────────
$clientToken = $input['csrf_token'] ?? '';
$sessToken   = $_SESSION['csrf_token'] ?? '';
if (empty($sessToken) || !hash_equals($sessToken, $clientToken)) {
    http_response_code(403);
    exit(json_encode(['error' => 'Invalid request token. Please refresh the page and try again.']));
}

$action = $input['action'] ?? '';

// ── PayPal helpers ───────────────────────────────────────────────────────────

function pp_base(): string {
    return PAYPAL_SANDBOX
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';
}

/**
 * Fire a cURL call to PayPal, return ['code' => int, 'data' => array].
 */
function pp_curl(string $url, string $method, array $headers, string $body = ''): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    if ($body !== '') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    $resp = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return ['code' => 0, 'data' => [], 'curl_error' => $err];
    }
    return ['code' => $code, 'data' => json_decode($resp, true) ?? []];
}

/**
 * Obtain a short-lived Bearer token via client credentials.
 */
function pp_access_token(): ?string {
    $creds  = base64_encode(PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET);
    $result = pp_curl(
        pp_base() . '/v1/oauth2/token',
        'POST',
        [
            'Authorization: Basic ' . $creds,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        ],
        'grant_type=client_credentials'
    );
    return $result['data']['access_token'] ?? null;
}

// ── Action: create_order ─────────────────────────────────────────────────────
if ($action === 'create_order') {
    // Validate amount server-side — range $1.00 – $10,000.00
    $amount = filter_var($input['amount'] ?? '', FILTER_VALIDATE_FLOAT);
    if ($amount === false || $amount < 1.00 || $amount > 10000.00) {
        http_response_code(400);
        exit(json_encode(['error' => 'Donation amount must be between $1.00 and $10,000.00.']));
    }
    $amountStr = number_format($amount, 2, '.', '');

    $token = pp_access_token();
    if (!$token) {
        http_response_code(502);
        exit(json_encode(['error' => 'Could not connect to payment processor. Please try again.']));
    }

    $idempKey  = 'donate-' . bin2hex(random_bytes(16));
    $orderBody = json_encode([
        'intent'           => 'CAPTURE',
        'purchase_units'   => [[
            'amount'      => ['currency_code' => PAYPAL_CURRENCY, 'value' => $amountStr],
            'description' => 'Donation to On-Deck Alliance',
        ]],
        'application_context' => [
            'brand_name'          => SITE_NAME,
            'shipping_preference' => 'NO_SHIPPING',
            'user_action'         => 'PAY_NOW',
        ],
    ]);

    $result = pp_curl(
        pp_base() . '/v2/checkout/orders',
        'POST',
        [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'PayPal-Request-Id: ' . $idempKey,
        ],
        $orderBody
    );

    if ($result['code'] !== 201 || empty($result['data']['id'])) {
        http_response_code(502);
        exit(json_encode(['error' => 'Could not create payment order. Please try again.']));
    }

    exit(json_encode(['order_id' => $result['data']['id']]));
}

// ── Action: capture_order ────────────────────────────────────────────────────
if ($action === 'capture_order') {
    // Sanitize order ID — PayPal IDs are alphanumeric uppercase
    $raw = $input['order_id'] ?? '';
    $orderId = preg_replace('/[^A-Z0-9\-_]/', '', strtoupper((string)$raw));
    if (strlen($orderId) < 4 || strlen($orderId) > 64) {
        http_response_code(400);
        exit(json_encode(['error' => 'Invalid order reference.']));
    }

    $token = pp_access_token();
    if (!$token) {
        http_response_code(502);
        exit(json_encode(['error' => 'Could not connect to payment processor. Please try again.']));
    }

    $idempKey = 'cap-' . bin2hex(random_bytes(16));
    $result   = pp_curl(
        pp_base() . '/v2/checkout/orders/' . $orderId . '/capture',
        'POST',
        [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'PayPal-Request-Id: ' . $idempKey,
        ],
        '{}'
    );

    $data   = $result['data'] ?? [];
    $status = $data['status'] ?? '';

    if ($result['code'] !== 201 || $status !== 'COMPLETED') {
        http_response_code(402);
        exit(json_encode(['error' => 'Payment could not be completed. Status: ' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8')]));
    }

    // Extract values from PayPal's verified, server-side response only
    $unit      = $data['purchase_units'][0] ?? [];
    $capture   = $unit['payments']['captures'][0] ?? [];
    $payer     = $data['payer'] ?? [];

    $givenName = $payer['name']['given_name'] ?? '';
    $surname   = $payer['name']['surname']    ?? '';
    $payerName = trim($givenName . ' ' . $surname);

    // Sanitize optional donor message (user-supplied)
    $rawMsg  = $input['message'] ?? '';
    $message = mb_substr(strip_tags(trim((string)$rawMsg)), 0, 500);

    $donation = [
        'id'             => $data['id'],                            // PayPal order ID
        'amount'         => $capture['amount']['value']            ?? '0.00',
        'currency'       => $capture['amount']['currency_code']    ?? PAYPAL_CURRENCY,
        'payer_name'     => $payerName,
        'payer_email'    => $payer['email_address']                ?? '',
        'message'        => $message,
        'status'         => 'completed',
        'paypal_capture' => $capture['id']                         ?? '',
        'created_at'     => date('c'),
    ];

    save_donation($donation);

    exit(json_encode([
        'success'    => true,
        'amount'     => $donation['amount'],
        'currency'   => $donation['currency'],
        'payer_name' => $donation['payer_name'],
    ]));
}

// ── Unknown action ───────────────────────────────────────────────────────────
http_response_code(400);
exit(json_encode(['error' => 'Unknown action.']));
