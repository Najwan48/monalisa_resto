<?php
require_once 'includes/header.php';

$stmt_beranda = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'beranda'");
$stmt_beranda->execute();
$konten = $stmt_beranda->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt_menu = $pdo->prepare("SELECT id, nama_menu, asal_daerah, deskripsi_singkat, harga, foto_url FROM menu WHERE status = 'aktif' ORDER BY RAND() LIMIT 3");
$stmt_menu->execute();
$menus = $stmt_menu->fetchAll();
?>

<section class="hero">
    <div class="hero-content container">
        <h1><?= h($konten['tagline'] ?? 'Kekayaan Kuliner Jawa Tengah di Jantung Kota Bogor') ?></h1>
        <p><?= h($konten['pengantar'] ?? 'Selamat datang di Monalisa Resto.') ?></p>
        <div class="hero-buttons">
            <a href="katalog.php" class="btn btn-primary">Lihat Menu</a>
            <a href="#order" class="btn btn-secondary">Order Online</a>
        </div>
    </div>
</section>

<section class="section" style="background-color: var(--white);">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">Menu Andalan Kami</h2>
            <p style="margin-bottom: 2rem;">Sajian otentik yang wajib Anda coba.</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <?php foreach($menus as $menu): ?>
            <div class="menu-card">
                <img src="<?= h($menu['foto_url']) ?>" alt="<?= h($menu['nama_menu']) ?>" style="width: 100%; height: 250px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x250?text=Foto+Menu'">
                <div style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h3 style="margin-bottom: 0; font-size: 1.25rem;"><?= h($menu['nama_menu']) ?></h3>
                        <span style="font-weight: bold; color: var(--primary-color);"><?= format_rupiah($menu['harga']) ?></span>
                    </div>
                    <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;"><i class="fas fa-map-marker-alt"></i> <?= h($menu['asal_daerah']) ?></p>
                    <p style="font-size: 0.9rem; margin-bottom: 1rem;"><?= h($menu['deskripsi_singkat']) ?></p>
                    <a href="detail.php?id=<?= $menu['id'] ?>" class="btn btn-secondary" style="display: block; padding: 8px;">Detail Menu</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center" style="margin-top: 3rem;">
            <a href="katalog.php" class="btn btn-primary">Lihat Seluruh Menu</a>
        </div>
    </div>
</section>

<section id="order" class="section" style="background-color: var(--bg-dark); color: var(--white); text-align: center;">
    <div class="container">
        <h2 class="section-title" style="color: var(--secondary-color);">Nikmati di Rumah Anda</h2>
        <p style="margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">Pesan hidangan favorit Anda melalui platform partner kami untuk pengiriman langsung ke depan pintu Anda.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="https://gofood.link/a/BMMv8Pb" class="btn btn-gofood" target="_blank" rel="noopener noreferrer">
                Pesan via GoFood
            </a>
            <a href="https://r.grab.com/g/6-20260510_203031_8a7e66d9e9694765be4c04cda49c0859_MEXMPS-6-C2XANPEKCVDGNT" class="btn btn-grabfood" target="_blank" rel="noopener noreferrer">
                Pesan via GrabFood
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
