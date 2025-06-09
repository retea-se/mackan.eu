console.log("[DEBUG] Laddar geocoding.js");

document.getElementById("fetch-addresses").addEventListener("click", fetchAddresses);

async function fetchAddresses() {
    console.log("[DEBUG] Hämtar adresser för koordinater i tabellen...");
    let rows = document.querySelectorAll("#result-body tr");
    let coordinates = [];

    rows.forEach(row => {
        let cols = row.querySelectorAll("td");
        if (cols.length >= 2) {
            let lat = parseFloat(cols[0].innerText.trim());
            let lon = parseFloat(cols[1].innerText.trim());
            if (!isNaN(lat) && !isNaN(lon)) {
                coordinates.push({ row, lat, lon });
            }
        }
    });

    if (coordinates.length === 0) {
        console.warn("[WARNING] Inga giltiga koordinater att omvandla.");
        return;
    }

    console.log(`[DEBUG] Skickar ${coordinates.length} koordinater till Nominatim API`);
    for (let coord of coordinates) {
        try {
            let response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${coord.lat}&lon=${coord.lon}&addressdetails=1`);
            let data = await response.json();
            
            let houseNumber = data.address.house_number ? ` ${data.address.house_number}` : "";
            let street = data.address.road || "Ej tillgänglig";
            let city = data.address.city || data.address.town || data.address.village || "Ej tillgänglig";
            let postcode = data.address.postcode || "Ej tillgänglig";
            let country = data.address.country || "Ej tillgänglig";
            
            coord.row.innerHTML += `<td>${street}${houseNumber}</td><td>${city}</td><td>${postcode}</td><td>${country}</td>`;
            console.log(`[DEBUG] Adress hittad: ${street}${houseNumber}, ${city}, ${postcode}, ${country}`);
        } catch (error) {
            console.error("[ERROR] Misslyckades att hämta adress:", error);
        }
    }
}
