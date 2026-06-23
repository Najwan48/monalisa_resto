<?php
$page_title = 'Beranda';
require_once 'includes/header.php';

$stmt_beranda = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'beranda'");
$stmt_beranda->execute();
$konten = $stmt_beranda->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt_menu = $pdo->prepare("SELECT id, nama_menu, asal_daerah, deskripsi_singkat, harga, foto_url, kategori FROM menu WHERE status = 'aktif' ORDER BY RAND() LIMIT 5");
$stmt_menu->execute();
$menus = $stmt_menu->fetchAll();
?>

<main>

<section class="hero" aria-label="Hero Section">
    <div class="hero-text-side">
        <div class="reveal reveal-up">
            <span class="eyebrow" style="letter-spacing: 0.3em; opacity: 0.8;">EST. 1998 — BOGOR</span>
        </div>
        <h1 class="hero-heading reveal reveal-up delay-1">
            <?php
            $tagline = $konten['tagline'] ?? 'Kekayaan Kuliner Jawa Tengah';
            $tagline_words = explode(' ', $tagline);
            $first_word = array_shift($tagline_words);
            $rest_tagline = implode(' ', $tagline_words);
            ?>
            <span style="display: block; font-style: italic; color: var(--primary); font-family: 'Cormorant Garamond', serif; font-size: 0.8em; margin-bottom: -0.2em;"><?= h($first_word) ?></span>
            <?= h($rest_tagline) ?>
        </h1>
        <p class="hero-sub reveal reveal-up delay-2">
            <?= h($konten['pengantar'] ?? 'Menghidupkan kembali cita rasa otentik yang telah disempurnakan selama lebih dari dua dekade.') ?>
        </p>
        <div class="hero-actions reveal reveal-up delay-3">
            <a href="katalog.php" class="btn btn-primary">Jelajahi Menu</a>
            <a href="tentang.php" class="btn-ghost" style="text-decoration: none; font-weight: 700; border-bottom: 1px solid var(--primary);">
                Cerita Kami
            </a>
        </div>
    </div>

    <div class="hero-image-side">
        <div class="hero-image-wrapper reveal reveal-scale delay-2">
            <img src="assets/images/IMG_4339.webp"
                 alt="Monalisa Resto"
                 onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80'">
            <div class="hero-image-overlay"></div>
        </div>
        
        <div class="hero-badge reveal reveal-up delay-4">
            <div class="badge-number">25</div>
            <div class="badge-label">Tahun Warisan</div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--surface);">
    <div class="container">
        <div class="art-content-section">
            <div class="reveal reveal-left">
                <span class="eyebrow">Warisan & Seni</span>
                <h2 class="section-title">Harmoni Rasa<br>dan Estetika</h2>
                <div class="divider-left"></div>
                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                    Di Monalisa Resto, kami percaya bahwa pengalaman kuliner terbaik lahir dari harmoni antara hidangan yang lezat dan suasana yang menginspirasi. Lukisan ikonik "Monalisa Art" di ruang kami menjadi saksi perjalanan rasa yang kami tawarkan.
                </p>
                <a href="tentang.php" class="btn btn-outline">Pelajari Sejarah Kami</a>
            </div>
            <div class="art-image-wrapper reveal reveal-right delay-2">
                <img src="assets/images/monalisa-art.webp" alt="Monalisa Art at Resto" style="width: 100%; border-radius: var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--bg-warm);" aria-label="Signature Dishes">
    <div class="container">
        <div class="signature-grid">
            <div style="position: sticky; top: 8rem;" class="reveal reveal-up revealed sticky-sidebar">
                <span class="eyebrow">Pilihan Terbaik</span>
                <h2 class="section-title">Menu<br>Andalan</h2>
                <div class="divider-left"></div>
                <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 2.5rem;">Sajian otentik yang wajib Anda coba, disiapkan dengan bahan-bahan pilihan dan cinta yang tulus.</p>
                <a href="katalog.php" class="btn btn-outline">Semua Menu</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 2.5rem;">
                <?php foreach($menus as $index => $menu): ?>
                <div class="signature-item reveal reveal-up delay-<?= $index + 1 ?> revealed parallax-element" data-speed="0.2" style="<?= $index === count($menus) - 1 ? 'border-bottom: none; padding-bottom: 0;' : '' ?>">
                    <div class="menu-card-img" style="height: clamp(160px, 20vw, 240px);">
                        <img src="<?= h($menu['foto_url']) ?>"
                             alt="<?= h($menu['nama_menu']) ?>"
                             onerror="this.src='https://via.placeholder.com/280x200?text=Foto+Menu'">
                    </div>
                    <div>
                        <span class="menu-card-category"><?= h($menu['kategori'] ?? $menu['asal_daerah']) ?></span>
                        <h3 style="font-size: 1.75rem; margin-bottom: 0.75rem;"><?= h($menu['nama_menu']) ?></h3>
                        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem;"><?= h($menu['deskripsi_singkat']) ?></p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--primary); font-weight: 600;"><?= format_rupiah($menu['harga']) ?></span>
                            <a href="detail.php?id=<?= $menu['id'] ?>" class="btn-ghost">
                                Lihat Detail <i class="fas fa-arrow-right" style="font-size: 0.65rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--surface);" aria-label="Value Propositions">
    <div class="container">
        <div class="props-grid reveal reveal-scale">
            <?php
            $props = [
                ['fas fa-leaf', 'Bahan Segar', 'Kami memilih bahan-bahan segar berkualitas tinggi setiap harinya dari supplier terpercaya.'],
                ['fas fa-heart', 'Resep Warisan', 'Setiap hidangan dibuat berdasarkan resep keluarga yang telah diwariskan selama generasi.'],
                ['fas fa-star', 'Pengalaman Premium', 'Lebih dari sekedar makan — kami menghadirkan pengalaman kuliner yang tak terlupakan.'],
            ];
            foreach($props as $i => $prop): ?>
            <div class="prop-item">
                <div style="width: 56px; height: 56px; background: var(--primary-pale); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary);">
                    <i class="<?= $prop[0] ?>"></i>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem;"><?= $prop[1] ?></h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.7;"><?= $prop[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="order" class="section" style="background: var(--charcoal);" aria-label="Online Order">
    <div class="container" style="text-align: center; max-width: 700px;">
        <span class="eyebrow reveal reveal-up" style="color: var(--primary-light);">Pesan Sekarang</span>
        <h2 class="section-title reveal reveal-up delay-1" style="color: #fff;">Nikmati di Rumah Anda</h2>
        <div class="divider reveal reveal-up delay-2"></div>
        <p class="reveal reveal-up delay-3" style="color: rgba(255,255,255,0.55); line-height: 1.85; margin-bottom: 3.5rem;">Hidangan favorit Anda kini bisa dinikmati di rumah. Pesan melalui platform partner terpercaya kami.</p>
        <div class="order-actions reveal reveal-up delay-4">
            <?php if (!empty($konten['link_gofood'])): ?>
            <a href="<?= h($konten['link_gofood']) ?>" class="btn btn-gofood" target="_blank" rel="noopener noreferrer">
                <img src="assets/images/logo gofood.webp" alt="GoFood" style="height: 20px;">
                Pesan via GoFood
            </a>
            <?php endif; ?>
            <?php if (!empty($konten['link_grabfood'])): ?>
            <a href="<?= h($konten['link_grabfood']) ?>" class="btn btn-grabfood" target="_blank" rel="noopener noreferrer">
                <img src="assets/images/logo grabfood.webp" alt="GrabFood" style="height: 20px;">
                Pesan via GrabFood
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

</main>

<?php require_once 'includes/footer.php'; ?>
