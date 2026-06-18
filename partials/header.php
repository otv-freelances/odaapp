  <a class="skip-link" href="#main-content">Skip to content</a>
  <!-- Navigation Bar -->
  <header id="site-header" class="nav-shell" style="box-shadow:0 1px 4px rgba(0,0,0,0.10);">
    <!-- Announcement bar (reference: green strip) -->
    <div class="nav-announcement" aria-label="Announcements">
      <div class="nav-announcement__inner">
        <span class="nav-announcement__text">Now accepting new applicants to the “I CAN” program.</span>
      </div>
    </div>

    <!-- Desktop nav (center logo, split links) -->
    <div class="hidden md:block nav-main">
      <div class="nav-main__inner">
        <nav class="nav-links nav-links--left" aria-label="Primary">
          <a class="nav-link" href="/index.php">Home</a>
          <a class="nav-link" href="/about.php">About</a>
          <a class="nav-link" href="/program.php">Program</a>
        </nav>

        <a class="nav-logo" href="/index.php" aria-label="On-Deck Alliance home">
          <span class="nav-logo__ring" aria-hidden="true"></span>
          <img src="/images/ODA_logo.png" alt="ODA Wheel" class="nav-logo__img">
        </a>

        <div class="nav-right">
          <nav class="nav-links nav-links--right" aria-label="Site">
            <a class="nav-link" href="/contact.php">Contact</a>
            <a class="nav-link" href="/donate.php">Donate</a>
          </nav>
          <div class="nav-cta">
            <a class="nav-btn nav-btn-primary" href="/program.php">Join I CAN</a>
            <a class="nav-btn nav-btn-green" href="/volunteer.php">Volunteer</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Mobile nav -->
    <div class="flex md:hidden px-2 py-2 items-center justify-between bg-white mobile-nav-bar">
      <a href="/index.php">
        <div style="border-radius:50%;width:72px;height:72px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
          <img src="/images/ODA_logo.png" alt="ODA Wheel" class="max-w-none" style="width:72px;height:72px;object-fit:cover;">
        </div>
      </a>
      <button id="mobile-menu-btn" style="background:none;border:none;padding:6px;cursor:pointer;display:flex;flex-direction:column;gap:5px;" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu">
        <span class="hamburger-bar" style="display:block;width:26px;height:2px;background:#1a1a1a;border-radius:2px;transition:transform 0.25s,opacity 0.25s;"></span>
        <span class="hamburger-bar" style="display:block;width:26px;height:2px;background:#1a1a1a;border-radius:2px;transition:transform 0.25s,opacity 0.25s;"></span>
        <span class="hamburger-bar" style="display:block;width:26px;height:2px;background:#1a1a1a;border-radius:2px;transition:transform 0.25s,opacity 0.25s;"></span>
      </button>
    </div>
        <!-- Mobile dropdown menu -->
    <div id="mobile-menu" style="display:none;background:#fff;border-top:1px solid #e5e7eb;padding:20px 20px;flex-direction:column;gap:18px;" class="md:hidden">
      <a class="nav-link" data-close-mobile-menu href="/index.php">Home</a>
      <a class="nav-link" data-close-mobile-menu href="/about.php">About</a>
      <a class="nav-link" data-close-mobile-menu href="/program.php">Program</a>
      <a class="nav-link" data-close-mobile-menu href="/for-schools.php">For Schools</a>
      <a class="nav-link" data-close-mobile-menu href="/volunteer.php">Volunteer</a>
      <a class="nav-link" data-close-mobile-menu href="/partner.php">Partners</a>
      <a class="nav-link" data-close-mobile-menu href="/blog.php">Events / Blog</a>
      <a class="nav-link" data-close-mobile-menu href="/contact.php">Contact</a>
      <a class="nav-link" data-close-mobile-menu href="/donate.php">Donate</a>
      <a class="nav-btn nav-btn-primary" data-close-mobile-menu href="/program.php">Join I CAN</a>
      <a class="nav-btn nav-btn-ghost" data-close-mobile-menu href="/volunteer.php">Volunteer</a>
    </div>
  </header>
  <a id="main-content" class="sr-only" tabindex="-1"></a>
  <script src="/js/vendor/anime.min.js" defer></script>
  <script src="/js/oda-anime.js" defer></script>
  <script src="/js/header.js" defer></script>
  <script src="/js/site-animations.js" defer></script>
