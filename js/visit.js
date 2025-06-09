(function() {
  const data = {
    page: window.location.pathname,
    click_event: null,
    time_on_page: null,
    screen_size: `${window.screen.width}x${window.screen.height}`,
    error_message: null
  };

  // Logga första klicket på sidan
  document.addEventListener('click', function(e) {
    if (!data.click_event) {
      data.click_event = `Clicked: ${e.target.tagName} ${e.target.className}`;
    }
  }, { once: true });

  // Fånga fel
  window.onerror = function(msg) {
    data.error_message = msg;
  };

  // Tid på sidan
  const start = Date.now();
  window.addEventListener('beforeunload', function() {
    data.time_on_page = Math.round((Date.now() - start) / 1000);
    navigator.sendBeacon('/includes/logger.php', JSON.stringify(data));
  });

  // Skicka första besöket (utan tid/klick)
  fetch('/includes/logger.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
})();
