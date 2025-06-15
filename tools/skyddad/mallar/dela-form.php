<!-- mallar/dela-form.php - v2 -->
<?php
require_once __DIR__ . '/../includes/csrf.php';

$csrf_token = generateCsrfToken();
?>

<form method="POST" action="dela.php">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

  <label for="secret">Hemlig text:</label><br>
  <textarea id="secret" name="secret" rows="6" cols="50" required class="falt__input"></textarea><br><br>

  <button type="submit" class="knapp" data-tippy-content="Skapa en unik engångslänk till texten ovan">
    Skapa delningslänk
  </button>
</form>
