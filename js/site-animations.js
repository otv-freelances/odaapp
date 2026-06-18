/**
 * site-animations.js — global Anime.js effects across the site.
 *
 * Uses helpers from `window.ODAAnime` when available.
 */
(function () {
  'use strict';

  function splitTextIntoWords(root) {
    if (!root) return [];
    if (root.dataset.odaSplitDone === '1') {
      return Array.from(root.querySelectorAll('.oda-word'));
    }
    root.dataset.odaSplitDone = '1';

    const words = [];
    function processNode(node) {
      if (node.nodeType === Node.TEXT_NODE) {
        const text = node.nodeValue || '';
        if (!text.trim()) return;
        const frag = document.createDocumentFragment();
        const parts = text.split(/(\s+)/);
        parts.forEach((p) => {
          if (!p) return;
          if (/^\s+$/.test(p)) {
            frag.appendChild(document.createTextNode(p));
            return;
          }
          const span = document.createElement('span');
          span.className = 'oda-word';
          span.textContent = p;
          frag.appendChild(span);
          words.push(span);
        });
        node.parentNode && node.parentNode.replaceChild(frag, node);
        return;
      }
      if (node.nodeType !== Node.ELEMENT_NODE) return;
      const el = node;
      if (el.closest && el.closest('script,style,textarea,input,select')) return;
      Array.from(el.childNodes).forEach(processNode);
    }
    Array.from(root.childNodes).forEach(processNode);
    return words;
  }

  function prepareReveal(el) {
    if (!el || el.dataset.odaRevealPrepared === '1') return;
    el.dataset.odaRevealPrepared = '1';
    el.style.opacity = '0';
    el.style.transform = 'translateY(18px)';
  }

  function revealElements(els, preset, opts) {
    const helper = window.ODAAnime;
    if (!helper) return;
    const list = Array.isArray(els) ? els : Array.from(els || []);
    list.forEach(prepareReveal);
    helper.observeOnce(list, (el) => helper.animateIn(el, preset || 'fadeUp', opts));
  }

  function revealStagger(container, itemSelector, preset) {
    const helper = window.ODAAnime;
    if (!helper || !container) return;
    const items = Array.from(container.querySelectorAll(itemSelector));
    if (!items.length) return;
    items.forEach(prepareReveal);
    helper.observeOnce([container], () => {
      if (helper.prefersReducedMotion() || !helper.hasAnime) {
        items.forEach((el) => { el.style.opacity = ''; el.style.transform = ''; });
        return;
      }
      window.anime.remove(items);
      window.anime({
        targets: items,
        opacity: [0, 1],
        translateY: [18, 0],
        duration: 720,
        delay: window.anime.stagger(70, { start: 0 }),
        easing: 'easeOutCubic',
      });
    }, { threshold: 0.18, rootMargin: '0px 0px -10% 0px' });
  }

  function initTextReveals() {
    const helper = window.ODAAnime;
    if (!helper) return;
    const targets = Array.from(document.querySelectorAll('[data-oda-split="words"]'));
    targets.forEach((el) => {
      const words = splitTextIntoWords(el);
      words.forEach((w) => {
        w.style.display = 'inline-block';
        w.style.opacity = '0';
        w.style.transform = 'translateY(12px)';
      });
      helper.observeOnce([el], () => {
        if (helper.prefersReducedMotion() || !helper.hasAnime) {
          words.forEach((w) => { w.style.opacity = ''; w.style.transform = ''; });
          return;
        }
        window.anime.remove(words);
        window.anime({
          targets: words,
          opacity: [0, 1],
          translateY: [12, 0],
          duration: 760,
          delay: window.anime.stagger(28),
          easing: 'easeOutCubic',
          complete: function () {
            words.forEach((w) => { w.style.opacity = ''; w.style.transform = ''; });
          },
        });
      }, { threshold: 0.35, rootMargin: '0px 0px -10% 0px' });
    });
  }

  function initSvgDraw() {
    const helper = window.ODAAnime;
    if (!helper || helper.prefersReducedMotion() || !helper.hasAnime) return;

    const svgs = Array.from(document.querySelectorAll('svg[data-oda-draw]'));
    if (!svgs.length) return;

    const shapes = [];
    svgs.forEach((svg) => {
      svg.querySelectorAll('path, line, polyline, polygon, circle, rect').forEach((shape) => {
        if (shape.getAttribute('fill') && shape.getAttribute('fill') !== 'none') return;
        if (!shape.getAttribute('stroke')) return;
        shapes.push(shape);
      });
    });

    shapes.forEach((s) => {
      try {
        // Works for SVGGeometryElement (path/rect/circle/etc in modern browsers).
        const len = s.getTotalLength ? s.getTotalLength() : 0;
        if (!len || !Number.isFinite(len)) return;
        s.style.strokeDasharray = String(len);
        s.style.strokeDashoffset = String(len);
        s.style.opacity = '1';
      } catch {}
    });

    helper.observeOnce(svgs, () => {
      window.anime({
        targets: shapes,
        strokeDashoffset: [window.anime.setDashoffset, 0],
        duration: 900,
        delay: window.anime.stagger(60),
        easing: 'easeOutCubic',
      });
    }, { threshold: 0.9 });
  }

  function initHoverMicroInteractions() {
    const helper = window.ODAAnime;
    if (!helper || helper.prefersReducedMotion() || !helper.hasAnime) return;

    const hoverables = Array.from(document.querySelectorAll('.btn-slide, .nav-btn, .oda-btn, .feed-card, .event-card'));
    hoverables.forEach((el) => {
      if (el.dataset.odaHoverDone === '1') return;
      el.dataset.odaHoverDone = '1';

      el.addEventListener('pointerenter', () => {
        window.anime.remove(el);
        window.anime({
          targets: el,
          translateY: -3,
          scale: 1.02,
          duration: 220,
          easing: 'easeOutCubic',
        });
      });
      el.addEventListener('pointerleave', () => {
        window.anime.remove(el);
        window.anime({
          targets: el,
          translateY: 0,
          scale: 1,
          duration: 180,
          easing: 'easeOutCubic',
        });
      });
    });
  }

  function initCtaBreath() {
    const helper = window.ODAAnime;
    if (!helper || helper.prefersReducedMotion() || !helper.hasAnime) return;
    const ctas = Array.from(document.querySelectorAll('.cta-primary'))
      // Avoid transform conflicts with the hero CTA intro animation.
      .filter((el) => !el.closest('.hero-content'));
    ctas.forEach((el) => {
      if (el.dataset.odaCtaBreath === '1') return;
      el.dataset.odaCtaBreath = '1';
      window.anime({
        targets: el,
        scale: [1, 1.025],
        direction: 'alternate',
        loop: true,
        duration: 1600,
        easing: 'easeInOutSine',
      });
    });
  }

  function initCountUps() {
    const helper = window.ODAAnime;
    if (!helper) return;
    const countEls = Array.from(document.querySelectorAll('[data-count]'));
    if (!countEls.length) return;
    countEls.forEach((el) => { el.textContent = '0'; });
    helper.observeOnce(countEls, (el) => helper.countUp(el), { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });
  }

  function initScrollReveals() {
    // Page-agnostic: common components.
    revealElements(document.querySelectorAll('.card'), 'flipUp');
    revealElements(document.querySelectorAll('.skate-surface, footer'), 'fadeUp');

    // Staggered groups.
    revealStagger(document.querySelector('.news-grid'), '.news-card', 'fadeUp');
    document.querySelectorAll('.masonry-grid, .grid').forEach((grid) => {
      if (grid.matches('.news-grid')) return;
      if (grid.querySelectorAll('.feed-card').length) {
        revealStagger(grid, '.feed-card', 'fadeUp');
      }
      if (grid.querySelectorAll('.event-card').length) {
        revealStagger(grid, '.event-card', 'fadeUp');
      }
    });

    revealElements(document.querySelectorAll('.feed-card, .event-card'), 'fadeUp');
  }

  function initParallaxMedia() {
    const helper = window.ODAAnime;
    if (!helper || helper.prefersReducedMotion() || !helper.hasAnime) return;

    const mediaEls = Array.from(document.querySelectorAll('.news-card img, .feed-card img, .event-card img'))
      .filter((el) => !el.classList.contains('hero-media'));
    if (!mediaEls.length) return;

    const instances = mediaEls.slice(0, 32).map((el) => ({
      el,
      anim: window.anime({
        targets: el,
        translateY: [10, -10],
        scale: 1.03,
        duration: 1000,
        easing: 'linear',
        autoplay: false,
      }),
    }));

    let ticking = false;
    function update() {
      ticking = false;
      const vh = window.innerHeight || 1;
      instances.forEach(({ el, anim }) => {
        const rect = el.getBoundingClientRect();
        if (rect.bottom <= 0 || rect.top >= vh) return;
        const total = vh + rect.height;
        const p = Math.min(1, Math.max(0, (vh - rect.top) / Math.max(1, total)));
        anim.seek(p * anim.duration);
      });
    }
    function onScroll() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(update);
    }
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (!window.ODAAnime) return;

    // Replace the old CSS hero fade-in with Anime.js.
    const hero = document.querySelector('.skate-hero:not(.home-hero):not([data-home-reference-hero])');
    if (hero) {
      prepareReveal(hero);
      window.ODAAnime.observeOnce([hero], (el) => window.ODAAnime.animateIn(el, 'fadeUp', { duration: 680 }), { threshold: 0.6 });
    }

    // Replace the old CSS CTA intro animation with Anime.js.
    const heroCtas = Array.from(document.querySelectorAll('.hero-content:not(.home-hero-content) .cta-animate'))
      .filter((el) => !el.closest('[data-home-reference-hero]'));
    if (heroCtas.length && window.ODAAnime.hasAnime && !window.ODAAnime.prefersReducedMotion()) {
      heroCtas.forEach((el) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
      });
      window.anime({
        targets: heroCtas,
        opacity: [0, 1],
        translateY: [10, 0],
        duration: 720,
        delay: window.anime.stagger(90, { start: 220 }),
        easing: 'easeOutCubic',
        complete: function () {
          heroCtas.forEach((el) => { el.style.opacity = ''; el.style.transform = ''; });
        },
      });
    }

    initTextReveals();
    initSvgDraw();
    initCountUps();
    initScrollReveals();
    initParallaxMedia();
    initCtaBreath();
    initHoverMicroInteractions();

    // bfcache: re-run counters after back/forward navigation.
    window.addEventListener('pageshow', function (e) {
      if (e && e.persisted) initCountUps();
    });
  });
}());
