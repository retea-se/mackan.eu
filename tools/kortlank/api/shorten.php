<?php
// Kortlänk API – Skapa kortlänk
header('Content-Type: application/json');

// Ladda in databasanslutning
require_once __DIR__ . '/../includes/db.php';

// Ta emot data (POST: url, custom_alias, description, password)
$url          = trim($_POST['url'] ?? '');
$custom_alias = trim($_POST['custom_alias'] ?? '');
$description  = trim($_POST['description'] ?? '');
$password     = trim($_POST['password'] ?? '');

// Validera URL
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ogiltig URL']);
    exit;
}

// Om custom_alias anges, kontrollera att det är ledigt och giltigt
if ($custom_alias !== '') {
    if (!preg_match('/^[a-zA-Z0-9_-]{3,32}$/', $custom_alias)) {
        http_response_code(400);
        echo json_encode(['error' => 'Alias får bara innehålla bokstäver, siffror, - och _ (3-32 tecken)']);
        exit;
    }
    $stmt = $pdo->prepare("SELECT 1 FROM shortlinks WHERE custom_alias = ?");
    $stmt->execute([$custom_alias]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Aliaset är redan upptaget']);
        exit;
    }
}

// Skapa unikt id om inget alias anges
$id = $custom_alias !== '' ? $custom_alias : substr(bin2hex(random_bytes(8)), 0, 8);

// Hasha lösenord om det anges
$password_hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;

// Spara i databasen
$stmt = $pdo->prepare(
    "INSERT INTO shortlinks (id, target_url, created_at, custom_alias, description, password_hash, is_active)
     VALUES (?, ?, NOW(), ?, ?, ?, 1)"
);
$stmt->execute([$id, $url, $custom_alias ?: null, $description ?: null, $password_hash]);

// Bygg kortlänk
$base = 'https://'.$_SERVER['HTTP_HOST'].'/m/';
$short = $base . urlencode($id);

echo json_encode([
    'shortlink' => $short,
    'id'        => $id,
    'custom_alias' => $custom_alias ?: null,
    'description'  => $description ?: null
]);
