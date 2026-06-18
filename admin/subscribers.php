<?php
require_once __DIR__ . '/auth.php';
require_auth();
require_setup();

$subscribers = get_all_subscribers();
$pageTitle   = 'Subscribers';

// Build comma-separated email string for bulk copy
$emailList = implode(', ', array_column($subscribers, 'email'));

include __DIR__ . '/partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold text-gray-900">Newsletter Subscribers</h1>
  <span class="text-sm text-gray-500"><?= count($subscribers) ?> total</span>
</div>

<!-- Copy emails panel -->
<?php if (!empty($subscribers)): ?>
<div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
  <div class="flex items-center justify-between mb-2">
    <p class="text-sm font-semibold text-blue-800">Copy all emails (paste into Gmail BCC)</p>
    <button id="copy-btn"
            onclick="copyEmails()"
            class="text-xs bg-blue-700 text-white px-3 py-1.5 rounded-lg hover:bg-blue-600 transition font-semibold">
      Copy to Clipboard
    </button>
  </div>
  <textarea id="email-list" readonly rows="3"
            class="w-full text-xs bg-white border border-blue-200 rounded-lg px-3 py-2 text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-blue-400"><?= e($emailList) ?></textarea>
  <p id="copy-confirm" class="hidden mt-2 text-xs text-green-600 font-medium">✓ Copied to clipboard!</p>
</div>
<?php endif; ?>

<!-- Subscriber Table -->
<?php if (empty($subscribers)): ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <p class="text-gray-400 text-sm">No subscribers yet.</p>
    <p class="text-gray-400 text-xs mt-1">Add a signup form to your public site and call <code>add_subscriber()</code>.</p>
  </div>
<?php else: ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="text-left px-4 py-3 font-semibold text-gray-600">Email</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Name</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Subscribed</th>
          <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php foreach ($subscribers as $sub): ?>
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3 font-medium text-gray-900"><?= e($sub['email']) ?></td>
            <td class="px-4 py-3 text-gray-500 hidden sm:table-cell"><?= e($sub['name'] ?? '—') ?></td>
            <td class="px-4 py-3 text-gray-400 text-xs hidden md:table-cell">
              <?= isset($sub['subscribed_at']) ? e(date('Y-m-d', strtotime($sub['subscribed_at']))) : '—' ?>
            </td>
            <td class="px-4 py-3 text-right">
              <form method="POST" action="/admin/delete.php" class="inline"
                    onsubmit="return confirm('Remove <?= e(addslashes($sub['email'])) ?> from subscribers?')">
                <?= csrf_field() ?>
                <input type="hidden" name="type"  value="subscriber">
                <input type="hidden" name="id"    value="<?= e($sub['email']) ?>">
                <input type="hidden" name="email" value="<?= e($sub['email']) ?>">
                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<!-- Newsletter signup integration note -->
<div class="mt-8 bg-gray-50 border border-gray-200 rounded-xl p-5">
  <h2 class="text-sm font-semibold text-gray-700 mb-2">Adding a Public Signup Form</h2>
  <p class="text-xs text-gray-500 mb-3">
    Create a public-facing <code>subscribe.php</code> and call the helper below.
    All subscribers are stored in <code>data/subscribers.json</code>.
  </p>
  <pre class="bg-gray-800 text-green-300 text-xs rounded-lg p-4 overflow-x-auto"><code>&lt;?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name  = trim($_POST['name']  ?? '');
    if (add_subscriber($email, $name)) {
        // success
    } else {
        // already subscribed or invalid email
    }
}
?&gt;</code></pre>
</div>

<script>
function copyEmails() {
  const el      = document.getElementById('email-list');
  const confirm = document.getElementById('copy-confirm');
  el.select();
  el.setSelectionRange(0, 99999);

  if (navigator.clipboard) {
    navigator.clipboard.writeText(el.value).then(() => {
      confirm.classList.remove('hidden');
      setTimeout(() => confirm.classList.add('hidden'), 3000);
    });
  } else {
    document.execCommand('copy');
    confirm.classList.remove('hidden');
    setTimeout(() => confirm.classList.add('hidden'), 3000);
  }
}
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
