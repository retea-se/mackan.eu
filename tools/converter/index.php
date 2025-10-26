<!-- tools/converter/index.php - v4 -->
<?php $title = 'Data Converter'; ?>
<?php $metaDescription = 'Konvertera mellan olika dataformat som CSV, JSON, YAML och XML'; ?>
<?php include '../../includes/layout-start.php'; ?>

<style>
/* Converter-specific enhancements */
.converter-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2em;
  background: var(--background-color);
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.converter-header {
  text-align: center;
  margin-bottom: 2em;
  padding-bottom: 1em;
  border-bottom: 2px solid var(--border-color);
}

.converter-header h1 {
  font-size: 2.5em;
  color: var(--primary-color);
  margin: 0 0 0.5em 0;
  font-weight: 700;
}

.converter-header p {
  color: var(--text-color);
  opacity: 0.8;
  font-size: 1.1em;
}

.converter-tabs {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1em;
  margin-bottom: 2em;
  padding: 1em;
  background: var(--secondary-color);
  border-radius: 8px;
}

.converter-tab {
  position: relative;
  padding: 1em;
  background: var(--background-color);
  border: 2px solid var(--border-color);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  font-weight: 600;
  color: var(--text-color);
}

.converter-tab:hover {
  background: var(--hover-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.converter-tab.active {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.converter-tab .tab-icon {
  font-size: 1.5em;
  display: block;
  margin-bottom: 0.5em;
}

.converter-tab .tab-title {
  font-size: 0.9em;
  display: block;
}

.converter-content {
  background: var(--background-color);
  border-radius: 8px;
  padding: 2em;
  min-height: 400px;
  border: 1px solid var(--border-color);
}

@media (max-width: 768px) {
  .converter-container {
    padding: 1em;
  }
  
  .converter-tabs {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5em;
  }
  
  .converter-tab {
    padding: 0.8em;
  }
  
  .converter-tab .tab-icon {
    font-size: 1.2em;
  }
  
  .converter-header h1 {
    font-size: 2em;
  }
}
</style>

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
<script>
  // Tippy-tooltips för nya tabs
  tippy('.converter-tab[data-tippy-content]');
</script>

<?php include '../../includes/layout-end.php'; ?>
