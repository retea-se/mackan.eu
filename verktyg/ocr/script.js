const uploadZone = document.getElementById('upload-zone');
const fileInput = document.getElementById('file-input');
const languageSelect = document.getElementById('language-select');
const runOcrButton = document.getElementById('run-ocr');
const ocrResults = document.getElementById('ocr-results');
const progressBar = document.getElementById('progress-bar');
const progressContainer = document.getElementById('progress-container');

// Aktivera klick på upload-zonen för att öppna filväljaren
uploadZone.addEventListener('click', () => fileInput.click());

uploadZone.addEventListener('dragover', event => {
    event.preventDefault();
    event.stopPropagation();
    // Anpassa stil här om du vill, t.ex. ändra bakgrundsfärg på dragover
});

uploadZone.addEventListener('drop', event => {
    event.preventDefault();
    event.stopPropagation();
    fileInput.files = event.dataTransfer.files; // Uppdatera input-fältet med den droppade filen
    uploadZone.textContent = `File selected: ${fileInput.files[0].name}`;
    // Du kan också automatiskt starta OCR-processen här om du föredrar det
});

fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        uploadZone.textContent = `File selected: ${fileInput.files[0].name}`;
    }
});

runOcrButton.addEventListener('click', () => {
    if (!fileInput.files.length) {
        alert('Please select a PDF file first.');
        return;
    }
    const lang = languageSelect.value;
    runOcr(fileInput.files[0], lang);
});

async function runOcr(file, lang) {
    ocrResults.textContent = '';
    progressContainer.style.display = 'block';
    progressBar.style.width = '0%';

    const fileReader = new FileReader();
    fileReader.onload = async (e) => {
        const typedArray = new Uint8Array(e.target.result);
        try {
            const pdf = await pdfjsLib.getDocument(typedArray).promise;
            const numPages = pdf.numPages;
            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                const page = await pdf.getPage(pageNum);
                const viewport = page.getViewport({ scale: 2 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                await page.render({ canvasContext: context, viewport: viewport }).promise;
                const imgData = canvas.toDataURL();

                await Tesseract.recognize(
                    imgData,
                    lang,
                    {
                        logger: m => {
                            if (m.status === 'recognizing text') {
                                const progress = ((pageNum - 1) / numPages + m.progress * (1 / numPages)) * 100;
                                progressBar.style.width = `${progress}%`;
                            }
                        }
                    }
                ).then(({ data: { text } }) => {
                    ocrResults.textContent += text + "\n";
                    if (pageNum === numPages) {
                        progressBar.style.width = '100%';
                    }
                });
            }
        } catch (error) {
            console.error('Error during OCR processing: ', error);
            ocrResults.textContent = 'Error during OCR processing.';
        }
    };
    fileReader.readAsArrayBuffer(file);
}
