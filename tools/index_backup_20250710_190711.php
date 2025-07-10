<?php
// tools/index.php - Översikt över alla verktyg
$title = 'Verktyg - Professionella onlineverktyg för tekniska beräkningar';
$metaDescription = 'Samling av professionella onlineverktyg: koordinatkonvertering, reservkraftverk-kalkylatorer, QR-kod generator, lösenordsgenerator och mer. Gratis att använda.';
$keywords = 'onlineverktyg, koordinatkonvertering, reservkraftverk, QR-kod, lösenordsgenerator, tekniska beräkningar, GIS, kalkylator';
$canonical = 'https://mackan.eu/tools/';

include '../includes/layout-start.php';
?>

<div class="layout__container">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Du är här" style="margin-bottom: 1rem; font-size: 0.9rem; color: #6c757d;">
        <a href="/" style="color: #007bff; text-decoration: none;">🏠 Hem</a> ›
        <span>🔧 Verktyg</span>
    </nav>

    <h1 class="rubrik">Professionella verktyg</h1>
    <p class="lead" style="font-size: 1.2rem; color: #6c757d; margin-bottom: 2rem;">
        Samling av tekniska verktyg för koordinathantering, energiberäkningar och produktivitet.
        Alla verktyg är gratis att använda och kräver ingen registrering.
    </p>

    <!-- Verktygsöversikt -->
    <div class="tools-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 3rem;">

        <!-- Koordinatverktyg -->
        <div class="tool-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 2rem; margin-right: 0.5rem;">🗺️</span>
                <h2 style="margin: 0; color: #007bff;">
                    <a href="/tools/koordinat/" style="color: inherit; text-decoration: none;">Koordinatverktyg</a>
                </h2>
            </div>
            <p style="color: #6c757d; margin-bottom: 1rem;">
                Professionellt verktyg för konvertering mellan WGS84, SWEREF99 och RT90.
                Stöder batch-import, kartvisning och CSV-export.
            </p>
            <div style="margin-bottom: 1rem;">
                <span style="background: #e7f3ff; color: #0066cc; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">GIS</span>
                <span style="background: #e7f3ff; color: #0066cc; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Lantmäteri</span>
                <span style="background: #e7f3ff; color: #0066cc; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Batch-import</span>
            </div>
            <a href="/tools/koordinat/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Öppna verktyg</a>
        </div>

        <!-- RKA-kalkylatorer -->
        <div class="tool-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 2rem; margin-right: 0.5rem;">⚡</span>
                <h2 style="margin: 0; color: #007bff;">
                    <a href="/tools/rka/" style="color: inherit; text-decoration: none;">RKA-kalkylatorer</a>
                </h2>
            </div>
            <p style="color: #6c757d; margin-bottom: 1rem;">
                Avancerade kalkylatorer för dimensionering av reservkraftverk.
                Beräkna tankvolym, bränsleförbrukning och provkörningsschema.
            </p>
            <div style="margin-bottom: 1rem;">
                <span style="background: #fff3cd; color: #856404; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Elkraft</span>
                <span style="background: #fff3cd; color: #856404; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Generator</span>
                <span style="background: #fff3cd; color: #856404; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Miljöanalys</span>
            </div>
            <a href="/tools/rka/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Öppna verktyg</a>
        </div>

        <!-- QR-kodgenerator -->
        <div class="tool-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 2rem; margin-right: 0.5rem;">📱</span>
                <h2 style="margin: 0; color: #007bff;">
                    <a href="/tools/qr_v2/" style="color: inherit; text-decoration: none;">QR-kodgenerator</a>
                </h2>
            </div>
            <p style="color: #6c757d; margin-bottom: 1rem;">
                Skapa anpassade QR-koder med logo, färger och olika format.
                Perfekt för marknadsföring och informationsdelning.
            </p>
            <div style="margin-bottom: 1rem;">
                <span style="background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Anpassning</span>
                <span style="background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Logo</span>
                <span style="background: #d4edda; color: #155724; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Export</span>
            </div>
            <a href="/tools/qr_v2/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Öppna verktyg</a>
        </div>

        <!-- Lösenordsgenerator -->
        <div class="tool-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 2rem; margin-right: 0.5rem;">🔐</span>
                <h2 style="margin: 0; color: #007bff;">
                    <a href="/tools/passwordgenerator/" style="color: inherit; text-decoration: none;">Lösenordsgenerator</a>
                </h2>
            </div>
            <p style="color: #6c757d; margin-bottom: 1rem;">
                Generera säkra lösenord med anpassningsbara kriterier.
                Inkluderar styrkeanalys och inga data lagras.
            </p>
            <div style="margin-bottom: 1rem;">
                <span style="background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Säkerhet</span>
                <span style="background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">GDPR</span>
                <span style="background: #f8d7da; color: #721c24; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Offline</span>
            </div>
            <a href="/tools/passwordgenerator/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Öppna verktyg</a>
        </div>

        <!-- Enhetskonverterare -->
        <div class="tool-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 2rem; margin-right: 0.5rem;">🔄</span>
                <h2 style="margin: 0; color: #007bff;">
                    <a href="/tools/converter/" style="color: inherit; text-decoration: none;">Enhetskonverterare</a>
                </h2>
            </div>
            <p style="color: #6c757d; margin-bottom: 1rem;">
                Konvertera mellan olika måttenheter snabbt och enkelt.
                Stöder längd, vikt, volym, temperatur och mer.
            </p>
            <div style="margin-bottom: 1rem;">
                <span style="background: #e2e3e5; color: #495057; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Matematik</span>
                <span style="background: #e2e3e5; color: #495057; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Enheter</span>
                <span style="background: #e2e3e5; color: #495057; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Precision</span>
            </div>
            <a href="/tools/converter/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Öppna verktyg</a>
        </div>

        <!-- PTS-sökverktyg -->
        <div class="tool-card" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 2rem; margin-right: 0.5rem;">📋</span>
                <h2 style="margin: 0; color: #007bff;">
                    <a href="/tools/pts/" style="color: inherit; text-decoration: none;">PTS-sökverktyg</a>
                </h2>
            </div>
            <p style="color: #6c757d; margin-bottom: 1rem;">
                Sök i Post- och telestyrelsens register för frekvenstillstånd,
                radiosändare och telekommunikation.
            </p>
            <div style="margin-bottom: 1rem;">
                <span style="background: #d1ecf1; color: #0c5460; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Radio</span>
                <span style="background: #d1ecf1; color: #0c5460; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; margin-right: 0.5rem;">Frekvens</span>
                <span style="background: #d1ecf1; color: #0c5460; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">PTS</span>
            </div>
            <a href="/tools/pts/" style="color: #007bff; text-decoration: none; font-weight: 600;">→ Öppna verktyg</a>
        </div>

    </div>

    <!-- Information och FAQ -->
    <section style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <h2 style="margin-top: 0; color: #495057;">Om våra verktyg</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div>
                <h3 style="color: #007bff;">🆓 Helt gratis</h3>
                <p>Alla verktyg är gratis att använda utan begränsningar eller krav på registrering.</p>
            </div>
            <div>
                <h3 style="color: #28a745;">🔒 Säkert och privat</h3>
                <p>Inga data lagras på våra servrar. Alla beräkningar sker lokalt i din webbläsare.</p>
            </div>
            <div>
                <h3 style="color: #17a2b8;">📱 Responsiv design</h3>
                <p>Fungerar perfekt på alla enheter - dator, tablet och mobil.</p>
            </div>
        </div>
    </section>

    <!-- FAQ för SEO -->
    <section style="margin-bottom: 2rem;">
        <h2>Vanliga frågor</h2>
        <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px;">
            <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Kostar det något att använda verktygen?</summary>
            <p style="margin-top: 1rem; margin-bottom: 0;">Nej, alla verktyg på Mackan.eu är helt gratis att använda och kräver ingen registrering eller betalning.</p>
        </details>

        <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px;">
            <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Sparas mina data någonstans?</summary>
            <p style="margin-top: 1rem; margin-bottom: 0;">Nej, alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter eller data skickas till våra servrar.</p>
        </details>

        <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px;">
            <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Vilka koordinatsystem stöds i koordinatverktyget?</summary>
            <p style="margin-top: 1rem; margin-bottom: 0;">Verktyget stöder WGS84 (GPS), SWEREF99 (svenska referenssystemet) och RT90 (äldre svenska systemet) med alla vanliga zoner.</p>
        </details>

        <details style="margin-bottom: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px;">
            <summary style="cursor: pointer; font-weight: 600; color: #007bff;">Kan jag använda verktygen offline?</summary>
            <p style="margin-top: 1rem; margin-bottom: 0;">De flesta verktyg fungerar offline efter första laddningen, förutom de som behöver externa data som kartunderlag eller API-anrop.</p>
        </details>
    </section>

</div>

<!-- Strukturerad data för verktygsöversikt -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Professionella onlineverktyg",
  "description": "Samling av tekniska verktyg för koordinathantering, energiberäkningar och produktivitet",
  "url": "https://mackan.eu/tools/",
  "mainEntity": {
    "@type": "ItemList",
    "itemListElement": [
      {
        "@type": "SoftwareApplication",
        "position": 1,
        "name": "Koordinatkonverterare",
        "description": "Konvertera mellan WGS84, SWEREF99 och RT90",
        "url": "https://mackan.eu/tools/koordinat/",
        "applicationCategory": "UtilityApplication"
      },
      {
        "@type": "SoftwareApplication",
        "position": 2,
        "name": "RKA-kalkylatorer",
        "description": "Dimensionera reservkraftverk och beräkna bränsleförbrukning",
        "url": "https://mackan.eu/tools/rka/",
        "applicationCategory": "UtilityApplication"
      },
      {
        "@type": "SoftwareApplication",
        "position": 3,
        "name": "QR-kodgenerator",
        "description": "Skapa anpassade QR-koder med logo och färger",
        "url": "https://mackan.eu/tools/qr_v2/",
        "applicationCategory": "UtilityApplication"
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
        "text": "Nej, alla verktyg på Mackan.eu är helt gratis att använda och kräver ingen registrering eller betalning."
      }
    },
    {
      "@type": "Question",
      "name": "Sparas mina data någonstans?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Nej, alla beräkningar och konverteringar sker lokalt i din webbläsare. Inga personuppgifter eller data skickas till våra servrar."
      }
    },
    {
      "@type": "Question",
      "name": "Vilka koordinatsystem stöds i koordinatverktyget?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Verktyget stöder WGS84 (GPS), SWEREF99 (svenska referenssystemet) och RT90 (äldre svenska systemet) med alla vanliga zoner."
      }
    }
  ]
}
</script>

<style>
.tool-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.breadcrumbs a:hover {
    text-decoration: underline;
}

details[open] summary {
    margin-bottom: 1rem;
}

.lead {
    line-height: 1.6;
}

@media (max-width: 768px) {
    .tools-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>

<?php include '../includes/layout-end.php'; ?>
