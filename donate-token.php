<?php
/**
 * donate-token.php — Returns CSRF token + public PayPal config as JSON.
 * Called by donate-modal.js before the first PayPal interaction.
 * No authentication required (public endpoint — only returns safe values).
 */
require_once __DIR__ . '/admin/auth.php';   // starts session, provides csrf_token()

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('X-Content-Type-Options: nosniff');

exit(json_encode([
    'csrf_token'       => csrf_token(),
    'paypal_client_id' => PAYPAL_CLIENT_ID,   // intentionally public — safe for browser
    'currency'         => PAYPAL_CURRENCY,
    'sandbox'          => (bool) PAYPAL_SANDBOX,
]));
