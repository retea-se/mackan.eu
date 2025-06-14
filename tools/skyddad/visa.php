<?php
// tools/skyddad/visa.php - v8
$title = 'Skyddad';
$metaDescription = 'Engångslänk för visning av hemlig text som självförstörs efter visning.';
include '../../includes/layout-start.php';
require_once 'visa-handler.php';
?>

<main class="layout__container">

  <div class="kort">
    <?= $result ?? '❌ Inget resultat tillgängligt.' ?>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
