<?php
$page = [
  'body_class' => 'programs-page',
  'meta_title' => 'Free Toledo Skateboarding Lessons for Kids | On-Deck Alliance',
  'meta_description' => 'Explore free skateboarding lessons and youth development programming for kids in Toledo through On-Deck Alliance.',
  'hero' => [
    'layout' => 'mono_cards',
    'eyebrow' => 'Programs',
    'title' => 'Youth mentorship through skateboarding',
    'copy' => 'ODA programs give Toledo youth a place to move, practice, connect, and grow. They are free, welcoming to beginners, and built around visible progress.',
    'buttons' => [
      ['label' => 'I CAN Program', 'href' => '/program.php', 'class' => 'ep-btn--primary'],
      ['label' => 'Sign Up', 'href' => '/program.php#signup', 'class' => 'ep-btn--green'],
    ],
    'cards' => [
      ['image' => '/images/optimized/i-can.webp', 'title' => 'I CAN', 'copy' => 'Core principle-based program.'],
      ['image' => '/images/optimized/kids.webp', 'title' => 'Youth sessions', 'copy' => 'Movement, mentorship, and belonging.'],
      ['image' => '/images/optimized/smiling.webp', 'title' => 'Confidence', 'copy' => 'Small wins that compound.'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'How it works',
    'title' => 'Programs that turn practice into confidence',
    'copy' => 'Our youth programs focus on consistency, encouragement, and skill-building. The result is more than skateboarding—it is a space where students can experience progress in a way that feels real.',
    'image' => '/images/optimized/push-ups-fun.webp',
    'alt' => 'Students in a program session',
    'bullets' => [
      'Free participation for Toledo youth',
      'Mentorship and positive peer culture',
      'Skateboarding as a tool for growth',
    ],
  ],
  'grid' => [
    'band_class' => 'ep-band--soft',
    'eyebrow' => 'Program lanes',
    'title' => 'More image-based, more engaging, more human',
    'copy' => 'We want the page system to feel like the program itself: visual, energetic, and grounded in people.',
    'cards' => [
      ['image' => '/images/optimized/i-can.webp', 'title' => 'I CAN Program', 'copy' => 'Core youth development through skateboarding.'],
      ['image' => '/images/optimized/raising-hands.webp', 'title' => 'School engagement', 'copy' => 'Opportunities that connect beyond one session.'],
      ['image' => '/images/optimized/about-group-skateboards.jpg', 'title' => 'Community activation', 'copy' => 'Programming that grows through relationships.'],
    ],
  ],
  'cta' => [
    'eyebrow' => 'Ready to get involved?',
    'title' => 'Whether you are a family, school, or supporter, there is a next step',
    'copy' => 'Start with the I CAN Program, reach out about partnerships, or support the mission directly.',
    'image' => '/images/group shot.webp',
    'alt' => 'Group shot from ODA',
    'buttons' => [
      ['label' => 'Explore I CAN', 'href' => '/program.php', 'class' => 'ep-btn--primary'],
      ['label' => 'For Schools', 'href' => '/for-schools.php', 'class' => 'ep-btn--light'],
    ],
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
