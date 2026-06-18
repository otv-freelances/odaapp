<?php
$page = [
  'body_class' => 'volunteer-page',
  'meta_title' => 'Volunteer Opportunities in Toledo | On-Deck Alliance',
  'meta_description' => 'Volunteer with On-Deck Alliance and support youth skateboarding, mentorship, and community events in Toledo.',
  'hero' => [
    'layout' => 'split_image',
    'eyebrow' => 'Volunteer',
    'title' => "Volunteer and help grow\nyouth skateboarding\nin Toledo",
    'copy' => 'Volunteer roles support events, coaching, outreach, school partnerships, and the day-to-day energy that keeps students engaged.',
    'image' => '/images/optimized/about-coach-collage.jpg',
    'alt' => 'Volunteers and mentors supporting youth',
    'caption' => [
      'eyebrow' => 'Community role',
      'copy' => 'Show up for students, events, and the momentum that makes the program feel alive.',
    ],
    'buttons' => [
      ['label' => 'Contact Us', 'href' => '#contact-form', 'class' => 'ep-btn--primary'],
      ['label' => 'Events', 'href' => '/events.php', 'class' => 'ep-btn--light'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'Why people help',
    'title' => 'Volunteer energy turns into visible support for students',
    'copy' => 'Whether you are helping behind the scenes or directly with young people, your presence helps keep the environment calm, encouraging, and consistent.',
    'image' => '/images/optimized/about-group-skateboards.jpg',
    'alt' => 'ODA community moment',
    'bullets' => [
      'Community events and open sessions',
      'Youth-facing encouragement and mentorship',
      'Program support that scales with real needs',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'Where volunteers fit',
    'title' => 'Practical roles, real relationships, and clear impact',
    'copy' => 'Volunteers help students feel welcomed, seen, and supported. They also help the program stay organized enough to keep growing.',
    'image' => '/images/optimized/about-coach-collage.jpg',
    'alt' => 'Mentorship and coaching',
    'cards' => [
      ['eyebrow' => 'Coaching', 'title' => 'Lead by example', 'copy' => 'Encouragement and patience matter as much as skill.'],
      ['eyebrow' => 'Support', 'title' => 'Help run events', 'copy' => 'From setup to check-in, logistics shape the experience.'],
      ['eyebrow' => 'Community', 'title' => 'Build trust', 'copy' => 'Students remember adults who keep returning.'],
    ],
  ],
  'form' => [
    'band_class' => 'ep-band--dark',
    'eyebrow' => 'Get involved',
    'title' => 'Send a message',
    'copy' => 'Tell us how you want to help and the ODA team will follow up.',
    'action' => '/contact-process.php',
    'hidden' => ['action' => 'contact', 'redirect' => 'volunteer.php'],
    'interest' => 'Interest',
    'button_label' => 'Send',
    'dark' => true,
    'csrf' => true,
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
