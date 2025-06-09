<!-- tools/pts/readme.php - v1 -->
<?php
$title = 'Om PTS Diarium – Ärendeanalys';
$metaDescription = 'Sök, filtrera, exportera och analysera ärenden från PTS diarium. Läs om verktygets funktion och teknik här.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <a href="index.php" class="info-link-floating" title="Tillbaka till verktyget">&larr;</a>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p>Detta verktyg används för att interaktivt hämta, filtrera, analysera och exportera diarieförda ärenden från Post- och telestyrelsens öppna API. Fokus ligger på öppenhet, datadriven insyn och stöd för vidare informationsbearbetning.</p>

    <h2>Funktioner</h2>
    <ul>
      <li>Sök och hämta ärenden via datumintervall</li>
      <li>Visa alla fält: ID, diarienummer, status, datum, rubrik</li>
      <li>Expandera rader för att visa tillhörande handlingar (deeds)</li>
      <li>Filtrera rubriker i realtid</li>
      <li>Exportera resultat till JSON och CSV</li>
      <li>Generera ordmoln från rubrikinnehåll</li>
      <li>Popup-vy med ordfrekvenstabell</li>
      <li>Separat söksida för fritextsökning i rubriker (utan datumfilter)</li>
    </ul>

    <h2>Användning</h2>
    <p>Välj ett datumintervall och klicka på <strong>Kör</strong>. Resultaten visas i en responsiv tabell. Du kan filtrera innehållet med sökfältet ovanför tabellen. Klicka på ett ärende för att visa relaterade handlingar. Knappen <strong>Ordmoln</strong> visualiserar de vanligaste orden i rubrikerna, och <strong>Exportera</strong>-knapparna låter dig spara datan lokalt.</p>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Datumintervall: 2025-05-01 – 2025-06-01
Filtrering: "radiotillstånd"

Resultat:
ID     | Diarienummer | Datum       | Status | Rubrik
-------|--------------|-------------|--------|--------------------------------------------
582733 | 25-7909      | 2025-06-01  | –      | Nytt radiotillstånd för maritim radio
582725 | 25-7901      | 2025-05-31  | –      | Ändring av radiotillstånd för maritim radio
    </pre>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
