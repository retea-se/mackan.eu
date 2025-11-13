<?php
// tools/index.php - Verktygslandningssida med samma design som huvudsidan
$title = 'Verktyg - Professionella onlineverktyg för utvecklare och tekniker';
$metaDescription = 'Utforska kostnadsfria onlineverktyg för utvecklare och tekniker: koordinatkonvertering, QR-kod generator, lösenordsgenerator, RKA-kalkylatorer och mer. Generera, konvertera och analysera data snabbt och enkelt.';
$keywords = 'onlineverktyg, koordinatkonvertering, QR-kod generator, lösenordsgenerator, RKA-kalkylator, enhetskonverterare, utvecklarverktyg, tekniska beräkningar, gratis verktyg';
$canonical = 'https://mackan.eu/tools/';

include '../includes/layout-start.php';
include '../includes/breadcrumbs.php';

$tools = include __DIR__ . '/../config/tools.php';

// Sortera verktyg alfabetiskt efter 'title'
usort($tools, function ($a, $b) {
  return strcasecmp($a['title'], $b['title']);
});

?>

<main class="layout__container">
  <section class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      Professionella verktyg för utvecklare och tekniker
    </h1>
    <p class="text--lead">
      Här hittar du användbara (nördiga) onlineverktyg för konvertering, datagenerering och testning.
      Snabbt, säkert och gratis. Alla verktyg är utan registrering och dina data lagras aldrig.
    </p>
  </section>

  <section class="layout__sektion">
    <div class="meny">
      <?php foreach ($tools as $tool): ?>
        <a href="<?= htmlspecialchars($tool['href']) ?>" class="meny__kort">
          <?php if (!empty($tool['icon'])): ?>
            <div class="meny__ikon" aria-hidden="true"><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i></div>
          <?php endif; ?>
          <div class="meny__text"><?= htmlspecialchars($tool['title']) ?></div>
        <?php if (!empty($tool['desc'])): ?>
          <div class="meny__beskrivning"><?= htmlspecialchars($tool['desc']) ?></div>
        <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--sektion text--center">Varför välja Mackan.eu?</h2>
    <div class="layout__grid">
      <article class="kort text--center">
        <span class="text--lead" aria-hidden="true">🆓</span>
        <h3 class="rubrik rubrik--underrubrik">Helt gratis</h3>
        <p class="text--muted">
          Alla verktyg är gratis att använda utan begränsningar eller krav på konto.
        </p>
      </article>
      <article class="kort text--center">
        <span class="text--lead" aria-hidden="true">🔒</span>
        <h3 class="rubrik rubrik--underrubrik">Säkert och privat</h3>
        <p class="text--muted">
          Beräkningar sker lokalt i din webbläsare. Inga personuppgifter skickas eller sparas.
        </p>
      </article>
      <article class="kort text--center">
        <span class="text--lead" aria-hidden="true">📱</span>
        <h3 class="rubrik rubrik--underrubrik">Fungerar på alla enheter</h3>
        <p class="text--muted">
          Responstänk i varje verktyg gör det smidigt på dator, surfplatta och mobil.
        </p>
      </article>
      <article class="kort text--center">
        <span class="text--lead" aria-hidden="true">⚡</span>
        <h3 class="rubrik rubrik--underrubrik">Snabb och effektiv</h3>
        <p class="text--muted">
          Optimerade verktyg som ger resultat på sekunder även för större datamängder.
        </p>
      </article>
    </div>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--sektion text--center">Mest populära verktyg</h2>
    <div class="layout__grid">
      <article class="kort">
        <h3 class="rubrik rubrik--underrubrik text--center">
          <span aria-hidden="true" class="text--highlight"><i class="fa-solid fa-compass"></i></span>
          Koordinatkonverterare
        </h3>
        <p class="text--muted">
          Konvertera mellan WGS84, SWEREF99 och RT90. Stöd för batch-import, kartvisning och CSV-export.
        </p>
        <div class="knapp__grupp">
          <a class="knapp knapp--liten" href="/tools/koordinat/">Använd verktyget</a>
        </div>
      </article>

      <article class="kort">
        <h3 class="rubrik rubrik--underrubrik text--center">
          <span aria-hidden="true" class="text--highlight"><i class="fa-solid fa-qrcode"></i></span>
          QR-kodgenerator
        </h3>
        <p class="text--muted">
          Skapa QR-koder med logotyp, färger och olika format. Perfekt för marknadsföring och informationsdelning.
        </p>
        <div class="knapp__grupp">
          <a class="knapp knapp--liten" href="/tools/qr_v2/">Använd verktyget</a>
        </div>
      </article>

      <article class="kort">
        <h3 class="rubrik rubrik--underrubrik text--center">
          <span aria-hidden="true" class="text--highlight"><i class="fa-solid fa-key"></i></span>
          Lösenordsgenerator
        </h3>
        <p class="text--muted">
          Generera säkra lösenord med valbara kriterier, passfraslägen och export direkt i webbläsaren.
        </p>
        <div class="knapp__grupp">
          <a class="knapp knapp--liten" href="/tools/passwordgenerator/">Använd verktyget</a>
        </div>
      </article>
    </div>
  </section>

  <section class="layout__sektion">
    <h2 class="rubrik rubrik--sektion text--center">Vanliga frågor</h2>
    <div class="faq">
      <details class="faq__item">
        <summary class="faq__summary">Kostar det något att använda verktygen?</summary>
        <p class="faq__content">
          Nej, alla verktyg på Mackan.eu är helt gratis att använda och kräver ingen registrering eller betalning.
          Plattformen finansieras inte genom reklam eller dataförsäljning.
        </p>
      </details>
      <details class="faq__item">
        <summary class="faq__summary">Sparas mina data någonstans?</summary>
        <p class="faq__content">
          Alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter eller känsliga data skickas till Mackan.eu.
          Plattformen är GDPR-kompatibel genom design.
        </p>
      </details>
      <details class="faq__item">
        <summary class="faq__summary">Vilka koordinatsystem stöds i koordinatverktyget?</summary>
        <p class="faq__content">
          Verktyget stöder WGS84 (GPS), SWEREF99 (svenska referenssystemet) och RT90 (äldre svenska systemet) med alla vanliga zoner.
          Perfekt för GIS-arbete och lantmäteri.
        </p>
      </details>
      <details class="faq__item">
        <summary class="faq__summary">Kan jag använda verktygen offline?</summary>
        <p class="faq__content">
          De flesta verktyg fungerar offline efter första laddningen, förutom de som behöver externa data som kartunderlag eller API-anrop.
          Det gör verktygen praktiska även vid fältarbete.
        </p>
      </details>
      <details class="faq__item">
        <summary class="faq__summary">Stöds batch-import i verktygen?</summary>
        <p class="faq__content">
          Ja, flera verktyg som koordinatkonverteraren stöder batch-import via CSV eller text så att du kan bearbeta stora datamängder effektivt.
        </p>
      </details>
    </div>
  </section>

</main>

<!-- Strukturerad data för verktygsöversikt -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Professionella onlineverktyg för utvecklare och tekniker",
  "description": "Kostnadsfria onlineverktyg för konvertering, datagenerering och testning. Koordinatkonvertering, QR-kod generator, lösenordsgenerator och mer.",
  "url": "https://mackan.eu/tools/",
  "publisher": {
    "@type": "Organization",
    "name": "Mackan.eu",
    "url": "https://mackan.eu"
  },
  "mainEntity": {
    "@type": "ItemList",
    "itemListElement": [
      {
        "@type": "SoftwareApplication",
        "position": 1,
        "name": "Koordinatkonverterare",
        "description": "Konvertera mellan WGS84, SWEREF99 och RT90 med batch-import och CSV-export",
        "url": "https://mackan.eu/tools/koordinat/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 2,
        "name": "QR-kodgenerator",
        "description": "Skapa anpassade QR-koder med logo och färger för marknadsföring",
        "url": "https://mackan.eu/tools/qr_v2/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 3,
        "name": "Lösenordsgenerator",
        "description": "Generera säkra lösenord med anpassningsbara kriterier och styrkeanalys",
        "url": "https://mackan.eu/tools/passwordgenerator/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 4,
        "name": "RKA-kalkylatorer",
        "description": "Dimensionera reservkraftverk och beräkna bränsleförbrukning",
        "url": "https://mackan.eu/tools/rka/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 5,
        "name": "JSON Converter",
        "description": "Konvertera mellan olika dataformat: JSON, CSV, XML och mer",
        "url": "https://mackan.eu/tools/converter/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 6,
        "name": "PTS Diarium",
        "description": "Sök i Post- och telestyrelsens register för frekvenstillstånd",
        "url": "https://mackan.eu/tools/pts/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 7,
        "name": "Kortlänk",
        "description": "Skapa korta, anpassade länkar för enklare delning och spårning",
        "url": "https://mackan.eu/tools/kortlank/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 8,
        "name": "Persontestdata",
        "description": "Generera realistisk testdata för utveckling och testning",
        "url": "https://mackan.eu/tools/testdata/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        }
      }
    ]
  }
}
</script>

<!-- FAQ strukturerad data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Kostar det något att använda verktygen?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nej, alla verktyg på Mackan.eu är helt gratis att använda och kräver ingen registrering eller betalning. Plattformen finansieras inte genom reklam eller dataförsäljning."
      }
    },
    {
      "@type": "Question",
      "name": "Sparas mina data någonstans?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nej, alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter eller känsliga data skickas till Mackan.eu. Plattformen är GDPR-kompatibel genom design."
      }
    },
    {
      "@type": "Question",
      "name": "Vilka koordinatsystem stöds i koordinatverktyget?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Verktyget stöder WGS84 (GPS), SWEREF99 (svenska referenssystemet) och RT90 (äldre svenska systemet) med alla vanliga zoner. Perfekt för GIS-arbete och lantmäteri."
      }
    },
    {
      "@type": "Question",
      "name": "Kan jag använda verktygen offline?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "De flesta verktyg fungerar offline efter första laddningen, förutom de som behöver externa data som kartunderlag eller API-anrop. Perfekt för fältarbete."
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
  "name": "Verktyg - Professionella onlineverktyg för utvecklare och tekniker",
  "description": "Utforska kostnadsfria onlineverktyg för utvecklare och tekniker: koordinatkonvertering, QR-kod generator, lösenordsgenerator, RKA-kalkylatorer och mer.",
  "url": "https://mackan.eu/tools/",
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
        "name": "Verktyg",
        "item": "https://mackan.eu/tools/"
      }
    ]
  }
}
</script>

<?php include '../includes/layout-end.php'; ?>
