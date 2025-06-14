<?php
// ********** START Header **********
?>
<header class="nav">
  <div class="nav__vänster">
    <a href="/index.php" class="sidfot__ikonlank" aria-label="Startsida">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>

  <div class="nav__titel">
    <?= $title ?? 'Mackan.eu' ?>
  </div>

  <div class="nav__höger">
    <button id="themeToggle" class="knapp__ikon" aria-label="Byt tema">
      <i class="fa-solid fa-moon"></i>
    </button>
  </div>
</header>
<script src="/js/theme-toggle.js"></script>
<?php
// ********** SLUT Header **********
?>
