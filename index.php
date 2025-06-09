<?php
// index.php - v4
ini_set('display_errors', 1);
error_reporting(E_ALL);
$title = 'Verktyg';
$metaDescription = 'Utforska kostnadsfria onlineverktyg för utvecklare och tekniker. Generera, konvertera och analysera data snabbt och enkelt.';
?>
<?php include 'includes/layout-start.php'; ?>

<?php
$tools = include __DIR__ . '/config/tools.php';

// Sortera verktyg alfabetiskt efter 'title'
usort($tools, function ($a, $b) {
  return strcasecmp($a['title'], $b['title']);
});
?>

<main class="container">
  <p class="lead mb-2">
    Här hittar du användbara (nördiga) onlineverktyg för konvertering, datagenerering och testning. Alla verktyg körs lokalt i din webbläsare – snabbt, säkert och gratis.
  </p>
  <section class="menu-grid">
    <?php foreach ($tools as $tool): ?>
      <a href="<?= htmlspecialchars($tool['href']) ?>" class="menu-card">
        <i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i>
        <span><?= htmlspecialchars($tool['title']) ?></span>
      </a>
    <?php endforeach; ?>
  </section>
</main>

<?php include 'includes/layout-end.php'; ?>
