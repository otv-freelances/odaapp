/**
 * header.js — behavior for partials/header.php.
 */
(function () {
  'use strict';

  function setDonateHandlers(root) {
    const donateEls = root.querySelectorAll('[data-open-donate]');
    donateEls.forEach((el) => {
      el.addEventListener('click', (e) => {
        e.preventDefault();
        if (typeof window.openDonateModal === 'function') {
          window.openDonateModal();
          return;
        }
        window.location.href = '/donate.php';
      });
    });
  }

  function initHeaderScrollState() {
    const header = document.getElementById('site-header');
    if (!header) return;
    function onScroll() {
      if (window.scrollY > 8) header.classList.add('is-scrolled');
      else header.classList.remove('is-scrolled');
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  function initMobileMenu() {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if (!menuBtn || !menu) return;

    const bars = menuBtn.querySelectorAll('.hamburger-bar');
    let lastActive = null;

    function getFirstFocusable(container) {
      return container.querySelector('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])');
    }

    function setOpen(open, animate) {
      const helper = window.ODAAnime;
      const shouldAnimate = animate !== false && helper && helper.hasAnime && !helper.prefersReducedMotion();

      document.documentElement.classList.toggle('is-mobile-menu-open', open);
      document.body.classList.toggle('is-mobile-menu-open', open);
      menu.setAttribute('aria-hidden', open ? 'false' : 'true');

      if (open) lastActive = document.activeElement;

      if (shouldAnimate) {
        menu.style.display = 'flex';
        window.anime.remove(menu);
        window.anime({
          targets: menu,
          opacity: open ? [0, 1] : [1, 0],
          translateY: open ? [-6, 0] : [0, -6],
          duration: open ? 160 : 120,
          easing: open ? 'easeOutCubic' : 'easeInCubic',
          complete: function () {
            if (!open) menu.style.display = 'none';
            menu.style.opacity = '';
            menu.style.transform = '';
          },
        });
      } else {
        menu.style.display = open ? 'flex' : 'none';
      }
      menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      menuBtn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');

      if (open) {
        const first = getFirstFocusable(menu);
        if (first) first.focus();
      } else if (lastActive && typeof lastActive.focus === 'function') {
        lastActive.focus();
      }

      if (bars.length >= 3) {
        if (open) {
          bars[0].style.transform = 'translateY(7px) rotate(45deg)';
          bars[1].style.opacity = '0';
          bars[2].style.transform = 'translateY(-7px) rotate(-45deg)';
        } else {
          bars[0].style.transform = '';
          bars[1].style.opacity = '';
          bars[2].style.transform = '';
        }
      }
    }

    menuBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const isOpen = menu.style.display === 'flex';
      setOpen(!isOpen, true);
    });

    menu.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;
      if (t.closest('[data-close-mobile-menu]') || t.closest('a[href]')) setOpen(false, true);
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menu.style.display === 'flex') setOpen(false, true);
    });

    setOpen(false, false);
  }

  document.addEventListener('DOMContentLoaded', function () {
    initHeaderScrollState();
    initMobileMenu();
    setDonateHandlers(document);
  });
}());
