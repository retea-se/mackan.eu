// script.js

// Array som lagrar alla fält
let fields = [];

// Beskrivningar kopplade till varje "value" i <select>
const fieldTypeDescriptions = {
  text: "Enradigt textfält",
  password: "Maskad text (lösenord)",
  email: "E-postadress",
  url: "Webbadress",
  tel: "Telefonnummer",
  number: "Siffervärde",
  checkbox: "Kryssruta (på/av)",
  radio: "Radioknapp (envals)",
  select: "Rullgardinsmeny (<select>)",
  textarea: "Flerradig text",
  date: "Datumväljare",
  time: "Klockslag",
  "datetime-local": "Datum och tid (lokal)",
  week: "Veckonummer",
  month: "Månad/år",
  color: "Färgväljare",
  range: "Slider (range)",
  file: "Filuppladdning",
  button: "Allmän knapp",
  submit: "Skicka-knapp",
  reset: "Rensa formulär",
  image: "Bildknapp (submit)",
  search: "Sökinmatning"
};

// Hämta DOM-element
const templateNameInput = document.getElementById('templateName');
const templateTitle      = document.getElementById('templateTitle');

const fieldNameInput     = document.getElementById('fieldName');
const fieldTypeSelect    = document.getElementById('fieldType');
// Nytt: Hämta kommentar
const fieldComment       = document.getElementById('fieldComment');

const addFieldButton     = document.getElementById('addFieldButton');
const fieldsList         = document.getElementById('fieldsList');
const exportButton       = document.getElementById('exportButton');
const exportResult       = document.getElementById('exportResult');

// 1. Uppdatera mallens namn (rubriken) i realtid
templateNameInput.addEventListener('input', () => {
  // Om inget är ifyllt, visa en defaulttext
  templateTitle.textContent = templateNameInput.value.trim() || "Min Mall";
});

// 2. Lägg till ett nytt fält
addFieldButton.addEventListener('click', () => {
  const name = fieldNameInput.value.trim();
  const type = fieldTypeSelect.value;
  const comment = fieldComment.value.trim();  // Nytt

  if (!name) {
    alert("Var vänlig ange ett fältnamn.");
    return;
  }

  // Hämta svensk beskrivning utifrån fieldType
  const description = fieldTypeDescriptions[type] || "";

  // Skapa ett objekt som representerar fältet
  const newField = {
    fieldName: name,           
    fieldType: type,           
    description: description,  
    comment: comment           // Nytt
  };

  fields.push(newField);
  updateFieldsList();

  // Rensa inputfältet efter inmatning
  fieldNameInput.value = '';
  fieldComment.value = '';
  fieldNameInput.focus();
});

// 3. Funktion för att uppdatera listan av fält i UI
function updateFieldsList() {
  fieldsList.innerHTML = '';

  fields.forEach((field, index) => {
    const li = document.createElement('li');
    // Visa fältnamn, typ, description och ev. kommentar
    li.textContent = 
      `${field.fieldName} (${field.fieldType} - ${field.description})`
      + (field.comment ? ` | Kommentar: ${field.comment}` : '');

    // Skapa "Ta bort"-knapp
    const removeButton = document.createElement('button');
    removeButton.textContent = 'Ta bort';
    removeButton.style.marginLeft = '10px';
    removeButton.addEventListener('click', () => {
      fields.splice(index, 1);
      updateFieldsList();
    });

    li.appendChild(removeButton);
    fieldsList.appendChild(li);
  });
}

// 4. Exportera fält (och mallens namn) som JSON
exportButton.addEventListener('click', () => {
  const templateName = templateNameInput.value.trim() || "MinMall";

  // Bygg upp JSON-strukturen
  const data = {
    templateName: templateName,
    fields: fields
  };

  // Gör en JSON-sträng med indrag (2)
  const jsonStr = JSON.stringify(data, null, 2);

  // Visa resultatet på sidan
  exportResult.textContent = jsonStr;

  // Initiera nerladdning av filen, t.ex. "Datorinventarie v1.json"
  downloadJSON(jsonStr, templateName + '.json');
});

// En hjälpfunktion för att ladda ner JSON-innehåll som en fil
function downloadJSON(content, fileName) {
  const blob = new Blob([content], { type: "application/json" });
  const url = URL.createObjectURL(blob);

  // Skapa en temporär länk för nerladdning
  const a = document.createElement('a');
  a.href = url;
  a.download = fileName;
  a.click();

  // Rensa upp URL-objektet efteråt
  URL.revokeObjectURL(url);
}
