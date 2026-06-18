/**
 * oda-anime.js — tiny helpers around Anime.js for ODA pages.
 * Requires `window.anime` (anime.min.js) but provides no-op fallbacks.
 */
(function () {
  'use strict';

  const hasAnime = typeof window.anime === 'function';

  function prefersReducedMotion() {
    return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
  }

  function fmtNumber(n) {
    try { return Number(n).toLocaleString('en-US'); } catch { return String(n); }
  }

  function safeDuration(ms, fallback) {
    const v = Number(ms);
    return Number.isFinite(v) && v > 0 ? v : fallback;
  }

  function animateIn(el, preset, opts) {
    if (!el) return;
    if (prefersReducedMotion() || !hasAnime) {
      el.style.opacity = '';
      el.style.transform = '';
      return;
    }
    const duration = safeDuration(opts && opts.duration, 700);
    const delay = safeDuration(opts && opts.delay, 0);
    const easing = (opts && opts.easing) || 'easeOutCubic';

    window.anime.remove(el);

    if (preset === 'flipUp') {
      el.style.transformOrigin = '50% 80%';
      window.anime({
        targets: el,
        opacity: [0, 1],
        translateY: [18, 0],
        rotateX: [12, 0],
        duration,
        delay,
        easing,
      });
      return;
    }

    // default: fadeUp
    window.anime({
      targets: el,
      opacity: [0, 1],
      translateY: [18, 0],
      duration,
      delay,
      easing,
    });
  }

  function observeOnce(els, onEnter, options) {
    const list = Array.isArray(els) ? els : Array.from(els || []);
    if (!list.length) return null;
    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
      list.forEach((el) => onEnter(el));
      return null;
    }
    const io = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        observer.unobserve(entry.target);
        onEnter(entry.target);
      });
    }, options || { threshold: 0.18, rootMargin: '0px 0px -10% 0px' });
    list.forEach((el) => io.observe(el));
    return io;
  }

  function countUp(el, opts) {
    if (!el) return;
    const to = Number(el.getAttribute('data-count') || (opts && opts.to) || '0');
    const prefix = el.getAttribute('data-prefix') || (opts && opts.prefix) || '';
    const suffix = el.getAttribute('data-suffix') || (opts && opts.suffix) || '';
    const durationAttr = el.getAttribute('data-duration');
    const duration = safeDuration(durationAttr || (opts && opts.duration), 1400);
    const easing = (opts && opts.easing) || 'easeOutExpo';

    if (!Number.isFinite(to) || prefersReducedMotion() || !hasAnime) {
      el.textContent = `${prefix}${fmtNumber(Math.floor(to || 0))}${suffix}`;
      return;
    }

    const state = { v: 0 };
    el.textContent = `${prefix}0${suffix}`;
    window.anime({
      targets: state,
      v: Math.max(0, Math.floor(to)),
      duration,
      easing,
      round: 1,
      update: function () {
        el.textContent = `${prefix}${fmtNumber(state.v)}${suffix}`;
      },
    });
  }

  function modalOpen(modalEl, innerSelector) {
    if (!modalEl) return;
    modalEl.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    const inner = innerSelector ? modalEl.querySelector(innerSelector) : null;
    if (!inner || prefersReducedMotion() || !hasAnime) return;
    window.anime.remove(inner);
    inner.style.opacity = '0';
    inner.style.transform = 'translateY(10px)';
    window.anime({
      targets: inner,
      opacity: [0, 1],
      translateY: [10, 0],
      duration: 220,
      easing: 'easeOutCubic',
    });
  }

  function modalClose(modalEl, innerSelector) {
    if (!modalEl) return;
    const inner = innerSelector ? modalEl.querySelector(innerSelector) : null;
    if (!inner || prefersReducedMotion() || !hasAnime) {
      modalEl.style.display = 'none';
      document.body.style.overflow = '';
      return;
    }
    window.anime.remove(inner);
    window.anime({
      targets: inner,
      opacity: [1, 0],
      translateY: [0, 10],
      duration: 180,
      easing: 'easeInCubic',
      complete: function () {
        modalEl.style.display = 'none';
        document.body.style.overflow = '';
        inner.style.opacity = '';
        inner.style.transform = '';
      },
    });
  }

  function scrollToY(y, opts) {
    const target = Math.max(0, Math.floor(Number(y) || 0));
    const offset = Number(opts && opts.offset) || 0;
    const to = Math.max(0, target + offset);

    if (prefersReducedMotion() || !hasAnime) {
      window.scrollTo(0, to);
      return;
    }

    const duration = safeDuration(opts && opts.duration, 750);
    const easing = (opts && opts.easing) || 'easeOutCubic';

    const state = { v: window.scrollY || 0 };
    window.anime.remove(state);
    window.anime({
      targets: state,
      v: to,
      duration,
      easing,
      update: function () { window.scrollTo(0, state.v); },
    });
  }

  function scrollToEl(el, opts) {
    if (!el) return;
    const rect = el.getBoundingClientRect();
    const top = (window.scrollY || 0) + rect.top;
    scrollToY(top, opts);
  }

  function enableSmoothAnchors(opts) {
    const selector = (opts && opts.selector) || 'a[href^="#"]';
    const offset = Number(opts && opts.offset) || 0;
    const duration = safeDuration(opts && opts.duration, 750);
    const easing = (opts && opts.easing) || 'easeOutCubic';

    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;
      const a = t.closest(selector);
      if (!(a instanceof HTMLAnchorElement)) return;

      const href = a.getAttribute('href') || '';
      if (!href.startsWith('#')) return;
      const id = href.slice(1);
      if (!id) return;

      const target = document.getElementById(id);
      if (!target) return;

      e.preventDefault();
      scrollToEl(target, { offset, duration, easing });
      // Keep URL in sync (without jumping).
      try { history.pushState(null, '', '#' + id); } catch {}
    });
  }

  window.ODAAnime = {
    hasAnime,
    prefersReducedMotion,
    animateIn,
    observeOnce,
    countUp,
    modalOpen,
    modalClose,
    scrollToY,
    scrollToEl,
    enableSmoothAnchors,
  };

  // Safe default: only smooth scroll for explicit anchor clicks.
  document.addEventListener('DOMContentLoaded', function () {
    enableSmoothAnchors({ offset: -2 });
  });
}());
