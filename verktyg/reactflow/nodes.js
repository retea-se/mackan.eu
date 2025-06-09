// ********** START nodes.js - v1 **********

const { memo } = React;
const { Handle, Position, NodeToolbar } = window.ReactFlow;

export const CustomNode = memo(({ id, data }) => {
  const deleteNode = () => {
    if (typeof data.onDelete === 'function') {
      data.onDelete(id);
    }
  };

  const copyNode = () => {
    if (typeof data.onCopy === 'function') {
      data.onCopy(id);
    }
  };

  const expandNode = () => {
    alert("🔍 Expand för nod " + id);
  };

  return React.createElement(React.Fragment, null,
    React.createElement(NodeToolbar, {
      isVisible: data.toolbarVisible,
      position: data.toolbarPosition || Position.Top,
    },
      React.createElement("button", { onClick: deleteNode }, "❌"),
      React.createElement("button", { onClick: copyNode }, "📋"),
      React.createElement("button", { onClick: expandNode }, "🔍")
    ),
    React.createElement("div", { style: { padding: '10px 20px', border: '1px solid #999', borderRadius: '6px', background: '#fff' } },
      data.label
    ),
    React.createElement(Handle, { type: "target", position: Position.Left }),
    React.createElement(Handle, { type: "source", position: Position.Right })
  );
});
// ********** SLUT nodes.js - v1 **********
