// dashboard.js - v2.1 (ECharts, utan geo)

console.log("🧠 Cyber Dashboard v2.1 (utan geo) startad");

document.addEventListener("DOMContentLoaded", async () => {
  const res = await fetch("visits-export.php");
  const data = await res.json();

  console.log(`📦 Laddade ${data.length} rader`);

  renderSummary(data);
  renderHourlyVisits(data);
  renderTopPages(data);
  renderDeviceSplit(data);
  renderTopClicks(data);
  renderScreenRes(data);
  renderReferers(data);
  renderLanguages(data);
  renderTimezones(data);
  renderUserAgents(data);
  render3DBar(data); // 🧊 bonus
});

// 📊 Totalsummering
function renderSummary(data) {
  const total = data.length;
  const unique = new Set(data.map(r => r.IP)).size;
  const humans = data.filter(r => r.Typ?.includes("Människa")).length;
  const bots = total - humans;

  document.getElementById("stat-total").textContent = total;
  document.getElementById("stat-unique").textContent = unique;
  document.getElementById("stat-humans").textContent = humans;
  document.getElementById("stat-bots").textContent = bots;
}

// 📈 Besök per timme
function renderHourlyVisits(data) {
  const now = new Date();
  const labels = Array.from({ length: 24 }, (_, i) => {
    const d = new Date(now.getTime() - (23 - i) * 3600000);
    return `${d.getHours().toString().padStart(2, "0")}:00`;
  });
  const counts = new Array(24).fill(0);
  data.forEach(row => {
    const t = new Date(row.Tid);
    const diff = Math.floor((now - t) / 3600000);
    if (diff >= 0 && diff < 24) counts[23 - diff]++;
  });

  const chart = echarts.init(document.getElementById("chart-hour"));
  chart.setOption({
    xAxis: { type: "category", data: labels, axisLabel: { color: "#aaa" } },
    yAxis: { type: "value", axisLabel: { color: "#aaa" } },
    series: [{ data: counts, type: "line", smooth: true, areaStyle: {} }],
    grid: { left: 40, right: 20, bottom: 40, top: 20 }
  });
}

// 🧾 Topp sidor
function renderTopPages(data) {
  const map = {};
  data.forEach(r => map[r.Sida] = (map[r.Sida] || 0) + 1);
  const top = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 5);

  const chart = echarts.init(document.getElementById("chart-pages"));
  chart.setOption({
    xAxis: { type: "value", axisLabel: { color: "#aaa" } },
    yAxis: { type: "category", data: top.map(([k]) => k), axisLabel: { color: "#aaa" } },
    series: [{ data: top.map(([, v]) => v), type: "bar" }],
    grid: { left: 100, right: 20, top: 10, bottom: 20 }
  });
}

// 💻 Enheter
function renderDeviceSplit(data) {
  const counts = { Desktop: 0, Mobil: 0, Bot: 0 };
  data.forEach(r => {
    const t = r.Typ?.includes("Bot") ? "Bot" : (r.Enhet || "").toLowerCase();
    if (t.includes("mobil")) counts.Mobil++;
    else if (t.includes("desktop")) counts.Desktop++;
    else counts.Bot++;
  });

  const chart = echarts.init(document.getElementById("chart-device"));
  chart.setOption({
    series: [{
      type: "pie",
      radius: "60%",
      data: Object.entries(counts).map(([name, value]) => ({ name, value })),
      label: { color: "#ccc" }
    }]
  });
}

// 🖱️ Topp klick
function renderTopClicks(data) {
  const clicks = {};
  data.forEach(r => {
    if (r.Klick) clicks[r.Klick] = (clicks[r.Klick] || 0) + 1;
  });
  const top = Object.entries(clicks).sort((a, b) => b[1] - a[1]).slice(0, 5);

  const chart = echarts.init(document.getElementById("chart-clicks"));
  chart.setOption({
    xAxis: { type: "category", data: top.map(([k]) => k), axisLabel: { color: "#ccc" } },
    yAxis: { type: "value", axisLabel: { color: "#ccc" } },
    series: [{ type: "bar", data: top.map(([, v]) => v) }],
    grid: { left: 40, right: 20, top: 20, bottom: 40 }
  });
}

// 📐 Skärmstorlekar
function renderScreenRes(data) {
  const map = {};
  data.forEach(r => {
    const s = r.Skärm;
    if (s) map[s] = (map[s] || 0) + 1;
  });
  const top = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 6);

  const chart = echarts.init(document.getElementById("chart-res"));
  chart.setOption({
    xAxis: { type: "category", data: top.map(([r]) => r), axisLabel: { color: "#ccc" } },
    yAxis: { type: "value", axisLabel: { color: "#ccc" } },
    series: [{ type: "bar", data: top.map(([, v]) => v) }]
  });
}

// 🔗 Referers
function renderReferers(data) {
  const map = {};
  data.forEach(r => {
    const ref = r.Referer || "(direkt)";
    map[ref] = (map[ref] || 0) + 1;
  });
  const top = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 5);

  const chart = echarts.init(document.getElementById("chart-referer"));
  chart.setOption({
    xAxis: { type: "category", data: top.map(([r]) => r), axisLabel: { color: "#ccc", rotate: 30 } },
    yAxis: { type: "value", axisLabel: { color: "#ccc" } },
    series: [{ type: "bar", data: top.map(([, v]) => v) }]
  });
}

// 🈷️ Språk
function renderLanguages(data) {
  const map = {};
  data.forEach(r => {
    const lang = r.Språk || "okänd";
    map[lang] = (map[lang] || 0) + 1;
  });

  const chart = echarts.init(document.getElementById("chart-lang"));
  chart.setOption({
    series: [{
      type: "pie",
      radius: "65%",
      data: Object.entries(map).map(([name, value]) => ({ name, value })),
      label: { color: "#ccc" }
    }]
  });
}

// 🕓 Tidszoner
function renderTimezones(data) {
  const map = {};
  data.forEach(r => {
    const z = r.Tidszon || "okänd";
    map[z] = (map[z] || 0) + 1;
  });
  const top = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 5);

  const chart = echarts.init(document.getElementById("chart-tz"));
  chart.setOption({
    xAxis: { type: "category", data: top.map(([t]) => t), axisLabel: { color: "#ccc" } },
    yAxis: { type: "value", axisLabel: { color: "#ccc" } },
    series: [{ type: "bar", data: top.map(([, v]) => v) }]
  });
}

// 🛸 User Agents
function renderUserAgents(data) {
  const map = {};
  data.forEach(r => {
    const part = r["User Agent"]?.split(")")[0]?.split("(")[1] || "okänd";
    map[part] = (map[part] || 0) + 1;
  });
  const top = Object.entries(map).sort((a, b) => b[1] - a[1]).slice(0, 6);

  const chart = echarts.init(document.getElementById("chart-agent"));
  chart.setOption({
    yAxis: { type: "category", data: top.map(([ua]) => ua), axisLabel: { color: "#aaa" } },
    xAxis: { type: "value", axisLabel: { color: "#aaa" } },
    series: [{ type: "bar", data: top.map(([, v]) => v) }],
    grid: { left: 100 }
  });
}

// 🧊 3D-analys – klickade element per enhetstyp
function render3DBar(data) {
  const devices = ["desktop", "mobil", "bot"];
  const elements = [...new Set(data.map(r => r.Klick || "-"))].slice(0, 5);

  const counts = [];
  devices.forEach((device, x) => {
    elements.forEach((el, y) => {
      const count = data.filter(r => {
        const t = r.Typ.includes("Bot") ? "bot" : r.Enhet?.toLowerCase();
        return (t?.includes(device) && r.Klick === el);
      }).length;
      counts.push([x, y, count]);
    });
  });

  const chart = echarts.init(document.getElementById("chart-3d"));
  chart.setOption({
    tooltip: {},
    visualMap: {
      max: Math.max(...counts.map(c => c[2])),
      inRange: { color: ['#0ff', '#0f0', '#f0f'] }
    },
    xAxis3D: { type: 'category', data: devices },
    yAxis3D: { type: 'category', data: elements },
    zAxis3D: { type: 'value' },
    grid3D: {
      boxWidth: 100,
      boxDepth: 80,
      viewControl: { projection: 'orthographic' }
    },
    series: [{
      type: 'bar3D',
      data: counts.map(item => ({ value: item })),
      shading: 'color',
      label: { show: false }
    }]
  });
}
