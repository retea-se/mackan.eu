window.onload = async () => {
    // Lägg till lyssnare för klick på "Skapa PDF"-knappen
    document.getElementById('convert').addEventListener('click', async () => {
        const urls = document.getElementById('urls').value.split('\n');
        const pdfPromises = [];

        // Loopa igenom varje webbsida och konvertera till PDF
        for (const url of urls) {
            if (url.trim() !== '') {
                try {
                    const response = await fetch(url);
                    const htmlContent = await response.text();

                    // Konvertera HTML till PDF
                    const pdfPromise = html2pdf().from(htmlContent).outputPdf();
                    pdfPromises.push(pdfPromise);
                } catch (error) {
                    console.error(`Det gick inte att hämta eller konvertera från ${url}: ${error}`);
                }
            }
        }

        // Vänta på att alla PDF-filer ska skapas
        const pdfs = await Promise.all(pdfPromises);

        // Visa filspardialogen
        const blob = new Blob(pdfs, { type: 'application/pdf' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);

        // Ange filnamnet baserat på den sista delen av webbadressen
        const urlsArray = urls.filter(url => url.trim() !== '').map(url => url.trim());
        const lastUrl = urlsArray[urlsArray.length - 1];
        const filename = lastUrl.substring(lastUrl.lastIndexOf('/') + 1) + '.pdf';

        a.download = filename;
        a.click();
    });
};
