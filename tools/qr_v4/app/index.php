<?php
// tools/qr_v4/app/index.php
$title = 'QR v4 – Superverktyg';
$metaDescription = 'Samlat QR-verktyg med single- och batchlägen, expertläge för design, export och hjälpfunktioner.';
include '../../../includes/layout-start.php';
?>
<link rel="stylesheet" href="styles.css?v=1">

<main class="qr-app" data-version="v4">
  <header class="qr-app__hero">
    <div>
      <p class="qr-eyebrow">QR v4 · Endast lokalt · Ingen inloggning</p>
      <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
      <p class="qr-lead">
        Ett black & white-inspirerat verktyg som samlar allt från single QR till batch, export och expertinställningar –
        utan reklam eller konto.
      </p>
      <ul class="qr-checklist">
        <li>1. Välj läge</li>
        <li>2. Mata in innehåll</li>
        <li>3. Förhandsgranska & finjustera</li>
        <li>4. Exportera eller spara</li>
      </ul>
    </div>
    <div class="qr-hero__status">
      <div>
        <span class="status-label">Status</span>
        <span class="status-value" id="statusIndicator">Redo</span>
      </div>
      <div>
        <span class="status-label">Historik</span>
        <span class="status-value" id="historyCount">0</span>
      </div>
      <div>
        <button id="themeToggle" class="qr-theme-toggle" aria-label="Växla tema" title="Växla mellan mörkt och ljust tema">
          <span class="qr-theme-toggle__icon">🌙</span>
        </button>
      </div>
      <div>
        <span class="status-label">Testläge</span>
        <span class="status-value">Lokalt</span>
      </div>
    </div>
  </header>

<div class="qr-layout">
  <div class="qr-flow">
    <section class="qr-step" aria-labelledby="stepTypeTitle">
      <header class="qr-step__header">
        <p class="qr-step__eyebrow">Steg 1</p>
        <div>
          <h2 id="stepTypeTitle">Typ & läge</h2>
          <p class="qr-step__lead">Välj flöde och dataset för din QR.</p>
        </div>
        <div class="qr-panel__actions">
          <button id="resetForm" class="qr-link">Återställ</button>
          <button id="duplicateForm" class="qr-link">Duplicera</button>
        </div>
      </header>
      <div class="qr-mode" role="region" aria-label="Läge">
        <div class="qr-mode__tabs" role="tablist">
          <button class="qr-tab qr-tab--active" data-mode="single" role="tab" aria-selected="true">Enkel QR</button>
          <button class="qr-tab" data-mode="batch" role="tab" aria-selected="false">Batch & etiketter</button>
        </div>
      </div>
      <div class="qr-quickstart" id="quickStartCards" aria-label="Snabbstarter"></div>
      <div class="qr-tags" id="typeTags" aria-live="polite">
        <!-- Populated via JS -->
      </div>
    </section>

    <section class="qr-step" aria-labelledby="stepContentTitle">
      <header class="qr-step__header">
        <p class="qr-step__eyebrow">Steg 2</p>
        <div>
          <h2 id="stepContentTitle">Innehåll</h2>
          <p class="qr-step__lead">Fyll i fält för vald typ eller batch.</p>
        </div>
      </header>
      <div id="formFields" class="qr-form" aria-live="polite"></div>
      <div id="batchControls" class="qr-batch hidden">
        <div class="qr-batch__types" id="batchTypes"></div>
        <textarea id="batchTextarea" rows="8" placeholder="Ange data, en rad per QR..."></textarea>
        <div class="qr-actions">
          <button id="batchGenerate" class="qr-button qr-button--outline">Generera batch</button>
        </div>
        <p id="batchStatus" class="qr-status" aria-live="polite"></p>
      </div>
    </section>

    <section class="qr-step" aria-labelledby="stepStyleTitle">
      <header class="qr-step__header">
        <p class="qr-step__eyebrow">Steg 3</p>
        <div>
          <h2 id="stepStyleTitle">Stil</h2>
          <p class="qr-step__lead">Justera färger, storlek och logotyp.</p>
        </div>
        <button id="openExpertDrawer" class="qr-link" aria-haspopup="dialog">Fler stilval</button>
      </header>
      <div class="qr-style">
        <div class="qr-grid-two">
          <label>
            Primär färg
            <input type="color" id="primaryColor" value="#111111">
          </label>
          <label>
            Bakgrund
            <input type="color" id="backgroundColor" value="#fefefe">
          </label>
          <label>
            Storlek (px)
            <input type="range" id="sizeSlider" min="180" max="420" value="280">
          </label>
          <label class="qr-logo-upload">
            Ladda logotyp (SVG/PNG)
            <input type="file" id="logoInput" accept=".svg,.png,.jpg,.jpeg">
          </label>
        </div>
        <div id="logoPreview" class="qr-logo-preview hidden" aria-live="polite">
          <img alt="Förhandsvisning av logotyp" />
          <div>
            <p>Logga aktiv</p>
            <button id="clearLogo" type="button" class="qr-link">Ta bort</button>
          </div>
        </div>
        <p class="qr-style__note">Systemet blockerar export om loggan täcker för stor yta eller om kontrasten är för låg.</p>
      </div>
      <div class="qr-actions">
        <button id="generateBtn" class="qr-button">Generera / Spara</button>
        <button id="saveTemplate" class="qr-button qr-button--ghost">Spara som mall</button>
        <button id="testMobile" class="qr-button qr-button--outline">Testa i mobil</button>
      </div>
    </section>
  </div>

  <div class="qr-stack">
    <section class="qr-panel qr-panel--preview" aria-labelledby="previewTitle">
      <div class="qr-panel__header">
        <h2 id="previewTitle">Förhandsvy & export</h2>
        <button id="openHelp" class="qr-link" aria-haspopup="dialog">Hjälp</button>
      </div>
      <div id="previewSummary" class="qr-summary">
        <p><strong>Typ:</strong> <span id="summaryType">–</span></p>
        <p><strong>Data:</strong> <span id="summaryData">–</span></p>
        <p><strong>Färg/logga:</strong> <span id="summaryStyle">Standard</span></p>
      </div>
      <div id="warningPanel" class="qr-warning hidden" role="alert"></div>
      <div id="qrPreview" class="qr-preview" aria-live="polite"></div>

      <div class="qr-export" id="exportPanel" aria-hidden="true">
        <h3>Export</h3>
        <div class="qr-export__buttons">
          <button class="qr-button qr-button--outline" data-export="png">PNG</button>
          <button class="qr-button qr-button--outline" data-export="svg">SVG</button>
          <button class="qr-button qr-button--outline" data-export="zip">ZIP (batch)</button>
          <button class="qr-button qr-button--outline" data-export="docx">DOCX</button>
          <button class="qr-button qr-button--outline" data-export="pdf">PDF</button>
          <button class="qr-button qr-button--ghost" data-export="copy">Kopiera bild</button>
        </div>
      </div>

      <div id="batchPreview" class="qr-batch-preview"></div>
    </section>

    <section class="qr-assist" id="assistPanel" aria-label="Assistanspanel">
      <div class="qr-assist__chips">
        <span class="qr-chip" data-chip="contrast">Kontrast: –</span>
        <span class="qr-chip" data-chip="logo">Logga: saknas</span>
        <span class="qr-chip" data-chip="export">Export: blockerad</span>
      </div>
      <div class="qr-help">
        <div class="qr-sticky">
          <h4>Guidelines</h4>
          <p>Kontrast ≥ 4.5:1, logga ≤ 30 %, skyddszon ≥ 10 %.</p>
        </div>
        <div class="qr-sticky">
          <h4>Batch</h4>
          <p>Använd kommatecken mellan nod & adress. CSV-stöd kommer.</p>
        </div>
        <div class="qr-sticky">
          <h4>Export</h4>
          <p>ZIP används endast vid batch. PDF skapar A4-grid.</p>
        </div>
        <div class="qr-sticky">
          <h4>Mobiltest</h4>
          <p>“Testa i mobil” öppnar QR i nytt fönster för skanning.</p>
        </div>
      </div>
    </section>

    <section class="qr-panel qr-panel--history" aria-labelledby="historyTitle">
      <div class="qr-panel__header">
        <h2 id="historyTitle">Historik & mallar</h2>
        <div class="qr-panel__actions">
          <button id="clearHistory" class="qr-link">Rensa historik</button>
          <button id="clearTemplates" class="qr-link">Rensa mallar</button>
        </div>
      </div>
      <ul id="historyList" class="qr-history" aria-live="polite"></ul>
      <h3>Mallar</h3>
      <ul id="templateList" class="qr-history" aria-live="polite"></ul>
    </section>
  </div>
</div>
</main>

<template id="historyItemTemplate">
  <li>
    <button class="qr-history__item">
      <span class="qr-history__type"></span>
      <span class="qr-history__data"></span>
      <span class="qr-history__date"></span>
    </button>
  </li>
</template>

<aside id="helpDrawer" class="qr-drawer" role="dialog" aria-modal="true" aria-labelledby="helpDrawerTitle" hidden>
  <div class="qr-drawer__panel" tabindex="-1">
    <header class="qr-drawer__header">
      <h2 id="helpDrawerTitle">Snabbguide & regler</h2>
      <button id="closeHelp" class="qr-link">Stäng</button>
    </header>
    <section>
      <h3>1. Kontrast & läsbarhet</h3>
      <p>Följ <code>accessibility-guidelines.md</code>: kontrast ≥ 4.5:1, logga ≤ 30 %, minst 10 % skyddszon.</p>
    </section>
    <section>
      <h3>2. Expertläge</h3>
      <ul>
        <li>Modul- och hörnform påverkar läsbarheten – systemet varnar vid risk.</li>
        <li>Gradienter fungerar endast med QR Code Styling; håll bakgrunden ljus.</li>
      </ul>
    </section>
    <section>
      <h3>3. Batchtips</h3>
      <ul>
        <li>Använd CSV-liknande format (komma eller semikolon).</li>
        <li>Felanmälan: <code>NOD, adress</code> → genererar mailto med ämne.</li>
      </ul>
    </section>
    <section>
      <h3>4. Export & loggar</h3>
      <p>All export sker lokalt. Fel loggas i <code>tools/qr_v4/logs/</code> vid scriptkörning.</p>
    </section>
  </div>
</aside>

<aside id="expertDrawer" class="qr-drawer" role="dialog" aria-modal="true" aria-labelledby="expertDrawerTitle" hidden>
  <div class="qr-drawer__panel" tabindex="-1">
    <header class="qr-drawer__header">
      <h2 id="expertDrawerTitle">Expertinställningar</h2>
      <button id="closeExpertDrawer" class="qr-link">Stäng</button>
    </header>
    <div id="expertPanel" class="qr-expert">
      <div class="qr-grid-two">
        <label>
          Modulform
          <select id="moduleShape">
            <option value="square">Square</option>
            <option value="dots">Dots</option>
            <option value="rounded">Rounded</option>
            <option value="extra-rounded">Extra Rounded</option>
          </select>
        </label>
        <label>
          Hörnform
          <select id="cornerShape">
            <option value="square">Square</option>
            <option value="rounded">Rounded</option>
            <option value="dot">Dot</option>
          </select>
        </label>
        <label>
          Gradient start
          <input type="color" id="gradientStart" value="#111111">
        </label>
        <label>
          Gradient slut
          <input type="color" id="gradientEnd" value="#000000">
        </label>
        <label>
          Gradienttyp
          <select id="gradientType">
            <option value="none">Ingen</option>
            <option value="linear">Linjär</option>
            <option value="radial">Radiell</option>
          </select>
        </label>
        <label>
          Logostorlek (% av QR)
          <input type="range" id="logoSize" min="10" max="30" value="20">
        </label>
      </div>
      <p class="qr-note">
        Varningar visas när kontrast eller logotypens storlek hotar läsbarhet. Export blockeras när chipen i assistpanelen signalerar rött.
      </p>
    </div>
  </div>
</aside>

<?php include '../../../includes/layout-end.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.6.0/lib/qr-code-styling.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx@8.5.0/build/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="script.js" type="module"></script>


