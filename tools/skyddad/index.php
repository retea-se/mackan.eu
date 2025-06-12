<?php


// tools/skyddad/index.php - v2
$title = 'Skyddad delning';
$metaDescription = 'Dela hemliga meddelanden via engångslänk som självförstörs efter visning.';
include '../../includes/layout-start.php';
require_once 'dela-handler.php';
?>

<main class="container">


  <div class="card">
    <?php if (!empty($result)) echo $result; ?>
    <?php include 'mallar/dela-form.php'; ?>
  </div>
</main>

<?php include '../../includes/layout-end.php'; ?>
