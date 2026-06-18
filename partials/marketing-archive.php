<?php
if (!function_exists('ma_escape')) {
  function ma_escape($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

if (!function_exists('ma_media_exists')) {
  function ma_media_exists($src): bool {
    $src = trim((string) $src);
    if ($src === '') return false;
    if (preg_match('#^https?://#i', $src)) return true;
    $path = parse_url($src, PHP_URL_PATH) ?: $src;
    $path = ltrim((string) $path, '/');
    return is_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path));
  }
}

if (!function_exists('ma_render_media')) {
  function ma_render_media($src, $alt, $label = 'Image Placeholder'): void {
    if (ma_media_exists($src)) {
      echo '<img src="' . ma_escape($src) . '" alt="' . ma_escape($alt) . '" loading="lazy">';
      return;
    }
    echo '<div class="ep-media-placeholder"><div><span class="ep-media-placeholder__label">' . ma_escape($label) . '</span><span class="ep-media-placeholder__sub">Visual coming soon</span></div></div>';
  }
}

$archive = $archive ?? [];
$items = $archive['items'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= ma_escape($archive['meta_title'] ?? 'On-Deck Alliance') ?></title>
  <link rel="icon" type="image/webp" href="/images/favicon.webp">
  <meta name="description" content="<?= ma_escape($archive['meta_description'] ?? '') ?>">
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="/dist/output.css">
  <link rel="stylesheet" href="/skate-upgrade.css">
  <link rel="stylesheet" href="/editorial-pages.css">
</head>
<body class="ep-page overflow-x-hidden <?= ma_escape($archive['body_class'] ?? '') ?>">
  <?php require_once __DIR__ . '/header.php'; ?>
  <main>
    <section class="ep-band ep-band--soft">
      <div class="ep-shell ep-hero-grid">
        <div class="ep-hero-copy">
          <p class="ep-kicker"><?= ma_escape($archive['eyebrow'] ?? '') ?></p>
          <h1 class="ep-title"><?= nl2br(ma_escape($archive['title'] ?? '')) ?></h1>
          <p class="ep-copy"><?= ma_escape($archive['copy'] ?? '') ?></p>
        </div>
        <div class="ep-hero-cards">
          <?php foreach (($archive['hero_cards'] ?? []) as $card): ?>
            <article class="ep-mini-card">
              <div class="ep-mini-card__media">
                <?php ma_render_media($card['image'] ?? '', $card['alt'] ?? ($card['title'] ?? ''), $card['title'] ?? 'Image'); ?>
              </div>
              <div class="ep-mini-card__body">
                <span class="ep-mini-card__title"><?= ma_escape($card['title'] ?? '') ?></span>
                <?php if (!empty($card['copy'])): ?><span class="ep-mini-card__copy"><?= ma_escape($card['copy']) ?></span><?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="ep-band">
      <div class="ep-shell">
        <?php if (empty($items)): ?>
          <p class="ep-empty"><?= ma_escape($archive['empty'] ?? 'Nothing here yet.') ?></p>
        <?php else: ?>
          <div class="ep-archive-grid">
            <?php foreach ($items as $item): ?>
              <?php
                $title = $archive['title_cb']($item);
                $body = $archive['body_cb']($item);
                $image = $archive['image_cb']($item);
                $meta = $archive['meta_cb']($item);
                $href = $archive['href_cb']($item);
              ?>
              <<?= !empty($href) ? 'a' : 'article' ?> class="ep-archive-card"<?= !empty($href) ? ' href="' . ma_escape($href) . '"' : '' ?>>
                <div class="ep-archive-card__media">
                  <?php ma_render_media('/' . ltrim((string) $image, '/'), $title, $title); ?>
                </div>
                <div class="ep-archive-card__body">
                  <?php if ($meta): ?><span class="ep-archive-card__meta"><?= ma_escape($meta) ?></span><?php endif; ?>
                  <h2 class="ep-card-title"><?= ma_escape($title) ?></h2>
                  <p class="ep-card-copy"><?= ma_escape(mb_substr($body, 0, 140)) ?><?= mb_strlen($body) > 140 ? '...' : '' ?></p>
                </div>
              </<?= !empty($href) ? 'a' : 'article' ?>>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
  <?php require_once __DIR__ . '/footer.php'; ?>
  <script src="/js/donate-modal.js"></script>
</body>
</html>
