<?php
// skapa.php - v4
// git commit: Uppdatera includes-sökvägar

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/csrf.php';
if (!function_exists('validate_csrf_token')) {
    function validate_csrf_token($token) {
        // Simple CSRF token validation example, replace with your actual logic
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
require_once __DIR__ . '/includes/db.php';

// Ladda valideringsfunktioner
require_once __DIR__ . '/../../includes/tools-validator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        die('❌ Ogiltig CSRF-token');
    }

    // Validera password
    $password = validateString($_POST['password'] ?? '', ['min' => 1, 'max' => 10000, 'default' => '', 'trim' => true]);
    if (empty($password)) {
        die('❌ Ingen text angiven.');
    }
    
    $id = bin2hex(random_bytes(16));
    $encrypted = openssl_encrypt($password, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
    $expires = time() + 86400;

    $stmt = $db->prepare("INSERT INTO passwords (id, encrypted_data, views_left, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $encrypted, 1, $expires]);

    $token = hash_hmac('sha256', $id, TOKEN_SECRET);
    $url = "https://mackan.eu/tools/skyddad/visa.php?id=$id&token=$token";

    echo "<div class=\"kort\"><p><strong>✅ Skapad länk:</strong><br><a href=\"$url\">$url</a></p></div>";
}
