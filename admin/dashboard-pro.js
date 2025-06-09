// dashboard-pro.js - v1.0

// ******************* START dashboard-pro.js - v1.0 *******************

console.log("🧠 INITIERAR CYBER-DASHBOARD v1.0");

let updateTimer = null;

// Initialisering vid DOM-load
document.addEventListener("DOMContentLoaded", () => {
  console.log("🚀 DOM laddad – hämtar data...");
  loadData();
  updateTimer = setInterval(loadData, 60000); // ⏱️ Auto-uppdatera var 60 sek
});

// 🚚 Hämtar och distribuerar data
async function loadData() {
  try {
    const res = await fetch("visits-export.php");
    const data = await res.json();

    console.log(`📦 ${data.length} rader hämtade från visits-export.php`);

    const ipMap = await resolveCountries(data);
    renderAllModules(data, ipMap);

  } catch (err) {
    console.error("❌ Fel vid dataladdning:", err);
  }
}

// 🌍 IP → Landskod (via geo-country.php), med lokal cache
async function resolveCountries(data) {
  const uniqueIPs = [...new Set(data.map(r => r.IP))];
  const cache = {};
  const result = {};

  console.log(`🌐 Unika IP att slå upp: ${uniqueIPs.length}`);

  for (const ip of uniqueIPs) {
    try {
      if (!cache[ip]) {
const res = await fetch("./geo-country.php?ip=" + ip);

        const json = await res.json();
        cache[ip] = json.country || "okänd";
        console.log(`🌎 ${ip} → ${cache[ip]}`);
      }
      result[ip] = cache[ip];
    } catch (e) {
      console.warn(`⚠️ Geo lookup fail för ${ip}:`, e);
      result[ip] = "okänd";
    }
  }

  return result;
}

// 🧠 Kör alla analysmoduler
function renderAllModules(data, ipMap) {
  renderSummary(data);
  renderVisitsByHour(data);
  renderTopPages(data);
  renderDeviceSplit(data);
  renderTopClicks(data);
  renderCountries(data, ipMap);
  renderResolutions(data);
  renderReferers(data);
  renderLanguages(data);
  renderTimezones(data);
  renderUserAgents(data);
}

// 📊 Totalsummering
function renderSummary(data) {
  const total = data.length;
  const unique = new Set(data.map(r => r.IP)).size;
  const humans = data.filter(r => r.Typ.includes("Människa")).length;
  const bots = total - humans;

  document.getElementById("stat-total").textContent = total;
  document.getElementById("stat-unique").textContent = unique;
  document.getElementById("stat-humans").textContent = humans;
  document.getElementById("stat-bots").textContent = bots;

  console.log(`🧮 Statistik: ${total} rader, ${unique} IP, 👤 ${humans} / 🤖 ${bots}`);
}

// 📈 Besök per timme (senaste 24h)
function renderVisitsByHour(data) {
  const now = new Date();
  const labels = Array.from({ length: 24 }, (_, i) => {
    const d = new Date(now.getTime() - (23 - i) * 3600000);
    return `${d.getHours().toString().padStart(2, "0")}:00`;
  });

  const hourlyCounts = new Array(24).fill(0);
  data.forEach(row => {
    const time = new Date(row.Tid);
    const diff = Math.floor((now - time) / 3600000);
    if (diff >= 0 && diff < 24) hourlyCounts[23 - diff]++;
  });

  new Chart(document.getElementById("chart-hour"), {
    type: "line",
    data: {
      labels,
      datasets: [{
        label: "Besök/timme",
        data: hourlyCounts,
        borderColor: "#00ffcc",
        backgroundColor: "rgba(0,255,204,0.1)",
        tension: 0.3
      }]
    },
    options: {
      animation: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: "#0ff" } },
        y: { ticks: { color: "#0ff" }, beginAtZero: true }
      }
    }
  });

  console.log("⏱️ Besök/timme-graph renderad");
}

// 🔝 Mest besökta sidor
function renderTopPages(data) {
  const pages = {};
  data.forEach(r => pages[r.Sida] = (pages[r.Sida] || 0) + 1);
  const top = Object.entries(pages).sort((a, b) => b[1] - a[1]).slice(0, 5);

  const ctx = document.getElementById("chart-pages");
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: top.map(([s]) => s),
      datasets: [{ data: top.map(([, c]) => c), backgroundColor: "#39f" }]
    },
    options: {
      indexAxis: 'y',
      plugins: { legend: { display: false } },
      scales: { x: { ticks: { color: "#9cf" }, beginAtZero: true }, y: { ticks: { color: "#9cf" } } }
    }
  });

  console.log("📄 Topp 5 sidor renderade");
}

// 🧠 Enhetsfördelning
function renderDeviceSplit(data) {
  const counts = { desktop: 0, mobil: 0, bot: 0 };
  data.forEach(r => {
    const t = r.Typ.includes("Bot") ? "bot" : (r.Enhet || "").toLowerCase();
    if (t.includes("mobil")) counts.mobil++;
    else if (t.includes("desktop")) counts.desktop++;
    else counts.bot++;
  });

  new Chart(document.getElementById("chart-device"), {
    type: "doughnut",
    data: {
      labels: ["Desktop", "Mobil", "Bot"],
      datasets: [{ data: [counts.desktop, counts.mobil, counts.bot], backgroundColor: ["#58f", "#0ff", "#f44"] }]
    },
    options: { plugins: { legend: { labels: { color: "#ccc" } } } }
  });

  console.log("💻 Enhetsdiagram renderat");
}

// 🎯 Topp klick
function renderTopClicks(data) {
  const clicks = {};
  data.forEach(r => {
    const val = r.Klick;
    if (val) clicks[val] = (clicks[val] || 0) + 1;
  });
  const top = Object.entries(clicks).sort((a, b) => b[1] - a[1]).slice(0, 5);

  new Chart(document.getElementById("chart-clicks"), {
    type: "bar",
    data: {
      labels: top.map(([k]) => k),
      datasets: [{ data: top.map(([, c]) => c), backgroundColor: "#0cf" }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: "#8ef" } },
        y: { ticks: { color: "#8ef" }, beginAtZero: true }
      }
    }
  });

  console.log("🖱️ Klickanalys renderad");
}

// 🌍 Länder
function renderCountries(data, ipMap) {
  const counts = {};
  data.forEach(r => {
    const c = ipMap[r.IP] || "okänd";
    counts[c] = (counts[c] || 0) + 1;
  });

  const top = Object.entries(counts).sort((a, b) => b[1] - a[1]).slice(0, 8);

  new Chart(document.getElementById("chart-country"), {
    type: "bar",
    data: {
      labels: top.map(([k]) => k),
      datasets: [{ data: top.map(([, v]) => v), backgroundColor: "#2df" }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: "#7ff" } },
        y: { ticks: { color: "#7ff" }, beginAtZero: true }
      }
    }
  });

  console.log("🌎 Länderdiagram renderat");
}

// 🖥️ Skärmstorlekar
function renderResolutions(data) {
  const map = {};
  data.forEach(r => {
    if (!r.Skärm) return;
    map[r.Skärm] = (map[r.Skärm] || 0) + 1;
  });
  const sorted = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 6);

  new Chart(document.getElementById("chart-res"), {
    type: "bar",
    data: {
      labels: sorted.map(([s]) => s),
      datasets: [{ data: sorted.map(([, c]) => c), backgroundColor: "#6f6" }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { x: { ticks: { color: "#afa" } }, y: { ticks: { color: "#afa" }, beginAtZero: true } }
    }
  });

  console.log("📐 Skärmstorlekar renderade");
}

// 🌐 Referer
function renderReferers(data) {
  const ref = {};
  data.forEach(r => {
    const key = r.Referer || "(direkt)";
    ref[key] = (ref[key] || 0) + 1;
  });
  const top = Object.entries(ref).sort((a, b) => b[1] - a[1]).slice(0, 5);

  new Chart(document.getElementById("chart-referer"), {
    type: "bar",
    data: {
      labels: top.map(([r]) => r),
      datasets: [{ data: top.map(([, c]) => c), backgroundColor: "#f93" }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { x: { ticks: { color: "#fdd" } }, y: { ticks: { color: "#fdd" }, beginAtZero: true } }
    }
  });

  console.log("🔗 Referer-diagram klart");
}

// 🌐 Språk
function renderLanguages(data) {
  const lang = {};
  data.forEach(r => {
    const s = r.Språk || "okänd";
    lang[s] = (lang[s] || 0) + 1;
  });

  const top = Object.entries(lang).sort((a, b) => b[1] - a[1]).slice(0, 5);

  new Chart(document.getElementById("chart-lang"), {
    type: "doughnut",
    data: {
      labels: top.map(([l]) => l),
      datasets: [{ data: top.map(([, c]) => c), backgroundColor: ["#0ff", "#9f9", "#ff9", "#f99", "#99f"] }]
    },
    options: { plugins: { legend: { labels: { color: "#ccc" } } } }
  });

  console.log("🈷️ Språkfördelning klar");
}

// 🌐 Tidszoner
function renderTimezones(data) {
  const z = {};
  data.forEach(r => {
    const t = r.Tidszon || "okänd";
    z[t] = (z[t] || 0) + 1;
  });

  const top = Object.entries(z).sort((a, b) => b[1] - a[1]).slice(0, 6);

  new Chart(document.getElementById("chart-tz"), {
    type: "bar",
    data: {
      labels: top.map(([t]) => t),
      datasets: [{ data: top.map(([, c]) => c), backgroundColor: "#ccc" }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { x: { ticks: { color: "#ddd" } }, y: { ticks: { color: "#ddd" }, beginAtZero: true } }
    }
  });

  console.log("🕓 Tidszoner visualiserade");
}

// 🧪 User Agents
function renderUserAgents(data) {
  const ua = {};
  data.forEach(r => {
    const part = r["User Agent"]?.split(")")[0]?.split("(")[1] || "okänd";
    ua[part] = (ua[part] || 0) + 1;
  });

  const top = Object.entries(ua).sort((a, b) => b[1] - a[1]).slice(0, 6);

  new Chart(document.getElementById("chart-agent"), {
    type: "bar",
    data: {
      labels: top.map(([u]) => u),
      datasets: [{ data: top.map(([, c]) => c), backgroundColor: "#f0f" }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { x: { ticks: { color: "#fcf" } }, y: { ticks: { color: "#fcf" }, beginAtZero: true } }
    }
  });

  console.log("🛸 User Agent-fördelning färdig");
}


// ******************* SLUT dashboard-pro.js - v1.0 *******************
