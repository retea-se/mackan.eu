<!-- layout-start.php - v4 -->
<?php
// layout-start.php - v4
$title = $title ?? 'Mackan.eu';
$metaDescription = $metaDescription ?? 'Onlineverktyg för nördar';
?>
<!DOCTYPE html>
<html lang="sv" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
  <link rel="manifest" href="/icon/site.webmanifest">
  <link rel="shortcut icon" href="/icon/favicon.ico">

  <!-- CSS -->
  <link rel="stylesheet" href="/css/reset.css">
  <link rel="stylesheet" href="/css/variables.css">
  <link rel="stylesheet" href="/css/utilities.css">
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/navbar.css">
  <link rel="stylesheet" href="/css/tools.css">
  <link rel="stylesheet" href="/css/theme.css">
  <link rel="stylesheet" href="/css/typography.css">
  <link rel="stylesheet" href="/css/google-fonts.css">
  <link rel="stylesheet" href="/css/page.css">
  <link rel="stylesheet" href="/css/readme.css">
  <link rel="stylesheet" href="/css/index-cards.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>
