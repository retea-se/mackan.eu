<?php
// layout-start.php - v7
// git commit: Infört sticky-footer-stöd via .layout och .layout__main

header("Content-Security-Policy: default-src 'self'; connect-src 'self' https://skatteverket.entryscape.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://cdn.sheetjs.com https://html2canvas.hertzen.com https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; img-src 'self' data:;");

$title = $title ?? 'Mackan.eu';
$metaDescription = $metaDescription ?? 'Onlineverktyg för nördar';
?>
<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <meta name="description" content="<?= $metaDescription ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* === Sticky footer-stöd === */
    html, body {
      height: 100%;
      margin: 0;
    }
    .layout {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .layout__main {
      flex: 1 0 auto;
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/header.php'; ?>
    <main class="layout__main">
