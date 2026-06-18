<?php
if (!function_exists('mp_escape')) {
  function mp_escape($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

if (!function_exists('mp_media_exists')) {
  function mp_media_exists($src): bool {
    $src = trim((string) $src);
    if ($src === '') return false;
    if (preg_match('#^https?://#i', $src)) return true;
    $path = parse_url($src, PHP_URL_PATH) ?: $src;
    $path = ltrim((string) $path, '/');
    return is_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path));
  }
}

if (!function_exists('mp_render_media')) {
  function mp_render_media($src, $alt, $class = '', $label = 'Image Placeholder'): void {
    if (mp_media_exists($src)) {
      echo '<img class="' . mp_escape($class) . '" src="' . mp_escape($src) . '" alt="' . mp_escape($alt) . '" loading="lazy">';
      return;
    }
    echo '<div class="ep-media-placeholder ' . mp_escape($class) . '">';
    echo '<div><span class="ep-media-placeholder__label">' . mp_escape($label) . '</span><span class="ep-media-placeholder__sub">Visual coming soon</span></div>';
    echo '</div>';
  }
}

$page = $page ?? [];
$hero = $page['hero'] ?? [];
$feature = $page['feature'] ?? null;
$showcase = $page['showcase'] ?? null;
$grid = $page['grid'] ?? null;
$sections = $page['sections'] ?? [];
$cta = $page['cta'] ?? null;
$form = $page['form'] ?? null;
$heroLayout = $hero['layout'] ?? 'cards';
$heroBandClass = $hero['band_class'] ?? 'ep-band--soft';
$styleVersion = @filemtime(dirname(__DIR__) . '/editorial-pages.css') ?: time();
$coreStyleVersion = @filemtime(dirname(__DIR__) . '/skate-upgrade.css') ?: $styleVersion;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= mp_escape($page['meta_title'] ?? 'On-Deck Alliance') ?></title>
  <link rel="icon" type="image/webp" href="/images/favicon.webp">
  <meta name="description" content="<?= mp_escape($page['meta_description'] ?? '') ?>">
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="/dist/output.css">
  <link rel="stylesheet" href="/skate-upgrade.css?v=<?= mp_escape((string) $coreStyleVersion) ?>">
  <link rel="stylesheet" href="/editorial-pages.css?v=<?= mp_escape((string) $styleVersion) ?>">
</head>
<body class="ep-page overflow-x-hidden <?= mp_escape($page['body_class'] ?? '') ?>">
  <?php require_once __DIR__ . '/header.php'; ?>
  <main>
    <section class="ep-band <?= mp_escape($heroBandClass) ?>">
      <div class="ep-shell">
        <?php if ($heroLayout === 'stacked_cards'): ?>
          <div class="ep-hero-stack-head">
            <?php if (!empty($hero['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['eyebrow']) ?></p><?php endif; ?>
            <h1 class="ep-title"><?= nl2br(mp_escape($hero['title'] ?? '')) ?></h1>
            <?php if (!empty($hero['copy'])): ?><p class="ep-copy"><?= mp_escape($hero['copy']) ?></p><?php endif; ?>
            <?php if (!empty($hero['buttons'])): ?>
              <div class="ep-hero-actions" style="justify-content:center;">
                <?php foreach ($hero['buttons'] as $button): ?>
                  <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="ep-hero-cards ep-hero-cards--stacked">
            <?php foreach (($hero['cards'] ?? []) as $card): ?>
              <article class="ep-mini-card">
                <div class="ep-mini-card__media">
                  <?php mp_render_media($card['image'] ?? '', $card['alt'] ?? ($card['title'] ?? ''), '', $card['title'] ?? 'Image'); ?>
                </div>
                <div class="ep-mini-card__body">
                  <span class="ep-mini-card__title"><?= mp_escape($card['title'] ?? '') ?></span>
                  <?php if (!empty($card['copy'])): ?><span class="ep-mini-card__copy"><?= mp_escape($card['copy']) ?></span><?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

        <?php elseif ($heroLayout === 'feature'): ?>
          <div class="ep-hero-grid">
            <div class="ep-hero-copy">
              <?php if (!empty($hero['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['eyebrow']) ?></p><?php endif; ?>
              <h1 class="ep-title"><?= nl2br(mp_escape($hero['title'] ?? '')) ?></h1>
              <?php if (!empty($hero['copy'])): ?><p class="ep-copy"><?= mp_escape($hero['copy']) ?></p><?php endif; ?>
              <?php if (!empty($hero['buttons'])): ?>
                <div class="ep-hero-actions">
                  <?php foreach ($hero['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <figure class="ep-hero-media">
              <?php mp_render_media($hero['image'] ?? '', $hero['alt'] ?? ($hero['title'] ?? ''), '', $hero['title'] ?? 'Hero Image'); ?>
              <?php if (!empty($hero['notes'])): ?>
                <div class="ep-hero-note-stack">
                  <?php foreach ($hero['notes'] as $note): ?>
                    <article class="ep-hero-note">
                      <?php if (!empty($note['eyebrow'])): ?><span class="ep-hero-note__kicker"><?= mp_escape($note['eyebrow']) ?></span><?php endif; ?>
                      <span class="ep-hero-note__title"><?= mp_escape($note['title'] ?? '') ?></span>
                      <?php if (!empty($note['copy'])): ?><span class="ep-hero-note__copy"><?= mp_escape($note['copy']) ?></span><?php endif; ?>
                    </article>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </figure>
          </div>

        <?php elseif ($heroLayout === 'split_image'): ?>
          <div class="ep-hero-grid">
            <div class="ep-hero-copy">
              <?php if (!empty($hero['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['eyebrow']) ?></p><?php endif; ?>
              <h1 class="ep-title"><?= nl2br(mp_escape($hero['title'] ?? '')) ?></h1>
              <?php if (!empty($hero['copy'])): ?><p class="ep-copy"><?= mp_escape($hero['copy']) ?></p><?php endif; ?>
              <?php if (!empty($hero['buttons'])): ?>
                <div class="ep-hero-actions">
                  <?php foreach ($hero['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <figure class="ep-hero-frame<?= !empty($hero['mono']) ? ' ep-hero-frame--mono' : '' ?>">
              <?php mp_render_media($hero['image'] ?? '', $hero['alt'] ?? ($hero['title'] ?? ''), '', $hero['title'] ?? 'Hero Image'); ?>
              <?php if (!empty($hero['caption'])): ?>
                <figcaption class="ep-hero-caption-card">
                  <?php if (!empty($hero['caption']['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['caption']['eyebrow']) ?></p><?php endif; ?>
                  <p class="ep-copy"><?= mp_escape($hero['caption']['copy'] ?? '') ?></p>
                </figcaption>
              <?php endif; ?>
            </figure>
          </div>

        <?php elseif ($heroLayout === 'dark_showcase'): ?>
          <div class="ep-showcase-grid">
            <figure class="ep-showcase-photo">
              <?php mp_render_media($hero['image'] ?? '', $hero['alt'] ?? ($hero['title'] ?? ''), '', $hero['title'] ?? 'Hero Image'); ?>
            </figure>
            <div class="ep-showcase-side">
              <?php if (!empty($hero['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['eyebrow']) ?></p><?php endif; ?>
              <h1 class="ep-title"><?= nl2br(mp_escape($hero['title'] ?? '')) ?></h1>
              <?php if (!empty($hero['copy'])): ?><p class="ep-copy"><?= mp_escape($hero['copy']) ?></p><?php endif; ?>
              <?php if (!empty($hero['chips'])): ?>
                <div class="ep-hero-chip-row">
                  <?php foreach ($hero['chips'] as $chip): ?>
                    <span class="ep-hero-chip"><?= mp_escape($chip) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <?php foreach (($hero['notes'] ?? []) as $note): ?>
                <article class="ep-overlay-card">
                  <?php if (!empty($note['eyebrow'])): ?><span class="ep-overlay-card__kicker"><?= mp_escape($note['eyebrow']) ?></span><?php endif; ?>
                  <span class="ep-overlay-card__title"><?= mp_escape($note['title'] ?? '') ?></span>
                  <?php if (!empty($note['copy'])): ?><span class="ep-overlay-card__copy"><?= mp_escape($note['copy']) ?></span><?php endif; ?>
                </article>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($heroLayout === 'contact_panel'): ?>
          <div class="ep-hero-grid">
            <div class="ep-hero-copy">
              <?php if (!empty($hero['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['eyebrow']) ?></p><?php endif; ?>
              <h1 class="ep-title"><?= nl2br(mp_escape($hero['title'] ?? '')) ?></h1>
              <?php if (!empty($hero['copy'])): ?><p class="ep-copy"><?= mp_escape($hero['copy']) ?></p><?php endif; ?>
              <?php if (!empty($hero['buttons'])): ?>
                <div class="ep-hero-actions">
                  <?php foreach ($hero['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="ep-hero-panel">
              <?php if (!empty($hero['panel']['title'])): ?><h2 class="ep-hero-panel__title"><?= mp_escape($hero['panel']['title']) ?></h2><?php endif; ?>
              <?php if (!empty($hero['panel']['copy'])): ?><p class="ep-hero-panel__copy"><?= mp_escape($hero['panel']['copy']) ?></p><?php endif; ?>
              <?php if (!empty($hero['panel']['items'])): ?>
                <ul class="ep-hero-panel__items">
                  <?php foreach ($hero['panel']['items'] as $item): ?>
                    <li><?= mp_escape($item) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <?php if (!empty($hero['panel']['buttons'])): ?>
                <div class="ep-panel-actions">
                  <?php foreach ($hero['panel']['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

        <?php else: ?>
          <div class="ep-hero-grid">
            <div class="ep-hero-copy">
              <?php if (!empty($hero['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($hero['eyebrow']) ?></p><?php endif; ?>
              <h1 class="ep-title"><?= nl2br(mp_escape($hero['title'] ?? '')) ?></h1>
              <?php if (!empty($hero['copy'])): ?><p class="ep-copy"><?= mp_escape($hero['copy']) ?></p><?php endif; ?>
              <?php if (!empty($hero['buttons'])): ?>
                <div class="ep-hero-actions">
                  <?php foreach ($hero['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="ep-hero-cards<?= $heroLayout === 'mono_cards' ? ' ep-hero-cards--mono' : '' ?>">
              <?php foreach (($hero['cards'] ?? []) as $card): ?>
                <article class="ep-mini-card">
                  <div class="ep-mini-card__media">
                    <?php mp_render_media($card['image'] ?? '', $card['alt'] ?? ($card['title'] ?? ''), '', $card['title'] ?? 'Image'); ?>
                  </div>
                  <div class="ep-mini-card__body">
                    <span class="ep-mini-card__title"><?= mp_escape($card['title'] ?? '') ?></span>
                    <?php if (!empty($card['copy'])): ?><span class="ep-mini-card__copy"><?= mp_escape($card['copy']) ?></span><?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php if ($feature): ?>
      <section class="ep-band <?= !empty($feature['band_class']) ? mp_escape($feature['band_class']) : '' ?>">
        <div class="ep-shell ep-split-grid <?= !empty($feature['reverse']) ? 'ep-split-grid--reverse' : '' ?>">
          <div class="ep-split-copy">
            <?php if (!empty($feature['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($feature['eyebrow']) ?></p><?php endif; ?>
            <h2 class="ep-section-title"><?= nl2br(mp_escape($feature['title'] ?? '')) ?></h2>
            <?php if (!empty($feature['copy'])): ?><p class="ep-section-copy"><?= mp_escape($feature['copy']) ?></p><?php endif; ?>
            <?php if (!empty($feature['bullets'])): ?>
              <ul class="ep-list">
                <?php foreach ($feature['bullets'] as $bullet): ?>
                  <li><?= mp_escape($bullet) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <?php if (!empty($feature['buttons'])): ?>
              <div class="ep-panel-actions">
                <?php foreach ($feature['buttons'] as $button): ?>
                  <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <figure class="ep-split-image">
            <?php mp_render_media($feature['image'] ?? '', $feature['alt'] ?? ($feature['title'] ?? ''), '', $feature['title'] ?? 'Feature Image'); ?>
          </figure>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($showcase): ?>
      <section class="ep-band ep-band--dark">
        <div class="ep-shell ep-showcase-grid">
          <figure class="ep-showcase-photo">
            <?php mp_render_media($showcase['image'] ?? '', $showcase['alt'] ?? ($showcase['title'] ?? ''), '', $showcase['title'] ?? 'Showcase Image'); ?>
          </figure>
          <div class="ep-showcase-side">
            <?php if (!empty($showcase['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($showcase['eyebrow']) ?></p><?php endif; ?>
            <h2 class="ep-section-title"><?= nl2br(mp_escape($showcase['title'] ?? '')) ?></h2>
            <?php if (!empty($showcase['copy'])): ?><p class="ep-section-copy"><?= mp_escape($showcase['copy']) ?></p><?php endif; ?>
            <?php foreach (($showcase['cards'] ?? []) as $card): ?>
              <article class="ep-overlay-card">
                <?php if (!empty($card['eyebrow'])): ?><span class="ep-overlay-card__kicker"><?= mp_escape($card['eyebrow']) ?></span><?php endif; ?>
                <span class="ep-overlay-card__title"><?= mp_escape($card['title'] ?? '') ?></span>
                <?php if (!empty($card['copy'])): ?><span class="ep-overlay-card__copy"><?= mp_escape($card['copy']) ?></span><?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($grid): ?>
      <section class="ep-band <?= !empty($grid['band_class']) ? mp_escape($grid['band_class']) : '' ?>">
        <div class="ep-shell">
          <div class="ep-grid-head">
            <?php if (!empty($grid['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($grid['eyebrow']) ?></p><?php endif; ?>
            <h2 class="ep-section-title"><?= nl2br(mp_escape($grid['title'] ?? '')) ?></h2>
            <?php if (!empty($grid['copy'])): ?><p class="ep-section-copy"><?= mp_escape($grid['copy']) ?></p><?php endif; ?>
          </div>
          <div class="ep-grid">
            <?php foreach (($grid['cards'] ?? []) as $card): ?>
              <article class="ep-grid-card">
                <div class="ep-grid-card__media">
                  <?php mp_render_media($card['image'] ?? '', $card['alt'] ?? ($card['title'] ?? ''), '', $card['title'] ?? 'Grid Image'); ?>
                </div>
                <div class="ep-grid-card__body">
                  <h3 class="ep-card-title"><?= mp_escape($card['title'] ?? '') ?></h3>
                  <?php if (!empty($card['copy'])): ?><p class="ep-grid-card__copy"><?= mp_escape($card['copy']) ?></p><?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php foreach ($sections as $section): ?>
      <?php $sectionLayout = $section['layout'] ?? 'grid'; ?>
      <?php if ($sectionLayout === 'split_image'): ?>
        <section<?= !empty($section['id']) ? ' id="' . mp_escape($section['id']) . '"' : '' ?> class="ep-band <?= !empty($section['band_class']) ? mp_escape($section['band_class']) : '' ?>">
          <div class="ep-shell ep-split-grid <?= !empty($section['reverse']) ? 'ep-split-grid--reverse' : '' ?>">
            <div class="ep-split-copy">
              <?php if (!empty($section['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($section['eyebrow']) ?></p><?php endif; ?>
              <h2 class="ep-section-title"><?= nl2br(mp_escape($section['title'] ?? '')) ?></h2>
              <?php if (!empty($section['copy'])): ?><p class="ep-section-copy"><?= mp_escape($section['copy']) ?></p><?php endif; ?>
              <?php if (!empty($section['bullets'])): ?>
                <ul class="ep-list">
                  <?php foreach ($section['bullets'] as $bullet): ?>
                    <li><?= mp_escape($bullet) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <?php if (!empty($section['buttons'])): ?>
                <div class="ep-panel-actions">
                  <?php foreach ($section['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <figure class="ep-split-image">
              <?php mp_render_media($section['image'] ?? '', $section['alt'] ?? ($section['title'] ?? ''), '', $section['title'] ?? 'Feature Image'); ?>
            </figure>
          </div>
        </section>
      <?php elseif ($sectionLayout === 'showcase'): ?>
        <section<?= !empty($section['id']) ? ' id="' . mp_escape($section['id']) . '"' : '' ?> class="ep-band <?= !empty($section['band_class']) ? mp_escape($section['band_class']) : 'ep-band--dark' ?>">
          <div class="ep-shell ep-showcase-grid">
            <figure class="ep-showcase-photo">
              <?php mp_render_media($section['image'] ?? '', $section['alt'] ?? ($section['title'] ?? ''), '', $section['title'] ?? 'Showcase Image'); ?>
            </figure>
            <div class="ep-showcase-side">
              <?php if (!empty($section['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($section['eyebrow']) ?></p><?php endif; ?>
              <h2 class="ep-section-title"><?= nl2br(mp_escape($section['title'] ?? '')) ?></h2>
              <?php if (!empty($section['copy'])): ?><p class="ep-section-copy"><?= mp_escape($section['copy']) ?></p><?php endif; ?>
              <?php foreach (($section['cards'] ?? []) as $card): ?>
                <article class="ep-overlay-card">
                  <?php if (!empty($card['eyebrow'])): ?><span class="ep-overlay-card__kicker"><?= mp_escape($card['eyebrow']) ?></span><?php endif; ?>
                  <span class="ep-overlay-card__title"><?= mp_escape($card['title'] ?? '') ?></span>
                  <?php if (!empty($card['copy'])): ?><span class="ep-overlay-card__copy"><?= mp_escape($card['copy']) ?></span><?php endif; ?>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      <?php elseif ($sectionLayout === 'grid'): ?>
        <section<?= !empty($section['id']) ? ' id="' . mp_escape($section['id']) . '"' : '' ?> class="ep-band <?= !empty($section['band_class']) ? mp_escape($section['band_class']) : '' ?>">
          <div class="ep-shell">
            <div class="ep-grid-head">
              <?php if (!empty($section['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($section['eyebrow']) ?></p><?php endif; ?>
              <h2 class="ep-section-title"><?= nl2br(mp_escape($section['title'] ?? '')) ?></h2>
              <?php if (!empty($section['copy'])): ?><p class="ep-section-copy"><?= mp_escape($section['copy']) ?></p><?php endif; ?>
            </div>
            <div class="ep-grid">
              <?php foreach (($section['cards'] ?? []) as $card): ?>
                <article class="ep-grid-card">
                  <div class="ep-grid-card__media">
                    <?php mp_render_media($card['image'] ?? '', $card['alt'] ?? ($card['title'] ?? ''), '', $card['title'] ?? 'Grid Image'); ?>
                  </div>
                  <div class="ep-grid-card__body">
                    <h3 class="ep-card-title"><?= mp_escape($card['title'] ?? '') ?></h3>
                    <?php if (!empty($card['copy'])): ?><p class="ep-grid-card__copy"><?= mp_escape($card['copy']) ?></p><?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      <?php elseif ($sectionLayout === 'cta'): ?>
        <section<?= !empty($section['id']) ? ' id="' . mp_escape($section['id']) . '"' : '' ?> class="ep-band <?= !empty($section['band_class']) ? mp_escape($section['band_class']) : '' ?>">
          <div class="ep-shell ep-cta-grid">
            <div class="ep-cta-copy">
              <?php if (!empty($section['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($section['eyebrow']) ?></p><?php endif; ?>
              <h2 class="ep-section-title"><?= nl2br(mp_escape($section['title'] ?? '')) ?></h2>
              <?php if (!empty($section['copy'])): ?><p class="ep-section-copy"><?= mp_escape($section['copy']) ?></p><?php endif; ?>
              <?php if (!empty($section['bullets'])): ?>
                <ul class="ep-list">
                  <?php foreach ($section['bullets'] as $bullet): ?>
                    <li><?= mp_escape($bullet) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <?php if (!empty($section['buttons'])): ?>
                <div class="ep-panel-actions">
                  <?php foreach ($section['buttons'] as $button): ?>
                    <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <figure class="ep-cta-panel ep-cta-panel--image">
              <?php mp_render_media($section['image'] ?? '', $section['alt'] ?? ($section['title'] ?? ''), '', $section['title'] ?? 'Callout Image'); ?>
            </figure>
          </div>
        </section>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($cta): ?>
      <section class="ep-band <?= !empty($cta['band_class']) ? mp_escape($cta['band_class']) : '' ?>">
        <div class="ep-shell ep-cta-grid">
          <div class="ep-cta-copy">
            <?php if (!empty($cta['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($cta['eyebrow']) ?></p><?php endif; ?>
            <h2 class="ep-section-title"><?= nl2br(mp_escape($cta['title'] ?? '')) ?></h2>
            <?php if (!empty($cta['copy'])): ?><p class="ep-section-copy"><?= mp_escape($cta['copy']) ?></p><?php endif; ?>
            <?php if (!empty($cta['bullets'])): ?>
              <ul class="ep-list">
                <?php foreach ($cta['bullets'] as $bullet): ?>
                  <li><?= mp_escape($bullet) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <?php if (!empty($cta['buttons'])): ?>
              <div class="ep-panel-actions">
                <?php foreach ($cta['buttons'] as $button): ?>
                  <a class="ep-btn <?= mp_escape($button['class'] ?? 'ep-btn--primary') ?>" href="<?= mp_escape($button['href'] ?? '#') ?>"><?= mp_escape($button['label'] ?? 'Learn More') ?></a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <figure class="ep-cta-panel ep-cta-panel--image">
            <?php mp_render_media($cta['image'] ?? '', $cta['alt'] ?? ($cta['title'] ?? ''), '', $cta['title'] ?? 'Callout Image'); ?>
          </figure>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($form): ?>
      <section class="ep-band <?= !empty($form['band_class']) ? mp_escape($form['band_class']) : '' ?>">
        <div class="ep-shell ep-form-grid">
          <div>
            <?php if (!empty($form['eyebrow'])): ?><p class="ep-kicker"><?= mp_escape($form['eyebrow']) ?></p><?php endif; ?>
            <h2 class="ep-form-title"><?= nl2br(mp_escape($form['title'] ?? '')) ?></h2>
            <?php if (!empty($form['copy'])): ?><p class="ep-form-copy"><?= mp_escape($form['copy']) ?></p><?php endif; ?>
          </div>
          <div class="ep-form-panel <?= !empty($form['dark']) ? 'ep-form-panel--dark' : '' ?>">
            <form method="POST" action="<?= mp_escape($form['action'] ?? '/contact-process.php') ?>" class="ep-form" novalidate>
              <?php if (!empty($form['csrf']) && function_exists('csrf_field')) echo csrf_field(); ?>
              <?php foreach (($form['hidden'] ?? []) as $name => $value): ?>
                <input type="hidden" name="<?= mp_escape($name) ?>" value="<?= mp_escape($value) ?>">
              <?php endforeach; ?>
              <div>
                <label for="name">Name</label>
                <input id="name" name="name" maxlength="120" placeholder="Your full name" required>
              </div>
              <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" maxlength="254" placeholder="you@example.com" required>
              </div>
              <div>
                <label for="phone">Phone</label>
                <input id="phone" name="phone" maxlength="20" placeholder="(419) 555-0123">
              </div>
              <?php if (!empty($form['interest'])): ?>
                <div>
                  <label for="interest"><?= mp_escape($form['interest']) ?></label>
                  <input id="interest" name="interests" maxlength="200" placeholder="Programs, volunteering, partnerships...">
                </div>
              <?php endif; ?>
              <div class="ep-form__field--full">
                <label for="message">Message</label>
                <textarea id="message" name="message" maxlength="2000" placeholder="Tell us how we can help, what program you're interested in, or any question you have." required></textarea>
              </div>
              <div class="ep-form__field--full">
                <button class="ep-btn <?= mp_escape($form['button_class'] ?? 'ep-btn--primary') ?>" type="submit"><?= mp_escape($form['button_label'] ?? 'Send') ?></button>
              </div>
            </form>
          </div>
        </div>
      </section>
    <?php endif; ?>
  </main>
  <?php require_once __DIR__ . '/footer.php'; ?>
  <script src="/js/donate-modal.js"></script>
</body>
</html>
