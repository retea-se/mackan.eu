<?php
$title = 'Om Lösenordsgeneratorn';
$metaDescription = 'Skapa säkra och starka lösenord snabbt med full kontroll på längd och teckentyper. Lär dig hur det fungerar och vad som gör ett lösenord säkert.';
include '../../includes/layout-start.php';
?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <?php include '../../includes/back-link.php'; ?>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p>Detta verktyg är skapat för att snabbt generera starka lösenord direkt i webbläsaren, utan att data skickas någonstans. Du har full kontroll över vilka teckentyper som ska användas och hur långt lösenordet ska vara. Perfekt för utvecklare, administratörer och alla som vill öka sin säkerhet.</p>

    <h2>Så fungerar det</h2>
    <ul>
      <li>Ställ in önskad <strong>lösenordslängd</strong> (mellan 4 och 128 tecken)</li>
      <li>Välj vilka <strong>teckentyper</strong> som ska användas:
        <ul>
          <li>Små bokstäver (a–z)</li>
          <li>Stora bokstäver (A–Z)</li>
          <li>Siffror (0–9)</li>
          <li>Specialtecken (!@#$%^...)</li>
        </ul>
      </li>
      <li>Välj hur många lösenord du vill generera</li>
      <li>Klicka på <strong>Generera</strong></li>
    </ul>

    <h2>Förhandslösenord</h2>
    <p>Ovanför inställningarna visas alltid ett färskt <strong>förhandslösenord</strong> baserat på nuvarande inställningar. Du kan kopiera det direkt eller generera om det med ett klick.</p>

    <h2>Export</h2>
    <p>När lösenord har genererats kan du exportera dem som <strong>JSON</strong>, <strong>CSV</strong> eller <strong>TXT</strong>. Exportknappen visas automatiskt efter första genereringen.</p>

<h2>Styrkeindikator</h2>
<p>Varje lösenord bedöms med en <strong>styrketagg</strong> som syns till höger om lösenordet i tabellen. Bedömningen baseras på två faktorer:</p>
<ol>
  <li><strong>Längd:</strong> Ju längre lösenord, desto bättre skydd mot brute-force-angrepp.</li>
  <li><strong>Variation:</strong> Kombination av små/stora bokstäver, siffror och symboler gör det exponentiellt svårare att gissa.</li>
</ol>

<p>Styrkan visas med färgade etiketter:</p>

<ul>
  <li><span class="tag-svag">Svag</span>
    – kortare än 10 tecken <em>eller</em> innehåller bara 1–2 typer av tecken.
    <br><em>Exempel:</em> <code>abcd1234</code>, <code>Password</code>
  </li>
  <li><span class="tag-medel">Bra</span>
    – minst 10 tecken och minst 3 teckentyper.
    <br><em>Exempel:</em> <code>Abc123!xyz</code>
  </li>
  <li><span class="tag-stark">Stark</span>
    – minst 14 tecken och innehåller <u>alla</u> fyra teckentyper.
    <br><em>Exempel:</em> <code>9fL!vGd@T6$WbZp1rU2x</code>
  </li>
</ul>

<p>Lösenordsstyrkan påverkas <em>inte</em> av ordlistor, men du bör ändå undvika verkliga ord och personliga data.</p>

    </ul>
    <p>Obs! Inget system är perfekt – undvik riktiga ord eller återanvändning av lösenord.</p>

    <h2>Tips för bättre lösenord</h2>
    <ul>
      <li>Använd minst 12 tecken – gärna fler</li>
      <li>Inkludera alla fyra teckentyper</li>
      <li>Skapa olika lösenord för olika tjänster</li>
      <li>Använd en lösenordshanterare för att spara säkert</li>
    </ul>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Längd: 24
Tecken: Små + Stora + Siffror + Symboler

Resultat:
Lösenord                 | Styrka
-------------------------|---------
KD4@n>acqZ0UYxtW7^!igfH[ | Stark
    </pre>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
