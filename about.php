<?php
$page = [
  'body_class' => 'about-page',
  'meta_title' => 'About On-Deck Alliance | Toledo Skateboarding Nonprofit',
  'meta_description' => 'Learn how On-Deck Alliance supports youth in Toledo through skateboarding, mentorship, and community.',
  'hero' => [
    'layout' => 'stacked_cards',
    'eyebrow' => 'About ODA',
    'title' => 'Skateboarding for self-sufficiency & growth',
    'copy' => 'On-Deck Alliance uses skateboarding, mentorship, and community-based programming to help young people in Toledo build confidence, connection, and long-term momentum.',
    'buttons' => [
      ['label' => 'Explore Program', 'href' => '/program.php', 'class' => 'ep-btn--primary'],
      ['label' => 'Contact', 'href' => '/contact.php', 'class' => 'ep-btn--light'],
    ],
    'cards' => [
      ['image' => '/images/optimized/about-group-skateboards.jpg', 'title' => 'Youth first', 'copy' => 'Community-built support rooted in belonging.'],
      ['image' => '/images/optimized/about-kids-ramp-bw.jpg', 'title' => 'Safe practice', 'copy' => 'Growth through repetition and real support.'],
      ['image' => '/images/optimized/about-coach-collage.jpg', 'title' => 'Trusted mentors', 'copy' => 'Adults who keep showing up.'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'What we build',
    'title' => 'Building skateboarding opportunities in Toledo',
    'copy' => 'We create spaces where skateboarding becomes a vehicle for confidence, belonging, and self-expression. The goal is bigger than tricks: we want young people to feel supported while they practice resilience in real time.',
    'image' => '/images/group shot.webp',
    'alt' => 'Youth and mentors in the community',
    'bullets' => [
      'Principle-based youth development',
      'Free and accessible programming',
      'Strong adult mentorship and community ties',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'How it feels',
    'title' => 'A program shaped by people, not just activities',
    'copy' => 'Every session is designed to be engaging, structured, and human. Students learn by doing, mentors reinforce progress, and families see growth that carries beyond the skate session itself.',
    'image' => '/images/optimized/about-founder-megaphone.jpg',
    'alt' => 'ODA speaking to the community',
    'cards' => [
      ['eyebrow' => 'Mentorship', 'title' => 'Real relationships', 'copy' => 'Students build trust with adults who stay consistent.'],
      ['eyebrow' => 'Wellness', 'title' => 'Whole-student support', 'copy' => 'Physical activity and emotional growth move together.'],
      ['eyebrow' => 'Belonging', 'title' => 'A place to show up', 'copy' => 'Young people feel seen, supported, and expected.'],
    ],
  ],
  'grid' => [
    'band_class' => 'ep-band--soft',
    'eyebrow' => 'Our approach',
    'title' => 'Guided by youth development, physical activity, and social-emotional learning',
    'copy' => 'The work blends movement, mentorship, and community. We keep the structure simple, visual, and image-led—just like the way students experience it.',
    'cards' => [
      ['image' => '/images/smiling.webp', 'title' => 'Mental wellness', 'copy' => 'Practice becomes confidence and self-belief.'],
      ['image' => '/images/optimized/about-kids-ramp-bw.jpg', 'title' => 'Safe spaces', 'copy' => 'Students need places where learning feels active and social.'],
      ['image' => '/images/optimized/about-coach-collage.jpg', 'title' => 'Mentorship', 'copy' => 'Supportive adults help students connect effort with progress.'],
    ],
  ],
  'cta' => [
    'eyebrow' => 'Take the next step',
    'title' => 'Whether you are looking for a place to belong or a way to help, we are ready',
    'copy' => 'Families, schools, volunteers, and partners all have a place in the ecosystem around ODA.',
    'image' => '/images/optimized/about-board-detail.jpg',
    'alt' => 'Skateboard detail close-up',
    'buttons' => [
      ['label' => 'Join I CAN', 'href' => '/program.php', 'class' => 'ep-btn--primary'],
      ['label' => 'Volunteer', 'href' => '/volunteer.php', 'class' => 'ep-btn--green'],
    ],
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
