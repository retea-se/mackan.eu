// tools/bolagsverket/script.js - v2

// ********** START Sektion: Eventlyssnare **********
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('tokenForm');
  const tokenOutput = document.getElementById('tokenOutput');
  const resultSection = document.getElementById('resultSection');
  const resetBtn = document.getElementById('resetBtn');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('get_token.php');
      const data = await response.json();

      if (response.ok) {
        tokenOutput.textContent = JSON.stringify(data, null, 2);
        resultSection.classList.remove('hidden');
        console.log('Access token hämtad:', data);
      } else {
        tokenOutput.textContent = 'Fel vid hämtning av token: ' + data.error;
        resultSection.classList.remove('hidden');
        console.error('Fel vid hämtning av token:', data.error);
      }
    } catch (error) {
      tokenOutput.textContent = 'Fel vid hämtning av token: ' + error.message;
      resultSection.classList.remove('hidden');
      console.error('Fel vid hämtning av token:', error);
    }
  });

  resetBtn.addEventListener('click', () => {
    tokenOutput.textContent = '';
    resultSection.classList.add('hidden');
  });
});
// ********** SLUT Sektion: Eventlyssnare **********
