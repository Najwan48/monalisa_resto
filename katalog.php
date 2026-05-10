<?php
// katalog.php
require_once 'includes/header.php';

// Ambil kategori untuk filter
$stmt_cat = $pdo->query("SELECT DISTINCT kategori FROM menu WHERE status = 'aktif'");
$categories = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);

// Filter
$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';

// Query Menu
$query = "SELECT id, nama_menu, asal_daerah, deskripsi_singkat, harga, kategori, foto_url FROM menu WHERE status = 'aktif'";
$params = [];
if ($kategori_aktif !== 'Semua') {
    $query .= " AND kategori = ?";
    $params[] = $kategori_aktif;
}
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$menus = $stmt->fetchAll();
?>

<div style="background-color: var(--bg-dark); color: var(--white); padding: 4rem 0; text-align: center;">
    <h1 class="section-title" style="color: var(--secondary-color);">Katalog Menu</h1>
    <p>Eksplorasi hidangan otentik kami.</p>
</div>

<section class="section">
    <div class="container">
        
        <!-- Filter Kategori -->
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
            <a href="katalog.php" class="btn <?= ($kategori_aktif === 'Semua') ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 6px 16px; font-size: 0.9rem;">Semua</a>
            <?php foreach($categories as $cat): ?>
                <a href="katalog.php?kategori=<?= urlencode($cat) ?>" class="btn <?= ($kategori_aktif === $cat) ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 6px 16px; font-size: 0.9rem;"><?= h($cat) ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Grid Menu -->
        <?php if(empty($menus)): ?>
            <p class="text-center">Belum ada menu di kategori ini.</p>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <?php foreach($menus as $menu): ?>
                <div class="menu-card">
                    <img src="<?= h($menu['foto_url']) ?>" alt="<?= h($menu['nama_menu']) ?>" style="width: 100%; height: 250px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x250?text=Foto+Menu'">
                    <div style="padding: 1.5rem;">
                        <span style="display: inline-block; padding: 4px 8px; background-color: var(--bg-light); color: var(--primary-color); border-radius: 4px; font-size: 0.75rem; margin-bottom: 0.5rem; font-weight: bold;"><?= h($menu['kategori']) ?></span>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <h3 style="margin-bottom: 0; font-size: 1.25rem;"><?= h($menu['nama_menu']) ?></h3>
                        </div>
                        <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;"><i class="fas fa-map-marker-alt"></i> <?= h($menu['asal_daerah']) ?></p>
                        <p style="font-size: 0.9rem; margin-bottom: 1rem;"><?= h($menu['deskripsi_singkat']) ?></p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: bold; color: var(--primary-color); font-size: 1.2rem;"><?= format_rupiah($menu['harga']) ?></span>
                            <a href="detail.php?id=<?= $menu['id'] ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.9rem;">Detail</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
