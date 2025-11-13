<!-- index.php - v12 med SEO-förbättringar och JSON-LD -->
<?php
include 'lang.php';
$lang = $_GET['lang'] ?? 'sv';
if (!isset($langs[$lang])) $lang = 'sv';
$t = $langs[$lang];
$title = $t['title'];
$metaDescription = $t['metaDescription'];
$keywords = 'lösenordsgenerator, säkra lösenord, password generator, säkerhet, GDPR, offline lösenord';
$canonical = 'https://mackan.eu/tools/passwordgenerator/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '",
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
    "Generera säkra lösenord",
    "Offline funktion",
    "GDPR-kompatibel",
    "Anpassningsbar längd"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>';
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      <?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>
    </p>
    <div class="text--muted text--small">
      <a href="?lang=sv">Svenska</a> | <a href="?lang=en">English</a>
    </div>
  </header>

  <!-- ********** Förhandslösenord högst upp ********** -->
  <section class="layout__sektion">
    <div class="kort">
      <div class="kort__innehall" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
        <span id="previewPassword" class="losenord__text" style="font-size:1.3rem;flex:1;min-width:200px;"></span>
        <button type="button" id="previewCopy" class="knapp__ikon" aria-label="<?= $t['copy'] ?>" data-tippy-content="<?= $t['tippy_copy'] ?>">
          <i class="fa-solid fa-copy"></i>
        </button>
        <button type="button" id="previewRefresh" class="knapp__ikon" aria-label="<?= $t['refresh'] ?>" data-tippy-content="<?= $t['tippy_refresh'] ?>">
          <i class="fa-solid fa-rotate"></i>
        </button>
        <span id="previewStrength"></span>
      </div>
    </div>
  </section>

  <!-- ********** Formulär ********** -->
  <section class="layout__sektion">
    <form id="generatorForm" class="form" novalidate>
      <div class="form__grupp">
        <label for="length" class="falt__etikett"><?= $t['length'] ?></label>
        <input type="number" id="length" class="falt__input" min="4" max="128" value="20">
      </div>

      <div class="form__grupp">
        <label for="amount" class="falt__etikett"><?= $t['amount'] ?></label>
        <input type="number" id="amount" class="falt__input" min="1" max="100" value="5">
      </div>

      <div class="form__grupp">
        <span class="falt__etikett">Alternativ</span>
        <div class="flex-column">
          <label class="falt__checkbox"><input type="checkbox" id="useLower" checked> <?= $t['lower'] ?></label>
          <label class="falt__checkbox"><input type="checkbox" id="useUpper" checked> <?= $t['upper'] ?></label>
          <label class="falt__checkbox"><input type="checkbox" id="useNumbers" checked> <?= $t['numbers'] ?></label>
          <label class="falt__checkbox"><input type="checkbox" id="useSymbols" checked> <?= $t['symbols'] ?></label>
          <label class="falt__checkbox"><input type="checkbox" id="usePassphrase"> <?= $t['passphrase'] ?></label>
        </div>
      </div>

      <div class="form__verktyg">
        <button type="submit" class="knapp" data-tippy-content="<?= $t['generate'] ?>"><?= $t['generate'] ?></button>
      </div>
    </form>
  </section>

  <!-- ********** Resultattabell ********** -->
  <section class="layout__sektion hidden" id="resultWrapper">
    <div class="tabell__wrapper">
      <table class="tabell" id="resultTable">
        <thead>
          <tr>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <!-- JS genererar rader här -->
        </tbody>
      </table>
    </div>
    <div class="knapp__grupp">
      <button id="exportBtn" class="knapp" data-tippy-content="<?= $t['export'] ?>"><?= $t['tippy_export'] ?></button>
      <button id="resetBtn" class="knapp knapp--liten" data-tippy-content="<?= $t['reset'] ?>"><?= $t['tippy_reset'] ?></button>
    </div>
  </section>

</main>

<div id="toast" class="toast"></div>

<script src="script.js"></script>
<script src="preview.js"></script>
<script src="passphrase.js"></script>
<script src="export.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.generatePreviewPassword) {
      generatePreviewPassword();
    }

    const previewCopy = document.getElementById('previewCopy');
    if (previewCopy) {
      previewCopy.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const pw = document.getElementById('previewPassword').textContent.trim();
        if (pw) {
          navigator.clipboard.writeText(pw);
          showToast(t_copied);
        }
      });
    }

    const previewRefresh = document.getElementById('previewRefresh');
    if (previewRefresh) {
      previewRefresh.addEventListener('click', function(e) {
        e.preventDefault();
        if (window.generatePreviewPassword) {
          generatePreviewPassword();
        }
      });
    }
  });

  const t_copied = "<?= $t['copied'] ?>";
  function showToast(msg) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('toast--synlig');
    setTimeout(() => {
      toast.classList.remove('toast--synlig');
    }, 1800);
  }
</script>
<?php include '../../includes/layout-end.php'; ?>
