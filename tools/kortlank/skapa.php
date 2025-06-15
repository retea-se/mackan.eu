<?php

// Skapa kortlänk – Mackan.eu
$title = 'Skapa kortlänk';
$metaDescription = 'Skapa en kort och enkel länk till valfri webbadress. Stöd för alias, beskrivning och lösenordsskydd.';
include '../../includes/layout-start.php';

// Hantera formulär
$resultat = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/includes/db.php';
    $url = trim($_POST['url'] ?? '');
    $custom_alias = trim($_POST['custom_alias'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $resultat = '<div class="toast toast--fara">Ogiltig URL</div>';
    } else {
        // Kontrollera alias
        if ($custom_alias !== '') {
            $stmt = $pdo->prepare("SELECT 1 FROM shortlinks WHERE custom_alias = ?");
            $stmt->execute([$custom_alias]);
            if ($stmt->fetch()) {
                $resultat = '<div class="toast toast--fara">Aliaset är redan upptaget.</div>';
            }
        }
        if ($resultat === '') {
            $id = $custom_alias !== '' ? $custom_alias : substr(bin2hex(random_bytes(8)), 0, 8);
            $password_hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
            $stmt = $pdo->prepare(
                "INSERT INTO shortlinks (id, target_url, created_at, custom_alias, description, password_hash, is_active)
                 VALUES (?, ?, NOW(), ?, ?, ?, 1)"
            );
            $stmt->execute([$id, $url, $custom_alias ?: null, $description ?: null, $password_hash]);
            $base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/m/";
            $short = $base . urlencode($id);
            $resultat = '<div class="toast toast--ok">Kortlänk skapad: <a href="' . htmlspecialchars($short) . '" target="_blank" class="knapp knapp--sekundär" data-tippy-content="Öppna kortlänken i ny flik">' . htmlspecialchars($short) . '</a></div>';
        }
    }
}
?>

<main class="layout__container">
  <h1 class="rubrik">
    <?= $title ?>
    <?php include '../../includes/readme-icon.php'; ?>
  </h1>

  <section class="kort kort--smal mt-1">
    <div class="kort__rubrik">Ny kortlänk</div>
    <?php if ($resultat) echo $resultat; ?>
    <form class="form" method="post" autocomplete="off">
      <div class="form__grupp">
        <label for="url" class="fält__etikett">Lång länk (URL):</label>
        <input type="url" name="url" id="url" class="fält__input" required aria-label="Lång länk">
      </div>
      <div class="form__grupp">
        <label for="custom_alias" class="fält__etikett">Eget alias (valfritt):</label>
        <input type="text" name="custom_alias" id="custom_alias" class="fält__input" pattern="[a-zA-Z0-9_-]{3,32}" aria-label="Alias">
      </div>
      <div class="form__grupp">
        <label for="description" class="fält__etikett">Beskrivning (valfritt):</label>
        <input type="text" name="description" id="description" class="fält__input" maxlength="255" aria-label="Beskrivning">
      </div>
      <div class="form__grupp">
        <label for="password" class="fält__etikett">Lösenord (valfritt):</label>
        <input type="password" name="password" id="password" class="fält__input" aria-label="Lösenord">
      </div>
      <div class="form__verktyg">
        <button type="submit" class="knapp" data-tippy-content="Skapa kortlänk">Skapa kortlänk</button>
      </div>
    </form>
  </section>
</main>

<?php include '../../includes/layout-end.php';
