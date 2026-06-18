  </main>
</div><!-- end main wrapper -->

<script>
  // Mobile nav toggle
  const toggle  = document.getElementById('mob-toggle');
  const nav     = document.getElementById('mob-nav');
  const overlay = document.getElementById('mob-overlay');
  const close   = document.getElementById('mob-close');
  if (toggle) toggle.addEventListener('click', () => nav.classList.remove('hidden'));
  if (overlay) overlay.addEventListener('click', () => nav.classList.add('hidden'));
  if (close) close.addEventListener('click', () => nav.classList.add('hidden'));

  // Auto-dismiss flash messages
  const flash = document.querySelector('[data-flash]');
  if (flash) setTimeout(() => flash.remove(), 5000);
</script>
</body>
</html>
