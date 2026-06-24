<?php
require_once 'includes/header.php';

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak'");
$stmt->execute();
$kontak_data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<main>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="page-header">
    <div class="parallax-shape parallax-shape-ring parallax-element reveal" data-speed="0.22" style="top: 18%; right: 7%;"></div>
    <div class="parallax-shape parallax-shape-dot parallax-element reveal" data-speed="0.28" style="bottom: 20%; left: 4%;"></div>
    <div class="parallax-shape parallax-shape-line parallax-element reveal" data-speed="0.2" style="top: 40%; left: 6%;"></div>
    <div class="container parallax-element" data-speed="0.15">
        <span class="eyebrow reveal reveal-up">Hubungan Pelanggan</span>
        <h1 class="section-title reveal reveal-up delay-1">Terhubung dengan Kami</h1>
        <div class="divider reveal reveal-up delay-2"></div>
        <p class="reveal reveal-up delay-3" style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
            Kami selalu siap melayani Anda. Kunjungi kami untuk pengalaman kuliner autentik yang tak terlupakan.
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <h2 class="section-title reveal reveal-up" style="font-size: 2.2rem; margin-bottom: 3rem;">Kontak & Lokasi</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem;">
                    <div class="reveal reveal-up delay-1">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">Alamat</span>
                        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--charcoal);">
                            <a href="https://maps.app.goo.gl/J5wMuZqK8dnd5nu19" target="_blank"><?= escapeHtml($kontak_data['alamat'] ?? 'Jl. Raya Tajur No. 30, Kota Bogor') ?></a>
                        </p>
                    </div>

                    <div class="reveal reveal-up delay-2">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">Jam Operasional</span>
                        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--charcoal);"><?= escapeHtml($kontak_data['jam_operasional'] ?? 'Setiap Hari: 08.00 - 21.00 WIB') ?></p>
                    </div>
                    
                    <div class="reveal reveal-up delay-3">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">Telepon</span>
                        <p style="font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                            <a href="tel:<?= str_replace(['-', ' ', '(', ')'], '', escapeHtml($kontak_data['telepon'] ?? '081234567890')) ?>" 
                               style="color: var(--primary); text-decoration: none;" 
                               title="Klik untuk menelepon">
                                <?= escapeHtml($kontak_data['telepon'] ?? '0812-3456-7890') ?>
                            </a>
                            <button onclick="copyToClipboard('<?= escapeHtml($kontak_data['telepon'] ?? '0812-3456-7890') ?>', this)" 
                                    style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.9rem; padding: 5px; display: flex; align-items: center; gap: 0.4rem; transition: all 0.3s;"
                                    title="Salin nomor">
                                <i class="ri-file-copy-line"></i>
                                <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: none;" class="copy-status">Tersalin</span>
                            </button>
                        </p>
                    </div>
                    
                    <div class="reveal reveal-up delay-4">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">WhatsApp</span>
                        <p style="font-size: 1.1rem; font-weight: 600;">
                            <?php
                            $raw_wa = !empty($kontak_data['whatsapp']) ? $kontak_data['whatsapp'] : ($kontak_data['telepon'] ?? '081234567890');
                            $clean_wa = preg_replace('/[^0-9]/', '', $raw_wa);
                            $whatsapp_phone = $clean_wa;
                            if (strpos($clean_wa, '0') === 0) {
                                $whatsapp_phone = '62' . substr($clean_wa, 1);
                            }
                            ?>
                            <a href="https://wa.me/<?= escapeHtml($whatsapp_phone) ?>" 
                               target="_blank"
                               style="color: #25D366; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;" 
                               title="Chat via WhatsApp">
                                <i class="ri-whatsapp-line" style="font-size: 1.3rem;"></i> Hubungi via WhatsApp
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div id="map" class="reveal reveal-scale delay-5 themed-map" style="width: 100%; height: 450px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-lg); z-index: 1;"></div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--bg-warm);">
    <div class="container">
        <div class="art-content-section">
            <div class="reveal reveal-left parallax-element" data-speed="0.15">
                <span class="eyebrow">Warisan Monalisa</span>
                <h2 class="section-title">Nilai Seni dalam<br>Setiap Sajian</h2>
                <div class="divider-left"></div>
                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                    Restoran kami tidak hanya menyajikan makanan, tetapi juga sebuah pengalaman yang menghargai keindahan. "Monalisa Art" adalah jantung dari identitas visual kami, melambangkan keanggunan dan kualitas yang tak lekang oleh waktu.
                </p>
            </div>
            <div class="art-image-wrapper reveal reveal-right delay-2">
                <img src="assets/images/monalisa-art.webp" alt="Monalisa Art Piece" style="width: 100%; border-radius: var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const lat = -6.6263927;
        const lng = 106.8214916;
        const map = L.map('map', { attributionControl: false }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);
        
        marker.bindPopup('<b>Monalisa Restaurant</b><br>Klik pin untuk petunjuk arah.').openPopup();

        marker.on('click', () => {
            window.open('https://maps.app.goo.gl/J5wMuZqK8dnd5nu19', '_blank');
        });

        marker.getElement().style.cursor = 'pointer';
    });
</script>

<?php require_once 'includes/footer.php'; ?>
