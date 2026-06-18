<?php
require_once __DIR__ . '/admin/auth.php';

$archive = [
  'body_class' => 'events-page',
  'meta_title' => 'Events | On-Deck Alliance',
  'meta_description' => 'See upcoming and recent On-Deck Alliance events in Toledo.',
  'eyebrow' => 'Community / Programs / Meetups',
  'title' => 'Events',
  'copy' => 'A more image-based event archive for community days, sessions, and public moments around the program.',
  'hero_cards' => [
    ['image' => '/images/group shot.webp', 'title' => 'Community days', 'copy' => 'Shared local moments.'],
    ['image' => '/images/partners/new/IMG20230818171950.jpg', 'title' => 'Events', 'copy' => 'People showing up together.'],
    ['image' => '/images/optimized/donate-group-skateboards.jpg', 'title' => 'Participation', 'copy' => 'Youth, families, and partners.'],
  ],
  'items' => get_all_events(),
  'title_cb' => fn($item) => normalize_mojibake($item['title'] ?? 'Event'),
  'body_cb' => fn($item) => normalize_mojibake(strip_tags($item['body'] ?? '')),
  'image_cb' => fn($item) => ($item['image'] ?: 'images/group shot.webp'),
  'meta_cb' => fn($item) => !empty($item['date']) ? date('F j, Y', strtotime($item['date'])) : '',
  'href_cb' => fn($item) => '',
  'empty' => 'No events yet.',
];

require_once __DIR__ . '/partials/marketing-archive.php';
