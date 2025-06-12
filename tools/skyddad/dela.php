<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$title = 'Skyddad';
$metaDescription = 'Skapa en engångslänk till en krypterad hemlighet som förstörs efter visning.';
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
