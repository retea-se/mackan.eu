<?php
// layout-end.php - v3
include __DIR__ . '/footer.php';
include __DIR__ . '/visitor-logger-js.php';
?>

<script src="/js/info-check.js" defer></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<!-- <script src="/js/visit.js"></script> -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    tippy('[data-tippy-content]');
  });
</script>
<script src="/js/theme-toggle.js" defer></script>

</body>
</html>
