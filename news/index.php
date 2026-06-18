<?php
require_once dirname(__DIR__) . '/admin/auth.php';

$slug = trim($_GET['slug'] ?? '');
$item = ($slug !== '' && preg_match('/^[\w\-]+$/u', $slug)) ? find_item_by_slug($slug) : null;
if (!$item) {
  http_response_code(404);
}

$title = $item ? normalize_mojibake($item['title'] ?? '') : 'Post Not Found';
$body = $item ? normalize_mojibake($item['body'] ?? '') : '';
$image = $item && !empty($item['image']) ? $item['image'] : 'images/group shot.webp';
$date = $item && !empty($item['date']) ? date('M j, Y', strtotime($item['date'])) : '';

function detail_escape($value): string {
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= detail_escape($title) ?> | On-Deck Alliance</title>
  <link rel="icon" type="image/webp" href="/images/favicon.webp">
  <meta name="description" content="<?= detail_escape($item ? mb_substr(strip_tags($body), 0, 150) : 'On-Deck Alliance post') ?>">
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="/dist/output.css">
  <link rel="stylesheet" href="/skate-upgrade.css">
  <link rel="stylesheet" href="/editorial-pages.css">
</head>
<body class="ep-page overflow-x-hidden news-detail-page">
  <?php require_once dirname(__DIR__) . '/partials/header.php'; ?>
  <main>
    <section class="ep-band ep-band--soft">
      <div class="ep-shell ep-hero-grid">
        <div class="ep-hero-copy">
          <p class="ep-kicker"><?= detail_escape($date ?: 'On-Deck Alliance') ?></p>
          <h1 class="ep-title"><?= nl2br(detail_escape($title)) ?></h1>
          <p class="ep-copy"><?= $item ? 'A more visual story page for updates, events, and program moments across ODA.' : 'We could not find the story you were looking for.' ?></p>
          <div class="ep-hero-actions">
            <a class="ep-btn ep-btn--primary" href="/blog.php">Back to Blog</a>
            <a class="ep-btn ep-btn--light" href="/events.php">Events</a>
          </div>
        </div>
        <div class="ep-hero-cards">
          <article class="ep-mini-card">
            <div class="ep-mini-card__media"><img src="/<?= detail_escape($image) ?>" alt="<?= detail_escape($title) ?>"></div>
            <div class="ep-mini-card__body"><span class="ep-mini-card__title">Featured post</span><span class="ep-mini-card__copy">The story in one image.</span></div>
          </article>
          <article class="ep-mini-card">
            <div class="ep-mini-card__media"><img src="/images/group shot.webp" alt="Community moment"></div>
            <div class="ep-mini-card__body"><span class="ep-mini-card__title">Community</span><span class="ep-mini-card__copy">Stories anchored in people.</span></div>
          </article>
          <article class="ep-mini-card">
            <div class="ep-mini-card__media"><img src="/images/optimized/about-coach-collage.jpg" alt="Mentorship"></div>
            <div class="ep-mini-card__body"><span class="ep-mini-card__title">Mentorship</span><span class="ep-mini-card__copy">Updates connected to the work itself.</span></div>
          </article>
        </div>
      </div>
    </section>

    <section class="ep-band">
      <div class="ep-shell">
        <?php if (!$item): ?>
          <p class="ep-empty">That post does not exist.</p>
        <?php else: ?>
          <div class="ep-split-grid">
            <figure class="ep-split-image">
              <img src="/<?= detail_escape($image) ?>" alt="<?= detail_escape($title) ?>">
            </figure>
            <div class="ep-split-copy">
              <p class="ep-kicker">Post detail</p>
              <h2 class="ep-section-title">Read the full story</h2>
              <div class="ep-section-copy" style="color:#374151;"><?= $body ?></div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
  <?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>
  <script src="/js/donate-modal.js"></script>
</body>
</html>
