<?php
require_once __DIR__ . '/auth.php';
require_auth();
require_setup();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/dashboard.php');
    exit;
}

verify_csrf();

$type = $_POST['type'] ?? '';
$id   = trim($_POST['id'] ?? '');

if ($id === '') {
    flash('No ID provided.', 'error');
    header('Location: /admin/dashboard.php');
    exit;
}

switch ($type) {
    case 'post':
        $post = get_post($id);
        if ($post) {
            // Delete associated image if present
            if (!empty($post['image'])) {
                $imgPath = APP_ROOT . '/' . ltrim($post['image'], '/');
                if (file_exists($imgPath)) @unlink($imgPath);
            }
            if (delete_post($id)) {
                flash('Post deleted.');
            } else {
                flash('Could not delete post.', 'error');
            }
        } else {
            flash('Post not found.', 'error');
        }
        header('Location: /admin/posts.php');
        break;

    case 'event':
        $event = get_event($id);
        if ($event) {
            if (!empty($event['image'])) {
                $imgPath = APP_ROOT . '/' . ltrim($event['image'], '/');
                if (file_exists($imgPath)) @unlink($imgPath);
            }
            if (delete_event($id)) {
                flash('Event deleted.');
            } else {
                flash('Could not delete event.', 'error');
            }
        } else {
            flash('Event not found.', 'error');
        }
        header('Location: /admin/events.php');
        break;

    case 'latest':
        $item = get_latest_item($id);
        if ($item) {
            if (!empty($item['image'])) {
                $imgPath = APP_ROOT . '/' . ltrim($item['image'], '/');
                if (file_exists($imgPath)) @unlink($imgPath);
            }
            if (delete_latest_item($id)) {
                flash('Item deleted.');
            } else {
                flash('Could not delete item.', 'error');
            }
        } else {
            flash('Item not found.', 'error');
        }
        header('Location: /admin/latest.php');
        break;

    case 'subscriber':
        $email = trim($_POST['email'] ?? '');
        if ($email && delete_subscriber($email)) {
            flash('Subscriber removed.');
        } else {
            flash('Could not remove subscriber.', 'error');
        }
        header('Location: /admin/subscribers.php');
        break;

    case 'donation':
        $paypalId = trim($_POST['id'] ?? '');
        if ($paypalId && delete_donation($paypalId)) {
            flash('Donation record removed.');
        } else {
            flash('Could not remove donation record.', 'error');
        }
        header('Location: /admin/donations.php');
        break;

    default:
        flash('Unknown item type.', 'error');
        header('Location: /admin/dashboard.php');
}

exit;
