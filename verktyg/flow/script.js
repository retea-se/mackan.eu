// script.js - v5.6 (optimerad)
console.log("🧠 script.js - v5.6 laddad");

const { createElement, StrictMode, useState, useCallback, useEffect } = React;
const { createRoot } = ReactDOM;
const {
  ReactFlow,
  Background,
  Controls,
  MiniMap,
  addEdge,
  useNodesState,
  useEdgesState,
  useReactFlow,
  ReactFlowProvider
} = window.ReactFlow;

function FlowApp() {
  console.log("🚀 FlowApp renderas");

  const initialNodes = [
    { id: "1", position: { x: 100, y: 100 }, data: { label: "Startnod" }, type: "start", className: "nod-start" },
    { id: "2", position: { x: 300, y: 100 }, data: { label: "Målnod" }, type: "slut", className: "nod-slut" }
  ];
  const initialEdges = [{ id: "e1-2", source: "1", target: "2", type: "default" }];

  const [nodes, setNodes, onNodesChange] = useNodesState(initialNodes);
  const [edges, setEdges, onEdgesChange] = useEdgesState(initialEdges);
  const [nodeCount, setNodeCount] = useState(3);
  const [selectedNodes, setSelectedNodes] = useState([]);

  window.setNodes = setNodes;
window.setEdges = setEdges; 
  const { getViewport } = useReactFlow();

  window.applyImportedFlow = (importedNodes, importedEdges) => {
    const nodesWithClass = importedNodes.map(n => ({
      ...n,
      className: n.type ? "nod-" + n.type : "nod-steg",
    }));
    setNodes(nodesWithClass);
    setEdges(importedEdges);
    console.log("✅ Flöde uppdaterat via import");
  };

  const addNode = () => {
    const id = nodeCount.toString();
    const lastNode = nodes[nodes.length - 1];
    const baseX = lastNode?.position?.x || 100;
    const baseY = lastNode?.position?.y || 100;
    const offsetY = 60;
    const newPos = { x: baseX, y: baseY + offsetY };
    const type = "steg";

    const newNode = {
      id,
      data: { label: `Nod ${id}` },
      position: newPos,
      type,
      className: "nod-" + type
    };

    console.log("➕ Ny nod tillagd:");
    console.table({
      id,
      underNod: lastNode?.id || "ingen",
      position: newPos,
      zoom: getViewport().zoom
    });

    setNodes(nds => [...nds, newNode]);
    setNodeCount(n => n + 1);
  };

  window.addNode = addNode;

  const onConnect = useCallback(params => {
    console.log("➕ Koppling skapad:", params);
    setEdges(eds => addEdge(params, eds));
  }, [setEdges]);

  const onNodeClick = useCallback((_, node) => {
    console.log("🖱️ Nod klickad:", node);
  }, []);

  const onEdgeClick = useCallback((_, edge) => {
    console.log("🔗 Kant klickad:", edge);
  }, []);

  const onNodesChangeWrapper = useCallback(changes => {
    onNodesChange(changes);
    const selected = changes
      .filter(c => c.selected !== undefined)
      .map(c => c.id);
    if (selected.length > 0) setSelectedNodes(selected);
  }, [onNodesChange]);

  const deleteSelectedNodes = () => {
    if (selectedNodes.length === 0) return;
    console.log("🗑️ Raderar noder:", selectedNodes);
    setNodes(nds => nds.filter(n => !selectedNodes.includes(n.id)));
    setEdges(eds => eds.filter(e => !selectedNodes.includes(e.source) && !selectedNodes.includes(e.target)));
    setSelectedNodes([]);
  };

  useEffect(() => {
    const handleKeyDown = e => {
      if (e.key === "Delete" || e.key === "Backspace") {
        deleteSelectedNodes();
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [selectedNodes]);

  const onNodeDoubleClick = useCallback((_, node) => {
    const newLabel = prompt("Ändra nodens text:", node.data.label);
    if (newLabel !== null) {
      setNodes(nds =>
        nds.map(n => (n.id === node.id ? { ...n, data: { ...n.data, label: newLabel } } : n))
      );
      console.log(`✏️ Nod ${node.id} ändrad till: "${newLabel}"`);
    }
  }, [setNodes]);

  window.flowData = { nodes, edges };

  return createElement(
    "div",
    { style: { width: "100vw", height: "100vh", position: "relative" } },
    createElement(ReactFlow, {
      nodes,
      edges,
      onNodesChange: onNodesChangeWrapper,
      onEdgesChange,
      onConnect,
      onNodeClick,
      onNodeDoubleClick,
      onEdgeClick,
      fitView: true,
      children: [
        createElement(Background, { key: "bg" }),
        createElement(MiniMap, { key: "map" }),
        createElement(Controls, { key: "ctrl" })
      ]
    })
  );
}

const root = createRoot(document.getElementById("root"));
root.render(
  createElement(
    StrictMode,
    null,
    createElement(ReactFlowProvider, null, createElement(FlowApp))
  )
);

console.log("✅ FlowApp monterad i DOM");
