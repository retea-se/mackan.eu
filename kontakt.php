<!-- kontakt.php - v1 -->
<?php $title = 'Kontakt'; ?>
<?php include 'includes/layout-start.php'; ?>

<main class="container">
  <h1>Kontakt</h1>
  <p>Har du frågor eller feedback? Kontakta mig gärna!</p>

  <form action="mailto:info@mackan.eu" method="post" enctype="text/plain">
    <input class="input" type="text" name="namn" placeholder="Ditt namn" required>
    <input class="input" type="email" name="epost" placeholder="Din e-post" required>
    <textarea class="textarea" name="meddelande" placeholder="Meddelande" rows="5" required></textarea>
    <button type="submit" class="button">Skicka</button>
  </form>
</main>

<?php include 'includes/layout-end.php'; ?>
