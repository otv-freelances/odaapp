<?php
require_once __DIR__ . '/config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: program.php');
    exit;
}

// Sanitize inputs
$first_name    = trim(htmlspecialchars($_POST['first_name']    ?? '', ENT_QUOTES, 'UTF-8'));
$last_name     = trim(htmlspecialchars($_POST['last_name']     ?? '', ENT_QUOTES, 'UTF-8'));
$age           = (int) ($_POST['age'] ?? 0);
$guardian_name = trim(htmlspecialchars($_POST['guardian_name'] ?? '', ENT_QUOTES, 'UTF-8'));
$email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone         = trim(htmlspecialchars($_POST['phone']   ?? '', ENT_QUOTES, 'UTF-8'));
$message       = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));
$returnTo      = basename(trim($_POST['return_to'] ?? 'program.php'));
if (!preg_match('/^[a-z0-9._-]+\.php$/i', $returnTo)) {
    $returnTo = 'program.php';
}

// Validate required fields
if (!$first_name || !$last_name || !$guardian_name || !filter_var($email, FILTER_VALIDATE_EMAIL) || $age < 6 || $age > 17) {
    header('Location: ' . $returnTo . '#signup');
    exit;
}

// Build email body
$to      = defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'info@ondeckalliance.org';
$subject = 'New I CAN Program Sign-Up: ' . $first_name . ' ' . $last_name;
$body    = "New sign-up submission from the I CAN Program page.\n\n"
         . "Student name:     $first_name $last_name\n"
         . "Age:              $age\n"
         . "Parent/Guardian:  $guardian_name\n"
         . "Email:            $email\n"
         . "Phone:            $phone\n\n"
         . "Notes:\n$message\n";

$headers = "From: noreply@ondeckalliance.org\r\n"
         . "Reply-To: $email\r\n"
         . "X-Mailer: PHP/" . PHP_VERSION;

mail($to, $subject, $body, $headers);

header('Location: ' . $returnTo . '?signup=success#signup');
exit;
