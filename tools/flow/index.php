<?php
// tools/flow/index.php - v2
// Flow Builder - kopiera innehåll från index-working.php

$title = 'Flow Builder - Enkelt flödesdiagram';
$metaDescription = 'Enkelt verktyg för att skapa flödesdiagram med drag-and-drop';
$keywords = 'flow builder, flödesdiagram, diagram, flowchart, drag and drop';
$canonical = 'https://mackan.eu/tools/flow/';
?>
<?php include '../../includes/layout-start.php'; ?>

<div class="layout__container">
    <div class="verktygsinfo">
        <h1 class="verktygsinfo__titel">
            <i class="fas fa-project-diagram"></i> Flow Builder
        </h1>
        <p class="verktygsinfo__beskrivning">
            Skapa flödesdiagram enkelt med drag-and-drop
        </p>
    </div>

    <div class="kort kort--ram">
        <div class="flow-container">
            <p class="text--muted">För full funktionalitet, besök <a href="index-working.php">den avancerade versionen</a>.</p>
            <p><a href="index-working.php" class="knapp">Öppna Flow Builder</a></p>
        </div>
    </div>
</div>

<?php include '../../includes/layout-end.php'; ?>

<style>
/* FlowCraft Pro Styles */
:root {
    --flow-primary: #6366f1;
    --flow-secondary: #8b5cf6;
    --flow-accent: #06b6d4;
    --flow-success: #10b981;
    --flow-warning: #f59e0b;
    --flow-danger: #ef4444;
    --flow-dark: #1f2937;
    --flow-light: #f8fafc;
    --flow-gray: #6b7280;
    --flow-border: #e5e7eb;
    --flow-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    --flow-radius: 12px;
    --flow-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    margin: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.flow-app {
    display: flex;
    height: 100vh;
    background: white;
    border-radius: var(--flow-radius);
    margin: 1em;
    box-shadow: var(--flow-shadow);
    overflow: hidden;
}

/* Sidebar */
.flow-sidebar {
    width: 320px;
    background: var(--flow-dark);
    color: white;
    display: flex;
    flex-direction: column;
    border-radius: var(--flow-radius) 0 0 var(--flow-radius);
}

.flow-header {
    padding: 1.5em;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.flow-header h1 {
    margin: 0;
    font-size: 1.5em;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.flow-header p {
    margin: 0.5em 0 0 0;
    color: #9ca3af;
    font-size: 0.9em;
}

.flow-tabs {
    display: flex;
    margin-top: 1em;
}

.flow-tab {
    flex: 1;
    padding: 0.5em;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    transition: var(--flow-transition);
    border-bottom: 2px solid transparent;
}

.flow-tab.active {
    color: white;
    border-bottom-color: var(--flow-primary);
}

.flow-tab:hover {
    color: white;
}

.flow-content {
    flex: 1;
    overflow-y: auto;
    padding: 1em;
}

/* Node Palette */
.node-palette {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5em;
    margin-bottom: 1.5em;
}

.node-item {
    padding: 0.8em;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    cursor: grab;
    transition: var(--flow-transition);
    text-align: center;
    font-size: 0.85em;
}

.node-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.node-item:active {
    cursor: grabbing;
}

.node-icon {
    font-size: 1.5em;
    display: block;
    margin-bottom: 0.3em;
}

/* Tools Panel */
.flow-tools {
    display: flex;
    flex-direction: column;
    gap: 0.5em;
}

.tool-group {
    margin-bottom: 1em;
}

.tool-group h3 {
    margin: 0 0 0.5em 0;
    font-size: 0.9em;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.tool-button {
    width: 100%;
    padding: 0.8em;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: var(--flow-transition);
    font-size: 0.85em;
    display: flex;
    align-items: center;
    gap: 0.5em;
    margin-bottom: 0.3em;
}

.tool-button:hover {
    background: rgba(255, 255, 255, 0.1);
}

.tool-button.active {
    background: var(--flow-primary);
}

/* Main Canvas Area */
.flow-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f8fafc;
}

.flow-toolbar {
    background: white;
    border-bottom: 1px solid var(--flow-border);
    padding: 1em;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1em;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 0.5em;
}

.toolbar-button {
    padding: 0.5em 1em;
    background: white;
    border: 1px solid var(--flow-border);
    border-radius: 6px;
    cursor: pointer;
    transition: var(--flow-transition);
    font-size: 0.85em;
    display: flex;
    align-items: center;
    gap: 0.3em;
}

.toolbar-button:hover {
    background: var(--flow-light);
    border-color: var(--flow-primary);
}

.toolbar-button.primary {
    background: var(--flow-primary);
    color: white;
    border-color: var(--flow-primary);
}

.toolbar-button.primary:hover {
    background: #5855eb;
}

.flow-canvas {
    flex: 1;
    position: relative;
    background:
        radial-gradient(circle at 20px 20px, #e5e7eb 1px, transparent 1px),
        linear-gradient(to right, transparent 19px, #f3f4f6 20px, transparent 21px),
        linear-gradient(to bottom, transparent 19px, #f3f4f6 20px, transparent 21px);
    background-size: 20px 20px, 20px 20px, 20px 20px;
}

/* Properties Panel */
.flow-properties {
    width: 300px;
    background: white;
    border-left: 1px solid var(--flow-border);
    display: flex;
    flex-direction: column;
}

.properties-header {
    padding: 1em;
    border-bottom: 1px solid var(--flow-border);
    background: var(--flow-light);
}

.properties-content {
    flex: 1;
    overflow-y: auto;
    padding: 1em;
}

.property-group {
    margin-bottom: 1.5em;
}

.property-group h4 {
    margin: 0 0 0.5em 0;
    font-size: 0.9em;
    color: var(--flow-gray);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.property-input {
    width: 100%;
    padding: 0.5em;
    border: 1px solid var(--flow-border);
    border-radius: 4px;
    font-size: 0.9em;
    transition: var(--flow-transition);
}

.property-input:focus {
    outline: none;
    border-color: var(--flow-primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.color-palette {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0.3em;
    margin-top: 0.5em;
}

.color-swatch {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--flow-transition);
}

.color-swatch:hover {
    transform: scale(1.1);
}

.color-swatch.active {
    border-color: var(--flow-dark);
}

/* Statistics Panel */
.flow-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1em;
    margin-top: 1em;
}

.stat-card {
    background: white;
    padding: 1em;
    border-radius: 8px;
    text-align: center;
    border: 1px solid var(--flow-border);
}

.stat-value {
    font-size: 1.5em;
    font-weight: 700;
    color: var(--flow-primary);
    margin-bottom: 0.2em;
}

.stat-label {
    font-size: 0.8em;
    color: var(--flow-gray);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .flow-app {
        margin: 0;
        border-radius: 0;
        height: 100vh;
    }

    .flow-sidebar {
        width: 280px;
    }

    .flow-properties {
        width: 250px;
    }
}

@media (max-width: 768px) {
    .flow-app {
        flex-direction: column;
    }

    .flow-sidebar,
    .flow-properties {
        width: 100%;
        height: auto;
        max-height: 200px;
    }

    .flow-main {
        flex: 1;
        min-height: 400px;
    }

    .node-palette {
        grid-template-columns: repeat(4, 1fr);
    }

    .toolbar-group {
        flex-wrap: wrap;
    }
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Success/Error Messages */
.message-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1em 1.5em;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 1000;
    animation: slideIn 0.3s ease-out;
}

.message-toast.success {
    background: var(--flow-success);
}

.message-toast.error {
    background: var(--flow-danger);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Minimap */
.flow-minimap {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 200px;
    height: 120px;
    background: white;
    border: 1px solid var(--flow-border);
    border-radius: 8px;
    box-shadow: var(--flow-shadow);
    z-index: 100;
}
</style>

<div class="flow-app">
    <!-- Sidebar -->
    <div class="flow-sidebar">
        <div class="flow-header">
            <h1>⚡ FlowCraft Pro</h1>
            <p>Advanced Flow Builder with AI</p>

            <div class="flow-tabs">
                <button class="flow-tab active" data-tab="nodes">Nodes</button>
                <button class="flow-tab" data-tab="tools">Tools</button>
                <button class="flow-tab" data-tab="templates">Templates</button>
            </div>
        </div>

        <div class="flow-content">
            <!-- Nodes Tab -->
            <div id="tab-nodes" class="tab-content">
                <div class="node-palette">
                    <div class="node-item" draggable="true" data-node-type="start">
                        <span class="node-icon">🟢</span>
                        Start
                    </div>
                    <div class="node-item" draggable="true" data-node-type="end">
                        <span class="node-icon">🔴</span>
                        End
                    </div>
                    <div class="node-item" draggable="true" data-node-type="process">
                        <span class="node-icon">⚙️</span>
                        Process
                    </div>
                    <div class="node-item" draggable="true" data-node-type="decision">
                        <span class="node-icon">❓</span>
                        Decision
                    </div>
                    <div class="node-item" draggable="true" data-node-type="api">
                        <span class="node-icon">🌐</span>
                        API Call
                    </div>
                    <div class="node-item" draggable="true" data-node-type="database">
                        <span class="node-icon">💾</span>
                        Database
                    </div>
                    <div class="node-item" draggable="true" data-node-type="email">
                        <span class="node-icon">📧</span>
                        Email
                    </div>
                    <div class="node-item" draggable="true" data-node-type="webhook">
                        <span class="node-icon">🔗</span>
                        Webhook
                    </div>
                    <div class="node-item" draggable="true" data-node-type="ai">
                        <span class="node-icon">🤖</span>
                        AI/ML
                    </div>
                    <div class="node-item" draggable="true" data-node-type="timer">
                        <span class="node-icon">⏰</span>
                        Timer
                    </div>
                </div>
            </div>

            <!-- Tools Tab -->
            <div id="tab-tools" class="tab-content" style="display: none;">
                <div class="tool-group">
                    <h3>Layout</h3>
                    <button class="tool-button" data-action="auto-layout">
                        <span>🎯</span> Auto Layout
                    </button>
                    <button class="tool-button" data-action="align-horizontal">
                        <span>↔️</span> Align Horizontal
                    </button>
                    <button class="tool-button" data-action="align-vertical">
                        <span>↕️</span> Align Vertical
                    </button>
                </div>

                <div class="tool-group">
                    <h3>AI Tools</h3>
                    <button class="tool-button" data-action="ai-suggest">
                        <span>🧠</span> AI Suggestions
                    </button>
                    <button class="tool-button" data-action="ai-optimize">
                        <span>⚡</span> Optimize Flow
                    </button>
                    <button class="tool-button" data-action="ai-document">
                        <span>📝</span> Generate Docs
                    </button>
                </div>

                <div class="tool-group">
                    <h3>Analysis</h3>
                    <button class="tool-button" data-action="validate-flow">
                        <span>✅</span> Validate Flow
                    </button>
                    <button class="tool-button" data-action="performance-check">
                        <span>📊</span> Performance
                    </button>
                    <button class="tool-button" data-action="bottleneck-analysis">
                        <span>🔍</span> Bottlenecks
                    </button>
                </div>
            </div>

            <!-- Templates Tab -->
            <div id="tab-templates" class="tab-content" style="display: none;">
                <div class="tool-group">
                    <h3>Business</h3>
                    <button class="tool-button" data-template="approval-workflow">
                        <span>✍️</span> Approval Workflow
                    </button>
                    <button class="tool-button" data-template="customer-onboarding">
                        <span>👋</span> Customer Onboarding
                    </button>
                    <button class="tool-button" data-template="order-processing">
                        <span>🛒</span> Order Processing
                    </button>
                </div>

                <div class="tool-group">
                    <h3>Development</h3>
                    <button class="tool-button" data-template="ci-cd-pipeline">
                        <span>🚀</span> CI/CD Pipeline
                    </button>
                    <button class="tool-button" data-template="error-handling">
                        <span>⚠️</span> Error Handling
                    </button>
                    <button class="tool-button" data-template="microservices">
                        <span>🏗️</span> Microservices
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Canvas Area -->
    <div class="flow-main">
        <!-- Toolbar -->
        <div class="flow-toolbar">
            <div class="toolbar-group">
                <button class="toolbar-button" data-action="new">
                    <span>📄</span> New
                </button>
                <button class="toolbar-button" data-action="open">
                    <span>📂</span> Open
                </button>
                <button class="toolbar-button" data-action="save">
                    <span>💾</span> Save
                </button>
                <button class="toolbar-button" data-action="export">
                    <span>📤</span> Export
                </button>
            </div>

            <div class="toolbar-group">
                <button class="toolbar-button" data-action="undo">
                    <span>↶</span> Undo
                </button>
                <button class="toolbar-button" data-action="redo">
                    <span>↷</span> Redo
                </button>
                <button class="toolbar-button" data-action="zoom-in">
                    <span>🔍</span> Zoom In
                </button>
                <button class="toolbar-button" data-action="zoom-out">
                    <span>🔍</span> Zoom Out
                </button>
                <button class="toolbar-button" data-action="fit-view">
                    <span>🎯</span> Fit View
                </button>
            </div>

            <div class="toolbar-group">
                <button class="toolbar-button" data-action="collaborate">
                    <span>👥</span> Collaborate
                </button>
                <button class="toolbar-button primary" data-action="execute">
                    <span>▶️</span> Execute Flow
                </button>
            </div>
        </div>

        <!-- React Flow Canvas -->
        <div class="flow-canvas" id="react-flow">
            <!-- React Flow will be rendered here -->
        </div>

        <!-- Minimap -->
        <div class="flow-minimap" id="minimap">
            <!-- Minimap will be rendered here -->
        </div>
    </div>

    <!-- Properties Panel -->
    <div class="flow-properties">
        <div class="properties-header">
            <h3>Properties</h3>
        </div>

        <div class="properties-content" id="properties-panel">
            <div class="property-group">
                <h4>Node Properties</h4>
                <label>Name:</label>
                <input type="text" class="property-input" id="node-name" placeholder="Enter node name">

                <label>Description:</label>
                <textarea class="property-input" id="node-description" rows="3" placeholder="Enter description"></textarea>
            </div>

            <div class="property-group">
                <h4>Style</h4>
                <label>Background Color:</label>
                <div class="color-palette">
                    <div class="color-swatch" style="background: #6366f1;" data-color="#6366f1"></div>
                    <div class="color-swatch" style="background: #8b5cf6;" data-color="#8b5cf6"></div>
                    <div class="color-swatch" style="background: #06b6d4;" data-color="#06b6d4"></div>
                    <div class="color-swatch" style="background: #10b981;" data-color="#10b981"></div>
                    <div class="color-swatch" style="background: #f59e0b;" data-color="#f59e0b"></div>
                    <div class="color-swatch" style="background: #ef4444;" data-color="#ef4444"></div>
                </div>
            </div>

            <div class="property-group">
                <h4>Configuration</h4>
                <label>Timeout (ms):</label>
                <input type="number" class="property-input" id="node-timeout" placeholder="5000">

                <label>Retry Count:</label>
                <input type="number" class="property-input" id="node-retry" placeholder="3">
            </div>
        </div>
    </div>
</div>

<!-- Statistics Dashboard -->
<div class="flow-stats">
    <div class="stat-card">
        <div class="stat-value" id="node-count">0</div>
        <div class="stat-label">Nodes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="connection-count">0</div>
        <div class="stat-label">Connections</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="complexity-score">0</div>
        <div class="stat-label">Complexity</div>
    </div>
</div>

<script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
<script src="https://unpkg.com/reactflow@11.10.4/dist/umd/index.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<script>
// Fix ReactFlow reference - wait for library to load
setTimeout(() => {
    window.ReactFlowLib = window.ReactFlow;
    console.log('ReactFlow available:', !!window.ReactFlowLib);
    console.log('ReactFlow object:', window.ReactFlow);
}, 100);
</script>

<script src="flow-app-simple.js"></script>
<script src="flow-features-fixed.js"></script>
<script src="flow-ai-fixed.js"></script>

<?php include '../../includes/layout-end.php'; ?>
