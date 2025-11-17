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

<!-- Tippy.js manager with MutationObserver for dynamic content -->
<script src="/js/tippy-manager.js"></script>

<script>
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
