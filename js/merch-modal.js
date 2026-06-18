/**
 * merch-modal.js — On-Deck Alliance Printify merch store overlay.
 * Drop in a <script src="/merch-modal.js"></script> and call openMerchModal().
 *
 * Reads window.ODA_MERCH.storeUrl (injected server-side via PHP).
 * The Printify Popup Store URL is set in config.php → PRINTIFY_STORE_URL.
 */
(function () {
  'use strict';

  /* ── CSS ───────────────────────────────────────────────────────────────── */
  var CSS = [
    '#oda-merch-overlay{position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.72);padding:1rem;}',
    '#oda-merch-overlay.oda-merch-open{display:flex;}',
    '#oda-merch-panel{position:relative;background:#fff;border-radius:16px;width:100%;max-width:1100px;height:90vh;max-height:880px;display:flex;flex-direction:column;box-shadow:0 32px 80px rgba(0,0,0,.45);animation:odaMerchUp .22s ease;overflow:hidden;}',
    '@keyframes odaMerchUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}',
    '#oda-merch-header{display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.25rem;border-bottom:1px solid #e5e7eb;flex-shrink:0;background:#fff;gap:1rem;}',
    '#oda-merch-title{font-family:"Arial Black",Arial,sans-serif;font-weight:900;font-size:1rem;text-transform:uppercase;letter-spacing:.05em;color:#1a1a1a;line-height:1.1;}',
    '#oda-merch-subtitle{font-family:Arial,sans-serif;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:#CC3A20;margin-bottom:3px;}',
    '#oda-merch-close{background:rgba(0,0,0,.07);border:none;border-radius:50%;width:34px;height:34px;cursor:pointer;font-size:1.05rem;color:#6b7280;display:flex;align-items:center;justify-content:center;transition:background .15s;line-height:1;flex-shrink:0;}',
    '#oda-merch-close:hover{background:rgba(0,0,0,.15);}',
    '#oda-merch-body{position:relative;flex:1;display:flex;flex-direction:column;overflow:hidden;}',
    '#oda-merch-iframe{border:none;width:100%;flex:1;background:#f9fafb;display:block;}',
    '#oda-merch-loading{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#fff;z-index:2;transition:opacity .3s;}',
    '#oda-merch-loading.oda-merch-hide{opacity:0;pointer-events:none;}',
    '.oda-merch-spin{width:38px;height:38px;border:4px solid #e5e7eb;border-top-color:#CC3A20;border-radius:50%;animation:odaMerchSpin .7s linear infinite;margin-bottom:.8rem;}',
    '@keyframes odaMerchSpin{to{transform:rotate(360deg)}}',
    '#oda-merch-empty{position:absolute;inset:0;display:none;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem;text-align:center;gap:.5rem;}',
    '#oda-merch-empty strong{font-family:"Arial Black",Arial,sans-serif;font-weight:900;font-size:1.1rem;color:#1a1a1a;text-transform:uppercase;letter-spacing:.03em;}',
    '#oda-merch-empty p{font-family:Arial,sans-serif;font-size:.84rem;color:#6b7280;margin:0;line-height:1.65;max-width:380px;}',
    '#oda-merch-empty code{font-family:monospace;font-size:.82rem;background:#f3f4f6;padding:1px 5px;border-radius:4px;color:#374151;}',
    '@media(max-width:640px){#oda-merch-panel{height:100%;max-height:none;border-radius:0;}}',
  ].join('');

  var styleEl = document.createElement('style');
  styleEl.textContent = CSS;
  document.head.appendChild(styleEl);

  /* ── HTML ──────────────────────────────────────────────────────────────── */
  var overlay = document.createElement('div');
  overlay.id = 'oda-merch-overlay';
  overlay.setAttribute('role', 'dialog');
  overlay.setAttribute('aria-modal', 'true');
  overlay.setAttribute('aria-label', 'ODA Merch Store');
  overlay.innerHTML =
    '<div id="oda-merch-panel">'
    + '<div id="oda-merch-header">'
    +   '<div>'
    +     '<div id="oda-merch-subtitle">On-Deck Alliance</div>'
    +     '<div id="oda-merch-title">Merch Store</div>'
    +   '</div>'
    +   '<button id="oda-merch-close" type="button" aria-label="Close store">&#215;</button>'
    + '</div>'
    + '<div id="oda-merch-body">'
    +   '<div id="oda-merch-loading">'
    +     '<div class="oda-merch-spin"></div>'
    +     '<span style="font-family:Arial,sans-serif;font-size:.75rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;">Loading store\u2026</span>'
    +   '</div>'
    +   '<div id="oda-merch-empty">'
    +     '<strong>Store Not Configured</strong>'
    +     '<p>Set your Printify Popup Store URL in <code>config.php</code> (<code>PRINTIFY_STORE_URL</code>) to activate the merch store.</p>'
    +   '</div>'
    +   '<iframe id="oda-merch-iframe" title="ODA Merch Store" allowfullscreen></iframe>'
    + '</div>'
    + '</div>';
  document.body.appendChild(overlay);

  /* ── Refs ──────────────────────────────────────────────────────────────── */
  var panel    = document.getElementById('oda-merch-panel');
  var closeBtn = document.getElementById('oda-merch-close');
  var iframe   = document.getElementById('oda-merch-iframe');
  var loading  = document.getElementById('oda-merch-loading');
  var empty    = document.getElementById('oda-merch-empty');

  /* ── Helpers ───────────────────────────────────────────────────────────── */
  function getStoreUrl() {
    return (window.ODA_MERCH && typeof window.ODA_MERCH.storeUrl === 'string')
      ? window.ODA_MERCH.storeUrl.trim()
      : '';
  }

  function open() {
    var url = getStoreUrl();
    overlay.classList.add('oda-merch-open');
    document.body.style.overflow = 'hidden';

    if (!url) {
      loading.style.display = 'none';
      empty.style.display = 'flex';
      iframe.style.display = 'none';
      return;
    }

    /* Only reload the iframe if the URL changed or it was never set */
    if (iframe.getAttribute('src') !== url) {
      loading.style.display = 'flex';
      loading.classList.remove('oda-merch-hide');
      empty.style.display = 'none';
      iframe.style.display = 'block';
      iframe.setAttribute('src', url);
    } else {
      loading.style.display = 'none';
      empty.style.display = 'none';
      iframe.style.display = 'block';
    }
  }

  function close() {
    overlay.classList.remove('oda-merch-open');
    document.body.style.overflow = '';
  }

  /* ── Hide loader once iframe content loads ─────────────────────────────── */
  iframe.addEventListener('load', function () {
    loading.classList.add('oda-merch-hide');
    setTimeout(function () { loading.style.display = 'none'; }, 320);
  });

  /* ── Close triggers ────────────────────────────────────────────────────── */
  closeBtn.addEventListener('click', close);
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) close();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('oda-merch-open')) close();
  });

  /* ── Trap focus inside panel ────────────────────────────────────────────── */
  panel.addEventListener('keydown', function (e) {
    if (e.key !== 'Tab') return;
    var focusable = panel.querySelectorAll('button,iframe,[tabindex]:not([tabindex="-1"])');
    var first = focusable[0];
    var last  = focusable[focusable.length - 1];
    if (e.shiftKey) {
      if (document.activeElement === first) { e.preventDefault(); last.focus(); }
    } else {
      if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
    }
  });

  /* ── Public API ─────────────────────────────────────────────────────────── */
  window.openMerchModal  = open;
  window.closeMerchModal = close;
}());
