<!-- index.php - v10 -->
<?php
include 'lang.php';
$lang = $_GET['lang'] ?? 'sv';
if (!isset($langs[$lang])) $lang = 'sv';
$t = $langs[$lang];
$title = $t['title'];
$metaDescription = $t['metaDescription'];
?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <!-- ********** Språkval ********** -->
  <div style="text-align:right;">
    <a href="?lang=sv">Svenska</a> | <a href="?lang=en">English</a>
  </div>

  <!-- ********** Förhandslösenord högst upp ********** -->
  <section class="losenord__sektion" style="margin-top:2rem;">
    <div class="losenord__rubrik" style="display:flex;align-items:center;gap:1rem;">
      <span id="previewPassword" class="losenord__text" style="font-size:1.3rem;"></span>
      <button type="button" id="previewCopy" class="knapp__ikon" aria-label="<?= $t['copy'] ?>" data-tippy-content="<?= $t['tippy_copy'] ?>">
        <i class="fa-solid fa-copy"></i>
      </button>
      <button type="button" id="previewRefresh" class="knapp__ikon" aria-label="<?= $t['refresh'] ?>" data-tippy-content="<?= $t['tippy_refresh'] ?>">
        <i class="fa-solid fa-rotate"></i>
      </button>
      <span id="previewStrength"></span>
    </div>
  </section>

  <!-- ********** Formulär ********** -->
  <form id="generatorForm" class="form__grupp" style="margin-top:2rem;">
    <label for="length"><?= $t['length'] ?></label>
    <input type="number" id="length" class="falt__input" min="4" max="128" value="20">

    <label for="amount"><?= $t['amount'] ?></label>
    <input type="number" id="amount" class="falt__input" min="1" max="100" value="5">

    <label><input type="checkbox" id="useLower" checked> <?= $t['lower'] ?></label>
    <label><input type="checkbox" id="useUpper" checked> <?= $t['upper'] ?></label>
    <label><input type="checkbox" id="useNumbers" checked> <?= $t['numbers'] ?></label>
    <label><input type="checkbox" id="useSymbols" checked> <?= $t['symbols'] ?></label>
    <label><input type="checkbox" id="usePassphrase"> <?= $t['passphrase'] ?></label>

    <button type="submit" class="knapp"><?= $t['generate'] ?></button>
  </form>

  <!-- ********** Resultattabell ********** -->
  <div class="tabell__wrapper utils--dold" id="resultWrapper">
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
  <div class="knapp__grupp utils--dold" id="resultButtons">
    <button id="exportBtn" class="knapp" data-tippy-content="<?= $t['export'] ?>"><?= $t['tippy_export'] ?></button>
    <button id="resetBtn" class="knapp" data-tippy-content="<?= $t['reset'] ?>"><?= $t['tippy_reset'] ?></button>
  </div>

</main>

<div id="toast" class="toast"></div>

<script src="script.js"></script>
<script src="preview.js"></script>
<script src="passphrase.js"></script>
<script src="export.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.tippy) {
      tippy('[data-tippy-content]', { theme: 'light', delay: [100, 0] });
    }

    if (window.generatePreviewPassword) {
      generatePreviewPassword();
    }

    document.getElementById('previewCopy').addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const pw = document.getElementById('previewPassword').textContent.trim();
      navigator.clipboard.writeText(pw);
      showToast(t_copied);
    });

    document.getElementById('previewRefresh').addEventListener('click', function(e) {
      e.preventDefault();
      if (window.generatePreviewPassword) {
        generatePreviewPassword();
      }
    });
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
