<?php
$page = [
  'body_class' => 'donate-page',
  'meta_title' => 'Support Free Toledo Skateboarding Lessons | On-Deck Alliance',
  'meta_description' => 'Support free skateboarding lessons, mentorship, gear, and safe community spaces for Toledo youth.',
  'hero' => [
    'layout' => 'split_image',
    'eyebrow' => 'Support ODA',
    'title' => 'Your support helps us expand access, mentorship, and momentum',
    'copy' => 'Every donation helps fund boards, helmets, lessons, transportation support, and the kind of programming that keeps students coming back.',
    'image' => '/images/optimized/donate-group-skateboards.jpg',
    'alt' => 'Students with skateboards',
    'caption' => [
      'eyebrow' => 'Free programs',
      'copy' => 'Support turns into gear, safe participation, and more students getting to stay involved.',
    ],
    'buttons' => [
      ['label' => 'Donate Now', 'href' => 'javascript:openDonateModal()', 'class' => 'ep-btn--primary'],
      ['label' => 'Volunteer', 'href' => '/volunteer.php', 'class' => 'ep-btn--green'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'Why giving matters',
    'title' => 'Skateboarding is the hook — support is what makes it sustainable',
    'copy' => 'Students stay involved when access is real. Donations help us cover gear, free sessions, community events, and the behind-the-scenes support required to keep a youth program healthy.',
    'image' => '/images/optimized/donate-kids-ramp-bw.jpg',
    'alt' => 'Students skating together',
    'bullets' => [
      'Helmets, pads, and boards',
      'Free lessons and recurring sessions',
      'Safe spaces for youth to practice and belong',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'What your gift fuels',
    'title' => 'Real resources that show up in everyday program life',
    'copy' => 'Support is visible in the details: gear that fits, lessons that stay free, and adults with enough capacity to keep youth engaged over time.',
    'image' => '/images/group shot.webp',
    'alt' => 'Group photo from the program',
    'cards' => [
      ['eyebrow' => 'Gear', 'title' => 'Boards & helmets', 'copy' => 'Students can participate without buying in first.'],
      ['eyebrow' => 'Sessions', 'title' => 'Free program access', 'copy' => 'Families are not priced out of consistent support.'],
      ['eyebrow' => 'Growth', 'title' => 'Long-term mentorship', 'copy' => 'Funding creates the conditions for steady relationships.'],
    ],
  ],
  'grid' => [
    'eyebrow' => 'Where impact lands',
    'title' => 'Image-based support for a very real day-to-day mission',
    'copy' => 'Your support does not disappear into abstraction. It becomes visible in how students arrive, what they have access to, and whether they can keep going.',
    'cards' => [
      ['image' => '/images/optimized/donate-helmet-bw.jpg', 'title' => 'Safety', 'copy' => 'Protective gear lowers risk and raises confidence.'],
      ['image' => '/images/optimized/donate-lesson-coach.jpg', 'title' => 'Instruction', 'copy' => 'Students get feedback, structure, and encouragement.'],
      ['image' => '/images/optimized/donate-group-skateboards.jpg', 'title' => 'Community', 'copy' => 'Funding helps keep the whole environment welcoming.'],
    ],
  ],
  'cta' => [
    'band_class' => 'ep-band--blue',
    'eyebrow' => 'Ready to help?',
    'title' => 'You can fund confidence, consistency, and belonging',
    'copy' => 'If you want to help Toledo youth access something meaningful, this is a direct way to do it.',
    'image' => '/images/optimized/donate-student-lockers.jpg',
    'alt' => 'Student-focused program image',
    'buttons' => [
      ['label' => 'Open Donate Modal', 'href' => 'javascript:openDonateModal()', 'class' => 'ep-btn--light'],
      ['label' => 'Partner With Us', 'href' => '/partner.php', 'class' => 'ep-btn--dark'],
    ],
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
