/**
 * program-page.js — behavior extracted from program.php.
 */
(function () {
  'use strict';

  function initAccordions() {
    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;

      const phaseBtn = t.closest('.eys-toggle');
      if (phaseBtn) {
        const body = phaseBtn.parentElement && phaseBtn.parentElement.querySelector('.eys-body');
        const chevron = phaseBtn.querySelector('.eys-chevron');
        if (!body) return;
        const open = body.style.display !== 'none';
        body.style.display = open ? 'none' : 'block';
        if (chevron) chevron.style.transform = open ? '' : 'rotate(180deg)';
        return;
      }

      const faqBtn = t.closest('.faq-toggle');
      if (faqBtn) {
        const body = faqBtn.parentElement && faqBtn.parentElement.querySelector('.faq-body');
        const chevron = faqBtn.querySelector('.faq-chevron');
        if (!body) return;
        const open = body.style.display !== 'none';
        body.style.display = open ? 'none' : 'block';
        if (chevron) chevron.style.transform = open ? '' : 'rotate(180deg)';
      }
    });
  }

  function initSignupFormColumns() {
    const form = document.querySelector('.signup-form');
    if (!form) return;
    function checkWidth() {
      if (window.innerWidth < 640) {
        form.style.gridTemplateColumns = '1fr';
      } else {
        form.style.gridTemplateColumns = '1fr 1fr';
      }
    }
    checkWidth();
    window.addEventListener('resize', checkWidth);
  }

  document.addEventListener('DOMContentLoaded', function () {
    initAccordions();
    initSignupFormColumns();
  });
}());

