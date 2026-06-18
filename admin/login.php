<?php
require_once __DIR__ . '/auth.php';
require_setup();

// Already logged in → go to dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error   = '';
$expired = !empty($_GET['expired']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if (is_rate_limited()) {
        $mins  = ceil(lockout_seconds_remaining() / 60);
        $error = "Too many failed attempts. Try again in {$mins} minute(s).";
    } else {
        $user = trim($_POST['username'] ?? '');
        $pass = $_POST['password'] ?? '';

        // Check submitted credentials against every configured user.
        // hash_equals used throughout to prevent timing-based enumeration.
        $authenticated = false;
        foreach (ADMIN_USERS as $_u) {
            if (hash_equals((string)($_u['username'] ?? ''), $user) &&
                hash_equals((string)($_u['password'] ?? ''), $pass)) {
                $authenticated = true;
                break;
            }
        }

        if ($authenticated) {
            clear_failed_logins();
            // Regenerate session ID on privilege escalation (prevents fixation)
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['last_activity']   = time();
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            record_failed_login();
            // Deliberate slight delay to slow brute-force (100–300ms)
            usleep(random_int(100_000, 300_000));
            $remaining = attempts_remaining();
            if ($remaining > 0) {
                $error = "Invalid credentials. {$remaining} attempt(s) remaining before lockout.";
            } else {
                $mins  = ceil(LOCKOUT_DURATION / 60);
                $error = "Too many failed attempts. Account locked for {$mins} minutes.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login — <?= e(SITE_NAME) ?></title>
<link rel="icon" type="image/webp" href="/images/favicon.webp">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-lg w-full max-w-sm p-8">
  <div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-xl mb-3">
      <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
      </svg>
    </div>
    <h1 class="text-xl font-bold text-gray-900"><?= e(SITE_NAME) ?></h1>
  </div>

  <?php if ($expired): ?>
    <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-lg p-3 text-sm mb-4">
      Your session expired. Please log in again.
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg p-3 text-sm mb-4">
      <?= e($error) ?>
    </div>
  <?php endif; ?>

  <?php if (is_rate_limited()): ?>
    <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg p-3 text-sm">
      Account temporarily locked due to too many failed attempts.
      Try again in <?= ceil(lockout_seconds_remaining() / 60) ?> minute(s).
    </div>
  <?php else: ?>
    <form method="POST" autocomplete="off" novalidate>
      <?= csrf_field() ?>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <input type="text" name="username" required autofocus
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               value="<?= e($_POST['username'] ?? '') ?>">
      </div>
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
      </div>
      <button type="submit"
              class="w-full bg-gray-900 text-white rounded-lg py-2 font-semibold hover:bg-gray-700 transition">
        Sign In
      </button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>

