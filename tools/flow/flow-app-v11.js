// FlowCraft Pro - React Flow v11 Implementation
(() => {
    // Wait for libraries to load
    const waitForLibraries = () => {
        if (!window.React || !window.ReactDOM || !window.ReactFlow) {
            setTimeout(waitForLibraries, 100);
            return;
        }

        const { useState, useCallback, useRef, useEffect } = React;
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
            Handle,
            useReactFlow,
            ReactFlowProvider
        } = ReactFlow;

        // Custom Node Component
        const CustomNode = ({ data, isConnectable }) => {
            const nodeStyles = {
                start: {
                    background: 'linear-gradient(135deg, #d1fae5, #a7f3d0)',
                    borderColor: '#10b981',
                    color: '#065f46'
                },
                end: {
                    background: 'linear-gradient(135deg, #fee2e2, #fecaca)',
                    borderColor: '#ef4444',
                    color: '#991b1b'
                },
                process: {
                    background: 'linear-gradient(135deg, #e0e7ff, #c7d2fe)',
                    borderColor: '#6366f1',
                    color: '#3730a3'
                },
                decision: {
                    background: 'linear-gradient(135deg, #fef3c7, #fde68a)',
                    borderColor: '#f59e0b',
                    color: '#92400e'
                },
                api: {
                    background: 'linear-gradient(135deg, #cffafe, #a5f3fc)',
                    borderColor: '#06b6d4',
                    color: '#155e75'
                },
                database: {
                    background: 'linear-gradient(135deg, #ede9fe, #ddd6fe)',
                    borderColor: '#8b5cf6',
                    color: '#5b21b6'
                }
            };

            const style = nodeStyles[data.type] || nodeStyles.process;
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

            return (
                <>
                    <Handle type="target" position={Position.Top} isConnectable={isConnectable} />
                    <div style={{
                        padding: '12px 16px',
                        borderRadius: '12px',
                        border: `2px solid ${style.borderColor}`,
                        background: style.background,
                        color: style.color,
                        fontSize: '14px',
                        fontWeight: '600',
                        minWidth: '120px',
                        textAlign: 'center',
                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                    }}>
                        <div style={{ fontSize: '18px', marginBottom: '4px' }}>
                            {icons[data.type] || '⚙️'}
                        </div>
                        <div>{data.label}</div>
                        {data.description && (
                            <div style={{ fontSize: '11px', opacity: 0.7, marginTop: '4px', fontWeight: 'normal' }}>
                                {data.description}
                            </div>
                        )}
                    </div>
                    <Handle type="source" position={Position.Bottom} isConnectable={isConnectable} />
                    <Handle type="target" position={Position.Left} isConnectable={isConnectable} />
                    <Handle type="source" position={Position.Right} isConnectable={isConnectable} />
                </>
            );
        };

        // Node types
        const nodeTypes = {
            customNode: CustomNode
        };

        // Main App Component
        const FlowApp = () => {
            const [nodes, setNodes, onNodesChange] = useNodesState([
                {
                    id: '1',
                    type: 'customNode',
                    position: { x: 200, y: 100 },
                    data: { label: 'Start Process', type: 'start', description: 'Initial node' }
                },
                {
                    id: '2',
                    type: 'customNode',
                    position: { x: 400, y: 200 },
                    data: { label: 'Process Data', type: 'process', description: 'Main processing' }
                }
            ]);

            const [edges, setEdges, onEdgesChange] = useEdgesState([
                {
                    id: 'e1-2',
                    source: '1',
                    target: '2',
                    type: 'smoothstep',
                    animated: true
                }
            ]);

            const reactFlowWrapper = useRef(null);
            const { screenToFlowPosition } = useReactFlow();

            const onConnect = useCallback((params) => {
                setEdges((eds) => addEdge({
                    ...params,
                    type: 'smoothstep',
                    animated: true,
                    markerEnd: {
                        type: MarkerType.ArrowClosed
                    }
                }, eds));
            }, [setEdges]);

            const onDragOver = useCallback((event) => {
                event.preventDefault();
                event.dataTransfer.dropEffect = 'move';
            }, []);

            const onDrop = useCallback((event) => {
                event.preventDefault();

                const type = event.dataTransfer.getData('application/reactflow');
                if (!type) return;

                const position = screenToFlowPosition({
                    x: event.clientX,
                    y: event.clientY,
                });

                const newNode = {
                    id: `${Date.now()}`,
                    type: 'customNode',
                    position,
                    data: { 
                        label: `${type.charAt(0).toUpperCase() + type.slice(1)}`,
                        type: type,
                        description: `New ${type} node`
                    }
                };

                setNodes((nds) => nds.concat(newNode));
            }, [screenToFlowPosition]);

            // Update stats
            useEffect(() => {
                const nodeCount = document.getElementById('node-count');
                const connectionCount = document.getElementById('connection-count');
                const complexityScore = document.getElementById('complexity-score');

                if (nodeCount) nodeCount.textContent = nodes.length;
                if (connectionCount) connectionCount.textContent = edges.length;
                if (complexityScore) complexityScore.textContent = Math.floor((nodes.length * 2) + (edges.length * 1.5));
            }, [nodes, edges]);

            return (
                <div ref={reactFlowWrapper} style={{ width: '100%', height: '100%' }}>
                    <ReactFlow
                        nodes={nodes}
                        edges={edges}
                        onNodesChange={onNodesChange}
                        onEdgesChange={onEdgesChange}
                        onConnect={onConnect}
                        onDrop={onDrop}
                        onDragOver={onDragOver}
                        nodeTypes={nodeTypes}
                        fitView
                        deleteKeyCode="Delete"
                    >
                        <Controls />
                        <MiniMap />
                        <Background variant="dots" gap={20} size={1} />
                    </ReactFlow>
                </div>
            );
        };

        // Wrapper with ReactFlowProvider
        const FlowAppWrapper = () => {
            return (
                <ReactFlowProvider>
                    <FlowApp />
                </ReactFlowProvider>
            );
        };

        // Setup drag handlers
        const setupDragHandlers = () => {
            document.querySelectorAll('.node-item').forEach(item => {
                item.addEventListener('dragstart', (e) => {
                    const nodeType = e.target.dataset.nodeType || e.target.closest('.node-item').dataset.nodeType;
                    e.dataTransfer.setData('application/reactflow', nodeType);
                    e.dataTransfer.effectAllowed = 'move';
                });
            });
        };

        // Render the app
        const container = document.getElementById('react-flow');
        if (container) {
            const root = ReactDOM.createRoot(container);
            root.render(<FlowAppWrapper />);
            console.log('✅ FlowCraft Pro v11 initialized!');
            setTimeout(setupDragHandlers, 100);
        }
    };

    waitForLibraries();
})();