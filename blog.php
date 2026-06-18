<?php
require_once __DIR__ . '/admin/auth.php';

$archive = [
  'body_class' => 'blog-page',
  'meta_title' => 'Blog & Events | On-Deck Alliance',
  'meta_description' => 'Stories, updates, and events from On-Deck Alliance.',
  'eyebrow' => 'Stories / Updates / Events',
  'title' => 'Blog & Events',
  'copy' => 'A more visual archive of the stories, updates, and community moments shaping the work around ODA.',
  'hero_cards' => [
    ['image' => '/images/group shot.webp', 'title' => 'Community', 'copy' => 'Events and milestones.'],
    ['image' => '/images/optimized/about-coach-collage.jpg', 'title' => 'Mentorship', 'copy' => 'Stories from the ground.'],
    ['image' => '/images/optimized/raising-hands.webp', 'title' => 'Youth voice', 'copy' => 'What growth looks like.'],
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
