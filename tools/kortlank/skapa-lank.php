<?php
$title = 'Skapa ny länk';
$metaDescription = 'Förkorta en länk snabbt och enkelt. Klistra in din länk och få en direkt kortadress!';
include '../../includes/layout-start.php';
?>

<main class="layout__container">
  <header class="layout__sektion text--center">
    <h1 class="rubrik rubrik--sektion">
      <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
    </h1>
    <p class="text--lead">
      <?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>
    </p>
  </header>

  <section class="layout__sektion">
    <div class="kort">
    <form id="form-skapalank" class="form kort__innehall" autocomplete="off">
      <div class="form__grupp">
        <label for="url" class="falt__etikett">Länk att förkorta</label>
        <input type="url" id="url" name="url" class="falt__input" required placeholder="Klistra in din länk här">
      </div>
      <div class="form__verktyg">
        <button type="submit" class="knapp knapp--stor">Skapa</button>
      </div>
    </form>
    <section id="resultat" class="kort__innehall hidden">
      <div class="kort kort--huvud">
        <div class="kort__rubrik">Din länk:</div>
        <div class="kort__innehall text--center">
          <span id="kortLank"></span>
          <div class="knapp__grupp">
            <button id="copyBtn" class="knapp knapp__ikon hidden" data-tippy-content="Kopiera länk" aria-label="Kopiera länk">
              <i class="fa-solid fa-copy"></i>
            </button>
            <button id="qrBtn" class="knapp knapp__ikon"
              data-tippy-content=""
              data-tippy-allowHTML="true"
              aria-label="Visa QR-kod">
              <i class="fa-solid fa-qrcode"></i>
            </button>
          </div>
        </div>
        <div id="toast" class="toast hidden" role="status" aria-live="polite">Kopierat!</div>
      </div>
    </section>
    </div>
  </section>
</main>

<script>
const form = document.getElementById('form-skapalank');
const urlInput = document.getElementById('url');
const resultat = document.getElementById('resultat');
const kortLank = document.getElementById('kortLank');
const copyBtn = document.getElementById('copyBtn');
const qrBtn = document.getElementById('qrBtn');
const toast = document.getElementById('toast');
function setQrTippy(shortlink) {
  if (!qrBtn) return;
  const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(shortlink);
  qrBtn.setAttribute('data-tippy-content', `<img src="${qrUrl}" alt="QR-kod" width="120">`);
}

// Anropa setQrTippy(data.shortlink) när du fått kortlänken!

form.addEventListener('submit', async function(e) {
  e.preventDefault();
  const url = urlInput.value.trim();
  if (!url) return;

  try {
    const formData = new URLSearchParams();
    formData.append('url', url);

    const resp = await fetch('api/shorten.php', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: formData
    });
    const data = await resp.json();

    if (data && data.shortlink) {
      kortLank.textContent = data.shortlink;
      kortLank.setAttribute('data-url', data.shortlink);
      resultat.classList.remove('hidden');
      copyBtn.classList.remove('hidden');
      setQrTippy(data.shortlink);
    } else {
      kortLank.textContent = data && data.error ? data.error : 'Något gick fel. Försök igen!';
      resultat.classList.remove('hidden');
      copyBtn.classList.add('hidden');
      qrBtn.setAttribute('data-tippy-content', '');
    }
  } catch (err) {
    kortLank.textContent = 'Fel vid anslutning till servern.';
    resultat.classList.remove('hidden');
    copyBtn.classList.add('hidden');
    qrBtn.setAttribute('data-tippy-content', '');
  }
});

copyBtn.addEventListener('click', function() {
  const text = kortLank.getAttribute('data-url');
  if (!text) return;
  navigator.clipboard.writeText(text).then(() => {
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 1200);
  });
});

urlInput.addEventListener('paste', function() {
  setTimeout(() => {
    const url = urlInput.value.trim();
    if (!url) return;
    skapaKortlank(url);
  }, 10);
});

async function skapaKortlank(url) {
  const formData = new URLSearchParams();
  formData.append('url', url);

  try {
    const resp = await fetch('api/shorten.php', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: formData
    });
    const data = await resp.json();

    if (data && data.shortlink) {
      kortLank.textContent = data.shortlink;
      kortLank.setAttribute('data-url', data.shortlink);
      resultat.classList.remove('hidden');
      copyBtn.classList.remove('hidden');
      setQrTippy(data.shortlink);
    } else {
      kortLank.textContent = data && data.error ? data.error : 'Något gick fel. Försök igen!';
      resultat.classList.remove('hidden');
      copyBtn.classList.add('hidden');
      qrBtn.setAttribute('data-tippy-content', '');
    }
  } catch (err) {
    kortLank.textContent = 'Fel vid anslutning till servern.';
    resultat.classList.remove('hidden');
    copyBtn.classList.add('hidden');
    qrBtn.setAttribute('data-tippy-content', '');
  }
}
</script>

<?php include '../../includes/layout-end.php'; ?>
