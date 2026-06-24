<?php
require_once 'includes/header.php';

$stmt = $pdo->query("SELECT judul, foto_url FROM galeri ORDER BY urutan ASC");
$galeri = $stmt->fetchAll();
?>

<main>

<div class="page-header">
    <div class="parallax-shape parallax-shape-ring parallax-element reveal" data-speed="0.25" style="top: 12%; left: 6%;"></div>
    <div class="parallax-shape parallax-shape-line parallax-element reveal" data-speed="0.2" style="bottom: 30%; right: 5%;"></div>
    <div class="parallax-shape parallax-shape-dot parallax-element reveal" data-speed="0.3" style="top: 45%; right: 10%;"></div>
    <div class="container parallax-element" data-speed="0.15">
        <span class="eyebrow reveal reveal-up">Eksplorasi Visual</span>
        <h1 class="section-title reveal reveal-up delay-1">Galeri Monalisa</h1>
        <div class="divider reveal reveal-up delay-2"></div>
        <p class="reveal reveal-up delay-3" style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
            Melihat lebih dekat kehangatan suasana dan detail autentik yang kami hadirkan di setiap sudut Monalisa Resto.
        </p>
    </div>
</div>

<!-- Monalisa Art Section -->
<section class="section" style="background: var(--surface);">
    <div class="container">
        <div class="art-content-section">
            <div class="reveal reveal-left parallax-element" data-speed="0.15">
                <span class="eyebrow">Monalisa Art</span>
                <h2 class="section-title">Sentuhan Klasik<br>Penuh Cerita</h2>
                <div class="divider-left"></div>
                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                    Sesuai dengan namanya, Monalisa Resto menghadirkan atmosfer yang terinspirasi oleh keindahan seni klasik. Lukisan ikonik Monalisa yang terpasang anggun di ruang utama bukan sekadar dekorasi, melainkan simbol dedikasi kami terhadap keindahan dan keabadian rasa.
                </p>
                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8;">
                    Setiap elemen dekorasi dipilih dengan teliti untuk menciptakan harmoni antara sejarah, seni, dan kenyamanan kuliner bagi setiap tamu yang datang.
                </p>
            </div>
            <div class="art-image-wrapper reveal reveal-right delay-2">
                <img src="assets/images/monalisa-art.webp" alt="Monalisa Art Painting" style="width: 100%; border-radius: var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--bg);">
    <div class="container">
        <?php if(empty($galeri)): ?>
            <div style="text-align: center; padding: 5rem 0;">
                <p style="color: var(--text-muted); font-style: italic;">Belum ada foto di galeri.</p>
            </div>
        <?php else: ?>
            <div class="gallery-grid">
                <?php foreach($galeri as $index => $item): ?>
                <div class="gallery-item reveal reveal-up delay-<?= ($index % 3) + 1 ?>" 
                     onclick="openLightbox('<?= escapeHtml($item['foto_url']) ?>', '<?= escapeHtml($item['judul']) ?>')">
                    
                    <img src="<?= escapeHtml($item['foto_url']) ?>" alt="<?= escapeHtml($item['judul']) ?>" loading="lazy" decoding="async">
                    
                    <div class="gallery-overlay">
                        <div class="gallery-caption">
                            <h3 style="font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 0.5rem;"><?= escapeHtml($item['judul']) ?></h3>
                            <div style="width: 40px; height: 1px; background: var(--primary);"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

</main>

<div id="lightbox" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(10,10,10,0.98); backdrop-filter: blur(20px); align-items: center; justify-content: center; flex-direction: column; padding: 2rem;">
    <button onclick="closeLightbox()" style="position: absolute; top: 2rem; right: 2rem; background: none; border: none; color: #fff; font-size: 2.5rem; cursor: pointer; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; opacity: 0.8; transition: opacity 0.3s;">&times;</button>
    
    <div style="max-width: 85vw; max-height: 75vh; position: relative;">
        <img id="lightbox-img" src="" alt="" style="display: block; width: auto; height: auto; max-width: 100%; max-height: 75vh; object-fit: contain;">
    </div>
    
    <div id="lightbox-caption" style="color: #fff; margin-top: 2.5rem; font-size: 1.4rem; font-family: var(--font-display); letter-spacing: 0.05em; text-align: center; max-width: 600px;"></div>
</div>

<script>
    function openLightbox(src, caption) {
        const lb = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        const cap = document.getElementById('lightbox-caption');
        
        img.src = src;
        cap.innerText = caption;
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>

<?php require_once 'includes/footer.php'; ?>
