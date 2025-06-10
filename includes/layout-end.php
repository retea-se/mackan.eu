<?php
// layout-end.php - v4
include __DIR__ . '/footer.php';
include __DIR__ . '/visitor-logger-js.php';
?>

<script src="/js/info-check.js" defer></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
  // Initiera tippy.js på alla element med tooltip
  document.addEventListener('DOMContentLoaded', function () {
    tippy('[data-tippy-content]');
  });

  // Kopiera länkfunktion
  function copyLink() {
    const el = document.getElementById('secretLink');
    if (!el) return;

    const text = el.innerText;
    navigator.clipboard.writeText(text).then(() => {
      const btn = document.querySelector('.button.tiny');
      if (btn) {
        btn.innerText = 'Kopierad!';
        setTimeout(() => btn.innerText = 'Kopiera', 2000);
      }
    });
  }
</script>
<script src="/js/theme.js" defer></script>


</body>
</html>
