<?php
require_once __DIR__ . '/auth.php';
require_auth();
require_setup();

$pageTitle = 'Blog Posts';
$posts     = get_all_posts();

include __DIR__ . '/partials/header.php';
?>

<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold text-gray-900">Blog Posts</h1>
  <a href="/admin/post-edit.php"
     class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-gray-700 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Post
  </a>
</div>

<?php if (empty($posts)): ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <p class="text-gray-400 text-sm">No posts yet. <a href="/admin/post-edit.php" class="text-gray-700 underline">Create the first one.</a></p>
  </div>
<?php else: ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 w-16">Image</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600">Title</th>
          <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Date</th>
          <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php foreach ($posts as $post): ?>
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3">
              <?php if (!empty($post['image'])): ?>
                <img src="/<?= e($post['image']) ?>" alt=""
                     class="w-10 h-10 rounded object-cover bg-gray-100">
              <?php else: ?>
                <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-300">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3">
              <span class="font-medium text-gray-900"><?= e($post['title']) ?></span>
            </td>
            <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">
              <?= e($post['date'] ?? '') ?>
            </td>
            <td class="px-4 py-3 text-right">
              <a href="/admin/post-edit.php?id=<?= urlencode($post['id']) ?>"
                 class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
              <form method="POST" action="/admin/delete.php" class="inline"
                    onsubmit="return confirm('Delete this post? This cannot be undone.')">
                <?= csrf_field() ?>
                <input type="hidden" name="type" value="post">
                <input type="hidden" name="id"   value="<?= e($post['id']) ?>">
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
