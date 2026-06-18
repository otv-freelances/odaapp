/**
 * donate-modal.js — On-Deck Alliance donation modal.
 * Drop in a <script src="/donate-modal.js"></script> and call openDonateModal().
 * Lazily fetches CSRF token + PayPal SDK on first open.
 */
(function () {
  'use strict';

  /* ── Constants ─────────────────────────────────────────────────────────── */
  var PROCESS_URL = '/donate-process.php';
  var TOKEN_URL   = '/donate-token.php';

  /* ── CSS ───────────────────────────────────────────────────────────────── */
  var CSS = [
    '#oda-overlay{position:fixed;inset:0;z-index:9998;display:none;align-items:flex-start;justify-content:center;background:rgba(0,0,0,.65);padding:1.25rem 1rem;overflow-y:auto;}',
    '#oda-overlay.oda-open{display:flex;}',
    '#oda-card{position:relative;background:#fff;border-radius:20px;width:100%;max-width:480px;margin:auto;padding:2rem 2rem 2.25rem;box-shadow:0 24px 64px rgba(0,0,0,.35);animation:odaUp .22s ease;}',
    '@keyframes odaUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}',
    '#oda-close-btn{position:absolute;top:.9rem;right:.9rem;background:rgba(0,0,0,.07);border:none;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;color:#6b7280;display:flex;align-items:center;justify-content:center;transition:background .15s;line-height:1;}',
    '#oda-close-btn:hover{background:rgba(0,0,0,.15);}',
    '.oda-pill{cursor:pointer;border:2px solid #e5e7eb;border-radius:8px;padding:8px 16px;background:#f9fafb;color:#374151;font-weight:700;font-size:.9rem;font-family:Arial,sans-serif;transition:all .15s;}',
    '.oda-pill:hover{border-color:#CC3A20;color:#CC3A20;}',
    '.oda-pill.oda-active{border-color:#CC3A20;background:#CC3A20;color:#fff;}',
    '.oda-inp{border:1.5px solid #e5e7eb;border-radius:8px;background:#f9fafb;color:#1a1a1a;padding:9px 12px;font-size:.9rem;font-family:Arial,sans-serif;outline:none;width:100%;box-sizing:border-box;transition:border-color .15s;}',
    '.oda-inp:focus{border-color:#CC3A20;}',
    '.oda-hf-box{border:1.5px solid #e5e7eb;border-radius:8px;background:#f9fafb;height:42px;padding:0 12px;transition:border-color .15s;box-sizing:border-box;}',
    '.oda-hf-box.oda-focused{border-color:#CC3A20;}',
    '.oda-btn-red{background:#CC3A20;color:#fff;font-weight:900;font-family:"Arial Black",Arial,sans-serif;border-radius:8px;padding:12px;border:none;cursor:pointer;font-size:.9rem;width:100%;transition:background .15s;letter-spacing:.02em;}',
    '.oda-btn-red:hover:not(:disabled){background:#a82e18;}',
    '.oda-btn-red:disabled{background:#9ca3af;cursor:not-allowed;}',
    '#oda-flash{display:none;border-radius:8px;padding:10px 14px;font-size:.84rem;font-weight:600;margin-bottom:1rem;font-family:Arial,sans-serif;}',
    '#oda-flash.oda-err{background:#fef2f2;border:1px solid #fca5a5;color:#b91c1c;}',
    '#oda-flash.oda-info{background:#eff6ff;border:1px solid #93c5fd;color:#1d4ed8;}',
    '#oda-panel-loading{text-align:center;padding:2.5rem 0;}',
    '.oda-big-spin{display:inline-block;width:34px;height:34px;border:4px solid #e5e7eb;border-top-color:#CC3A20;border-radius:50%;animation:odaSpin .7s linear infinite;margin-bottom:.65rem;}',
    '.oda-spin{display:inline-block;width:15px;height:15px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:odaSpin .5s linear infinite;vertical-align:middle;margin-right:5px;}',
    '@keyframes odaSpin{to{transform:rotate(360deg)}}',
    '#oda-panel-success{display:none;text-align:center;padding:1.5rem 0 .5rem;}',
    '#oda-panel-form{display:none;}',
    '.oda-field-lbl{display:block;font-family:Arial,sans-serif;font-weight:700;font-size:.67rem;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;margin-bottom:.4rem;}',
  ].join('');

  /* ── HTML template ─────────────────────────────────────────────────────── */
  var TMPL = '<div id="oda-overlay">'
    + '<div id="oda-card" role="dialog" aria-modal="true" aria-label="Donate to On-Deck Alliance">'
    +   '<button id="oda-close-btn" type="button" aria-label="Close donation form">\u2715</button>'

    /* Header */
    +   '<div style="margin-bottom:1.2rem;padding-right:2rem;">'
    +     '<div style="font:700 .6rem/1 Arial,sans-serif;text-transform:uppercase;letter-spacing:.14em;color:#CC3A20;margin-bottom:5px;">On-Deck Alliance</div>'
    +     '<h2 style="font:900 1.5rem/1.1 \'Arial Black\',Arial,sans-serif;color:#1a1a1a;margin:0 0 .3rem;">Support the Alliance</h2>'
    +     '<p style="font:400 .86rem/1.5 Arial,sans-serif;color:#6b7280;margin:0;">Every dollar keeps skate culture alive in Toledo.</p>'
    +   '</div>'

    /* Flash message area */
    +   '<div id="oda-flash"></div>'

    /* Loading panel */
    +   '<div id="oda-panel-loading">'
    +     '<div class="oda-big-spin"></div>'
    +     '<p style="font:400 .86rem/1 Arial,sans-serif;color:#9ca3af;margin:.2rem 0 0;">Loading payment options\u2026</p>'
    +   '</div>'

    /* Success panel */
    +   '<div id="oda-panel-success">'
    +     '<div style="font-size:2.75rem;margin-bottom:.6rem;">\uD83E\uDD18</div>'
    +     '<h3 style="font:900 1.3rem/1.1 \'Arial Black\',Arial,sans-serif;color:#1DB855;margin:0 0 .45rem;">Thank you!</h3>'
    +     '<p id="oda-success-msg" style="font:400 .9rem/1.55 Arial,sans-serif;color:#374151;margin:0 0 1.4rem;"></p>'
    +     '<button type="button" class="oda-btn-red" onclick="window.closeDonateModal()" style="max-width:200px;margin:0 auto;display:block;">Close</button>'
    +   '</div>'

    /* Main form panel */
    +   '<div id="oda-panel-form">'

    /* Amount pills */
    +     '<div style="margin-bottom:1.1rem;">'
    +       '<label class="oda-field-lbl">Amount</label>'
    +       '<div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.6rem;">'
    +         '<button type="button" class="oda-pill" data-amount="10">$10</button>'
    +         '<button type="button" class="oda-pill oda-active" data-amount="25">$25</button>'
    +         '<button type="button" class="oda-pill" data-amount="50">$50</button>'
    +         '<button type="button" class="oda-pill" data-amount="100">$100</button>'
    +       '</div>'
    +       '<div style="display:flex;align-items:center;gap:.35rem;">'
    +         '<span style="font:700 .88rem/1 Arial,sans-serif;color:#9ca3af;">$</span>'
    +         '<input id="oda-custom-amt" type="number" min="1" max="10000" step="1" class="oda-inp" placeholder="Custom amount" style="max-width:175px;">'
    +       '</div>'
    +     '</div>'

    /* Optional message */
    +     '<div style="margin-bottom:1.2rem;">'
    +       '<label for="oda-msg-field" class="oda-field-lbl">Message <span style="font-weight:400;text-transform:none;">(optional)</span></label>'
    +       '<textarea id="oda-msg-field" rows="2" maxlength="500" class="oda-inp" placeholder="Leave a note for the ODA team\u2026" style="resize:vertical;min-height:56px;"></textarea>'
    +     '</div>'

    /* PayPal Smart Buttons */
    +     '<div id="oda-pp-buttons" style="margin-bottom:.55rem;min-height:46px;"></div>'

    /* Card section (shown only when Hosted Fields eligible) */
    +     '<div id="oda-card-sep" style="display:none;text-align:center;font:400 .78rem/1 Arial,sans-serif;color:#d1d5db;margin:.2rem 0 .7rem;">&mdash; or pay by card &mdash;</div>'
    +     '<div id="oda-hf-form-wrap" style="display:none;">'
    +       '<form id="oda-hf-form" autocomplete="off">'
    +         '<div style="margin-bottom:.6rem;">'
    +           '<label class="oda-field-lbl">Cardholder Name</label>'
    +           '<input id="oda-card-name" type="text" class="oda-inp" placeholder="Name on card">'
    +         '</div>'
    +         '<div style="margin-bottom:.6rem;">'
    +           '<label class="oda-field-lbl">Card Number</label>'
    +           '<div id="oda-hf-number" class="oda-hf-box"></div>'
    +         '</div>'
    +         '<div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:1rem;">'
    +           '<div><label class="oda-field-lbl">Expiry</label><div id="oda-hf-expiry" class="oda-hf-box"></div></div>'
    +           '<div><label class="oda-field-lbl">CVV</label><div id="oda-hf-cvv" class="oda-hf-box"></div></div>'
    +         '</div>'
    +         '<button type="submit" class="oda-btn-red" id="oda-card-btn"><span id="oda-card-btn-text">Donate by Card</span></button>'
    +       '</form>'
    +     '</div>'

    +     '<p style="text-align:center;font:400 .7rem/1.4 Arial,sans-serif;color:#d1d5db;margin-top:.85rem;">Secured by PayPal. Card details go directly to PayPal\'s encrypted servers.</p>'
    +   '</div>'   /* /oda-panel-form */

    + '</div>'     /* /oda-card */
    + '</div>';    /* /oda-overlay */

  /* ── State ─────────────────────────────────────────────────────────────── */
  var _sdkLoading  = false;
  var _ppReady     = false;
  var _csrfToken   = null;
  var _selectedAmt = 25;

  /* ── DOM helper ─────────────────────────────────────────────────────────── */
  function el(id) { return document.getElementById(id); }

  /* ── Inject modal + CSS into page (idempotent) ──────────────────────────── */
  function _inject() {
    if (el('oda-overlay')) return;

    var style = document.createElement('style');
    style.textContent = CSS;
    document.head.appendChild(style);

    var tmp = document.createElement('div');
    tmp.innerHTML = TMPL;
    document.body.appendChild(tmp.firstElementChild);

    _bindEvents();
  }

  function _bindEvents() {
    el('oda-close-btn').addEventListener('click', window.closeDonateModal);

    el('oda-overlay').addEventListener('click', function (e) {
      if (e.target === this) window.closeDonateModal();
    });

    /* Amount pills */
    var pills = el('oda-overlay').querySelectorAll('.oda-pill');
    for (var i = 0; i < pills.length; i++) {
      (function (btn) {
        btn.addEventListener('click', function () {
          var all = el('oda-overlay').querySelectorAll('.oda-pill');
          for (var j = 0; j < all.length; j++) all[j].classList.remove('oda-active');
          btn.classList.add('oda-active');
          _selectedAmt = parseFloat(btn.dataset.amount);
          el('oda-custom-amt').value = '';
          _clearFlash();
        });
      }(pills[i]));
    }

    el('oda-custom-amt').addEventListener('input', function () {
      var all = el('oda-overlay').querySelectorAll('.oda-pill');
      for (var j = 0; j < all.length; j++) all[j].classList.remove('oda-active');
      _selectedAmt = parseFloat(this.value) || 0;
      _clearFlash();
    });
  }

  /* ── Flash messages ─────────────────────────────────────────────────────── */
  function _flash(text, type) {
    var f = el('oda-flash');
    f.textContent = text;
    f.className   = type === 'info' ? 'oda-info' : 'oda-err';
    f.style.display = 'block';
  }

  function _clearFlash() {
    var f = el('oda-flash');
    if (f) { f.style.display = 'none'; f.textContent = ''; }
  }

  /* ── Panel control ──────────────────────────────────────────────────────── */
  var _panels = ['oda-panel-loading', 'oda-panel-form', 'oda-panel-success'];

  function _showPanel(name) {
    for (var i = 0; i < _panels.length; i++) {
      var p = el(_panels[i]);
      if (p) p.style.display = (_panels[i] === 'oda-panel-' + name) ? 'block' : 'none';
    }
  }

  /* ── Amount helpers ─────────────────────────────────────────────────────── */
  function _getAmt() {
    var c = parseFloat(el('oda-custom-amt').value);
    return c > 0 ? c : _selectedAmt;
  }

  function _getMsgText() {
    return (el('oda-msg-field').value || '').trim().slice(0, 500);
  }

  function _validateAmt() {
    var a = _getAmt();
    if (!a || a < 1)  { _flash('Please enter a donation amount of at least $1.00.'); return false; }
    if (a > 10000)    { _flash('Maximum single donation is $10,000.'); return false; }
    return true;
  }

  /* ── Reset form for re-open ─────────────────────────────────────────────── */
  function _resetForm() {
    _clearFlash();
    _selectedAmt = 25;
    var pills = (el('oda-overlay') || document).querySelectorAll('.oda-pill');
    for (var i = 0; i < pills.length; i++) {
      pills[i].classList.toggle('oda-active', pills[i].dataset.amount === '25');
    }
    el('oda-custom-amt').value = '';
    el('oda-msg-field').value  = '';
    _showPanel('form');
  }

  /* ── Network helpers ────────────────────────────────────────────────────── */
  function _fetchConfig() {
    return fetch(TOKEN_URL, { credentials: 'same-origin' })
      .then(function (r) {
        if (!r.ok) throw new Error('Could not load payment config.');
        return r.json();
      });
  }

  function _postJSON(body) {
    body.csrf_token = _csrfToken;
    return fetch(PROCESS_URL, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body:    JSON.stringify(body),
    }).then(function (r) {
      return r.json().then(function (json) {
        if (!r.ok || json.error) throw new Error(json.error || 'Request failed');
        return json;
      });
    });
  }

  function _loadSdk(clientId, currency) {
    return new Promise(function (resolve, reject) {
      if (window.paypal) { resolve(); return; }
      var url = 'https://www.paypal.com/sdk/js'
              + '?client-id='  + encodeURIComponent(clientId)
              + '&currency='   + encodeURIComponent(currency)
              + '&intent=capture&components=buttons,hosted-fields';
      var s      = document.createElement('script');
      s.src      = url;
      s.onload   = resolve;
      s.onerror  = function () { reject(new Error('Failed to load payment SDK.')); };
      document.head.appendChild(s);
    });
  }

  /* ── PayPal createOrder / captureOrder (shared by both payment paths) ───── */
  function _createOrder() {
    if (!_validateAmt()) return Promise.reject(new Error('invalid'));
    return _postJSON({ action: 'create_order', amount: _getAmt() })
      .then(function (d) { return d.order_id; });
  }

  function _captureOrder(orderId) {
    return _postJSON({ action: 'capture_order', order_id: orderId, message: _getMsgText() })
      .then(function (d) {
        if (d.success) {
          var who = d.payer_name ? ' from ' + d.payer_name : '';
          el('oda-success-msg').textContent =
            'Your donation of $' + parseFloat(d.amount).toFixed(2) + who + ' has been received. We appreciate your support!';
          _showPanel('success');
        }
      });
  }

  /* ── Render PayPal once (called after SDK loads) ────────────────────────── */
  function _renderPayPal() {
    var pp = window.paypal;

    /* Show the form now — containers must be visible before PayPal renders into them */
    _showPanel('form');

    /* Smart Buttons (PayPal wallet + Venmo) */
    pp.Buttons({
      style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'donate' },
      createOrder: function () {
        return _createOrder().catch(function (err) {
          if (err.message !== 'invalid') _flash(err.message || 'Could not start payment.');
          throw err;
        });
      },
      onApprove: function (data) {
        _clearFlash();
        return _captureOrder(data.orderID).catch(function (err) {
          _flash(err.message || 'Capture failed. Please contact us if your card was charged.');
        });
      },
      onError: function (err) {
        console.error('[PayPal SDK]', err);
        _flash('PayPal encountered an error. Please try again or use a card below.');
      },
      onCancel: function () { _clearFlash(); },
    }).render('#oda-pp-buttons');

    /* Hosted Fields — on-page card entry (requires Advanced Card on PayPal account) */
    if (pp.HostedFields && pp.HostedFields.isEligible()) {
      el('oda-card-sep').style.display     = 'block';
      el('oda-hf-form-wrap').style.display = 'block';

      pp.HostedFields.render({
        createOrder: function () {
          return _createOrder().catch(function (err) {
            if (err.message !== 'invalid') _flash(err.message || 'Could not start payment.');
            throw err;
          });
        },
        fields: {
          number:         { selector: '#oda-hf-number', placeholder: '4111 1111 1111 1111' },
          cvv:            { selector: '#oda-hf-cvv',    placeholder: '\u2022\u2022\u2022' },
          expirationDate: { selector: '#oda-hf-expiry', placeholder: 'MM/YY' },
        },
        styles: {
          'input':    { color: '#1a1a1a', 'font-size': '0.9rem', 'font-family': 'Arial,sans-serif' },
          ':focus':   { color: '#1a1a1a' },
          '.invalid': { color: '#dc2626' },
        },
      }).then(function (hf) {
        /* Focus ring on hosted field wrappers */
        var hfMap = { number: 'oda-hf-number', cvv: 'oda-hf-cvv', expirationDate: 'oda-hf-expiry' };
        hf.on('focus', function (ev) {
          var w = el(hfMap[ev.emittedBy]);
          if (w) w.classList.add('oda-focused');
        });
        hf.on('blur', function () {
          ['oda-hf-number', 'oda-hf-cvv', 'oda-hf-expiry'].forEach(function (id) {
            var w = el(id); if (w) w.classList.remove('oda-focused');
          });
        });

        /* Card form submit */
        el('oda-hf-form').addEventListener('submit', function (e) {
          e.preventDefault();
          _clearFlash();
          if (!_validateAmt()) return;
          var btn  = el('oda-card-btn');
          var txt  = el('oda-card-btn-text');
          btn.disabled  = true;
          txt.innerHTML = '<span class="oda-spin"></span>Processing\u2026';
          hf.submit({ cardholderName: el('oda-card-name').value.trim() })
            .then(function (payload) { return _captureOrder(payload.orderId); })
            .catch(function (err)    { _flash(err.message || 'Card payment failed. Please check your details.'); })
            .finally(function ()     { btn.disabled = false; txt.textContent = 'Donate by Card'; });
        });
      }).catch(function (err) {
        console.warn('[HostedFields]', err);
        el('oda-card-sep').style.display     = 'none';
        el('oda-hf-form-wrap').style.display = 'none';
      });
    }

    _ppReady = true;
  }

  /* ── Initialize: fetch token, load SDK, render ──────────────────────────── */
  function _init() {
    if (_sdkLoading || _ppReady) return;
    _sdkLoading = true;
    _showPanel('loading');

    _fetchConfig()
      .then(function (cfg) {
        _csrfToken = cfg.csrf_token;
        return _loadSdk(cfg.paypal_client_id, cfg.currency);
      })
      .then(function () { _renderPayPal(); })
      .catch(function (err) {
        _flash(err.message || 'Could not load payment options. Please refresh and try again.');
        el('oda-panel-loading').style.display = 'none';
      })
      .finally(function () { _sdkLoading = false; });
  }

  /* ── Public API ─────────────────────────────────────────────────────────── */
  window.openDonateModal = function () {
    _inject();
    if (_ppReady) _resetForm();
    el('oda-overlay').classList.add('oda-open');
    document.body.style.overflow = 'hidden';
    if (!_ppReady && !_sdkLoading) _init();
  };

  window.closeDonateModal = function () {
    var o = el('oda-overlay');
    if (o) o.classList.remove('oda-open');
    document.body.style.overflow = '';
  };

  /* ── Keyboard: Escape closes modal ─────────────────────────────────────── */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') window.closeDonateModal();
  });

  /* ── Auto-inject on DOM ready ───────────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', _inject);
  } else {
    _inject();
  }

}());
