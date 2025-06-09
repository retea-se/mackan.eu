// ********** START main.js - v12 **********

const { createElement, useCallback, useState, useEffect } = React;
const { createRoot } = ReactDOM;
const RF = window.ReactFlow;

function FlowApp() {
  const {
    ReactFlow,
    addEdge,
    MiniMap,
    Controls,
    Background,
    applyNodeChanges,
    applyEdgeChanges,
  } = RF;

  const [nodes, setNodes] = useState([
    { id: '1', data: { label: 'Start' }, position: { x: 100, y: 100 } },
    { id: '2', data: { label: 'Slut' }, position: { x: 300, y: 100 } },
  ]);
  const [edges, setEdges] = useState([{ id: 'e1-2', source: '1', target: '2' }]);
  const [idCounter, setIdCounter] = useState(3);
  const [selectedNode, setSelectedNode] = useState(null);

  const onConnect = useCallback((params) => {
    console.log('Ny koppling:', params);
    setEdges((eds) => addEdge(params, eds));
  }, []);

  const onNodesChange = useCallback((changes) => {
    setNodes((nds) => applyNodeChanges(changes, nds));
  }, []);

  const onEdgesChange = useCallback((changes) => {
    setEdges((eds) => applyEdgeChanges(changes, eds));
  }, []);

  const onNodeClick = (_, node) => {
    setSelectedNode(node.id);
    console.log("🟢 Vald nod:", node.id);
  };

  useEffect(() => {
    const addBtn = document.getElementById('add-node');
    const deleteBtn = document.getElementById('delete-node');
    const exportBtn = document.getElementById('export-flow');
    const importInput = document.getElementById('import-input');
    const dragItem = document.querySelector('.drag-item');

    addBtn.onclick = () => {
      const newId = idCounter.toString();
      const newNode = {
        id: newId,
        data: { label: `Nod ${newId}` },
        position: {
          x: Math.random() * 400 + 100,
          y: Math.random() * 300 + 100,
        },
      };
      setNodes((nds) => [...nds, newNode]);
      setIdCounter((id) => id + 1);
      console.log("➕ Ny nod skapad:", newNode);
    };

    deleteBtn.onclick = () => {
      if (!selectedNode) return;
      setNodes((nds) => nds.filter((n) => n.id !== selectedNode));
      setEdges((eds) => eds.filter((e) => e.source !== selectedNode && e.target !== selectedNode));
      setSelectedNode(null);
      console.log("🗑️ Noden togs bort");
    };

    exportBtn.onclick = () => {
      const json = JSON.stringify({ nodes, edges }, null, 2);
      navigator.clipboard.writeText(json).then(() => {
        console.log("📤 Flow kopierad till urklipp");
      });
    };

    importInput.onkeydown = (e) => {
      if (e.key === 'Enter') {
        try {
          const obj = JSON.parse(e.target.value);
          setNodes(obj.nodes || []);
          setEdges(obj.edges || []);
          console.log("📥 Flow importerad");
        } catch (err) {
          console.warn("❌ Ogiltig JSON");
        }
      }
    };

    dragItem.addEventListener('dragstart', (e) => {
      e.dataTransfer.setData('application/reactflow', 'default');
      e.dataTransfer.effectAllowed = 'move';
    });

    document.addEventListener('keydown', (e) => {
      if ((e.key === 'Delete' || e.key === 'Backspace') && selectedNode) {
        setNodes((nds) => nds.filter((n) => n.id !== selectedNode));
        setEdges((eds) => eds.filter((e) => e.source !== selectedNode && e.target !== selectedNode));
        setSelectedNode(null);
        console.log("⌨️ Noden togs bort med Delete");
      }
    });
  }, [nodes, edges, selectedNode, idCounter]);

  const onDrop = (event) => {
    event.preventDefault();
    const type = event.dataTransfer.getData('application/reactflow');
    if (!type) return;

    const bounds = event.target.getBoundingClientRect();
    const position = {
      x: event.clientX - bounds.left,
      y: event.clientY - bounds.top,
    };
    const id = idCounter.toString();
    const newNode = {
      id,
      type,
      position,
      data: { label: `Drag-in ${id}` },
    };
    setNodes((nds) => [...nds, newNode]);
    setIdCounter((id) => id + 1);
    console.log("📦 Nod droppad:", newNode);
  };

  const onDragOver = (event) => {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
  };

  return createElement('div', { className: 'reactflow-wrapper' },
    createElement(ReactFlow, {
      nodes,
      edges,
      onNodesChange,
      onEdgesChange,
      onConnect,
      onNodeClick,
      onDrop,
      onDragOver,
      fitView: true,
      children: [
        createElement(MiniMap, { key: 'minimap' }),
        createElement(Controls, { key: 'controls' }),
        createElement(Background, { key: 'background' }),
      ]
    })
  );
}

const root = createRoot(document.getElementById('root'));
root.render(createElement(FlowApp));

console.log("main.js - v12 (återställning från v9)");
// ********** SLUT main.js - v12 **********
