// FlowCraft Pro - Simple React Flow Implementation
document.addEventListener('DOMContentLoaded', function() {
    // Wait for React Flow to load
    function initializeFlow() {
        if (!window.ReactFlow) {
            setTimeout(initializeFlow, 100);
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
            ReactFlowProvider,
            useReactFlow
        } = window.ReactFlow;

        // Custom Node Component
        function CustomNode({ data }) {
            const nodeStyles = {
                start: { bg: '#d1fae5', border: '#10b981', color: '#065f46' },
                end: { bg: '#fee2e2', border: '#ef4444', color: '#991b1b' },
                process: { bg: '#e0e7ff', border: '#6366f1', color: '#3730a3' },
                decision: { bg: '#fef3c7', border: '#f59e0b', color: '#92400e' },
                api: { bg: '#cffafe', border: '#06b6d4', color: '#155e75' },
                database: { bg: '#ede9fe', border: '#8b5cf6', color: '#5b21b6' }
            };

            const style = nodeStyles[data.type] || nodeStyles.process;
            const icons = {
                start: '🟢', end: '🔴', process: '⚙️', decision: '❓',
                api: '🌐', database: '💾', email: '📧', webhook: '🔗',
                ai: '🤖', timer: '⏰'
            };

            return React.createElement('div', {
                style: {
                    position: 'relative',
                    padding: '12px 16px',
                    borderRadius: '12px',
                    border: `2px solid ${style.border}`,
                    background: style.bg,
                    color: style.color,
                    fontSize: '14px',
                    fontWeight: '600',
                    minWidth: '120px',
                    textAlign: 'center',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                }
            }, [
                React.createElement(Handle, {
                    key: 'target-top',
                    type: 'target',
                    position: Position.Top,
                    style: { background: '#555' }
                }),
                React.createElement('div', {
                    key: 'icon',
                    style: { fontSize: '18px', marginBottom: '4px' }
                }, icons[data.type] || '⚙️'),
                React.createElement('div', { key: 'label' }, data.label),
                React.createElement(Handle, {
                    key: 'source-bottom',
                    type: 'source',
                    position: Position.Bottom,
                    style: { background: '#555' }
                }),
                React.createElement(Handle, {
                    key: 'target-left',
                    type: 'target',
                    position: Position.Left,
                    style: { background: '#555' }
                }),
                React.createElement(Handle, {
                    key: 'source-right',
                    type: 'source',
                    position: Position.Right,
                    style: { background: '#555' }
                })
            ]);
        }

        const nodeTypes = { customNode: CustomNode };

        // Flow App Component
        function FlowApp() {
            const reactFlowInstance = useRef(null);
            const [nodes, setNodes, onNodesChange] = useNodesState([
                {
                    id: '1',
                    type: 'customNode',
                    position: { x: 250, y: 100 },
                    data: { label: 'Start', type: 'start' }
                },
                {
                    id: '2',
                    type: 'customNode',
                    position: { x: 450, y: 200 },
                    data: { label: 'Process', type: 'process' }
                }
            ]);

            const [edges, setEdges, onEdgesChange] = useEdgesState([
                {
                    id: 'e1-2',
                    source: '1',
                    target: '2',
                    animated: true
                }
            ]);

            const onConnect = useCallback((params) => {
                setEdges((eds) => addEdge(params, eds));
            }, []);

            const onInit = useCallback((instance) => {
                reactFlowInstance.current = instance;
                console.log('Flow instance initialized');
            }, []);

            const onDragOver = useCallback((event) => {
                event.preventDefault();
                event.dataTransfer.dropEffect = 'move';
            }, []);

            const onDrop = useCallback((event) => {
                event.preventDefault();
                
                const type = event.dataTransfer.getData('application/reactflow');
                if (!type || !reactFlowInstance.current) return;

                const position = reactFlowInstance.current.screenToFlowPosition({
                    x: event.clientX,
                    y: event.clientY,
                });

                const newNode = {
                    id: `${Date.now()}`,
                    type: 'customNode',
                    position,
                    data: { label: type, type: type }
                };

                setNodes((nds) => nds.concat(newNode));
            }, []);

            // Update stats
            useEffect(() => {
                document.getElementById('node-count').textContent = nodes.length;
                document.getElementById('connection-count').textContent = edges.length;
                document.getElementById('complexity-score').textContent = nodes.length + edges.length;
            }, [nodes, edges]);

            return React.createElement(ReactFlow, {
                nodes: nodes,
                edges: edges,
                onNodesChange: onNodesChange,
                onEdgesChange: onEdgesChange,
                onConnect: onConnect,
                onInit: onInit,
                onDrop: onDrop,
                onDragOver: onDragOver,
                nodeTypes: nodeTypes,
                fitView: true,
                attributionPosition: 'bottom-left'
            }, [
                React.createElement(Controls, { key: 'controls' }),
                React.createElement(MiniMap, { key: 'minimap' }),
                React.createElement(Background, { key: 'bg', variant: 'dots' })
            ]);
        }

        // Render app
        const container = document.getElementById('react-flow');
        if (container) {
            const root = ReactDOM.createRoot(container);
            root.render(React.createElement(ReactFlowProvider, {},
                React.createElement(FlowApp)
            ));
            
            // Setup drag handlers
            setTimeout(() => {
                document.querySelectorAll('.node-item').forEach(item => {
                    item.addEventListener('dragstart', (e) => {
                        const nodeType = e.target.dataset.nodeType || 
                                       e.target.closest('.node-item').dataset.nodeType;
                        e.dataTransfer.setData('application/reactflow', nodeType);
                        e.dataTransfer.effectAllowed = 'move';
                    });
                });
            }, 100);
            
            console.log('✅ FlowCraft initialized successfully!');
        }
    }

    initializeFlow();
});