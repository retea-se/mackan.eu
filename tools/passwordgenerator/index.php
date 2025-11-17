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
<?php include '../../includes/tool-layout-start.php'; ?>

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
        <button type="button" id="previewCopy" class="knapp__ikon" aria-label="Kopiera lösenord" data-tippy-content="<?= $t['tippy_copy'] ?>">
          <i class="fa-solid fa-copy" aria-hidden="true"></i>
        </button>
        <button type="button" id="previewRefresh" class="knapp__ikon" aria-label="Generera nytt lösenord" data-tippy-content="<?= $t['tippy_refresh'] ?>">
          <i class="fa-solid fa-rotate" aria-hidden="true"></i>
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

  <!-- Vanliga frågor -->
  <section class="layout__sektion faq">
    <h2 class="faq__rubrik">Vanliga frågor</h2>
    <ul class="faq__lista">
      <li class="faq__item">
        <h3 class="faq__fraga">Hur skapar jag ett starkt lösenord?</h3>
        <div class="faq__svar">
          <p>Ett starkt lösenord bör vara minst 12 tecken långt och innehålla en kombination av gemener, versaler, siffror och symboler. Använd verktyget för att generera säkra lösenord automatiskt baserat på dina valda inställningar. Ju längre och mer varierat lösenordet är, desto säkrare blir det.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Vad betyder olika teckentyper?</h3>
        <div class="faq__svar">
          <p>Gemener är små bokstäver (a-z), versaler är stora bokstäver (A-Z), siffror är 0-9, och symboler är specialtecken som !@#$%. Genom att kombinera flera teckentyper ökar du lösenordets komplexitet och gör det svårare att knäcka. Du kan välja vilka teckentyper som ska ingå i ditt genererade lösenord.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Är lösenorden säkra att använda?</h3>
        <div class="faq__svar">
          <p>Ja, lösenorden genereras direkt i din webbläsare med kryptografiskt säker slumpgenerering. Alla beräkningar sker lokalt på din enhet och ingen information skickas till någon server. Detta gör verktyget säkert för att skapa lösenord även för känsliga system.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Sparas lösenorden någonstans?</h3>
        <div class="faq__svar">
          <p>Nej, verktyget sparar inte några lösenord. All generering sker lokalt i din webbläsare utan någon kommunikation med externa servrar. När du stänger sidan försvinner lösenorden helt, så se till att spara dem i en lösenordshanterare innan du lämnar sidan.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Hur många lösenord kan jag generera?</h3>
        <div class="faq__svar">
          <p>Du kan generera upp till 100 lösenord samtidigt genom att ange önskat antal i formuläret. Detta är praktiskt när du behöver skapa många unika lösenord på en gång, till exempel för flera användarkonton eller system. Varje lösenord genereras individuellt och är unikt.</p>
        </div>
      </li>
      <li class="faq__item">
        <h3 class="faq__fraga">Varför ska man använda olika lösenord?</h3>
        <div class="faq__svar">
          <p>Att återanvända samma lösenord på flera tjänster innebär att om en tjänst komprometteras kan alla dina konton vara i fara. Genom att använda unika lösenord för varje tjänst begränsar du skadan vid ett eventuellt dataintrång. En lösenordshanterare kan hjälpa dig att hålla koll på alla dina unika lösenord.</p>
        </div>
      </li>
    </ul>
  </section>

</main>

<div id="toast" class="toast"></div>

<script src="script.js"></script>
<script src="preview.js"></script>
<script src="passphrase.js"></script>
<script src="export.js"></script>
<script src="/js/faq.js"></script>
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
<?php include '../../includes/tool-layout-end.php'; ?>
