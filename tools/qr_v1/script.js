// JSZip-instans kommer skapas när det behövs
let zip = null;

// Funktion för att vänta på JSZip
function waitForJSZip(callback) {
  if (typeof JSZip !== 'undefined') {
    callback();
  } else {
    setTimeout(() => waitForJSZip(callback), 100);
  }
}

// Kontrollera om `docx` och andra moduler är korrekt laddade
console.log("JSZip loaded:", typeof JSZip !== "undefined" ? "Yes" : "No");
console.log("QRCode.js loaded:", typeof QRCode !== "undefined" ? "Yes" : "No");
console.log("docx.js loaded:", typeof docx !== "undefined" ? "Yes" : "No");

// Extrahera "Nod" och "Adress" från en rad
function extractNodAndAddress(line) {
    const regex = /^([^,\s]+)[,\s]*(.*)$/;
    const match = line.match(regex);
    if (match && match[1] && match[2]) {
        console.log("extractNodAndAddress - match found:", match);
        return { nod: match[1], address: match[2] };
    } else {
        console.log("extractNodAndAddress - no match for line:", line);
        return null;
    }
}

function showQRCodeSection(type) {
    console.log("Visar QR-kodsektionen för:", type);

    const qrSection = document.getElementById("qr-section");
    if (!qrSection) {
        console.error("Elementet `qr-section` hittades inte!");
        return;
    }

    qrSection.style.display = "block"; // Gör synlig
    qrSection.classList.remove("hidden"); // Ta bort eventuell dold klass
    console.log("qr-section är nu synlig.");

    const textbox = document.getElementById("textbox");
    const qrcodeContainer = document.getElementById("qrcode");

    if (!textbox || !qrcodeContainer) {
        console.error("Element för textbox eller QR-kod saknas!");
        return;
    }

    textbox.value = ""; // Rensar textrutan
    textbox.placeholder = `Ange text för ${type} per rad...`;
    qrcodeContainer.innerHTML = ""; // Rensar QR-koder
    document.getElementById("generate-button").innerText = `Generera QR-koder för ${type}`;
}

function generateQRCode() {
    console.log("generateQRCode kallad...");
    const qrContainer = document.getElementById("qrcode");
    if (!qrContainer) {
        console.error("QR-kodscontainern `qrcode` saknas!");
        return;
    }

    qrContainer.innerHTML = ""; // Rensa tidigare QR-koder
    const lines = document.getElementById("textbox").value.split("\n");
    console.log("Rader att bearbeta:", lines);

    lines.forEach((line, index) => {
        if (!line.trim()) {
            console.log(`Tom rad hoppar över index ${index}`);
            return;
        }

        const extractionResult = extractNodAndAddress(line);
        let displayText = line; // Standard: använd hela raden
        let emailLink = line; // Standard: använd raden som e-postlänk

        if (extractionResult) {
            const { nod, address } = extractionResult;
            displayText = address;
            const subject = encodeURIComponent(`Felanmälan ${nod}`);
            emailLink = `mailto:example@example.com?subject=${subject}&body=${address}`;
        }

        // Skapa en div för QR-koden och texten
        const qrDivContainer = document.createElement("div");
        qrDivContainer.className = "qr-container";
        qrDivContainer.setAttribute("data-email-link", emailLink);
        qrContainer.appendChild(qrDivContainer);

        // Generera QR-kod med QRCode.js
        const qrDiv = document.createElement("div");
        qrDiv.id = `qrcode${index}`;
        qrDivContainer.appendChild(qrDiv);

        new QRCode(qrDiv.id, {
            text: emailLink,
            width: 128,
            height: 128
        });

        // Lägg till alt-text på QR-bilden efter den skapats
        setTimeout(() => {
            const img = qrDiv.querySelector('img');
            if (img) {
                img.alt = `QR kod för: ${displayText}`;
            }
        }, 100);

        console.log(`QR-kod genererad för index ${index}:`, emailLink);

        // Lägg till text under QR-koden
        const qrTextDiv = document.createElement("div");
        qrTextDiv.className = "qr-text";
        qrTextDiv.textContent = displayText;
        qrDivContainer.appendChild(qrTextDiv);
    });

    console.log("Alla QR-koder genererade!");
}

function downloadAllQRCodes() {
    console.log("Laddar ner alla QR-koder som PNG...");
    waitForJSZip(() => {
        const qrContainerList = document.querySelectorAll(".qr-container");
        if (!qrContainerList.length) {
            console.warn("Inga QR-koder hittades för nedladdning!");
            return;
        }

        zip = new JSZip();
        const currentDate = new Date().toISOString().split("T")[0];
        const zipFileName = `QR-koder-${currentDate}.zip`;

        qrContainerList.forEach((qrContainer, index) => {
            const lineText = qrContainer.querySelector(".qr-text")?.textContent || `QR${index + 1}`;
            const canvas = qrContainer.querySelector("canvas");
            if (!canvas) {
                console.warn(`QR-kod saknas för index ${index}`);
                return;
            }

            const dataURL = canvas.toDataURL("image/png", 1.0);
            const dataBlob = dataURLtoBlob(dataURL);
            zip.file(`${lineText}.png`, dataBlob);
        });

        zip.generateAsync({ type: "blob" }).then((content) => {
            const link = document.createElement("a");
            link.download = zipFileName;
            link.href = URL.createObjectURL(content);
            link.click();
            console.log("QR-koder nedladdade som ZIP:", zipFileName);
        });
    });
}

function dataURLtoBlob(dataURL) {
    const parts = dataURL.split(",");
    const contentType = parts[0].split(":")[1];
    const raw = window.atob(parts[1]);
    const rawLength = raw.length;
    const uInt8Array = new Uint8Array(rawLength);

    for (let i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], { type: contentType });
}

async function downloadAllDocx() {
    console.log("Laddar ner QR-koder som DOCX...");

    await new Promise(resolve => waitForJSZip(resolve));

    const qrContainerList = document.querySelectorAll(".qr-container");
    const wordZip = new JSZip();

    for (const qrContainer of qrContainerList) {
        const lineText = qrContainer.querySelector(".qr-text")?.textContent || "QR Code";
        const canvas = qrContainer.querySelector("canvas");
        if (!canvas) {
            console.warn(`Canvas saknas för QR-kod med text: ${lineText}`);
            continue;
        }

        const dataURL = canvas.toDataURL("image/png", 1.0);
        const binary = atob(dataURL.split(",")[1]);
        const arrayBuffer = new ArrayBuffer(binary.length);
        const uint8Array = new Uint8Array(arrayBuffer);

        for (let i = 0; i < binary.length; i++) {
            uint8Array[i] = binary.charCodeAt(i);
        }

        const doc = new docx.Document({
            sections: [
                {
                    children: [
                        new docx.Paragraph(lineText),
                        new docx.Paragraph({
                            children: [
                                new docx.ImageRun({
                                    data: uint8Array,
                                    transformation: { width: 100, height: 100 }
                                }),
                            ],
                        }),
                    ],
                },
            ],
        });

        const blob = await docx.Packer.toBlob(doc);
        wordZip.file(`${lineText}.docx`, blob);
    }

    wordZip.generateAsync({ type: "blob" }).then((content) => {
        const link = document.createElement("a");
        link.download = "WordFiles.zip";
        link.href = URL.createObjectURL(content);
        link.click();
        console.log("DOCX-filer nedladdade som ZIP.");
    });
}
