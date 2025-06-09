// script.js - v13

document.addEventListener('DOMContentLoaded', () => {
    console.log('Log: JS v13 started');

    // ********** START Version Info **********
    const HTML_VERSION = 'v9';
    const CSS_VERSION = 'v11';
    const JS_VERSION = 'v13';

    const versionInfo = document.getElementById('versionInfo');
    if (versionInfo) {
        const today = new Date();
        const formattedDate = `${today.getFullYear()}-${(today.getMonth() + 1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
        versionInfo.innerHTML = `TL QR 25 | HTML ${HTML_VERSION} | CSS ${CSS_VERSION} | JS ${JS_VERSION} | ${formattedDate}`;
        console.log('Log: Version info updated');
    }
    // ********** SLUT Version Info **********

    // ********** START Variabler **********
    const typeButtons = document.querySelectorAll('.type-button');
    const formFields = document.getElementById('formFields');
    const generateBtn = document.getElementById('generateBtn');
    const qrPreview = document.getElementById('qrPreview');
    let selectedType = '';

    console.log('Log: Element status:', {
        typeButtonsCount: typeButtons.length,
        formFieldsFound: formFields !== null,
        generateBtnFound: generateBtn !== null,
        qrPreviewFound: qrPreview !== null
    });

    if (!formFields || !generateBtn || !qrPreview || typeButtons.length === 0) {
        console.error('Error: Required elements missing!');
        return;
    }
    // ********** SLUT Variabler **********

    // ********** START Händelser **********
    typeButtons.forEach(button => {
        button.addEventListener('click', () => {
            console.log('Log: Form type selected:', button.getAttribute('data-type'));
            typeButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            selectedType = button.getAttribute('data-type');
            renderFields(selectedType);

            qrPreview.innerHTML = "";
            const buttonsContainer = document.getElementById('extraButtons');
            if (buttonsContainer) {
                buttonsContainer.remove();
            }
        });
    });

    generateBtn.addEventListener('click', () => {
        console.log('Log: Generate button clicked');
        const qrData = collectFormData(selectedType);
        console.log('Log: Collected data:', qrData);

        qrPreview.innerHTML = "";

        const formattedData = formatData(selectedType, qrData);
        console.log('Log: Formatted QR data:', formattedData);

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
        const oldButtons = document.getElementById('extraButtons');
        if (oldButtons) {
            oldButtons.remove();
        }

        const buttonsContainer = document.createElement('div');
        buttonsContainer.id = "extraButtons";
        buttonsContainer.style.marginTop = "1rem";
        buttonsContainer.style.display = "flex";
        buttonsContainer.style.gap = "1rem";
        buttonsContainer.style.justifyContent = "center";

        const downloadBtn = document.createElement('button');
        downloadBtn.className = "button";
        downloadBtn.textContent = "Ladda ner bild";
        downloadBtn.addEventListener('click', downloadQRCode);
        buttonsContainer.appendChild(downloadBtn);

        const copyBtn = document.createElement('button');
        copyBtn.className = "button";
        copyBtn.textContent = "Kopiera bild";
        copyBtn.addEventListener('click', copyQRCodeToClipboard);
        buttonsContainer.appendChild(copyBtn);

        qrPreview.parentNode.appendChild(buttonsContainer);
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
                <div class="form-group">
                    <label for="textInput">Text:</label>
                    <input type="text" id="textInput" class="input" placeholder="Skriv text här">
                </div>
            `;
        } else if (type === 'url') {
            formFields.innerHTML = `
                <div class="form-group">
                    <label for="urlInput">URL:</label>
                    <input type="url" id="urlInput" class="input" placeholder="https://exempel.se">
                </div>
            `;
        } else if (type === 'vcard') {
            formFields.innerHTML = `
                <div class="form-group"><label for="firstName">Förnamn:</label><input type="text" id="firstName" class="input"></div>
                <div class="form-group"><label for="lastName">Efternamn:</label><input type="text" id="lastName" class="input"></div>
                <div class="form-group"><label for="email">E-post:</label><input type="email" id="email" class="input"></div>
                <div class="form-group"><label for="phone">Telefonnummer:</label><input type="tel" id="phone" class="input"></div>
                <div class="form-group"><label for="company">Företag:</label><input type="text" id="company" class="input"></div>
                <div class="form-group"><label for="job">Jobbtitel:</label><input type="text" id="job" class="input"></div>
                <div class="form-group"><label for="street">Gatuadress:</label><input type="text" id="street" class="input"></div>
                <div class="form-group"><label for="city">Stad:</label><input type="text" id="city" class="input"></div>
                <div class="form-group"><label for="zip">Postnummer:</label><input type="text" id="zip" class="input"></div>
                <div class="form-group"><label for="country">Land:</label><input type="text" id="country" class="input"></div>
            `;
        } else if (type === 'wifi') {
            formFields.innerHTML = `
                <div class="form-group"><label for="ssid">SSID:</label><input type="text" id="ssid" class="input"></div>
                <div class="form-group"><label for="password">Lösenord:</label><input type="text" id="password" class="input"></div>
                <div class="form-group"><label for="encryption">Kryptering:</label>
                    <select id="encryption" class="input">
                        <option value="WPA">WPA/WPA2</option>
                        <option value="WEP">WEP</option>
                        <option value="nopass">Ingen kryptering</option>
                    </select>
                </div>
            `;
        } else if (type === 'email') {
            formFields.innerHTML = `
                <div class="form-group"><label for="emailAddress">E-postadress:</label><input type="email" id="emailAddress" class="input"></div>
                <div class="form-group"><label for="emailSubject">Ämne:</label><input type="text" id="emailSubject" class="input"></div>
                <div class="form-group"><label for="emailBody">Meddelande:</label><textarea id="emailBody" class="input" rows="4"></textarea></div>
            `;
        } else if (type === 'sms') {
            formFields.innerHTML = `
                <div class="form-group"><label for="smsNumber">Mobilnummer:</label><input type="tel" id="smsNumber" class="input"></div>
                <div class="form-group"><label for="smsMessage">Meddelande:</label><textarea id="smsMessage" class="input" rows="3"></textarea></div>
            `;
        } else if (type === 'phone') {
            formFields.innerHTML = `
                <div class="form-group"><label for="phoneNumber">Telefonnummer:</label><input type="tel" id="phoneNumber" class="input"></div>
            `;
        } else if (type === 'geo') {
            formFields.innerHTML = `
                <div class="form-group"><label for="latitude">Latitude:</label><input type="text" id="latitude" class="input" placeholder="59.3293"></div>
                <div class="form-group"><label for="longitude">Longitude:</label><input type="text" id="longitude" class="input" placeholder="18.0686"></div>
            `;
        }
    }

    function collectFormData(type) {
        const data = {};

        if (type === 'text') {
            data.text = document.getElementById('textInput')?.value || '';
        } else if (type === 'url') {
            data.url = document.getElementById('urlInput')?.value || '';
        } else if (type === 'vcard') {
            data.firstName = document.getElementById('firstName')?.value || '';
            data.lastName = document.getElementById('lastName')?.value || '';
            data.email = document.getElementById('email')?.value || '';
            data.phone = document.getElementById('phone')?.value || '';
            data.company = document.getElementById('company')?.value || '';
            data.job = document.getElementById('job')?.value || '';
            data.street = document.getElementById('street')?.value || '';
            data.city = document.getElementById('city')?.value || '';
            data.zip = document.getElementById('zip')?.value || '';
            data.country = document.getElementById('country')?.value || '';
        } else if (type === 'wifi') {
            data.ssid = document.getElementById('ssid')?.value || '';
            data.password = document.getElementById('password')?.value || '';
            data.encryption = document.getElementById('encryption')?.value || 'WPA';
        } else if (type === 'email') {
            data.to = document.getElementById('emailAddress')?.value || '';
            data.subject = document.getElementById('emailSubject')?.value || '';
            data.body = document.getElementById('emailBody')?.value || '';
        } else if (type === 'sms') {
            data.number = document.getElementById('smsNumber')?.value || '';
            data.message = document.getElementById('smsMessage')?.value || '';
        } else if (type === 'phone') {
            data.number = document.getElementById('phoneNumber')?.value || '';
        } else if (type === 'geo') {
            data.latitude = document.getElementById('latitude')?.value || '';
            data.longitude = document.getElementById('longitude')?.value || '';
        }

        return data;
    }

    function formatData(type, data) {
        if (type === 'text') {
            return data.text;
        } else if (type === 'url') {
            return data.url;
        } else if (type === 'vcard') {
            return `BEGIN:VCARD\nVERSION:3.0\nN:${data.lastName};${data.firstName}\nFN:${data.firstName} ${data.lastName}\nORG:${data.company}\nTITLE:${data.job}\nTEL:${data.phone}\nEMAIL:${data.email}\nADR:;;${data.street};${data.city};;${data.zip};${data.country}\nEND:VCARD`;
        } else if (type === 'wifi') {
            return `WIFI:T:${data.encryption};S:${data.ssid};P:${data.password};;`;
        } else if (type === 'email') {
            return `mailto:${data.to}?subject=${encodeURIComponent(data.subject)}&body=${encodeURIComponent(data.body)}`;
        } else if (type === 'sms') {
            return `SMSTO:${data.number}:${data.message}`;
        } else if (type === 'phone') {
            return `tel:${data.number}`;
        } else if (type === 'geo') {
            return `geo:${data.latitude},${data.longitude}`;
        } else {
            return '';
        }
    }

    console.log('Log: JS v13 ready');
});
