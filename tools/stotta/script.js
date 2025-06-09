// script.js - v3
// Verifierar och analyserar svenska personnummer

// ********** START Hjälpfunktioner **********

function standardizePnrFormat(pnr) {
  pnr = pnr.trim().replace(/-/g, '');
  if (pnr.length === 10) {
    const year = parseInt(pnr.substring(0, 2));
    const century = (year > new Date().getFullYear() % 100) ? '19' : '20';
    return century + pnr;
  }
  return pnr;
}

function validateLuhn(value) {
  let sum = 0;
  for (let i = 0; i < value.length; i++) {
    let digit = parseInt(value.charAt(i));
    if (i % 2 === value.length % 2) digit *= 2;
    if (digit > 9) digit -= 9;
    sum += digit;
  }
  return sum % 10 === 0;
}

function isLeapYear(year) {
  return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
}

function formatPnrForDisplay(pnr) {
  return pnr.substring(2, 8) + "-" + pnr.substring(8, 12);
}

function getZodiacSign(month, day) {
  month = parseInt(month); day = parseInt(day);
  if ((month == 1 && day <= 19) || (month == 12 && day >= 22)) return "Stenbocken ♑️";
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
  const today = new Date();
  let bday = new Date(today.getFullYear(), month - 1, day);
  if (today > bday) bday.setFullYear(today.getFullYear() + 1);
  return Math.floor((bday - today) / (1000 * 60 * 60 * 24));
}

// ********** SLUT Hjälpfunktioner **********

// ********** START Huvudfunktion **********

function processPersonnummer() {
  console.log("🔍 Bearbetning startad");

  let maleCount = 0;
  let femaleCount = 0;
  let totalAge = 0;

  const input = document.getElementById('personnummerList').value;
  console.log("📥 Input:\n" + input);

  const personnummerArray = input.split(/\r?\n/).filter(Boolean);
  console.log("📄 Antal rader:", personnummerArray.length);

  const resultsTable = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
  resultsTable.innerHTML = '';

  personnummerArray.forEach((raw, index) => {
    console.log(`🔢 Rad ${index + 1}: ${raw}`);
    const pnr = standardizePnrFormat(raw);
    const errors = [];

    if (!pnr || pnr.length !== 12) {
      console.warn(`⚠️ Ogiltigt format på rad ${index + 1}`);
      return;
    }

    const newRow = resultsTable.insertRow();

    const birthDate = new Date(pnr.substring(0, 4), pnr.substring(4, 6) - 1, pnr.substring(6, 8));
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    if (today < new Date(today.getFullYear(), birthDate.getMonth(), birthDate.getDate())) age--;

    const cell1 = newRow.insertCell();
    if (age < 18 || age > 65) {
      const warning = document.createElement('span');
      warning.innerText = '⚠️';
      warning.title = age < 18 ? 'Minderårig' : 'Pensionär';
      warning.style.marginRight = '5px';
      cell1.appendChild(warning);
    }
    cell1.innerHTML += formatPnrForDisplay(pnr);

    const isValid = validateLuhn(pnr.substring(2));
    const cell2 = newRow.insertCell();
    cell2.innerText = isValid ? 'OK' : 'EJ OK';

    const cell3 = newRow.insertCell();
    if (!isValid) {
      const month = parseInt(pnr.substring(4, 6));
      const day = parseInt(pnr.substring(6, 8));
      const maxDay = [0, 31, isLeapYear(pnr.substring(0, 4)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
      if (!/^\d{12}$/.test(pnr)) errors.push("Fel format");
      if (month < 1 || month > 12) errors.push("Ogiltig månad");
      if (day < 1 || day > maxDay) errors.push("Ogiltig dag");
      if (birthDate > today) errors.push("Framtida datum");
      errors.push("Ogiltigt kontrollnummer");
      cell3.innerHTML = errors.join("<br>") + " ❌";
      cell3.style.color = "red";
    } else {
      cell3.innerHTML = "✅";
      cell3.style.color = "green";
      cell3.style.textAlign = "center";
    }

    const cell4 = newRow.insertCell();
    if (errors.length === 0) {
      const genderDigit = parseInt(pnr.charAt(pnr.length - 2));
      if (genderDigit % 2 === 0) {
        cell4.innerText = "KVINNA";
        femaleCount++;
      } else {
        cell4.innerText = "MAN";
        maleCount++;
      }
    }

    const cell5 = newRow.insertCell();
    if (errors.length === 0) {
      cell5.innerText = age;
      totalAge += age;
    }

    const cell6 = newRow.insertCell();
    if (errors.length === 0) {
      cell6.innerText = getZodiacSign(pnr.substring(4, 6), pnr.substring(6, 8));
    }

    const cell7 = newRow.insertCell();
    if (errors.length === 0) {
      cell7.innerText = getDaysUntilBirthday(pnr.substring(4, 6), pnr.substring(6, 8));
    }
  });

  const total = maleCount + femaleCount;
  const avg = personnummerArray.length > 0 ? Math.round(totalAge / personnummerArray.length) : 0;

  document.getElementById('maleCount').innerText = maleCount;
  document.getElementById('femaleCount').innerText = femaleCount;
  document.getElementById('malePercentage').innerText = total ? Math.round(maleCount / total * 100) + '%' : '0%';
  document.getElementById('femalePercentage').innerText = total ? Math.round(femaleCount / total * 100) + '%' : '0%';
  document.getElementById('averageAge').innerText = avg;

document.getElementById('resultsTable').classList.remove('hidden');
document.getElementById('summary').classList.remove('hidden');
document.getElementById('resultsHeader').classList.remove('hidden');
document.getElementById('exportButton').classList.remove('hidden');

  console.log("✅ Bearbetning klar");
}

// ********** SLUT Huvudfunktion **********

window.processPersonnummer = processPersonnummer;
