<!-- ********** START Header ********** -->
<header class="navbar">
  <div class="navbar-left">
    <a href="/index.php" class="footer-home" aria-label="Startsida">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>

  <div class="navbar-center">
    <div class="navbar-title">
      <?= $title ?? 'Mackan.eu' ?>
    </div>
  </div>

  <div class="navbar-right">
    <?php if (file_exists(__DIR__ . '/../tools/' . basename(dirname($_SERVER['PHP_SELF'])) . '/readme.php')): ?>
      <?php include __DIR__ . '/readme-icon.php'; ?>
    <?php endif; ?>
    <button id="themeToggle" class="theme-toggle" aria-label="Byt tema">
      <i class="fa-solid fa-moon"></i>
    </button>
  </div>
</header>
<!-- ********** SLUT Header ********** -->
