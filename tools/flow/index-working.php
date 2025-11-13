<?php
$title = 'Flow Builder - Enkelt flödesdiagram';
$metaDescription = 'Enkelt verktyg för att skapa flödesdiagram med drag-and-drop';
?>
<?php include '../../includes/layout-start.php'; ?>

<div class="container">
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
            <!-- Sidebar -->
            <div class="flow-sidebar">
                <h3 class="rubrik rubrik--h3">Lägg till noder</h3>
                
                <div class="flow-nodes">
                    <div class="flow-node-button" data-type="start">
                        <i class="fas fa-play-circle"></i>
                        <span>Start</span>
                    </div>
                    <div class="flow-node-button" data-type="process">
                        <i class="fas fa-cogs"></i>
                        <span>Process</span>
                    </div>
                    <div class="flow-node-button" data-type="decision">
                        <i class="fas fa-code-branch"></i>
                        <span>Beslut</span>
                    </div>
                    <div class="flow-node-button" data-type="data">
                        <i class="fas fa-database"></i>
                        <span>Data</span>
                    </div>
                    <div class="flow-node-button" data-type="end">
                        <i class="fas fa-stop-circle"></i>
                        <span>Slut</span>
                    </div>
                </div>

                <div class="flow-actions">
                    <button class="knapp knapp--sekundar knapp--liten" onclick="clearFlow()">
                        <i class="fas fa-trash"></i> Rensa
                    </button>
                    <button class="knapp knapp--primar knapp--liten" onclick="exportFlow()">
                        <i class="fas fa-download"></i> Exportera
                    </button>
                </div>
            </div>

            <!-- Canvas -->
            <div class="flow-canvas">
                <div id="reactflow" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="kort kort--info">
        <h3 class="rubrik rubrik--h3">
            <i class="fas fa-info-circle"></i> Instruktioner
        </h3>
        <ul>
            <li>Klicka på en nodtyp för att lägga till den</li>
            <li>Dra noder för att flytta dem</li>
            <li>Dra från en nods kant till en annan för att koppla</li>
            <li>Dubbelklicka på nod för att redigera text</li>
            <li>Tryck Delete för att ta bort vald nod</li>
        </ul>
    </div>
</div>

<style>
.flow-container {
    display: flex;
    height: 600px;
    gap: calc(var(--spacing-unit) * 2);
    background: var(--background-color);
    border-radius: var(--border-radius);
}

.flow-sidebar {
    width: 200px;
    background: var(--secondary-color);
    padding: calc(var(--spacing-unit) * 2);
    border-radius: var(--border-radius);
    display: flex;
    flex-direction: column;
    gap: calc(var(--spacing-unit) * 2);
}

.flow-nodes {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-unit);
}

.flow-node-button {
    display: flex;
    align-items: center;
    gap: var(--spacing-unit);
    padding: calc(var(--spacing-unit) * 1.5);
    background: var(--background-color);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.2s ease;
    font-family: var(--font-family);
    font-size: 14px;
    color: var(--text-color);
}

.flow-node-button:hover {
    background: var(--hover-color);
    border-color: var(--primary-color);
    transform: translateY(-1px);
}

.flow-node-button i {
    color: var(--primary-color);
    font-size: 16px;
}

.flow-canvas {
    flex: 1;
    background: #fafafa;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    position: relative;
    overflow: hidden;
}

[data-theme="dark"] .flow-canvas {
    background: #1a1a1a;
}

.flow-actions {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-unit);
}

/* React Flow styling */
.react-flow__node {
    font-family: var(--font-family);
    font-size: 14px;
    cursor: move;
}

.react-flow__node-input,
.react-flow__node-output,
.react-flow__node-default {
    border: 2px solid var(--primary-color);
    border-radius: var(--border-radius);
    background: var(--background-color);
    color: var(--text-color);
    padding: 8px 12px;
    min-width: 80px;
    text-align: center;
}

.react-flow__node.selected {
    box-shadow: 0 0 0 2px var(--primary-color);
}

.react-flow__handle {
    width: 8px;
    height: 8px;
    background: var(--primary-color);
    border: 2px solid var(--background-color);
}

.react-flow__edge-path {
    stroke: var(--primary-color);
    stroke-width: 2;
}

.react-flow__edge.selected .react-flow__edge-path {
    stroke: var(--accent-color);
}

.react-flow__controls {
    background: var(--background-color);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
}

.react-flow__controls-button {
    background: var(--background-color);
    border: none;
    color: var(--text-color);
    cursor: pointer;
}

.react-flow__controls-button:hover {
    background: var(--hover-color);
}

@media (max-width: 768px) {
    .flow-container {
        flex-direction: column;
        height: auto;
    }
    
    .flow-sidebar {
        width: 100%;
        order: 2;
    }
    
    .flow-canvas {
        height: 400px;
        order: 1;
    }
    
    .flow-nodes {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .flow-node-button {
        flex: 1 0 calc(50% - var(--spacing-unit));
    }
}
</style>

<script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/reactflow@11/dist/umd/index.js"></script>

<script>
// Global variables
let reactFlowInstance;
let nodeId = 1;
let nodes = [];
let edges = [];
let setNodes, setEdges;

// Initialize React Flow
function initializeFlow() {
    if (!window.ReactFlow) {
        setTimeout(initializeFlow, 100);
        return;
    }

    const { ReactFlow, ReactFlowProvider, Controls, Background, MiniMap, 
            useNodesState, useEdgesState, addEdge, useReactFlow } = window.ReactFlow;

    // Flow component
    function Flow() {
        const [localNodes, localSetNodes, onNodesChange] = useNodesState([
            {
                id: '1',
                type: 'input',
                position: { x: 250, y: 100 },
                data: { label: 'Start' },
                style: {
                    background: '#d4f1d4',
                    border: '2px solid #4caf50'
                }
            }
        ]);
        
        const [localEdges, localSetEdges, onEdgesChange] = useEdgesState([]);
        const reactFlow = useReactFlow();

        // Expose to global scope
        React.useEffect(() => {
            nodes = localNodes;
            edges = localEdges;
            setNodes = localSetNodes;
            setEdges = localSetEdges;
            reactFlowInstance = reactFlow;
        }, [localNodes, localEdges, localSetNodes, localSetEdges, reactFlow]);

        const onConnect = React.useCallback((params) => {
            localSetEdges((eds) => addEdge({
                ...params,
                animated: true,
                style: { stroke: '#007acc', strokeWidth: 2 }
            }, eds));
        }, [localSetEdges]);

        const onNodeDoubleClick = React.useCallback((event, node) => {
            const newLabel = prompt('Ange ny text:', node.data.label);
            if (newLabel !== null) {
                localSetNodes((nds) => nds.map((n) => {
                    if (n.id === node.id) {
                        return { ...n, data: { ...n.data, label: newLabel } };
                    }
                    return n;
                }));
            }
        }, [localSetNodes]);

        return React.createElement(ReactFlow, {
            nodes: localNodes,
            edges: localEdges,
            onNodesChange: onNodesChange,
            onEdgesChange: onEdgesChange,
            onConnect: onConnect,
            onNodeDoubleClick: onNodeDoubleClick,
            fitView: true,
            deleteKeyCode: 'Delete'
        }, [
            React.createElement(Background, { key: 'bg', variant: 'dots' }),
            React.createElement(Controls, { key: 'controls' }),
            React.createElement(MiniMap, { key: 'minimap', style: { height: 100 } })
        ]);
    }

    // App wrapper
    function App() {
        return React.createElement(ReactFlowProvider, {}, 
            React.createElement(Flow)
        );
    }

    // Render
    const container = document.getElementById('reactflow');
    const root = ReactDOM.createRoot(container);
    root.render(React.createElement(App));
}

// Add node function
function addNode(type) {
    if (!setNodes) return;

    const nodeStyles = {
        start: { background: '#d4f1d4', border: '2px solid #4caf50' },
        process: { background: '#e3f2fd', border: '2px solid #2196f3' },
        decision: { background: '#fff3e0', border: '2px solid #ff9800' },
        data: { background: '#f3e5f5', border: '2px solid #9c27b0' },
        end: { background: '#ffebee', border: '2px solid #f44336' }
    };

    const nodeType = type === 'start' ? 'input' : type === 'end' ? 'output' : 'default';
    
    const newNode = {
        id: `${++nodeId}`,
        type: nodeType,
        position: { 
            x: Math.random() * 400 + 100, 
            y: Math.random() * 300 + 100 
        },
        data: { label: type.charAt(0).toUpperCase() + type.slice(1) },
        style: nodeStyles[type] || nodeStyles.process
    };

    setNodes((nds) => [...nds, newNode]);
}

// Clear flow
function clearFlow() {
    if (setNodes && setEdges) {
        setNodes([]);
        setEdges([]);
    }
}

// Export flow
function exportFlow() {
    if (!nodes || !edges) return;

    const flow = { nodes, edges };
    const dataStr = JSON.stringify(flow, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', 'flow.json');
    linkElement.click();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initializeFlow();
    
    // Add click handlers to node buttons
    document.querySelectorAll('.flow-node-button').forEach(button => {
        button.addEventListener('click', (e) => {
            const type = e.target.closest('.flow-node-button').dataset.type;
            addNode(type);
        });
    });
});
</script>

<?php include '../../includes/layout-end.php'; ?>