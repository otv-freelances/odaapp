<?php
require_once __DIR__ . '/auth.php';
require_auth();
require_setup();

$id      = isset($_GET['id']) ? trim($_GET['id']) : '';
$editing = ($id !== '');
$item    = $editing ? get_latest_item($id) : null;
$errors  = [];

if ($editing && $item === null) {
    flash('Item not found.', 'error');
    header('Location: /admin/latest.php');
    exit;
}

// ── Handle form submission ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $type     = trim($_POST['type']     ?? 'update');
    $title    = trim($_POST['title']    ?? '');
    $date     = trim($_POST['date']     ?? '');
    $location = trim($_POST['location'] ?? '');
    $time     = trim($_POST['time']     ?? '');
    $body     = trim($_POST['body']     ?? '');

    $allowedTypes = ['update', 'event', 'blog'];
    if (!in_array($type, $allowedTypes, true)) $type = 'update';
    if ($title === '') $errors[] = 'Title is required.';
    if ($date  === '') $errors[] = 'Date is required.';
    if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $errors[] = 'Invalid date format.';
    if ($time !== '' && !preg_match('/^\d{2}:\d{2}$/', $time)) $errors[] = 'Invalid time format (HH:MM).';

    // Image upload
    $imagePath = $item['image'] ?? '';
    $upload    = handle_image_upload('image', 'latest');
    if ($upload['error'])  $errors[] = $upload['error'];
    elseif ($upload['ok']) $imagePath = $upload['path'];

    if (!empty($_POST['remove_image'])) {
        if (!empty($item['image']) && file_exists(APP_ROOT . '/' . $item['image'])) {
            @unlink(APP_ROOT . '/' . $item['image']);
        }
        $imagePath = '';
    }

    if (empty($errors)) {
        $now = date('c');
        if ($editing) {
            $record = array_merge($item, [
                'type'       => $type,
                'title'      => $title,
                'date'       => $date,
                'location'   => $location,
                'time'       => $time,
                'body'       => $body,
                'image'      => $imagePath,
                'updated_at' => $now,
            ]);
        } else {
            $newId  = make_id($date, $title);
            $record = [
                'id'         => $newId,
                'type'       => $type,
                'title'      => $title,
                'date'       => $date,
                'location'   => $location,
                'time'       => $time,
                'body'       => $body,
                'image'      => $imagePath,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        // _dir is set by get_latest_item for legacy items — save_latest_item
        // uses it to migrate the item and then removes it from the record.
        if (save_latest_item($record)) {
            flash($editing ? 'Item updated.' : 'Item published.');
            header('Location: /admin/latest.php');
            exit;
        }
        $errors[] = 'Failed to save. Check file permissions on the data/ directory.';
    }
}

// Determine default type for new items
$currentType = $_POST['type'] ?? $item['type'] ?? 'update';

$pageTitle = $editing ? 'Edit Item' : 'New Item';
include __DIR__ . '/partials/header.php';
?>

<div class="flex items-center gap-3 mb-6">
  <a href="/admin/latest.php" class="text-gray-400 hover:text-gray-600 transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
  </a>
  <h1 class="text-2xl font-bold text-gray-900"><?= $editing ? 'Edit Item' : 'New Item' ?></h1>
</div>

<?php if ($errors): ?>
  <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-6">
    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
      <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
  <?= csrf_field() ?>

  <!-- Tag / Type -->
  <div class="max-w-xs">
    <label class="block text-sm font-semibold text-gray-700 mb-1">Tag <span class="text-red-500">*</span></label>
    <select id="type-select" name="type"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 bg-white">
      <option value="update" <?= $currentType === 'update' ? 'selected' : '' ?>>Update</option>
      <option value="event"  <?= $currentType === 'event'  ? 'selected' : '' ?>>Event</option>
      <option value="blog"   <?= $currentType === 'blog'   ? 'selected' : '' ?>>Blog</option>
    </select>
    <p class="mt-1 text-xs text-gray-400">This tag shows on the public site.</p>
  </div>

  <!-- Title -->
  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
    <input type="text" name="title" required
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
           value="<?= e($_POST['title'] ?? $item['title'] ?? '') ?>">
  </div>

  <!-- Date -->
  <div class="max-w-xs">
    <label class="block text-sm font-semibold text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
    <input type="date" name="date" required
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
           value="<?= e($_POST['date'] ?? $item['date'] ?? date('Y-m-d')) ?>">
  </div>

  <!-- Event-only fields -->
  <div id="event-fields">
    <div class="grid grid-cols-2 gap-4 max-w-sm mb-4">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Time</label>
        <input type="time" name="time"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               value="<?= e($_POST['time'] ?? $item['time'] ?? '') ?>">
      </div>
    </div>
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
      <input type="text" name="location" placeholder="e.g. 123 Main St, Toledo OH"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
             value="<?= e($_POST['location'] ?? $item['location'] ?? '') ?>">
    </div>
  </div>

  <!-- Image -->
  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
    <?php $curImg = $item['image'] ?? ''; ?>
    <?php if ($editing && $curImg): ?>
      <div class="mb-3 flex items-start gap-4">
        <img src="/<?= e($curImg) ?>" alt="Current image"
             class="w-24 h-24 object-cover rounded-lg border border-gray-200">
        <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer mt-1">
          <input type="checkbox" name="remove_image" value="1" class="rounded">
          Remove current image
        </label>
      </div>
    <?php endif; ?>
    <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
           class="block text-sm text-gray-600
                  file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                  file:text-sm file:font-semibold
                  file:bg-gray-100 file:text-gray-700
                  hover:file:bg-gray-200">
    <p class="mt-1 text-xs text-gray-400">JPEG, PNG, GIF, or WebP. Max 8 MB.</p>
  </div>

  <!-- Body -->
  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Body</label>
    <textarea name="body" rows="16"
              class="body-field w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 resize-y"
              placeholder="Write your content here. HTML is supported."><?= e($_POST['body'] ?? $item['body'] ?? '') ?></textarea>
    <p class="mt-1 text-xs text-gray-400">HTML is supported. Wrap paragraphs in &lt;p&gt; tags.</p>
  </div>

  <!-- Actions -->
  <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
    <button type="submit"
            class="bg-gray-900 text-white text-sm font-semibold px-6 py-2 rounded-lg hover:bg-gray-700 transition">
      <?= $editing ? 'Update' : 'Publish' ?>
    </button>
    <a href="/admin/latest.php" class="text-sm text-gray-500 hover:text-gray-700 transition">Cancel</a>
  </div>
</form>

<script>
(function () {
  var sel    = document.getElementById('type-select');
  var fields = document.getElementById('event-fields');
  function toggle() {
    fields.style.display = sel.value === 'event' ? '' : 'none';
  }
  sel.addEventListener('change', toggle);
  toggle(); // run on load
}());
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
