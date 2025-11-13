<?php
// includes/readme-icon.php - standardiserad readme-länk
if (!isset($readmePath)) {
    $readmePath = basename($_SERVER['PHP_SELF'], '.php') . '/readme.php';
}
?>
<a href="<?= htmlspecialchars($readmePath, ENT_QUOTES, 'UTF-8'); ?>" class="info-link" data-tippy-content="Om verktyget">
  <span class="info-link__icon" aria-hidden="true">ℹ️</span>
  <span class="info-link__text">Om verktyget</span>
</a>
