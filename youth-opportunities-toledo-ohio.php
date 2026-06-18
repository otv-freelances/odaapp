<?php
$page = [
  'body_class' => 'youth-page',
  'meta_title' => 'Youth Opportunities in Toledo, Ohio | On-Deck Alliance',
  'meta_description' => 'Explore youth opportunities in Toledo, Ohio through skateboarding, mentorship, and community support with On-Deck Alliance.',
  'hero' => [
    'layout' => 'mono_cards',
    'eyebrow' => 'Toledo youth opportunities',
    'title' => "Youth opportunities\nin Toledo, Ohio",
    'copy' => 'Real programs. Real mentorship. Real impact. On-Deck Alliance creates youth opportunities in Toledo through skateboarding, mentorship, confidence-building, and strong community support.',
    'buttons' => [
      ['label' => 'Explore Program', 'href' => '/program.php', 'class' => 'ep-btn--primary'],
      ['label' => 'For Schools', 'href' => '/for-schools.php', 'class' => 'ep-btn--green'],
    ],
    'cards' => [
      ['image' => '/images/hero-poster.webp', 'title' => 'Visible growth', 'copy' => 'Progress students can feel.'],
      ['image' => '/images/kids.webp', 'title' => 'Belonging', 'copy' => 'A place to show up and stay.'],
      ['image' => '/images/kids raising hands.webp', 'title' => 'Mentorship', 'copy' => 'Support from caring adults.'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'Why this matters',
    'title' => 'A youth-focused page with a stronger visual story',
    'copy' => 'This page exists to help families, educators, and supporters understand that youth opportunities in Toledo can be active, relational, and deeply community-based.',
    'image' => '/images/kids.webp',
    'alt' => 'Youth programming',
    'bullets' => [
      'Programs for beginners and returning students',
      'Mentorship rooted in real consistency',
      'A stronger sense of place, belonging, and progress',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'Who it serves',
    'title' => 'Built for students who need encouragement, structure, and a different lane',
    'copy' => 'Not every young person connects to traditional activities. That is exactly why image-based, movement-centered, relationship-based programming matters.',
    'image' => '/images/optimized/raising-hands.webp',
    'alt' => 'Students raising hands',
    'cards' => [
      ['eyebrow' => 'Families', 'title' => 'Positive after-school options', 'copy' => 'Programs that feel safe, social, and meaningful.'],
      ['eyebrow' => 'Schools', 'title' => 'Mission-aligned support', 'copy' => 'Community programs that reinforce growth and belonging.'],
      ['eyebrow' => 'Youth', 'title' => 'A visible path forward', 'copy' => 'Students get to practice progress in public.'],
    ],
  ],
  'grid' => [
    'band_class' => 'ep-band--soft',
    'eyebrow' => 'Opportunity lanes',
    'title' => 'Programs, events, and mentorship that feel real',
    'copy' => 'This is the broader picture behind the phrase “youth opportunities”: repeated access, trusted adults, and a culture that invites students back.',
    'cards' => [
      ['image' => '/images/kids.webp', 'title' => 'After-school sessions', 'copy' => 'Weekly opportunities for movement and growth.'],
      ['image' => '/images/group shot.webp', 'title' => 'Community events', 'copy' => 'Open moments that build connection.'],
      ['image' => '/images/hero-poster.webp', 'title' => 'Mentorship', 'copy' => 'Encouragement that stays consistent over time.'],
    ],
  ],
  'cta' => [
    'eyebrow' => 'Get involved',
    'title' => 'Whether you are a family, mentor, or partner, there is a way in',
    'copy' => 'Use this page as an SEO doorway, but let the next click take people into the deeper work.',
    'image' => '/images/optimized/about-group-skateboards.jpg',
    'alt' => 'ODA youth group',
    'buttons' => [
      ['label' => 'Join I CAN', 'href' => '/program.php#signup', 'class' => 'ep-btn--primary'],
      ['label' => 'Volunteer', 'href' => '/volunteer.php', 'class' => 'ep-btn--light'],
    ],
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
