<?php
// index.php - v5
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

<main class="layout__container">
  <p class="rubrik rubrik--sektion mb-2">
    Här hittar du användbara (nördiga) onlineverktyg för konvertering, datagenerering och testning. Snabbt, säkert och gratis.
  </p>
  <div class="meny">
    <?php foreach ($tools as $tool): ?>
      <a href="<?= htmlspecialchars($tool['href']) ?>" class="meny__kort">
        <?php if (!empty($tool['icon'])): ?>
          <div class="meny__ikon"><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i></div>
        <?php endif; ?>
        <div class="meny__text"><?= htmlspecialchars($tool['title']) ?></div>
        <?php if (!empty($tool['desc'])): ?>
          <div class="meny__beskrivning"><?= htmlspecialchars($tool['desc']) ?></div>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </div>
</main>

<?php include 'includes/layout-end.php'; ?>
