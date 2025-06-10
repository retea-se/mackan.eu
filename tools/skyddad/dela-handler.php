<?php
// dela-handler.php - v6
// git commit: Visa länk snyggt i card med radbrytning och kopieraknapp

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/db.php';

$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $result = '❌ Ogiltig CSRF-token';
        return;
    }

    $text = trim($_POST['secret'] ?? '');
    if ($text === '') {
        $result = '❌ Ingen text angiven.';
        return;
    }

    $id = bin2hex(random_bytes(16));
    $encrypted = openssl_encrypt($text, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
    $expires = time() + 86400;

    $stmt = $db->prepare("INSERT INTO passwords (id, encrypted_data, views_left, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $encrypted, 1, $expires]);

    $token = hash_hmac('sha256', $id, TOKEN_SECRET);
    $url = "https://mackan.eu/tools/skyddad/visa.php?id=$id&token=$token";

$result = <<<HTML
<h2 class="mb-1">Dela!</h2>
<div class="card">
  <div class="preview-password-row">
    <span id="secretLink" style="word-break: break-word; font-family: monospace;">$url</span>
    <div class="preview-actions">
      <button type="button" class="button tiny" data-tippy-content="Kopiera länk" onclick="copyLink()">Kopiera</button>
    </div>
  </div>
</div>
HTML;

}
