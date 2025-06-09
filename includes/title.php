<?php
// title.php - v4
if (!empty($title) && empty($hideTitle)):
  // Hämta nuvarande filväg
  $currentDir = dirname($_SERVER['SCRIPT_FILENAME']);
  $readmePath = $currentDir . '/readme.php';
  $readmeExists = file_exists($readmePath);
?>
  <section class="container page-title">
    <h1>
      <?= htmlspecialchars($title) ?>
      <?php if ($readmeExists): ?>
        <a href="readme.php" class="info-link-floating" title="Om verktyget">ⓘ</a>
      <?php endif; ?>
    </h1>
  </section>
<?php endif; ?>
