// QR v3 Script - Kombinerad funktionalitet

document.addEventListener('DOMContentLoaded', () => {
    console.log('QR v3 loaded');

    const modeButtons = document.querySelectorAll('.qr__mode-btn[data-mode]');
    const singleMode = document.getElementById('single-mode');
    const batchMode = document.getElementById('batch-mode');
    const typeButtons = document.querySelectorAll('.qr__type-btn[data-type]');
    const batchTypeButtons = document.querySelectorAll('.qr__mode-btn[data-batch-type]');
    const singleForm = document.getElementById('single-form');
    const singleGenerate = document.getElementById('single-generate');
    const batchInput = document.getElementById('batch-input');
    const batchTextarea = document.getElementById('batch-textarea');
    const batchGenerate = document.getElementById('batch-generate');
    const qrPreview = document.getElementById('qr-preview');
    const batchPreview = document.getElementById('batch-preview');
    const exportOptions = document.getElementById('export-options');
    const batchStatus = document.getElementById('batch-status');

    if (!singleMode || !batchMode) {
        console.error('QR v3: Required markup missing');
        return;
    }

    let currentMode = 'single';
    let selectedType = '';
    let selectedBatchType = '';
    let generatedQRs = [];

    modeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const mode = btn.dataset.mode;
            if (!mode) return;

            modeButtons.forEach(b => b.classList.remove('qr__mode-btn--active'));
            btn.classList.add('qr__mode-btn--active');
            currentMode = mode;

            if (currentMode === 'single') {
                singleMode.classList.remove('hidden');
                singleMode.classList.add('qr__mode-content--active');
                batchMode.classList.add('hidden');
                batchMode.classList.remove('qr__mode-content--active');
            } else {
                batchMode.classList.remove('hidden');
                batchMode.classList.add('qr__mode-content--active');
                singleMode.classList.add('hidden');
                singleMode.classList.remove('qr__mode-content--active');
            }

            clearPreviews();
            exportOptions.classList.add('hidden');
            batchInput.classList.add('hidden');
            batchStatus?.classList.add('hidden');
        });
    });

    typeButtons.forEach(button => {
        button.addEventListener('click', () => {
            typeButtons.forEach(c => c.classList.remove('qr__type-btn--active'));
            button.classList.add('qr__type-btn--active');
            selectedType = button.dataset.type || '';
            renderSingleForm(selectedType);
            singleGenerate.classList.remove('hidden');
            qrPreview.innerHTML = '';
            exportOptions.classList.add('hidden');
        });
    });

    batchTypeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            batchTypeButtons.forEach(b => b.classList.remove('qr__mode-btn--active'));
            btn.classList.add('qr__mode-btn--active');
            selectedBatchType = btn.dataset.batchType || '';
            batchInput.classList.remove('hidden');
            batchTextarea.placeholder = getBatchPlaceholder(selectedBatchType);
            batchStatus?.classList.add('hidden');
            batchPreview.innerHTML = '';
            exportOptions.classList.add('hidden');
        });
    });

    singleGenerate.addEventListener('click', generateSingleQR);
    batchGenerate.addEventListener('click', generateBatchQRs);

    document.getElementById('download-png').addEventListener('click', downloadPNG);
    document.getElementById('download-zip').addEventListener('click', downloadZIP);
    document.getElementById('download-docx').addEventListener('click', downloadDOCX);

    function renderSingleForm(type) {
        const inputs = {
            text: `
                <div class="form__grupp">
                    <label for="text-input" class="falt__etikett">Text</label>
                    <textarea id="text-input" class="falt__textarea" rows="4" placeholder="Ange text som ska kodas..."></textarea>
                </div>
            `,
            url: `
                <div class="form__grupp">
                    <label for="url-input" class="falt__etikett">URL</label>
                    <input type="url" id="url-input" class="falt__input" placeholder="https://example.com">
                </div>
            `,
            vcard: `
                <div class="form__grupp"><label for="vcard-name" class="falt__etikett">Namn</label><input type="text" id="vcard-name" class="falt__input" placeholder="För- och efternamn"></div>
                <div class="form__grupp"><label for="vcard-phone" class="falt__etikett">Telefon</label><input type="tel" id="vcard-phone" class="falt__input" placeholder="070-123 45 67"></div>
                <div class="form__grupp"><label for="vcard-email" class="falt__etikett">E-post</label><input type="email" id="vcard-email" class="falt__input" placeholder="namn@example.com"></div>
                <div class="form__grupp"><label for="vcard-org" class="falt__etikett">Organisation</label><input type="text" id="vcard-org" class="falt__input" placeholder="Företag AB"></div>
            `,
            wifi: `
                <div class="form__grupp"><label for="wifi-ssid" class="falt__etikett">SSID</label><input type="text" id="wifi-ssid" class="falt__input" placeholder="Mitt WiFi"></div>
                <div class="form__grupp"><label for="wifi-password" class="falt__etikett">Lösenord</label><input type="text" id="wifi-password" class="falt__input" placeholder="wifi-lösenord"></div>
                <div class="form__grupp"><label for="wifi-security" class="falt__etikett">Säkerhet</label>
                    <select id="wifi-security" class="falt__input">
                        <option value="WPA">WPA/WPA2</option>
                        <option value="WEP">WEP</option>
                        <option value="">Öppet nätverk</option>
                    </select>
                </div>
            `,
            email: `
                <div class="form__grupp"><label for="email-to" class="falt__etikett">E-postadress</label><input type="email" id="email-to" class="falt__input" placeholder="mottagare@example.com"></div>
                <div class="form__grupp"><label for="email-subject" class="falt__etikett">Ämne</label><input type="text" id="email-subject" class="falt__input"></div>
                <div class="form__grupp"><label for="email-body" class="falt__etikett">Meddelande</label><textarea id="email-body" class="falt__textarea" rows="4"></textarea></div>
            `,
            sms: `
                <div class="form__grupp"><label for="sms-number" class="falt__etikett">Telefonnummer</label><input type="tel" id="sms-number" class="falt__input" placeholder="070-123 45 67"></div>
                <div class="form__grupp"><label for="sms-message" class="falt__etikett">Meddelande</label><textarea id="sms-message" class="falt__textarea" rows="3"></textarea></div>
            `,
            phone: `
                <div class="form__grupp"><label for="phone-number" class="falt__etikett">Telefonnummer</label><input type="tel" id="phone-number" class="falt__input" placeholder="070-123 45 67"></div>
            `,
            geo: `
                <div class="form__grupp"><label for="geo-lat" class="falt__etikett">Latitud</label><input type="number" id="geo-lat" class="falt__input" step="any" placeholder="59.3293"></div>
                <div class="form__grupp"><label for="geo-lng" class="falt__etikett">Longitud</label><input type="number" id="geo-lng" class="falt__input" step="any" placeholder="18.0686"></div>
            `
        };

        singleForm.innerHTML = inputs[type] || '';
    }

    function generateSingleQR() {
        const qrData = getSingleQRData();
        if (!qrData) return;

        clearPreviews();
        generatedQRs = [{ text: qrData, name: `qr_${Date.now()}` }];

        const qrItem = document.createElement('div');
        qrItem.className = 'qr__item';

        const qrCodeWrap = document.createElement('div');
        qrCodeWrap.className = 'qr__code';
        qrItem.appendChild(qrCodeWrap);

        new QRCode(qrCodeWrap, {
            text: qrData,
            width: 200,
            height: 200
        });

        qrPreview.innerHTML = '';
        qrPreview.appendChild(qrItem);

        exportOptions.classList.remove('hidden');
    }

    function generateBatchQRs() {
        if (!selectedBatchType) {
            alert('Välj först vilken typ av batch du vill generera.');
            return;
        }

        const lines = batchTextarea.value.split('\n').map(line => line.trim()).filter(Boolean);
        if (lines.length === 0) {
            alert('Ange minst en rad med data.');
            return;
        }

        clearPreviews();
        generatedQRs = [];

        const fragment = document.createDocumentFragment();
        lines.forEach((line, index) => {
            const qrItem = document.createElement('div');
            qrItem.className = 'qr__item';

            const qrCodeWrap = document.createElement('div');
            qrCodeWrap.className = 'qr__code';
            qrItem.appendChild(qrCodeWrap);

            const formatted = formatBatchData(selectedBatchType, line);
            new QRCode(qrCodeWrap, {
                text: formatted,
                width: 200,
                height: 200
            });

            const caption = document.createElement('p');
            caption.className = 'text--muted text--small';
            caption.textContent = line;
            qrItem.appendChild(caption);

            fragment.appendChild(qrItem);
            generatedQRs.push({ text: formatted, name: `batch_${index + 1}` });
        });

        batchPreview.appendChild(fragment);
        exportOptions.classList.remove('hidden');
        batchStatus.textContent = `${generatedQRs.length} QR-koder genererade.`;
        batchStatus.classList.remove('hidden');
    }

    function getSingleQRData() {
        switch (selectedType) {
            case 'text':
                return document.getElementById('text-input')?.value || '';
            case 'url':
                return document.getElementById('url-input')?.value || '';
            case 'vcard':
                return `BEGIN:VCARD\nVERSION:3.0\nFN:${document.getElementById('vcard-name')?.value || ''}\nTEL:${document.getElementById('vcard-phone')?.value || ''}\nEMAIL:${document.getElementById('vcard-email')?.value || ''}\nORG:${document.getElementById('vcard-org')?.value || ''}\nEND:VCARD`;
            case 'wifi':
                return `WIFI:T:${document.getElementById('wifi-security')?.value || 'WPA'};S:${document.getElementById('wifi-ssid')?.value || ''};P:${document.getElementById('wifi-password')?.value || ''};;`;
            case 'email':
                return `mailto:${document.getElementById('email-to')?.value || ''}?subject=${encodeURIComponent(document.getElementById('email-subject')?.value || '')}&body=${encodeURIComponent(document.getElementById('email-body')?.value || '')}`;
            case 'sms':
                return `SMSTO:${document.getElementById('sms-number')?.value || ''}:${document.getElementById('sms-message')?.value || ''}`;
            case 'phone':
                return `TEL:${document.getElementById('phone-number')?.value || ''}`;
            case 'geo':
                return `geo:${document.getElementById('geo-lat')?.value || ''},${document.getElementById('geo-lng')?.value || ''}`;
            default:
                alert('Välj en typ av QR-kod.');
                return '';
        }
    }

    function formatBatchData(type, line) {
        if (type === 'felanmalan') {
            const [nod, address = ''] = line.split(/[,;\t]/).map(part => part.trim());
            const subject = encodeURIComponent(`Felanmälan ${nod || ''}`);
            return `mailto:felanmalan@example.com?subject=${subject}&body=${encodeURIComponent(address)}`;
        }
        if (type === 'links') {
            return line;
        }
        return line;
    }

    function clearPreviews() {
        qrPreview.innerHTML = '';
        batchPreview.innerHTML = '';
        generatedQRs = [];
    }

    function getBatchPlaceholder(type) {
        switch (type) {
            case 'felanmalan':
                return 'Exempel:\nNod1, Adress för nod 1\nNod2, Adress för nod 2';
            case 'links':
                return 'Exempel:\nhttps://example.com\nhttps://google.com';
            case 'text':
                return 'Exempel:\nText för QR 1\nText för QR 2';
            default:
                return 'Ange text per rad...';
        }
    }

    function downloadPNG() {
        if (generatedQRs.length === 0) {
            alert('Inga QR-koder att spara.');
            return;
        }

        // För batch-mode: rekommendera ZIP/DOCX istället
        if (currentMode === 'batch' && generatedQRs.length > 1) {
            const useZip = confirm(`${generatedQRs.length} QR-koder genererade. PNG laddar bara ner den första. Vill du ladda ner alla som ZIP istället?`);
            if (useZip) {
                downloadZIP();
                return;
            }
        }

        // Hämta rätt canvas baserat på mode
        const canvas = currentMode === 'single'
            ? qrPreview.querySelector('canvas')
            : batchPreview.querySelector('canvas');

        if (!canvas) {
            alert('Kunde inte hitta någon QR-kod.');
            return;
        }

        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = `${generatedQRs[0].name}.png`;
        link.click();
    }

    async function downloadZIP() {
        if (generatedQRs.length === 0) {
            alert('Inga QR-koder att spara.');
            return;
        }

        const zip = new JSZip();
        const canvases = (qrPreview.querySelectorAll('canvas').length ? qrPreview : batchPreview).querySelectorAll('canvas');
        await Promise.all(Array.from(canvases).map(async (canvas, index) => {
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
            zip.file(`${generatedQRs[index]?.name || 'qr_' + (index + 1)}.png`, blob);
        }));

        const content = await zip.generateAsync({ type: 'blob' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(content);
        link.download = 'qr-koder.zip';
        link.click();
    }

    async function downloadDOCX() {
        if (generatedQRs.length === 0) {
            alert('Inga QR-koder att spara.');
            return;
        }

        const doc = new docx.Document({
            sections: [
                {
                    children: generatedQRs.map((qr, index) => {
                        const canvas = (qrPreview.querySelectorAll('canvas').length ? qrPreview : batchPreview).querySelectorAll('canvas')[index];
                        const dataUrl = canvas.toDataURL('image/png');
                        const binary = atob(dataUrl.split(',')[1]);
                        const buffer = new ArrayBuffer(binary.length);
                        const uint8Array = new Uint8Array(buffer);
                        for (let i = 0; i < binary.length; i++) {
                            uint8Array[i] = binary.charCodeAt(i);
                        }
                        return new docx.Paragraph({
                            children: [
                                new docx.TextRun({ text: qr.text, break: 1 }),
                                new docx.ImageRun({ data: uint8Array, transformation: { width: 200, height: 200 } }),
                                new docx.TextRun({ text: '\n', break: 1 })
                            ]
                        });
                    })
                }
            ]
        });

        const blob = await docx.Packer.toBlob(doc);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'qr-koder.docx';
        link.click();
    }
});
