<?php
// tools/skyddad/readme.php - v5
// git commit: Förbättra tabellutseende och breddanpassning på desktop

$title = 'Om Skyddad';
$metaDescription = 'Lär dig hur Skyddad fungerar, vilka säkerhetsnivåer som används, och hur du skyddar dina hemliga texter via engångslänkar.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <?php include '../../includes/back-link.php'; ?>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p><strong>Skyddad</strong> är ett verktyg för att skapa och dela hemliga texter via <strong>engångslänkar</strong>. Texten är krypterad, länken är omöjlig att gissa, och informationen tas bort automatiskt efter visning.</p>

    <h2>Hur det fungerar</h2>
    <ol>
      <li>Du skriver in en text du vill dela.</li>
      <li>Texten krypteras lokalt med <strong>AES-256-CBC</strong> och sparas i databasen.</li>
      <li>En länk med unik ID och signatur (HMAC) skapas.</li>
      <li>Du delar länken till mottagaren.</li>
      <li>När någon öppnar länken:
        <ul>
          <li>Servern validerar signaturen.</li>
          <li>Om giltig: dekrypterar och visar texten.</li>
          <li>Innehållet raderas från databasen direkt.</li>
        </ul>
      </li>
    </ol>

    <h2>Säkerhetsfunktioner</h2>
    <ul>
      <li>🔐 <strong>CSRF-skydd</strong> för att förhindra externa POST-attacker.</li>
      <li>🔑 <strong>HMAC-signatur</strong> för länken förhindrar manipulation.</li>
      <li>🔏 <strong>AES-256-kryptering</strong> gör att text aldrig lagras i klartext.</li>
      <li>🗑 <strong>Självförstörelse</strong> sker efter första visning.</li>
      <li>⌛ <strong>24h gräns</strong> för alla hemligheter.</li>
    </ul>

    <h2>Förkortningar</h2>
    <div class="table-wrapper">
      <table class="table">
        <thead><tr><th>Förkortning</th><th>Beskrivning</th></tr></thead>
        <tbody>
          <tr><td>CSRF</td><td>Cross-Site Request Forgery – skydd mot otillåtna formuläranrop</td></tr>
          <tr><td>HMAC</td><td>Hash-based Message Authentication Code – skyddar länkar från att manipuleras</td></tr>
          <tr><td>AES-256-CBC</td><td>Advanced Encryption Standard med 256-bitars nyckel och CBC-läge – stark kryptering</td></tr>
          <tr><td>IP</td><td>Internet Protocol – används för att visa varifrån en händelse kommer</td></tr>
          <tr><td>Cron</td><td>Automatiskt serverjobb som körs med jämna mellanrum</td></tr>
        </tbody>
      </table>
    </div>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Text: "Min API-nyckel till staging: xyz123"
Genererad länk: https://mackan.eu/tools/skyddad/visa.php?id=...

⇨ När någon klickar på länken visas texten exakt en gång, sedan tas allt bort.
    </pre>

    <h2>Status</h2>
    <div class="table-wrapper">
      <table class="table">
        <thead><tr><th>Del</th><th>Vad det innebär</th><th>Status</th></tr></thead>
        <tbody>
          <tr><td>🔐 CSRF-skydd</td><td>Skyddar formulär mot extern manipulation</td><td>✔</td></tr>
          <tr><td>🔑 HMAC-token</td><td>Unik signatur i länken skyddar mot gissning</td><td>✔</td></tr>
          <tr><td>🔏 AES-256</td><td>Kryptering innan lagring</td><td>✔</td></tr>
          <tr><td>🗑 Självförstöring</td><td>Text tas bort efter visning</td><td>✔</td></tr>
          <tr><td>⌛ 24h giltighet</td><td>Automatisk utgång efter ett dygn</td><td>✔</td></tr>
          <tr><td>📁 Kodstruktur</td><td>Separata mappar för logik och mallar</td><td>✔</td></tr>
          <tr><td>🧪 Felvisning</td><td>PHP-errors visas i dev-läge</td><td>✔</td></tr>
          <tr><td>📊 Adminpanel</td><td>Visar antal skapade och visade texter</td><td>✔</td></tr>
          <tr><td>📈 Statistikdiagram</td><td>Stapeldiagram via ECharts</td><td>✔</td></tr>
        </tbody>
      </table>
    </div>

    <h2>Integritet</h2>
    <p>Skyddad skyddar din hemlighet. Ingen loggning, ingen spårning, ingen insyn. Vi sparar ingen metadata eller klartext. Händelser i systemet är anonyma och visas endast i form av statistik eller totalsiffror.</p>

    <h2>Förslag på framtida utveckling</h2>
    <ul>
      <li>⏳ <strong>Livstid (TTL)</strong>: Användare kan välja giltighetstid</li>
      <li>📂 <strong>Export som .txt</strong>: Möjlighet att ladda ned texten lokalt</li>
      <li>📏 <strong>QR-kod</strong>: Generera QR för enklare delning</li>
      <li>🔁 <strong>Delningshistoria</strong>: Lista över egna skapade länkar (lokalt)</li>
      <li>🔒 <strong>PIN-skydd</strong>: Skydda länken med valfri kod</li>
      <li>🌐 <strong>Språkstöd</strong>: Fler språkversioner av gränssnittet</li>
      <li>📈 <strong>Avancerad adminpanel</strong>: Filter, export, fler vyer</li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
