<?php
require_once __DIR__ . '/auth.php';
require_auth();
header('Location: /admin/latest.php');
exit;
$posts     = get_all_posts();
$events    = get_all_events();

// Build unified list with type tag
$items = [];
foreach ($posts as $p) {
    $items[] = [
        'type'  => 'post',
        'id'    => $p['id']       ?? '',
        'title' => $p['title']    ?? '',
        'date'  => $p['date']     ?? '',
        'image' => $p['image']    ?? '',
        'meta'  => '',
    ];
}
foreach ($events as $e) {
    $loc   = $e['location'] ?? '';
    $time  = $e['time']     ?? '';
    $meta  = implode(' · ', array_filter([$loc, $time]));
    $items[] = [
        'type'  => 'event',
        'id'    => $e['id']    ?? '',
        'title' => $e['title'] ?? '',
        'date'  => $e['date']  ?? '',
        'image' => $e['image'] ?? '',
        'meta'  => $meta,
    ];
}
usort($items, fn($a, $b) => strcmp($b['date'], $a['date']));

include __DIR__ . '/partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold text-gray-900">Blog &amp; Events</h1>
  <div class="flex gap-2">
    <a href="/admin/post-edit.php"
       class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-gray-700 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      New Update
    </a>
    <a href="/admin/event-edit.php"
       class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-gray-50 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      New Event
    </a>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <p class="text-gray-400 text-sm">Nothing here yet. <a href="/admin/post-edit.php" class="text-gray-700 underline">Create a post</a> or <a href="/admin/event-edit.php" class="text-gray-700 underline">add an event</a>.</p>
  </div>
<?php else: ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 w-16">Image</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 w-20">Type</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600">Title</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Date</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Details</th>
          <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php foreach ($items as $item):
          $editUrl   = $item['type'] === 'post'
              ? '/admin/post-edit.php?id='  . urlencode($item['id'])
              : '/admin/event-edit.php?id=' . urlencode($item['id']);
          $deleteType = $item['type'] === 'post' ? 'post' : 'event';
          $typeBg    = $item['type'] === 'post'  ? 'bg-blue-100 text-blue-700'  : 'bg-green-100 text-green-700';
          $typeLabel = $item['type'] === 'post'  ? 'Update' : 'Event';
        ?>
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3">
              <?php if (!empty($item['image'])): ?>
                <img src="/<?= e($item['image']) ?>" alt=""
                     class="w-10 h-10 rounded object-cover bg-gray-100">
              <?php else: ?>
                <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-300">
                  <?php if ($item['type'] === 'event'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <?php else: ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3">
              <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold <?= $typeBg ?>"><?= $typeLabel ?></span>
            </td>
            <td class="px-4 py-3">
              <span class="font-medium text-gray-900"><?= e($item['title']) ?></span>
            </td>
            <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">
              <?= e($item['date']) ?>
            </td>
            <td class="px-4 py-3 text-gray-400 hidden md:table-cell text-xs">
              <?= e($item['meta']) ?>
            </td>
            <td class="px-4 py-3 text-right">
              <a href="<?= e($editUrl) ?>"
                 class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
              <form method="POST" action="/admin/delete.php" class="inline"
                    onsubmit="return confirm('Delete this item? This cannot be undone.')">
                <?= csrf_field() ?>
                <input type="hidden" name="type" value="<?= e($deleteType) ?>">
                <input type="hidden" name="id"   value="<?= e($item['id']) ?>">
                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
