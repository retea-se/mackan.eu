/**
 * export.js - Hanterar export av koordinater
 *
 * Funktionalitet:
 * - Export till CSV, JSON och Excel
 * - Korrekt rubriker vid export
 * - Teckenuppsättning (UTF-8 BOM) för svenska tecken
 * - Genererar filnamn med datum/tid
 */

console.log("[DEBUG] Laddar export.js");

// Vänta tills DOM är redo
document.addEventListener("DOMContentLoaded", function () {
    console.log("[DEBUG] DOM laddad, initierar export event listeners");

    const exportCsvButton = document.getElementById("export-csv");
    const exportJsonButton = document.getElementById("export-json");
    const exportXlsxButton = document.getElementById("export-xlsx");

    if (exportCsvButton) exportCsvButton.addEventListener("click", () => exportTable("csv"));
    if (exportJsonButton) exportJsonButton.addEventListener("click", () => exportTable("json"));
    if (exportXlsxButton) exportXlsxButton.addEventListener("click", () => exportTable("xlsx"));
});

function exportTable(type) {
    const table = document.getElementById("result-table");

    if (!table) {
        console.error("[ERROR] Ingen tabell hittades! Ingen data att exportera.");
        return;
    }

    let data = [];
    let headers = [];

    // 🔥 Hämta rubriker från tabellens thead, se till att det bara blir EN rad
    const headerCells = table.querySelectorAll("thead tr th");
    if (headerCells.length === 0) {
        console.error("[ERROR] Tabellrubriker saknas!");
        return;
    }
    headerCells.forEach(th => headers.push(th.innerText.trim()));

    // Hämta data från tbody och se till att INGA rubrikrader kommer med
    const rows = table.querySelectorAll("tbody tr");
    rows.forEach(row => {
        let rowData = [];
        row.querySelectorAll("td").forEach(td => rowData.push(td.innerText.trim()));
        data.push(rowData);
    });

    const filename = `Export_Koordinater_${getTimestamp()}.${type}`;

    if (type === "csv") {
        exportCSV(headers, data, filename);
    } else if (type === "json") {
        exportJSON(headers, data, filename);
    } else if (type === "xlsx") {
        exportXLSX(headers, data, filename);
    } else {
        console.error("[ERROR] Okänd exporttyp:", type);
    }
}


// 🔥 Exportera som CSV med UTF-8 BOM och rubriker
function exportCSV(headers, data, filename) {
    let csvContent = "\uFEFF" + headers.join(";") + "\n"; // UTF-8 BOM för svenska tecken

    data.forEach(row => {
        csvContent += row.join(";") + "\n"; // Använd semikolon (;) som separator
    });

    let blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    console.log("[DEBUG] CSV-export slutförd");
}

// 🔥 Exportera som JSON med rubriker
function exportJSON(headers, data, filename) {
    let jsonArray = data.map(row => {
        let obj = {};
        headers.forEach((header, index) => {
            obj[header] = row[index] || "N/A";
        });
        return obj;
    });

    let jsonString = JSON.stringify(jsonArray, null, 2);
    let blob = new Blob([jsonString], { type: "application/json" });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    console.log("[DEBUG] JSON-export slutförd");
}

// 🔥 Exportera som Excel med rubriker
function exportXLSX(headers, data, filename) {
    if (typeof XLSX === "undefined") {
        console.error("[ERROR] XLSX-biblioteket saknas. Lägg till SheetJS.");
        return;
    }

    let worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]);
    let workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Koordinater");

    let blob = XLSX.write(workbook, { bookType: "xlsx", type: "binary" });

    let buffer = new ArrayBuffer(blob.length);
    let view = new Uint8Array(buffer);
    for (let i = 0; i < blob.length; i++) {
        view[i] = blob.charCodeAt(i) & 0xff;
    }

    let xlsxBlob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

    let link = document.createElement("a");
    link.href = URL.createObjectURL(xlsxBlob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    console.log("[DEBUG] XLSX-export slutförd");
}


// Generera filnamn med aktuell tid
function getTimestamp() {
    const now = new Date();
    return now.toISOString().slice(0, 19).replace("T", "_").replace(/:/g, "-");
}
