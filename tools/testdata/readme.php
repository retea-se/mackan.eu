<!-- tools/testdata/readme.php - v2 -->
<?php
$title = 'Personinformation - testdata – Detaljerad beskrivning och användning';
$metaDescription = 'En teknisk och detaljerad beskrivning av testdata-generatorns funktioner, datakällor och användningsområden.';
?>

<?php include '../../includes/layout-start.php'; ?>



  <article class="card readme">

    <h2>Syfte och användningsområde</h2>
    <p>
      Detta verktyg är utformat för att generera realistisk svensk testdata med personuppgifter inklusive namn, kön, företagsnamn, telefonnummer, e-postadresser och personnummer. Syftet är att möjliggöra testning och utveckling av applikationer och system där riktiga personuppgifter inte får användas, samtidigt som testdatan behåller strukturell och formatmässig trovärdighet.
    </p>

    <h2>Datakällor och struktur</h2>
    <p>
      Testdatan hämtas  dynamiskt från flera förberedda datakällor som lagras i databastabeller. Dessa inkluderar:
    </p>
    <ul>
      <li><strong>Förnamn</strong> – Med koppling till kön (man, kvinna, okänd).</li>
      <li><strong>Efternamn</strong> – Vanliga svenska efternamn.</li>
      <li><strong>Företagsdelar</strong> – Svenska och engelska ord som kombineras för att skapa trovärdiga företagsnamn.</li>
      <li><strong>Domäner</strong> – Vanliga svenska och internationella domäner (.se, .nu, .com) som används vid e-postgenerering.</li>
      <li><strong>E-postformat</strong> – Strängar med platshållare för förnamn, efternamn, företagsdomän m.m. som används för att generera varierade e-postadresser.</li>
      <li><strong>Telefonserier</strong> – Definierade intervall för mobil- och fasttelefonnummer baserade på riktiga nummerserier.</li>
    </ul>

    <h2>Genereringslogik</h2>
    <p>
      Vid varje generering sker följande steg i backend:
    </p>
    <ol>
      <li>Slumpmässigt val av ett förnamn med kopplat kön.</li>
      <li>Slumpmässigt val av ett efternamn.</li>
      <li>Slumpmässigt val och sammansättning av två företagsdelar till ett företagsnamn.</li>
      <li>Slumpmässigt val av domän från domänlistan.</li>
      <li>E-postadressen genereras genom att ett slumpmässigt e-postformat väljs och platshållare ersätts med förnamn, efternamn och företagsdomän i korrekta format.</li>
      <li>Telefonnummer genereras inom definierade nummerintervall för både fast och mobiltelefon.</li>
      <li>Personnummer hämtas via separat generering och valideras mot kön med Luhn-algoritmen.</li>
    </ol>

    <h2>Viktigt om telefonnummer i media</h2>
    <p>
      Använd inte riktiga telefonnummer i böcker, filmer eller andra medier eftersom det kan orsaka oönskade problem för den som har numret. I stället har vi reserverat några nummerserier för fast och mobil telefoni som du fritt kan använda nummer ur.
    </p>
    <p><strong>Reserverade nummerserier:</strong></p>
    <ul>
      <li>Mobilnummer: 070-1740605 – 1740699 (exempel: 070-1740635)</li>
      <li>Fastnätsnummer: 031-3900600 – 3900699 (exempel: 031-3900642)</li>
      <li>Fastnätsnummer: 040-6280400 – 6280499 (exempel: 040-6280473)</li>
      <li>Fastnätsnummer: 08-46500400 – 46500499 (exempel: 08-46500456)</li>
      <li>Fastnätsnummer: 0980-319200 – 319299 (exempel: 0980-319247)</li>
    </ul>
    <p>Källa: <a href="https://www.pts.se/" target="_blank" rel="noopener noreferrer">www.pts.se</a></p>
    <p><em>Sidan uppdaterades: 2024-05-14</em></p>

    <h2>Personnummer och testmiljöer</h2>
    <p>
      Personnummer som genereras är baserade på Skatteverkets testpersonnummer. Dessa är avsedda för testmiljöer och får endast användas i sådana sammanhang. Testpersonnummer täcker sekelsiffror för åren 1890-2025, och nya testnummer för kommande år publiceras i december varje år.
    </p>
    <p>
      Skatteverket tillhandahåller ett API med testpersonnummer som kan filtreras och hämtas via deras tjänst:
      <a href="https://skatteverket.entryscape.net/rowstore/dataset/b4de7df7-63c0-4e7e-bb59-1f156a591763" target="_blank" rel="noopener noreferrer">Skatteverkets testpersonnummer API</a>.
    </p>
    <p>
      Personnumren i verktyget valideras och formateras enligt svenska standarder, inklusive kontroll av kön och Luhn-algoritmen för kontrollsiffra.
    </p>

    <h2>Exportfunktioner</h2>
    <p>
      Efter generering kan datan exporteras i flera olika format med möjlighet att antingen visa data i ny flik eller ladda ner direkt:
    </p>
    <ul>
      <li><strong>JSON</strong> – Visas i ny flik med kopierings- och nedladdningsknapp.</li>
      <li><strong>CSV</strong> – Visas i ny flik, nedladdningsbar.</li>
      <li><strong>TXT</strong> – Visas i ny flik, nedladdningsbar som tab-separerad textfil.</li>
      <li><strong>Excel (.xlsx)</strong> – Laddas ned direkt med SheetJS.</li>
    </ul>

    <h2>Teknisk implementation</h2>
    <p>
      Verktyget är byggt med en modern arkitektur där backend (PHP) levererar slumpmässiga dataposter från fördefinierade tabeller. Frontend kommunicerar via asynkrona anrop (fetch) för att generera testpersoner och hantera exportfunktionalitet.
      Validering och formatering sker både i backend och frontend för hög datakvalitet.
    </p>

    <h2>Användningsområden</h2>
    <ul>
      <li>Utveckling och testning av applikationer som kräver realistisk men anonym data.</li>
      <li>Demonstration och utbildning inom datavalidering, personnummerhantering och dataexport.</li>
      <li>Prestandatester med varierande datamängder.</li>
      <li>Simulering av kundregister och kontaktlistor i testmiljö.</li>
    </ul>

  </article>
</main>

<?php include '../../includes/layout-end.php'; ?>
