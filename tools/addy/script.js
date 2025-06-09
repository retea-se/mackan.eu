// script.js - v2
// Funktioner för att generera, kopiera och byta språk i AnonAddy-verktyget

// ********** START Adressgenerator **********
function generateAddress() {
  const from = document.getElementById('fromAddress').value.trim();
  const to = document.getElementById('toAddress').value.trim();

  if (!from || !to || !to.includes('@') || !from.includes('@')) return;

  const [fromUser, fromDomain] = from.split('@');
  const [toUser, toDomain] = to.split('@');

  const result = `${fromUser}+${toUser}=${toDomain}@${fromDomain}`;
  const output = document.getElementById('generatedAddress');

  output.value = result;
  document.getElementById('copyButton').classList.remove('hidden');
}
// ********** SLUT Adressgenerator **********

// ********** START Kopiera **********
function copyToClipboard() {
  const input = document.getElementById('generatedAddress');
  input.select();
  document.execCommand('copy');
}
// ********** SLUT Kopiera **********

// ********** START Språkväxling **********
let currentLanguage = 'sv';

const translations = {
  sv: {
    fromLabel: 'Från',
    toLabel: 'Till',
    resultLabel: 'Resultat',
    generateButton: 'Generera Adress',
    copyButton: 'Kopiera',
    toggleThemeButton: 'Växla Tema',
    toggleLanguageButton: 'Sv/En'
  },
  en: {
    fromLabel: 'From',
    toLabel: 'To',
    resultLabel: 'Result',
    generateButton: 'Generate Address',
    copyButton: 'Copy',
    toggleThemeButton: 'Toggle Theme',
    toggleLanguageButton: 'En/Sv'
  }
};

function toggleLanguage() {
  currentLanguage = currentLanguage === 'sv' ? 'en' : 'sv';
  const t = translations[currentLanguage];

  document.getElementById('fromLabel').textContent = t.fromLabel;
  document.getElementById('toLabel').textContent = t.toLabel;
  document.getElementById('resultLabel').textContent = t.resultLabel;
  document.getElementById('generateButton').textContent = t.generateButton;
  document.getElementById('copyButton').textContent = t.copyButton;
  document.getElementById('themeToggle').textContent = t.toggleThemeButton;
  document.getElementById('languageToggle').textContent = t.toggleLanguageButton;
}
// ********** SLUT Språkväxling **********

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('languageToggle').addEventListener('click', toggleLanguage);
});
