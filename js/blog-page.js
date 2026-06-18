/**
 * blog-page.js — behavior extracted from blog.php.
 */
(function () {
  'use strict';

  function sanitizeHtmlFragment(html) {
    const tpl = document.createElement('template');
    tpl.innerHTML = String(html || '');

    const blockedTags = new Set([
      'SCRIPT', 'IFRAME', 'OBJECT', 'EMBED', 'LINK', 'META', 'BASE', 'FORM', 'STYLE',
    ]);

    const walker = document.createTreeWalker(tpl.content, NodeFilter.SHOW_ELEMENT, null);
    const toRemove = [];
    while (walker.nextNode()) {
      const el = walker.currentNode;
      if (!(el instanceof Element)) continue;

      if (blockedTags.has(el.tagName)) {
        toRemove.push(el);
        continue;
      }

      Array.from(el.attributes).forEach((attr) => {
        const name = attr.name.toLowerCase();
        const value = attr.value || '';
        if (name.startsWith('on')) el.removeAttribute(attr.name);
        if ((name === 'href' || name === 'src') && /^\s*javascript:/i.test(value)) {
          el.removeAttribute(attr.name);
        }
      });
    }
    toRemove.forEach((n) => n.remove());
    return tpl.innerHTML;
  }

  function initBlogModal() {
    const modal = document.getElementById('post-modal');
    if (!modal) return;

    function closeModal() {
      if (window.ODAAnime) {
        window.ODAAnime.modalClose(modal, '#post-modal-inner');
        return;
      }
      modal.style.display = 'none';
      document.body.style.overflow = '';
    }

    function openPost(card) {
      const raw = card.querySelector('.post-data');
      if (!raw) return;
      const data = JSON.parse(raw.textContent || '{}');

      const metaEl = document.getElementById('modal-meta');
      const titleEl = document.getElementById('modal-title');
      const bodyEl = document.getElementById('modal-body');
      const imgWrap = document.getElementById('modal-image-wrap');
      if (!metaEl || !titleEl || !bodyEl || !imgWrap) return;

      metaEl.textContent = data.date || '';
      titleEl.textContent = data.title || '';
      bodyEl.innerHTML = sanitizeHtmlFragment(data.body || '');

      if (data.image) {
        imgWrap.innerHTML = '<img src="' + String(data.image) + '" alt="" style="width:100%;max-height:380px;object-fit:cover;display:block;">';
      } else {
        imgWrap.innerHTML = '';
      }

      if (window.ODAAnime) {
        window.ODAAnime.modalOpen(modal, '#post-modal-inner');
      } else {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    }

    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;

      const card = t.closest('[data-post-card]');
      if (card) {
        openPost(card);
        return;
      }

      if (t === modal || t.closest('[data-modal-close]')) closeModal();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter' && e.key !== ' ') return;
      const t = e.target;
      if (!(t instanceof Element)) return;
      const card = t.closest('[data-post-card]');
      if (!card) return;
      e.preventDefault();
      openPost(card);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initBlogModal();
  });
}());
