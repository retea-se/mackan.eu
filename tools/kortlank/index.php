<?php
// tools/kortlank/index.php - v1
// Startsida för kortlänk-verktyget

$title = 'Kortlänk';
$metaDescription = 'Förkorta en länk snabbt och enkelt. Klistra in din länk och få en direkt kortadress!';
$keywords = 'kortlänk, URL shortener, förkorta länk, kortadress, länkförkortning, gratis';
$canonical = 'https://mackan.eu/tools/kortlank/';

include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      <?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>
    </p>
  </header>

  <section class="layout__sektion text--center">
    <a href="skapa-lank.php" class="knapp">Skapa ny kortlänk</a>
    <a href="readme.php" class="knapp knapp--sekundar">Om verktyget</a>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>

