<?php

// layout-start.php - v5
// git commit: Använd meta.php som gemensam <head>-sektion och säkerställ korrekt fallback

// Content-Security-Policy (CSP) header för att öka säkerheten.
// OBS! Om externa resurser (JS, CSS, typsnitt) slutar fungera i framtiden
// kan det bero på att deras domäner inte är tillagda här.
// Lägg till nya domäner i respektive direktiv vid behov.
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://cdn.sheetjs.com https://html2canvas.hertzen.com https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; img-src 'self' data:;");

$title = $title ?? 'Mackan.eu';
$metaDescription = $metaDescription ?? 'Onlineverktyg för nördar';
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title><?= $title ?? 'Mackan.eu' ?></title>
  <meta name="description" content="<?= $metaDescription ?? '' ?>">
  <link rel="stylesheet" href="/css/main.css">
  <!-- Lägg till övriga CSS-filer här -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
