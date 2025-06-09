let konverteradData;
let filnamn;
let dataTable;
let chart;

function konvertera() {
    const fileInput = document.getElementById('fileInput');
    const exportButton = document.getElementById("exportButton");
    const statistikDiv = document.getElementById("statistik");

    if (!fileInput.files || fileInput.files.length === 0) {
        alert("Välj en fil först.");
        return;
    }

    const file = fileInput.files[0];
    filnamn = file.name.split('.')[0];
    const filtyp = file.name.split('.').pop().toLowerCase(); // Fix: Definiera filtyp här

    const reader = new FileReader();

    reader.onload = function (e) {
        const contents = e.target.result;
        let data;

        try {
            if (filtyp === 'csv') {
                data = Papa.parse(contents, { header: true, dynamicTyping: true }).data;
            } else if (filtyp === 'xlsx') {
                const workbook = XLSX.read(contents, { type: 'binary' });
                const sheetName = workbook.SheetNames[0];
                data = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
            } else if (filtyp === 'json') {
                data = JSON.parse(contents);
            } else {
                throw new Error("Filtypen stöds inte. Välj CSV, JSON eller XLSX.");
            }
        } catch (error) {
            alert("Fel vid parsing av filen: " + error.message);
            return;
        }

        visaFörhandsvisning(data);
        statistikDiv.innerHTML = visaStatistik(data);
        exportButton.style.display = "block";
        konverteradData = data;
    };

    if (filtyp === 'xlsx') {
        reader.readAsArrayBuffer(file);
    } else {
        reader.readAsText(file);
    }
}

function visaFörhandsvisning(data) {
    const dataTableElement = $('#dataTable');

    if ($.fn.DataTable.isDataTable('#dataTable')) {
        dataTable.destroy();
        dataTableElement.empty();
    }
    if (chart) chart.destroy();

    const columns = Object.keys(data[0]).map(key => ({ title: key, data: key }));

    dataTable = dataTableElement.DataTable({
        data: data,
        columns: columns,
        pageLength: 10
    });
}

function visaStatistik(data) {
    let statistikStr = `<p>Antal rader: ${data.length}</p>`;
    statistikStr += `<p>Antal kolumner: ${Object.keys(data[0]).length}</p>`;
    return statistikStr;
}

function exportera() {
    if (!konverteradData) {
        alert("Ingen data att exportera.");
        return;
    }

    const outputFormat = document.getElementById('outputFormat').value;
    let output;

    if (outputFormat === 'csv') {
        output = Papa.unparse(konverteradData);
    } else if (outputFormat === 'json') {
        output = JSON.stringify(konverteradData, null, 2);
    } else if (outputFormat === 'xlsx') {
        const worksheet = XLSX.utils.json_to_sheet(konverteradData);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
        output = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    }

    const blob = new Blob([output]);
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${filnamn}.${outputFormat}`;
    link.click();
}
function konvertera() {
    const fileInput = document.getElementById('fileInput');
    const exportButton = document.getElementById("exportButton");
    const statistikDiv = document.getElementById("statistik");

    if (!fileInput.files || fileInput.files.length === 0) {
        alert("Välj en fil först.");
        return;
    }

    const file = fileInput.files[0];
    filnamn = file.name.split('.')[0];
    const filtyp = file.name.split('.').pop().toLowerCase();

    const reader = new FileReader();

    reader.onload = function (e) {
        const contents = e.target.result;
        let data;
        let headers;

        try {
            if (filtyp === 'csv') {
                const parsedData = Papa.parse(contents, { header: true, dynamicTyping: true });
                headers = parsedData.meta.fields; // Kolumnnamn från CSV
                data = parsedData.data;
            } else if (filtyp === 'xlsx') {
                const workbook = XLSX.read(contents, { type: 'binary' });
                const sheetName = workbook.SheetNames[0];
                data = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
                headers = Object.keys(data[0]); // Kolumnnamn från XLSX
            } else if (filtyp === 'json') {
                data = JSON.parse(contents);
                headers = Object.keys(data[0]); // Kolumnnamn från JSON
            } else {
                throw new Error("Filtypen stöds inte. Välj CSV, JSON eller XLSX.");
            }

            // Validera data
            let inconsistentRows = [];
            data = data.filter((row, index) => {
                const rowKeys = Object.keys(row);
                if (JSON.stringify(headers) !== JSON.stringify(rowKeys)) {
                    inconsistentRows.push(index + 1);
                    return false; // Hoppa över raden
                }
                return true;
            });

            if (inconsistentRows.length > 0) {
                console.warn(`Inkonsekventa rader hoppades över: ${inconsistentRows.join(', ')}`);
                alert(`Följande rader hoppades över på grund av inkonsekvent kolumnstruktur: ${inconsistentRows.join(', ')}`);
            }

        } catch (error) {
            alert("Fel vid parsing av filen: " + error.message);
            return;
        }

        visaFörhandsvisning(data);
        statistikDiv.innerHTML = visaStatistik(data);
        exportButton.style.display = "block";
        konverteradData = data;
    };

    if (filtyp === 'xlsx') {
        reader.readAsArrayBuffer(file);
    } else {
        reader.readAsText(file);
    }
}
