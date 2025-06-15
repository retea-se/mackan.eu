<?php
// dela-handler.php - v6
// git commit: Spara valfri PIN hashad i passwords-tabellen

require_once __DIR__ . '/includes/bootstrap.php';

$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf_token)) {
        $result = '<div class="error">❌ Ogiltig CSRF-token. Försök igen.</div>';
        return;
    }

    $secret = trim($_POST['secret'] ?? '');
    if (empty($secret)) {
        $result = '<div class="error">❌ Ingen text angiven.</div>';
        return;
    }

    $iv_length = openssl_cipher_iv_length('AES-256-CBC');
    $iv = random_bytes($iv_length);
    $encrypted = openssl_encrypt($secret, 'AES-256-CBC', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
    // Spara IV + krypterad data ihop, base64-kodat
    $encrypted_data = base64_encode($iv . $encrypted);

    $id = bin2hex(random_bytes(16));
    $token = hash_hmac('sha256', $id, TOKEN_SECRET);
    $expires = time() + 86400;

    $pin = trim($_POST['pin'] ?? '');
    $pin_hash = $pin !== '' ? password_hash($pin, PASSWORD_DEFAULT) : null;

    $stmt = $pdo->prepare("INSERT INTO passwords (id, encrypted_data, expires_at, views_left, pin_hash) VALUES (?, ?, ?, 1, ?)");
    $stmt->execute([$id, $encrypted_data, $expires, $pin_hash]);

    // Logga skapandet
    $logStmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
    $logStmt->execute(['created', $id, $_SERVER['REMOTE_ADDR']]);

    $url = "https://$_SERVER[HTTP_HOST]/tools/skyddad/visa.php?id=$id&token=$token";

    // Skapa kortlänk via API
    $ch = curl_init('https://'.$_SERVER['HTTP_HOST'].'/tools/kortlank/api/shorten.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => $url]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $shortlink = $data['shortlink'] ?? $url; // fallback till lång länk om fel

    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);

    $result = <<<HTML
<div class="kort kort--huvud">
  <div class="kort__rubrik">✅ Skapad kortlänk</div>
  <div class="kort__rad" style="display:flex;align-items:center;gap:0.5rem;">
    <span id="shortLink"><a href="$shortlink">$shortlink</a></span>
    <button type="button" class="knapp__ikon" onclick="copyShortLink()" aria-label="Kopiera kortlänk">
      <i class="fa-solid fa-copy"></i>
    </button>
  </div>
  <div class="kort__innehall center" style="margin-top:1rem;">
    <img src="$qrUrl" alt="QR-kod för länken" width="120" height="120" style="border-radius:8px;border:1px solid #eee;">
  </div>
  <div class="kort__rad" style="margin-top:0.5rem;">
    <button type="button" class="knapp knapp--liten" onclick="toggleLongLink()" id="visaLangBtn">
      Visa ursprunglig länk
    </button>
  </div>
  <div class="kort__innehall hidden" id="longlink" style="margin-top:0.5rem; word-break: break-all;">
    <small>$url</small>
  </div>
</div>
<script>
function copyShortLink() {
  const link = document.querySelector('#shortLink a').href;
  navigator.clipboard.writeText(link);
}
function toggleLongLink() {
  const el = document.getElementById('longlink');
  const btn = document.getElementById('visaLangBtn');
  const isHidden = el.classList.contains('hidden');
  el.classList.toggle('hidden');
  btn.textContent = isHidden ? 'Dölj ursprunglig länk' : 'Visa ursprunglig länk';
}
</script>
HTML;
}
