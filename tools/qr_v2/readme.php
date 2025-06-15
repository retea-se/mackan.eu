<?php
// tools/qr_v2/readme.php - v3
$title = 'Dokumentation – QR v2';
$metaDescription = 'Lär dig om QR v2-verktyget: generera QR-koder för text, URL, kontakt, WiFi m.m. Läs om QR-teknikens historia, användning och funktioner.';
include '../../includes/layout-start.php';
?>

<main class="container">

  <article class="card">
    <h2>Vad är en QR-kod?</h2>
    <p>
      <strong>QR-kod</strong> står för <em>Quick Response-kod</em>. Det är en tvådimensionell streckkod som snabbt kan läsas av med en smartphone eller QR-läsare. QR-koder kan lagra mycket mer information än vanliga streckkoder, till exempel webbadresser, kontaktuppgifter, WiFi-inställningar och mycket mer.
    </p>
    <p>
      QR-tekniken utvecklades i Japan 1994 av företaget Denso Wave, ursprungligen för att spåra bildelar i industrin. Idag används QR-koder över hela världen – på affischer, biljetter, för digitala menyer och betalningar.
    </p>
    <p>
      När du skannar en QR-kod med din mobilkamera tolkas informationen direkt och du kan snabbt öppna en länk, spara en kontakt eller ansluta till ett WiFi-nätverk utan att skriva in något manuellt.
    </p>
  </article>

  <article class="card">
    <h2>Användningsområden för olika QR-typer</h2>
    <ul>
      <li>
        <strong>Text:</strong> Dela ett meddelande, instruktioner eller kodsnuttar. Perfekt för snabb informationsöverföring utan internetlänk.
      </li>
      <li>
        <strong>URL:</strong> Länka direkt till en webbsida, t.ex. kampanjsidor, produktinformation, bokningssystem eller digitala menyer.
      </li>
      <li>
        <strong>Kontakt (vCard):</strong> Låt användare spara dina kontaktuppgifter direkt i sin adressbok – smidigt på visitkort, mässor eller events.
      </li>
      <li>
        <strong>WiFi:</strong> Dela WiFi-namn och lösenord så att gäster kan ansluta utan att skriva in något – perfekt på caféer, kontor eller hemma.
      </li>
      <li>
        <strong>E-post:</strong> Skapa ett färdigt e-postutkast med mottagare, ämne och meddelande. Användbart för support, feedback eller bokningar.
      </li>
      <li>
        <strong>SMS:</strong> Skapa ett färdigt SMS-meddelande till ett specifikt nummer. Bra för tävlingar, kampanjer eller snabb kundkontakt.
      </li>
      <li>
        <strong>Telefon:</strong> Låt användaren ringa upp ett nummer direkt genom att skanna koden – praktiskt på skyltar, annonser eller kundservice.
      </li>
      <li>
        <strong>Plats:</strong> Dela en exakt plats (latitud/longitud) som öppnas i kartappen – användbart för eventplatser, mötespunkter eller sevärdheter.
      </li>
    </ul>
  </article>

  <article class="card">
    <h2>Exempel på QR-kod</h2>
    <pre class="terminal-output">
Text: https://mackan.eu

Resultat:
[QR-kod genereras och visas i verktyget]
    </pre>
  </article>

  <section style="margin-top:2rem;">
    <a href="index.php" class="button" data-tippy-content="Tillbaka till QR-verktyget">Tillbaka till QR-verktyget</a>
  </section>

</main>

<?php include '../../includes/layout-end.php'; ?>
