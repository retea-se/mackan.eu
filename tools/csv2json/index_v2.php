<?php $title = 'CSV till JSON v2'; ?>
<?php include '../../includes/layout-start.php'; ?>

<main class="layout__container">

  <section class="kort">
    <h2>Ladda upp din CSV-data</h2>
    <p>Använd formuläret nedan för att ladda upp en CSV-fil och använda de avancerade verktygen.</p>
    <div class="form__grupp"><!-- TODO: osäker konvertering: file-upload -->
      <label for="csvFileInput">Ladda upp en CSV-fil:</label>
      <input type="file" id="csvFileInput" class="falt__input" accept=".csv" />
      <button class="knapp" onclick="handleFileUpload()">Läs in fil</button>
    </div>
    <textarea id="csvInput" class="falt__textarea" placeholder="Klistra in din CSV-data här..."></textarea>
  </section>

  <section class="kort"><!-- TODO: osäker konvertering: csv-statistics -->
    <h2>Statistik för CSV-filen</h2>
    <p id="csvStats">Ladda upp en fil för att visa statistik.</p>
  </section>
</main>

<?php include '../../includes/layout-end.php'; ?>
<script src="avancerad.js" defer></script>
