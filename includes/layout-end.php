<?php
// layout-end.php - v6
include __DIR__ . '/footer.php';
include __DIR__ . '/visitor-logger-js.php';
?>

    </main>
  </div> <!-- ✅ Stänger layout-wrappern här -->

<script src="/js/info-check.js" defer></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    tippy('[data-tippy-content]');
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
