<?php
/**
 * howto.php - How-to guides för populära verktyg
 *
 * Steg-för-steg-guider för att använda verktygen effektivt.
 * Maskinläsbart innehåll med HowTo schema för bättre SEO.
 */

$title = 'Så här använder du verktygen - Steg-för-steg-guider';
$metaDescription = 'Lär dig hur du använder verktygen på Mackan.eu med detaljerade steg-för-steg-guider. Så skapar du QR-koder, konverterar koordinater, genererar lösenord och mer.';
$keywords = 'how-to, guide, instruktioner, QR-kod skapa, koordinat konvertera, lösenord generera, tutorial, mackan.eu';
$canonical = 'https://mackan.eu/howto.php';
$ogType = 'article';

include 'includes/layout-start.php';
include 'includes/breadcrumbs.php';
?>

<main class="layout__container">
  <section class="layout__sektion">
    <h1 class="rubrik rubrik--sektion">Så här använder du verktygen</h1>
    <p class="text--lead">
      Detaljerade steg-för-steg-guider för att använda verktygen på Mackan.eu effektivt.
    </p>
  </section>

  <!-- QR-kodgenerator guide -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Så skapar du en QR-kod med logotyp</h2>
    <div class="howto">
      <ol class="howto__steps">
        <li>
          <strong>Välj QR-kodtyp:</strong> Öppna QR-kodgeneratorn och välj typ av innehåll
          (URL, text, WiFi, kontaktkort, e-post, SMS, telefon eller geografisk position).
        </li>
        <li>
          <strong>Fyll i information:</strong> Ange den data som QR-koden ska innehålla.
          För URL:er, inkludera http:// eller https://. För WiFi, ange nätverksnamn, lösenord och säkerhetstyp.
        </li>
        <li>
          <strong>Anpassa utseende:</strong> Välj färger för QR-koden och bakgrunden.
          För bästa läsbarhet, använd hög kontrast (t.ex. svart på vit).
        </li>
        <li>
          <strong>Lägg till logotyp (valfritt):</strong> Klicka på "Avancerat" och ladda upp din logotyp
          (PNG eller SVG, max 500 KB). Logotypen visas i mitten av QR-koden.
        </li>
        <li>
          <strong>Förhandsgranska:</strong> QR-koden uppdateras automatiskt när du gör ändringar.
          Kontrollera att den ser bra ut och är läsbar.
        </li>
        <li>
          <strong>Exportera:</strong> Klicka på "Ladda ner PNG" för att spara QR-koden som bild,
          eller "Kopiera" för att kopiera den till urklipp.
        </li>
      </ol>
      <p class="text--muted">
        <strong>Tips:</strong> Testa alltid QR-koden med din mobiltelefon innan du använder den i tryck.
        Se till att logotypen inte täcker mer än 30% av QR-kodens yta för bästa läsbarhet.
      </p>
    </div>
  </section>

  <!-- Koordinatkonvertering guide -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Så konverterar du koordinater</h2>
    <div class="howto">
      <ol class="howto__steps">
        <li>
          <strong>Öppna koordinatkonverteraren:</strong> Gå till koordinatverktyget på Mackan.eu.
        </li>
        <li>
          <strong>Välj källkoordinatsystem:</strong> Välj det system som dina koordinater är i
          (WGS84 för GPS, SWEREF99 för svenska kartor, RT90 för äldre svenska kartor).
        </li>
        <li>
          <strong>Ange koordinater:</strong> Skriv in koordinaterna i valfritt format:
          <ul>
            <li>Decimalgrader: <code>59.3293, 18.0686</code></li>
            <li>Grader/minuter/sekunder: <code>59°19'45.5"N 18°4'7.0"E</code></li>
            <li>Northing/Easting: <code>6580832, 674032</code> (för SWEREF99/RT90)</li>
          </ul>
        </li>
        <li>
          <strong>Välj målkoordinatsystem:</strong> Välj det system du vill konvertera till.
        </li>
        <li>
          <strong>Konvertera:</strong> Klicka på "Konvertera" för att få resultatet.
          Koordinaten visas också på en interaktiv karta.
        </li>
        <li>
          <strong>Exportera (valfritt):</strong> För flera koordinater, använd batch-läget
          och exportera resultatet som CSV eller JSON.
        </li>
      </ol>
      <p class="text--muted">
        <strong>Tips:</strong> För batch-konvertering, klistra in flera koordinater (en per rad)
        eller importera en CSV-fil. Verktyget bearbetar alla koordinater samtidigt.
      </p>
    </div>
  </section>

  <!-- Lösenordsgenerering guide -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Så genererar du säkra lösenord</h2>
    <div class="howto">
      <ol class="howto__steps">
        <li>
          <strong>Öppna lösenordsgeneratorn:</strong> Gå till lösenordsverktyget på Mackan.eu.
        </li>
        <li>
          <strong>Välj längd:</strong> Ange önskad längd (minst 12 tecken rekommenderas,
          16-20 tecken för känsliga konton).
        </li>
        <li>
          <strong>Välj teckenkategorier:</strong> Aktivera de kategorier du vill inkludera:
          <ul>
            <li>Stora bokstäver (A-Z)</li>
            <li>Små bokstäver (a-z)</li>
            <li>Siffror (0-9)</li>
            <li>Specialtecken (!@#$%^&*)</li>
          </ul>
        </li>
        <li>
          <strong>Generera:</strong> Klicka på "Generera" för att skapa ett nytt lösenord.
          Verktyget visar lösenordets styrka.
        </li>
        <li>
          <strong>Alternativ: Passfras:</strong> För lättare att komma ihåg, välj "Passfras"-läge
          som skapar lösenord av flera ord (t.ex. "korv-bil-moln-123").
        </li>
        <li>
          <strong>Kopiera eller exportera:</strong> Kopiera lösenordet direkt eller exportera
          flera lösenord som textfil eller CSV.
        </li>
      </ol>
      <p class="text--muted">
        <strong>Tips:</strong> Använd unika lösenord för varje konto. Spara lösenord i en
        lösenordshanterare istället för att skriva ner dem. Passfraser är ofta lättare att
        komma ihåg men ändå säkra.
      </p>
    </div>
  </section>

  <!-- CSV till JSON guide -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Så konverterar du CSV till JSON</h2>
    <div class="howto">
      <ol class="howto__steps">
        <li>
          <strong>Öppna CSV till JSON-verktyget:</strong> Gå till konverteringsverktyget på Mackan.eu.
        </li>
        <li>
          <strong>Ladda upp eller klistra in data:</strong> Antingen ladda upp en CSV-fil eller
          klistra in CSV-data direkt i textfältet.
        </li>
        <li>
          <strong>Konfigurera inställningar:</strong> Ange avgränsare (komma, semikolon, tab),
          om första raden innehåller rubriker, och önskat JSON-format (array eller objekt).
        </li>
        <li>
          <strong>Förhandsgranska:</strong> Verktyget visar en förhandsvisning av JSON-resultatet
          så du kan kontrollera att konverteringen är korrekt.
        </li>
        <li>
          <strong>Kopiera eller ladda ner:</strong> Kopiera JSON-koden direkt eller ladda ner
          som .json-fil.
        </li>
      </ol>
      <p class="text--muted">
        <strong>Tips:</strong> Om din CSV har specialtecken eller olika avgränsare, använd
        "Avancerat"-läge för att justera inställningarna. Verktyget kan också hantera citattecken
        och escape-tecken korrekt.
      </p>
    </div>
  </section>

  <!-- Batch QR-koder guide -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Så skapar du flera QR-koder samtidigt</h2>
    <div class="howto">
      <ol class="howto__steps">
        <li>
          <strong>Välj batch-läge:</strong> I QR-kodgeneratorn, växla till "Batch & etiketter"-läge.
        </li>
        <li>
          <strong>Välj batchmall:</strong> Välj typ av batch (felanmälan, länkar, valfri text)
          eller skapa en egen mall.
        </li>
        <li>
          <strong>Ange data:</strong> Klistra in eller skriv in data för varje QR-kod, en per rad.
          För felanmälan, använd formatet: <code>nodnummer, adress</code>
        </li>
        <li>
          <strong>Generera:</strong> Klicka på "Generera" för att skapa alla QR-koder.
          Verktyget visar en lista med miniatyrer och status.
        </li>
        <li>
          <strong>Exportera:</strong> Välj exportformat:
          <ul>
            <li><strong>ZIP:</strong> Alla QR-koder som separata PNG-filer</li>
            <li><strong>DOCX:</strong> QR-koder inbäddade i ett Word-dokument</li>
            <li><strong>PDF:</strong> QR-koder i etikettlayout (A4-format)</li>
          </ul>
        </li>
      </ol>
      <p class="text--muted">
        <strong>Tips:</strong> För stora batchar (100+ QR-koder), kan det ta några sekunder att generera.
        PDF-exporten är perfekt för utskrift av etiketter med QR-koder.
      </p>
    </div>
  </section>

  <section class="layout__sektion text--center">
    <p class="text--lead">
      Behöver du mer hjälp? <a href="/faq.php">Läs vanliga frågor</a> eller
      <a href="/tools/">utforska alla verktyg</a>.
    </p>
  </section>
</main>

<!-- HowTo strukturerad data för QR-kodgenerator -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "Så skapar du en QR-kod med logotyp",
  "description": "Steg-för-steg-guide för att skapa anpassade QR-koder med logotyp på Mackan.eu",
  "image": "https://mackan.eu/icon/android-chrome-512x512.png",
  "totalTime": "PT2M",
  "tool": [
    {
      "@type": "HowToTool",
      "name": "QR-kodgenerator på Mackan.eu"
    }
  ],
  "step": [
    {
      "@type": "HowToStep",
      "position": 1,
      "name": "Välj QR-kodtyp",
      "text": "Öppna QR-kodgeneratorn och välj typ av innehåll (URL, text, WiFi, kontaktkort, e-post, SMS, telefon eller geografisk position).",
      "url": "https://mackan.eu/tools/qr_v2/"
    },
    {
      "@type": "HowToStep",
      "position": 2,
      "name": "Fyll i information",
      "text": "Ange den data som QR-koden ska innehålla. För URL:er, inkludera http:// eller https://. För WiFi, ange nätverksnamn, lösenord och säkerhetstyp."
    },
    {
      "@type": "HowToStep",
      "position": 3,
      "name": "Anpassa utseende",
      "text": "Välj färger för QR-koden och bakgrunden. För bästa läsbarhet, använd hög kontrast (t.ex. svart på vit)."
    },
    {
      "@type": "HowToStep",
      "position": 4,
      "name": "Lägg till logotyp",
      "text": "Klicka på 'Avancerat' och ladda upp din logotyp (PNG eller SVG, max 500 KB). Logotypen visas i mitten av QR-koden."
    },
    {
      "@type": "HowToStep",
      "position": 5,
      "name": "Förhandsgranska",
      "text": "QR-koden uppdateras automatiskt när du gör ändringar. Kontrollera att den ser bra ut och är läsbar."
    },
    {
      "@type": "HowToStep",
      "position": 6,
      "name": "Exportera",
      "text": "Klicka på 'Ladda ner PNG' för att spara QR-koden som bild, eller 'Kopiera' för att kopiera den till urklipp."
    }
  ]
}
</script>

<!-- HowTo strukturerad data för koordinatkonvertering -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "Så konverterar du koordinater",
  "description": "Steg-för-steg-guide för att konvertera koordinater mellan WGS84, SWEREF99 och RT90",
  "image": "https://mackan.eu/icon/android-chrome-512x512.png",
  "totalTime": "PT3M",
  "tool": [
    {
      "@type": "HowToTool",
      "name": "Koordinatkonverterare på Mackan.eu"
    }
  ],
  "step": [
    {
      "@type": "HowToStep",
      "position": 1,
      "name": "Öppna koordinatkonverteraren",
      "text": "Gå till koordinatverktyget på Mackan.eu.",
      "url": "https://mackan.eu/tools/koordinat/"
    },
    {
      "@type": "HowToStep",
      "position": 2,
      "name": "Välj källkoordinatsystem",
      "text": "Välj det system som dina koordinater är i (WGS84 för GPS, SWEREF99 för svenska kartor, RT90 för äldre svenska kartor)."
    },
    {
      "@type": "HowToStep",
      "position": 3,
      "name": "Ange koordinater",
      "text": "Skriv in koordinaterna i valfritt format: decimalgrader (59.3293, 18.0686), grader/minuter/sekunder, eller Northing/Easting för SWEREF99/RT90."
    },
    {
      "@type": "HowToStep",
      "position": 4,
      "name": "Välj målkoordinatsystem",
      "text": "Välj det system du vill konvertera till."
    },
    {
      "@type": "HowToStep",
      "position": 5,
      "name": "Konvertera",
      "text": "Klicka på 'Konvertera' för att få resultatet. Koordinaten visas också på en interaktiv karta."
    },
    {
      "@type": "HowToStep",
      "position": 6,
      "name": "Exportera",
      "text": "För flera koordinater, använd batch-läget och exportera resultatet som CSV eller JSON."
    }
  ]
}
</script>

<!-- WebPage strukturerad data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Så här använder du verktygen - Steg-för-steg-guider",
  "description": "Lär dig hur du använder verktygen på Mackan.eu med detaljerade steg-för-steg-guider.",
  "url": "https://mackan.eu/howto.php",
  "inLanguage": "sv-SE",
  "isPartOf": {
    "@type": "WebSite",
    "name": "Mackan.eu",
    "url": "https://mackan.eu"
  },
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Hem",
        "item": "https://mackan.eu/"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "How-to guides",
        "item": "https://mackan.eu/howto.php"
      }
    ]
  }
}
</script>

<?php include 'includes/layout-end.php'; ?>

