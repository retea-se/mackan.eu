<!-- tools/testid/readme.php - v6 -->
<?php
$title = 'TestID';
$metaDescription = 'TestID genererar testpersonnummer baserat på Skatteverkets öppna API. Fiktiva men giltiga personnummer med kön, ålder och export till JSON, CSV och Excel.';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">
  <h1 class="title">
    <?= $title ?>
    <a href="index.php" class="info-link-floating" title="Tillbaka till verktyget">&larr;</a>
  </h1>

  <article class="card readme">
    <h2>Syfte</h2>
    <p>
      <strong>TestID</strong> är ett utvecklarverktyg för att generera <em>testpersonnummer</em> enligt
      definition från <a href="https://skatteverket.se" target="_blank">Skatteverket</a>. Personnumren är fiktiva men korrekt formaterade med gällande struktur och kontrollsiffra (Luhn).
      Verktyget använder data från Skatteverkets öppna API.
    </p>

    <h2>Funktioner</h2>
    <ul>
      <li>Välj antal testpersonnummer att generera (1–100)</li>
      <li>Filtrera efter födelseår (från/till)</li>
      <li>Automatisk födelsetolkning: datum, kön, ålder</li>
      <li>Luhn-kontroll för giltighet (visas med ✔ eller ❌)</li>
      <li>Responsiv tabell med sticky rubriker och tydliga kolumner</li>
      <li>Exportfunktion för JSON, CSV och Excel</li>
      <li>Sortering genom klick på valfri kolumn</li>
      <li>Laddningsindikator under API-hämtning</li>
    </ul>

    <h2>Användning</h2>
    <p>
      Välj hur många testpersonnummer du vill generera. Du kan även begränsa årspannet till exempelvis <code>1950–1990</code> om du vill ha personer i viss ålder.
      Klicka sedan på <strong>Hämta</strong> för att ladda data från Skatteverkets API.
    </p>
    <p>
      Verktyget analyserar födelsedatum, beräknar ålder och tolkar kön utifrån den elfte siffran (jämn = kvinna, udda = man).
      Personnummer kontrolleras mot <a href="https://sv.wikipedia.org/wiki/Luhn-algoritmen" target="_blank">Luhn-algoritmen</a> för att visa om kontrollsiffran är korrekt.
    </p>
    <p>
      När tabellen laddats visas exportknappar för nedladdning i tre format. Klicka på valfri knapp för att spara resultatet.
    </p>

    <h2>Datakälla</h2>
    <p>
      Personnumren hämtas via Skatteverkets API för testpersonnummer:
      <a href="https://skatteverket.entryscape.net/rowstore/dataset/b4de7df7-63c0-4e7e-bb59-1f156a591763" target="_blank">
        https://skatteverket.entryscape.net/rowstore/dataset/...
      </a> (JSON-format, CC0-licens).
    </p>
    <p>
      Du hittar mer information om testpersonnummer och hur de får användas via
      <a href="https://skatteverket.se/utvecklare" target="_blank">Skatteverkets utvecklarportal</a>.
    </p>

    <h2>Exempel</h2>
    <pre class="terminal-output">
Antal: 5
Från: 1980  Till: 1990

Resultat:
Personnummer   | Födelsedatum | Kön   | Ålder | Giltigt
----------------|--------------|--------|--------|--------
198504122389    | 1985-04-12   | Kvinna | 39     | ✔
198902242387    | 1989-02-24   | Man    | 35     | ❌
...
    </pre>
  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
