<?php
$title = 'Flow Builder - Skapa flödesdiagram enkelt';
$metaDescription = 'Enkelt verktyg för att skapa flödesdiagram med drag-and-drop';
?>
<?php include '../../includes/layout-start.php'; ?>

<div class="container">
    <div class="verktygsinfo">
        <h1 class="verktygsinfo__titel">
            <i class="fas fa-project-diagram"></i> Flow Builder
        </h1>
        <p class="verktygsinfo__beskrivning">
            Skapa professionella flödesdiagram enkelt med drag-and-drop
        </p>
    </div>

    <div class="kort kort--ram">
        <div class="flow-container" id="flow-container">
            <!-- Sidebar med noder -->
            <div class="flow-sidebar">
                <h3 class="rubrik rubrik--h3">Dra och släpp</h3>
                
                <div class="flow-nodes">
                    <div class="flow-node flow-node--draggable" draggable="true" data-type="start">
                        <i class="fas fa-play-circle"></i>
                        <span>Start</span>
                    </div>
                    <div class="flow-node flow-node--draggable" draggable="true" data-type="process">
                        <i class="fas fa-cogs"></i>
                        <span>Process</span>
                    </div>
                    <div class="flow-node flow-node--draggable" draggable="true" data-type="decision">
                        <i class="fas fa-code-branch"></i>
                        <span>Beslut</span>
                    </div>
                    <div class="flow-node flow-node--draggable" draggable="true" data-type="data">
                        <i class="fas fa-database"></i>
                        <span>Data</span>
                    </div>
                    <div class="flow-node flow-node--draggable" draggable="true" data-type="end">
                        <i class="fas fa-stop-circle"></i>
                        <span>Slut</span>
                    </div>
                </div>

                <div class="flow-actions">
                    <button class="knapp knapp--sekundar knapp--liten" onclick="flowBuilder.clearCanvas()">
                        <i class="fas fa-trash"></i> Rensa
                    </button>
                    <button class="knapp knapp--primar knapp--liten" onclick="flowBuilder.exportFlow()">
                        <i class="fas fa-download"></i> Exportera
                    </button>
                </div>
            </div>

            <!-- Canvas -->
            <div class="flow-canvas" id="flow-canvas">
                <div id="react-flow" class="flow-canvas__inner"></div>
            </div>
        </div>
    </div>

    <div class="kort kort--info">
        <h3 class="rubrik rubrik--h3">
            <i class="fas fa-info-circle"></i> Så här använder du verktyget
        </h3>
        <ul>
            <li>Dra noder från vänster sidopanel till canvas</li>
            <li>Klicka och dra mellan noder för att skapa kopplingar</li>
            <li>Dubbelklicka på en nod för att redigera text</li>
            <li>Använd scroll för att zooma in/ut</li>
            <li>Håll ned mellanslag och dra för att panorera</li>
        </ul>
    </div>
</div>

<style>
/* Flow-specifika stilar som följer webbplatsens design */
.flow-container {
    display: flex;
    height: 600px;
    gap: var(--spacing-unit);
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

.flow-node {
    display: flex;
    align-items: center;
    gap: var(--spacing-unit);
    padding: calc(var(--spacing-unit) * 1.5);
    background: var(--background-color);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    cursor: grab;
    transition: all 0.2s ease;
    font-family: var(--font-family);
    font-size: 14px;
    color: var(--text-color);
}

.flow-node--draggable:hover {
    background: var(--hover-color);
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.flow-node--draggable:active {
    cursor: grabbing;
}

.flow-node i {
    color: var(--primary-color);
    font-size: 18px;
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
    background: #0f0f0f;
}

.flow-canvas__inner {
    width: 100%;
    height: 100%;
}

.flow-actions {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-unit);
}

/* React Flow anpassningar */
.react-flow__node {
    font-family: var(--font-family);
}

.react-flow__node-default {
    background: var(--background-color);
    border: 2px solid var(--primary-color);
    border-radius: var(--border-radius);
    padding: 10px 15px;
    font-size: 14px;
    color: var(--text-color);
}

.react-flow__node.selected {
    box-shadow: 0 0 0 2px var(--primary-color);
}

.react-flow__edge-path {
    stroke: var(--primary-color);
    stroke-width: 2;
}

.react-flow__controls {
    background: var(--background-color);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.react-flow__controls-button {
    background: var(--background-color);
    border: none;
    color: var(--text-color);
    width: 28px;
    height: 28px;
    font-size: 16px;
    cursor: pointer;
}

.react-flow__controls-button:hover {
    background: var(--hover-color);
}

/* Responsiv design */
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
    
    .flow-node {
        flex: 1 0 calc(50% - var(--spacing-unit));
    }
}
</style>

<!-- React och React Flow -->
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/reactflow@11/dist/umd/index.js"></script>

<!-- Flow Builder Script -->
<script>
// Enkel Flow Builder implementation
const flowBuilder = {
    reactFlowInstance: null,
    
    init() {
        const { ReactFlow, ReactFlowProvider, Controls, Background, MiniMap, 
                useNodesState, useEdgesState, addEdge, useReactFlow } = window.ReactFlow;
        
        // Flow component
        const Flow = () => {
            const [nodes, setNodes, onNodesChange] = useNodesState([
                {
                    id: '1',
                    type: 'input',
                    position: { x: 250, y: 100 },
                    data: { label: 'Start' },
                    style: {
                        background: '#d4f1d4',
                        border: '2px solid #4caf50',
                        borderRadius: '8px',
                        padding: '10px',
                        minWidth: '100px',
                        textAlign: 'center'
                    },
                    draggable: true,
                    selectable: true
                }
            ]);
            
            const [edges, setEdges, onEdgesChange] = useEdgesState([]);
            const reactFlowInstance = useReactFlow();
            
            const onConnect = React.useCallback((params) => {
                setEdges((eds) => addEdge({
                    ...params,
                    style: { stroke: 'var(--primary-color)', strokeWidth: 2 },
                    animated: true
                }, eds));
            }, []);
            
            const onDragOver = React.useCallback((event) => {
                event.preventDefault();
                event.dataTransfer.dropEffect = 'move';
            }, []);
            
            const onDrop = React.useCallback((event) => {
                event.preventDefault();
                
                const type = event.dataTransfer.getData('node-type');
                if (!type) return;
                
                const position = reactFlowInstance.screenToFlowPosition({
                    x: event.clientX,
                    y: event.clientY,
                });
                
                const nodeStyles = {
                    start: { background: '#d4f1d4', border: '2px solid #4caf50' },
                    process: { background: '#e3f2fd', border: '2px solid #2196f3' },
                    decision: { background: '#fff3e0', border: '2px solid #ff9800' },
                    data: { background: '#f3e5f5', border: '2px solid #9c27b0' },
                    end: { background: '#ffebee', border: '2px solid #f44336' }
                };
                
                const nodeTypes = {
                    start: 'input',
                    process: 'default', 
                    decision: 'default',
                    data: 'default',
                    end: 'output'
                };
                
                const newNode = {
                    id: `${Date.now()}`,
                    type: nodeTypes[type] || 'default',
                    position,
                    data: { label: type.charAt(0).toUpperCase() + type.slice(1) },
                    style: {
                        ...nodeStyles[type],
                        borderRadius: '8px',
                        padding: '10px',
                        minWidth: '100px',
                        textAlign: 'center'
                    },
                    draggable: true,
                    selectable: true
                };
                
                setNodes((nds) => nds.concat(newNode));
            }, [reactFlowInstance]);
            
            const onNodeDoubleClick = React.useCallback((event, node) => {
                const newLabel = prompt('Ange text:', node.data.label);
                if (newLabel !== null) {
                    setNodes((nds) => nds.map((n) => {
                        if (n.id === node.id) {
                            return { ...n, data: { ...n.data, label: newLabel } };
                        }
                        return n;
                    }));
                }
            }, []);
            
            // Exponera funktioner
            React.useEffect(() => {
                flowBuilder.clearCanvas = () => {
                    setNodes([]);
                    setEdges([]);
                };
                
                flowBuilder.exportFlow = () => {
                    const flow = { nodes, edges };
                    const dataStr = JSON.stringify(flow, null, 2);
                    const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);
                    
                    const exportFileDefaultName = 'flow.json';
                    const linkElement = document.createElement('a');
                    linkElement.setAttribute('href', dataUri);
                    linkElement.setAttribute('download', exportFileDefaultName);
                    linkElement.click();
                };
            }, [nodes, edges]);
            
            return React.createElement(ReactFlow, {
                nodes: nodes,
                edges: edges,
                onNodesChange: onNodesChange,
                onEdgesChange: onEdgesChange,
                onConnect: onConnect,
                onDrop: onDrop,
                onDragOver: onDragOver,
                onNodeDoubleClick: onNodeDoubleClick,
                fitView: true,
                deleteKeyCode: "Delete",
                nodesDraggable: true,
                nodesConnectable: true,
                elementsSelectable: true
            }, [
                React.createElement(Background, { 
                    key: 'bg',
                    variant: "dots", 
                    gap: 20, 
                    size: 1 
                }),
                React.createElement(Controls, { key: 'controls' }),
                React.createElement(MiniMap, { 
                    key: 'minimap',
                    style: { height: 100 } 
                })
            ]);
        };
        
        // App wrapper
        const App = () => {
            return React.createElement(ReactFlowProvider, {},
                React.createElement(Flow)
            );
        };
        
        // Rendera
        const container = document.getElementById('react-flow');
        const root = ReactDOM.createRoot(container);
        root.render(React.createElement(App));
        
        // Setup drag handlers
        document.querySelectorAll('.flow-node--draggable').forEach(node => {
            node.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('node-type', node.dataset.type);
                e.dataTransfer.effectAllowed = 'move';
            });
        });
    }
};

// Vänta på att React Flow ska laddas
document.addEventListener('DOMContentLoaded', () => {
    const checkReactFlow = setInterval(() => {
        if (window.ReactFlow) {
            clearInterval(checkReactFlow);
            flowBuilder.init();
        }
    }, 100);
});
</script>

<?php include '../../includes/layout-end.php'; ?>