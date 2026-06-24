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
                            <a href="<?= escapeHtml($kontak_data['maps_url'] ?? 'https://maps.app.goo.gl/J5wMuZqK8dnd5nu19') ?>" target="_blank" data-kontak="alamat"><?= escapeHtml($kontak_data['alamat']) ?></a>
                        </p>
                    </div>

                    <div class="reveal reveal-up delay-2">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">Jam Operasional</span>
                        <p style="font-size: 1.1rem; line-height: 1.6; color: var(--charcoal);" data-kontak="jam"><?= escapeHtml($kontak_data['jam_operasional']) ?></p>
                    </div>
                    
                    <?php if (!empty($kontak_data['telepon'])): ?>
                    <div class="reveal reveal-up delay-3">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">Telepon</span>
                        <p style="font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                            <a href="tel:<?= str_replace(['-', ' ', '(', ')'], '', escapeHtml($kontak_data['telepon'])) ?>"
                               data-kontak="telepon"
                               style="color: var(--primary); text-decoration: none;"
                               title="Klik untuk menelepon">
                                <?= escapeHtml($kontak_data['telepon']) ?>
                            </a>
                            <button onclick="copyToClipboard('<?= escapeHtml($kontak_data['telepon']) ?>', this)"
                                    style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.9rem; padding: 5px; display: flex; align-items: center; gap: 0.4rem; transition: all 0.3s;"
                                    title="Salin nomor">
                                <i class="ri-file-copy-line"></i>
                                <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: none;" class="copy-status">Tersalin</span>
                            </button>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($kontak_data['whatsapp'])): ?>
                    <div class="reveal reveal-up delay-4">
                        <span class="eyebrow" style="margin-bottom: 0.5rem; color: var(--text-faint);">WhatsApp</span>
                        <p style="font-size: 1.1rem; font-weight: 600;">
                            <?php
                            $raw_wa = $kontak_data['whatsapp'];
                            $clean_wa = preg_replace('/[^0-9]/', '', $raw_wa);
                            $whatsapp_phone = $clean_wa;
                            if (strpos($clean_wa, '0') === 0) {
                                $whatsapp_phone = '62' . substr($clean_wa, 1);
                            }
                            ?>
                            <a href="https://wa.me/<?= escapeHtml($whatsapp_phone) ?>"
                               data-kontak="whatsapp"
                               target="_blank"
                               style="color: #25D366; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;"
                               title="Chat via WhatsApp">
                                <i class="ri-whatsapp-line" style="font-size: 1.3rem;"></i> Hubungi via WhatsApp
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="map" class="reveal reveal-scale delay-5 themed-map" style="width: 100%; height: clamp(300px, 50vh, 450px); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-lg); z-index: 1; overflow: hidden;"></div>
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
        const lat = <?= (float)($kontak_data['maps_lat'] ?? -6.6263927) ?>;
        const lng = <?= (float)($kontak_data['maps_lng'] ?? 106.8214916) ?>;
        const map = L.map('map', { attributionControl: false }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const brandIcon = L.divIcon({
            className: 'custom-marker',
            html: '<svg width="36" height="48" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 0C8.059 0 0 8.059 0 18c0 12.6 18 30 18 30s18-17.4 18-30C36 8.059 27.941 0 18 0z" fill="#8B6914"/><circle cx="18" cy="17" r="8" fill="#fff"/></svg>',
            iconSize: [36, 48],
            iconAnchor: [18, 48],
            popupAnchor: [0, -48]
        });

        const marker = L.marker([lat, lng], { icon: brandIcon }).addTo(map);

        const googleMapsUrl = <?= json_encode($kontak_data['maps_url'] ?? 'https://maps.app.goo.gl/J5wMuZqK8dnd5nu19') ?>;
        marker.bindPopup(
            '<div style="text-align:center;padding:0.25rem 0">' +
            '<b style="font-size:1rem;color:#1C1C1C">Monalisa Resto</b><br>' +
            '<span style="font-size:0.85rem;color:#767676">' + '<?= htmlspecialchars($kontak_data['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?>' + '</span><br>' +
            '<a href="' + googleMapsUrl + '" target="_blank" style="display:inline-block;margin-top:8px;padding:6px 16px;background:#8B6914;color:#fff;border-radius:6px;text-decoration:none;font-size:0.85rem;font-weight:600">Buka di Maps</a>' +
            '</div>'
        ).openPopup();

        marker.on('click', () => {
            window.open(googleMapsUrl, '_blank');
        });

        marker.getElement().style.cursor = 'pointer';
    });
</script>

<script>
initRealtimePolling('/monalisa_resto/api_kontak.php', 30000, function(data) {
    var alamatEl = document.querySelector('[data-kontak="alamat"]');
    if (alamatEl && data.alamat) alamatEl.textContent = data.alamat;
    var jamEl = document.querySelector('[data-kontak="jam"]');
    if (jamEl && data.jam_operasional) jamEl.textContent = data.jam_operasional;
    var telEl = document.querySelector('[data-kontak="telepon"]');
    if (telEl && data.telepon) {
        telEl.textContent = data.telepon;
        telEl.href = 'tel:' + data.telepon.replace(/[^0-9]/g, '');
    }
    var waEl = document.querySelector('[data-kontak="whatsapp"]');
    if (waEl) {
        if (data.whatsapp) {
            waEl.href = 'https://wa.me/' + data.whatsapp;
            waEl.closest('.reveal').style.display = '';
        } else {
            waEl.closest('.reveal').style.display = 'none';
        }
    }
    if (data.maps_url && alamatEl) {
        alamatEl.href = data.maps_url;
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
