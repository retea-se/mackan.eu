<!-- tools/converter/index.php - v2 -->
<?php $title = 'Converter'; ?>
<?php include '../../includes/layout-start.php'; ?>

<!-- ********** START: Verktygsmeny ********** -->
<div class="container">
  <div class="horizontal-tools">
    <button class="button tiny" data-tab="csvtojson">CSV → JSON</button>
    <button class="button tiny" data-tab="formatter">Formatter</button>
    <button class="button tiny" data-tab="validator">Validera</button>
    <button class="button tiny" data-tab="fixer">Reparera</button>
    <button class="button tiny" data-tab="utilities">Verktyg</button>
    <button class="button tiny" data-tab="converter">Konvertera</button>
  </div>
</div>
<!-- ********** SLUT: Verktygsmeny ********** -->

<!-- ********** START: Tabbinnehåll ********** -->
<div class="container">
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

<section id="tab-converter" class="tab-section">
  <h2>Konvertera JSON ↔ CSV/YAML/XML</h2>
  <input type="file" id="testUpload" />
</section>

</div>
<!-- ********** SLUT: Tabbinnehåll ********** -->

<script src="script.js" defer></script>

<?php include '../../includes/layout-end.php'; ?>
