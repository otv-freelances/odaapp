<?php
// admin/partials/header.php
// Usage: include with $pageTitle set beforehand
$pageTitle  = $pageTitle ?? SITE_NAME;
$flash      = get_flash();
$currentUri = $_SERVER['REQUEST_URI'] ?? '';

function nav_link(string $href, string $label, string $icon, string $current): string {
    $active = strpos($current, $href) !== false;
    $base   = 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition ';
    $cls    = $active
        ? $base . 'bg-white/15 text-white'
        : $base . 'text-gray-300 hover:bg-white/10 hover:text-white';
    return "<a href=\"{$href}\" class=\"{$cls}\">{$icon}<span>{$label}</span></a>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> — <?= e(SITE_NAME) ?></title>
<link rel="icon" type="image/webp" href="/images/favicon.webp">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  /* Sidebar scrollbar */
  #sidebar::-webkit-scrollbar { width: 4px; }
  #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
  /* Rich textarea */
  textarea.body-field { font-family: ui-monospace, monospace; font-size: 0.875rem; }
</style>
</head>
<body class="bg-gray-100 min-h-screen flex">

<!-- Sidebar -->
<aside id="sidebar" class="hidden md:flex flex-col w-56 min-h-screen bg-gray-900 text-white p-4 flex-shrink-0 overflow-y-auto">
  <div class="mb-8">
    <span class="text-lg font-bold tracking-tight"><?= e(SITE_NAME) ?></span>
  </div>

  <nav class="flex flex-col gap-1 flex-1">
    <?php
    $icons = [
        'dashboard' => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        'posts'     => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
        'events'    => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        'subs'      => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        'donate'    => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ];
    echo nav_link('/admin/dashboard.php',   'Dashboard', $icons['dashboard'], $currentUri);
    echo nav_link('/admin/latest.php',        'Latest',    $icons['posts'],     $currentUri);
    echo '<a href="/admin/latest-edit.php" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-white/10 transition ml-4">+ New Item</a>';
    echo nav_link('/admin/subscribers.php',   'Subscribers', $icons['subs'],    $currentUri);
    echo nav_link('/admin/donations.php',     'Donations',   $icons['donate'],  $currentUri);
    ?>
  </nav>

  <div class="mt-auto pt-4 border-t border-white/10">
    <a href="/admin/logout.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-white/10 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
      </svg>
      <span>Logout</span>
    </a>
  </div>
</aside>

<!-- Main -->
<div class="flex-1 flex flex-col min-w-0">

  <!-- Top bar (mobile) -->
  <header class="md:hidden bg-gray-900 text-white px-4 py-3 flex items-center justify-between">
    <span class="font-bold"><?= e(SITE_NAME) ?></span>
    <button id="mob-toggle" class="p-1">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </header>

  <!-- Mobile drawer -->
  <div id="mob-nav" class="hidden md:hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/50" id="mob-overlay"></div>
    <aside class="absolute left-0 top-0 bottom-0 w-56 bg-gray-900 text-white p-4 flex flex-col overflow-y-auto">
      <div class="mb-6 flex items-center justify-between">
        <span class="font-bold"><?= e(SITE_NAME) ?></span>
        <button id="mob-close" class="text-gray-400 hover:text-white">âœ•</button>
      </div>
      <nav class="flex flex-col gap-1 flex-1">
        <?php
        echo nav_link('/admin/dashboard.php',    'Dashboard',    $icons['dashboard'], $currentUri);
        echo nav_link('/admin/posts.php',        'Blog Posts',   $icons['posts'],     $currentUri);
        echo '<a href="/admin/post-edit.php" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-white/10 transition ml-4">+ New Post</a>';
        echo nav_link('/admin/events.php',       'Events',       $icons['events'],    $currentUri);
        echo '<a href="/admin/event-edit.php" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white hover:bg-white/10 transition ml-4">+ New Event</a>';
        echo nav_link('/admin/subscribers.php',  'Subscribers',  $icons['subs'],      $currentUri);
        echo nav_link('/admin/donations.php',    'Donations',    $icons['donate'],    $currentUri);
        ?>
      </nav>
      <div class="mt-auto pt-4 border-t border-white/10">
        <a href="/admin/logout.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-white/10 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          <span>Logout</span>
        </a>
      </div>
    </aside>
  </div>

  <main class="flex-1 p-6 md:p-8 max-w-5xl w-full">

    <?php if ($flash): ?>
      <div class="mb-6 rounded-lg px-4 py-3 text-sm font-medium
        <?= $flash['type'] === 'error' ? 'bg-red-50 border border-red-300 text-red-700' : 'bg-green-50 border border-green-300 text-green-700' ?>">
        <?= e($flash['msg']) ?>
      </div>
    <?php endif; ?>

