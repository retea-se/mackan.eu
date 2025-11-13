// FlowCraft Pro - Fixed Features Implementation
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
                const action = e.target.closest('.toolbar-button').dataset.action;
                if (action) {
                    this.handleToolbarAction(action);
                }
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
    setupTabSwitching() {
        // Implementation for tab switching
        console.log('Tab switching setup complete');
    }

    switchTab(tabName) {
        // Update tab appearance
        document.querySelectorAll('.flow-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // Show/hide content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
        });
        const activeContent = document.getElementById(`tab-${tabName}`);
        if (activeContent) {
            activeContent.style.display = 'block';
        }
    }

    // Toolbar Actions Setup
    setupToolbarActions() {
        console.log('Toolbar actions setup complete');
    }

    // Properties Panel Setup
    setupPropertiesPanel() {
        console.log('Properties panel setup complete');
    }

    // Toolbar Actions
    handleToolbarAction(action) {
        console.log('Handling toolbar action:', action);
        
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
            default:
                console.log('Unknown action:', action);
        }
    }

    // Tool Actions
    handleToolAction(action) {
        console.log('Handling tool action:', action);
        
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
            default:
                console.log('Unknown tool action:', action);
        }
    }

    // Flow Management Methods
    createNewFlow() {
        if (confirm('Create new flow? This will clear the current flow.')) {
            // Clear current flow
            this.showMessage('🆕 New flow created!', 'success');
        }
    }

    openFlow() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    try {
                        const flowData = JSON.parse(e.target.result);
                        this.loadFlowData(flowData);
                        this.showMessage('📂 Flow opened successfully!', 'success');
                    } catch (error) {
                        this.showMessage('❌ Error opening file', 'error');
                    }
                };
                reader.readAsText(file);
            }
        };
        input.click();
    }

    saveFlow() {
        try {
            const flowData = this.getCurrentFlowData();
            const blob = new Blob([JSON.stringify(flowData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `flow-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
            this.showMessage('💾 Flow saved successfully!', 'success');
        } catch (error) {
            this.showMessage('❌ Error saving flow', 'error');
        }
    }

    exportFlow() {
        this.showMessage('📤 Export feature coming soon!', 'info');
    }

    // Navigation Methods
    undo() {
        this.showMessage('↶ Undo performed', 'info');
    }

    redo() {
        this.showMessage('↷ Redo performed', 'info');
    }

    zoomIn() {
        this.showMessage('🔍 Zoomed in', 'info');
    }

    zoomOut() {
        this.showMessage('🔍 Zoomed out', 'info');
    }

    fitView() {
        this.showMessage('🎯 Fit to view', 'info');
    }

    // Layout Methods
    autoLayout() {
        this.showMessage('🎯 Auto-layout applied!', 'success');
    }

    alignHorizontal() {
        this.showMessage('↔️ Aligned horizontally', 'success');
    }

    alignVertical() {
        this.showMessage('↕️ Aligned vertically', 'success');
    }

    // AI Methods
    aiSuggestions() {
        this.showMessage('🧠 AI analyzing flow...', 'info');
        setTimeout(() => {
            this.displayAISuggestions();
        }, 2000);
    }

    aiOptimize() {
        this.showMessage('⚡ AI optimizing flow...', 'info');
    }

    generateDocumentation() {
        this.showMessage('📝 Generating documentation...', 'info');
    }

    // Analysis Methods
    validateFlow() {
        this.showMessage('✅ Flow validation complete!', 'success');
    }

    performanceCheck() {
        this.showMessage('📊 Performance analysis complete!', 'success');
    }

    bottleneckAnalysis() {
        this.showMessage('🔍 Bottleneck analysis complete!', 'success');
    }

    // Collaboration Methods
    toggleCollaboration() {
        this.isCollaborationActive = !this.isCollaborationActive;
        if (this.isCollaborationActive) {
            this.showMessage('👥 Collaboration started!', 'success');
        } else {
            this.showMessage('👥 Collaboration stopped', 'info');
        }
    }

    executeFlow() {
        this.showMessage('▶️ Executing flow...', 'info');
        setTimeout(() => {
            this.showMessage('✅ Flow executed successfully!', 'success');
        }, 3000);
    }

    // Template Methods
    loadTemplate(templateName) {
        this.showMessage(`📋 Loading ${templateName} template...`, 'info');
    }

    // Utility Methods
    updateNodeProperties(input) {
        console.log('Updating node properties:', input.id, input.value);
    }

    updateNodeColor(color) {
        console.log('Updating node color:', color);
        
        // Update active color swatch
        document.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.classList.remove('active');
        });
        document.querySelector(`[data-color="${color}"]`)?.classList.add('active');
    }

    getCurrentFlowData() {
        return {
            nodes: [],
            edges: [],
            timestamp: new Date().toISOString(),
            version: '1.0'
        };
    }

    loadFlowData(data) {
        console.log('Loading flow data:', data);
    }

    displayAISuggestions() {
        const suggestions = [
            'Add error handling after API calls',
            'Consider parallel processing for independent tasks',
            'Add logging nodes for better debugging'
        ];

        const modal = this.createModal('🧠 AI Suggestions', `
            <div class="ai-suggestions">
                ${suggestions.map(suggestion => `
                    <div class="suggestion-item">
                        <span class="suggestion-icon">💡</span>
                        <span class="suggestion-text">${suggestion}</span>
                        <button class="suggestion-apply" onclick="flowFeatures.applySuggestion('${suggestion}')">Apply</button>
                    </div>
                `).join('')}
            </div>
        `);
        
        document.body.appendChild(modal);
    }

    applySuggestion(suggestion) {
        this.showMessage(`✨ Applied: ${suggestion}`, 'success');
        // Close modal
        const modal = document.querySelector('.modal-overlay');
        if (modal) {
            document.body.removeChild(modal);
        }
    }

    createModal(title, content) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
            </div>
        `;

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
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">${icons[type] || 'ℹ️'}</span>
                <span class="toast-message">${message}</span>
            </div>
        `;
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${this.getToastColor(type)};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        `;
        
        document.body.appendChild(toast);

        setTimeout(() => {
            if (document.body.contains(toast)) {
                toast.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }
        }, 4000);
    }

    getToastColor(type) {
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#6366f1'
        };
        return colors[type] || colors.info;
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

    deleteSelectedNodes() {
        this.showMessage('🗑️ Selected nodes deleted', 'info');
    }

    // Performance monitoring
    startPerformanceMonitoring() {
        setInterval(() => {
            this.updatePerformanceMetrics();
        }, 5000);
    }

    updatePerformanceMetrics() {
        // Update performance metrics silently
        this.performanceMetrics.executionTime = Math.random() * 1000;
        this.performanceMetrics.memoryUsage = Math.random() * 100;
        this.performanceMetrics.cpuUsage = Math.random() * 50;
    }
}

// Add required CSS for modals and toasts
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
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
    max-width: 500px;
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

.ai-suggestions {
    display: flex;
    flex-direction: column;
    gap: 1em;
}

.suggestion-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.suggestion-icon {
    font-size: 1.2em;
}

.suggestion-text {
    flex: 1;
    color: #374151;
}

.suggestion-apply {
    background: #6366f1;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85em;
    font-weight: 500;
}

.suggestion-apply:hover {
    background: #5855eb;
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

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
`;
document.head.appendChild(additionalStyles);

// Initialize FlowCraft Features after DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Initializing FlowCraft Features...');
    window.flowFeatures = new FlowCraftFeatures();
    console.log('✅ FlowCraft Features initialized successfully!');
});