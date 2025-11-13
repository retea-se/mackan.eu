/**
 * modal-utils.js - v1
 * Enkla modal-funktioner för att ersätta alert() och confirm()
 */

/**
 * Visar en enkel alert-modal
 * @param {string} message - Meddelandet att visa
 * @param {string} title - Titel (valfritt, standard: "Meddelande")
 * @returns {Promise<void>}
 */
export function showAlert(message, title = 'Meddelande') {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-labelledby', 'modal-title');

        modal.innerHTML = `
            <div class="modal__dialog">
                <button class="modal__close" aria-label="Stäng">&times;</button>
                <h2 class="modal__title" id="modal-title">${escapeHtml(title)}</h2>
                <div class="modal__body">
                    <p>${escapeHtml(message)}</p>
                </div>
                <div class="knapp__grupp" style="margin-top: 1.5rem;">
                    <button class="knapp" id="modal-ok">OK</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.classList.add('modal-open');

        const closeModal = () => {
            modal.remove();
            document.body.classList.remove('modal-open');
            resolve();
        };

        modal.querySelector('.modal__close').addEventListener('click', closeModal);
        modal.querySelector('#modal-ok').addEventListener('click', closeModal);

        // Stäng vid klick utanför modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Stäng vid ESC
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        // Fokusera på OK-knappen
        modal.querySelector('#modal-ok').focus();
    });
}

/**
 * Visar en confirm-modal
 * @param {string} message - Meddelandet att visa
 * @param {string} title - Titel (valfritt, standard: "Bekräfta")
 * @returns {Promise<boolean>} - true om användaren klickade "Ja", false om "Nej"
 */
export function showConfirm(message, title = 'Bekräfta') {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-labelledby', 'modal-title');

        modal.innerHTML = `
            <div class="modal__dialog">
                <button class="modal__close" aria-label="Stäng">&times;</button>
                <h2 class="modal__title" id="modal-title">${escapeHtml(title)}</h2>
                <div class="modal__body">
                    <p>${escapeHtml(message)}</p>
                </div>
                <div class="knapp__grupp" style="margin-top: 1.5rem;">
                    <button class="knapp" id="modal-yes">Ja</button>
                    <button class="knapp knapp--sekundär" id="modal-no">Nej</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.classList.add('modal-open');

        const closeModal = (result) => {
            modal.remove();
            document.body.classList.remove('modal-open');
            resolve(result);
        };

        modal.querySelector('.modal__close').addEventListener('click', () => closeModal(false));
        modal.querySelector('#modal-yes').addEventListener('click', () => closeModal(true));
        modal.querySelector('#modal-no').addEventListener('click', () => closeModal(false));

        // Stäng vid klick utanför modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(false);
            }
        });

        // Stäng vid ESC
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                closeModal(false);
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        // Fokusera på "Ja"-knappen
        modal.querySelector('#modal-yes').focus();
    });
}

/**
 * Escaper HTML för att förhindra XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Globala alias för bakåtkompatibilitet (valfritt)
if (typeof window !== 'undefined') {
    window.showAlert = showAlert;
    window.showConfirm = showConfirm;
}

