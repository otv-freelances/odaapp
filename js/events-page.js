/**
 * events-page.js — behavior extracted from events.php.
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

  function initEventsModal() {
    const modal = document.getElementById('event-modal');
    if (!modal) return;

    function closeModal() {
      if (window.ODAAnime) {
        window.ODAAnime.modalClose(modal, '#event-modal-inner');
        return;
      }
      modal.style.display = 'none';
      document.body.style.overflow = '';
    }

    function openEvent(card) {
      const raw = card.querySelector('.event-data');
      if (!raw) return;
      const data = JSON.parse(raw.textContent || '{}');

      const badgeWrap = document.getElementById('modal-badge-wrap');
      const metaEl = document.getElementById('modal-meta');
      const titleEl = document.getElementById('modal-title');
      const bodyEl = document.getElementById('modal-body');
      const imgWrap = document.getElementById('modal-image-wrap');
      if (!badgeWrap || !metaEl || !titleEl || !bodyEl || !imgWrap) return;

      const meta = [data.date, data.time, data.location].filter(Boolean).join(' · ');
      metaEl.textContent = meta;
      titleEl.textContent = data.title || '';
      bodyEl.innerHTML = sanitizeHtmlFragment(data.body || '');

      badgeWrap.innerHTML = data.isPast
        ? '<span style="display:inline-block;background:#1a1a1a;color:rgba(255,255,255,0.7);font-family:Arial,sans-serif;font-weight:700;font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;padding:3px 10px;border-radius:4px;">Past Event</span>'
        : '<span style="display:inline-block;background:#1DB855;color:#fff;font-family:Arial,sans-serif;font-weight:700;font-size:0.55rem;text-transform:uppercase;letter-spacing:0.1em;padding:3px 10px;border-radius:4px;">Upcoming</span>';

      imgWrap.innerHTML = data.image
        ? '<img src="' + String(data.image) + '" alt="" style="width:100%;max-height:340px;object-fit:cover;display:block;">'
        : '';

      if (window.ODAAnime) {
        window.ODAAnime.modalOpen(modal, '#event-modal-inner');
      } else {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    }

    document.addEventListener('click', (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;

      const card = t.closest('[data-event-card]');
      if (card) {
        openEvent(card);
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
      const card = t.closest('[data-event-card]');
      if (!card) return;
      e.preventDefault();
      openEvent(card);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initEventsModal();
  });
}());
