    </main>
    <?php
    if (!isset($skipFooter) || !$skipFooter) {
      include __DIR__ . '/footer.php';
    }
    include __DIR__ . '/visitor-logger-js.php';
    ?>
  </div> <!-- ✅ Stänger layout-wrappern här -->

<script src="/includes/tools-common.js"></script>
<script src="/js/info-check.js" defer></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Vänta på att Tippy.js ska laddas med robust retry-mekanism
    function initTippy(attempts = 0) {
      if (typeof tippy !== 'undefined') {
        tippy('[data-tippy-content]');
        console.log('✅ Tippy initialized successfully');
      } else if (attempts < 50) { // Försök i 5 sekunder (50 x 100ms)
        setTimeout(() => initTippy(attempts + 1), 100);
      } else {
        console.warn('⚠️ Tippy failed to load after 50 attempts');
      }
    }
    initTippy();
  });

  function copyLink() {
    const el = document.getElementById('secretLink');
    if (!el) return;

    const text = el.innerText;
    navigator.clipboard.writeText(text).then(() => {
      const btn = document.querySelector('.knapp.knapp--liten');
      if (btn) {
        btn.innerText = 'Kopierad!';
        setTimeout(() => btn.innerText = 'Kopiera', 2000);
      }
    });
  }
</script>

</body>
</html>
