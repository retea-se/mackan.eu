<?php
// includes/cleanup.php - v2
// git commit: Begränsa loggfil till senaste 50 rader

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$logPath = __DIR__ . '/cronlog.txt';

try {
    $stmt = $db->prepare("DELETE FROM passwords WHERE expires_at < ?");
    $stmt->execute([time()]);

    $message = "[" . date('Y-m-d H:i:s') . "] Städning genomförd\n";
} catch (Exception $e) {
    $message = "[" . date('Y-m-d H:i:s') . "] Fel: " . $e->getMessage() . "\n";
}

// Lägg till i loggfilen
file_put_contents($logPath, $message, FILE_APPEND);

// Begränsa loggfilen till 50 senaste rader
$lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lines = array_slice($lines, -50);
file_put_contents($logPath, implode("\n", $lines) . "\n");
