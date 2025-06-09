const express = require('express');
const app = express();

app.get('/', (req, res) => {
    console.log('Hello World request received'); // Logga till serverns konsol

    // Skicka HTML och JavaScript till klienten
    res.send(`
        <html>
            <body>
                <p>Hello World</p>
                <script>
                    console.log('Hello World request received in browser');
                </script>
            </body>
        </html>
    `);
});

const port = process.env.PORT || 3000;
app.listen(port, () => {
    console.log(`Server running on port ${port}`); // Logga när servern startar
});
