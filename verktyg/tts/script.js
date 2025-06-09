// ******************* START script.js *******************

// Hämtar textruta och knappar
const textInput = document.getElementById("textInput");
const voiceSelect = document.getElementById("voiceSelect");
const playBtn = document.getElementById("playBtn");
const downloadBtn = document.getElementById("downloadBtn");

let synth = window.speechSynthesis;
let voices = [];
let mediaRecorder; // För inspelning
let audioChunks = []; // Lagrar ljuddata

// Ladda röster
function loadVoices() {
    voices = synth.getVoices();
    voiceSelect.innerHTML = '';
    voices.forEach((voice, index) => {
        if (voice.lang.includes("sv")) { // Filtrera svenska röster
            const option = document.createElement('option');
            option.textContent = `${voice.name} (${voice.lang})`;
            option.value = index;
            voiceSelect.appendChild(option);
        }
    });
}

// Spela upp och spela in text
playBtn.addEventListener("click", () => {
    const text = textInput.value;
    const utterance = new SpeechSynthesisUtterance(text);
    const selectedVoice = voices[voiceSelect.value];
    utterance.voice = selectedVoice;

    // Starta inspelning
    const audioContext = new AudioContext();
    const destination = audioContext.createMediaStreamDestination();
    const source = audioContext.createMediaStreamSource(destination.stream);
    mediaRecorder = new MediaRecorder(destination.stream);

    audioChunks = [];
    mediaRecorder.ondataavailable = (event) => audioChunks.push(event.data);
    mediaRecorder.onstop = () => {
        const blob = new Blob(audioChunks, { type: "audio/wav" });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = "tts-audio.wav"; // Default till WAV
        link.click();
    };

    synth.speak(utterance);
    mediaRecorder.start();

    utterance.onend = () => {
        mediaRecorder.stop();
    };
});

// Ladda röster vid förändring
if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = loadVoices;
}

// Ladda ner ljudfil som MP3 eller WAV
downloadBtn.addEventListener("click", () => {
    const format = prompt("Ange format (mp3 eller wav):", "wav");
    if (format !== "wav" && format !== "mp3") {
        alert("Endast mp3 och wav stöds!");
        return;
    }

    const blobType = format === "mp3" ? "audio/mpeg" : "audio/wav";
    const blob = new Blob(audioChunks, { type: blobType });
    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.href = url;
    link.download = `tts-audio.${format}`;
    link.click();
});

// ******************* SLUT script.js *******************

