<?php
/**
 * faq.php - Omfattande FAQ-sida med maskinläsbart innehåll
 *
 * Denna sida innehåller vanliga frågor om alla verktyg på mackan.eu
 * med strukturerad data (JSON-LD) för bättre SEO och AI-hittbarhet.
 */

$title = 'Vanliga frågor - FAQ om verktyg på Mackan.eu';
$metaDescription = 'Hitta svar på vanliga frågor om alla verktyg på Mackan.eu: QR-kodgenerator, koordinatkonverterare, lösenordsgenerator, RKA-kalkylator och mer. Lär dig hur du använder verktygen effektivt.';
$keywords = 'FAQ, vanliga frågor, hjälp, QR-kod, koordinatkonvertering, lösenordsgenerator, RKA-kalkylator, mackan.eu, verktyg, support';
$canonical = 'https://mackan.eu/faq.php';
$ogType = 'article';

include 'includes/layout-start.php';
include 'includes/breadcrumbs.php';

$tools = include __DIR__ . '/config/tools.php';
?>

<main class="layout__container">
  <section class="layout__sektion">
    <h1 class="rubrik rubrik--sektion">Vanliga frågor om verktygen</h1>
    <p class="text--lead">
      Här hittar du svar på vanliga frågor om alla verktyg på Mackan.eu.
      Om du inte hittar det du söker, kontakta oss gärna.
    </p>
  </section>

  <!-- Allmänna frågor -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Allmänna frågor</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Vad är Mackan.eu?</summary>
        <div class="faq__content">
          <p>Mackan.eu är en samling kostnadsfria onlineverktyg för utvecklare, tekniker och GIS-professionella.
          Plattformen erbjuder verktyg för koordinatkonvertering, QR-kodgenerering, lösenordsgenerering,
          datakonvertering och mer. Alla verktyg är gratis att använda och kräver ingen registrering.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kostar det något att använda verktygen?</summary>
        <div class="faq__content">
          <p>Nej, alla verktyg på Mackan.eu är helt gratis att använda. Det finns inga dolda kostnader,
          inga premiumversioner och inga betalväggar. Verktygen är utvecklade som ett öppet projekt
          för att hjälpa utvecklare och tekniker i sitt dagliga arbete.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Behöver jag skapa ett konto?</summary>
        <div class="faq__content">
          <p>Nej, inget konto krävs. Alla verktyg fungerar direkt i webbläsaren utan registrering.
          Vissa verktyg sparar inställningar lokalt i din webbläsare (localStorage) för att komma
          ihåg dina preferenser, men inga personuppgifter skickas till våra servrar.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Sparas mina data någonstans?</summary>
        <div class="faq__content">
          <p>Nej, alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter,
          lösenord, koordinater eller andra känsliga data skickas till Mackan.eu. Detta gör plattformen
          GDPR-kompatibel genom design och säkerställer att dina data förblir privata.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Fungerar verktygen på mobil?</summary>
        <div class="faq__content">
          <p>Ja, alla verktyg är responsiva och fungerar på dator, surfplatta och mobiltelefon.
          Gränssnitten är optimerade för touch-interaktion och fungerar bra även på mindre skärmar.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag använda verktygen offline?</summary>
        <div class="faq__content">
          <p>De flesta verktyg fungerar offline efter första laddningen, förutom de som behöver externa
          data som kartunderlag eller API-anrop. Verktyg som koordinatkonverteraren, QR-kodgeneratorn
          och lösenordsgeneratorn fungerar helt offline. Verktyg som använder kartor eller externa
          API:er kräver internetanslutning.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vilka webbläsare stöds?</summary>
        <div class="faq__content">
          <p>Verktygen fungerar i alla moderna webbläsare som stöder ES6 JavaScript, inklusive Chrome,
          Firefox, Safari, Edge och Opera. För bästa prestanda rekommenderas den senaste versionen
          av din webbläsare.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- QR-kodgenerator -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">QR-kodgenerator</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Hur skapar jag en QR-kod?</summary>
        <div class="faq__content">
          <p>Välj typ av QR-kod (URL, text, WiFi, kontaktkort, etc.), fyll i informationen i formuläret,
          anpassa färger och logotyp om du vill, och klicka på "Generera". Du kan sedan ladda ner QR-koden
          som PNG-bild eller kopiera den direkt.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag lägga till min logotyp i QR-koden?</summary>
        <div class="faq__content">
          <p>Ja, du kan ladda upp en logotyp (PNG eller SVG) som visas i mitten av QR-koden.
          Logotypen bör vara högupplöst och ha god kontrast mot bakgrunden för bästa läsbarhet.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vilka färger kan jag använda?</summary>
        <div class="faq__content">
          <p>Du kan välja valfri färg för QR-koden och bakgrunden. För bästa läsbarhet rekommenderas
          hög kontrast mellan kod och bakgrund (t.ex. svart på vit eller mörk färg på ljus bakgrund).</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag skapa flera QR-koder samtidigt?</summary>
        <div class="faq__content">
          <p>Ja, i batch-läget kan du skapa flera QR-koder samtidigt. Använd batch-läget för att
          skapa QR-koder för felanmälningar, länkar eller annat innehåll i bulk. Du kan exportera
          alla koder som ZIP-fil eller DOCX-dokument.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vilka format kan jag exportera QR-koden i?</summary>
        <div class="faq__content">
          <p>Du kan exportera QR-koder som PNG-bilder, ZIP-arkiv (för batch), DOCX-dokument eller
          PDF med etikettlayout. Alla format är optimerade för utskrift och digital användning.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- Koordinatkonverterare -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Koordinatkonverterare</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Vilka koordinatsystem stöds?</summary>
        <div class="faq__content">
          <p>Verktyget stöder WGS84 (GPS-koordinater), SWEREF99 (svenska referenssystemet med olika zoner)
          och RT90 (äldre svenska systemet). Du kan konvertera mellan alla dessa system med hög precision.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Hur konverterar jag koordinater?</summary>
        <div class="faq__content">
          <p>Ange koordinaterna i det system de är i, välj målkoordinatsystem, och klicka på "Konvertera".
          Du kan ange koordinater i olika format: decimalgrader, grader/minuter/sekunder, eller som
          northing/easting-värden.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag konvertera flera koordinater samtidigt?</summary>
        <div class="faq__content">
          <p>Ja, du kan använda batch-import för att konvertera flera koordinater samtidigt.
          Klistra in koordinaterna i textfältet eller importera en CSV-fil. Verktyget bearbetar
          alla koordinater och ger dig resultatet som CSV eller JSON.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vad är skillnaden mellan WGS84, SWEREF99 och RT90?</summary>
        <div class="faq__content">
          <p><strong>WGS84</strong> är det globala GPS-systemet som används av satellitnavigering.
          <strong>SWEREF99</strong> är det svenska referenssystemet som ger bättre precision i Sverige.
          <strong>RT90</strong> är det äldre svenska systemet som fortfarande används i många äldre kartor
          och dokument. Verktyget konverterar korrekt mellan alla tre system.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag se koordinaterna på en karta?</summary>
        <div class="faq__content">
          <p>Ja, verktyget visar konverterade koordinater på en interaktiv karta. Du kan se exakt
          var koordinaten ligger och jämföra olika koordinatsystem visuellt.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- Lösenordsgenerator -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Lösenordsgenerator</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Hur genererar jag ett säkert lösenord?</summary>
        <div class="faq__content">
          <p>Välj längd (minst 12 tecken rekommenderas), aktivera olika teckenkategorier (stora bokstäver,
          små bokstäver, siffror, specialtecken), och klicka på "Generera". Verktyget visar också
          lösenordets styrka så du kan se om det är tillräckligt säkert.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vad är en passfras?</summary>
        <div class="faq__content">
          <p>En passfras är ett längre lösenord bestående av flera ord, t.ex. "korv-bil-moln-123".
          Passfraser är ofta lättare att komma ihåg men ändå säkra. Verktyget kan generera passfraser
          med svenska eller engelska ord.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Hur långt bör ett lösenord vara?</summary>
        <div class="faq__content">
          <p>För bästa säkerhet rekommenderas minst 12-16 tecken för vanliga lösenord.
          För känsliga konton bör lösenordet vara minst 20 tecken. Verktyget visar styrkeindikator
          så du kan se om ditt lösenord är tillräckligt starkt.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag exportera genererade lösenord?</summary>
        <div class="faq__content">
          <p>Ja, du kan exportera lösenord som textfil, CSV eller JSON. Detta är användbart om du
          behöver generera flera lösenord för olika konton eller tjänster.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- RKA-kalkylator -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">RKA-kalkylator</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Vad är RKA?</summary>
        <div class="faq__content">
          <p>RKA står för Reservkraftverk och används för att dimensionera reservkraftverk och
          beräkna bränsleförbrukning. Kalkylatorn hjälper dig att beräkna rätt storlek på
          reservkraftverk för din specifika situation.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Hur beräknar jag reservkraft?</summary>
        <div class="faq__content">
          <p>Ange din lastprofil, valfri provkörning, och välj RKA-profil. Kalkylatorn beräknar
          automatiskt nödvändig effekt, bränsleförbrukning och ger rekommendationer baserat på
          dina indata.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vad är en RKA-profil?</summary>
        <div class="faq__content">
          <p>En RKA-profil definierar specifikationer för ett reservkraftverk, inklusive effekt,
          bränsleförbrukning och andra tekniska parametrar. Verktyget innehåller fördefinierade
          profiler för vanliga reservkraftverk.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- Datakonvertering -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Datakonvertering</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Vilka format kan jag konvertera mellan?</summary>
        <div class="faq__content">
          <p>Verktygen stöder konvertering mellan JSON, CSV, XML, och andra dataformat.
          Du kan också konvertera CSS till JSON, CSV till JSON, och formatera/validera JSON-data.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Hur konverterar jag CSV till JSON?</summary>
        <div class="faq__content">
          <p>Klistra in din CSV-data eller ladda upp en CSV-fil. Verktyget analyserar strukturen
          och konverterar automatiskt till JSON. Du kan anpassa inställningar som avgränsare,
          rubriker och datatyper innan konvertering.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag validera JSON-data?</summary>
        <div class="faq__content">
          <p>Ja, JSON Converter-verktyget kan validera JSON-data och visa eventuella fel.
          Det hjälper dig att hitta syntaxfel och säkerställa att din JSON är korrekt formaterad.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- Övriga verktyg -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Övriga verktyg</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Vad är kortlänk-verktyget?</summary>
        <div class="faq__content">
          <p>Kortlänk-verktyget låter dig skapa korta, anpassade länkar från långa URL:er.
          Detta är användbart för delning på sociala medier, e-post eller tryckta material där
          utrymmet är begränsat.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Hur fungerar skyddad delning?</summary>
        <div class="faq__content">
          <p>Skyddad delning låter dig skapa lösenordsskyddade länkar för känslig information.
          Du anger ett lösenord när du skapar länken, och mottagaren måste ange samma lösenord
          för att komma åt innehållet.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Vad är persontestdata-verktyget?</summary>
        <div class="faq__content">
          <p>Persontestdata-verktyget genererar realistiska testpersoner med personnummer, adresser
          och metadata. Detta är användbart för utvecklare som behöver testdata för sina applikationer
          utan att använda riktiga personuppgifter.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Hur använder jag Bolagsverket-verktyget?</summary>
        <div class="faq__content">
          <p>Ange organisationsnummer eller företagsnamn för att hämta data från Bolagsverkets API.
          Verktyget visar företagsinformation, årsredovisningar och andra tillgängliga dokument.
          Du kan exportera data för vidare analys.</p>
        </div>
      </details>
    </div>
  </section>

  <!-- Tekniska frågor -->
  <section class="layout__sektion">
    <h2 class="rubrik rubrik--underrubrik">Tekniska frågor</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Vilken precision har koordinatkonverteringen?</summary>
        <div class="faq__content">
          <p>Koordinatkonverteringen använder officiella transformationer och ger hög precision
          (typiskt inom några centimeter). Precisionen varierar beroende på koordinatsystem och
          zon, men är tillräcklig för de flesta GIS- och lantmäteriändamål.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Stöder verktygen API-anrop?</summary>
        <div class="faq__content">
          <p>För närvarande finns inga publika API:er för verktygen. Alla verktyg är designade
          för direktanvändning i webbläsaren. API-stöd kan komma i framtida versioner.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Kan jag använda verktygen kommersiellt?</summary>
        <div class="faq__content">
          <p>Ja, alla verktyg är gratis att använda även för kommersiella ändamål. Det finns inga
          begränsningar eller licenskrav för kommersiell användning.</p>
        </div>
      </details>

      <details class="faq__item">
        <summary class="faq__summary">Var kan jag rapportera buggar eller föreslå förbättringar?</summary>
        <div class="faq__content">
          <p>Kontakta oss via e-post eller GitHub om du hittar buggar eller har förslag på förbättringar.
          Vi uppskattar all feedback som hjälper oss att göra verktygen bättre.</p>
        </div>
      </details>
    </div>
  </section>

  <section class="layout__sektion text--center">
    <p class="text--lead">
      Hittade du inte det du sökte? <a href="/tools/">Utforska alla verktyg</a> eller
      <a href="/">gå tillbaka till startsidan</a>.
    </p>
  </section>
</main>

<!-- Omfattande FAQ strukturerad data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Vad är Mackan.eu?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Mackan.eu är en samling kostnadsfria onlineverktyg för utvecklare, tekniker och GIS-professionella. Plattformen erbjuder verktyg för koordinatkonvertering, QR-kodgenerering, lösenordsgenerering, datakonvertering och mer. Alla verktyg är gratis att använda och kräver ingen registrering."
      }
    },
    {
      "@type": "Question",
      "name": "Kostar det något att använda verktygen?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nej, alla verktyg på Mackan.eu är helt gratis att använda. Det finns inga dolda kostnader, inga premiumversioner och inga betalväggar. Verktygen är utvecklade som ett öppet projekt för att hjälpa utvecklare och tekniker i sitt dagliga arbete."
      }
    },
    {
      "@type": "Question",
      "name": "Sparas mina data någonstans?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nej, alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter, lösenord, koordinater eller andra känsliga data skickas till Mackan.eu. Detta gör plattformen GDPR-kompatibel genom design och säkerställer att dina data förblir privata."
      }
    },
    {
      "@type": "Question",
      "name": "Hur skapar jag en QR-kod?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Välj typ av QR-kod (URL, text, WiFi, kontaktkort, etc.), fyll i informationen i formuläret, anpassa färger och logotyp om du vill, och klicka på 'Generera'. Du kan sedan ladda ner QR-koden som PNG-bild eller kopiera den direkt."
      }
    },
    {
      "@type": "Question",
      "name": "Vilka koordinatsystem stöds?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Verktyget stöder WGS84 (GPS-koordinater), SWEREF99 (svenska referenssystemet med olika zoner) och RT90 (äldre svenska systemet). Du kan konvertera mellan alla dessa system med hög precision."
      }
    },
    {
      "@type": "Question",
      "name": "Hur genererar jag ett säkert lösenord?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Välj längd (minst 12 tecken rekommenderas), aktivera olika teckenkategorier (stora bokstäver, små bokstäver, siffror, specialtecken), och klicka på 'Generera'. Verktyget visar också lösenordets styrka så du kan se om det är tillräckligt säkert."
      }
    },
    {
      "@type": "Question",
      "name": "Kan jag använda verktygen offline?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "De flesta verktyg fungerar offline efter första laddningen, förutom de som behöver externa data som kartunderlag eller API-anrop. Verktyg som koordinatkonverteraren, QR-kodgeneratorn och lösenordsgeneratorn fungerar helt offline."
      }
    },
    {
      "@type": "Question",
      "name": "Kan jag konvertera flera koordinater samtidigt?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ja, du kan använda batch-import för att konvertera flera koordinater samtidigt. Klistra in koordinaterna i textfältet eller importera en CSV-fil. Verktyget bearbetar alla koordinater och ger dig resultatet som CSV eller JSON."
      }
    },
    {
      "@type": "Question",
      "name": "Vad är skillnaden mellan WGS84, SWEREF99 och RT90?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "WGS84 är det globala GPS-systemet som används av satellitnavigering. SWEREF99 är det svenska referenssystemet som ger bättre precision i Sverige. RT90 är det äldre svenska systemet som fortfarande används i många äldre kartor och dokument. Verktyget konverterar korrekt mellan alla tre system."
      }
    },
    {
      "@type": "Question",
      "name": "Kan jag lägga till min logotyp i QR-koden?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ja, du kan ladda upp en logotyp (PNG eller SVG) som visas i mitten av QR-koden. Logotypen bör vara högupplöst och ha god kontrast mot bakgrunden för bästa läsbarhet."
      }
    },
    {
      "@type": "Question",
      "name": "Hur konverterar jag CSV till JSON?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Klistra in din CSV-data eller ladda upp en CSV-fil. Verktyget analyserar strukturen och konverterar automatiskt till JSON. Du kan anpassa inställningar som avgränsare, rubriker och datatyper innan konvertering."
      }
    },
    {
      "@type": "Question",
      "name": "Vad är en passfras?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "En passfras är ett längre lösenord bestående av flera ord, t.ex. 'korv-bil-moln-123'. Passfraser är ofta lättare att komma ihåg men ändå säkra. Verktyget kan generera passfraser med svenska eller engelska ord."
      }
    },
    {
      "@type": "Question",
      "name": "Vad är RKA?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "RKA står för Reservkraftverk och används för att dimensionera reservkraftverk och beräkna bränsleförbrukning. Kalkylatorn hjälper dig att beräkna rätt storlek på reservkraftverk för din specifika situation."
      }
    },
    {
      "@type": "Question",
      "name": "Hur fungerar skyddad delning?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Skyddad delning låter dig skapa lösenordsskyddade länkar för känslig information. Du anger ett lösenord när du skapar länken, och mottagaren måste ange samma lösenord för att komma åt innehållet."
      }
    },
    {
      "@type": "Question",
      "name": "Vilken precision har koordinatkonverteringen?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Koordinatkonverteringen använder officiella transformationer och ger hög precision (typiskt inom några centimeter). Precisionen varierar beroende på koordinatsystem och zon, men är tillräcklig för de flesta GIS- och lantmäteriändamål."
      }
    }
  ]
}
</script>

<!-- WebPage strukturerad data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Vanliga frågor - FAQ om verktyg på Mackan.eu",
  "description": "Hitta svar på vanliga frågor om alla verktyg på Mackan.eu: QR-kodgenerator, koordinatkonverterare, lösenordsgenerator, RKA-kalkylator och mer.",
  "url": "https://mackan.eu/faq.php",
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
        "name": "FAQ",
        "item": "https://mackan.eu/faq.php"
      }
    ]
  },
  "about": {
    "@type": "Thing",
    "name": "Onlineverktyg för utvecklare och tekniker"
  }
}
</script>

<?php include 'includes/layout-end.php'; ?>

