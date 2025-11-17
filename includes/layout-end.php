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
    // Vänta på att Tippy.js ska laddas (laddas med defer)
    function initTippy() {
      if (typeof tippy !== 'undefined') {
        tippy('[data-tippy-content]');
      } else {
        setTimeout(initTippy, 100);
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
