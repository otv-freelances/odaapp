<?php
$signupSuccess = !empty($_GET['signup']) && $_GET['signup'] === 'success';

$homeMutable = [];
try {
  $homeJson = @file_get_contents(__DIR__ . '/data/home-mutable.json');
  if ($homeJson !== false) {
    $decoded = json_decode($homeJson, true);
    if (is_array($decoded)) {
      $homeMutable = $decoded;
    }
  }
} catch (Throwable $e) {
  $homeMutable = [];
}

$partnersCfg = $homeMutable['proud_partners'] ?? [];
$partnersList = $partnersCfg['partners'] ?? [];

if (!$partnersList) {
  $partnersList = [
    ['name' => 'Frederick Douglass', 'file' => 'frederick-douglass.png'],
    ['name' => 'City of Toledo', 'file' => 'toledo-youth-services.jpg'],
    ['name' => 'Dubs Foundation', 'file' => 'dubs-foundation.png'],
    ['name' => 'Advocate Skateworks', 'file' => 'advocate-skateworks.webp'],
    ['name' => 'Pizza Cat Toledo', 'file' => 'pizza-cat-toledo.png'],
    ['name' => 'Nuclear Skate Cult', 'file' => 'nuclear-skate-cult.jpg'],
    ['name' => 'Steez LLC', 'file' => 'steez-llc.webp'],
    ['name' => 'Lavish Hardware', 'file' => 'lavish-hardware.png'],
    ['name' => 'Modern Skate Shop', 'file' => 'modern-skate-shop.png'],
    ['name' => 'Just Skateboarding', 'file' => 'just-skateboarding.jpg'],
    ['name' => 'Rynos Skate Shop', 'file' => 'rynos-skate-shop.jpg'],
    ['name' => 'Lil Jawns', 'file' => 'lil-jawns.png'],
    ['name' => 'Vans', 'file' => 'vans.webp'],
    ['name' => 'Printed on a Lark', 'file' => 'printed-on-a-lark.png'],
    ['name' => 'Smollie Skateboard', 'file' => 'smollie-skateboard.webp'],
  ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Free Toledo Skateboarding Lessons for Kids (Ages 6–17) | On-Deck Alliance</title>
  <link rel="icon" type="image/webp" href="/images/favicon.webp">
  <meta name="description" content="Looking for skateboarding lessons near you in Toledo? Our nonprofit offers 100% free skateboarding lessons for kids ages 6–17. Beginners welcome—sign up today.">
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="/dist/output.css">
  <link rel="stylesheet" href="/skate-upgrade.css">
  <style>
    .ican-page {
      background:
        repeating-linear-gradient(135deg, rgba(17,17,17,0.035) 0 2px, transparent 2px 10px),
        #fbfbf8;
      color: #111;
      font-family: Arial, sans-serif;
    }

    .ican-shell {
      width: min(calc(100% - 32px), 76rem);
      margin: 0 auto;
    }

    .ican-kicker {
      margin: 0 0 10px;
      color: #cc3a20;
      font-weight: 900;
      font-size: 0.68rem;
      letter-spacing: 0.14em;
      text-transform: uppercase;
    }

    .ican-title,
    .ican-section-title,
    .ican-panel-title,
    .eys-headline,
    .ican-signup-title {
      margin: 0;
      font-family: "Arial Black", Arial, sans-serif;
      font-weight: 900;
      text-transform: uppercase;
      line-height: 0.96;
    }

    .ican-title {
      font-size: clamp(2.1rem, 4.3vw, 4rem);
      letter-spacing: 0.01em;
      max-width: 9.5ch;
    }

    .ican-title span {
      font-size: 0.82em;
    }

    .ican-copy,
    .ican-text,
    .ican-panel-copy,
    .eys-copy,
    .ican-signup-copy {
      color: #575757;
      line-height: 1.72;
      font-size: 0.96rem;
    }

    .ican-copy strong,
    .ican-text strong,
    .ican-panel-copy strong,
    .eys-copy strong,
    .ican-signup-copy strong {
      color: #111;
    }

    .ican-hero-wrap {
      padding: clamp(26px, 4vw, 44px) 0 12px;
    }

    .ican-hero-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.18fr) minmax(280px, 0.42fr);
      gap: clamp(18px, 3vw, 28px);
      align-items: start;
    }

    .ican-stage {
      position: relative;
      min-height: clamp(340px, 50vw, 585px);
      border-radius: 0 34px 0 34px;
      overflow: hidden;
      background: #111;
      box-shadow: 0 22px 42px rgba(0,0,0,0.14);
    }

    .ican-stage img {
      width: 100%;
      height: 100%;
      display: block;
      object-fit: cover;
    }

    .ican-stage::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(0,0,0,0.03), rgba(0,0,0,0.12));
      pointer-events: none;
    }

    .ican-stage-wordmark {
      position: absolute;
      left: clamp(16px, 4vw, 58px);
      top: clamp(14px, 3vw, 34px);
      margin: 0;
      font-family: "Arial Black", Arial, sans-serif;
      font-size: clamp(4rem, 9vw, 8rem);
      line-height: 0.82;
      text-transform: uppercase;
      color: #111;
      -webkit-text-stroke: 8px #00a85a;
      paint-order: stroke fill;
      text-shadow: 0 8px 18px rgba(0,0,0,0.14);
      z-index: 2;
    }

    .ican-hero-side {
      display: grid;
      gap: 18px;
      padding-top: 12px;
    }

    .ican-summary {
      padding: 16px 0 0;
    }

    .ican-summary .ican-copy {
      margin-top: 14px;
      font-size: 0.9rem;
    }

    .ican-cta-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 18px;
    }

    .ican-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 42px;
      padding: 0 18px;
      border-radius: 6px;
      text-decoration: none;
      border: 0;
      font-family: "Arial Black", Arial, sans-serif;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      cursor: pointer;
      transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .ican-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.12);
    }

    .ican-btn--primary { background: #cc3a20; color: #fff; }
    .ican-btn--green { background: #1db855; color: #fff; }
    .ican-btn--dark { background: #111; color: #fff; }
    .ican-btn--light { background: #fff; color: #111; border: 1px solid rgba(0,0,0,0.12); }

    .ican-side-photo {
      margin: 0;
      border-radius: 26px 26px 26px 8px;
      overflow: hidden;
      background: #111;
      box-shadow: 0 18px 32px rgba(0,0,0,0.12);
    }

    .ican-side-photo img {
      width: 100%;
      height: 152px;
      object-fit: cover;
      display: block;
      filter: grayscale(1) contrast(1.08);
    }

    .ican-partners {
      padding: 24px 0 30px;
    }

    .ican-partners-grid {
      display: grid;
      grid-template-columns: repeat(15, minmax(0, 1fr));
      gap: 10px;
      align-items: center;
    }

    .ican-partner {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 44px;
      padding: 8px;
      background: rgba(255,255,255,0.84);
      border: 1px solid rgba(0,0,0,0.05);
      box-shadow: 0 10px 18px rgba(0,0,0,0.04);
    }

    .ican-partner img {
      width: 100%;
      max-width: 64px;
      max-height: 38px;
      object-fit: contain;
      filter: grayscale(1) contrast(1.08);
      mix-blend-mode: multiply;
    }

    .ican-audience,
    .ican-practical,
    .ican-faq {
      padding: 10px 0 34px;
    }

    .ican-panel-grid,
    .ican-overview-grid,
    .ican-signup-grid {
      display: grid;
      grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
      gap: clamp(24px, 4vw, 42px);
      align-items: start;
    }

    .ican-overview {
      padding: 24px 0 30px;
    }

    .ican-card {
      background: rgba(255,255,255,0.94);
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 12px;
      box-shadow: 0 18px 34px rgba(0,0,0,0.07);
      overflow: hidden;
    }

    .ican-card__body {
      padding: 22px;
    }

    .ican-card__media {
      min-height: 100%;
      background: #111;
    }

    .ican-card__media img {
      width: 100%;
      height: 100%;
      min-height: 320px;
      object-fit: cover;
      display: block;
    }

    .ican-promote-list,
    .ican-bullet-list {
      list-style: none;
      margin: 18px 0 0;
      padding: 0;
      display: grid;
      gap: 18px;
    }

    .ican-promote-list li {
      display: grid;
      grid-template-columns: 24px 1fr;
      gap: 12px;
      align-items: start;
    }

    .ican-bullet-list {
      gap: 11px;
    }

    .ican-bullet-list li {
      position: relative;
      padding-left: 16px;
      color: #4f4f4f;
      line-height: 1.66;
    }

    .ican-bullet-list li::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0.64em;
      width: 7px;
      height: 7px;
      border-radius: 999px;
      background: #cc3a20;
    }

    .ican-promote-dot {
      width: 18px;
      height: 18px;
      border-radius: 999px;
      margin-top: 1px;
      position: relative;
    }

    .ican-promote-dot::after {
      content: "";
      position: absolute;
      inset: 5px;
      border-radius: 999px;
      background: rgba(255,255,255,0.9);
    }

    .ican-promote-dot--red { background: #cc3a20; }
    .ican-promote-dot--blue { background: #2f6fc1; }
    .ican-promote-dot--green { background: #1db855; }
    .ican-promote-dot--yellow { background: #f4a300; }

    .ican-promote-title {
      display: block;
      font-family: "Arial Black", Arial, sans-serif;
      font-size: 0.85rem;
      text-transform: uppercase;
      line-height: 1.08;
      letter-spacing: 0.02em;
    }

    .ican-promote-text {
      display: block;
      margin-top: 4px;
      color: #666;
      font-size: 0.82rem;
      line-height: 1.6;
    }

    .ican-chip-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 18px;
    }

    .ican-chip {
      display: inline-flex;
      align-items: center;
      min-height: 30px;
      padding: 0 11px;
      background: rgba(47,111,193,0.1);
      border: 1px solid rgba(47,111,193,0.18);
      color: #2f6fc1;
      font-size: 0.72rem;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .eys-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 10px 14px;
      margin-top: 16px;
      color: #868686;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .eys-bars {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px 12px;
      margin-top: 18px;
    }

    .eys-bar {
      min-height: 30px;
      border-radius: 3px;
      display: flex;
      align-items: center;
      padding: 0 12px;
      color: #fff;
      font-family: "Arial Black", Arial, sans-serif;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
    }

    .eys-bar--green { background: #1db855; }
    .eys-bar--blue { background: #2f6fc1; }
    .eys-bar--orange { background: #cc3a20; }
    .eys-bar--gray { background: #808694; }

    .eys-note {
      margin-top: 12px;
      font-size: 0.78rem;
      color: #8a8a8a;
      line-height: 1.55;
    }

    .ican-phases {
      padding: 18px 0 34px;
    }

    .ican-phases-card {
      background: #fff;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 18px 34px rgba(0,0,0,0.07);
    }

    .ican-phases-head {
      padding: 22px 26px 16px;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .ican-phases-stack {
      display: grid;
    }

    .eys-phase {
      border-top: 1px solid rgba(255,255,255,0.12);
    }

    .eys-phase:first-child {
      border-top: 0;
    }

    .eys-toggle {
      width: 100%;
      border: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      text-align: left;
      padding: 18px 22px;
      cursor: pointer;
      color: #fff;
    }

    .eys-toggle--green { background: #1db855; }
    .eys-toggle--blue { background: #2f6fc1; }
    .eys-toggle--orange { background: #cc3a20; }
    .eys-toggle--gray { background: #808694; }
    .eys-toggle--yellow { background: #f4a300; }

    .eys-toggle strong {
      display: block;
      font-family: "Arial Black", Arial, sans-serif;
      font-size: 0.82rem;
      line-height: 1.06;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .eys-toggle span {
      display: block;
      margin-top: 4px;
      font-size: 0.74rem;
      line-height: 1.5;
      color: rgba(255,255,255,0.85);
    }

    .eys-chevron {
      flex: 0 0 auto;
      transition: transform 0.2s ease;
    }

    .eys-body {
      display: none;
      padding: 18px 22px 20px;
      background: #fff;
      color: #444;
      line-height: 1.72;
      font-size: 0.92rem;
    }

    .eys-body strong {
      color: #111;
    }

    .ican-phases-foot {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 14px 22px 18px;
      border-top: 1px solid rgba(0,0,0,0.08);
    }

    .ican-phases-foot p {
      margin: 0;
      color: #8a8a8a;
      font-size: 0.76rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }

    .ican-steam {
      padding: 10px 0 42px;
    }

    .ican-steam-panel {
      display: grid;
      grid-template-columns: minmax(0, 1.18fr) minmax(280px, 0.82fr);
      background: #1a1a1a;
      color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 20px 38px rgba(0,0,0,0.1);
    }

    .ican-steam-copy,
    .ican-steam-list {
      padding: 26px 24px;
    }

    .ican-steam-copy {
      border-right: 1px solid rgba(255,255,255,0.1);
    }

    .ican-steam-copy .ican-section-title,
    .ican-steam-list .ican-panel-title {
      color: #fff;
      font-size: clamp(1.75rem, 3.2vw, 2.55rem);
    }

    .ican-steam-copy p,
    .ican-steam-list li {
      color: rgba(255,255,255,0.78);
      line-height: 1.72;
      font-size: 0.92rem;
    }

    .ican-steam-list ul {
      list-style: none;
      padding: 0;
      margin: 18px 0 0;
      display: grid;
      gap: 10px;
    }

    .ican-steam-list li {
      position: relative;
      padding-left: 14px;
      text-transform: uppercase;
      font-weight: 800;
      font-size: 0.78rem;
      letter-spacing: 0.03em;
    }

    .ican-steam-list li::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0.52em;
      width: 6px;
      height: 6px;
      border-radius: 999px;
      background: #1db855;
    }

    .ican-faq-list {
      display: grid;
      gap: 10px;
      margin-top: 18px;
    }

    .ican-faq-item {
      background: #fff;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 10px;
      padding: 18px 20px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.06);
    }

    .ican-faq-item strong {
      display: block;
      margin-bottom: 8px;
      color: #111;
      font-family: "Arial Black", Arial, sans-serif;
      font-size: 0.84rem;
      text-transform: uppercase;
      line-height: 1.08;
    }

    .ican-faq-item p {
      margin: 0;
      color: #595959;
      line-height: 1.68;
      font-size: 0.92rem;
    }

    .ican-signup {
      padding: 10px 0 60px;
    }

    .ican-signup-copy .ican-signup-title {
      font-size: clamp(1.9rem, 3.8vw, 3.15rem);
    }

    .ican-success {
      margin-bottom: 18px;
      padding: 12px 14px;
      border-radius: 8px;
      background: #e8fff1;
      border: 1px solid rgba(29,184,85,0.25);
      color: #15733d;
      font-weight: 700;
    }

    .signup-form-wrap {
      background: #fff;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 12px;
      padding: 22px;
      box-shadow: 0 18px 34px rgba(0,0,0,0.07);
    }

    .signup-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .signup-form .full {
      grid-column: 1 / -1;
    }

    .signup-form label {
      display: block;
      margin-bottom: 6px;
      color: #111;
      font-weight: 900;
      font-size: 0.68rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .signup-form input,
    .signup-form textarea {
      width: 100%;
      box-sizing: border-box;
      border: 1px solid rgba(0,0,0,0.12);
      background: #fff;
      color: #111;
      border-radius: 6px;
      padding: 12px;
      font-size: 0.94rem;
    }

    .signup-form textarea {
      min-height: 120px;
      resize: vertical;
    }

    @media (max-width: 1100px) {
      .ican-partners-grid {
        grid-template-columns: repeat(10, minmax(0, 1fr));
      }
    }

    @media (max-width: 960px) {
      .ican-hero-grid,
      .ican-panel-grid,
      .ican-overview-grid,
      .ican-steam-panel,
      .ican-signup-grid {
        grid-template-columns: 1fr;
      }

      .ican-partners-grid {
        grid-template-columns: repeat(6, minmax(0, 1fr));
      }

      .ican-title {
        max-width: none;
      }

      .ican-steam-copy {
        border-right: 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
      }
    }

    @media (max-width: 720px) {
      .ican-page {
        background:
          repeating-linear-gradient(135deg, rgba(17,17,17,0.028) 0 2px, transparent 2px 9px),
          #fbfbf8;
      }

      .ican-shell {
        width: min(calc(100% - 20px), 76rem);
      }

      .ican-stage {
        min-height: 320px;
        border-radius: 0 24px 0 24px;
      }

      .ican-stage-wordmark {
        left: 16px;
        top: 14px;
        font-size: clamp(3rem, 16vw, 5rem);
        -webkit-text-stroke: 5px #00a85a;
      }

      .ican-partners-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
      }

      .eys-bars {
        grid-template-columns: 1fr;
      }

      .ican-phases-foot {
        flex-direction: column;
        align-items: stretch;
      }

      .signup-form {
        grid-template-columns: 1fr;
      }

      .signup-form .full {
        grid-column: auto;
      }
    }
  </style>
</head>
<body class="ican-page overflow-x-hidden">
  <?php require_once __DIR__ . '/partials/header.php'; ?>

  <main>
    <section class="ican-hero-wrap">
      <div class="ican-shell">
        <div class="ican-hero-grid">
          <div class="ican-stage">
            <h1 class="ican-stage-wordmark">I CAN</h1>
            <img src="/images/group shot.webp" alt="Students and mentors in the I CAN Program">
          </div>

          <aside class="ican-hero-side">
            <div class="ican-summary">
              <p class="ican-kicker">Youth development through skateboarding</p>
              <h2 class="ican-title">Free Toledo skateboarding lessons for kids <span>(Ages 6–17)</span></h2>
              <p class="ican-copy">Looking for <strong>skateboarding lessons near you in Toledo</strong>? The On-Deck Alliance at the Frederick Douglass Center offers <strong>100% free skateboarding lessons for kids and beginners</strong>, focused on building confidence, resilience, and real-life skills through skateboarding. We’re not just teaching tricks—we’re using skateboarding to heal through self-sufficiency and efficacy.</p>
              <div class="ican-cta-row">
                <a class="ican-btn ican-btn--primary" href="#signup">Sign Up Now</a>
                <a class="ican-btn ican-btn--light" href="#faq">Program FAQ</a>
              </div>
            </div>

            <figure class="ican-side-photo">
              <img src="/images/optimized/about-kids-ramp-bw.jpg" alt="Student skating during a program session" loading="lazy">
            </figure>
          </aside>
        </div>
      </div>
    </section>

    <section class="ican-partners">
      <div class="ican-shell">
        <div class="ican-partners-grid">
          <?php foreach (array_slice($partnersList, 0, 15) as $partner): ?>
            <div class="ican-partner">
              <img src="/images/partners/<?= htmlspecialchars($partner['file'] ?? '', ENT_QUOTES) ?>" alt="<?= htmlspecialchars($partner['name'] ?? 'Partner', ENT_QUOTES) ?>" loading="lazy">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="ican-audience">
      <div class="ican-shell">
        <div class="ican-panel-grid">
          <div class="ican-card">
            <div class="ican-card__body">
              <p class="ican-kicker">Skateboarding lessons for kids &amp; beginners in Toledo</p>
              <h2 class="ican-section-title">Designed for first-time riders, beginners, and youth who need a positive environment</h2>
              <p class="ican-text">Our program is designed for kids ages 6–17, first-time riders, beginners learning how to skateboard, and youth looking for a positive, structured environment. If you’ve been searching for <strong>skateboarding lessons near me</strong>, <strong>skateboard lessons near me</strong>, or <strong>skateboarding classes near me</strong>, this is a local, trusted program built for Toledo families.</p>
              <ul class="ican-bullet-list">
                <li>Kids ages 6–17</li>
                <li>First-time riders and beginners</li>
                <li>Youth looking for a positive, structured environment</li>
                <li>Families who want free local access to skateboarding lessons</li>
              </ul>
            </div>
          </div>

          <figure class="ican-card ican-card__media">
            <img src="/images/optimized/kids.webp" alt="Youth learning together in a structured skateboarding environment" loading="lazy">
          </figure>
        </div>
      </div>
    </section>

    <section class="ican-overview">
      <div class="ican-shell">
        <div class="ican-overview-grid">
          <div>
            <p class="ican-kicker">What makes our skateboarding program different</p>
            <h2 class="ican-section-title">A principle-based youth program addressing inequity through skateboarding</h2>
            <p class="ican-text">We are a principle-based youth program addressing cultural and economic inequities through skateboarding. Our focus is long-term change through practice, support, and structure that helps students keep growing.</p>
            <ul class="ican-promote-list">
              <li>
                <span class="ican-promote-dot ican-promote-dot--red"></span>
                <div>
                  <span class="ican-promote-title">Mental &amp; Physical Wellness</span>
                  <span class="ican-promote-text">Skateboarding builds balance, coordination, stress relief, and confidence.</span>
                </div>
              </li>
              <li>
                <span class="ican-promote-dot ican-promote-dot--blue"></span>
                <div>
                  <span class="ican-promote-title">Safe Skateable Spaces</span>
                  <span class="ican-promote-text">We actively create and maintain environments where kids feel safe learning and progressing.</span>
                </div>
              </li>
              <li>
                <span class="ican-promote-dot ican-promote-dot--green"></span>
                <div>
                  <span class="ican-promote-title">Social &amp; Emotional Growth</span>
                  <span class="ican-promote-text">Resilience, patience, inclusion, and self-expression grow naturally through repetition and support.</span>
                </div>
              </li>
              <li>
                <span class="ican-promote-dot ican-promote-dot--yellow"></span>
                <div>
                  <span class="ican-promote-title">Structured Opportunity</span>
                  <span class="ican-promote-text">A trusted local program for youth looking for consistency, encouragement, and room to progress.</span>
                </div>
              </li>
            </ul>
          </div>

          <div>
            <p class="ican-kicker">Our curriculum: Earn Your Stripes</p>
            <h2 class="ican-section-title">Earn Your Stripes</h2>
            <p class="eys-copy">Our program follows a structured progression called <strong>Earn Your Stripes (EYS)</strong>—a step-by-step system that teaches both skateboarding and life skills. Students advance by earning colored grip tape stripes tied to the principles of courage, commitment, consistency, and confidence.</p>
            <div class="eys-meta">
              <span>Ages 6–17</span>
              <span>Free to participate</span>
              <span>Equipment provided</span>
            </div>
            <div class="eys-bars" aria-label="Program principles">
              <div class="eys-bar eys-bar--green">Courage</div>
              <div class="eys-bar eys-bar--blue">Commitment</div>
              <div class="eys-bar eys-bar--orange">Consistency</div>
              <div class="eys-bar eys-bar--gray">Confidence</div>
            </div>
            <p class="eys-note">Each level builds both skill and mindset through real challenges, trial and error, and perseverance that carry far beyond the skatepark.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="ican-phases">
      <div class="ican-shell">
        <div class="ican-phases-card">
          <div class="ican-phases-head">
            <p class="ican-kicker">Curriculum breakdown</p>
            <h2 class="ican-section-title">Expandable sections</h2>
          </div>

          <div class="ican-phases-stack">
            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--green" type="button">
                <div>
                  <strong>Green Stripe — Courage</strong>
                  <span>"It takes courage to even try."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Students overcome fear while learning the absolute basics.</strong><br><br>
                They build their skateboard, learn safety and proper falling techniques, explore skatepark design ideas, and create custom grip tape designs.<br><br>
                <strong>Skills learned:</strong> stance awareness, pushing and riding, turning and control, tail stops, and 180° power slides.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--blue" type="button">
                <div>
                  <strong>Blue Stripe — Commitment</strong>
                  <span>"Fall in love with the process."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Students begin mastering the Ollie, the foundation of most tricks.</strong><br><br>
                This phase teaches repetition, dedication, and how progress is built through committed practice.<br><br>
                <strong>Skills learned:</strong> ollies and variations including fakie, frontside, backside, switch, nollie, plus 180s and half cabs.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--orange" type="button">
                <div>
                  <strong>Red Stripe — Consistency</strong>
                  <span>"Consistency is the decision to succeed no matter what."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Students begin manipulating the board independently.</strong><br><br>
                This phase reinforces persistence and the discipline to keep trying through frustration and failure.<br><br>
                <strong>Skills learned:</strong> frontside and backside shove-its, fakie, switch, nollie variations, and pop shove-its.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--gray" type="button">
                <div>
                  <strong>Clear Stripe — Confidence</strong>
                  <span>"Knowing you can handle challenges."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Students learn advanced flip tricks and build real self-belief.</strong><br><br>
                Clear stripe students begin to recognize that they can handle challenge, recovery, and continued growth.<br><br>
                <strong>Skills learned:</strong> kickflips, heelflips, and fakie, switch, and nollie variations.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--yellow" type="button">
                <div>
                  <strong>Graduation</strong>
                  <span>You earned it.</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Students who complete the program become mentors for newer students and receive a brand-new professional skateboard.</strong><br><br>
                Graduation means more than a finished curriculum—it means visible progress, real pride, and proof that consistency changes what feels possible.
              </div>
            </div>
          </div>

          <div class="ican-phases-foot">
            <p>Ready to start your journey?</p>
            <a class="ican-btn ican-btn--primary" href="#signup">Sign Up Now</a>
          </div>
        </div>
      </div>
    </section>

    <section class="ican-steam">
      <div class="ican-shell">
        <div class="ican-steam-panel">
          <div class="ican-steam-copy">
            <p class="ican-kicker" style="color:#1db855;">Beyond the board</p>
            <h2 class="ican-section-title">STEM / STEAM Programs</h2>
            <p>We introduce integrated STEAM and STEM curriculum for students ages 10–17. Students build their own skateboards, learn how parks are designed using CAD, design skate apparel, and market the program through social media. As they grow, skateboarding becomes a doorway into teamwork, problem-solving, entrepreneurship, and self-reliance.</p>
            <div class="ican-cta-row">
              <a class="ican-btn ican-btn--green" href="/contact.php">Read: STEAM in Skateboarding</a>
            </div>
          </div>
          <div class="ican-steam-list">
            <p class="ican-kicker" style="color:#1db855;">The dye grip pathways</p>
            <h3 class="ican-panel-title">Career-connected skills</h3>
            <ul>
              <li>Videography &amp; editing</li>
              <li>Marketing / branding / social media</li>
              <li>Graphic design (apparel)</li>
              <li>CAD design (ramps &amp; parks)</li>
              <li>Engineering / constructing ramps</li>
              <li>Entrepreneur opportunities</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="ican-practical">
      <div class="ican-shell">
        <div class="ican-panel-grid">
          <div class="ican-card">
            <div class="ican-card__body">
              <p class="ican-kicker">How our skateboarding lessons work</p>
              <h2 class="ican-section-title">Warm-up, skill progression, coaching, practice, and community</h2>
              <p class="ican-text">A typical session includes warm-up and fundamentals, skill progression training, one-on-one coaching, practice and encouragement, and community building. We keep group sizes manageable to ensure every student gets real attention.</p>
              <ul class="ican-bullet-list">
                <li>Warm-up and fundamentals</li>
                <li>Skill progression training</li>
                <li>One-on-one coaching</li>
                <li>Practice plus encouragement</li>
                <li>Community building and accountability</li>
              </ul>
            </div>
          </div>

          <div class="ican-card">
            <div class="ican-card__body">
              <p class="ican-kicker">Where are the lessons held?</p>
              <h2 class="ican-section-title">Frederick Douglass Center in Toledo, Ohio</h2>
              <p class="ican-text">Our <strong>skateboarding classes near Toledo</strong> take place at the Frederick Douglass Center. It is a trusted local location for families searching for <strong>skateboarding lessons near me</strong> in the Toledo area.</p>
              <div class="ican-chip-row">
                <span class="ican-chip">Frederick Douglass Center</span>
                <span class="ican-chip">Toledo, Ohio</span>
                <span class="ican-chip">Free lessons</span>
              </div>
              <div class="ican-cta-row">
                <a class="ican-btn ican-btn--primary" href="#signup">Sign Up Now</a>
                <a class="ican-btn ican-btn--light" href="/contact.php">Ask a Question</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ican-practical">
      <div class="ican-shell">
        <div class="ican-panel-grid">
          <figure class="ican-card ican-card__media">
            <img src="/images/josh_cunningham.webp" alt="Joshua Cunningham, founder of On-Deck Alliance" loading="lazy">
          </figure>

          <div class="ican-card">
            <div class="ican-card__body">
              <p class="ican-kicker">About The On-Deck Alliance</p>
              <h2 class="ican-section-title">Founded by Joshua Cunningham to empower youth through skateboarding and mentorship</h2>
              <p class="ican-text">Founded by <strong>Joshua Cunningham</strong>, the On-Deck Alliance is committed to empowering youth through skateboarding and mentorship. Our coaches are passionate about helping the next generation build confidence, discipline, and life skills.</p>
              <div class="ican-cta-row">
                <a class="ican-btn ican-btn--dark" href="/about.php#about-coaches">Meet the Coaches</a>
                <a class="ican-btn ican-btn--light" href="/about.php">About ODA</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="faq" class="ican-faq">
      <div class="ican-shell">
        <p class="ican-kicker">Frequently asked questions</p>
        <h2 class="ican-section-title">What families usually want to know</h2>
        <div class="ican-faq-list">
          <article class="ican-faq-item">
            <strong>What is the curriculum of the I CAN program?</strong>
            <p>It is our Earn Your Stripes system, combining skateboarding progression with core life principles.</p>
          </article>
          <article class="ican-faq-item">
            <strong>How do skateboarding lessons for beginners work?</strong>
            <p>We teach step by step, starting with balance, safety, and riding basics before moving toward tricks and progression.</p>
          </article>
          <article class="ican-faq-item">
            <strong>Are these skateboarding lessons for kids only?</strong>
            <p>Yes. The current program is for youth ages 6–17.</p>
          </article>
          <article class="ican-faq-item">
            <strong>Where do you meet?</strong>
            <p>We meet through the Frederick Douglass Center in Toledo, Ohio.</p>
          </article>
          <article class="ican-faq-item">
            <strong>How do I sign up?</strong>
            <p>Use the signup form below to get started and our team will follow up with next steps.</p>
          </article>
          <article class="ican-faq-item">
            <strong>How many kids are in each session?</strong>
            <p>We keep groups structured and manageable to ensure individual attention and support.</p>
          </article>
        </div>
      </div>
    </section>

    <section id="signup" class="ican-signup">
      <div class="ican-shell">
        <div class="ican-signup-grid">
          <div class="ican-signup-copy">
            <?php if ($signupSuccess): ?>
              <div class="ican-success">Thanks — your signup was received. We’ll follow up soon.</div>
            <?php endif; ?>
            <p class="ican-kicker">Ready to start?</p>
            <h2 class="ican-signup-title">Sign up for free Toledo skateboarding lessons</h2>
            <p class="ican-signup-copy">Give your child the opportunity to learn, grow, and build confidence through our <strong>free Toledo skateboarding lessons</strong>. Beginners are welcome, equipment support is available, and every session is designed to help students progress.</p>
            <div class="ican-chip-row">
              <span class="ican-chip">Ages 6–17</span>
              <span class="ican-chip">Beginners welcome</span>
              <span class="ican-chip">Toledo youth</span>
            </div>
          </div>

          <div class="signup-form-wrap">
            <form method="POST" action="/signup-process.php" class="signup-form">
              <input type="hidden" name="return_to" value="program.php">

              <div>
                <label for="first_name">First name</label>
                <input id="first_name" name="first_name" required>
              </div>

              <div>
                <label for="last_name">Last name</label>
                <input id="last_name" name="last_name" required>
              </div>

              <div>
                <label for="age">Age</label>
                <input id="age" name="age" type="number" min="6" max="17" required>
              </div>

              <div>
                <label for="guardian_name">Guardian</label>
                <input id="guardian_name" name="guardian_name" required>
              </div>

              <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
              </div>

              <div>
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="tel">
              </div>

              <div class="full">
                <label for="message">Notes</label>
                <textarea id="message" name="message"></textarea>
              </div>

              <div class="full">
                <button class="ican-btn ican-btn--primary" type="submit">Sign Up Now</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require_once __DIR__ . '/partials/footer.php'; ?>
  <script src="/js/program-page.js" defer></script>
  <script src="/js/donate-modal.js"></script>
</body>
</html>
