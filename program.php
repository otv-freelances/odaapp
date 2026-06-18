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
  <title>I CAN Program | On-Deck Alliance</title>
  <link rel="icon" type="image/webp" href="/images/favicon.webp">
  <meta name="description" content="The I CAN Program provides free principle-based youth development through skateboarding for Toledo youth ages 6 to 17.">
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
      font-size: clamp(2.3rem, 4.6vw, 4.35rem);
      letter-spacing: 0.01em;
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

    .ican-hero-wrap {
      padding: clamp(26px, 4vw, 44px) 0 12px;
    }

    .ican-hero-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.22fr) minmax(280px, 0.38fr);
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

    .ican-summary .ican-title {
      max-width: 6ch;
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

    .ican-overview {
      padding: 24px 0 30px;
    }

    .ican-overview-grid {
      display: grid;
      grid-template-columns: minmax(0, 0.88fr) minmax(0, 1.12fr);
      gap: clamp(26px, 4vw, 54px);
      align-items: start;
    }

    .ican-promote-list {
      list-style: none;
      margin: 22px 0 0;
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

    .ican-signup {
      padding: 10px 0 60px;
    }

    .ican-signup-grid {
      display: grid;
      grid-template-columns: minmax(0, 0.86fr) minmax(0, 1.14fr);
      gap: clamp(24px, 4vw, 42px);
      align-items: start;
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
      .ican-overview-grid,
      .ican-steam-panel,
      .ican-signup-grid {
        grid-template-columns: 1fr;
      }

      .ican-partners-grid {
        grid-template-columns: repeat(6, minmax(0, 1fr));
      }

      .ican-summary .ican-title {
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
              <h2 class="ican-title">I CAN Program</h2>
              <p class="ican-copy">Principle-based youth programming addressing cultural and economic inequity through the benefits of skateboarding. We believe in long-term change through confidence, consistency, and real belonging.</p>
              <div class="ican-cta-row">
                <a class="ican-btn ican-btn--primary" href="#signup">Sign Up Now</a>
                <a class="ican-btn ican-btn--light" href="/contact.php">Contact</a>
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

    <section class="ican-overview">
      <div class="ican-shell">
        <div class="ican-overview-grid">
          <div>
            <p class="ican-kicker">Our focus</p>
            <h2 class="ican-section-title">What We Promote</h2>
            <ul class="ican-promote-list">
              <li>
                <span class="ican-promote-dot ican-promote-dot--red"></span>
                <div>
                  <span class="ican-promote-title">Mental &amp; Physical Wellness</span>
                  <span class="ican-promote-text">Through the discipline and joy of skateboarding, students build healthier habits and stronger confidence.</span>
                </div>
              </li>
              <li>
                <span class="ican-promote-dot ican-promote-dot--blue"></span>
                <div>
                  <span class="ican-promote-title">Safe Skateable Spaces</span>
                  <span class="ican-promote-text">Community-built places where young people can practice, connect, and feel seen.</span>
                </div>
              </li>
              <li>
                <span class="ican-promote-dot ican-promote-dot--green"></span>
                <div>
                  <span class="ican-promote-title">Social &amp; Emotional Skills</span>
                  <span class="ican-promote-text">Cooperation, empathy, communication, and confidence all grow through repeated practice.</span>
                </div>
              </li>
              <li>
                <span class="ican-promote-dot ican-promote-dot--yellow"></span>
                <div>
                  <span class="ican-promote-title">Entrepreneurship &amp; STEAM</span>
                  <span class="ican-promote-text">Real-world skills through board-building, design, media, and creative problem-solving.</span>
                </div>
              </li>
            </ul>
          </div>

          <div>
            <p class="ican-kicker">Curriculum overview</p>
            <h2 class="ican-section-title">Earn Your Stripes</h2>
            <p class="eys-copy">EYS is a progression-based curriculum where students advance through skill levels tied to four core principles. From first ride to flip tricks and beyond, every challenge builds real confidence, problem-solving, and perseverance that carry far beyond the skatepark.</p>
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
            <p class="eys-note">Students move through program stripes as they demonstrate each principle. Graduation earns a board, gear, and a deeper sense of capability.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="ican-phases">
      <div class="ican-shell">
        <div class="ican-phases-card">
          <div class="ican-phases-head">
            <p class="ican-kicker">Step by step</p>
            <h2 class="ican-section-title">Curriculum Phases</h2>
          </div>

          <div class="ican-phases-stack">
            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--green" type="button">
                <div>
                  <strong>Green Stripes — Courage Level</strong>
                  <span>"It takes courage to even try."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Focus:</strong> introducing the board, stance, balance, pushing, and first attempts. Students learn that showing up and trying counts. The green level builds safety habits, courage, and trust in the process.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--blue" type="button">
                <div>
                  <strong>Blue Stripes — Commitment Level</strong>
                  <span>"Fall in love with the process."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Focus:</strong> repetition, board control, and intentional practice. Students begin linking effort to improvement and learn that commitment means returning to the work even when progress feels slow.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--orange" type="button">
                <div>
                  <strong>Red Stripes — Consistency Level</strong>
                  <span>"Consistency is the decision to be successful no matter what happens."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Focus:</strong> showing up regularly, refining skills, and bringing the same effort over time. Red level teaches discipline, responsibility, and the connection between routine and results.
              </div>
            </div>

            <div class="eys-phase">
              <button class="eys-toggle eys-toggle--gray" type="button">
                <div>
                  <strong>Clear Stripes — Confidence Level</strong>
                  <span>"The knowledge of one's ability to adequately handle a given situation."</span>
                </div>
                <svg class="eys-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none"><path stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="eys-body">
                <strong>Focus:</strong> leadership, ownership, and visible self-belief. Students at this stage begin carrying their learning into other spaces—school, family life, friendships, and future goals.
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
                <strong>Outcome:</strong> students finish with more than new skate skills. They leave with gear, a stronger sense of self, and proof that perseverance and support can change what feels possible.
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

    <section id="signup" class="ican-signup">
      <div class="ican-shell">
        <div class="ican-signup-grid">
          <div class="ican-signup-copy">
            <?php if ($signupSuccess): ?>
              <div class="ican-success">Thanks — your signup was received. We’ll follow up soon.</div>
            <?php endif; ?>
            <p class="ican-kicker">Join I CAN</p>
            <h2 class="ican-signup-title">Sign up for the program</h2>
            <p class="ican-signup-copy">Free skateboarding lessons for Toledo youth ages 6–17. Beginners are welcome, equipment support is available, and every session is designed to build confidence through consistent practice.</p>
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
