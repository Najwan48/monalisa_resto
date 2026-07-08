<?php
require_once 'includes/header.php';

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'tentang_kami'");
$stmt->execute();
$konten = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<main>

<div class="page-header">
    <div class="parallax-shape parallax-shape-ring parallax-element reveal" data-speed="0.28" style="top: 10%; left: 7%;"></div>
    <div class="parallax-shape parallax-shape-dot parallax-element reveal" data-speed="0.2" style="bottom: 22%; right: 6%;"></div>
    <div class="parallax-shape parallax-shape-line parallax-element reveal" data-speed="0.25" style="top: 55%; right: 4%;"></div>
    <div class="container parallax-element" data-speed="0.15">
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
            <div class="reveal reveal-left parallax-element" data-speed="0.15">
                <span class="eyebrow">Sejarah Kami</span>
                <h2 class="section-title">Dedikasi Pada<br>Tradisi</h2>
                <div class="divider-left"></div>
                <div style="line-height: 1.8; font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem;" data-konten="sejarah">
                    <?= nl2br(escapeHtml($konten['sejarah'] ?? 'Sejak tahun 1972, Restoran Monalisa telah menjadi ikon kuliner di Jalan Raya Tajur, Bogor.')) ?>
                </div>
            </div>
            <div class="reveal reveal-right delay-2">
                <div class="art-image-wrapper">
                    <img src="assets/images/monalisa-art.webp" alt="Monalisa Art Piece"
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
                <img src="assets/images/galeri/IMG_4224.webp" alt="Monalisa Resto Interior"
                     style="width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
            </div>
            <div class="reveal reveal-up parallax-element" data-speed="0.15">
                <span class="eyebrow">Visi Kami</span>
                <h2 class="section-title">Membawa Kehangatan<br>ke Meja Anda</h2>
                <div class="divider-left"></div>
                <p style="font-size: 1.4rem; font-style: italic; line-height: 1.6; color: var(--primary); font-family: var(--font-display);" data-konten="visi">
                    "<?= escapeHtml($konten['visi'] ?? 'Monalisa hadir untuk membawa kehangatan rumah ke meja makan Anda melalui resep legendaris yang terjaga.') ?>"
                </p>
            </div>
        </div>
    </div>
</section>

</main>

<?php require_once 'includes/footer.php'; ?>
<script>
initRealtimePolling('/monalisa_resto/api_konten.php?halaman=tentang_kami', 30000, function(data) {
    var sejarahEl = document.querySelector('[data-konten="sejarah"]');
    if (sejarahEl && data.sejarah) sejarahEl.innerHTML = data.sejarah.replace(/\n/g, '<br>');
    var visiEl = document.querySelector('[data-konten="visi"]');
    if (visiEl && data.visi) visiEl.textContent = '"' + data.visi + '"';
});
</script>
