<?php
require_once __DIR__ . '/auth.php';
require_auth();
require_setup();

$subs      = get_all_subscribers();
$donations = get_all_donations();
$donTotal  = donations_total();

$recentItems = array_slice(get_all_latest(), 0, 8);
foreach ($recentItems as &$ri) {
    $ri['editUrl'] = '/admin/latest-edit.php?id=' . urlencode($ri['id'] ?? '');
}
unset($ri);

$pageTitle = 'Dashboard';
include __DIR__ . '/partials/header.php';
?>

<h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

<!-- Stats row -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
  <?php
  $stats = [
      ['label' => 'Latest', 'count' => count($recentItems), 'href' => '/admin/latest.php', 'color' => 'bg-blue-50 text-blue-700'],
      ['label' => 'Subscribers',        'count' => count($subs),                   'href' => '/admin/subscribers.php',   'color' => 'bg-purple-50 text-purple-700'],
      ['label' => 'Total Donated',      'count' => '$' . number_format($donTotal, 2), 'href' => '/admin/donations.php',  'color' => 'bg-amber-50 text-amber-700'],
  ];
  foreach ($stats as $s): ?>
    <a href="<?= $s['href'] ?>"
       class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4 hover:shadow-md transition group">
      <div class="<?= $s['color'] ?> rounded-xl p-3 text-2xl font-bold min-w-[3rem] text-center">
        <?= $s['count'] ?>
      </div>
      <span class="font-semibold text-gray-700 group-hover:text-gray-900 transition"><?= $s['label'] ?></span>
    </a>
  <?php endforeach; ?>
</div>

<!-- Quick Actions -->
<div class="flex flex-wrap gap-3 mb-8">
  <a href="/admin/latest-edit.php"
     class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-gray-700 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Item
  </a>
  <a href="/admin/backup.php"
     class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-gray-50 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
    </svg>
    Download Backup
  </a>
</div>

<!-- Recent News & Events -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
  <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
    <h2 class="font-semibold text-gray-800">Latest</h2>
    <a href="/admin/latest.php" class="text-xs text-gray-400 hover:text-gray-600 transition">View all →</a>
  </div>
  <?php if (empty($recentItems)): ?>
    <p class="px-5 py-8 text-sm text-gray-400 text-center">Nothing yet.</p>
  <?php else: ?>
    <ul class="divide-y divide-gray-100">
      <?php foreach ($recentItems as $ri): ?>
        <li class="px-5 py-3 flex items-center justify-between gap-2">
          <div class="flex items-center gap-3 min-w-0">
            <?php
              $dtMap = ['update'=>['bg-blue-100 text-blue-700','Update'],'event'=>['bg-green-100 text-green-700','Event'],'blog'=>['bg-amber-100 text-amber-700','Blog']];
              $dtType = $ri['type'] ?? 'update';
              [$dtCls,$dtLbl] = $dtMap[$dtType] ?? ['bg-gray-100 text-gray-700', ucfirst($dtType)];
            ?>
            <span class="inline-block flex-shrink-0 px-1.5 py-0.5 rounded text-xs font-semibold <?= $dtCls ?>">
              <?= $dtLbl ?>
            </span>
            <div class="min-w-0">
              <p class="text-sm font-medium text-gray-800 truncate"><?= e($ri['title']) ?></p>
              <p class="text-xs text-gray-400"><?= e($ri['date']) ?></p>
            </div>
          </div>
          <a href="<?= e($ri['editUrl']) ?>"
             class="text-xs text-blue-600 hover:text-blue-800 flex-shrink-0">Edit</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
