<?php
// dela-handler.php - v6
// git commit: Spara valfri PIN hashad i passwords-tabellen

require_once __DIR__ . '/includes/bootstrap.php';

$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf_token)) {
        $result = '<div class="error">❌ Ogiltig CSRF-token. Försök igen.</div>';
        return;
    }

    $secret = trim($_POST['secret'] ?? '');
    if (empty($secret)) {
        $result = '<div class="error">❌ Ingen text angiven.</div>';
        return;
    }

    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = random_bytes($iv_length);
    $encrypted = openssl_encrypt($secret, 'AES-256-CBC', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
    // Spara IV + krypterad data ihop, base64-kodat
    $encrypted_data = base64_encode($iv . $encrypted);

    $id = bin2hex(random_bytes(16));
    $token = hash_hmac('sha256', $id, TOKEN_SECRET);
    $expires = time() + 86400;

    $pin = trim($_POST['pin'] ?? '');
    $pin_hash = $pin !== '' ? password_hash($pin, PASSWORD_DEFAULT) : null;

    $stmt = $pdo->prepare("INSERT INTO passwords (id, encrypted_data, expires_at, views_left, pin_hash) VALUES (?, ?, ?, 1, ?)");
    $stmt->execute([$id, $encrypted_data, $expires, $pin_hash]);

    // Logga skapandet
    $logStmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
    $logStmt->execute(['created', $id, $_SERVER['REMOTE_ADDR']]);

    $url = "https://$_SERVER[HTTP_HOST]/tools/skyddad/visa.php?id=$id&token=$token";
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);

    $result = <<<HTML
<div class="success">
  ✅ Länk skapad! <br>
  <pre id="secretLink">$url</pre>
  <div class="knapp__grupp" style="margin-top:1rem;">
    <button onclick="copyLink()" class="knapp knapp__ikon" data-tippy-content="Kopiera länken" aria-label="Kopiera länken">
      <i class="fa-solid fa-copy"></i>
    </button>
    <button class="knapp knapp__ikon" data-tippy-content="<img src='$qrUrl' alt='QR-kod' width='120'>" data-tippy-allowHTML="true" aria-label="Visa QR-kod">
      <i class="fa-solid fa-qrcode"></i>
    </button>
  </div>
</div>
HTML;
}
