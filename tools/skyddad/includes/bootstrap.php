<?php
// includes/bootstrap.php - v8
// git commit: Byt till getenv() för admininloggning (bättre kompatibilitet med .env)

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

// Autentisering för admin
$username = getenv('ADMIN_USER') ?: '';
$password = getenv('ADMIN_PASS') ?: '';

if (
    !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $username ||
    $_SERVER['PHP_AUTH_PW'] !== $password
) {
    header('WWW-Authenticate: Basic realm="Skyddad Adminpanel"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Åtkomst nekad';
    exit;
}

// CLI eller direkt körning av cleanup
if (php_sapi_name() === 'cli' || isset($_GET['run_cleanup'])) {
    require_once __DIR__ . '/cleanup.php';
}
