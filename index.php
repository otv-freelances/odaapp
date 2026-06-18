<?php require_once __DIR__ . '/admin/auth.php'; ?>
<?php
$homeMutable = [];
try {
  $homeJson = @file_get_contents(__DIR__ . '/data/home-mutable.json');
  if ($homeJson !== false) {
    $decoded = json_decode($homeJson, true);
    if (is_array($decoded)) $homeMutable = $decoded;
  }
} catch (Throwable $e) {
  $homeMutable = [];
}

$mosaic       = $homeMutable['mosaic'] ?? [];
$rotator      = $homeMutable['testimonial_rotator'] ?? [];
$partnersCfg  = $homeMutable['proud_partners'] ?? [];
$partnersList = $partnersCfg['partners'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>On-Deck Alliance</title>
    <link rel="icon" type="image/webp" href="/images/favicon.webp">
<link rel="stylesheet" href="./style.css">
  <link rel="stylesheet" href="./dist/output.css">
  <link rel="stylesheet" href="./skate-upgrade.css">
</head>
<body class="overflow-x-hidden">

  <?php require_once __DIR__ . '/partials/header.php'; ?>

  <section class="hero-section skate-hero w-full overflow-hidden relative z-0" data-home-reference-hero>
    <video 
      class="hero-media w-full h-full object-cover absolute inset-0"
      autoplay 
      muted 
      loop 
      playsinline
    >
    <source src="https://i.imgur.com/L43AlDC.mp4" type="video/webm">
    <!-- <source src="./images/herovideo.webm" type="video/webm"> -->
    </video>
    <!-- <img class="hero-media w-full h-full object-cover absolute inset-0" src="./images/hero-poster.webp" alt="Youth skateboarding at On-Deck Alliance"> -->
    <div class="hero-overlay"></div>

    <div class="hero-content">
      <div class="home-hero-lockup home-hero-card">
        <h1 class="home-hero-lockup__title">Empowering youth through <br><span>skateboarding</span></h1>
        <p class="home-hero-lockup__copy">Building confidence, community, and opportunity.</p>
        <div class="home-hero-lockup__actions">
          <button class="oda-btn oda-btn--primary cta-primary cta-animate btn-slide" type="button" onclick="openDonateModal()">Donate</button>
          <a class="oda-btn oda-btn--blue cta-animate btn-slide" href="volunteer.php">Volunteer</a>
          <a class="oda-btn home-ican-btn--light cta-animate btn-slide" href="partner.php">Partner With Us</a>
        </div>
      </div>
    </div>
  </section>

  <main id="home-main" class="home-reference">
    <div class="home-reference-stat-grid">
      <article class="hero-impact-item hero-impact-item--accent">
        <div class="hero-impact-item__num countup" data-count="120" data-suffix="+">0</div>
        <div class="hero-impact-item__label">Youth served</div>
      </article>
      <article class="hero-impact-item">
        <div class="hero-impact-item__num countup" data-count="12">0</div>
        <div class="hero-impact-item__label">Years in Toledo</div>
      </article>
      <article class="hero-impact-item">
        <div class="hero-impact-item__num countup" data-count="5">0</div>
        <div class="hero-impact-item__label">Partner schools</div>
      </article>
    </div>
    <section id="envision-section" class="home-envision-wrap">
      <div class="home-envision-bg"></div>
      <div class="home-reference-wrap home-reference-intro">
        <article class="home-reference-mission">
          <p class="home-reference-kicker">Our mission is to promote self sufficiency and beneficial change in historically underserved communities.</p>
          <h2 class="home-reference-mission__title">Envision a world where marginalized communities heal and grow through access to the benefits of skateboarding</h2>
          <p class="home-reference-mission__copy">At ODA we use skateboarding as a backdrop for principle-based youth programming &mdash; addressing racial and economic inequalities through:</p>
        </article>

        <div class="home-reference-media">
          <?php
            $featureA = $mosaic['tiles_left'][0] ?? [];
            $featureB = $mosaic['tiles_left'][1] ?? [];
            $featureC = $mosaic['tiles_right']['smalls'][0] ?? [];
          ?>
          <div class="home-mosaic home-mosaic--reference">
            <figure class="home-tile home-envision-card home-envision-card--square card" data-dot="<?= htmlspecialchars($featureA['dot'] ?? 'orange', ENT_QUOTES) ?>">
              <img class="home-tile__img" src="<?= htmlspecialchars($featureA['img'] ?? '', ENT_QUOTES) ?>" alt="<?= htmlspecialchars($featureA['label'] ?? 'Program photo', ENT_QUOTES) ?>" loading="lazy">
              <figcaption class="home-envision-card__overlay home-envision-card__overlay--cyan">
                <span class="home-envision-card__band">120</span>
                <span class="home-envision-card__meta">Youth reached<br>through our programs</span>
              </figcaption>
            </figure>

            <figure class="home-tile home-envision-card home-envision-card--tall card" data-dot="<?= htmlspecialchars($featureB['dot'] ?? 'blue', ENT_QUOTES) ?>">
              <img class="home-tile__img" src="<?= htmlspecialchars($featureB['img'] ?? '', ENT_QUOTES) ?>" alt="<?= htmlspecialchars($featureB['label'] ?? 'Program photo', ENT_QUOTES) ?>" loading="lazy">
              <figcaption class="home-envision-card__overlay home-envision-card__overlay--red">
                <span class="home-envision-card__band">100% Free</span>
                <span class="home-envision-card__meta">Gear provided<br>to all participants</span>
              </figcaption>
            </figure>

            <figure class="home-tile home-envision-card home-envision-card--tall-secondary card" data-dot="<?= htmlspecialchars($featureC['dot'] ?? 'green', ENT_QUOTES) ?>">
              <img class="home-tile__img" src="<?= htmlspecialchars($featureC['img'] ?? '', ENT_QUOTES) ?>" alt="<?= htmlspecialchars($featureC['alt'] ?? 'Program photo', ENT_QUOTES) ?>" loading="lazy">
              <figcaption class="home-envision-card__overlay home-envision-card__overlay--blue">
                <span class="home-envision-card__band">12 Years</span>
                <span class="home-envision-card__meta">Serving Toledo,<br>Ohio youth</span>
              </figcaption>
            </figure>

            <figure class="home-envision-logo card" aria-label="I CAN values">
              <img src="./images/iCan_logo.png" alt="ODA I CAN program values logo" loading="lazy">
            </figure>
          </div>
        </div>
      </div>

      <script>
        window.ODA_HOME = <?= json_encode(['testimonial_rotator' => $rotator], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
      </script>
    </section>

    <section class="home-mid-strip" aria-label="Quick actions">
      <div class="home-reference-wrap">
        <div class="home-mid-strip__card">
          <?php $midQuote = ($rotator['items'][0] ?? []); ?>
          <aside class="home-mid-quote card" aria-label="Student feedback preview">
            <div class="home-mid-quote__media">
              <img src="<?= htmlspecialchars($midQuote['img'] ?? 'images/smiling.webp', ENT_QUOTES) ?>" alt="" loading="lazy">
            </div>
            <div class="home-mid-quote__body">
              <div class="home-mid-quote__kicker">Student feedback</div>
              <div class="home-mid-quote__text"><?= htmlspecialchars($midQuote['quote'] ?? 'This program changed my life.', ENT_QUOTES) ?></div>
              <div class="home-mid-quote__by"><?= htmlspecialchars($midQuote['by'] ?? 'ODA student', ENT_QUOTES) ?></div>
            </div>
          </aside>

          <div class="home-mid-actions" aria-label="Quick links">
            <a class="home-mid-action home-mid-action--blue btn-slide" href="donate.php">DONATE NOW</a>
            <a class="home-mid-action home-ican-btn--light btn-slide" href="program.php#signup">SIGN UP</a>
            <a class="home-mid-action oda-btn--dark btn-slide" href="contact.php">SUBSCRIBE</a>
            <a class="home-mid-action oda-btn--green btn-slide" href="#" onclick="event.preventDefault();openMerchModal()">MERCH</a>
            <a class="home-mid-action oda-btn--blue btn-slide" href="volunteer.php">VOLUNTEER</a>
          </div>

          <div class="home-mid-visual" aria-label="Program moments and story">
            <?php
              $c1 = $mosaic['tiles_right']['smalls'][0]['img'] ?? 'images/push ups_fun.webp';
              $c2 = $mosaic['tiles_right']['smalls'][1]['img'] ?? 'images/for educators.webp';
              $c3 = $mosaic['tiles_left'][0]['img'] ?? 'images/kids raising hands.webp';
            ?>
            <article class="home-mid-story">
              <div class="home-mid-story__kicker">The community</div>
              <h3 class="home-mid-story__title">Showing up every day</h3>
              <p class="home-mid-story__copy">From park sessions to board giveaways, every visit is our chance to make a difference one skater at a time.</p>
            </article>
            <div class="home-mid-collage" aria-label="Program moments">
              <figure class="home-mid-collage__tile home-mid-collage__tile--wide card">
                <img src="<?= htmlspecialchars($c3, ENT_QUOTES) ?>" alt="Program moment" loading="lazy">
              </figure>
              <figure class="home-mid-collage__tile card">
                <img src="<?= htmlspecialchars($c1, ENT_QUOTES) ?>" alt="Program moment" loading="lazy">
              </figure>
              <figure class="home-mid-collage__tile card">
                <img src="<?= htmlspecialchars($c2, ENT_QUOTES) ?>" alt="Program moment" loading="lazy">
              </figure>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="partners-section" class="home-partners-wrap">
      <div class="home-partners-white">
        <div class="home-reference-wrap home-partners-reference">
          <div class="home-partners-head">
            <div class="home-partners-title" aria-label="Proud Partners">
              <span class="home-partners-title__layer home-partners-title__layer--cyan" aria-hidden="true"><span>Proud</span><span>Partners</span></span>
              <span class="home-partners-title__layer home-partners-title__layer--red" aria-hidden="true"><span>Proud</span><span>Partners</span></span>
              <span class="home-partners-title__layer home-partners-title__layer--base"><span>Proud</span><span>Partners</span></span>
              <span class="home-partners-title__icon" aria-hidden="true">
                <svg data-oda-draw width="40" height="40" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 17l9 10 10-8 5 7-8 9 10 10"/>
                  <path d="M20 46l13-12"/>
                  <circle cx="19" cy="49" r="5"/>
                  <circle cx="48" cy="48" r="5"/>
                </svg>
              </span>
            </div>
          </div>

          <div class="home-partners-collage home-partners-collage--aligned">
            <?php foreach ($partnersList as $p): ?>
              <div class="home-partner-logo">
                <img src="images/partners/<?= htmlspecialchars($p['file'] ?? '', ENT_QUOTES) ?>" alt="<?= htmlspecialchars($p['name'] ?? 'Partner', ENT_QUOTES) ?>" loading="lazy">
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="home-proud-block">
        <div class="home-ican-band">
          <section class="home-ican home-reference-wrap" aria-label="The I CAN Program">
            <div class="home-ican-grid">
              <div class="home-ican-left">
                <div class="home-ican-title" aria-label="The I CAN Program">
                  <span class="home-ican-title__the">The</span>
                  <span class="home-ican-title__main">I CAN <span>Program</span></span>
                </div>
                <div class="home-ican-kicker">Skateboarding becomes self-belief.</div>
                <p class="home-ican-desc">The I CAN Program uses skateboarding as a vehicle for youth development. Every session builds courage, commitment, consistency, and confidence.</p>
                <div class="home-ican-cta">
                  <a class="home-ican-btn home-ican-btn--light btn-slide" href="program.php">Learn More</a>
                  <a class="home-ican-btn home-ican-btn--primary btn-slide cta-primary" href="program.php#signup">Sign Up</a>
                </div>
              </div>

              <div class="home-ican-center">
                <div class="home-ican-clipboard">
                  <div class="home-ican-clipboard__clip" aria-hidden="true"></div>
                  <div class="home-ican-clipboard__paper">
                    <img class="home-ican-clipboard__image" src="./images/pamphlet-clean.svg" alt="On-Deck Alliance pamphlet preview">
                    <div class="home-ican-stripes home-ican-stripes--clipboard" aria-label="Program actions">
                      <a class="home-ican-stripe home-ican-stripe--black" href="program.php">Program details</a>
                      <a class="home-ican-stripe home-ican-stripe--green" href="program.php#signup">Join I CAN</a>
                      <a class="home-ican-stripe home-ican-stripe--orange" href="donate.php">Support youth</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="home-ican-right" aria-label="Earn Your Stripes visual">
                <div class="home-ican-board-wrap">
                  <img class="home-ican-board" src="./images/griptape.png" alt="Skateboard with 4 Cs stripes">
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>

    <section class="home-involved-section" aria-label="Ways to get involved">
      <div class="home-reference-wrap">
        <div class="home-involved-cards">
          <article class="home-involved-card home-involved-card--dark">
            <span>Join The</span>
            <h2>I CAN Program</h2>
            <p>Free skating, free gear, and real mentorship.</p>
            <a class="home-ican-btn home-ican-btn--light btn-slide" href="program.php">Join Program</a>
          </article>
          <article class="home-involved-card home-involved-card--blue">
            <span>Volunteer</span>
            <h2>With Us</h2>
            <p>Coach, mentor, or help run community events.</p>
            <a class="home-ican-btn home-ican-btn--light btn-slide" href="volunteer.php">Get Started</a>
          </article>
          <article class="home-involved-card home-involved-card--green">
            <span>ODA</span>
            <h2>Merch</h2>
            <p>Every purchase helps fund the program.</p>
            <a class="home-ican-btn home-ican-btn--light btn-slide" href="#" onclick="event.preventDefault();openMerchModal()">Shop Now</a>
          </article>
        </div>
      </div>
    </section>

    <section class="blog-events-section skate-surface py-12">
      <div class="home-reference-wrap">
        <div class="flex items-end justify-between gap-4 mb-8">
          <div>
            <p class="home-reference-kicker">Events &amp; updates</p>
            <h2 class="home-reference-section-title">Blog &amp; Events</h2>
          </div>
          <a class="home-reference-link" href="blog.php">View All</a>
        </div>

        <div class="home-reference-news">
          <?php
          $now = date('Y-m-d');
          $displayItems = array_slice(get_all_latest(), 0, 3);
          $tagColors = [
              'event'  => '#16a34a',
              'update' => '#2563eb',
              'blog'   => '#111827',
          ];
          $tagLabels = ['event' => 'Event', 'update' => 'Update', 'blog' => 'Blog'];
          foreach ($displayItems as $item):
            $iType     = $item['type']     ?? 'update';
            $iTitle    = $item['title']    ?? '';
            $iDate     = $item['date']     ?? '';
            $iBody     = $item['body']     ?? '';
            $iImage    = $item['image']    ?? '';
            $iLocation = $item['location'] ?? '';
            $dateBadge  = $iDate ? date('M Y', strtotime($iDate)) : '';
            $typeLabel  = $tagLabels[$iType] ?? ucfirst($iType);
            $badgeColor = $iType === 'event'
                ? ($iDate >= $now ? '#16a34a' : '#dc3b22')
                : ($tagColors[$iType] ?? '#2563eb');
            $plainBody = strip_tags($iBody);
            $excerpt = mb_substr($plainBody, 0, 120);
            if (mb_strlen($plainBody) > 120) $excerpt .= '...';
          ?>
          <article class="home-reference-news-card news-card">
            <?php if ($iImage): ?>
              <img src="/<?= htmlspecialchars($iImage, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($iTitle, ENT_QUOTES) ?>">
            <?php else: ?>
              <div class="home-reference-news-card__placeholder" aria-hidden="true"></div>
            <?php endif; ?>
            <div class="home-reference-news-card__body">
              <div class="home-reference-badges">
                <?php if ($dateBadge): ?>
                  <span><?= htmlspecialchars($dateBadge) ?></span>
                <?php endif; ?>
                <span style="background:<?= htmlspecialchars($badgeColor, ENT_QUOTES) ?>;color:#fff;"><?= htmlspecialchars($typeLabel) ?></span>
              </div>
              <?php if ($iLocation): ?>
                <p class="home-reference-news-card__location"><?= htmlspecialchars($iLocation) ?></p>
              <?php endif; ?>
              <h3><?= htmlspecialchars($iTitle) ?></h3>
              <?php if ($excerpt): ?>
                <p><?= htmlspecialchars($excerpt) ?></p>
              <?php endif; ?>
            </div>
          </article>
          <?php endforeach; ?>
          <?php if (empty($displayItems)): ?>
            <div class="home-empty-state">No posts yet. Check back soon.</div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <?php require_once __DIR__ . '/partials/footer.php'; ?>

  <script src="/js/donate-modal.js"></script>
  <script>window.ODA_MERCH={storeUrl:<?php echo json_encode(defined('PRINTIFY_STORE_URL') ? PRINTIFY_STORE_URL : ''); ?>,shopName:<?php echo json_encode(defined('PRINTIFY_SHOP_NAME') ? PRINTIFY_SHOP_NAME : 'ODA Merch'); ?>};</script>
  <script src="/js/merch-modal.js"></script>
  <script src="/js/index-page.js" defer></script>
</body>
</html>
