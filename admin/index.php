<?php
// admin/index.php — redirect bare /admin/ visits to dashboard
require_once __DIR__ . '/auth.php';
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: /admin/dashboard.php');
} else {
    header('Location: /admin/login.php');
}
exit;
