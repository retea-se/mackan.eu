// script.js - v14 - Improved alt-text with MutationObserver

document.addEventListener('DOMContentLoaded', () => {
    console.log('Log: JS v14 started');

    // ********** START Version Info **********
    const HTML_VERSION = 'v9';
    const CSS_VERSION = 'v11';
    const JS_VERSION = 'v14';

    const versionInfo = document.getElementById('versionInfo');
    if (versionInfo) {
        const today = new Date();
        const formattedDate = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
        versionInfo.textContent = `TL QR 25 · HTML ${HTML_VERSION} · CSS ${CSS_VERSION} · JS ${JS_VERSION} · ${formattedDate}`;
        console.log('Log: Version info updated');
    }
    // ********** SLUT Version Info **********

    // ********** START Variabler **********
    const typeButtons = document.querySelectorAll('.qr__type-btn');
    const formFields = document.getElementById('formFields');
    const generateBtn = document.getElementById('generateBtn');
    const qrPreview = document.getElementById('qrPreview');
    const extraButtons = document.getElementById('extraButtons');
    let selectedType = '';
    let lastQRDescription = 'QR-kod'; // Lagra senaste beskrivningen för alt-text

    console.log('Log: Element status:', {
        typeButtonsCount: typeButtons.length,
        formFieldsFound: formFields !== null,
        generateBtnFound: generateBtn !== null,
        qrPreviewFound: qrPreview !== null
    });

    if (!formFields || !generateBtn || !qrPreview || !extraButtons || typeButtons.length === 0) {
        console.error('Error: Required elements missing!');
        return;
    }
    // ********** SLUT Variabler **********

    // ********** START Händelser **********
    typeButtons.forEach(button => {
        button.addEventListener('click', () => {
            console.log('Log: Form type selected:', button.getAttribute('data-type'));
            typeButtons.forEach(btn => btn.classList.remove('qr__type-btn--active'));
            button.classList.add('qr__type-btn--active');
            selectedType = button.getAttribute('data-type');
            renderFields(selectedType);

            generateBtn.classList.remove('hidden');
            qrPreview.innerHTML = "";
            extraButtons.classList.add('hidden');
            extraButtons.innerHTML = '';
            extraButtons.setAttribute('aria-hidden', 'true');
        });
    });

    // Skapa MutationObserver för att lägga till alt-text på QR-bilder
    const qrObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) { // Element node
                    // Kolla både noden själv och dess barn
                    const images = node.tagName === 'IMG' ? [node] : node.querySelectorAll('img');
                    images.forEach((img) => {
                        if (!img.alt || img.alt === '') {
                            img.alt = `QR-kod för ${lastQRDescription}`;
                            console.log(`✅ Alt-text added: ${img.alt}`);
                        }
                    });
                }
            });
        });
    });

    // Observera QR-preview
    if (qrPreview) {
        qrObserver.observe(qrPreview, { childList: true, subtree: true });
    }

    generateBtn.addEventListener('click', () => {
        console.log('Log: Generate button clicked');
        const qrData = collectFormData(selectedType);
        console.log('Log: Collected data:', qrData);

        qrPreview.innerHTML = "";

        const formattedData = formatData(selectedType, qrData);
        console.log('Log: Formatted QR data:', formattedData);

        // Sätt beskrivning för alt-text baserat på typ och data
        try {
            lastQRDescription = selectedType === 'text' ? (qrData.text || 'text') :
                               selectedType === 'url' ? (qrData.url || 'länk') :
                               selectedType === 'wifi' ? `WiFi: ${qrData.ssid || 'nätverk'}` :
                               selectedType === 'vcard' ? `Kontakt: ${qrData.name || 'kontakt'}` :
                               selectedType === 'email' ? `E-post: ${qrData.email || 'e-post'}` :
                               selectedType === 'sms' ? `SMS: ${qrData.phone || 'telefon'}` :
                               selectedType === 'phone' ? `Tel: ${qrData.phone || 'telefon'}` :
                               selectedType === 'geo' ? `Plats: ${qrData.lat || '?'}, ${qrData.lng || '?'}` :
                               'innehåll';
        } catch (e) {
            lastQRDescription = 'innehåll';
            console.warn('⚠️ Could not generate description:', e);
        }

        new QRCode(qrPreview, {
            text: formattedData,
            width: 256,
            height: 256
        });

        createExtraButtons();
    });
    // ********** SLUT Händelser **********

    // ********** START Funktioner **********
    function createExtraButtons() {
        extraButtons.innerHTML = '';

        const downloadBtn = document.createElement('button');
        downloadBtn.className = 'knapp knapp--liten';
        downloadBtn.textContent = 'Ladda ner bild';
        downloadBtn.addEventListener('click', downloadQRCode);
        extraButtons.appendChild(downloadBtn);

        const copyBtn = document.createElement('button');
        copyBtn.className = 'knapp knapp--liten';
        copyBtn.textContent = 'Kopiera bild';
        copyBtn.addEventListener('click', copyQRCodeToClipboard);
        extraButtons.appendChild(copyBtn);

        extraButtons.classList.remove('hidden');
        extraButtons.setAttribute('aria-hidden', 'false');
    }

    function downloadQRCode() {
        const canvas = qrPreview.querySelector('canvas');
        if (!canvas) {
            console.error('Error: Ingen QR-kod att ladda ner.');
            return;
        }
        const now = new Date();
        const timestamp = `${now.getFullYear()}-${(now.getMonth() + 1).toString().padStart(2,'0')}-${now.getDate().toString().padStart(2,'0')}_${now.getHours().toString().padStart(2,'0')}-${now.getMinutes().toString().padStart(2,'0')}-${now.getSeconds().toString().padStart(2,'0')}`;
        const filename = `QR_${selectedType}_${timestamp}.png`;

        const link = document.createElement('a');
        link.href = canvas.toDataURL("image/png");
        link.download = filename;
        link.click();
    }

    async function copyQRCodeToClipboard() {
        const canvas = qrPreview.querySelector('canvas');
        if (!canvas) {
            console.error('Error: Ingen QR-kod att kopiera.');
            return;
        }
        try {
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
            await navigator.clipboard.write([
                new ClipboardItem({ 'image/png': blob })
            ]);
            console.log('Log: QR-kod kopierad till urklipp.');
            alert('QR-koden är kopierad! Nu kan du klistra in den i ett mejl eller dokument.');
        } catch (error) {
            console.error('Error: Kopiering misslyckades.', error);
            alert('Kunde inte kopiera QR-koden. Prova en annan webbläsare.');
        }
    }

    function renderFields(type) {
        formFields.innerHTML = '';

        if (type === 'text') {
            formFields.innerHTML = `
                <div class="form__grupp">
                    <label for="textInput" class="falt__etikett">Text</label>
                    <input type="text" id="textInput" class="falt__input" placeholder="Skriv text här">
                </div>
            `;
        } else if (type === 'url') {
            formFields.innerHTML = `
                <div class="form__grupp">
                    <label for="urlInput" class="falt__etikett">URL</label>
                    <input type="url" id="urlInput" class="falt__input" placeholder="https://exempel.se">
                </div>
            `;
        } else if (type === 'vcard') {
            formFields.innerHTML = `
                <div class="form__grupp"><label for="firstName" class="falt__etikett">Förnamn</label><input type="text" id="firstName" class="falt__input"></div>
                <div class="form__grupp"><label for="lastName" class="falt__etikett">Efternamn</label><input type="text" id="lastName" class="falt__input"></div>
                <div class="form__grupp"><label for="email" class="falt__etikett">E-post</label><input type="email" id="email" class="falt__input"></div>
                <div class="form__grupp"><label for="phone" class="falt__etikett">Telefonnummer</label><input type="tel" id="phone" class="falt__input"></div>
                <div class="form__grupp"><label for="company" class="falt__etikett">Företag</label><input type="text" id="company" class="falt__input"></div>
                <div class="form__grupp"><label for="job" class="falt__etikett">Jobbtitel</label><input type="text" id="job" class="falt__input"></div>
                <div class="form__grupp"><label for="street" class="falt__etikett">Gatuadress</label><input type="text" id="street" class="falt__input"></div>
                <div class="form__grupp"><label for="city" class="falt__etikett">Stad</label><input type="text" id="city" class="falt__input"></div>
                <div class="form__grupp"><label for="zip" class="falt__etikett">Postnummer</label><input type="text" id="zip" class="falt__input"></div>
                <div class="form__grupp"><label for="country" class="falt__etikett">Land</label><input type="text" id="country" class="falt__input"></div>
            `;
        } else if (type === 'wifi') {
            formFields.innerHTML = `
                <div class="form__grupp"><label for="ssid" class="falt__etikett">SSID</label><input type="text" id="ssid" class="falt__input"></div>
                <div class="form__grupp"><label for="password" class="falt__etikett">Lösenord</label><input type="text" id="password" class="falt__input"></div>
                <div class="form__grupp"><label for="encryption" class="falt__etikett">Kryptering</label>
                    <select id="encryption" class="falt__input">
                        <option value="WPA">WPA/WPA2</option>
                        <option value="WEP">WEP</option>
                        <option value="nopass">Ingen kryptering</option>
                    </select>
                </div>
            `;
        } else if (type === 'email') {
            formFields.innerHTML = `
                <div class="form__grupp"><label for="emailAddress" class="falt__etikett">E-postadress</label><input type="email" id="emailAddress" class="falt__input"></div>
                <div class="form__grupp"><label for="emailSubject" class="falt__etikett">Ämne</label><input type="text" id="emailSubject" class="falt__input"></div>
                <div class="form__grupp"><label for="emailBody" class="falt__etikett">Meddelande</label><textarea id="emailBody" class="falt__textarea" rows="4"></textarea></div>
            `;
        } else if (type === 'sms') {
            formFields.innerHTML = `
                <div class="form__grupp"><label for="smsNumber" class="falt__etikett">Mobilnummer</label><input type="tel" id="smsNumber" class="falt__input"></div>
                <div class="form__grupp"><label for="smsMessage" class="falt__etikett">Meddelande</label><textarea id="smsMessage" class="falt__textarea" rows="3"></textarea></div>
            `;
        } else if (type === 'phone') {
            formFields.innerHTML = `
                <div class="form__grupp"><label for="phoneNumber" class="falt__etikett">Telefonnummer</label><input type="tel" id="phoneNumber" class="falt__input"></div>
            `;
        } else if (type === 'geo') {
            formFields.innerHTML = `
                <div class="form__grupp"><label for="latitude" class="falt__etikett">Latitud</label><input type="text" id="latitude" class="falt__input" placeholder="59.3293"></div>
                <div class="form__grupp"><label for="longitude" class="falt__etikett">Longitud</label><input type="text" id="longitude" class="falt__input" placeholder="18.0686"></div>
            `;
        }
    }

    function collectFormData(type) {
        const data = {};
        const inputs = formFields.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            data[input.id] = input.value;
        });
        return data;
    }

    function formatData(type, data) {
        switch (type) {
            case 'text':
                return data.textInput || '';
            case 'url':
                return data.urlInput || '';
            case 'vcard':
                return `BEGIN:VCARD\nVERSION:3.0\nFN:${data.firstName || ''} ${data.lastName || ''}\nEMAIL:${data.email || ''}\nTEL:${data.phone || ''}\nORG:${data.company || ''}\nTITLE:${data.job || ''}\nADR:;;${data.street || ''};${data.city || ''};;${data.zip || ''};${data.country || ''}\nEND:VCARD`;
            case 'wifi':
                return `WIFI:T:${data.encryption || 'WPA'};S:${data.ssid || ''};P:${data.password || ''};;`;
            case 'email':
                return `mailto:${data.emailAddress || ''}?subject=${encodeURIComponent(data.emailSubject || '')}&body=${encodeURIComponent(data.emailBody || '')}`;
            case 'sms':
                return `SMSTO:${data.smsNumber || ''}:${data.smsMessage || ''}`;
            case 'phone':
                return `TEL:${data.phoneNumber || ''}`;
            case 'geo':
                return `geo:${data.latitude || ''},${data.longitude || ''}`;
            default:
                return '';
        }
    }
    // ********** SLUT Funktioner **********
});
