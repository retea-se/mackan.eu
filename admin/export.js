// export.js - v3

window.initExportButtons = function (data) {
  const exportContainer = document.getElementById("exportTools");
  if (!exportContainer) {
    console.warn("⚠️ exportTools-div saknas – exportknappar skippas");
    return;
  }

  exportContainer.innerHTML = ""; // Töm om tidigare finns

  const formats = ["json", "csv", "txt"];
  formats.forEach(format => {
    const btn = document.createElement("button");
    btn.textContent = `⬇️ Exportera ${format.toUpperCase()}`;
    btn.className = "btn";
    btn.style.marginRight = "0.5em";
    btn.addEventListener("click", () => exportData(data, format));
    exportContainer.appendChild(btn);
  });
};

function exportData(data, format) {
  let content = "";
  const timestamp = new Date().toISOString().replace(/[:.]/g, "-");
  const filename = `export_${timestamp}.${format}`;

  if (format === "json") {
    content = JSON.stringify(data, null, 2);
  } else if (format === "csv") {
    const headers = Object.keys(data[0] || {});
    const csvRows = data.map(row =>
      headers.map(h => `"${(row[h] || "").toString().replace(/"/g, '""')}"`).join(",")
    );
    content = headers.join(",") + "\n" + csvRows.join("\n");
  } else if (format === "txt") {
    content = data.map(row => JSON.stringify(row)).join("\n");
  }

  const blob = new Blob([content], { type: "text/plain" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);

  console.log(`💾 Exporterat ${data.length} rader till ${filename}`);
}
