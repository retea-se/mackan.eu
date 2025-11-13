// FlowCraft Pro - Advanced Features Implementation
class FlowCraftFeatures {
    constructor() {
        this.undoStack = [];
        this.redoStack = [];
        this.collaborators = new Map();
        this.isCollaborationActive = false;
        this.autoSaveInterval = null;
        this.performanceMetrics = {
            executionTime: 0,
            memoryUsage: 0,
            cpuUsage: 0
        };
        
        this.initializeFeatures();
    }

    initializeFeatures() {
        this.setupEventListeners();
        this.setupDragAndDrop();
        this.setupTabSwitching();
        this.setupToolbarActions();
        this.setupPropertiesPanel();
        this.startPerformanceMonitoring();
    }

    // Event Listeners Setup
    setupEventListeners() {
        // Tab switching
        document.querySelectorAll('.flow-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.switchTab(e.target.dataset.tab);
            });
        });

        // Toolbar actions
        document.querySelectorAll('.toolbar-button').forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleToolbarAction(e.target.closest('.toolbar-button').dataset.action);
            });
        });

        // Tool buttons
        document.querySelectorAll('.tool-button').forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.target.closest('.tool-button').dataset.action;
                const template = e.target.closest('.tool-button').dataset.template;
                
                if (action) this.handleToolAction(action);
                if (template) this.loadTemplate(template);
            });
        });

        // Properties panel inputs
        document.addEventListener('input', (e) => {
            if (e.target.matches('.property-input')) {
                this.updateNodeProperties(e.target);
            }
        });

        // Color swatches
        document.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.addEventListener('click', (e) => {
                this.updateNodeColor(e.target.dataset.color);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            this.handleKeyboardShortcuts(e);
        });
    }

    // Drag and Drop Setup
    setupDragAndDrop() {
        document.querySelectorAll('.node-item').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('application/reactflow', e.target.dataset.nodeType);
                e.dataTransfer.effectAllowed = 'move';
            });
        });
    }

    // Tab Switching
    switchTab(tabName) {
        // Update tab appearance
        document.querySelectorAll('.flow-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

        // Show/hide content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
        });
        document.getElementById(`tab-${tabName}`).style.display = 'block';
    }

    // Toolbar Actions
    handleToolbarAction(action) {
        switch (action) {
            case 'new':
                this.createNewFlow();
                break;
            case 'open':
                this.openFlow();
                break;
            case 'save':
                this.saveFlow();
                break;
            case 'export':
                this.exportFlow();
                break;
            case 'undo':
                this.undo();
                break;
            case 'redo':
                this.redo();
                break;
            case 'zoom-in':
                this.zoomIn();
                break;
            case 'zoom-out':
                this.zoomOut();
                break;
            case 'fit-view':
                this.fitView();
                break;
            case 'collaborate':
                this.toggleCollaboration();
                break;
            case 'execute':
                this.executeFlow();
                break;
        }
    }

    // Tool Actions
    handleToolAction(action) {
        switch (action) {
            case 'auto-layout':
                this.autoLayout();
                break;
            case 'align-horizontal':
                this.alignHorizontal();
                break;
            case 'align-vertical':
                this.alignVertical();
                break;
            case 'ai-suggest':
                this.aiSuggestions();
                break;
            case 'ai-optimize':
                this.aiOptimize();
                break;
            case 'ai-document':
                this.generateDocumentation();
                break;
            case 'validate-flow':
                this.validateFlow();
                break;
            case 'performance-check':
                this.performanceCheck();
                break;
            case 'bottleneck-analysis':
                this.bottleneckAnalysis();
                break;
        }
    }

    // Advanced Features Implementation

    // 1. Auto Layout Algorithm
    autoLayout() {
        this.showMessage('Applying intelligent auto-layout...', 'info');
        
        // Simulate AI-powered layout optimization
        setTimeout(() => {
            // This would integrate with a sophisticated layout algorithm
            const nodes = this.getCurrentNodes();
            const optimizedPositions = this.calculateOptimalLayout(nodes);
            
            this.updateNodePositions(optimizedPositions);
            this.showMessage('Auto-layout applied successfully!', 'success');
        }, 1500);
    }

    calculateOptimalLayout(nodes) {
        // Advanced force-directed layout algorithm
        const positions = new Map();
        const nodeWidth = 120;
        const nodeHeight = 60;
        const horizontalSpacing = 200;
        const verticalSpacing = 150;

        // Group nodes by type and level
        const levels = this.analyzeLevels(nodes);
        
        levels.forEach((levelNodes, level) => {
            const startX = 100;
            const startY = 100 + (level * verticalSpacing);
            
            levelNodes.forEach((node, index) => {
                positions.set(node.id, {
                    x: startX + (index * horizontalSpacing),
                    y: startY
                });
            });
        });

        return positions;
    }

    analyzeLevels(nodes) {
        // Analyze flow structure to determine optimal levels
        const levels = new Map();
        const startNodes = nodes.filter(n => n.data.type === 'start');
        
        // BFS to assign levels
        const queue = [];
        const visited = new Set();
        
        startNodes.forEach(node => {
            queue.push({ node, level: 0 });
        });

        while (queue.length > 0) {
            const { node, level } = queue.shift();
            
            if (visited.has(node.id)) continue;
            visited.add(node.id);

            if (!levels.has(level)) {
                levels.set(level, []);
            }
            levels.get(level).push(node);

            // Add connected nodes to next level
            const connectedNodes = this.getConnectedNodes(node);
            connectedNodes.forEach(connectedNode => {
                if (!visited.has(connectedNode.id)) {
                    queue.push({ node: connectedNode, level: level + 1 });
                }
            });
        }

        return levels;
    }

    // 2. AI-Powered Suggestions
    aiSuggestions() {
        this.showMessage('🧠 Analyzing flow for AI suggestions...', 'info');
        
        setTimeout(() => {
            const suggestions = this.generateAISuggestions();
            this.displaySuggestions(suggestions);
        }, 2000);
    }

    generateAISuggestions() {
        const nodes = this.getCurrentNodes();
        const edges = this.getCurrentEdges();
        const suggestions = [];

        // Analyze common patterns and suggest improvements
        suggestions.push({
            type: 'optimization',
            title: 'Add Error Handling',
            description: 'Consider adding error handling nodes after API calls',
            confidence: 0.85,
            action: () => this.addErrorHandling()
        });

        suggestions.push({
            type: 'performance',
            title: 'Parallel Processing',
            description: 'These processes can run in parallel for better performance',
            confidence: 0.72,
            action: () => this.optimizeParallelProcessing()
        });

        suggestions.push({
            type: 'best-practice',
            title: 'Add Logging',
            description: 'Add logging nodes for better debugging and monitoring',
            confidence: 0.91,
            action: () => this.addLoggingNodes()
        });

        return suggestions;
    }

    displaySuggestions(suggestions) {
        const modal = this.createModal('AI Suggestions', `
            <div class="ai-suggestions">
                ${suggestions.map(suggestion => `
                    <div class="suggestion-card" data-suggestion="${suggestion.type}">
                        <div class="suggestion-header">
                            <h4>${suggestion.title}</h4>
                            <span class="confidence-badge">${Math.round(suggestion.confidence * 100)}%</span>
                        </div>
                        <p>${suggestion.description}</p>
                        <button class="apply-suggestion-btn" onclick="flowFeatures.applySuggestion('${suggestion.type}')">
                            Apply Suggestion
                        </button>
                    </div>
                `).join('')}
            </div>
        `);
        
        document.body.appendChild(modal);
    }

    // 3. Real-time Collaboration
    toggleCollaboration() {
        if (this.isCollaborationActive) {
            this.stopCollaboration();
        } else {
            this.startCollaboration();
        }
    }

    startCollaboration() {
        this.isCollaborationActive = true;
        this.showMessage('🔗 Starting collaboration session...', 'info');
        
        // Simulate WebSocket connection
        setTimeout(() => {
            this.simulateCollaborators();
            this.showMessage('👥 Collaboration active! Others can now join.', 'success');
            
            // Update button state
            const collabButton = document.querySelector('[data-action="collaborate"]');
            collabButton.innerHTML = '<span>👥</span> Stop Collaboration';
            collabButton.classList.add('active');
        }, 1000);
    }

    simulateCollaborators() {
        const collaborators = [
            { id: 'user1', name: 'Alice', color: '#ef4444', cursor: { x: 300, y: 200 } },
            { id: 'user2', name: 'Bob', color: '#10b981', cursor: { x: 450, y: 350 } }
        ];

        collaborators.forEach(collaborator => {
            this.addCollaboratorCursor(collaborator);
        });
    }

    addCollaboratorCursor(collaborator) {
        const cursor = document.createElement('div');
        cursor.className = 'collaborator-cursor';
        cursor.innerHTML = `
            <div class="cursor-pointer" style="background: ${collaborator.color}"></div>
            <div class="cursor-label" style="background: ${collaborator.color}">${collaborator.name}</div>
        `;
        cursor.style.cssText = `
            position: absolute;
            left: ${collaborator.cursor.x}px;
            top: ${collaborator.cursor.y}px;
            z-index: 1000;
            pointer-events: none;
            transition: all 0.1s ease;
        `;
        
        const canvasArea = document.querySelector('.flow-canvas');
        canvasArea.appendChild(cursor);

        // Animate cursor movement
        setInterval(() => {
            const newX = collaborator.cursor.x + (Math.random() - 0.5) * 100;
            const newY = collaborator.cursor.y + (Math.random() - 0.5) * 100;
            cursor.style.left = `${Math.max(0, newX)}px`;
            cursor.style.top = `${Math.max(0, newY)}px`;
        }, 2000);
    }

    // 4. Flow Execution Engine
    executeFlow() {
        this.setExecutionState(true);
        this.showMessage('▶️ Executing flow...', 'info');
        
        const executionPlan = this.createExecutionPlan();
        this.runExecutionPlan(executionPlan);
    }

    createExecutionPlan() {
        const nodes = this.getCurrentNodes();
        const edges = this.getCurrentEdges();
        
        // Create execution order based on dependencies
        const executionOrder = this.topologicalSort(nodes, edges);
        
        return {
            steps: executionOrder,
            totalSteps: executionOrder.length,
            startTime: Date.now()
        };
    }

    async runExecutionPlan(plan) {
        const progressBar = this.createProgressBar();
        
        for (let i = 0; i < plan.steps.length; i++) {
            const step = plan.steps[i];
            
            // Highlight current node
            this.highlightExecutingNode(step.id);
            
            // Simulate execution
            await this.executeNode(step);
            
            // Update progress
            const progress = ((i + 1) / plan.totalSteps) * 100;
            this.updateProgressBar(progressBar, progress);
            
            // Small delay for visualization
            await this.delay(500);
        }
        
        this.completeExecution(plan);
    }

    async executeNode(node) {
        const executionTime = Math.random() * 1000 + 500; // 0.5-1.5 seconds
        
        // Log execution
        console.log(`Executing ${node.data.type}: ${node.data.label}`);
        
        // Simulate different node behaviors
        switch (node.data.type) {
            case 'api':
                await this.simulateAPICall(node);
                break;
            case 'database':
                await this.simulateDatabaseQuery(node);
                break;
            case 'email':
                await this.simulateEmailSend(node);
                break;
            case 'ai':
                await this.simulateAIProcessing(node);
                break;
            default:
                await this.delay(executionTime);
        }
    }

    // 5. Performance Analytics
    performanceCheck() {
        this.showMessage('📊 Analyzing performance metrics...', 'info');
        
        setTimeout(() => {
            const metrics = this.calculatePerformanceMetrics();
            this.displayPerformanceReport(metrics);
        }, 1500);
    }

    calculatePerformanceMetrics() {
        const nodes = this.getCurrentNodes();
        const edges = this.getCurrentEdges();
        
        return {
            complexity: this.calculateComplexity(nodes, edges),
            estimatedExecutionTime: this.estimateExecutionTime(nodes),
            bottlenecks: this.identifyBottlenecks(nodes, edges),
            optimization: this.suggestOptimizations(nodes, edges),
            scalability: this.assessScalability(nodes, edges)
        };
    }

    displayPerformanceReport(metrics) {
        const modal = this.createModal('Performance Analysis', `
            <div class="performance-report">
                <div class="metric-grid">
                    <div class="metric-card">
                        <h4>Complexity Score</h4>
                        <div class="metric-value ${this.getComplexityClass(metrics.complexity)}">
                            ${metrics.complexity}
                        </div>
                        <div class="metric-description">
                            ${this.getComplexityDescription(metrics.complexity)}
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <h4>Estimated Execution Time</h4>
                        <div class="metric-value">${metrics.estimatedExecutionTime}ms</div>
                        <div class="metric-description">Average execution time</div>
                    </div>
                    
                    <div class="metric-card">
                        <h4>Scalability Rating</h4>
                        <div class="metric-value ${this.getScalabilityClass(metrics.scalability)}">
                            ${metrics.scalability}/10
                        </div>
                        <div class="metric-description">How well the flow scales</div>
                    </div>
                </div>
                
                <div class="bottlenecks-section">
                    <h4>Identified Bottlenecks</h4>
                    <ul>
                        ${metrics.bottlenecks.map(bottleneck => `
                            <li>${bottleneck}</li>
                        `).join('')}
                    </ul>
                </div>
                
                <div class="optimization-section">
                    <h4>Optimization Suggestions</h4>
                    <ul>
                        ${metrics.optimization.map(suggestion => `
                            <li>${suggestion}</li>
                        `).join('')}
                    </ul>
                </div>
            </div>
        `);
        
        document.body.appendChild(modal);
    }

    // 6. Advanced Export Features
    exportFlow() {
        const exportOptions = [
            { format: 'json', label: 'JSON Format', icon: '📄' },
            { format: 'png', label: 'PNG Image', icon: '🖼️' },
            { format: 'svg', label: 'SVG Vector', icon: '🎨' },
            { format: 'pdf', label: 'PDF Document', icon: '📑' },
            { format: 'code', label: 'Code Generation', icon: '💻' },
            { format: 'documentation', label: 'Documentation', icon: '📚' }
        ];

        const modal = this.createModal('Export Flow', `
            <div class="export-options">
                ${exportOptions.map(option => `
                    <button class="export-option" data-format="${option.format}">
                        <span class="export-icon">${option.icon}</span>
                        <span class="export-label">${option.label}</span>
                    </button>
                `).join('')}
            </div>
        `);

        // Add event listeners for export options
        modal.querySelectorAll('.export-option').forEach(option => {
            option.addEventListener('click', (e) => {
                const format = e.currentTarget.dataset.format;
                this.performExport(format);
                document.body.removeChild(modal);
            });
        });

        document.body.appendChild(modal);
    }

    performExport(format) {
        switch (format) {
            case 'json':
                this.exportAsJSON();
                break;
            case 'png':
                this.exportAsPNG();
                break;
            case 'svg':
                this.exportAsSVG();
                break;
            case 'pdf':
                this.exportAsPDF();
                break;
            case 'code':
                this.generateCode();
                break;
            case 'documentation':
                this.generateDocumentation();
                break;
        }
    }

    // Utility Methods
    createModal(title, content) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
            </div>
        `;

        // Add close functionality
        modal.querySelector('.modal-close').addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });

        return modal;
    }

    showMessage(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `message-toast ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">
                    ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️'}
                </span>
                <span class="toast-message">${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);

        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 4000);
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    getCurrentNodes() {
        // This would get nodes from React Flow instance
        return [];
    }

    getCurrentEdges() {
        // This would get edges from React Flow instance
        return [];
    }

    setExecutionState(isExecuting) {
        const executeButton = document.querySelector('[data-action="execute"]');
        if (executeButton) {
            if (isExecuting) {
                executeButton.innerHTML = '<span class="loading-spinner"></span> Executing...';
                executeButton.disabled = true;
            } else {
                executeButton.innerHTML = '<span>▶️</span> Execute Flow';
                executeButton.disabled = false;
            }
        }
    }

    // Keyboard Shortcuts
    handleKeyboardShortcuts(e) {
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case 's':
                    e.preventDefault();
                    this.saveFlow();
                    break;
                case 'z':
                    e.preventDefault();
                    if (e.shiftKey) {
                        this.redo();
                    } else {
                        this.undo();
                    }
                    break;
                case 'n':
                    e.preventDefault();
                    this.createNewFlow();
                    break;
                case 'o':
                    e.preventDefault();
                    this.openFlow();
                    break;
                case 'e':
                    e.preventDefault();
                    this.exportFlow();
                    break;
            }
        }
        
        // Delete key for selected nodes
        if (e.key === 'Delete' || e.key === 'Backspace') {
            this.deleteSelectedNodes();
        }
    }

    // Initialize features when script loads
    startPerformanceMonitoring() {
        setInterval(() => {
            this.updatePerformanceMetrics();
        }, 5000);
    }
}

// Initialize FlowCraft Features
const flowFeatures = new FlowCraftFeatures();

// Add modal styles
const modalStyles = `
<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(4px);
}

.modal-content {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5em;
    border-bottom: 1px solid #e5e7eb;
    background: #f8fafc;
    border-radius: 12px 12px 0 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25em;
    color: #1f2937;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    color: #6b7280;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.modal-body {
    padding: 1.5em;
}

.export-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1em;
}

.export-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5em;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.export-option:hover {
    border-color: #6366f1;
    background: #f8fafc;
    transform: translateY(-2px);
}

.export-icon {
    font-size: 2em;
    margin-bottom: 0.5em;
}

.export-label {
    font-weight: 600;
    color: #374151;
}

.ai-suggestions {
    display: flex;
    flex-direction: column;
    gap: 1em;
}

.suggestion-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1em;
    background: #f8fafc;
}

.suggestion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5em;
}

.suggestion-header h4 {
    margin: 0;
    color: #1f2937;
}

.confidence-badge {
    background: #10b981;
    color: white;
    padding: 0.2em 0.5em;
    border-radius: 12px;
    font-size: 0.8em;
    font-weight: 600;
}

.apply-suggestion-btn {
    background: #6366f1;
    color: white;
    border: none;
    padding: 0.5em 1em;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 0.5em;
}

.apply-suggestion-btn:hover {
    background: #5855eb;
}

.performance-report {
    max-width: 100%;
}

.metric-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1em;
    margin-bottom: 2em;
}

.metric-card {
    text-align: center;
    padding: 1em;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f8fafc;
}

.metric-card h4 {
    margin: 0 0 0.5em 0;
    color: #6b7280;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-value {
    font-size: 2em;
    font-weight: 700;
    margin-bottom: 0.3em;
}

.metric-value.low { color: #10b981; }
.metric-value.medium { color: #f59e0b; }
.metric-value.high { color: #ef4444; }

.metric-description {
    font-size: 0.8em;
    color: #6b7280;
}

.bottlenecks-section, .optimization-section {
    margin-bottom: 1.5em;
}

.bottlenecks-section h4, .optimization-section h4 {
    margin: 0 0 0.5em 0;
    color: #1f2937;
}

.bottlenecks-section ul, .optimization-section ul {
    margin: 0;
    padding-left: 1.5em;
    color: #374151;
}

.collaborator-cursor .cursor-pointer {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: relative;
}

.collaborator-cursor .cursor-pointer::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    width: 0;
    height: 0;
    border-left: 8px solid;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-color: inherit;
}

.collaborator-cursor .cursor-label {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 0.2em 0.5em;
    border-radius: 4px;
    color: white;
    font-size: 0.7em;
    font-weight: 600;
    white-space: nowrap;
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 0.5em;
}

.toast-icon {
    font-size: 1.2em;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@media (max-width: 768px) {
    .modal-content {
        margin: 1em;
        max-width: calc(100% - 2em);
    }
    
    .export-options {
        grid-template-columns: 1fr;
    }
    
    .metric-grid {
        grid-template-columns: 1fr;
    }
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', modalStyles);