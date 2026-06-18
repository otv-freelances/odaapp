<?php
/**
 * admin/donations.php — View and manage all received donations.
 */
require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/auth.php';
require_auth();
require_setup();

$donations = get_all_donations();
// Newest first
usort($donations, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

$total = donations_total();
$count = count($donations);

// Copy emails: collect unique, non-empty donor emails
$donorEmails = array_unique(array_filter(array_column($donations, 'payer_email')));

$pageTitle = 'Donations';
include __DIR__ . '/partials/header.php';
?>

<div style="max-width:900px;">

  <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <h1 class="text-2xl font-black text-gray-900">Donations</h1>
    <div class="flex gap-2">
      <?php if (!empty($donorEmails)): ?>
      <button id="copy-emails-btn"
              onclick="copyEmails([<?= implode(',', array_map(fn($e) => json_encode($e), $donorEmails)) ?>])"
              class="flex items-center gap-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        Copy Donor Emails
      </button>
      <?php endif; ?>
      <a href="/donate.php" target="_blank"
         class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg transition"
         style="background:#CC3A20; color:#fff;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        View Donation Page
      </a>
    </div>
  </div>

  <?php if (PAYPAL_SANDBOX): ?>
  <div class="mb-5 rounded-lg px-4 py-2.5 text-sm font-medium bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-2">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <strong>Sandbox / Test Mode</strong> — These are test donations. Set <code>PAYPAL_SANDBOX</code> to <code>false</code> in config.php to go live.
  </div>
  <?php endif; ?>

  <!-- ── Stats ─────────────────────────────────────────────────────────── -->
  <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
      <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Total Raised</p>
      <p class="text-2xl font-black" style="color:#1DB855;">$<?= number_format($total, 2) ?></p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
      <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Donations</p>
      <p class="text-2xl font-black text-gray-900"><?= $count ?></p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
      <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Unique Donors</p>
      <p class="text-2xl font-black text-gray-900"><?= count($donorEmails) ?></p>
    </div>
  </div>

  <!-- ── Donations table ───────────────────────────────────────────────── -->
  <?php if (empty($donations)): ?>
  <div class="bg-white rounded-xl p-10 text-center shadow-sm border border-gray-100">
    <p class="text-gray-400 text-lg font-medium">No donations yet.</p>
    <p class="text-gray-400 text-sm mt-1">Share the <a href="/donate.php" target="_blank" style="color:#CC3A20;">donation page</a> to start receiving contributions.</p>
  </div>
  <?php else: ?>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
          <th class="px-4 py-3 text-left">Date</th>
          <th class="px-4 py-3 text-left">Donor</th>
          <th class="px-4 py-3 text-left">Email</th>
          <th class="px-4 py-3 text-right">Amount</th>
          <th class="px-4 py-3 text-left">Message</th>
          <th class="px-4 py-3 text-left">PayPal ID</th>
          <th class="px-4 py-3 text-center">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($donations as $don): ?>
        <tr class="hover:bg-gray-50 transition">
          <td class="px-4 py-3 whitespace-nowrap text-gray-600">
            <?= e(date('M j, Y', strtotime($don['created_at'] ?? 'now'))) ?>
          </td>
          <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
            <?= e($don['payer_name'] ?? '—') ?>
          </td>
          <td class="px-4 py-3 text-gray-600">
            <?php if (!empty($don['payer_email'])): ?>
            <a href="mailto:<?= e($don['payer_email']) ?>"
               style="color:#1E62B5;" class="hover:underline">
              <?= e($don['payer_email']) ?>
            </a>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td class="px-4 py-3 text-right font-bold whitespace-nowrap" style="color:#1DB855;">
            $<?= e(number_format((float)($don['amount'] ?? 0), 2)) ?>
            <span class="text-xs text-gray-400 font-normal"><?= e($don['currency'] ?? 'USD') ?></span>
          </td>
          <td class="px-4 py-3 text-gray-600 max-w-xs">
            <?php if (!empty($don['message'])): ?>
            <span class="italic">&ldquo;<?= e(mb_substr($don['message'], 0, 80)) ?><?= strlen($don['message']) > 80 ? '…' : '' ?>&rdquo;</span>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td class="px-4 py-3">
            <code class="text-xs text-gray-400 bg-gray-100 rounded px-1.5 py-0.5"><?= e(mb_substr($don['id'] ?? '', 0, 14)) ?>…</code>
          </td>
          <td class="px-4 py-3 text-center">
            <form method="POST" action="/admin/delete.php"
                  onsubmit="return confirm('Remove this donation record? This does NOT issue a PayPal refund.');">
              <?= csrf_field() ?>
              <input type="hidden" name="type" value="donation">
              <input type="hidden" name="id"   value="<?= e($don['id'] ?? '') ?>">
              <button type="submit"
                      class="text-xs font-medium text-red-500 hover:text-red-700 hover:underline transition">
                Remove
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot class="bg-gray-50 border-t border-gray-100">
        <tr>
          <td colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-500 text-right">Total:</td>
          <td class="px-4 py-3 text-right font-black text-base" style="color:#1DB855;">$<?= number_format($total, 2) ?></td>
          <td colspan="3"></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <?php endif; ?>

  <p class="text-xs text-gray-400 mt-4">
    Removing a record only deletes it from this log — it does <strong>not</strong> issue a PayPal refund.
    To refund a donor, log in to your <a href="https://www.paypal.com/activities/" target="_blank" rel="noopener noreferrer" style="color:#1E62B5;">PayPal dashboard</a>.
  </p>

</div><!-- /max-width -->

<script>
function copyEmails(emails) {
  const text = emails.join(', ');
  navigator.clipboard.writeText(text).then(function () {
    const btn = document.getElementById('copy-emails-btn');
    const orig = btn.innerHTML;
    btn.textContent = '✓ Copied ' + emails.length + ' email' + (emails.length !== 1 ? 's' : '');
    btn.disabled = true;
    setTimeout(function () { btn.innerHTML = orig; btn.disabled = false; }, 2500);
  }).catch(function () {
    prompt('Copy donor emails:', text);
  });
}
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
