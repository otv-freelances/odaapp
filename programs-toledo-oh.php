<?php
$page = [
  'body_class' => 'programs-toledo-page',
  'meta_title' => 'Skateboarding Youth Programs in Toledo, OH | On-Deck Alliance',
  'meta_description' => 'Learn about skateboarding youth programs in Toledo, Ohio through On-Deck Alliance.',
  'hero' => [
    'layout' => 'split_image',
    'eyebrow' => 'Toledo, Ohio',
    'title' => 'Building skateboarding opportunities in Toledo',
    'copy' => 'This page is built for people specifically looking for youth skateboarding programs in Toledo, Ohio—families, students, and educators who want a local place to start.',
    'image' => '/images/group shot.webp',
    'alt' => 'Toledo youth with skateboards',
    'caption' => [
      'eyebrow' => 'Local focus',
      'copy' => 'Programs, mentors, and opportunities grounded in Toledo.',
    ],
    'buttons' => [
      ['label' => 'Explore Program', 'href' => '/program.php', 'class' => 'ep-btn--primary'],
      ['label' => 'Contact', 'href' => '/contact.php', 'class' => 'ep-btn--light'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'Why local matters',
    'title' => 'A city-specific program page that feels grounded in real people',
    'copy' => 'Toledo youth need opportunities that feel close, consistent, and accessible. ODA helps meet that need through skateboarding-based youth development and community support.',
    'image' => '/images/optimized/smiling.webp',
    'alt' => 'Local student portrait',
    'bullets' => [
      'Toledo-centered programming',
      'Free sessions for young people',
      'Strong alignment with youth development goals',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'What students get',
    'title' => 'Support, structure, and a lane to grow',
    'copy' => 'Young people need more than activities. They need environments where they can feel safe, practice, fail, recover, and keep moving.',
    'image' => '/images/optimized/kids.webp',
    'alt' => 'Students in program',
    'cards' => [
      ['eyebrow' => 'Access', 'title' => 'Free participation', 'copy' => 'Barriers stay low so students can keep showing up.'],
      ['eyebrow' => 'Growth', 'title' => 'Visible progress', 'copy' => 'Skateboarding turns effort into something students can see.'],
      ['eyebrow' => 'Mentorship', 'title' => 'Trusted adults', 'copy' => 'Relationships help the learning stick.'],
    ],
  ],
  'cta' => [
    'band_class' => 'ep-band--blue',
    'eyebrow' => 'Next steps',
    'title' => 'Start local, build bigger',
    'copy' => 'Use this page as a doorway into the broader ODA program ecosystem.',
    'image' => '/images/optimized/about-group-skateboards.jpg',
    'alt' => 'Group with skateboards',
    'buttons' => [
      ['label' => 'Join I CAN', 'href' => '/program.php#signup', 'class' => 'ep-btn--light'],
      ['label' => 'Volunteer', 'href' => '/volunteer.php', 'class' => 'ep-btn--dark'],
    ],
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
