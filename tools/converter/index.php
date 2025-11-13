<!-- tools/converter/index.php - v4 -->
<?php $title = 'Data Converter'; ?>
<?php $metaDescription = 'Konvertera mellan olika dataformat som CSV, JSON, YAML och XML'; ?>
<?php include '../../includes/layout-start.php'; ?>

<!-- ********** START: Converter Interface ********** -->
<div class="converter-container">
  <div class="converter-header">
    <h1>🔄 Data Converter</h1>
    <p>Konvertera mellan olika dataformat snabbt och enkelt</p>
  </div>

  <div class="converter-tabs">
    <div class="converter-tab active" data-tab="csvtojson" data-tippy-content="Konvertera CSV till JSON">
      <span class="tab-icon">📊</span>
      <span class="tab-title">CSV → JSON</span>
    </div>
    <div class="converter-tab" data-tab="formatter" data-tippy-content="Formatera JSON">
      <span class="tab-icon">✨</span>
      <span class="tab-title">Formatter</span>
    </div>
    <div class="converter-tab" data-tab="validator" data-tippy-content="Validera JSON">
      <span class="tab-icon">✅</span>
      <span class="tab-title">Validera</span>
    </div>
    <div class="converter-tab" data-tab="fixer" data-tippy-content="Reparera JSON">
      <span class="tab-icon">🔧</span>
      <span class="tab-title">Reparera</span>
    </div>
    <div class="converter-tab" data-tab="utilities" data-tippy-content="Småverktyg">
      <span class="tab-icon">🛠️</span>
      <span class="tab-title">Verktyg</span>
    </div>
    <div class="converter-tab" data-tab="converter" data-tippy-content="Konvertera JSON ↔ CSV/YAML/XML">
      <span class="tab-icon">🔄</span>
      <span class="tab-title">Konvertera</span>
    </div>
  </div>

  <div class="converter-content">
    <section id="tab-csvtojson" class="tab-section">
      <h2>📊 CSV till JSON</h2>
      <!-- Kommer fyllas av csvtojson.js -->
    </section>

    <section id="tab-formatter" class="tab-section hidden">
      <h2>✨ JSON Formatter</h2>
      <!-- Kommer fyllas av formatter.js -->
    </section>

    <section id="tab-validator" class="tab-section hidden">
      <h2>✅ Validera JSON</h2>
      <!-- Kommer fyllas av validator.js -->
    </section>

    <section id="tab-fixer" class="tab-section hidden">
      <h2>🔧 Reparera JSON</h2>
      <!-- Kommer fyllas av fixer.js -->
    </section>

    <section id="tab-utilities" class="tab-section hidden">
      <h2>🛠️ Småverktyg</h2>
      <!-- Kommer fyllas av utilities.js -->
    </section>

    <section id="tab-converter" class="tab-section hidden">
      <h2>🔄 Konvertera JSON ↔ CSV/YAML/XML</h2>
      <div id="uploadContainer"></div>
    </section>
  </div>
</div>
<!-- ********** SLUT: Converter Interface ********** -->


<script type="module" src="script.js"></script>

<?php include '../../includes/layout-end.php'; ?>
