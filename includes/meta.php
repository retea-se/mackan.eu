<?php
// includes/meta.php – v4
// git commit: Byt till main.css enligt ny BEM-struktur, ta bort gamla komponentlänkar

$title = $title ?? 'Mackan.eu';
$metaDescription = $metaDescription ?? 'Onlineverktyg för nördar';
$metaImage = $metaImage ?? '/icon/android-chrome-512x512.png';
$canonical = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#0066cc">
<link rel="canonical" href="<?= $canonical ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="Mackan.eu">
<meta property="og:title" content="<?= htmlspecialchars($title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta property="og:url" content="<?= $canonical ?>">
<meta property="og:image" content="https://mackan.eu<?= $metaImage ?>">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta name="twitter:image" content="https://mackan.eu<?= $metaImage ?>">

<!-- Favicon & manifest -->
<link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
<link rel="manifest" href="/icon/site.webmanifest">
<link rel="shortcut icon" href="/icon/favicon.ico">

<!-- Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="https://cdn.jsdelivr.net">

<!-- Fonts + CSS -->
<link rel="stylesheet" href="/css/google-fonts.css">
<link rel="stylesheet" href="/css/main.css">

<!-- Ikoner & bibliotek -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsoneditor@latest/dist/jsoneditor.min.css">


