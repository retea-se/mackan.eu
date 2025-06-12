<!-- dela-handler.php - v5 -->
<?php
error_log("📥 POST körs i dela-handler.php", 0);

// git commit: Byt ut $db mot $pdo (fix för undefined variable)

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

    $encrypted = openssl_encrypt($secret, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);

    $id = bin2hex(random_bytes(16));
    $token = hash_hmac('sha256', $id, TOKEN_SECRET);
    $expires = time() + 86400;

    $stmt = $pdo->prepare("INSERT INTO passwords (id, encrypted_data, expires_at, views_left) VALUES (?, ?, ?, 1)");
    $stmt->execute([$id, $encrypted, $expires]);

    // Logga skapandet
    $logStmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
    $logStmt->execute(['created', $id, $_SERVER['REMOTE_ADDR']]);

    $url = "https://$_SERVER[HTTP_HOST]/tools/skyddad/visa.php?id=$id&token=$token";

    $result = <<<HTML
<div class="success">
  ✅ Länk skapad! <br>
  <pre id="secretLink">$url</pre>
  <button onclick="copyLink()" class="button tiny" data-tippy-content="Kopiera länken">Kopiera</button>
</div>
HTML;
}
