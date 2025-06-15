<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../tools/kortlank/includes/db.php';

$id = $_GET['id'] ?? null;

// Om du använder "din.domän/s/abc123" utan ?id=, använd path-info:
if (!$id && isset($_SERVER['PATH_INFO'])) {
    $id = ltrim($_SERVER['PATH_INFO'], '/');
} elseif (!$id && isset($_SERVER['REQUEST_URI'])) {
    $parts = explode('/', $_SERVER['REQUEST_URI']);
    $id = end($parts);
    $id = preg_replace('/\?.*/', '', $id);
}

if (!$id) {
    http_response_code(404);
    echo "Ingen länk angiven.";
    exit;
}

$stmt = $pdo->prepare("SELECT target_url, is_active, expires_at, password_hash FROM shortlinks WHERE id = ? OR custom_alias = ?");
$stmt->execute([$id, $id]);
$row = $stmt->fetch();

if (!$row || !$row['is_active'] || ($row['expires_at'] && strtotime($row['expires_at']) < time())) {
    http_response_code(404);
    echo "Länken finns inte eller är inaktiv.";
    exit;
}

// (Här kan du lägga till lösenordskontroll om du vill)

$stmt = $pdo->prepare("UPDATE shortlinks SET hits = hits + 1, last_hit = NOW(), last_ip = ?, user_agent = ? WHERE id = ? OR custom_alias = ?");
$stmt->execute([$_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $id, $id]);

header("Location: " . $row['target_url'], true, 302);
exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
