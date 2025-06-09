/**
 * map.js - Hanterar Leaflet-kartan och kartlager
 * Version 1.7 - Fixar "Map container not found" genom att observera DOM
 */

console.log("[DEBUG] Laddar map.js - Version 1.7");

function initMap() {
    console.log("[DEBUG] Försöker initiera kartan...");

    let mapElement = document.getElementById("map");

    // Kontrollera att kartans container existerar
    if (!mapElement) {
        console.error("[ERROR] Map container not found! Väntar på att DOM uppdateras...");
        waitForMapContainer(); // Starta observer för att vänta på att #map läggs till i DOM
        return;
    }

    // Kontrollera om en karta redan finns och ta bort den
    if (window.map) {
        console.warn("[WARNING] Karta finns redan, tar bort tidigare instans...");
        window.map.remove();
        window.map = null;
    }

    console.log("[DEBUG] Skapar ny Leaflet-karta...");
    window.map = L.map('map').setView([59.3293, 18.0686], 10);

    // Definiera kartlager
    let standardMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(window.map);

    let historicMap = L.tileLayer('https://tile.thunderforest.com/pioneer/{z}/{x}/{y}.png?apikey=YOUR_API_KEY', {
        attribution: '© Thunderforest Pioneer'
    });

    let nasaSatellite = L.tileLayer('https://map1.vis.earthdata.nasa.gov/wmts-webmerc/VIIRS_CityLights_2012/default/2012-01-01/{z}/{y}/{x}.jpg', {
        attribution: '© NASA Earth Observatory'
    });

    let hackerStyle = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
        attribution: '© Stadia Maps'
    });

    // Lägg till lagerkontroll i kartans underkant
    let layerControl = L.control({ position: 'bottomleft' });

    layerControl.onAdd = function(map) {
        let div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
        div.innerHTML = `
            <button onclick="setMapLayer('standard')">🌍 Standard</button>
            <button onclick="setMapLayer('historic')">🏰 Historisk</button>
            <button onclick="setMapLayer('nasa')">🛰️ NASA</button>
            <button onclick="setMapLayer('hacker')">🖥️ Hacker</button>
        `;
        return div;
    };

    layerControl.addTo(window.map);

    // Hantera kartbyte
    window.setMapLayer = function(layer) {
        window.map.eachLayer(layer => window.map.removeLayer(layer)); // Ta bort nuvarande lager

        if (layer === "standard") {
            standardMap.addTo(window.map);
            console.log("[DEBUG] Växlat till Standard-karta");
        } else if (layer === "historic") {
            historicMap.addTo(window.map);
            console.log("[DEBUG] Växlat till Historisk karta");
        } else if (layer === "nasa") {
            nasaSatellite.addTo(window.map);
            console.log("[DEBUG] Växlat till NASA Satellit");
        } else if (layer === "hacker") {
            hackerStyle.addTo(window.map);
            console.log("[DEBUG] Växlat till Hacker Mode");
        }
    };

    // Markörhantering
    let marker = null;
    window.map.on('click', function (e) {
        let lat = e.latlng.lat.toFixed(6);
        let lng = e.latlng.lng.toFixed(6);
        console.log(`[DEBUG] Kartklick: Nord=${lat}, Öst=${lng}`);
        
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(window.map);
        }

        document.getElementById("coordinates").value = `${lat}, ${lng}`;
    });
}

// **Ny funktion: Väntar på att #map läggs till i DOM**
function waitForMapContainer() {
    console.log("[DEBUG] Startar observer för att vänta på <div id='map'>");

    let observer = new MutationObserver((mutations, observer) => {
        if (document.getElementById("map")) {
            console.log("[DEBUG] <div id='map'> hittad! Initierar karta...");
            observer.disconnect(); // Stoppa observer när vi hittat #map
            initMap();
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
}

// Vänta tills DOM är laddad innan vi startar kartan
document.addEventListener("DOMContentLoaded", function() {
    console.log("[DEBUG] DOM är nu redo. Startar kartinitiering...");
    initMap();
});
