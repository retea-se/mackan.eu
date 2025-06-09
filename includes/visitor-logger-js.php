<?php
// filepath: includes/visitor-logger-js.php
?>
<script>
try {
  // Hjälpfunktion för att avgöra enhetstyp
  function getDeviceType() {
    const ua = navigator.userAgent;
    if (/Mobi|Android/i.test(ua)) return "mobil";
    if (/Tablet|iPad/i.test(ua)) return "surfplatta";
    return "desktop";
  }

  // Hämta språk och tidszon
  const language = navigator.language || navigator.userLanguage || '';
  const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || '';

  // Logga sidvisning och skärmstorlek + extra info
  fetch('/includes/logger.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      page: window.location.pathname,
      screen_size: window.screen.width + 'x' + window.screen.height,
      device_type: getDeviceType(),
      language: language,
      timezone: timezone
    })
  });

  // Logga tid på sidan
  let start = Date.now();
  window.addEventListener('beforeunload', function () {
    fetch('/includes/logger.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      keepalive: true,
      body: JSON.stringify({
        page: window.location.pathname,
        screen_size: window.screen.width + 'x' + window.screen.height,
        time_on_page: Math.round((Date.now() - start) / 1000),
        device_type: getDeviceType(),
        language: language,
        timezone: timezone
      })
    });
  });

  // Logga klick
  document.addEventListener('click', function (e) {
    fetch('/includes/logger.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        page: window.location.pathname,
        click_event: e.target.tagName + (e.target.id ? '#' + e.target.id : ''),
        screen_size: window.screen.width + 'x' + window.screen.height,
        device_type: getDeviceType(),
        language: language,
        timezone: timezone
      })
    });
  });
} catch(e) {
  console.error("Logger JS error:", e);
}
</script>
