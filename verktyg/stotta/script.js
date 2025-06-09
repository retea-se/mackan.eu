// Uppdaterar tiden på skärmen varje sekund
setInterval(updateClock, 1000);

function updateClock() {
    const now = new Date();
    const days = ['Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag'];
    const months = ['januari', 'februari', 'mars', 'april', 'maj', 'juni', 'juli', 'augusti', 'september', 'oktober', 'november', 'december'];

    const dayName = days[now.getDay()];
    const monthName = months[now.getMonth()];
    const dateStr = `${dayName} den ${now.getDate()} ${monthName} ${now.getFullYear()}`;
    const timeStr = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}:${now.getSeconds().toString().padStart(2, '0')}`;

    document.getElementById('date').innerText = dateStr;
    document.getElementById('time').innerText = timeStr;
}

function toggleTheme() {
    const body = document.body;
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        sunIcon.style.display = 'none'; // Dölj solikonen i ljust tema
        moonIcon.style.display = '';    // Visa måneikonen i ljust tema
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        sunIcon.style.display = '';     // Visa solikonen i mörkt tema
        moonIcon.style.display = 'none';// Dölj måneikonen i mörkt tema
        localStorage.setItem('theme', 'dark');
    }
}


function setInitialTheme() {
    const currentTheme = localStorage.getItem('theme'); // Hämta det sparade temat
    const body = document.body;
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    if (currentTheme === 'dark') {
        body.classList.add('dark-theme');
        sunIcon.style.display = 'none';
        moonIcon.style.display = '';
    } else {
        body.classList.remove('dark-theme');
        sunIcon.style.display = '';
        moonIcon.style.display = 'none';
    }
}

// Kör funktionen när sidan laddas
document.addEventListener('DOMContentLoaded', setInitialTheme);


function isLeapYear(year) {
    return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
}

function formatPnrForDisplay(pnr) {
    return pnr.substring(2, 8) + "-" + pnr.substring(8, 12);
}

function processPersonnummer() {
    console.log("Bearbeta-knappen klickades på!");
    // Initialisera räknare och totaler
    var maleCount = 0;
    var femaleCount = 0;
    var totalAge = 0;

    // Hämta inmatade personnummer och förbered tabellen för resultat
    var input = document.getElementById('personnummerList').value;
    var personnummerArray = input.split(/\r?\n/).filter(Boolean); // Filtrera bort tomma rader
    var resultsTable = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
    resultsTable.innerHTML = '';

    // Iterera genom varje personnummer och bearbeta det
    for (var i = 0; i < personnummerArray.length; i++) {
        var pnr = standardizePnrFormat(personnummerArray[i]);
        var errors = []; // Lista för att lagra felmeddelanden

        if (pnr) {
            var newRow = resultsTable.insertRow(resultsTable.rows.length);

            // Beräkna ålder
            var birthDate = new Date(pnr.substring(0, 4), pnr.substring(4, 6) - 1, pnr.substring(6, 8));
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            if ((today.getMonth() < birthDate.getMonth()) || 
                (today.getMonth() == birthDate.getMonth() && today.getDate() < birthDate.getDate()) || 
                birthDate > today) {
                age--;
            }

            // Lägg till personnummer i tabellen med varningssymbol om nödvändigt
            var cell1 = newRow.insertCell(0);
            if (age < 18 || age > 65) {
                var warningSign = document.createElement('span');
                warningSign.innerHTML = '⚠️';
                warningSign.style.marginRight = '5px';
                if (age < 18) {
                    warningSign.title = 'Avvikande ålder, minderårig';
                } else {
                    warningSign.title = 'Avvikande ålder, pensionär';
                }
                cell1.appendChild(warningSign);
            }
            cell1.innerHTML += formatPnrForDisplay(pnr);


            // Validera personnummer och lägg till i tabellen
            var cell2 = newRow.insertCell(1);
            var isValid = validateLuhn(pnr.substring(2));
            cell2.innerHTML = isValid ? 'OK' : 'EJ OK';

            // Lägg till kommentarer om fel i tabellen
            var cellComment = newRow.insertCell(2);
            if (!isValid) {
                // Kontrollera format: 12 siffror
if (!/^\d{12}$/.test(pnr)) {
    errors.push("Fel format");
}

// Kontrollera månad
var month = parseInt(pnr.substring(4, 6));
if (month < 1 || month > 12) {
    errors.push("Ogiltig månad");
}

// Kontrollera dag
var day = parseInt(pnr.substring(6, 8));
var maxDay = [0, 31, isLeapYear(parseInt(pnr.substring(0, 4))) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
if (day < 1 || day > maxDay) {
    errors.push("Ogiltig dag");
}



// Kontrollera om personnumret representerar ett framtida datum
var birthDate = new Date(pnr.substring(0, 4), month - 1, day);
var today = new Date();
if (birthDate > today) {
    errors.push("Framtida datum");
}

// Lägg till kontrollnummrets felmeddelande (om det inte redan finns)
if (!validateLuhn(pnr.substring(2)) && !errors.includes("Fel format")) {
    errors.push("Ogiltigt kontrollnummer");
}


               cellComment.innerHTML = errors.join("<br>");
cellComment.style.color = "red";

if (errors.length > 0) {
    var errorIcon = document.createElement('span');
    errorIcon.innerHTML = '❌';
    errorIcon.style.marginLeft = '5px';
    errorIcon.title = errors.join("\n");
    cellComment.appendChild(errorIcon);
}

            } else {
                cellComment.innerHTML = "✅"; // Grön bock
                cellComment.style.textAlign = "center";
                cellComment.style.color = "green";
            }

           // Bestäm kön och lägg till i tabellen
var cell3 = newRow.insertCell(3);
if (errors.length === 0) {
    var genderDigit = parseInt(pnr.charAt(pnr.length - 2));
    if (genderDigit % 2 === 0) {
        cell3.innerHTML = 'KVINNA';
        femaleCount++;
    } else {
        cell3.innerHTML = 'MAN';
        maleCount++;
    }
}
 
            
            

           // Lägg till ålder i tabellen
var cell4 = newRow.insertCell(4);
if (errors.length === 0) {
    cell4.innerHTML = age;
    totalAge += age;
}

            // Bestäm stjärntecken och lägg till i tabellen
var cell5 = newRow.insertCell(5);
if (errors.length === 0) {
    cell5.innerHTML = getZodiacSign(pnr.substring(4, 6), pnr.substring(6, 8));
}
// Beräkna dagar kvar till födelsedag och lägg till i tabellen
var cell6 = newRow.insertCell(6);
if (errors.length > 0) {
    cell6.innerHTML = '';
} else {
    var daysLeft = getDaysUntilBirthday(pnr.substring(4, 6), pnr.substring(6, 8));
    cell6.innerHTML = daysLeft;
}
    }

    // Beräkna och visa sammanfattning av resultaten
    var totalPersons = maleCount + femaleCount;
    var malePercentage = totalPersons > 0 ? Math.round((maleCount / totalPersons) * 100) : 0;
    var femalePercentage = totalPersons > 0 ? Math.round((femaleCount / totalPersons) * 100) : 0;
    
    var averageAge = personnummerArray.length > 0 ? Math.round(totalAge / personnummerArray.length) : 0;

    document.getElementById('maleCount').innerText = maleCount;
    document.getElementById('femaleCount').innerText = femaleCount;
    document.getElementById('malePercentage').innerText = malePercentage + '%';
    document.getElementById('femalePercentage').innerText = femalePercentage + '%';
    document.getElementById('averageAge').innerText = averageAge;

    document.getElementById('resultsTable').style.display = 'table';
    document.getElementById('summary').style.display = 'block';
    document.getElementById('resultsHeader').style.display = 'block';
    document.getElementById('exportButton').style.display = 'inline-block';
}}

function standardizePnrFormat(pnr) {
    pnr = pnr.trim().replace(/-/g, '');
    if (pnr.length === 10) {
        var currentYear = new Date().getFullYear();
        var pnrYear = parseInt(pnr.substring(0, 2));
        if (currentYear - 2000 < pnrYear) {
            pnr = '19' + pnr;
        } else {
            pnr = '20' + pnr;
        }
    }
    return pnr;
}


function validateLuhn(value) {
    var sum = 0;
    var numDigits = value.length;
    var parity = numDigits % 2;
    for (var i = 0; i < numDigits; i++) {
        var digit = parseInt(value.charAt(i));
        if (i % 2 == parity) digit *= 2;
        if (digit > 9) digit -= 9;
        sum += digit;
    }
    return (sum % 10) == 0;
}

function getZodiacSign(month, day) {
    month = parseInt(month);
    day = parseInt(day);
    if ((month == 12 && day >= 22) || (month == 1 && day <= 19)) return "Stenbocken ♑️";
    if ((month == 1 && day >= 20) || (month == 2 && day <= 18)) return "Vattumannen ♒️";
    if ((month == 2 && day >= 19) || (month == 3 && day <= 20)) return "Fiskarna ♓️";
    if ((month == 3 && day >= 21) || (month == 4 && day <= 19)) return "Väduren ♈️";
    if ((month == 4 && day >= 20) || (month == 5 && day <= 20)) return "Oxen ♉️";
    if ((month == 5 && day >= 21) || (month == 6 && day <= 21)) return "Tvillingarna ♊️";
    if ((month == 6 && day >= 22) || (month == 7 && day <= 22)) return "Kräftan ♋️";
    if ((month == 7 && day >= 23) || (month == 8 && day <= 22)) return "Lejonet ♌️";
    if ((month == 8 && day >= 23) || (month == 9 && day <= 22)) return "Jungfrun ♍️";
    if ((month == 9 && day >= 23) || (month == 10 && day <= 22)) return "Vågen ♎️";
    if ((month == 10 && day >= 23) || (month == 11 && day <= 21)) return "Skorpionen ♏️";
    if ((month == 11 && day >= 22) || (month == 12 && day <= 21)) return "Skytten ♐️";
}


function getDaysUntilBirthday(month, day) {
    var today = new Date();
    var birthDateThisYear = new Date(today.getFullYear(), month - 1, day);
    if (today > birthDateThisYear) {
        var birthDateNextYear = new Date(today.getFullYear() + 1, month - 1, day);
        return Math.floor((birthDateNextYear - today) / (1000 * 60 * 60 * 24));
    } else {
        return Math.floor((birthDateThisYear - today) / (1000 * 60 * 60 * 24));
    }
}



function exportTableToExcel() {
    var table = document.getElementById('resultsTable');
    var wb = XLSX.utils.table_to_book(table, { sheet: "Resultat" });
    var wbout = XLSX.write(wb, { bookType: 'xlsx', bookSST: true, type: 'binary' });

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }

    saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), 'resultat.xlsx');
}

document.getElementById('exportButton').addEventListener('click', exportTableToExcel);

document.addEventListener("DOMContentLoaded", function() {
    var currentYear = new Date().getFullYear();
    document.getElementById('copyrightFooter').innerHTML = "Copyright " + currentYear;
});