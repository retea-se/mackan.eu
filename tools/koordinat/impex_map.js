
// impex_map.js - v13 (Modal-baserade meddelanden)

// Import modal utilities
import { showAlert } from '/js/modal-utils.js';

document.addEventListener("DOMContentLoaded", function () {
  // PERMANENT LÖSNING: Använd en custom Leaflet-ikon med unikt klassnamn
  const myIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41],
    className: 'my-leaflet-marker' // unikt namn, ingen global CSS kan påverka
  });


  // Debug: check if marker icon image loads
  const testImg = new window.Image();
  testImg.onload = function() { console.log('[DEBUG] Leaflet marker icon loaded successfully.'); };
  testImg.onerror = function() { console.error('[ERROR] Leaflet marker icon failed to load!'); };
  testImg.src = 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png';

  // Bulletproof CSS override for Leaflet marker icons and containers
  const style = document.createElement('style');
  style.innerHTML = `
    .leaflet-marker-icon, .leaflet-marker-shadow, .leaflet-pane img {
      border: 2px solid red !important;
      background: none !important;
      filter: none !important;
      opacity: 1 !important;
      content: normal !important;
      display: inline !important;
      mask: none !important;
      -webkit-mask: none !important;
      box-shadow: none !important;
      outline: none !important;
      image-rendering: auto !important;
      object-fit: contain !important;
      width: auto !important;
      height: auto !important;
      max-width: none !important;
      max-height: none !important;
    }
    .leaflet-marker-icon[src], .leaflet-pane img[src] {
      content: normal !important;
      background: none !important;
      filter: none !important;
      mask: none !important;
      -webkit-mask: none !important;
      display: inline !important;
    }
    .leaflet-marker-icon:before, .leaflet-marker-icon:after, .leaflet-pane img:before, .leaflet-pane img:after {
      content: none !important;
    }
    .leaflet-marker-icon {
      background-image: none !important;
    }
    .leaflet-pane, .leaflet-marker-pane {
      background: none !important;
      filter: none !important;
    }
  `;
  document.head.appendChild(style);

  console.log("[DEBUG] Laddar impex_map.js - v12");
  console.log("[DEBUG] TESTMARKÖR v12 laddad " + new Date().toISOString());

  let polylinePoints = [];
  let polyline;
  let markerGroup;

  const map = L.map("map").setView([59.3293, 18.0686], 10);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  markerGroup = L.layerGroup().addTo(map);

  console.log("[DEBUG] Leaflet-karta initierad");

  map.on("click", async function (e) {
    const lat = e.latlng.lat.toFixed(6);
    const lon = e.latlng.lng.toFixed(6);
    const sweref99 = getSweref99Zone(parseFloat(lon));

    console.log(`[DEBUG] Klick i karta: ${lat}, ${lon}`);

    const elevation = await fetchElevation(lat, lon);
    const address = await fetchAddress(lat, lon);

    const fullData = {
      latitude: lat,
      longitude: lon,
      elevation,
      sweref99,
      ...address,
    };

    appendResultRow(fullData);
    addMarkerToMap(lat, lon, fullData);
    addPointToPolyline(lat, lon, fullData); // ✅ skickar med data
  });

  const clearButton = document.getElementById("clear-table");
  if (clearButton) {
    clearButton.addEventListener("click", () => {
      const resultBody = document.getElementById("result-body");
      const resultSection = document.getElementById("result-section");
      const totalDistance = document.getElementById("total-distance");
      const terminalLines = document.getElementById("terminal-lines");
      const hackerOutput = document.getElementById("hacker-output-section");

      if (resultBody) resultBody.innerHTML = "";
      if (resultSection) resultSection.classList.add("hidden");
      if (totalDistance) totalDistance.textContent = "Total sträcka: 0 m";

      if (polyline) {
        polyline.remove();
        polyline = null;
      }

      polylinePoints = [];
      markerGroup.clearLayers();

      if (terminalLines) terminalLines.textContent = "";
      if (hackerOutput) hackerOutput.classList.add("hidden");

      console.log("[DEBUG] Rensade tabell, linje, markörer och terminal");
      showToast("Tabell och karta har rensats.");
    });
  }

  // Make markerGroup available globally for addMarkerToMap
  window._impex_markerGroup = markerGroup;

  // Event listeners för knappar
  const convertTextareaBtn = document.getElementById("convert-textarea");
  const loadMarkersBtn = document.getElementById("load-markers");

  if (convertTextareaBtn) {
    convertTextareaBtn.addEventListener("click", function() {
      const textarea = document.getElementById("coordinates-textarea");
      if (textarea && textarea.value.trim()) {
        // Använd impex.js funktionalitet om den finns
        if (typeof handleTextInput === 'function') {
          handleTextInput();
        } else {
          console.warn("[WARNING] handleTextInput function not found");
        }
      } else {
        showAlert("Klistra in koordinater i textfältet", "Information");
      }
    });
  }

  if (loadMarkersBtn) {
    loadMarkersBtn.addEventListener("click", function() {
      // Ladda markörer från resultattabellen
      const rows = document.querySelectorAll("#result-body tr");
      if (rows.length === 0) {
        showAlert("Inga koordinater i tabellen att ladda. Konvertera först koordinater.", "Information");
        return;
      }

      markerGroup.clearLayers();
      polylinePoints = [];
      if (polyline) {
        polyline.remove();
        polyline = null;
      }

      rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 2) {
          const lat = parseFloat(cols[0].textContent.trim());
          const lon = parseFloat(cols[1].textContent.trim());
          if (!isNaN(lat) && !isNaN(lon)) {
            const marker = L.marker([lat, lon], { icon: myIcon }).addTo(markerGroup);
            polylinePoints.push([lat, lon]);
          }
        }
      });

      if (polylinePoints.length > 1) {
        polyline = L.polyline(polylinePoints, { color: "blue" }).addTo(markerGroup);
        map.fitBounds(polyline.getBounds());
      } else if (polylinePoints.length === 1) {
        map.setView(polylinePoints[0], 13);
      }

      console.log(`[DEBUG] Laddade ${polylinePoints.length} markörer på kartan`);
    });
  }

  // Move all map-related functions inside so they have access to variables
  function addMarkerToMap(lat, lon, data) {
    const group = markerGroup;
    const marker = L.marker([lat, lon], { icon: myIcon })
      .bindPopup(`
        <b>Lat:</b> ${lat}<br>
        <b>Lon:</b> ${lon}<br>
        <b>Höjd:</b> ${data.elevation}<br>
        <b>Gata:</b> ${data.street}${data.house}<br>
        <b>Stad:</b> ${data.city}
      `)
      .addTo(group)
      .openPopup();
    console.log("[DEBUG] Marker tillagd på kartan (custom icon)");
  }

  function addPointToPolyline(lat, lon, data) {
    polylinePoints.push([lat, lon]);

    if (polyline) {
      polyline.setLatLngs(polylinePoints);
    } else {
      polyline = L.polyline(polylinePoints, { color: "blue" }).addTo(markerGroup);
    }

    console.log("[DEBUG] Polyline uppdaterad med punkt:", lat, lon);

    let segment = "";
    const i = polylinePoints.length - 1;
    if (i > 0) {
      const dist = getDistance(...polylinePoints[i - 1], ...polylinePoints[i]);
      segment = dist >= 10000
        ? `${(dist / 10000).toFixed(2)} mil`
        : dist >= 1000
        ? `${(dist / 1000).toFixed(2)} km`
        : `${dist.toFixed(2)} m`;

      const rows = document.querySelectorAll("#result-body tr");
      if (rows.length) {
        const lastRow = rows[rows.length - 1];
        lastRow.lastElementChild.textContent = segment;
      }
    }

    const total = calculateDistanceKm(polylinePoints);
    const totalDistanceEl = document.getElementById("total-distance");
    if (totalDistanceEl) {
      totalDistanceEl.textContent = `Total sträcka: ${total}`;
    }
    showToast(`Total sträcka: ${total}`);

    updateHackerOutput(data, segment);
  }

  function calculateDistanceKm(coords) {
    if (coords.length < 2) return "0 m";

    let total = 0;
    for (let i = 1; i < coords.length; i++) {
      const [lat1, lon1] = coords[i - 1];
      const [lat2, lon2] = coords[i];
      total += getDistance(lat1, lon1, lat2, lon2);
    }

    if (total >= 10000) return `${(total / 10000).toFixed(2)} mil`;
    if (total >= 1000) return `${(total / 1000).toFixed(2)} km`;
    return `${total.toFixed(2)} m`;
  }

  function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const toRad = deg => deg * (Math.PI / 180);
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const a =
      Math.sin(dLat / 2) ** 2 +
      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
      Math.sin(dLon / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  }

  function updateHackerOutput(data, segment = "") {
    const out = document.getElementById("terminal-lines");
    const div = document.getElementById("hacker-output-section");
    if (!out || !div) return;

    div.classList.remove("hidden");

    const line = `▶ ${data.latitude}, ${data.longitude} | ${data.elevation} | ${data.street}${data.house} | ${data.postcode} ${data.city} | ${segment}`;
    out.textContent += line + "\n";
  }

});

// ********** Höjddata via proxy **********
async function fetchElevation(lat, lon) {
  const proxyUrl = `/verktyg/koordinat/api/elevation.php?lat=${lat}&lon=${lon}`;
  console.log("[DEBUG] Anropar proxy:", proxyUrl);
  try {
    const res = await fetch(proxyUrl);
    const json = await res.json();
    const elevation = json.results?.[0]?.elevation;
    if (typeof elevation === "number") {
      console.log("[DEBUG] Höjddata via proxy:", elevation);
      return `${elevation.toFixed(2)} m`;
    } else {
      throw new Error("Ogiltig höjddata");
    }
  } catch (err) {
    console.warn("[WARN] Fallback aktiverad, höjd sätts till 0 m:", err);
    return "0 m";
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

    const city = a.city || a.town || a.village || a.suburb || a.municipality || a.county || "Okänd stad";

    console.log("[DEBUG] Adresskomponenter:", a);
    return {
      house: a.house_number ? ` ${a.house_number}` : "",
      street: a.road || "Okänd gata",
      city: city,
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


// ********** Tabellrad **********
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
    <td></td>
  `;
  const resultBody = document.getElementById("result-body");
  const resultSection = document.getElementById("result-section");

  if (resultBody) {
    resultBody.appendChild(row);
  }
  if (resultSection) {
    resultSection.classList.remove("hidden");
  }
  console.log("[DEBUG] Rad tillagd i resultat-tabellen");
}

// ********** SWEREF99-zon **********
function getSweref99Zone(lon) {
  return lon >= 10.5 && lon < 18.5 ? "SWEREF99 TM" : "Okänd zon";
}

// ********** Polyline och distanser **********
function addPointToPolyline(lat, lon, data) {
  polylinePoints.push([lat, lon]);

  if (polyline) {
    polyline.setLatLngs(polylinePoints);
  } else {
    polyline = L.polyline(polylinePoints, { color: "blue" }).addTo(markerGroup);
  }

  console.log("[DEBUG] Polyline uppdaterad med punkt:", lat, lon);

  let segment = "";
  const i = polylinePoints.length - 1;
  if (i > 0) {
    const dist = getDistance(...polylinePoints[i - 1], ...polylinePoints[i]);
    segment = dist >= 10000
      ? `${(dist / 10000).toFixed(2)} mil`
      : dist >= 1000
      ? `${(dist / 1000).toFixed(2)} km`
      : `${dist.toFixed(2)} m`;

    const rows = document.querySelectorAll("#result-body tr");
    if (rows.length) {
      const lastRow = rows[rows.length - 1];
      lastRow.lastElementChild.textContent = segment;
    }
  }

  const total = calculateDistanceKm(polylinePoints);
  document.getElementById("total-distance").textContent = `Total sträcka: ${total}`;
  showToast(`Total sträcka: ${total}`);

  updateHackerOutput(data, segment);
}

// ********** Sträcka **********
function calculateDistanceKm(coords) {
  if (coords.length < 2) return "0 m";

  let total = 0;
  for (let i = 1; i < coords.length; i++) {
    const [lat1, lon1] = coords[i - 1];
    const [lat2, lon2] = coords[i];
    total += getDistance(lat1, lon1, lat2, lon2);
  }

  if (total >= 10000) return `${(total / 10000).toFixed(2)} mil`;
  if (total >= 1000) return `${(total / 1000).toFixed(2)} km`;
  return `${total.toFixed(2)} m`;
}

function getDistance(lat1, lon1, lat2, lon2) {
  const R = 6371000;
  const toRad = deg => deg * (Math.PI / 180);
  const dLat = toRad(lat2 - lat1);
  const dLon = toRad(lon2 - lon1);
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
    Math.sin(dLon / 2) ** 2;
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

// ********** Marker **********
function addMarkerToMap(lat, lon, data) {
  // Use markerGroup from global if not in scope
  const group = typeof markerGroup !== 'undefined' ? markerGroup : window._impex_markerGroup;
  const marker = L.marker([lat, lon])
    .bindPopup(`
      <b>Lat:</b> ${lat}<br>
      <b>Lon:</b> ${lon}<br>
      <b>Höjd:</b> ${data.elevation}<br>
      <b>Gata:</b> ${data.street}${data.house}<br>
      <b>Stad:</b> ${data.city}
    `)
    .addTo(group)
    .openPopup();

  console.log("[DEBUG] Marker tillagd på kartan");
}

// ********** Hacker-output (global version) **********
function updateHackerOutput(data, segment = "") {
  const out = document.getElementById("terminal-lines");
  const div = document.getElementById("hacker-output-section");
  if (!out || !div) return;

  div.classList.remove("hidden");

  const line = `▶ ${data.latitude}, ${data.longitude} | ${data.elevation} | ${data.street}${data.house} | ${data.postcode} ${data.city} | ${segment}`;
  out.textContent += line + "\n";
}

// ********** Copy output **********
document.addEventListener("DOMContentLoaded", function() {
  const copyOutputBtn = document.getElementById("copy-output");
  if (copyOutputBtn) {
    copyOutputBtn.addEventListener("click", () => {
      const text = document.getElementById("terminal-lines");
      if (text && text.textContent) {
        navigator.clipboard.writeText(text.textContent).then(() => {
          showToast("Koordinater kopierade!");
        }).catch(err => {
          console.error("[ERROR] Failed to copy:", err);
        });
      }
    });
  }
});

// ********** Toast **********
function showToast(message) {
  const toast = document.getElementById("toast");
  if (!toast) {
    console.warn("[WARNING] Toast element not found");
    return;
  }
  toast.textContent = message;
  toast.classList.add("toast--synlig");
  setTimeout(() => {
    toast.classList.remove("toast--synlig");
  }, 2500);
}
