<!-- meta.php - optimerad version -->
<!DOCTYPE html>
<html lang="sv" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? htmlspecialchars($title) . ' | Mackan.eu' : 'Mackan.eu'; ?></title>

  <?php if (!empty($metaDescription)): ?>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
  <?php endif; ?>

  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#0066cc">

  <!-- Preconnect för snabbare externa laddningar -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">

  <!-- Ikoner -->
  <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
  <link rel="manifest" href="/icon/site.webmanifest">
  <link rel="shortcut icon" href="/icon/favicon.ico">

  <!-- Externa CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.css">

  <!-- Egna CSS-filer i rekommenderad ordning -->
  <link rel="stylesheet" href="/css/reset.css">
  <link rel="stylesheet" href="/css/google-fonts.css">
  <link rel="stylesheet" href="/css/variables.css">
  <link rel="stylesheet" href="/css/utilities.css">
  <link rel="stylesheet" href="/css/theme.css">
  <link rel="stylesheet" href="/css/typography.css">
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/components.css">
<!-- <link rel="stylesheet" href="/css/navbar.css"> -->
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/tools.css">
  <link rel="stylesheet" href="/css/index-cards.css">
  <link rel="stylesheet" href="/css/readme.css">

  <!-- Externa JS -->
  <script src="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

  <!-- Egna JS -->
  <script src="/js/theme-toggle.js" defer></script>
  <script src="/js/visit.js"></script>
</head>
<body>
