<?php
// mallar/dela-form.php - v5
// git commit: Korrigera includes-sökväg relativt till mall

if (!function_exists('generate_csrf_token')) {
    require_once __DIR__ . '/../includes/csrf.php';
}
?>

<h2>Dela en hemlighet</h2>

<form method="POST" action="dela.php">
  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

  <label for="secret"></label><br>
  <textarea id="secret" name="secret" rows="6" cols="50" required></textarea><br><br>
<button type="submit" class="button" data-tippy-content="Skapa en unik engångslänk till texten ovan">Skapa delningslänk</button>


</form>
