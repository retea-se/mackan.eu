// passphrase.js - v4
// git commit: Justerade generatePassphrase så den respekterar maxlängd i tecken

console.log("🔤 passphrase.js v4 laddad");

let ordlista = [];

fetch("fras.txt")
  .then((res) => res.ok ? res.text() : Promise.reject("Ordlista ej hittad"))
  .then((text) => {
    ordlista = text
      .split("\n")
      .map((rad) => rad.trim())
      .filter((rad) => rad.length > 0);
    console.log("📚 Ordlista laddad med", ordlista.length, "ord");
  })
  .catch((err) => console.warn("⚠️ Fel vid laddning av ordlista:", err));

function slumpaOrd() {
  if (ordlista.length === 0) return "[ordlista saknas]";
  return ordlista[Math.floor(Math.random() * ordlista.length)];
}

function versalStart(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function generatePassphrase(maxTecken = 20) {
  if (ordlista.length === 0) return "[ordlista saknas]";
  let försök = 0;
  let resultat = [];
  let total = 0;

  // Försök hitta minst två ord, annars nöj dig med ett
  while (försök < 10) {
    resultat = [];
    total = 0;
    while (true) {
      const nyttOrd = versalStart(slumpaOrd());
      const tillagt = resultat.length === 0 ? nyttOrd.length : nyttOrd.length + 1;
      if ((total + tillagt) > maxTecken) break;
      resultat.push(nyttOrd);
      total += tillagt;
    }
    if (resultat.length >= 2) break;
    försök++;
  }
  // Om det inte gick att få två ord, ta ett ord om det får plats
  if (resultat.length === 1) return resultat[0];
  // Om inget ord får plats, visa fel
  if (resultat.length === 0) return "[för kort längd]";
  return resultat.join("-");
}

window.generatePassphrase = generatePassphrase;
