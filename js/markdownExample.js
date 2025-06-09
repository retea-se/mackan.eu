// js/markdownExample.js - v1

/* ********** START: Rendera markdown-exempel med kopieringsknapp ********** */
export function renderMarkdownExample(containerId, codeString) {
  console.log(`markdownExample.js: Renderar markdown-exempel i container: ${containerId}`);

  const container = document.getElementById(containerId);
  if (!container) {
    console.warn(`markdownExample.js: Container med id "${containerId}" saknas i DOM.`);
    return;
  }

  container.innerHTML = `
    <pre class="terminal-output"><code>${escapeHtml(codeString)}</code></pre>
    <button class="button tiny" id="copyMarkdownBtn">Kopiera exempel</button>
  `;

  document.getElementById('copyMarkdownBtn').addEventListener('click', () => {
    navigator.clipboard.writeText(codeString)
      .then(() => {
        console.log('markdownExample.js: Markdown-exempel kopierat till urklipp.');
        alert('Markdown-exempel kopierat!');
      })
      .catch(err => {
        console.error('markdownExample.js: Kunde inte kopiera markdown-exempel:', err);
        alert('Kunde inte kopiera.');
      });
  });
}

/* Hjälpfunktion som ersätter HTML-specialtecken för korrekt visning */
function escapeHtml(text) {
  return text.replace(/[&<>"']/g, c => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  })[c]);
}
/* ********** SLUT: Rendera markdown-exempel med kopieringsknapp ********** */
