<?php
$title = 'Skapa ny länk';
$metaDescription = 'Förkorta en länk snabbt och enkelt. Klistra in din länk och få en direkt kortadress!';
include '../../includes/layout-start.php';
?>

<main class="container">
  <section class="kort kort--fokus utils--mt-2">
    <h1 class="kort__rubrik"><?= $title ?></h1>
    <form id="form-skapalank" class="form kort__innehall" autocomplete="off">
      <div class="falt falt--rad">
        <label for="url" class="falt__etikett">Länk att förkorta</label>
        <input type="url" id="url" name="url" class="falt__input" required placeholder="Klistra in din länk här">
      </div>
      <button type="submit" class="knapp knapp--stor utils--mt-1">Skapa</button>
    </form>
    <section id="resultat" class="kort__innehall utils--mt-2" style="display:none;">
      <div class="kort kort--huvud">
        <div class="kort__rubrik">Din länk:</div>
        <div class="kort__innehall">
          <span id="kortLank" class="utils--text-center"></span>
          <button id="copyBtn" class="knapp knapp--ikon utils--ml-1" data-tippy-content="Kopiera länk" aria-label="Kopiera länk">
            <i class="fa-solid fa-copy"></i>
          </button>
        </div>
        <div id="toast" class="toast utils--mt-1" style="display:none;">Kopierat!</div>
      </div>
    </section>
  </section>
</main>

<script>
const form = document.getElementById('form-skapalank');
const urlInput = document.getElementById('url');
const resultat = document.getElementById('resultat');
const kortLank = document.getElementById('kortLank');
const copyBtn = document.getElementById('copyBtn');
const toast = document.getElementById('toast');

form.addEventListener('submit', async function(e) {
  e.preventDefault();
  const url = urlInput.value.trim();
  if (!url) return;

  // AJAX-anrop till api/shorten.php
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
      // Visa resultatpanel och länk
      kortLank.textContent = data.shortlink;
      kortLank.setAttribute('data-url', data.shortlink);
      resultat.style.display = '';
      copyBtn.style.display = '';
    } else {
      kortLank.textContent = data && data.error ? data.error : 'Något gick fel. Försök igen!';
      resultat.style.display = '';
      copyBtn.style.display = 'none';
    }
  } catch (err) {
    kortLank.textContent = 'Fel vid anslutning till servern.';
    resultat.style.display = '';
    copyBtn.style.display = 'none';
  }
});

// Kopiera-knapp
copyBtn.addEventListener('click', function() {
  const text = kortLank.getAttribute('data-url');
  if (!text) return;
  navigator.clipboard.writeText(text).then(() => {
    toast.style.display = 'block';
    setTimeout(()=>toast.style.display='none', 1200);
  });
});

urlInput.addEventListener('paste', function(e) {
  setTimeout(() => {
    const url = urlInput.value.trim();
    if (!url) return;
    // Skicka AJAX-anrop som i submit-eventet
    skapaKortlank(url);
  }, 10); // Vänta tills värdet är inklistrat
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
      // Visa resultat och kopiera-knapp
      kortLank.textContent = data.shortlink;
      kortLank.setAttribute('data-url', data.shortlink);
      resultat.style.display = '';
      copyBtn.style.display = '';
    } else {
      kortLank.textContent = data && data.error ? data.error : 'Något gick fel. Försök igen!';
      resultat.style.display = '';
      copyBtn.style.display = 'none';
    }
  } catch (err) {
    kortLank.textContent = 'Fel vid anslutning till servern.';
    resultat.style.display = '';
    copyBtn.style.display = 'none';
  }
}
</script>

<?php include '../../includes/layout-end.php'; ?>
