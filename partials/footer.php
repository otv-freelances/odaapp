<?php
// Shared site footer.
?>
<footer style="background:#1a1a1a;margin-top:1.5rem;">
  <div class="max-w-7xl mx-auto px-6 py-12">

    <div class="flex flex-col md:flex-row md:items-start gap-10 pb-10" style="border-bottom:1px solid rgba(255,255,255,0.1);">

      <div class="flex flex-col gap-4 flex-shrink-0" style="max-width:260px;">
        <img src="/images/ODA_logo.png" alt="ODA Logo" style="width:64px;height:auto;opacity:0.9;filter:invert(1);">
        <p style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.5);line-height:1.6;margin:0;">
          Promoting self sufficiency and beneficial change in historically underserved communities through skateboarding.
        </p>
        <p style="font-family:Arial,sans-serif;font-size:0.72rem;color:rgba(255,255,255,0.35);line-height:1.6;margin:0;">
          Toledo, Ohio &middot; Tax ID: <span style="color:rgba(255,255,255,0.55);">TODO</span>
        </p>
      </div>

      <div class="flex flex-wrap gap-10 flex-1">
        <div class="flex flex-col gap-3">
          <div style="font-family:Arial,sans-serif;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.14em;color:#CC3A20;margin-bottom:4px;">Organization</div>
          <a href="/index.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Home</a>
          <a href="/about.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">About ODA</a>
          <a href="/programs.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Programs</a>
          <a href="/for-schools.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">For Schools</a>
          <a href="/contact.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Contact</a>
        </div>

        <div class="flex flex-col gap-3">
          <div style="font-family:Arial,sans-serif;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.14em;color:#CC3A20;margin-bottom:4px;">Get Involved</div>
          <a href="/volunteer.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Volunteer</a>
          <a href="/events.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Events</a>
          <a href="/blog.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Blog</a>
        </div>

        <div class="flex flex-col gap-3">
          <div style="font-family:Arial,sans-serif;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.14em;color:#CC3A20;margin-bottom:4px;">Important</div>
          <a href="/donate.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Donate</a>
          <a href="/partner.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Partners &amp; Sponsors</a>
          <a href="/partner.php#donate-skateboards" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Donate Skateboards</a>
          <a href="/youth-opportunities-toledo-ohio.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Youth Opportunities</a>
          <a href="/programs-toledo-oh.php" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Toledo Programs</a>
        </div>

        <div class="flex flex-col gap-3">
          <div style="font-family:Arial,sans-serif;font-weight:700;font-size:0.6rem;text-transform:uppercase;letter-spacing:0.14em;color:#CC3A20;margin-bottom:4px;">Follow Us</div>
          <a href="https://instagram.com" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Instagram</a>
          <a href="https://facebook.com" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">Facebook</a>
          <a href="https://youtube.com" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">YouTube</a>
          <a href="https://tiktok.com" style="font-family:Arial,sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.6);text-decoration:none;">TikTok</a>
        </div>
      </div>

    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-7">
      <p style="font-family:Arial,sans-serif;font-size:0.72rem;color:rgba(255,255,255,0.3);margin:0;">&copy; <?= date('Y') ?> ODA &mdash; On Deck Alliance. All rights reserved.</p>
      <button type="button" onclick="openDonateModal()" style="display:inline-block;background:#CC3A20;color:#fff;font-family:'Arial Black',Arial,sans-serif;font-weight:900;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;padding:10px 22px;border-radius:6px;border:none;cursor:pointer;white-space:nowrap;">Donate Now</button>
    </div>

  </div>
</footer>
