<?php
// config.php - v3
// git commit: Lägg till stöd för .env-laddning och flytta känsliga nycklar

$envPath = dirname(__DIR__) . '/.env';
if (!file_exists($envPath)) {
    die('❌ Fel: .env-fil saknas');
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
    [$name, $value] = explode('=', $line, 2);
    putenv(trim($name) . '=' . trim($value));
}

// 🔐 Kryptering (AES-256-CBC)
$rawIv = getenv('ENCRYPTION_IV');
define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY'));
define('ENCRYPTION_IV', substr(hash('sha256', $rawIv), 0, 16));

// 🔑 Token-skydd för visningslänk
define('TOKEN_SECRET', getenv('TOKEN_SECRET'));

// ✅ Debug (tillfälligt – ta bort efter test)
# echo "<!-- ENCRYPTION_KEY: " . ENCRYPTION_KEY . " -->";

