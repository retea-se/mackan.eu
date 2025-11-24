<?php
// includes/csrf.php - v2
// git commit: Förbättrad session-hantering för att undvika header-konflikter

// Starta session om inte redan startad
if (session_status() === PHP_SESSION_NONE) {
    // Konfigurera session cookie för bättre kompatibilitet (måste göras FÖRE session_start)
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax'); // Tillåter POST från samma site
    @session_start(); // @ för att undvika varningar om headers redan skickats
}

// Generera ett nytt CSRF-token och spara i sessionen
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verifiera det inkommande CSRF-tokenet mot det i sessionen
function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
