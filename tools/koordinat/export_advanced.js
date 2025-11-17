/**
 * export_advanced.js - Exporterar resultat-tabellen till CSV (v2 - Modal-baserade meddelanden)
 *
 * Funktionalitet:
 * - Exporterar synliga resultat i tabellen
 * - Rubriker inkluderas högst upp
 * - UTF-8 BOM används för att stödja svenska tecken
 * - CSV-filen namnges med aktuell datum/tid
 */

// Import modal utilities
import { showAlert } from '/js/modal-utils.js';

console.log("[DEBUG] Laddar export_advanced.js");

// Vänta tills DOM är redo innan vi kopplar eventlisteners
document.addEventListener("DOMContentLoaded", function () {
    console.log("[DEBUG] DOM laddad, kopplar export-knapp");

    const exportButton = document.getElementById("export-button");
    if (exportButton) {
        exportButton.addEventListener("click", exportToCSV);
    } else {
        console.error("[ERROR] Export-knappen hittades inte i DOM!");
    }
});

/**
 * Hämta tabellens innehåll och exportera som CSV
 */
function exportToCSV() {
    console.log("[DEBUG] Exportera-knappen klickad");

    const table = document.getElementById("result-table");
    if (!table) {
        console.error("[ERROR] Ingen resultat-tabell hittades!");
        showAlert("Ingen data att exportera.", "Information");
        return;
    }

    let csvContent = "\uFEFF"; // UTF-8 BOM för svenska tecken

    // Hämta rubriker från thead
    let headers = [];
    table.querySelectorAll("thead tr th").forEach(th => headers.push(th.innerText.trim()));
    csvContent += headers.join(";") + "\n"; // Semikolon-separerad

    // Hämta data från tbody
    let rows = [];
    table.querySelectorAll("tbody tr").forEach(row => {
        let rowData = [];
        row.querySelectorAll("td").forEach(td => rowData.push(td.innerText.trim()));
        rows.push(rowData.join(";"));
    });

    csvContent += rows.join("\n");

    // Skapa filnamn med datum/tid
    let timestamp = new Date().toISOString().slice(0, 19).replace("T", "_").replace(/:/g, "-");
    let filename = `Export_Koordinater_${timestamp}.csv`;

    // Skapa och ladda ner filen
    let blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    let link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    console.log("[DEBUG] CSV-export slutförd:", filename);
}
