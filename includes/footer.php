<?php
// footer.php - v1
?>
<footer class="footer">
  <div class="footer-left">
    <a href="javascript:history.back()" class="back-link">
      <span class="back-icon">&larr;</span> Tillbaka
    </a>
  </div>
  <div class="footer-center">
    © <span id="currentYear"></span> Mackan.eu
  </div>
</footer>
<script>
  document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>
