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

// Lägg till beskrivningar för ALLA verktyg för bättre SEO
$toolDescriptions = [
  'Addy' => 'E-postverktyg för hantering och validering av e-postadresser.',
  'Aptus' => 'Säkerhetsverktyg för nyckel- och åtkomsthantering.',
  'Flow' => 'Visuellt verktyg för att skapa flödesscheman och processdiagram.',
  'GeoParser & Plotter' => 'Parsea och plotta geografiska data på interaktiva kartor.',
  'Koordinater' => 'Grundläggande koordinatkonvertering mellan olika system.',
  'Koordinater Impex' => 'Professionellt verktyg för konvertering mellan WGS84, SWEREF99 och RT90. Stöder batch-import och CSV-export.',
  'Generera telefonnummer' => 'Generera giltiga svenska telefonnummer för testning och utveckling.',
  'Generera QR F.A. och URL' => 'Skapa QR-koder för webbadresser och kontaktinformation snabbt och enkelt.',
  'Generera QR övrigt' => 'Skapa anpassade QR-koder med logo, färger och olika format. Perfekt för marknadsföring.',
  'Kontrollera personnummer' => 'Validera svenska personnummer och organisationsnummer enligt Skatteverkets regler.',
  'Text till tal' => 'Konvertera text till tal med olika röster och språk. Perfekt för tillgänglighet.',
  'Test-ID' => 'Generera test-identiteter för utveckling och systemtestning.',
  'PTS Diarium' => 'Sök i Post- och telestyrelsens register för frekvenstillstånd och radiosändare.',
  'JSON Converter' => 'Konvertera mellan olika dataformat: JSON, CSV, XML och mer. Snabb och enkel konvertering.',
  'Bolagsverket' => 'Sök och validera företagsinformation från Bolagsverkets register.',
  'Persontestdata' => 'Generera realistisk testdata för utveckling och testning av applikationer.',
  'Lösenordsgenerator' => 'Generera säkra lösenord med anpassningsbara kriterier. Inkluderar styrkeanalys.',
  'Skyddad' => 'Skapa lösenordsskyddade länkar och säkra delningslösningar.',
  'CSS->JSON' => 'Konvertera CSS-kod till JSON-format för utveckling och konfiguration.',
  'Kortlänk' => 'Skapa korta, anpassade länkar för enklare delning och spårning.',
  'RKA-kalkylator' => 'Avancerade kalkylatorer för dimensionering av reservkraftverk, bränsleförbrukning och provkörningsschema.'
];
?>

<main class="layout__container">
  <h1 class="rubrik rubrik--sektion mb-2">
    Professionella verktyg för utvecklare och tekniker
  </h1>
  <p class="lead" style="font-size: 1.1rem; color: #6c757d; margin-bottom: 2rem; line-height: 1.6;">
    Här hittar du användbara (nördiga) onlineverktyg för konvertering, datagenerering och testning. 
    Snabbt, säkert och gratis. Alla verktyg kräver ingen registrering och dina data lagras aldrig.
  </p>
  
  <div class="meny">
    <?php foreach ($tools as $tool): ?>
      <a href="<?= htmlspecialchars($tool['href']) ?>" class="meny__kort">
        <?php if (!empty($tool['icon'])): ?>
          <div class="meny__ikon"><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i></div>
        <?php endif; ?>
        <div class="meny__text"><?= htmlspecialchars($tool['title']) ?></div>
        <?php if (!empty($tool['desc'])): ?>
          <div class="meny__beskrivning"><?= htmlspecialchars($tool['desc']) ?></div>
        <?php elseif (isset($toolDescriptions[$tool['title']])): ?>
          <div class="meny__beskrivning"><?= htmlspecialchars($toolDescriptions[$tool['title']]) ?></div>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Information och fördelar -->
  <section style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-top: 3rem; margin-bottom: 2rem;">
    <h2 style="margin-top: 0; color: #495057; text-align: center;">Varför välja Mackan.eu?</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 2rem;">
      <div style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🆓</div>
        <h3 style="color: #007bff; margin-bottom: 0.5rem;">Helt gratis</h3>
        <p style="color: #6c757d; margin: 0;">Alla verktyg är gratis att använda utan begränsningar eller krav på registrering.</p>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🔒</div>
        <h3 style="color: #28a745; margin-bottom: 0.5rem;">Säkert och privat</h3>
        <p style="color: #6c757d; margin: 0;">Inga data lagras på Mackan.eu. Alla beräkningar sker lokalt i din webbläsare.</p>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
        <h3 style="color: #17a2b8; margin-bottom: 0.5rem;">Responsiv design</h3>
        <p style="color: #6c757d; margin: 0;">Fungerar perfekt på alla enheter - dator, tablet och mobil.</p>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
        <h3 style="color: #ffc107; margin-bottom: 0.5rem;">Snabb och effektiv</h3>
        <p style="color: #6c757d; margin: 0;">Optimerade verktyg som ger resultat på sekunder, inte minuter.</p>
      </div>
    </div>
  </section>

  <!-- Populära verktyg -->
  <section style="margin-bottom: 2rem;">
    <h2 style="color: #495057; margin-bottom: 1.5rem;">Mest populära verktyg</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
      
      <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 1rem 0; color: #007bff;">
          <i class="fa-solid fa-compass" style="margin-right: 0.5rem;"></i>
          <a href="/tools/koordinat/" style="color: inherit; text-decoration: none;">Koordinatkonverterare</a>
        </h3>
        <p style="color: #6c757d; margin-bottom: 1rem; line-height: 1.5;">
          Konvertera mellan WGS84, SWEREF99 och RT90. Stöder batch-import, kartvisning och CSV-export. 
          Perfekt för GIS-arbete och lantmäteri.
        </p>
        <a href="/tools/koordinat/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Använd verktyg</a>
      </div>

      <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 1rem 0; color: #007bff;">
          <i class="fa-solid fa-qrcode" style="margin-right: 0.5rem;"></i>
          <a href="/tools/qr_v2/" style="color: inherit; text-decoration: none;">QR-kodgenerator</a>
        </h3>
        <p style="color: #6c757d; margin-bottom: 1rem; line-height: 1.5;">
          Skapa anpassade QR-koder med logo, färger och olika format. 
          Perfekt för marknadsföring och informationsdelning.
        </p>
        <a href="/tools/qr_v2/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Använd verktyg</a>
      </div>

      <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 1rem 0; color: #007bff;">
          <i class="fa-solid fa-key" style="margin-right: 0.5rem;"></i>
          <a href="/tools/passwordgenerator/" style="color: inherit; text-decoration: none;">Lösenordsgenerator</a>
        </h3>
        <p style="color: #6c757d; margin-bottom: 1rem; line-height: 1.5;">
          Generera säkra lösenord med anpassningsbara kriterier. 
          Inkluderar styrkeanalys och inga data lagras.
        </p>
        <a href="/tools/passwordgenerator/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Använd verktyg</a>
      </div>

    </div>
  </section>

  <!-- FAQ för SEO -->
  <section style="margin-bottom: 2rem;">
    <h2 style="color: #495057; margin-bottom: 1.5rem;">Vanliga frågor</h2>
    <div style="max-width: 800px;">
      
      <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white;">
        <summary style="cursor: pointer; font-weight: 600; color: #007bff; outline: none;">
          Kostar det något att använda verktygen?
        </summary>
        <p style="margin-top: 1rem; margin-bottom: 0; color: #495057; line-height: 1.5;">
          Nej, alla verktyg på Mackan.eu är helt gratis att använda och kräver ingen registrering eller betalning. 
          Plattformen finansieras inte genom reklam eller dataförsäljning.
        </p>
      </details>

      <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white;">
        <summary style="cursor: pointer; font-weight: 600; color: #007bff; outline: none;">
          Sparas mina data någonstans?
        </summary>
        <p style="margin-top: 1rem; margin-bottom: 0; color: #495057; line-height: 1.5;">
          Nej, alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter eller 
          känsliga data skickas till Mackan.eu. Plattformen är GDPR-kompatibel genom design.
        </p>
      </details>

      <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white;">
        <summary style="cursor: pointer; font-weight: 600; color: #007bff; outline: none;">
          Vilka koordinatsystem stöds i koordinatverktyget?
        </summary>
        <p style="margin-top: 1rem; margin-bottom: 0; color: #495057; line-height: 1.5;">
          Verktyget stöder WGS84 (GPS), SWEREF99 (svenska referenssystemet) och RT90 (äldre svenska systemet) 
          med alla vanliga zoner. Perfekt för GIS-arbete och lantmäteri.
        </p>
      </details>

      <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white;">
        <summary style="cursor: pointer; font-weight: 600; color: #007bff; outline: none;">
          Kan jag använda verktygen offline?
        </summary>
        <p style="margin-top: 1rem; margin-bottom: 0; color: #495057; line-height: 1.5;">
          De flesta verktyg fungerar offline efter första laddningen, förutom de som behöver externa data 
          som kartunderlag eller API-anrop. Perfekt för fältarbete.
        </p>
      </details>

      <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 6px; background: white;">
        <summary style="cursor: pointer; font-weight: 600; color: #007bff; outline: none;">
          Stöds batch-import i verktygen?
        </summary>
        <p style="margin-top: 1rem; margin-bottom: 0; color: #495057; line-height: 1.5;">
          Ja, flera verktyg som koordinatkonverteraren stöder batch-import via CSV eller text. 
          Du kan bearbeta stora datamängder effektivt.
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

<style>
/* Förbättra hover-effekter för meny-korten */
.meny__kort:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Förbättra styling för FAQ-sektionen */
details[open] summary {
    margin-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.5rem;
}

details summary:hover {
    color: #0056b3;
}

/* Responsiv design för populära verktyg */
@media (max-width: 768px) {
    .layout__container section > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
}

/* Förbättra läsbarhet */
.lead {
    font-weight: 400;
    letter-spacing: 0.3px;
}

/* Subtle animationer för bättre UX */
.meny__kort, details {
    transition: all 0.2s ease;
}
</style>

<?php include '../includes/layout-end.php'; ?>
