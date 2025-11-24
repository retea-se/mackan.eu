<?php
// tools/skyddad/index.php - v4
// git commit: Starta session innan headers skickas

// Starta session FÖRE tool-layout-start.php (som skickar headers)
require_once __DIR__ . '/includes/bootstrap.php';

$title = 'Skyddad delning';
$metaDescription = 'Dela hemliga meddelanden via engångslänk som självförstörs efter visning.';
$keywords = 'skyddad delning, engångslänk, säker delning, hemliga meddelanden, självförstörande länk';
$canonical = 'https://mackan.eu/tools/skyddad/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Skyddad delning",
  "description": "' . htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') . '",
  "url": "' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '",
  "applicationCategory": "UtilityApplication",
  "operatingSystem": "Web Browser",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "SEK"
  },
  "featureList": [
    "Engångslänk",
    "Självförstörande",
    "Säker delning",
    "Hemliga meddelanden"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>';

include '../../includes/tool-layout-start.php';
require_once 'dela-handler.php';
?>

  <div class="kort">
    <?php if (!empty($result)) echo $result; ?>
    <?php include 'mallar/dela-form.php'; ?>
  </div>
</main>

<?php include '../../includes/tool-layout-end.php'; ?>
