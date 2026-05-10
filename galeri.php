<?php
// galeri.php
require_once 'includes/header.php';

// Ambil data galeri
$stmt = $pdo->query("SELECT judul, foto_url FROM galeri ORDER BY urutan ASC, created_at DESC");
$galeri = $stmt->fetchAll();
?>

<div style="background-color: var(--bg-dark); color: var(--white); padding: 4rem 0; text-align: center;">
    <h1 class="section-title" style="color: var(--secondary-color);">Galeri Kami</h1>
    <p>Suasana hangat dan hidangan menggugah selera di Restaurant Monalisa.</p>
</div>

<section class="section">
    <div class="container">
        
        <?php if(empty($galeri)): ?>
            <p class="text-center">Belum ada foto di galeri.</p>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                <?php foreach($galeri as $item): ?>
                <div style="position: relative; overflow: hidden; border-radius: var(--radius); cursor: pointer; aspect-ratio: 4/3;" class="gallery-item" onclick="openLightbox('<?= h($item['foto_url']) ?>', '<?= h($item['judul']) ?>')">
                    <img src="<?= h($item['foto_url']) ?>" alt="<?= h($item['judul']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='https://via.placeholder.com/400x300?text=Foto+Galeri'">
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 1rem; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; transform: translateY(100%); transition: transform 0.3s ease;" class="gallery-caption">
                        <h3 style="margin: 0; font-size: 1.1rem;"><?= h($item['judul']) ?></h3>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- Lightbox Modal (Vanilla JS) -->
<div id="lightbox" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); align-items: center; justify-content: center; flex-direction: column;">
    <span onclick="closeLightbox()" style="position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; font-weight: bold; cursor: pointer;">&times;</span>
    <img id="lightbox-img" src="" alt="" style="max-width: 90%; max-height: 80vh; object-fit: contain;">
    <div id="lightbox-caption" style="color: white; margin-top: 20px; font-size: 1.2rem; font-family: var(--font-heading);"></div>
</div>

<style>
    .gallery-item:hover .gallery-caption { transform: translateY(0); }
</style>

<script>
    function openLightbox(src, caption) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox-caption').innerText = caption;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
</script>

<?php require_once 'includes/footer.php'; ?>
