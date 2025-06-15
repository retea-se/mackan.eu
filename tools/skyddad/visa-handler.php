<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// visa-handler.php - v7
// git commit: En ruta, knapp med klass, kopiera-knapp med tippy

require_once __DIR__ . '/includes/bootstrap.php';

$id = $_GET['id'] ?? null;
$token = $_GET['token'] ?? null;

if (!$id || !$token) {
    echo '❌ Ogiltig begäran.';
    return;
}

$expected_token = hash_hmac('sha256', $id, TOKEN_SECRET);
if (!hash_equals($expected_token, $token)) {
    echo '❌ Felaktig eller manipulerad länk.';
    return;
}

// Hämta posten från databasen
$stmt = $pdo->prepare("SELECT encrypted_data, pin_hash FROM passwords WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row) {
    // Visa endast denna ruta om inget resultat finns
    echo '<div class="kort kort--smal" style="margin:2rem auto;">';
    echo '<div class="kort__innehall" style="color:#c00;display:flex;align-items:center;gap:0.5em;"><i class="fa-solid fa-xmark"></i> Inget resultat tillgängligt.</div>';
    echo '<div class="knapp__grupp" style="margin-top:1.5rem;">';
    echo '<a href="dela.php" class="knapp knapp--liten" data-tippy-content="Skapa en ny hemlighet"><i class="fa-solid fa-plus"></i> Skapa ny</a>';
    echo '</div>';
    echo '</div>';
    exit;
}

// PIN-kontroll
if ($row['pin_hash']) {
    if (!isset($_POST['pin'])) {
        echo '<form method="POST" class="kort kort--smal" style="margin:2rem auto;">';
        echo '<div class="kort__rubrik">PIN-skydd</div>';
        echo '<div class="kort__innehall">';
        echo '<label for="pin">Ange PIN-kod:</label>';
        echo '<input type="password" name="pin" id="pin" class="falt__input" required style="margin-bottom:1rem;">';
        echo '<button type="submit" class="knapp">Visa</button>';
        echo '</div>';
        echo '</form>';
        exit;
    }
    if (!password_verify($_POST['pin'], $row['pin_hash'])) {
        echo '<form method="POST" class="kort kort--smal" style="margin:2rem auto;">';
        echo '<div class="kort__rubrik">PIN-skydd</div>';
        echo '<div class="kort__innehall">';
        echo '<span style="color:#c00;"><i class="fa-solid fa-xmark"></i> Fel PIN-kod.</span><br>';
        echo '<label for="pin">Ange PIN-kod:</label>';
        echo '<input type="password" name="pin" id="pin" class="falt__input" required style="margin-bottom:1rem;">';
        echo '<button type="submit" class="knapp">Visa</button>';
        echo '</div>';
        echo '</form>';
        exit;
    }
}

// Dekrypteringsfunktion
function decryptSecret($encrypted_data, $key) {
    $data = base64_decode($encrypted_data);
    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($data, 0, $iv_length);
    $ciphertext = substr($data, $iv_length);
    return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

// Dekryptera hemligheten
$decrypted = decryptSecret($row['encrypted_data'], ENCRYPTION_KEY);

if ($decrypted === false) {
    echo '<div class="kort kort--smal" style="margin:2rem auto;">';
    echo '<div class="kort__innehall" style="color:#c00;"><i class="fa-solid fa-xmark"></i> ❌ Något gick fel vid dekryptering.</div>';
    echo '</div>';
    exit;
}

// Ta bort efter visning
$stmt = $pdo->prepare("DELETE FROM passwords WHERE id = ?");
$stmt->execute([$id]);

// Logga visningen
$logStmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
$logStmt->execute(['viewed', $id, $_SERVER['REMOTE_ADDR']]);

// QR för meddelandetexten
$qrMsgUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($decrypted);

?>
<div class="kort kort--smal" style="margin:2rem auto;">
  <div class="kort__rubrik">Meddelande</div>
  <div class="kort__innehall" id="secretText" style="background:var(--card-bg-hover);border-radius:var(--border-radius);padding:1.2rem 1rem;margin-bottom:1.5rem;word-break:break-word;">
    <?= htmlspecialchars($decrypted) ?>
  </div>
  <div class="knapp__grupp" style="justify-content:center;margin-bottom:1.5rem;">
    <button class="knapp__ikon" onclick="copySecret()" data-tippy-content="Kopiera meddelandet" aria-label="Kopiera meddelandet">
      <i class="fa-solid fa-copy"></i>
    </button>
    <button class="knapp__ikon" data-tippy-content="<img src='<?= $qrMsgUrl ?>' alt='QR-kod' width='120'>" data-tippy-allowHTML="true" aria-label="Visa QR-kod">
      <i class="fa-solid fa-qrcode"></i>
    </button>
  </div>
  <div class="knapp__grupp" style="justify-content:center;">
    <a href="dela.php" class="knapp knapp--liten" data-tippy-content="Skapa en ny hemlighet">
      <i class="fa-solid fa-plus"></i> Skapa ny
    </a>
  </div>
</div>
<script>
function copySecret() {
  const el = document.getElementById('secretText');
  if (!el) return;
  navigator.clipboard.writeText(el.innerText).then(() => {
    const btn = document.querySelector('.knapp__ikon');
    if (btn && btn._tippy) {
      btn._tippy.setContent('Kopierat!');
      btn._tippy.show();
      setTimeout(() => {
        btn._tippy.setContent('Kopiera meddelandet');
        btn._tippy.hide();
      }, 1200);
    } else {
      alert('Kopierat!');
    }
  });
}
</script>
