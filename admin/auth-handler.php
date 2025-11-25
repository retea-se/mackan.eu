<?php
// auth-handler.php - Magic link authentication handler
session_start();

// Rate limiting: max 3 requests per 15 minutes per IP
function checkRateLimit($ip) {
    $rateLimitFile = __DIR__ . '/auth-rate-limit.json';
    $rateLimitData = [];

    if (file_exists($rateLimitFile)) {
        $rateLimitData = json_decode(file_get_contents($rateLimitFile), true) ?: [];
    }

    // Clean old entries (older than 15 minutes)
    $now = time();
    foreach ($rateLimitData as $key => $entry) {
        if ($now - $entry['time'] > 900) { // 15 minutes
            unset($rateLimitData[$key]);
        }
    }

    // Check current IP
    $ipKey = md5($ip);
    $count = 0;
    if (isset($rateLimitData[$ipKey])) {
        $count = $rateLimitData[$ipKey]['count'];
        if ($now - $rateLimitData[$ipKey]['time'] > 900) {
            $count = 0;
        }
    }

    if ($count >= 3) {
        return false; // Rate limit exceeded
    }

    // Increment count
    $rateLimitData[$ipKey] = [
        'count' => $count + 1,
        'time' => $now
    ];

    file_put_contents($rateLimitFile, json_encode($rateLimitData));
    return true;
}

// Generate secure token
function generateToken() {
    return bin2hex(random_bytes(32)); // 64 character hex string
}

// Store token in file-based storage (simple approach)
function storeToken($email, $token) {
    $tokenFile = __DIR__ . '/auth-tokens.json';
    $tokens = [];

    if (file_exists($tokenFile)) {
        $tokens = json_decode(file_get_contents($tokenFile), true) ?: [];
    }

    // Clean expired tokens (older than 1 hour)
    $now = time();
    foreach ($tokens as $key => $entry) {
        if ($now - $entry['created'] > 3600) { // 1 hour
            unset($tokens[$key]);
        }
    }

    // Store new token
    $tokens[$token] = [
        'email' => $email,
        'created' => $now,
        'used' => false
    ];

    file_put_contents($tokenFile, json_encode($tokens));
}

// Send magic link email (only for marcus.ornstedt@gmail.com)
function sendMagicLinkEmail($email, $token) {
    $allowedEmail = 'marcus.ornstedt@gmail.com';

    // Only send actual email to allowed address
    if (strtolower(trim($email)) !== strtolower($allowedEmail)) {
        return false; // Don't send, but return success message to user
    }

    $loginUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/admin/index.php?token=' . urlencode($token) . '&email=' . urlencode($email);

    $subject = 'Magic Link - Admin Dashboard';
    $message = "Klicka på länken nedan för att logga in:\n\n";
    $message .= $loginUrl . "\n\n";
    $message .= "Länken är giltig i 1 timme.\n";
    $message .= "Om du inte begärt denna länk kan du ignorera detta meddelande.\n";

    $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers .= "Reply-To: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    return @mail($email, $subject, $message, $headers);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!$email) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ogiltig e-postadress']);
        exit;
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    // Check rate limit
    if (!checkRateLimit($ip)) {
        http_response_code(429);
        echo json_encode(['success' => false, 'message' => 'För många förfrågningar. Försök igen om 15 minuter.']);
        exit;
    }

    // Generate token
    $token = generateToken();

    // Store token
    storeToken($email, $token);

    // Send email (only for allowed email)
    sendMagicLinkEmail($email, $token);

    // Always return success message (even if email wasn't sent)
    echo json_encode([
        'success' => true,
        'message' => 'Om e-postadressen finns i systemet har en inloggningslänk skickats.'
    ]);
    exit;
}

// Handle token validation (GET request)
if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    $tokenFile = __DIR__ . '/auth-tokens.json';
    if (!file_exists($tokenFile)) {
        header('Location: auth.php?error=invalid_token');
        exit;
    }

    $tokens = json_decode(file_get_contents($tokenFile), true) ?: [];

    if (!isset($tokens[$token])) {
        header('Location: auth.php?error=invalid_token');
        exit;
    }

    $tokenData = $tokens[$token];

    // Check if token is expired (1 hour)
    if (time() - $tokenData['created'] > 3600) {
        unset($tokens[$token]);
        file_put_contents($tokenFile, json_encode($tokens));
        header('Location: auth.php?error=expired_token');
        exit;
    }

    // Check if token is already used
    if ($tokenData['used']) {
        header('Location: auth.php?error=token_used');
        exit;
    }

    // Check if email matches
    if (strtolower($tokenData['email']) !== strtolower($email)) {
        header('Location: auth.php?error=email_mismatch');
        exit;
    }

    // Mark token as used
    $tokens[$token]['used'] = true;
    file_put_contents($tokenFile, json_encode($tokens));

    // Create session
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_login_time'] = time();

    // Redirect to dashboard
    header('Location: index.php');
    exit;
}

// Default: redirect to auth page
header('Location: auth.php');
exit;

