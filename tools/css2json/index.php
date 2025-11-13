<?php

// tools/css2json/index.php - v4
$title = 'CSS till JSON';
$metaDescription = 'Konvertera en eller flera CSS-filer till JSON-format direkt i webbläsaren.';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      Ladda upp en eller flera CSS-filer och få en JSON-representation som kan laddas ned
      eller kopieras vidare i ditt projekt.
    </p>
  </header>

  <section class="layout__sektion">
    <form class="form" aria-label="CSS till JSON-formulär" id="cssForm" novalidate>
      <div class="form__grupp">
        <label for="cssFiles" class="falt__etikett">Välj CSS-fil(er)</label>
        <input type="file" id="cssFiles" class="falt__input" multiple accept=".css" aria-label="Ladda upp en eller flera CSS-filer" data-tippy-content="Välj en eller flera CSS-filer att konvertera till JSON">
        <p class="falt__hjälp">Filerna bearbetas helt i din webbläsare.</p>
      </div>
      <div class="form__verktyg">
        <button type="button" class="knapp" id="convertBtn" data-tippy-content="Konvertera valda CSS-filer till JSON">Konvertera</button>
        <button type="button" class="knapp knapp--liten hidden" id="downloadBtn" data-tippy-content="Ladda ner resultatet som JSON-fil" aria-label="Ladda ner JSON" disabled>Ladda ner JSON</button>
        <button type="button" class="knapp knapp--liten hidden" id="resetBtn" data-tippy-content="Rensa uppladdade filer och resultat">Rensa</button>
      </div>
    </form>
  </section>

  <section class="layout__sektion hidden" id="resultSection">
    <article class="kort">
      <h2 class="kort__rubrik">JSON-resultat</h2>
      <pre id="output" class="kort__innehall" aria-label="JSON-resultat"></pre>
    </article>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="script.js" defer></script>
