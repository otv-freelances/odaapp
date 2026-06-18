<?php
require_once __DIR__ . '/admin/auth.php';

$archive = [
  'body_class' => 'news-page',
  'meta_title' => 'News | On-Deck Alliance',
  'meta_description' => 'News, updates, and recent stories from On-Deck Alliance.',
  'eyebrow' => 'Stories / Updates / News',
  'title' => 'News',
  'copy' => 'Recent updates from the work: program changes, community stories, and the moments that help the bigger picture stay visible.',
  'hero_cards' => [
    ['image' => '/images/optimized/about-group-skateboards.jpg', 'title' => 'Updates', 'copy' => 'Fresh from the program.'],
    ['image' => '/images/optimized/smiling.webp', 'title' => 'Youth growth', 'copy' => 'Stories that feel human.'],
    ['image' => '/images/optimized/about-coach-collage.jpg', 'title' => 'ODA voice', 'copy' => 'What we are building.'],
  ],
  'items' => get_all_latest(),
  'title_cb' => fn($item) => normalize_mojibake($item['title'] ?? 'Untitled'),
  'body_cb' => fn($item) => normalize_mojibake(strip_tags($item['body'] ?? '')),
  'image_cb' => fn($item) => ($item['image'] ?: 'images/group shot.webp'),
  'meta_cb' => fn($item) => !empty($item['date']) ? date('M j, Y', strtotime($item['date'])) : '',
  'href_cb' => fn($item) => '/news/' . e(post_slug(normalize_mojibake($item['title'] ?? 'Untitled'))),
  'empty' => 'No posts have been published yet.',
];

require_once __DIR__ . '/partials/marketing-archive.php';
