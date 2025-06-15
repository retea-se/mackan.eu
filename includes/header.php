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
        <?php
    // Visa readme-ikon om dokumentation finns
    $verktygMapp = basename(dirname($_SERVER['PHP_SELF']));
    $readmeSökväg = __DIR__ . '/../tools/' . $verktygMapp . '/readme.php';
    if (file_exists($readmeSökväg)) {
      include __DIR__ . '/readme-icon.php';
    }
    ?>
    <button id="themeToggle" class="knapp__ikon" aria-label="Byt tema">
      <i class="fa-solid fa-moon"></i>
    </button>
  </div>
</header>
<script src="/js/theme-toggle.js"></script>
<?php
// ********** SLUT Header **********
?>
