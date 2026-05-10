<?php
// tentang.php
require_once 'includes/header.php';

// Ambil data konten tentang kami
$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'tentang_kami'");
$stmt->execute();
$konten = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/tentang-bg.jpg') center/cover; color: var(--white); padding: 6rem 0; text-align: center;">
    <h1 class="section-title" style="color: var(--white); border-bottom-color: var(--secondary-color);">Tentang Kami</h1>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem; align-items: center;">
            
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 2rem;">Kisah Monalisa</h2>
                <div style="line-height: 1.8; font-size: 1.1rem;">
                    <?= nl2br(h($konten['sejarah'] ?? '[CONTOH: Berdiri sejak tahun 2010, Restaurant Monalisa lahir dari kecintaan kami terhadap kekayaan rempah Nusantara, khususnya masakan Jawa Tengah. Berawal dari resep warisan keluarga yang dijaga turun-temurun, kami berkomitmen untuk menyajikan hidangan autentik dengan kualitas bahan terbaik.]')) ?>
                </div>
            </div>
            
            <div>
                <img src="assets/images/tentang-img.jpg" alt="Restaurant Monalisa Interior" style="width: 100%; border-radius: var(--radius); box-shadow: var(--shadow);" onerror="this.src='https://via.placeholder.com/600x400?text=Foto+Restoran'">
            </div>
            
        </div>
    </div>
</section>

<section class="section" style="background-color: var(--bg-dark); color: var(--white);">
    <div class="container text-center">
        <h2 class="section-title" style="color: var(--secondary-color);">Visi Kami</h2>
        <p style="font-size: 1.5rem; max-width: 800px; margin: 0 auto; font-style: italic; line-height: 1.6;">
            "<?= h($konten['visi'] ?? '[CONTOH: Menjadi restoran pilihan utama keluarga untuk menikmati kuliner tradisional dengan layanan bintang lima.]') ?>"
        </p>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
