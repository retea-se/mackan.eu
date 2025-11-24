<?php
// dela-handler.php - v7
// git commit: Ta bort dubbel inkludering av bootstrap.php (redan inkluderad i dela.php)

// bootstrap.php är redan inkluderad i dela.php, så vi behöver inte inkludera den igen
// Alla funktioner och variabler ($pdo, ENCRYPTION_KEY, TOKEN_SECRET, verifyCsrfToken) är redan tillgängliga

$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf_token)) {
        $result = '<div class="error">❌ Ogiltig CSRF-token. Försök igen.</div>';
        // Fortsätt att rendera sidan så att användaren ser felet och kan försöka igen
    } else {
        // Ladda valideringsfunktioner
        require_once __DIR__ . '/../../includes/tools-validator.php';

        // Validera secret
        $secret = validateString($_POST['secret'] ?? '', ['min' => 1, 'max' => 10000, 'default' => '', 'trim' => true]);
        if (empty($secret)) {
            $result = '<div class="error">❌ Ingen text angiven.</div>';
        } else {
            // Validera PIN om det anges
            $pin = validateString($_POST['pin'] ?? '', ['min' => 0, 'max' => 255, 'default' => '', 'trim' => true]);

            $iv_length = openssl_cipher_iv_length('AES-256-CBC');
            $iv = random_bytes($iv_length);
            $encrypted = openssl_encrypt($secret, 'AES-256-CBC', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
            // Spara IV + krypterad data ihop, base64-kodat
            $encrypted_data = base64_encode($iv . $encrypted);

            $id = bin2hex(random_bytes(16));
            $token = hash_hmac('sha256', $id, TOKEN_SECRET);
            $expires = time() + 86400;

            // PIN är redan validerat ovan
            $pin_hash = $pin !== '' ? password_hash($pin, PASSWORD_DEFAULT) : null;

            $stmt = $pdo->prepare("INSERT INTO passwords (id, encrypted_data, expires_at, views_left, pin_hash) VALUES (?, ?, ?, 1, ?)");
            $stmt->execute([$id, $encrypted_data, $expires, $pin_hash]);

            // Logga skapandet
            $logStmt = $pdo->prepare("INSERT INTO log_events (event_type, secret_id, ip) VALUES (?, ?, ?)");
            $logStmt->execute(['created', $id, $_SERVER['REMOTE_ADDR']]);

            $url = "https://$_SERVER[HTTP_HOST]/tools/skyddad/visa.php?id=$id&token=$token";

            // Skapa kortlänk via API
            $shortlink = $url; // Fallback till lång länk
            $apiUrl = 'https://'.$_SERVER['HTTP_HOST'].'/tools/kortlank/api/shorten.php';
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => $url]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // För lokal utveckling
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Debug: logga om kortlänk misslyckas (ta bort i produktion)
            if ($response !== false && $httpCode === 200) {
                $data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($data['shortlink']) && !empty($data['shortlink'])) {
                    $shortlink = $data['shortlink'];
                }
            }

            // Använd kortlänken för QR-kod om den finns, annars den långa länken
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($shortlink);

            $isShortLink = ($shortlink !== $url);
            $linkText = htmlspecialchars($shortlink, ENT_QUOTES, 'UTF-8');
            $result = <<<HTML
<div class="kort kort--huvud">
  <div class="kort__rubrik">✅ Skapad delningslänk</div>
  <div class="kort__rad" style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
    <span id="shortLink" style="flex:1;min-width:200px;word-break:break-all;"><a href="$shortlink" target="_blank">$linkText</a></span>
    <button type="button" class="knapp__ikon" id="copyBtn" onclick="copyShortLink()" aria-label="Kopiera länk" title="Kopiera länk" style="flex-shrink:0;">
      <i class="fa-solid fa-copy"></i>
    </button>
  </div>
  <div class="kort__innehall center" style="margin-top:1rem;">
    <img src="$qrUrl" alt="QR-kod för länken" width="120" height="120" loading="lazy" decoding="async" style="border-radius:8px;border:1px solid #eee;">
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
  const linkEl = document.querySelector('#shortLink a');
  const link = linkEl ? linkEl.href : document.querySelector('#shortLink').textContent.trim();
  const btn = document.getElementById('copyBtn');

  if (!btn) return;

  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(link).then(() => {
      // Visa feedback med visuell ändring
      const originalContent = btn.innerHTML;
      const originalTitle = btn.getAttribute('aria-label') || 'Kopiera länk';
      btn.innerHTML = '<i class="fa-solid fa-check"></i>';
      btn.style.color = '#28a745';
      btn.setAttribute('aria-label', 'Kopierat!');

      setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.style.color = '';
        btn.setAttribute('aria-label', originalTitle);
      }, 2000);
    }).catch(err => {
      console.error('Kunde inte kopiera:', err);
      // Fallback: visa länken i en prompt
      prompt('Kopiera länken:', link);
    });
  } else {
    // Fallback för äldre webbläsare
    const textarea = document.createElement('textarea');
    textarea.value = link;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    try {
      document.execCommand('copy');
      const originalContent = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i>';
      btn.style.color = '#28a745';
      setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.style.color = '';
      }, 2000);
    } catch (err) {
      prompt('Kopiera länken:', link);
    }
    document.body.removeChild(textarea);
  }
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
    }
}
