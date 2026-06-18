<?php
$page = [
  'body_class' => 'partner-page',
  'meta_title' => 'Skateboarding Nonprofit Partners & Sponsors | On-Deck Alliance',
  'meta_description' => 'Meet ODA partners and sponsors and learn how partnerships support youth skateboarding in Toledo.',
  'hero' => [
    'layout' => 'dark_showcase',
    'band_class' => 'ep-hero--dark',
    'eyebrow' => 'Partners',
    'title' => 'Build skateboarding opportunities with us',
    'copy' => 'Local and national partners help provide facilities, gear, event support, mentorship, and the resources that keep youth programs accessible.',
    'image' => '/images/partners/new/PXL_20240627_223104388.jpg',
    'alt' => 'Partner-supported ODA community moment',
    'chips' => ['Sponsorship', 'Events', 'Resources', 'Community'],
    'notes' => [
      ['eyebrow' => 'Support', 'title' => 'Resource the mission', 'copy' => 'Partnership gives students more visible access.'],
      ['eyebrow' => 'Collaboration', 'title' => 'Grow the ecosystem', 'copy' => 'Schools, brands, and local leaders can help shape what is possible.'],
    ],
    'buttons' => [
      ['label' => 'Partner With Us', 'href' => '#contact-form', 'class' => 'ep-btn--primary'],
      ['label' => 'Donate', 'href' => '/donate.php', 'class' => 'ep-btn--green'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'Why partnership matters',
    'title' => 'Partnerships help turn local belief into visible access',
    'copy' => 'When schools, brands, organizations, and community leaders show up together, students get more than skate time—they get a stronger ecosystem around them.',
    'image' => '/images/partners/new/IMG_20240708_203054_03.jpg',
    'alt' => 'Partner-supported programming',
    'bullets' => [
      'Event support and activation',
      'Equipment, gear, and sponsorship',
      'Local visibility and community credibility',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'What collaboration looks like',
    'title' => 'A partner page that feels more like the work itself',
    'copy' => 'We want partnership to feel concrete and image-driven: students, spaces, events, and visible support all in one frame.',
    'image' => '/images/group shot.webp',
    'alt' => 'Large ODA group photo',
    'cards' => [
      ['eyebrow' => 'Sponsor', 'title' => 'Resource the mission', 'copy' => 'Help us keep programming free and visible.'],
      ['eyebrow' => 'Host', 'title' => 'Open doors locally', 'copy' => 'Bring the work into schools, events, and neighborhoods.'],
      ['eyebrow' => 'Amplify', 'title' => 'Share the story', 'copy' => 'Help more families and supporters find the work.'],
    ],
  ],
  'form' => [
    'eyebrow' => 'Start a conversation',
    'title' => 'Let’s build something meaningful together',
    'copy' => 'Tell us what kind of partnership you are considering and we will follow up.',
    'action' => '/contact-process.php',
    'hidden' => ['action' => 'contact', 'redirect' => 'partner.php'],
    'interest' => 'Interest',
    'button_label' => 'Send',
    'dark' => false,
    'csrf' => true,
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
