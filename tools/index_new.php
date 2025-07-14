<?php
// tools/index.php - v3 - Återanvänder design från huvudsidan med maximal SEO
$title = 'Professionella tekniska verktyg - Koordinatkonvertering, reservkraftverk och mer';
$metaDescription = 'Samling av professionella onlineverktyg för ingenjörer och konsulter: koordinatkonvertering WGS84/SWEREF99/RT90, reservkraftverk-kalkylatorer, QR-kod generator, lösenordsgenerator. Gratis, säkra och GDPR-kompatibla verktyg.';
$keywords = 'professionella verktyg, koordinatkonvertering, WGS84, SWEREF99, RT90, reservkraftverk, RKA kalkylator, QR-kod generator, lösenordsgenerator, tekniska beräkningar, GIS verktyg, ingenjörsverktyg';
$canonical = 'https://mackan.eu/tools/';

// Strukturerad data för verktyg (använder samma design som huvudsidan)
$toolsData = [
  [
    'title' => 'Koordinatkonverterare',
    'href'  => '/tools/koordinat/',
    'icon'  => 'fa-map',
    'desc'  => 'Professionell konvertering mellan WGS84, SWEREF99 och RT90. Stöder batch-import, kartvisning och CSV-export.'
  ],
  [
    'title' => 'RKA-kalkylatorer',
    'href'  => '/tools/rka/',
    'icon'  => 'fa-bolt',
    'desc'  => 'Dimensionera reservkraftverk professionellt. Beräkna tankvolym, bränsleförbrukning och miljöpåverkan.'
  ],
  [
    'title' => 'QR-kodgenerator',
    'href'  => '/tools/qr_v2/',
    'icon'  => 'fa-qrcode',
    'desc'  => 'Skapa anpassade QR-koder med logo, färger och olika format. WiFi, kontakt, text och mer.'
  ],
  [
    'title' => 'Lösenordsgenerator',
    'href'  => '/tools/passwordgenerator/',
    'icon'  => 'fa-lock',
    'desc'  => 'Generera säkra lösenord med anpassningsbara kriterier. GDPR-säkert, inga data sparas.'
  ],
  [
    'title' => 'Enhetskonverterare',
    'href'  => '/tools/converter/',
    'icon'  => 'fa-exchange-alt',
    'desc'  => 'Konvertera mellan olika måttenheter: längd, vikt, volym, temperatur och mer.'
  ],
  [
    'title' => 'PTS-sökverktyg',
    'href'  => '/tools/pts/',
    'icon'  => 'fa-search',
    'desc'  => 'Sök i Post- och telestyrelsens register för frekvenstillstånd och radiosändare.'
  ],
  [
    'title' => 'CSV till JSON',
    'href'  => '/tools/csv2json/',
    'icon'  => 'fa-file-code',
    'desc'  => 'Konvertera CSV-filer till JSON-format snabbt och säkert för utveckling.'
  ],
  [
    'title' => 'CSS till JSON',
    'href'  => '/tools/css2json/',
    'icon'  => 'fa-code',
    'desc'  => 'Extrahera CSS-regler och konvertera till JSON-struktur för automatisering.'
  ],
  [
    'title' => 'Testdata-generator',
    'href'  => '/tools/testdata/',
    'icon'  => 'fa-database',
    'desc'  => 'Generera realistisk testdata för utveckling och testning av applikationer.'
  ]
];

include '../includes/layout-start.php';
?>

<main class="layout__container">
  <!-- Breadcrumbs -->
  <nav class="breadcrumbs" aria-label="Du är här" style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">
    <a href="/" style="color: #007bff; text-decoration: none;">🏠 Hem</a> ›
    <span>🔧 Verktyg</span>
  </nav>

  <!-- Använder samma design som huvudsidan -->
  <p class="rubrik rubrik--sektion mb-2">
    Professionella verktyg för tekniska beräkningar, koordinathantering och produktivitet. Specialiserade för ingenjörer, GIS-experter och konsulter. Alla verktyg är gratis, säkra och GDPR-kompatibla.
  </p>

  <div class="meny">
    <?php foreach ($toolsData as $tool): ?>
      <a href="<?= htmlspecialchars($tool['href']) ?>" class="meny__kort">
        <?php if (!empty($tool['icon'])): ?>
          <div class="meny__ikon"><i class="fa-solid <?= htmlspecialchars($tool['icon']) ?>"></i></div>
        <?php endif; ?>
        <div class="meny__text"><?= htmlspecialchars($tool['title']) ?></div>
        <?php if (!empty($tool['desc'])): ?>
          <div class="meny__beskrivning"><?= htmlspecialchars($tool['desc']) ?></div>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- SEO-optimerad informationssektion -->
  <section style="margin-top: 3rem; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
    <h2 style="margin-top: 0; color: #495057; font-size: 1.5rem;">Tekniska verktyg för professionella</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
      <div>
        <h3 style="color: #007bff; margin-bottom: 0.5rem;">🎯 Koordinatsystem & GIS</h3>
        <ul style="margin: 0; padding-left: 1.2rem; line-height: 1.6;">
          <li><strong>WGS84:</strong> GPS-koordinater, globalt system</li>
          <li><strong>SWEREF99:</strong> Svenska referenssystemet, officiellt</li>
          <li><strong>RT90:</strong> Äldre svenska systemet, fortfarande används</li>
          <li><strong>Batch-import:</strong> CSV, Excel och textfiler</li>
        </ul>
      </div>

      <div>
        <h3 style="color: #28a745; margin-bottom: 0.5rem;">⚡ Elkraft & energi</h3>
        <ul style="margin: 0; padding-left: 1.2rem; line-height: 1.6;">
          <li><strong>Reservkraftverk:</strong> Dimensionering enligt standards</li>
          <li><strong>Tankberäkning:</strong> Ullage, sump och säkerhetsmarginaler</li>
          <li><strong>Bränsletyper:</strong> Diesel, HVO100, EcoPar</li>
          <li><strong>Miljöanalys:</strong> CO₂-utsläpp och kostnadskalkyl</li>
        </ul>
      </div>

      <div>
        <h3 style="color: #17a2b8; margin-bottom: 0.5rem;">🔒 Säkerhet & kvalitet</h3>
        <ul style="margin: 0; padding-left: 1.2rem; line-height: 1.6;">
          <li><strong>GDPR-kompatibelt:</strong> Inga data lagras på servrar</li>
          <li><strong>Offline-funktionalitet:</strong> Fungerar utan internetanslutning</li>
          <li><strong>Öppen källkod:</strong> Transparent och verifierbar</li>
          <li><strong>Professionell kvalitet:</strong> Enligt branschstandarder</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- FAQ för maximal SEO -->
  <section style="margin-top: 2rem;">
    <h2 style="color: #495057; font-size: 1.5rem;">Vanliga frågor om våra tekniska verktyg</h2>

    <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Vilka koordinatsystem stöds för professionell GIS-arbete?</summary>
      <p style="margin-top: 1rem; margin-bottom: 0;">Vårt koordinatverktyg stöder WGS84 (GPS-koordinater), SWEREF99 (svenska referenssystemet med alla zoner) och RT90 (äldre svenska systemet). Verktyget klarar batch-import av tusentals koordinater och exporterar till CSV, Excel och GeoJSON-format.</p>
    </details>

    <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Hur beräknas tankvolym för reservkraftverk enligt branschstandard?</summary>
      <p style="margin-top: 1rem; margin-bottom: 0;">RKA-kalkylatorn följer NFPA 110 och svenska standards. Beräkningen inkluderar ullage (10%), sump (5%), provkörning, buffertdagar och klimatjustering (derating). Verktyget stöder diesel, HVO100 och EcoPar med korrekta energidensiteter.</p>
    </details>

    <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Är verktygen säkra för känslig företagsinformation?</summary>
      <p style="margin-top: 1rem; margin-bottom: 0;">Ja, alla beräkningar sker lokalt i din webbläsare enligt "Privacy by Design". Inga data skickas till våra servrar, vilket garanterar fullständig GDPR-efterlevnad och företagssäkerhet. Verktygen fungerar även offline efter första laddningen.</p>
    </details>

    <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Vilka filformat stöds för import och export?</summary>
      <p style="margin-top: 1rem; margin-bottom: 0;">Koordinatverktyget: CSV, Excel, textfiler, GeoJSON. QR-generator: PNG, SVG, PDF med anpassad upplösning. RKA-verktyg: Excel-export med detaljerade beräkningar. Lösenordsgenerator: Textfil för batch-generering av lösenord.</p>
    </details>

    <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Kan verktygen användas för kommersiella konsultuppdrag?</summary>
      <p style="margin-top: 1rem; margin-bottom: 0;">Ja, alla verktyg är fria att använda för kommersiella ändamål utan licensavgifter. Vi rekommenderar att kritiska beräkningar verifieras mot branschstandarder och att backup-beräkningar görs för viktiga projekt.</p>
    </details>

    <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white;">
      <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Hur noggranna är koordinatkonverteringar för lantmäteriarbete?</summary>
      <p style="margin-top: 1rem; margin-bottom: 0;">Koordinatkonverteringar använder officiella transformationsparametrar från Lantmäteriet med submeter-noggrannhet. För högprecisionsarbete rekommenderar vi kontroll mot Lantmäteriets egna tjänster eller RTK-mätningar.</p>
    </details>
  </section>

</main>

<!-- Maximal SEO - Strukturerad data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Professionella tekniska verktyg",
  "description": "Samling av professionella onlineverktyg för tekniska beräkningar, koordinathantering och produktivitet",
  "url": "https://mackan.eu/tools/",
  "mainEntity": {
    "@type": "ItemList",
    "name": "Tekniska verktyg för professionella",
    "description": "Specialiserade verktyg för ingenjörer, GIS-experter och tekniska konsulter",
    "itemListElement": [
      {
        "@type": "SoftwareApplication",
        "position": 1,
        "name": "Koordinatkonverterare",
        "description": "Professionell konvertering mellan WGS84, SWEREF99 och RT90 koordinatsystem",
        "url": "https://mackan.eu/tools/koordinat/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        },
        "featureList": ["WGS84", "SWEREF99", "RT90", "Batch-import", "Kartvisning", "CSV-export", "GeoJSON"],
        "audience": {
          "@type": "Audience",
          "audienceType": "GIS specialists, Surveyors, Engineers"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 2,
        "name": "RKA-kalkylatorer",
        "description": "Dimensionera reservkraftverk och beräkna bränsleförbrukning enligt branschstandard",
        "url": "https://mackan.eu/tools/rka/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        },
        "featureList": ["Tankdimensionering", "Bränsleberäkning", "Miljöanalys", "NFPA 110", "Derating", "CO2-kalkyl"],
        "audience": {
          "@type": "Audience",
          "audienceType": "Electrical engineers, Power consultants"
        }
      },
      {
        "@type": "SoftwareApplication",
        "position": 3,
        "name": "QR-kodgenerator",
        "description": "Skapa anpassade QR-koder med logo och färger för professionell användning",
        "url": "https://mackan.eu/tools/qr_v2/",
        "applicationCategory": "UtilityApplication",
        "operatingSystem": "Web Browser",
        "offers": {
          "@type": "Offer",
          "price": "0",
          "priceCurrency": "SEK"
        },
        "featureList": ["Anpassad design", "Logo-integration", "PNG/SVG/PDF export", "WiFi QR", "vCard"],
        "audience": {
          "@type": "Audience",
          "audienceType": "Marketing professionals, Developers"
        }
      }
    ]
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
        "name": "Professionella verktyg",
        "item": "https://mackan.eu/tools/"
      }
    ]
  }
}
</script>

<!-- FAQ strukturerad data för Featured Snippets -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Vilka koordinatsystem stöds för professionell GIS-arbete?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Vårt koordinatverktyg stöder WGS84 (GPS-koordinater), SWEREF99 (svenska referenssystemet med alla zoner) och RT90 (äldre svenska systemet). Verktyget klarar batch-import av tusentals koordinater och exporterar till CSV, Excel och GeoJSON-format."
      }
    },
    {
      "@type": "Question",
      "name": "Hur beräknas tankvolym för reservkraftverk enligt branschstandard?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "RKA-kalkylatorn följer NFPA 110 och svenska standards. Beräkningen inkluderar ullage (10%), sump (5%), provkörning, buffertdagar och klimatjustering (derating). Verktyget stöder diesel, HVO100 och EcoPar med korrekta energidensiteter."
      }
    },
    {
      "@type": "Question",
      "name": "Är verktygen säkra för känslig företagsinformation?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ja, alla beräkningar sker lokalt i din webbläsare enligt Privacy by Design. Inga data skickas till våra servrar, vilket garanterar fullständig GDPR-efterlevnad och företagssäkerhet."
      }
    },
    {
      "@type": "Question",
      "name": "Kan verktygen användas för kommersiella konsultuppdrag?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ja, alla verktyg är fria att använda för kommersiella ändamål utan licensavgifter. Vi rekommenderar att kritiska beräkningar verifieras mot branschstandarder."
      }
    }
  ]
}
</script>

<!-- WebSite strukturerad data för sitelinks -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Mackan.eu - Professionella tekniska verktyg",
  "url": "https://mackan.eu",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://mackan.eu/tools/?q={search_term_string}",
    "query-input": "required name=search_term_string"
  },
  "sameAs": [
    "https://github.com/mackan-eu"
  ],
  "audience": {
    "@type": "Audience",
    "audienceType": "Engineers, GIS professionals, Technical consultants, Electrical engineers"
  }
}
</script>

<?php include '../includes/layout-end.php'; ?>
