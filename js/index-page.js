/**
 * index-page.js — scripts extracted from index.php (home page).
 */
(function () {
  'use strict';

  function initHomeTestimonialRotator() {
    const rotator = document.getElementById('home-rotator');
    const quoteEl = document.getElementById('home-rotator-quote');
    const byEl = document.getElementById('home-rotator-by');
    const imgEl = document.getElementById('home-rotator-img');
    if (!rotator || !quoteEl || !byEl || !imgEl) return;

    const data = window.ODA_HOME && window.ODA_HOME.testimonial_rotator;
    const items = (data && Array.isArray(data.items)) ? data.items : [];
    if (!items.length) return;

    const helper = window.ODAAnime || null;
    const canAnimate = !!(helper && helper.hasAnime && !helper.prefersReducedMotion());

    let index = 0;
    function applyItem(nextIndex, animate) {
      index = (nextIndex + items.length) % items.length;
      const item = items[index] || {};

      const updateDom = () => {
        quoteEl.textContent = item.quote || '';
        byEl.textContent = item.by || '';
        if (item.img) imgEl.src = item.img;
        imgEl.alt = item.by ? String(item.by) : '';
      };

      if (!animate || !canAnimate) {
        updateDom();
        return;
      }

      window.anime.remove([quoteEl, byEl, imgEl]);
      const tl = window.anime.timeline({ easing: 'easeOutCubic' });
      tl.add({
        targets: [quoteEl, byEl],
        opacity: [1, 0],
        translateY: [0, 8],
        duration: 220,
      }, 0)
        .add({
          targets: imgEl,
          opacity: [1, 0],
          scale: [1, 0.98],
          duration: 220,
        }, 0)
        .add({
          targets: {},
          duration: 1,
          begin: updateDom,
        })
        .add({
          targets: [quoteEl, byEl],
          opacity: [0, 1],
          translateY: [8, 0],
          duration: 420,
        }, '-=1')
        .add({
          targets: imgEl,
          opacity: [0, 1],
          scale: [0.98, 1],
          duration: 420,
        }, '-=420');
    }

    applyItem(0, false);

    if (!canAnimate) return;
    const intervalMs = 30000;
    let timer = window.setInterval(() => applyItem(index + 1, true), intervalMs);

    function stop() {
      if (timer) window.clearInterval(timer);
      timer = 0;
    }
    function start() {
      stop();
      timer = window.setInterval(() => applyItem(index + 1, true), intervalMs);
    }

    rotator.addEventListener('mouseenter', stop);
    rotator.addEventListener('mouseleave', start);

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stop();
      else start();
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initHomeTestimonialRotator();
  });

  // skate-interactions + guided section scroll
  window.addEventListener('load', function () {
    const reducedMotion = window.ODAAnime ? window.ODAAnime.prefersReducedMotion() : (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    const hero = document.querySelector('.skate-hero');
    const media = document.querySelector('.hero-media');
    const canParallax = !window.matchMedia('(max-width: 768px)').matches;
    if (hero && media && canParallax && !reducedMotion) {
      // Use Anime.js as a scroll-scrubbed animation.
      const hasAnime = window.ODAAnime && window.ODAAnime.hasAnime;
      const par = hasAnime
        ? window.anime({ targets: media, translateY: [0, 60], scale: 1.04, easing: 'linear', duration: 1000, autoplay: false })
        : null;

      let ticking = false;
      function onScroll() {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(() => {
          const rect = hero.getBoundingClientRect();
          if (rect.bottom > 0 && rect.top < window.innerHeight) {
            const p = Math.min(1, Math.max(0, (0 - rect.top) / Math.max(1, rect.height)));
            if (par) par.seek(p * par.duration);
            else {
              const offset = Math.min(60, Math.max(-60, rect.top * -0.08));
              media.style.transform = `scale(1.04) translateY(${offset}px)`;
            }
          }
          ticking = false;
        });
      }
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    }
  });
}());
