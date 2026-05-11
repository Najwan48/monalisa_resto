<?php
require_once 'includes/header.php';

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak'");
$stmt->execute();
$kontak_data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$csrf_token = generate_csrf_token();
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $nama  = h($_POST['nama'] ?? '');
        $email = h($_POST['email'] ?? '');
        $pesan = h($_POST['pesan'] ?? '');

        if (!empty($nama) && !empty($email) && !empty($pesan)) {
            $pesan_sukses = "Terima kasih, $nama. Pesan Anda telah kami terima dan akan segera kami proses.";
        }
    } else {
        $pesan_sukses = "Validasi form gagal. Silakan coba lagi.";
    }
}
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div style="background-color: var(--bg-dark); color: var(--white); padding: 4rem 0; text-align: center;">
    <h1 class="section-title" style="color: var(--secondary-color);">Hubungi Kami</h1>
    <p>Kami senang mendengar dari Anda.</p>
</div>

<section class="section">
    <div class="container">
        <?php if($pesan_sukses): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: var(--radius); margin-bottom: 2rem; border: 1px solid #c3e6cb;">
            <?= $pesan_sukses ?>
        </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 4rem;">
            <div>
                <h2 style="margin-bottom: 2rem; color: var(--primary-color);">Informasi Kontak</h2>
                <div style="display: flex; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 1rem; flex-shrink: 0;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 0.25rem;">Alamat</h4>
                        <p><a href="https://maps.app.goo.gl/J5wMuZqK8dnd5nu19" target="_blank" style="color: inherit; text-decoration: none;"><?= h($kontak_data['alamat'] ?? 'Jl. Raya Tajur No. 30, Kota Bogor') ?></a></p>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 1rem; flex-shrink: 0;">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 0.25rem;">Telepon</h4>
                        <p><?= h($kontak_data['telepon'] ?? '0812-3456-7890') ?></p>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 1rem; flex-shrink: 0;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 0.25rem;">Jam Operasional</h4>
                        <p><?= h($kontak_data['jam_operasional'] ?? 'Setiap Hari: 07.00 - 22.00 WIB') ?></p>
                    </div>
                </div>
                <div id="map" style="margin-top: 2rem; width: 100%; height: 300px; border-radius: var(--radius); border: 1px solid #ddd; z-index: 1;"></div>
            </div>

            <div style="background-color: var(--white); padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                <h2 style="margin-bottom: 2rem; color: var(--primary-color);">Kirim Pesan</h2>
                <form action="kontak.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <div style="margin-bottom: 1rem;">
                        <label for="nama" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Email</label>
                        <input type="email" id="email" name="email" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label for="pesan" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Pesan</label>
                        <textarea id="pesan" name="pesan" rows="5" required style="width: 100%; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
    const lat = -6.6263927;
    const lng = 106.8214916;

    const map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng]).addTo(map);
    
    marker.bindPopup('<b>Monalisa Restaurant</b><br>Jl. Raya Tajur No.30, Bogor.<br><a href="https://maps.app.goo.gl/J5wMuZqK8dnd5nu19" target="_blank" style="color: var(--primary-color); text-decoration: underline;">Petunjuk Arah</a>').openPopup();

    marker.on('click', function() {
        window.open('https://maps.app.goo.gl/J5wMuZqK8dnd5nu19', '_blank');
    });
    
    marker.getElement().style.cursor = 'pointer';
</script>
