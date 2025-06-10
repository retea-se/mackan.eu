<?php
// includes/csrf.php - v1
// git commit: Lägg till CSRF-tokenhantering

session_start();

// Genererar en CSRF-token och sparar i session
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validerar att inkommande token matchar den i session
function validate_csrf_token($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
?>
