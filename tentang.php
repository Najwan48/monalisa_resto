<?php
require_once 'includes/header.php';

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'tentang_kami'");
$stmt->execute();
$konten = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<main>

<div class="page-header">
    <div class="container">
        <span class="eyebrow reveal reveal-up">Warisan Budaya</span>
        <h1 class="section-title reveal reveal-up delay-1">Cerita Monalisa</h1>
        <div class="divider reveal reveal-up delay-2"></div>
        <p class="reveal reveal-up delay-3" style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
            Perjalanan panjang melintasi generasi, menjaga cita rasa autentik di jantung Kota Bogor.
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="art-content-section">
            <div class="reveal reveal-left">
                <span class="eyebrow">Sejarah Kami</span>
                <h2 class="section-title">Dedikasi Pada<br>Tradisi</h2>
                <div class="divider-left"></div>
                <div style="line-height: 1.8; font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem;">
                    <?= nl2br(h($konten['sejarah'] ?? 'Sejak tahun 1972, Restoran Monalisa telah menjadi ikon kuliner di Jalan Raya Tajur, Bogor.')) ?>
                </div>
            </div>
            <div class="reveal reveal-right delay-2">
                <div class="art-image-wrapper">
                    <img src="assets/images/monalisa-art.jpg" alt="Monalisa Art Piece" 
                         style="width: 100%; border-radius: var(--radius-lg);">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background-color: var(--surface);">
    <div class="container">
        <div class="split-section">
            <div class="reveal reveal-scale">
                <img src="assets/images/galeri/IMG_4224.jpg" alt="Monalisa Resto Interior" 
                     style="width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
            </div>
            <div class="reveal reveal-up">
                <span class="eyebrow">Visi Kami</span>
                <h2 class="section-title">Membawa Kehangatan<br>ke Meja Anda</h2>
                <div class="divider-left"></div>
                <p style="font-size: 1.4rem; font-style: italic; line-height: 1.6; color: var(--primary); font-family: var(--font-display);">
                    "<?= h($konten['visi'] ?? 'Monalisa hadir untuk membawa kehangatan rumah ke meja makan Anda melalui resep legendaris yang terjaga.') ?>"
                </p>
            </div>
        </div>
    </div>
</section>

</main>

<?php require_once 'includes/footer.php'; ?>
