<?php
// tools/flow/index.php - v3
// Flow Builder - Refaktorerad med standardlayout och SEO

$title = 'Flow Builder - Enkelt flödesdiagram';
$metaDescription = 'Enkelt verktyg för att skapa flödesdiagram med drag-and-drop. Skapa professionella flödesdiagram direkt i webbläsaren.';
$keywords = 'flow builder, flödesdiagram, diagram, flowchart, drag and drop, flödesdesign, workflow';
$canonical = 'https://mackan.eu/tools/flow/';

// Strukturerad data för sökmotorer
$extraHead = '
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebApplication",
  "name": "Flow Builder - Enkelt flödesdiagram",
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
    "Skapa flödesdiagram",
    "Drag and drop",
    "Professionella diagram",
    "Workflow design"
  ],
  "author": {
    "@type": "Organization",
    "name": "Mackan.eu"
  }
}
</script>
<link rel="stylesheet" href="flow.css">';

include '../../includes/tool-layout-start.php';
?>

  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
    </h1>
    <p class="text--lead">
      <?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>
    </p>
  </header>

  <section class="layout__sektion">
    <div class="flow-wrapper">
      <!-- Info kort -->
      <div class="kort kort--ram">
        <div class="kort__innehall">
          <p class="text--muted">
            <strong>Flow Builder</strong> låter dig skapa professionella flödesdiagram direkt i webbläsaren.
          </p>
          <p class="text--muted">
            För avancerad funktionalitet, besök <a href="index-working.php" class="knapp knapp--liten">avancerad version</a>.
          </p>
        </div>
      </div>

      <!-- Flow App Container -->
      <div class="flow-app" role="application" aria-label="Flow Builder">
        <!-- Sidebar -->
        <aside class="flow-sidebar" role="complementary" aria-label="Node palette">
          <div class="flow-header">
            <h2 class="rubrik rubrik--underrubrik">⚡ FlowCraft Pro</h2>
            <p class="text--muted text--small">Advanced Flow Builder</p>

            <nav class="flow-tabs" role="tablist" aria-label="Flow tabs">
              <button class="flow-tab flow-tab--aktiv" 
                      role="tab" 
                      aria-selected="true"
                      aria-controls="tab-nodes"
                      id="tab-btn-nodes"
                      data-tab="nodes">
                Nodes
              </button>
              <button class="flow-tab" 
                      role="tab" 
                      aria-selected="false"
                      aria-controls="tab-tools"
                      id="tab-btn-tools"
                      data-tab="tools">
                Tools
              </button>
              <button class="flow-tab" 
                      role="tab" 
                      aria-selected="false"
                      aria-controls="tab-templates"
                      id="tab-btn-templates"
                      data-tab="templates">
                Templates
              </button>
            </nav>
          </div>

          <div class="flow-content">
            <!-- Nodes Tab -->
            <div id="tab-nodes" 
                 class="tab-content" 
                 role="tabpanel" 
                 aria-labelledby="tab-btn-nodes">
              <h3 class="rubrik rubrik--h3">Lägg till noder</h3>
              <div class="node-palette" role="group" aria-label="Available nodes">
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="start"
                        aria-label="Start node">
                  <span class="node-icon" aria-hidden="true">🟢</span>
                  <span>Start</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="end"
                        aria-label="End node">
                  <span class="node-icon" aria-hidden="true">🔴</span>
                  <span>End</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="process"
                        aria-label="Process node">
                  <span class="node-icon" aria-hidden="true">⚙️</span>
                  <span>Process</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="decision"
                        aria-label="Decision node">
                  <span class="node-icon" aria-hidden="true">❓</span>
                  <span>Decision</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="api"
                        aria-label="API Call node">
                  <span class="node-icon" aria-hidden="true">🌐</span>
                  <span>API Call</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="database"
                        aria-label="Database node">
                  <span class="node-icon" aria-hidden="true">💾</span>
                  <span>Database</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="email"
                        aria-label="Email node">
                  <span class="node-icon" aria-hidden="true">📧</span>
                  <span>Email</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="webhook"
                        aria-label="Webhook node">
                  <span class="node-icon" aria-hidden="true">🔗</span>
                  <span>Webhook</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="ai"
                        aria-label="AI/ML node">
                  <span class="node-icon" aria-hidden="true">🤖</span>
                  <span>AI/ML</span>
                </button>
                <button type="button" 
                        class="node-item" 
                        draggable="true" 
                        data-node-type="timer"
                        aria-label="Timer node">
                  <span class="node-icon" aria-hidden="true">⏰</span>
                  <span>Timer</span>
                </button>
              </div>
            </div>

            <!-- Tools Tab -->
            <div id="tab-tools" 
                 class="tab-content hidden" 
                 role="tabpanel" 
                 aria-labelledby="tab-btn-tools"
                 aria-hidden="true">
              <div class="tool-group">
                <h3 class="rubrik rubrik--h3">Layout</h3>
                <button type="button" 
                        class="tool-button" 
                        data-action="auto-layout"
                        aria-label="Auto layout">
                  <span aria-hidden="true">🎯</span> Auto Layout
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-action="align-horizontal"
                        aria-label="Align horizontal">
                  <span aria-hidden="true">↔️</span> Align Horizontal
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-action="align-vertical"
                        aria-label="Align vertical">
                  <span aria-hidden="true">↕️</span> Align Vertical
                </button>
              </div>

              <div class="tool-group">
                <h3 class="rubrik rubrik--h3">AI Tools</h3>
                <button type="button" 
                        class="tool-button" 
                        data-action="ai-suggest"
                        aria-label="AI suggestions">
                  <span aria-hidden="true">🧠</span> AI Suggestions
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-action="ai-optimize"
                        aria-label="Optimize flow">
                  <span aria-hidden="true">⚡</span> Optimize Flow
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-action="ai-document"
                        aria-label="Generate documentation">
                  <span aria-hidden="true">📝</span> Generate Docs
                </button>
              </div>

              <div class="tool-group">
                <h3 class="rubrik rubrik--h3">Analysis</h3>
                <button type="button" 
                        class="tool-button" 
                        data-action="validate-flow"
                        aria-label="Validate flow">
                  <span aria-hidden="true">✅</span> Validate Flow
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-action="performance-check"
                        aria-label="Performance check">
                  <span aria-hidden="true">📊</span> Performance
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-action="bottleneck-analysis"
                        aria-label="Bottleneck analysis">
                  <span aria-hidden="true">🔍</span> Bottlenecks
                </button>
              </div>
            </div>

            <!-- Templates Tab -->
            <div id="tab-templates" 
                 class="tab-content hidden" 
                 role="tabpanel" 
                 aria-labelledby="tab-btn-templates"
                 aria-hidden="true">
              <div class="tool-group">
                <h3 class="rubrik rubrik--h3">Business</h3>
                <button type="button" 
                        class="tool-button" 
                        data-template="approval-workflow"
                        aria-label="Approval workflow template">
                  <span aria-hidden="true">✍️</span> Approval Workflow
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-template="customer-onboarding"
                        aria-label="Customer onboarding template">
                  <span aria-hidden="true">👋</span> Customer Onboarding
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-template="order-processing"
                        aria-label="Order processing template">
                  <span aria-hidden="true">🛒</span> Order Processing
                </button>
              </div>

              <div class="tool-group">
                <h3 class="rubrik rubrik--h3">Development</h3>
                <button type="button" 
                        class="tool-button" 
                        data-template="ci-cd-pipeline"
                        aria-label="CI/CD pipeline template">
                  <span aria-hidden="true">🚀</span> CI/CD Pipeline
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-template="error-handling"
                        aria-label="Error handling template">
                  <span aria-hidden="true">⚠️</span> Error Handling
                </button>
                <button type="button" 
                        class="tool-button" 
                        data-template="microservices"
                        aria-label="Microservices template">
                  <span aria-hidden="true">🏗️</span> Microservices
                </button>
              </div>
            </div>
          </div>
        </aside>

        <!-- Main Canvas Area -->
        <div class="flow-main" role="main" aria-label="Flow canvas">
          <!-- Toolbar -->
          <div class="flow-toolbar" role="toolbar" aria-label="Flow toolbar">
            <div class="toolbar-group">
              <button type="button" 
                      class="toolbar-button" 
                      data-action="new"
                      aria-label="New flow">
                <span aria-hidden="true">📄</span> New
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="open"
                      aria-label="Open flow">
                <span aria-hidden="true">📂</span> Open
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="save"
                      aria-label="Save flow">
                <span aria-hidden="true">💾</span> Save
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="export"
                      aria-label="Export flow">
                <span aria-hidden="true">📤</span> Export
              </button>
            </div>

            <div class="toolbar-group">
              <button type="button" 
                      class="toolbar-button" 
                      data-action="undo"
                      aria-label="Undo">
                <span aria-hidden="true">↶</span> Undo
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="redo"
                      aria-label="Redo">
                <span aria-hidden="true">↷</span> Redo
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="zoom-in"
                      aria-label="Zoom in">
                <span aria-hidden="true">🔍</span> Zoom In
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="zoom-out"
                      aria-label="Zoom out">
                <span aria-hidden="true">🔍</span> Zoom Out
              </button>
              <button type="button" 
                      class="toolbar-button" 
                      data-action="fit-view"
                      aria-label="Fit view">
                <span aria-hidden="true">🎯</span> Fit View
              </button>
            </div>

            <div class="toolbar-group">
              <button type="button" 
                      class="toolbar-button" 
                      data-action="collaborate"
                      aria-label="Collaborate">
                <span aria-hidden="true">👥</span> Collaborate
              </button>
              <button type="button" 
                      class="toolbar-button toolbar-button--primary" 
                      data-action="execute"
                      aria-label="Execute flow">
                <span aria-hidden="true">▶️</span> Execute Flow
              </button>
            </div>
          </div>

          <!-- React Flow Canvas -->
          <div class="flow-canvas" 
               id="react-flow" 
               role="region" 
               aria-label="Flow diagram canvas"
               tabindex="0">
            <!-- React Flow will be rendered here -->
          </div>

          <!-- Minimap -->
          <div class="flow-minimap" 
               id="minimap" 
               role="complementary" 
               aria-label="Flow minimap"
               aria-hidden="true">
            <!-- Minimap will be rendered here -->
          </div>
        </div>

        <!-- Properties Panel -->
        <aside class="flow-properties" 
               role="complementary" 
               aria-label="Node properties">
          <div class="properties-header">
            <h3 class="rubrik rubrik--h3">Properties</h3>
          </div>

          <div class="properties-content" id="properties-panel">
            <div class="property-group">
              <h4 class="rubrik rubrik--h4">Node Properties</h4>
              <label for="node-name" class="falt__etikett">Name:</label>
              <input type="text" 
                     id="node-name" 
                     class="falt__input property-input" 
                     placeholder="Enter node name"
                     aria-label="Node name">
              
              <label for="node-description" class="falt__etikett">Description:</label>
              <textarea id="node-description" 
                        class="falt__textarea property-input" 
                        rows="3" 
                        placeholder="Enter description"
                        aria-label="Node description"></textarea>
            </div>

            <div class="property-group">
              <h4 class="rubrik rubrik--h4">Style</h4>
              <label class="falt__etikett">Background Color:</label>
              <div class="color-palette" role="group" aria-label="Color palette">
                <button type="button" 
                        class="color-swatch" 
                        style="background: #6366f1;" 
                        data-color="#6366f1"
                        aria-label="Color #6366f1"></button>
                <button type="button" 
                        class="color-swatch" 
                        style="background: #8b5cf6;" 
                        data-color="#8b5cf6"
                        aria-label="Color #8b5cf6"></button>
                <button type="button" 
                        class="color-swatch" 
                        style="background: #06b6d4;" 
                        data-color="#06b6d4"
                        aria-label="Color #06b6d4"></button>
                <button type="button" 
                        class="color-swatch" 
                        style="background: #10b981;" 
                        data-color="#10b981"
                        aria-label="Color #10b981"></button>
                <button type="button" 
                        class="color-swatch" 
                        style="background: #f59e0b;" 
                        data-color="#f59e0b"
                        aria-label="Color #f59e0b"></button>
                <button type="button" 
                        class="color-swatch" 
                        style="background: #ef4444;" 
                        data-color="#ef4444"
                        aria-label="Color #ef4444"></button>
              </div>
            </div>

            <div class="property-group">
              <h4 class="rubrik rubrik--h4">Configuration</h4>
              <label for="node-timeout" class="falt__etikett">Timeout (ms):</label>
              <input type="number" 
                     id="node-timeout" 
                     class="falt__input property-input" 
                     placeholder="5000"
                     aria-label="Node timeout">
              
              <label for="node-retry" class="falt__etikett">Retry Count:</label>
              <input type="number" 
                     id="node-retry" 
                     class="falt__input property-input" 
                     placeholder="3"
                     aria-label="Node retry count">
            </div>
          </div>
        </aside>
      </div>

      <!-- Statistics Dashboard -->
      <div class="flow-stats layout__grid" role="region" aria-label="Flow statistics">
        <div class="kort stat-card">
          <div class="stat-value" id="node-count" aria-live="polite">0</div>
          <div class="stat-label">Nodes</div>
        </div>
        <div class="kort stat-card">
          <div class="stat-value" id="connection-count" aria-live="polite">0</div>
          <div class="stat-label">Connections</div>
        </div>
        <div class="kort stat-card">
          <div class="stat-value" id="complexity-score" aria-live="polite">0</div>
          <div class="stat-label">Complexity</div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../../includes/tool-layout-end.php'; ?>

<!-- React Flow Dependencies -->
<script crossorigin src="https://unpkg.com/react@18/umd/react.development.js" defer></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" defer></script>
<script src="https://unpkg.com/reactflow@11.10.4/dist/umd/index.js" defer></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js" defer></script>

<script>
// Fix ReactFlow reference - wait for library to load
setTimeout(() => {
    if (typeof window.ReactFlow !== 'undefined') {
        window.ReactFlowLib = window.ReactFlow;
        console.log('ReactFlow available:', !!window.ReactFlowLib);
    } else {
        console.warn('ReactFlow library not loaded');
    }
}, 500);
</script>

<script src="flow-app-simple.js" defer></script>
<script src="flow-features-fixed.js" defer></script>
<script src="flow-ai-fixed.js" defer></script>

<script>
// Tab switching med ARIA-stöd
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.flow-tab[data-tab]');
    const tabPanels = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetTab = tab.dataset.tab;
            
            // Uppdatera ARIA-states
            tabs.forEach(t => {
                t.classList.remove('flow-tab--aktiv');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('flow-tab--aktiv');
            tab.setAttribute('aria-selected', 'true');
            
            // Visa/dölj panels
            tabPanels.forEach(panel => {
                const isTarget = panel.id === `tab-${targetTab}`;
                panel.classList.toggle('hidden', !isTarget);
                panel.setAttribute('aria-hidden', !isTarget ? 'true' : 'false');
            });
        });
    });
});
</script>
