// FlowCraft Pro - Advanced React Flow Implementation
const { useState, useCallback, useRef, useEffect, useMemo } = React;
const { 
    ReactFlow, 
    MiniMap, 
    Controls, 
    Background, 
    useNodesState, 
    useEdgesState, 
    addEdge,
    MarkerType,
    Position
} = ReactFlowLib;

// Custom Node Types
const CustomNode = ({ data, isConnectable }) => {
    const getNodeStyle = () => {
        const baseStyle = {
            padding: '12px 16px',
            borderRadius: '12px',
            border: '2px solid',
            background: 'white',
            fontSize: '14px',
            fontWeight: '600',
            minWidth: '120px',
            textAlign: 'center',
            position: 'relative',
            boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
            transition: 'all 0.3s ease'
        };

        const nodeStyles = {
            start: {
                ...baseStyle,
                borderColor: '#10b981',
                background: 'linear-gradient(135deg, #d1fae5, #a7f3d0)',
                color: '#065f46'
            },
            end: {
                ...baseStyle,
                borderColor: '#ef4444',
                background: 'linear-gradient(135deg, #fee2e2, #fecaca)',
                color: '#991b1b'
            },
            process: {
                ...baseStyle,
                borderColor: '#6366f1',
                background: 'linear-gradient(135deg, #e0e7ff, #c7d2fe)',
                color: '#3730a3'
            },
            decision: {
                ...baseStyle,
                borderColor: '#f59e0b',
                background: 'linear-gradient(135deg, #fef3c7, #fde68a)',
                color: '#92400e',
                clipPath: 'polygon(20% 0%, 80% 0%, 100% 50%, 80% 100%, 20% 100%, 0% 50%)'
            },
            api: {
                ...baseStyle,
                borderColor: '#06b6d4',
                background: 'linear-gradient(135deg, #cffafe, #a5f3fc)',
                color: '#155e75'
            },
            database: {
                ...baseStyle,
                borderColor: '#8b5cf6',
                background: 'linear-gradient(135deg, #ede9fe, #ddd6fe)',
                color: '#5b21b6'
            },
            email: {
                ...baseStyle,
                borderColor: '#ec4899',
                background: 'linear-gradient(135deg, #fce7f3, #fbcfe8)',
                color: '#be185d'
            },
            webhook: {
                ...baseStyle,
                borderColor: '#84cc16',
                background: 'linear-gradient(135deg, #ecfccb, #d9f99d)',
                color: '#365314'
            },
            ai: {
                ...baseStyle,
                borderColor: '#a855f7',
                background: 'linear-gradient(135deg, #f3e8ff, #e9d5ff)',
                color: '#6b21a8'
            },
            timer: {
                ...baseStyle,
                borderColor: '#f97316',
                background: 'linear-gradient(135deg, #fed7aa, #fdba74)',
                color: '#9a3412'
            }
        };

        return nodeStyles[data.type] || nodeStyles.process;
    };

    const getNodeIcon = () => {
        const icons = {
            start: '🟢',
            end: '🔴',
            process: '⚙️',
            decision: '❓',
            api: '🌐',
            database: '💾',
            email: '📧',
            webhook: '🔗',
            ai: '🤖',
            timer: '⏰'
        };
        return icons[data.type] || '⚙️';
    };

    return (
        <div style={getNodeStyle()}>
            <div style={{ fontSize: '18px', marginBottom: '4px' }}>
                {getNodeIcon()}
            </div>
            <div>{data.label}</div>
            {data.description && (
                <div style={{ 
                    fontSize: '11px', 
                    opacity: 0.7, 
                    marginTop: '4px',
                    fontWeight: 'normal'
                }}>
                    {data.description}
                </div>
            )}
            
            {/* Connection handles */}
            <div className="react-flow__handle react-flow__handle-top"
                 style={{ 
                     background: '#374151', 
                     width: '8px', 
                     height: '8px',
                     border: '2px solid white'
                 }} />
            <div className="react-flow__handle react-flow__handle-bottom"
                 style={{ 
                     background: '#374151', 
                     width: '8px', 
                     height: '8px',
                     border: '2px solid white'
                 }} />
            <div className="react-flow__handle react-flow__handle-left"
                 style={{ 
                     background: '#374151', 
                     width: '8px', 
                     height: '8px',
                     border: '2px solid white'
                 }} />
            <div className="react-flow__handle react-flow__handle-right"
                 style={{ 
                     background: '#374151', 
                     width: '8px', 
                     height: '8px',
                     border: '2px solid white'
                 }} />
        </div>
    );
};

// Node types configuration
const nodeTypes = {
    customNode: CustomNode
};

// Edge types configuration
const edgeTypes = {
    default: {
        type: 'smoothstep',
        animated: true,
        style: { 
            strokeWidth: 3,
            stroke: '#6366f1'
        },
        markerEnd: {
            type: MarkerType.ArrowClosed,
            color: '#6366f1',
            width: 20,
            height: 20
        }
    }
};

// Main FlowCraft App Component
const FlowCraftApp = () => {
    const [nodes, setNodes, onNodesChange] = useNodesState([]);
    const [edges, setEdges, onEdgesChange] = useEdgesState([]);
    const [selectedNode, setSelectedNode] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const [flowStats, setFlowStats] = useState({ nodes: 0, connections: 0, complexity: 0 });
    const [activeTab, setActiveTab] = useState('nodes');
    const [undoStack, setUndoStack] = useState([]);
    const [redoStack, setRedoStack] = useState([]);
    const [collaborators, setCollaborators] = useState([]);
    const [isExecuting, setIsExecuting] = useState(false);
    
    const reactFlowWrapper = useRef(null);
    const reactFlowInstance = useRef(null);

    // Initialize default flow
    useEffect(() => {
        const initialNodes = [
            {
                id: 'start-1',
                type: 'customNode',
                position: { x: 100, y: 100 },
                data: { 
                    label: 'Start Process',
                    type: 'start',
                    description: 'Initial node'
                }
            },
            {
                id: 'process-1',
                type: 'customNode',
                position: { x: 300, y: 200 },
                data: { 
                    label: 'Process Data',
                    type: 'process',
                    description: 'Main processing step'
                }
            },
            {
                id: 'end-1',
                type: 'customNode',
                position: { x: 500, y: 300 },
                data: { 
                    label: 'End Process',
                    type: 'end',
                    description: 'Final node'
                }
            }
        ];

        const initialEdges = [
            {
                id: 'e1-2',
                source: 'start-1',
                target: 'process-1',
                type: 'smoothstep',
                animated: true,
                style: { strokeWidth: 3, stroke: '#6366f1' },
                markerEnd: { type: MarkerType.ArrowClosed, color: '#6366f1' }
            },
            {
                id: 'e2-3',
                source: 'process-1',
                target: 'end-1',
                type: 'smoothstep',
                animated: true,
                style: { strokeWidth: 3, stroke: '#6366f1' },
                markerEnd: { type: MarkerType.ArrowClosed, color: '#6366f1' }
            }
        ];

        setNodes(initialNodes);
        setEdges(initialEdges);
        updateFlowStats(initialNodes, initialEdges);
    }, []);

    // Update flow statistics
    const updateFlowStats = useCallback((currentNodes, currentEdges) => {
        const nodeCount = currentNodes.length;
        const connectionCount = currentEdges.length;
        const complexity = Math.floor((nodeCount * 2) + (connectionCount * 1.5));
        
        setFlowStats({ 
            nodes: nodeCount, 
            connections: connectionCount, 
            complexity 
        });

        // Update DOM elements
        const nodeCountEl = document.getElementById('node-count');
        const connectionCountEl = document.getElementById('connection-count');
        const complexityScoreEl = document.getElementById('complexity-score');

        if (nodeCountEl) nodeCountEl.textContent = nodeCount;
        if (connectionCountEl) connectionCountEl.textContent = connectionCount;
        if (complexityScoreEl) complexityScoreEl.textContent = complexity;
    }, []);

    // Handle connecting nodes
    const onConnect = useCallback((params) => {
        const newEdge = {
            ...params,
            type: 'smoothstep',
            animated: true,
            style: { strokeWidth: 3, stroke: '#6366f1' },
            markerEnd: { type: MarkerType.ArrowClosed, color: '#6366f1' }
        };
        setEdges((eds) => {
            const updatedEdges = addEdge(newEdge, eds);
            updateFlowStats(nodes, updatedEdges);
            return updatedEdges;
        });
    }, [nodes, updateFlowStats]);

    // Handle node selection
    const onNodeClick = useCallback((event, node) => {
        setSelectedNode(node);
        
        // Update properties panel
        const nameInput = document.getElementById('node-name');
        const descInput = document.getElementById('node-description');
        const timeoutInput = document.getElementById('node-timeout');
        const retryInput = document.getElementById('node-retry');

        if (nameInput) nameInput.value = node.data.label || '';
        if (descInput) descInput.value = node.data.description || '';
        if (timeoutInput) timeoutInput.value = node.data.timeout || '5000';
        if (retryInput) retryInput.value = node.data.retry || '3';
    }, []);

    // Handle drag over for drop functionality
    const onDragOver = useCallback((event) => {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
    }, []);

    // Handle dropping new nodes
    const onDrop = useCallback((event) => {
        event.preventDefault();

        const reactFlowBounds = reactFlowWrapper.current.getBoundingClientRect();
        const nodeType = event.dataTransfer.getData('application/reactflow');

        if (typeof nodeType === 'undefined' || !nodeType) {
            return;
        }

        const position = {
            x: event.clientX - reactFlowBounds.left,
            y: event.clientY - reactFlowBounds.top,
        };

        const newNode = {
            id: `${nodeType}-${Date.now()}`,
            type: 'customNode',
            position,
            data: { 
                label: `New ${nodeType.charAt(0).toUpperCase() + nodeType.slice(1)}`,
                type: nodeType,
                description: `${nodeType} node`
            }
        };

        setNodes((nds) => {
            const updatedNodes = nds.concat(newNode);
            updateFlowStats(updatedNodes, edges);
            return updatedNodes;
        });
    }, [edges, updateFlowStats]);

    // Auto-save functionality
    useEffect(() => {
        const autoSave = setInterval(() => {
            if (nodes.length > 0 || edges.length > 0) {
                const flowData = { nodes, edges, timestamp: Date.now() };
                localStorage.setItem('flowcraft-autosave', JSON.stringify(flowData));
                showMessage('Auto-saved', 'success');
            }
        }, 30000); // Auto-save every 30 seconds

        return () => clearInterval(autoSave);
    }, [nodes, edges]);

    // Message toast system
    const showMessage = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `message-toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    };

    // Handle React Flow instance
    const onInit = useCallback((reactFlowInstance) => {
        reactFlowInstance.current = reactFlowInstance;
    }, []);

    return (
        <div style={{ width: '100%', height: '100%' }}>
            <ReactFlow
                ref={reactFlowWrapper}
                nodes={nodes}
                edges={edges}
                onNodesChange={onNodesChange}
                onEdgesChange={onEdgesChange}
                onConnect={onConnect}
                onNodeClick={onNodeClick}
                onDrop={onDrop}
                onDragOver={onDragOver}
                onInit={onInit}
                nodeTypes={nodeTypes}
                fitView
                attributionPosition="bottom-left"
                style={{ background: 'transparent' }}
            >
                <Controls 
                    position="bottom-left"
                    style={{
                        background: 'white',
                        border: '1px solid #e5e7eb',
                        borderRadius: '8px',
                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                    }}
                />
                <MiniMap 
                    nodeColor={(node) => {
                        const colors = {
                            start: '#10b981',
                            end: '#ef4444',
                            process: '#6366f1',
                            decision: '#f59e0b',
                            api: '#06b6d4',
                            database: '#8b5cf6',
                            email: '#ec4899',
                            webhook: '#84cc16',
                            ai: '#a855f7',
                            timer: '#f97316'
                        };
                        return colors[node.data.type] || '#6366f1';
                    }}
                    style={{
                        background: 'white',
                        border: '1px solid #e5e7eb',
                        borderRadius: '8px',
                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                    }}
                />
                <Background 
                    color="#e5e7eb" 
                    gap={20} 
                    size={1}
                    style={{ opacity: 0.5 }}
                />
            </ReactFlow>
        </div>
    );
};

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('react-flow');
    if (container && typeof ReactDOM !== 'undefined') {
        const root = ReactDOM.createRoot(container);
        root.render(React.createElement(FlowCraftApp));
    }
});