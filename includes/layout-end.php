    </main>
    <?php
    if (!isset($skipFooter) || !$skipFooter) {
      include __DIR__ . '/footer.php';
    }
    include __DIR__ . '/visitor-logger-js.php';
    ?>
  </div> <!-- ✅ Stänger layout-wrappern här -->

<!-- DOM utilities - Safe DOM operations with null checks -->
<script src="/js/dom-utils.js"></script>

<script src="/includes/tools-common.js"></script>
<script src="/js/info-check.js" defer></script>

<!-- Tippy.js manager with MutationObserver for dynamic content -->
<script src="/js/tippy-manager.js"></script>

<script>
  function copyLink() {
    const el = safeGetById('secretLink');
    if (!el) return;

    const text = el.innerText;
    if (!navigator.clipboard) {
      console.warn('Clipboard API not available');
      return;
    }

    navigator.clipboard.writeText(text).then(() => {
      const btn = safeQuery('.knapp.knapp--liten');
      if (btn) {
        btn.innerText = 'Kopierad!';
        setTimeout(() => btn.innerText = 'Kopiera', 2000);
      }
    }).catch(err => {
      console.warn('Failed to copy to clipboard:', err);
    });
  }
</script>

</body>
</html>
