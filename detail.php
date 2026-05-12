<?php
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

<main style="padding-top: 7rem;">
    <div style="background: var(--bg-warm); border-bottom: 1px solid var(--border); padding: 1.25rem 0; margin-bottom: 2rem;">
        <div class="container">
            <a href="katalog.php" class="btn-ghost" style="font-size: 0.75rem;">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog Menu
            </a>
        </div>
    </div>

    <section class="section" style="padding-top: 1rem;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 4rem; align-items: start;">
                <div class="reveal reveal-up">
                    <div class="parallax-wrap" style="border-radius: var(--radius); box-shadow: var(--shadow-lg); height: 500px;">
                        <img src="<?= h($menu['foto_url']) ?>" alt="<?= h($menu['nama_menu']) ?>" 
                             class="parallax-img"
                             onerror="this.src='https://via.placeholder.com/600x600?text=Foto+Menu'">
                    </div>
                </div>
                <div class="reveal reveal-up delay-2">
                    <span class="eyebrow"><?= h($menu['kategori']) ?></span>
                    <h1 class="section-title" style="margin-bottom: 1rem;"><?= h($menu['nama_menu']) ?></h1>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Khas: <?= h($menu['asal_daerah']) ?>
                    </p>
                    
                    <div style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); font-weight: 600; margin-bottom: 3rem; line-height: 1;">
                        <?= format_rupiah($menu['harga']) ?>
                    </div>
                    
                    <div class="reveal reveal-up delay-3" style="margin-bottom: 3rem;">
                        <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; font-family: var(--font-body); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted);">Deskripsi</h3>
                        <p style="line-height: 1.9; color: var(--text); font-size: 1.05rem;"><?= nl2br(h($menu['deskripsi_lengkap'])) ?></p>
                    </div>

                    <div class="reveal reveal-up delay-4" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                        <div style="background: var(--surface); padding: 1.75rem; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                            <h4 style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary); margin-bottom: 0.75rem;">Komposisi Utama</h4>
                            <p style="font-size: 0.95rem;"><?= h($menu['bahan_utama']) ?></p>
                        </div>
                        
                        <?php if(!empty($menu['info_alergen']) && $menu['info_alergen'] !== 'Tidak ada'): ?>
                        <div style="background: #FFF5F5; padding: 1.75rem; border: 1px solid #FED7D7; border-radius: var(--radius-sm);">
                            <h4 style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #C53030; margin-bottom: 0.75rem;">Informasi Alergen</h4>
                            <p style="font-size: 0.95rem; color: #742A2A;"><?= h($menu['info_alergen']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="reveal reveal-up delay-5" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <a href="index.php#order" class="btn btn-primary" style="width: 100%;">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
