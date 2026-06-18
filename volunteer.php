<?php
$page = [
  'body_class' => 'volunteer-page',
  'meta_title' => 'Volunteer Opportunities in Toledo, Ohio | On-Deck Alliance',
  'meta_description' => 'Explore volunteer opportunities in Toledo, Ohio with On-Deck Alliance. Support youth through skateboarding, mentorship, events, and group service.',
  'hero' => [
    'layout' => 'split_image',
    'eyebrow' => 'Volunteer with us',
    'title' => "Volunteer opportunities\nin Toledo, Ohio",
    'copy' => 'Support youth, build community, and make a real impact through skateboarding, mentorship, events, and group service. Our volunteer program is flexible, meaningful, and accessible - no skating experience required.',
    'image' => '/images/optimized/about-coach-collage.jpg',
    'alt' => 'Volunteers and mentors supporting youth',
    'caption' => [
      'eyebrow' => 'Community role',
      'copy' => 'Individuals, students, and groups can all help create safe, structured, welcoming experiences for Toledo youth.',
    ],
    'buttons' => [
      ['label' => 'Sign Up', 'href' => '#contact-form', 'class' => 'ep-btn--primary'],
      ['label' => 'Group Opportunities', 'href' => '#group-volunteer', 'class' => 'ep-btn--light'],
    ],
  ],
  'feature' => [
    'eyebrow' => 'Why volunteer with us?',
    'title' => 'Your time supports more than events - it supports youth development',
    'copy' => 'When you volunteer, you are directly supporting programs that create safe, structured environments for youth, mentorship and life-skill development, access to skateboarding and creative expression, and hands-on learning connected to STEM and STEAM principles.',
    'image' => '/images/optimized/about-group-skateboards.jpg',
    'alt' => 'ODA community moment',
    'bullets' => [
      'Safe, structured environments for youth',
      'Mentorship and life-skills development',
      'Access to skateboarding and creative expression',
      'Hands-on learning connected to STEM and STEAM',
    ],
  ],
  'showcase' => [
    'eyebrow' => 'Featured opportunity',
    'title' => 'Game day volunteering is one of the most consistent ways to help',
    'copy' => 'One of the most popular ways to volunteer in Toledo is through our partnership with University of Toledo athletics. Volunteers help run concession stands during football games, work together in a fast-paced environment, and support a great cause while being part of the game day experience.',
    'image' => '/images/group shot.jpg',
    'alt' => 'Community group gathered together',
    'cards' => [
      ['eyebrow' => 'What you will do', 'title' => 'Concessions and support', 'copy' => 'Help run concession stands and support game day operations.'],
      ['eyebrow' => 'Best for groups', 'title' => 'Teams, clubs, and orgs', 'copy' => 'A strong fit for student organizations, clubs, and teams volunteering together.'],
      ['eyebrow' => 'Flexible', 'title' => 'Built around game days', 'copy' => 'Scheduling can align around available athletic event dates.'],
    ],
  ],
  'grid' => [
    'eyebrow' => 'More ways to volunteer',
    'title' => 'Practical roles with visible impact across the program',
    'copy' => 'In addition to game day opportunities, volunteers can support events, youth coaching, and the local skate community in ways that make the program feel more welcoming and more organized.',
    'cards' => [
      ['image' => '/images/optimized/donate-group-skateboards.jpg', 'title' => 'Event support', 'copy' => 'Assist with local skate events, outreach, setup, coordination, and engagement.'],
      ['image' => '/images/optimized/donate-lesson-coach.jpg', 'title' => 'Youth coaching', 'copy' => 'Support kids during sessions and encourage confidence, creativity, and growth.'],
      ['image' => '/images/optimized/donate-helmet-bw.jpg', 'title' => 'Skate community involvement', 'copy' => 'Help create safe and welcoming environments within the local skate scene.'],
    ],
  ],
  'sections' => [
    [
      'id' => 'group-volunteer',
      'layout' => 'split_image',
      'eyebrow' => 'Toledo volunteer opportunities for groups',
      'title' => 'Bring your team, club, class, or organization together',
      'copy' => 'We specialize in group-based volunteering, which is surprisingly hard to find locally. If you are searching for volunteer opportunities near you that allow your group to participate together, this is a strong fit. Not part of a group? No problem - individuals are always welcome too.',
      'image' => '/images/kids in front of lockers.webp',
      'alt' => 'Students and volunteers together indoors',
      'bullets' => [
        'College organizations',
        'Fraternities and sororities',
        'High school groups',
        'Corporate teams',
        'Local clubs and community organizations',
      ],
      'buttons' => [
        ['label' => 'Volunteer as a Group', 'href' => '#contact-form', 'class' => 'ep-btn--primary'],
        ['label' => 'Partner With Us', 'href' => '/partner.php', 'class' => 'ep-btn--dark'],
      ],
    ],
    [
      'layout' => 'cta',
      'band_class' => 'ep-band--blue',
      'eyebrow' => 'Partner with us',
      'title' => 'Businesses and organizations can support youth through action',
      'copy' => 'If you want to sponsor events, collaborate on programs, or support youth initiatives, we would love to connect. Volunteer energy and organizational support both help expand what is possible in Toledo.',
      'image' => '/images/chandler helpn with helmet.webp',
      'alt' => 'Mentor helping a student with safety gear',
      'bullets' => [
        'Sponsor events',
        'Collaborate on programs',
        'Support youth initiatives',
      ],
      'buttons' => [
        ['label' => 'Apply Here', 'href' => '#contact-form', 'class' => 'ep-btn--light'],
        ['label' => 'Business Partnership', 'href' => '/partner.php', 'class' => 'ep-btn--dark'],
      ],
    ],
    [
      'layout' => 'grid',
      'eyebrow' => 'Frequently asked questions',
      'title' => 'Quick answers for new volunteers',
      'copy' => 'If you have been searching for volunteer opportunities Toledo Ohio, volunteer in Toledo Ohio, or volunteering in Toledo, this is a good place to start.',
      'cards' => [
        [
          'image' => '/images/optimized/donate-helmet-bw.jpg',
          'title' => 'How do I volunteer?',
          'copy' => 'Fill out the form below and we will connect you with the best fit based on your availability and interests.',
        ],
        [
          'image' => '/images/optimized/donate-lesson-coach.jpg',
          'title' => 'Do I need skate experience?',
          'copy' => 'No. There are many ways to help that do not require any skating background at all.',
        ],
        [
          'image' => '/images/optimized/donate-group-skateboards.jpg',
          'title' => 'Can students earn service hours?',
          'copy' => 'Yes. Many of these opportunities work well for students needing volunteer or service hours.',
        ],
      ],
    ],
  ],
  'form' => [
    'band_class' => 'ep-band--dark',
    'eyebrow' => 'Ready to volunteer?',
    'title' => 'Send us a message',
    'copy' => 'Tell us whether you are signing up as an individual or a group, what kind of opportunity interests you, and when you are available. We will follow up with next steps and current opportunities.',
    'action' => '/contact-process.php',
    'hidden' => ['action' => 'contact', 'redirect' => 'volunteer.php'],
    'interest' => 'Interest',
    'button_label' => 'Send',
    'dark' => true,
    'csrf' => true,
  ],
];

require_once __DIR__ . '/partials/marketing-page.php';
