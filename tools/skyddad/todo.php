<?php
// tools/skyddad/todo.php - v2
// git commit: Numrera todo-punkter och bevara prioriterad struktur

$title = 'Att göra – Skyddad';
$metaDescription = 'Prioriterad lista över förbättringar och säkerhetsåtgärder för Skyddad-verktyget.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <?php include '../../includes/back-link.php'; ?>
  </h1>

  <article class="card readme">
    <h2>🟢 Snabbfixar (1–10 min)</h2>
    <ul>
      <li>1. ✅ <strong>Escapa all utdata med htmlspecialchars()</strong> – t.ex. i <code>visa-handler.php</code>.</li>
      <li>2. ✅ <strong>Lägg till <code>session_regenerate_id()</code> vid inloggning</strong>.</li>
      <li>3. ✅ <strong>Visa ett enklare felmeddelande vid exception</strong>, och logga tekniskt fel separat.</li>
      <li>4. 🟢 <strong>Lägg till Open Graph-data och Twitter Cards i <code>layout-start.php</code>.</strong></li>
      <li>5. 🟢 <strong>Ställ in <code>robots.txt</code> och <code>sitemap.xml</code>.</strong></li>
    </ul>

    <h2>🟡 Medelnivå (10–30 min)</h2>
    <ul>
      <li>6. 🟡 <strong>Skapa en <code>helper.php</code></strong> med funktioner för gemensam logik.</li>
      <li>7. 🟡 <strong>Inför <code>Content-Security-Policy</code> header</strong> i <code>layout-start.php</code>.</li>
      <li>8. 🟡 <strong>Lägg till rate limiting med IP-baserad kontroll</strong> i <code>dela-handler.php</code> och <code>visa-handler.php</code>.</li>
      <li>9. 🟡 <strong>Gör adminpanelen responsiv med filter (t.ex. dag/vecka)</strong>.</li>
      <li>10. 🟡 <strong>Lägg till favicons och <code>manifest.json</code></strong>.</li>
    </ul>

    <h2>🔵 Större förbättringar (30+ min)</h2>
    <ul>
      <li>11. 🔵 <strong>Implementera bruteforce-skydd och blockering efter 5 fel</strong>.</li>
      <li>12. 🔵 <strong>Lägg till möjlighet att välja TTL (giltighetstid)</strong> i delningsformuläret.</li>
      <li>13. 🔵 <strong>Skapa testfall och manuell teststrategi</strong>.</li>
      <li>14. 🔵 <strong>Inför valfritt PIN-skydd på visningslänken</strong>.</li>
      <li>15. 🔵 <strong>Gör gränssnittet flerspråkigt med språkväxlare</strong>.</li>
      <li>16. 🔵 <strong>Bygg QR-kodgenerator till visningslänken</strong>.</li>
    </ul>

    <h2>📈 Redan gjort</h2>
    <ul>
      <li>✔ Engångslänkar med AES-256 och HMAC-token</li>
      <li>✔ Adminpanel med händelseloggar och statistik</li>
      <li>✔ Automatisk radering av visade hemligheter</li>
      <li>✔ Integritetstext under delningsformuläret</li>
    </ul>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
