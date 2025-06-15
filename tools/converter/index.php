<!-- tools/converter/index.php - v3 -->
<?php $title = 'Converter'; ?>
<?php include '../../includes/layout-start.php'; ?>

<!-- ********** START: Verktygsmeny ********** -->
<div class="layout__container">
  <div class="form__verktyg knapp__grupp">
    <button class="knapp knapp--liten" data-tab="csvtojson" data-tippy-content="Konvertera CSV till JSON">CSV → JSON</button>
    <button class="knapp knapp--liten" data-tab="formatter" data-tippy-content="Formatera JSON">Formatter</button>
    <button class="knapp knapp--liten" data-tab="validator" data-tippy-content="Validera JSON">Validera</button>
    <button class="knapp knapp--liten" data-tab="fixer" data-tippy-content="Reparera JSON">Reparera</button>
    <button class="knapp knapp--liten" data-tab="utilities" data-tippy-content="Småverktyg">Verktyg</button>
    <button class="knapp knapp--liten" data-tab="converter" data-tippy-content="Konvertera JSON ↔ CSV/YAML/XML">Konvertera</button>
  </div>
</div>
<!-- ********** SLUT: Verktygsmeny ********** -->

<!-- ********** START: Tabbinnehåll ********** -->
<div class="layout__container">
  <section id="tab-csvtojson" class="tab-section">
    <h2>CSV till JSON</h2>
    <!-- Kommer fyllas av csvtojson.js -->
  </section>

  <section id="tab-formatter" class="tab-section hidden">
    <h2>Formatter</h2>
    <!-- Kommer fyllas av formatter.js -->
  </section>

  <section id="tab-validator" class="tab-section hidden">
    <h2>Validera JSON</h2>
  </section>

  <section id="tab-fixer" class="tab-section hidden">
    <h2>Reparera JSON</h2>
  </section>

  <section id="tab-utilities" class="tab-section hidden">
    <h2>Småverktyg</h2>
  </section>

  <section id="tab-converter" class="tab-section hidden">
    <h2>Konvertera JSON ↔ CSV/YAML/XML</h2>
    <div id="uploadContainer"></div>
  </section>
</div>
<!-- ********** SLUT: Tabbinnehåll ********** -->

<script type="module" src="script.js"></script>
<script>
  // Tippy-tooltips (Tippy.js är redan inläst)
  tippy('.knapp[data-tippy-content]');
</script>

<?php include '../../includes/layout-end.php'; ?>
