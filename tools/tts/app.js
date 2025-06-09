const express = require('express');
const textToSpeech = require('@google-cloud/text-to-speech');
const fs = require('fs');
const util = require('util');

const app = express();
const port = 40420; // Byt till en av de tillgängliga portarna på ditt webbhotell.

app.use(express.json());

// Google TTS-konfiguration
const client = new textToSpeech.TextToSpeechClient();

// TTS API Endpoint
app.post('/synthesize', async (req, res) => {
  const text = req.body.text;

  const request = {
    input: { text },
    voice: { languageCode: 'en-US', ssmlGender: 'NEUTRAL' },
    audioConfig: { audioEncoding: 'MP3' },
  };

  try {
    const [response] = await client.synthesizeSpeech(request);
    const writeFile = util.promisify(fs.writeFile);
    const fileName = 'output.mp3';
    await writeFile(fileName, response.audioContent, 'binary');
    console.log('Audio content written to file: output.mp3');
    res.sendFile(fileName, { root: __dirname });
  } catch (error) {
    console.error(error);
    res.status(500).send('Error synthesizing speech');
  }
});

app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});
