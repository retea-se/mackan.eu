<?php
// includes/readme-icon.php - v2
$readmePath = dirname($_SERVER['SCRIPT_FILENAME']) . '/readme.php';
if (file_exists($readmePath)) {
  echo '<a href="readme.php" class="info-link-inline" data-tippy-content="Om verktyget" aria-label="Läs mer om verktyget">';
  echo '<i class="fa-solid fa-circle-info"></i>';
  echo '</a>';
}
?>
