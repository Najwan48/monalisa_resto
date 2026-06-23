<?php
require_once 'db.php';
require_once 'functions.php';

set_no_cache_headers();

$halaman_sekarang = basename($_SERVER['PHP_SELF']);

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak'");
$stmt->execute();
$kontak = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? h($page_title) . ' — ' : '' ?>Monalisa Resto · Authentic Javanese Cuisine</title>
    <meta name="description" content="Monalisa Resto menyajikan kelezatan otentik khas Jawa Tengah di jantung Kota Bogor. Nikmati hidangan legendaris dalam suasana yang nyaman dan penuh seni.">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css?v=1.2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="assets/js/main.js" defer></script>
</head>
<body>

<header id="main-header">
    <div class="nav-container">
        <a href="index.php" class="logo">Monalisa Resto</a>

        <nav class="nav-links" id="nav-links">
            <a href="index.php" class="<?= $halaman_sekarang === 'index.php' ? 'active' : '' ?>">Beranda</a>
            <a href="katalog.php" class="<?= in_array($halaman_sekarang, ['katalog.php', 'detail.php']) ? 'active' : '' ?>">Menu</a>
            <a href="galeri.php" class="<?= $halaman_sekarang === 'galeri.php' ? 'active' : '' ?>">Galeri</a>
            <a href="kontak.php" class="<?= $halaman_sekarang === 'kontak.php' ? 'active' : '' ?>">Kontak & Lokasi</a>
            <a href="tentang.php" class="<?= $halaman_sekarang === 'tentang.php' ? 'active' : '' ?>">Tentang</a>
        </nav>

        <button class="hamburger" id="hamburger" aria-label="Toggle Menu">&#9776;</button>
    </div>
</header>
<div class="nav-overlay" id="nav-overlay"></div>
