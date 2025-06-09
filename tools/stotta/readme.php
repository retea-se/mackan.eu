<?php
// tools/pnr/readme.php - v1
$title = 'Dokumentation – PnrVerifier™';
$subtitle = 'Verifiera och analysera svenska personnummer';
include '../../includes/layout-start.php';
?>

<main class="container">

  <?php if (!empty($subtitle)): ?>
    <p class="subtitle"><?= $subtitle ?></p>
  <?php endif; ?>

  <article class="card">
    <h2>Syfte</h2>
    <p>
      PnrVerifier™ är ett verktyg för att kontrollera och analysera svenska personnummer. Det validerar strukturen enligt Luhn-algoritmen, beräknar ålder, kön, stjärntecken, visar eventuella fel, och låter dig exportera resultatet.
    </p>
  </article>

  <article class="card">
    <h2>Funktioner</h2>
    <ul>
      <li>Stöd för både 10- och 12-siffriga personnummer (med eller utan bindestreck)</li>
      <li>Validering enligt Luhn-algoritmen</li>
      <li>Kontroll av ogiltiga datum (dag/månad)</li>
      <li>Identifiering av framtida födelsedatum</li>
      <li>Åldersberäkning</li>
      <li>Könsbestämning</li>
      <li>Stjärntecken och antal dagar till födelsedag</li>
      <li>Visuell varning för minderåriga och pensionärer</li>
      <li>Summering av statistik (män/kvinnor, snittålder)</li>
      <li>Export till Excel (xlsx)</li>
    </ul>
  </article>

  <article class="card">
    <h2>Användning</h2>
    <ol>
      <li>Klistra in ett eller flera personnummer i textfältet (en per rad)</li>
      <li>Klicka på <strong>Bearbeta</strong></li>
      <li>Resultattabellen visas nedanför tillsammans med en sammanställning</li>
      <li>Om du vill, klicka på <strong>Exportera</strong> för att ladda ned en Excel-fil</li>
    </ol>
  </article>

  <article class="card">
    <h2>Teknik</h2>
    <ul>
      <li>JavaScript-funktioner för kontroll av datum, kön, kontrollsiffra och stjärntecken</li>
      <li><code>XLSX.js</code> och <code>FileSaver.js</code> används för exportfunktion</li>
      <li>Temaväxling har tagits bort för enkelhet och kompabilitet</li>
      <li>Alla fel visas med symboler och detaljerade kommentarer</li>
    </ul>
  </article>

  <article class="card">
    <h2>Exempel</h2>
    <p>Inmatning:</p>
    <pre class="terminal-output">
19900301-1234
20051231-5678
19800229-4321
    </pre>
    <p>Utdata: Validering, kön, ålder, ev. fel (t.ex. ogiltig dag) och fler detaljer.</p>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
