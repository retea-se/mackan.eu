// impex_map.js - v6

console.log("[DEBUG] Laddar impex_map.js - v6");

document.addEventListener("DOMContentLoaded", function () {
  const map = L.map("map").setView([59.3293, 18.0686], 10);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  console.log("[DEBUG] Leaflet-karta initierad");

  // Klick i karta
  map.on("click", async function (e) {
    const lat = e.latlng.lat.toFixed(6);
    const lon = e.latlng.lng.toFixed(6);
    const sweref99 = getSweref99Zone(parseFloat(lon));

    console.log(`[DEBUG] Klick i karta: ${lat}, ${lon}`);

    const elevation = await fetchElevation(lat, lon);
    const address = await fetchAddress(lat, lon);

    appendResultRow({
      latitude: lat,
      longitude: lon,
      elevation,
      sweref99,
      ...address,
    });
  });

  // Rensa-tabell-knapp
  const clearButton = document.getElementById("clear-table");
  if (clearButton) {
    clearButton.addEventListener("click", () => {
      const body = document.getElementById("result-body");
      body.innerHTML = "";
      console.log("[DEBUG] Resultattabell rensad");
    });
  }
});

// ********** Höjddata (alternativt API) **********
async function fetchElevation(lat, lon) {
  try {
    const response = await fetch(`https://api.opentopodata.org/v1/eudem25m?locations=${lat},${lon}`);
    const json = await response.json();
    const elevation = json.results?.[0]?.elevation ?? "N/A";
    console.log("[DEBUG] Höjddata (OpenTopo):", elevation);
    return `${elevation} m`;
  } catch (err) {
    console.error("[ERROR] Höjd-API (fallback):", err);
    return "N/A";
  }
}

// ********** Adressdata **********
async function fetchAddress(lat, lon) {
  try {
    const res = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1`
    );
    const data = await res.json();
    const a = data.address || {};
    return {
      house: a.house_number ? ` ${a.house_number}` : "",
      street: a.road || "Okänd gata",
      city: a.city || a.town || a.village || "Okänd stad",
      postcode: a.postcode || "Okänt postnummer",
      country: a.country || "Okänt land",
    };
  } catch (err) {
    console.error("[ERROR] Adress-API:", err);
    return {
      house: "",
      street: "Okänd gata",
      city: "Okänd stad",
      postcode: "Okänt postnummer",
      country: "Okänt land",
    };
  }
}

// ********** Skriv till tabell **********
function appendResultRow(data) {
  const row = document.createElement("tr");
  row.innerHTML = `
    <td>${data.latitude}</td>
    <td>${data.longitude}</td>
    <td>${data.elevation}</td>
    <td>${data.sweref99}</td>
    <td>${data.street}${data.house}</td>
    <td>${data.city}</td>
    <td>${data.postcode}</td>
    <td>${data.country}</td>
  `;
  document.getElementById("result-body").appendChild(row);
  document.getElementById("result").classList.remove("hidden");
  console.log("[DEBUG] Rad tillagd i resultat-tabellen");
}

// ********** Hjälpfunktion **********
function getSweref99Zone(lon) {
  return lon >= 10.5 && lon < 18.5 ? "SWEREF99 TM" : "Okänd zon";
}
