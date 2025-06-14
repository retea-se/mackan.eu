<?php
// visa-handler.php - v6
// git commit: Byt till bootstrap och $pdo istället för config/db och $db

require_once __DIR__ . '/includes/bootstrap.php';

$result = '';

$id = $_GET['id'] ?? null;
$token = $_GET['token'] ?? null;

if (!$id || !$token) {
    $result = '❌ Ogiltig begäran.';
    return;
}

$expected_token = hash_hmac('sha256', $id, TOKEN_SECRET);
if (!hash_equals($expected_token, $token)) {
    $result = '❌ Felaktig eller manipulerad länk.';
    return;
}

$stmt = $pdo->prepare("SELECT encrypted_data FROM passwords WHERE id = ? AND views_left > 0 AND expires_at > ?");
$stmt->execute([$id, time()]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    $result = '❌ Hemligheten finns inte eller har redan visats.';
    return;
}

$decrypted = openssl_decrypt($row['encrypted_data'], 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);

// Ta bort efter visning
$stmt = $pdo->prepare("DELETE FROM passwords WHERE id = ?");
$stmt->execute([$id]);

// Logga visningen
$logStmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
$logStmt->execute(['viewed', $id, $_SERVER['REMOTE_ADDR']]);

if (!$decrypted) {
    $result = '❌ Något gick fel vid dekryptering.';
    return;
}

$result  = "<h2>Skyddad delning:</h2>";
$result .= "<pre style=\"white-space: pre-wrap; word-break: break-word;\">";
$result .= htmlspecialchars($decrypted, ENT_QUOTES, 'UTF-8');
$result .= "</pre>";
$result .= '<div class="mt-1"><a href="dela.php" class="button tiny" data-tippy-content="Skapa en ny hemlighet">Skapa ny</a></div>';
