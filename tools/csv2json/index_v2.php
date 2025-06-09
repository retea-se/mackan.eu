<?php $title = 'CSV till JSON v2'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="container">


  <section class="upload-section card">
    <h2>Ladda upp din CSV-data</h2>
    <p>Använd formuläret nedan för att ladda upp en CSV-fil och använda de avancerade verktygen.</p>
    <div class="file-upload">
      <label for="csvFileInput">Ladda upp en CSV-fil:</label>
      <input type="file" id="csvFileInput" class="input file-input" accept=".csv" />
      <button class="button" onclick="handleFileUpload()">Läs in fil</button>
    </div>
    <textarea id="csvInput" class="textarea" placeholder="Klistra in din CSV-data här..."></textarea>
  </section>

  <section class="csv-statistics card">
    <h2>Statistik för CSV-filen</h2>
    <p id="csvStats">Ladda upp en fil för att visa statistik.</p>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="avancerad.js" defer></script>
