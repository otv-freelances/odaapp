<?php
require_once __DIR__ . '/admin/auth.php';

$page = [
  'body_class' => 'contact-page',
  'meta_title' => 'Contact | On-Deck Alliance',
  'meta_description' => 'Contact On-Deck Alliance about programs, partnerships, volunteering, and support.',
  'hero' => [
    'layout' => 'contact_panel',
    'band_class' => 'ep-band--blue',
    'eyebrow' => 'Contact',
    'title' => "Let's start a\nconversation",
    'copy' => 'Reach out about programs, volunteering, partnerships, donations, or any other way you want to connect with ODA.',
    'buttons' => [
      ['label' => 'Send Message', 'href' => '#contact-form', 'class' => 'ep-btn--light'],
      ['label' => 'View Program', 'href' => '/program.php', 'class' => 'ep-btn--dark'],
    ],
    'panel' => [
      'title' => 'How we can help',
      'copy' => 'Use this page for questions about youth programming, volunteering, school partnerships, community support, and getting involved.',
      'items' => [
        'Programs and sign-up questions',
        'School and community partnerships',
        'Volunteer, donate, or collaborate',
      ],
      'buttons' => [
        ['label' => 'Volunteer', 'href' => '/volunteer.php', 'class' => 'ep-btn--green'],
      ],
    ],
  ],
  'form' => [
    'band_class' => 'ep-band--dark',
    'eyebrow' => 'Reach out',
    'title' => 'Send us a message',
    'copy' => 'Tell us what you are looking for and we will follow up.',
    'action' => '/contact-process.php',
    'hidden' => ['action' => 'contact', 'redirect' => 'contact.php'],
    'button_label' => 'Send',
    'dark' => true,
    'csrf' => true,
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';

