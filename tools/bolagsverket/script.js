// tools/bolagsverket/script.js - v2

// ********** START Sektion: Eventlyssnare **********
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('tokenForm');
  const tokenOutput = document.getElementById('tokenOutput');
  const resultSection = document.getElementById('resultSection');
  const resetBtn = document.getElementById('resetBtn');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Visa loading-indikator
    const loadingEl = showLoading(resultSection, 'Hämtar token...');

    try {
      const response = await fetch('get_token.php');
      const data = await response.json();

      if (response.ok) {
        tokenOutput.textContent = JSON.stringify(data, null, 2);
        resultSection.classList.remove('hidden');
        console.log('Access token hämtad:', data);
        showToast('Token hämtad framgångsrikt.', 'success');
      } else {
        const errorMsg = 'Fel vid hämtning av token: ' + data.error;
        tokenOutput.textContent = errorMsg;
        resultSection.classList.remove('hidden');
        console.error('Fel vid hämtning av token:', data.error);
        showToast(errorMsg, 'error');
      }
    } catch (error) {
      const errorMsg = 'Fel vid hämtning av token: ' + error.message;
      tokenOutput.textContent = errorMsg;
      resultSection.classList.remove('hidden');
      console.error('Fel vid hämtning av token:', error);
      showToast(errorMsg, 'error');
    } finally {
      // Dölj loading-indikator
      hideLoading(resultSection);
    }
  });

  resetBtn.addEventListener('click', () => {
    tokenOutput.textContent = '';
    resultSection.classList.add('hidden');
  });
});
// ********** SLUT Sektion: Eventlyssnare **********
