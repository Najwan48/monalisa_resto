<?php
// detail.php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ? AND status = 'aktif'");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if (!$menu) {
    echo "<div class='container section text-center'><h2>Menu tidak ditemukan.</h2><a href='katalog.php' class='btn btn-primary'>Kembali ke Katalog</a></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div style="background-color: var(--bg-dark); padding: 2rem 0;">
    <div class="container">
        <a href="katalog.php" style="color: var(--secondary-color);">&larr; Kembali ke Katalog Menu</a>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; align-items: start;">
            
            <!-- Foto Menu -->
            <div>
                <img src="<?= h($menu['foto_url']) ?>" alt="<?= h($menu['nama_menu']) ?>" style="width: 100%; border-radius: var(--radius); box-shadow: var(--shadow);" onerror="this.src='https://via.placeholder.com/600x400?text=Foto+Menu'">
            </div>
            
            <!-- Detail Info -->
            <div>
                <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?= h($menu['nama_menu']) ?></h1>
                <p style="color: #666; font-size: 1.1rem; margin-bottom: 1.5rem;"><i class="fas fa-map-marker-alt"></i> Khas: <?= h($menu['asal_daerah']) ?> | Kategori: <?= h($menu['kategori']) ?></p>
                
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 2rem;">
                    <?= format_rupiah($menu['harga']) ?>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <h3 style="border-bottom: 2px solid var(--secondary-color); display: inline-block; margin-bottom: 1rem;">Deskripsi</h3>
                    <p style="line-height: 1.8;"><?= nl2br(h($menu['deskripsi_lengkap'])) ?></p>
                </div>
                
                <div style="margin-bottom: 2rem; background-color: var(--bg-light); padding: 1.5rem; border-radius: var(--radius);">
                    <h3 style="margin-bottom: 1rem;">Komposisi Utama</h3>
                    <p><?= h($menu['bahan_utama']) ?></p>
                </div>
                
                <?php if(!empty($menu['info_alergen']) && $menu['info_alergen'] !== 'Tidak ada'): ?>
                <div style="margin-bottom: 2rem; border-left: 4px solid #EE2737; padding-left: 1rem;">
                    <h4 style="color: #EE2737; margin-bottom: 0.5rem;">Informasi Alergen</h4>
                    <p><?= h($menu['info_alergen']) ?></p>
                </div>
                <?php endif; ?>
                
            </div>
            
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
