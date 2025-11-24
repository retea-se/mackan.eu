<!-- mallar/dela-form.php - v3 -->
<?php
// csrf.php är redan inkluderad via bootstrap.php i dela.php
// Funktionen generateCsrfToken() är redan tillgänglig

$csrf_token = generateCsrfToken();
?>

<form method="POST" action="dela.php">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

  <label for="secret">Skyddad text:</label><br>
  <textarea id="secret" name="secret" rows="6" cols="50" required class="falt__input"></textarea><br><br>

  <label for="pin">PIN-kod (valfri):</label><br>
  <input type="text" id="pin" name="pin" maxlength="8" pattern="\d*" class="falt__input" autocomplete="off" placeholder="Lämna tomt för inget PIN"><br><br>

  <button type="submit" class="knapp" data-tippy-content="Skapa en unik engångslänk till texten ovan">
    Skapa delningslänk
  </button>
</form>
