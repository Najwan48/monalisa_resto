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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= isset($page_title) ? escapeHtml($page_title) . ' — ' : '' ?>Monalisa Resto · Authentic Javanese Cuisine</title>
    <meta name="description" content="Monalisa Resto menyajikan kelezatan otentik khas Jawa Tengah di jantung Kota Bogor. Nikmati hidangan legendaris dalam suasana yang nyaman dan penuh seni.">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <?php if ($halaman_sekarang === 'galeri.php'): ?>
    <link rel="preload" as="image" href="assets/images/galeri/IMG_4222.webp">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
    <script src="assets/js/main.js?v=2" defer></script>
    <script>
        window.addEventListener('pageshow', function(e) {
            if (e.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</head>
<body>

<?php $cv = time(); ?>

<header id="main-header">
    <div class="nav-container">
        <a href="index.php?cv=<?= $cv ?>" class="logo">Monalisa Resto</a>

        <nav class="nav-links" id="nav-links">
            <a href="index.php?cv=<?= $cv ?>" class="<?= $halaman_sekarang === 'index.php' ? 'active' : '' ?>">Beranda</a>
            <a href="katalog.php?cv=<?= $cv ?>" class="<?= in_array($halaman_sekarang, ['katalog.php', 'detail.php']) ? 'active' : '' ?>">Menu</a>
            <a href="galeri.php?cv=<?= $cv ?>" class="<?= $halaman_sekarang === 'galeri.php' ? 'active' : '' ?>">Galeri</a>
            <a href="kontak.php?cv=<?= $cv ?>" class="<?= $halaman_sekarang === 'kontak.php' ? 'active' : '' ?>">Kontak & Lokasi</a>
            <a href="tentang.php?cv=<?= $cv ?>" class="<?= $halaman_sekarang === 'tentang.php' ? 'active' : '' ?>">Tentang</a>
        </nav>

        <button class="hamburger" id="hamburger" aria-label="Toggle Menu" aria-expanded="false" aria-controls="nav-links">
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
    </div>
</header>
<div class="nav-overlay" id="nav-overlay"></div>
