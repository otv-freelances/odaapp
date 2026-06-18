<?php
$page = [
  'body_class' => 'schools-page',
  'meta_title' => 'For Schools | Bring ODA to Your School',
  'meta_description' => 'Bring On-Deck Alliance programming to your school through skateboarding, mentorship, and youth development.',
  'hero' => [
    'layout' => 'split_image',
    'eyebrow' => 'For schools',
    'title' => 'Bring image-based, youth-centered programming to your school',
    'copy' => 'ODA offers a hands-on program that gives students a different way to practice confidence, wellness, social-emotional learning, and belonging.',
    'image' => '/images/for educators.webp',
    'alt' => 'Students and educators engaging in programming',
    'caption' => [
      'eyebrow' => 'School partnerships',
      'copy' => 'A different kind of program for students who need active, relational support.',
    ],
    'buttons' => [
      ['label' => 'Request Partnership', 'href' => '#contact-form', 'class' => 'ep-btn--primary'],
      ['label' => 'View Program', 'href' => '/program.php', 'class' => 'ep-btn--light'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'What schools gain',
    'title' => 'A different lane for students who need a different point of entry',
    'copy' => 'Some students respond best to programs that are active, visual, and relationship-based. ODA helps create that entry point while aligning with broader youth development goals.',
    'image' => '/images/kids raising hands.webp',
    'alt' => 'Students participating together',
    'bullets' => [
      'Physical activity with strong mentor presence',
      'Confidence-building through visible progress',
      'A supportive culture that complements school-day efforts',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'Inside the partnership',
    'title' => 'Built for educators who want programming that feels engaging and grounded',
    'copy' => 'We work with schools to make youth development feel accessible, structured, and relevant to the students in front of us.',
    'image' => '/images/for educators.webp',
    'alt' => 'Students and educators in a school program',
    'cards' => [
      ['eyebrow' => 'Access', 'title' => 'Practical implementation', 'copy' => 'Flexible collaboration that meets schools where they are.'],
      ['eyebrow' => 'Support', 'title' => 'Whole-student approach', 'copy' => 'Programming that reinforces confidence and belonging.'],
      ['eyebrow' => 'Community', 'title' => 'Shared outcomes', 'copy' => 'Students experience consistency across adults and settings.'],
    ],
  ],
  'form' => [
    'band_class' => 'ep-band--dark',
    'eyebrow' => 'Talk with us',
    'title' => 'Start a school partnership conversation',
    'copy' => 'Tell us about your students, school, or organization and the ODA team will follow up.',
    'action' => '/contact-process.php',
    'hidden' => ['action' => 'contact', 'redirect' => 'for-schools.php'],
    'interest' => 'Interest',
    'button_label' => 'Send',
    'dark' => true,
    'csrf' => true,
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
