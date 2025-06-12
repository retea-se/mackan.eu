<?php
// includes/csrf.php - v1
// git commit: Lägg till funktioner för CSRF-skydd: generering och verifiering

// Starta session om inte redan startad
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
