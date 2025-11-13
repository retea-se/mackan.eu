// FlowCraft Pro - Working React Flow Implementation
const { useState, useCallback, useRef, useEffect, useMemo } = React;

// Initialize when ReactFlow is available
const initializeFlowApp = () => {
    if (!window.ReactFlow) {
        console.log('⏳ Waiting for ReactFlow to load...');
        setTimeout(initializeFlowApp, 100);
        return;
    }
    
    console.log('✅ ReactFlow loaded successfully!');
    
    // Destructure ReactFlow components
    const { 
        ReactFlow, 
        MiniMap, 
        Controls, 
        Background, 
        useNodesState, 
        useEdgesState, 
        addEdge,
        MarkerType,
        Position,
        Handle
    } = window.ReactFlow;

    // Custom Node Component with proper handles
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

        return React.createElement('div', { style: getNodeStyle() }, [
            React.createElement('div', { 
                key: 'icon',
                style: { fontSize: '18px', marginBottom: '4px' } 
            }, getNodeIcon()),
            React.createElement('div', { key: 'label' }, data.label),
            data.description && React.createElement('div', {
                key: 'description',
                style: { 
                    fontSize: '11px', 
                    opacity: 0.7, 
                    marginTop: '4px',
                    fontWeight: 'normal'
                }
            }, data.description),
            // Handles
            React.createElement(Handle, {
                key: 'handle-top',
                type: 'target',
                position: Position.Top,
                style: { 
                    background: '#374151', 
                    width: '8px', 
                    height: '8px',
                    border: '2px solid white'
                },
                isConnectable: isConnectable
            }),
            React.createElement(Handle, {
                key: 'handle-bottom',
                type: 'source',
                position: Position.Bottom,
                style: { 
                    background: '#374151', 
                    width: '8px', 
                    height: '8px',
                    border: '2px solid white'
                },
                isConnectable: isConnectable
            }),
            React.createElement(Handle, {
                key: 'handle-left',
                type: 'target',
                position: Position.Left,
                style: { 
                    background: '#374151', 
                    width: '8px', 
                    height: '8px',
                    border: '2px solid white'
                },
                isConnectable: isConnectable
            }),
            React.createElement(Handle, {
                key: 'handle-right',
                type: 'source',
                position: Position.Right,
                style: { 
                    background: '#374151', 
                    width: '8px', 
                    height: '8px',
                    border: '2px solid white'
                },
                isConnectable: isConnectable
            })
        ]);
    };

    // Node types configuration
    const nodeTypes = {
        customNode: CustomNode
    };

    // Default edge configuration
    const defaultEdgeOptions = {
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
    };

    // Main FlowCraft App Component
    const FlowCraftApp = () => {
        const [nodes, setNodes, onNodesChange] = useNodesState([]);
        const [edges, setEdges, onEdgesChange] = useEdgesState([]);
        const [selectedNode, setSelectedNode] = useState(null);
        const [reactFlowInstance, setReactFlowInstance] = useState(null);
        const reactFlowWrapper = useRef(null);
        const getId = () => `node_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

        // Initialize default flow
        useEffect(() => {
            const initialNodes = [
                {
                    id: 'start-1',
                    type: 'customNode',
                    position: { x: 200, y: 100 },
                    data: { 
                        label: 'Start Process',
                        type: 'start',
                        description: 'Initial node'
                    },
                    draggable: true,
                    selectable: true,
                    connectable: true
                },
                {
                    id: 'process-1',
                    type: 'customNode',
                    position: { x: 400, y: 200 },
                    data: { 
                        label: 'Process Data',
                        type: 'process',
                        description: 'Main processing step'
                    },
                    draggable: true,
                    selectable: true,
                    connectable: true
                }
            ];

            const initialEdges = [
                {
                    id: 'e1-2',
                    source: 'start-1',
                    target: 'process-1',
                    ...defaultEdgeOptions
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
            
            // Update DOM elements safely
            setTimeout(() => {
                const nodeCountEl = document.getElementById('node-count');
                const connectionCountEl = document.getElementById('connection-count');
                const complexityScoreEl = document.getElementById('complexity-score');

                if (nodeCountEl) nodeCountEl.textContent = nodeCount;
                if (connectionCountEl) connectionCountEl.textContent = connectionCount;
                if (complexityScoreEl) complexityScoreEl.textContent = complexity;
            }, 100);
        }, []);

        // Handle connecting nodes
        const onConnect = useCallback((params) => {
            const newEdge = {
                ...params,
                ...defaultEdgeOptions
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
            setTimeout(() => {
                const nameInput = document.getElementById('node-name');
                const descInput = document.getElementById('node-description');
                const timeoutInput = document.getElementById('node-timeout');
                const retryInput = document.getElementById('node-retry');

                if (nameInput) nameInput.value = node.data.label || '';
                if (descInput) descInput.value = node.data.description || '';
                if (timeoutInput) timeoutInput.value = node.data.timeout || '5000';
                if (retryInput) retryInput.value = node.data.retry || '3';
            }, 50);
        }, []);

        // Handle drag over for drop functionality
        const onDragOver = useCallback((event) => {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        }, []);

        // Handle dropping new nodes
        const onDrop = useCallback((event) => {
            event.preventDefault();

            const nodeType = event.dataTransfer.getData('application/reactflow');

            if (typeof nodeType === 'undefined' || !nodeType) {
                return;
            }

            // Use screenToFlowPosition instead of deprecated project
            const position = reactFlowInstance.screenToFlowPosition({
                x: event.clientX,
                y: event.clientY,
            });

            const newNode = {
                id: getId(),
                type: 'customNode',
                position,
                data: { 
                    label: `${nodeType.charAt(0).toUpperCase() + nodeType.slice(1)}`,
                    type: nodeType,
                    description: `${nodeType} node`
                },
                draggable: true,
                selectable: true,
                connectable: true
            };

            setNodes((nds) => {
                const updatedNodes = nds.concat(newNode);
                updateFlowStats(updatedNodes, edges);
                return updatedNodes;
            });

            // Show success message
            showMessage(`✨ Added ${nodeType} node!`, 'success');
        }, [reactFlowInstance, edges, updateFlowStats]);

        // Message toast system
        const showMessage = (message, type = 'success') => {
            const toast = document.createElement('div');
            toast.className = `message-toast ${type}`;
            toast.innerHTML = `<span>${message}</span>`;
            
            // Toast styles
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#6366f1'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                animation: slideIn 0.3s ease-out;
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
            }, 3000);
        };

        // Initialize React Flow instance
        const onInit = useCallback((rfi) => {
            setReactFlowInstance(rfi);
            console.log('✅ React Flow initialized successfully!');
        }, []);

        // Handle node changes with stats update
        const handleNodesChange = useCallback((changes) => {
            onNodesChange(changes);
            setTimeout(() => updateFlowStats(nodes, edges), 100);
        }, [onNodesChange, nodes, edges, updateFlowStats]);

        // Handle edge changes with stats update
        const handleEdgesChange = useCallback((changes) => {
            onEdgesChange(changes);
            setTimeout(() => updateFlowStats(nodes, edges), 100);
        }, [onEdgesChange, nodes, edges, updateFlowStats]);

        return React.createElement('div', {
            style: { width: '100%', height: '100%' },
            ref: reactFlowWrapper
        }, React.createElement(ReactFlow, {
            nodes: nodes,
            edges: edges,
            onNodesChange: handleNodesChange,
            onEdgesChange: handleEdgesChange,
            onConnect: onConnect,
            onNodeClick: onNodeClick,
            onDrop: onDrop,
            onDragOver: onDragOver,
            onInit: onInit,
            nodeTypes: nodeTypes,
            defaultEdgeOptions: defaultEdgeOptions,
            fitView: true,
            snapToGrid: true,
            snapGrid: [20, 20],
            attributionPosition: 'bottom-left',
            style: { background: 'transparent' },
            deleteKeyCode: 'Delete',
            nodesDraggable: true,
            nodesConnectable: true,
            elementsSelectable: true,
            selectNodesOnDrag: false,
            panOnDrag: true,
            zoomOnScroll: true,
            zoomOnPinch: true,
            panOnScroll: false,
            preventScrolling: true,
            nodeOrigin: [0.5, 0.5]
        }, [
            React.createElement(Controls, {
                key: 'controls',
                position: 'bottom-left',
                style: {
                    background: 'white',
                    border: '1px solid #e5e7eb',
                    borderRadius: '8px',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                }
            }),
            React.createElement(MiniMap, {
                key: 'minimap',
                nodeColor: (node) => {
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
                },
                style: {
                    background: 'white',
                    border: '1px solid #e5e7eb',
                    borderRadius: '8px',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                },
                position: 'bottom-right'
            }),
            React.createElement(Background, {
                key: 'background',
                color: '#e5e7eb',
                gap: 20,
                size: 1,
                style: { opacity: 0.5 }
            })
        ]));
    };

    // Setup drag and drop for node palette
    const setupDragAndDrop = () => {
        const nodeItems = document.querySelectorAll('.node-item');
        nodeItems.forEach(item => {
            item.addEventListener('dragstart', (e) => {
                const nodeType = e.target.dataset.nodeType || e.target.closest('.node-item').dataset.nodeType;
                e.dataTransfer.setData('application/reactflow', nodeType);
                e.dataTransfer.effectAllowed = 'move';
                e.target.style.opacity = '0.5';
            });
            
            item.addEventListener('dragend', (e) => {
                e.target.style.opacity = '1';
            });
        });
    };
    
    // Initialize the React Flow app
    const container = document.getElementById('react-flow');
    if (container && typeof ReactDOM !== 'undefined') {
        const root = ReactDOM.createRoot(container);
        root.render(React.createElement(FlowCraftApp));
        console.log('🚀 FlowCraft Pro initialized successfully!');
        
        // Setup drag and drop
        setTimeout(setupDragAndDrop, 100);
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initializeFlowApp, 200);
});

// Add animation styles
const animationStyles = document.createElement('style');
animationStyles.textContent = `
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

.node-item {
    cursor: grab;
    user-select: none;
}

.node-item:active {
    cursor: grabbing;
}

.react-flow__node-customNode:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.react-flow__edge:hover {
    stroke-width: 4px !important;
}

.react-flow__handle {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.react-flow__node:hover .react-flow__handle {
    opacity: 1;
}
`;
document.head.appendChild(animationStyles);