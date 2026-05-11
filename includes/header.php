<?php
require_once 'db.php';
require_once 'functions.php';

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak'");
$stmt->execute();
$kontak = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monalisa Resto</title>
    <meta name="description" content="Monalisa Resto menyajikan hidangan autentik khas Jawa Tengah di jantung Kota Bogor.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <div class="nav-container container">
        <a href="index.php" class="logo">Monalisa Resto</a>
        
        <nav class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="tentang.php">Tentang Kami</a>
            <a href="katalog.php">Menu</a>
            <a href="galeri.php">Galeri</a>
            <a href="kontak.php">Kontak</a>
            <a href="index.php#order" class="btn btn-primary" style="padding: 8px 16px;">Pesan Sekarang</a>
        </nav>
        
        <div class="hamburger">
            &#9776;
        </div>
    </div>
</header>
