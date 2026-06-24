<?php
ob_start();
$page_title = 'Manajemen Konten';
require_once '../includes/admin_header.php';
require_once '../includes/validation.php';

$label_halaman = [
    'beranda'      => 'Beranda',
    'tentang_kami' => 'Tentang Kami',
    'kontak'       => 'Kontak & Lokasi',
];

$label_bagian = [
    'tagline'         => 'Tagline Hero',
    'pengantar'       => 'Teks Pengantar',
    'sejarah'         => 'Sejarah Restoran',
    'visi'            => 'Visi Restoran',
    'alamat'          => 'Alamat',
    'telepon'         => 'Telepon',
    'whatsapp'        => 'WhatsApp Bisnis',
    'jam_operasional' => 'Jam Operasional',
    'link_gofood'     => 'Link GoFood',
    'link_grabfood'   => 'Link GrabFood',
    'maps_url'        => 'Link Google Maps',
];

$pesan      = '';
$tipe_pesan = '';
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = 'error';
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $pesan = "Validasi keamanan gagal.";
    } else {
        $halaman = validate_input($_POST['halaman'] ?? '', 'string');
        $bagian  = validate_input($_POST['bagian'] ?? '', 'string');
        $isi     = $_POST['isi'] ?? '';

        if (in_array($bagian, ['telepon', 'whatsapp']) && $isi !== '' && !preg_match('/^[0-9+\-\s]+$/', $isi)) {
            $pesan = "Field '$bagian' hanya boleh berisi angka.";
            $tipe_pesan = 'danger';
        } elseif ($halaman && $bagian && array_key_exists($halaman, $label_halaman) && array_key_exists($bagian, $label_bagian)) {
            if ((strpos($bagian, 'link_') === 0 || $bagian === 'maps_url') && $isi !== '' && !validate_input($isi, 'url')) {
                $pesan = "Format link tidak valid.";
                $status = 'error';
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO konten_halaman (halaman, bagian, isi) VALUES (?, ?, ?)
                     ON DUPLICATE KEY UPDATE isi = VALUES(isi)"
                );
                $stmt->execute([$halaman, $bagian, $isi]);

                if ($halaman === 'kontak' && ($bagian === 'maps_url' || $bagian === 'alamat') && $isi !== '') {
                    $resolved_lat = null;
                    $resolved_lng = null;

                    if ($bagian === 'maps_url') {
                        $map_opts = ['http' => [
                            'header' => "User-Agent: MonalisaResto/1.0\r\n",
                            'timeout' => 5,
                            'follow_location' => false
                        ]];
                        $headers = @get_headers($isi, true, stream_context_create($map_opts));
                        $redirect_url = is_array($headers['Location']) ? end($headers['Location']) : ($headers['Location'] ?? '');
                        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $redirect_url, $m)) {
                            $resolved_lat = $m[1];
                            $resolved_lng = $m[2];
                        }
                    }

                    if (!$resolved_lat) {
                        $stmt_alamat = $pdo->prepare("SELECT isi FROM konten_halaman WHERE halaman='kontak' AND bagian='alamat'");
                        $stmt_alamat->execute();
                        $alamat_text = $stmt_alamat->fetchColumn();
                        if ($alamat_text) {
                            $geo_url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . urlencode($alamat_text);
                            $geo_opts = ['http' => ['header' => "User-Agent: MonalisaResto/1.0\r\n", 'timeout' => 5]];
                            $geo_raw = @file_get_contents($geo_url, false, stream_context_create($geo_opts));
                            if ($geo_raw) {
                                $geo_data = json_decode($geo_raw, true);
                                if (!empty($geo_data[0]['lat']) && !empty($geo_data[0]['lon'])) {
                                    $resolved_lat = $geo_data[0]['lat'];
                                    $resolved_lng = $geo_data[0]['lon'];
                                }
                            }
                        }
                    }

                    if ($resolved_lat && $resolved_lng) {
                        $stmt_geo = $pdo->prepare(
                            "INSERT INTO konten_halaman (halaman, bagian, isi) VALUES ('kontak', 'maps_lat', ?), ('kontak', 'maps_lng', ?)
                             ON DUPLICATE KEY UPDATE isi = VALUES(isi)"
                        );
                        $stmt_geo->execute([$resolved_lat, $resolved_lng]);
                    }
                }

                log_aktivitas($pdo, $_SESSION['admin_id'], "Edit konten: halaman=$halaman, bagian=$bagian");

                $nama_halaman = $label_halaman[$halaman] ?? ucfirst($halaman);
                $nama_bagian = $label_bagian[$bagian] ?? ucfirst($bagian);
                $pesan = "Konten halaman '$nama_halaman' - bagian '$nama_bagian' berhasil disimpan.";
                $status = 'success';
            }
        } else {
            $pesan = "Data tidak lengkap atau tidak valid.";
        }
    }

    if (isset($_POST['ajax'])) {
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $status,
            'message' => $pesan,
            'updated_at' => date('d M Y H:i')
        ]);
        exit;
    }
    
    $tipe_pesan = $status === 'success' ? 'success' : 'danger';
}

$semua_konten = $pdo->query(
    "SELECT halaman, bagian, isi, updated_at FROM konten_halaman ORDER BY halaman, bagian"
)->fetchAll();

$konten_db = [];
foreach ($semua_konten as $k) {
    $konten_db[$k['halaman']][$k['bagian']] = $k;
}

$struktur_halaman = [
    'beranda' => ['tagline', 'pengantar', 'link_gofood', 'link_grabfood'],
    'tentang_kami' => ['sejarah', 'visi'],
    'kontak' => ['alamat', 'telepon', 'whatsapp', 'jam_operasional', 'maps_url']
];

$konten_per_halaman = [];
foreach ($struktur_halaman as $halaman => $bagian_list) {
    foreach ($bagian_list as $bagian) {
        if (isset($konten_db[$halaman][$bagian])) {
            $konten_per_halaman[$halaman][$bagian] = $konten_db[$halaman][$bagian];
        } else {
            $konten_per_halaman[$halaman][$bagian] = [
                'halaman' => $halaman,
                'bagian' => $bagian,
                'isi' => '',
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
}


?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<style>#admin-map-preview{z-index:0;margin-bottom:1rem}.custom-marker{background:none!important;border:none!important}.custom-marker svg{filter:drop-shadow(0 2px 6px rgba(139,105,20,0.35));transition:transform 0.2s ease}.custom-marker:hover svg{transform:scale(1.1)}</style>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= escapeHtml($pesan) ?></div>
<?php endif; ?>

<?php foreach ($konten_per_halaman as $halaman => $bagian_list): ?>
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.5rem; color: var(--primary); border-bottom: 2px solid #f0f0f0; padding-bottom: 0.75rem;">
        Halaman: <?= escapeHtml($label_halaman[$halaman] ?? ucfirst($halaman)) ?>
    </h3>
    <?php foreach ($bagian_list as $bagian => $data): ?>
    <form method="POST" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="halaman" value="<?= escapeHtml($halaman) ?>">
        <input type="hidden" name="bagian" value="<?= escapeHtml($bagian) ?>">
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <label><?= escapeHtml($label_bagian[$bagian] ?? ucfirst($bagian)) ?></label>
            <?php
            $nilai_input = $data['isi'];
            if (empty($nilai_input)) {
                if ($bagian === 'link_gofood') {
                    $nilai_input = 'https://gofood.link/a/BMMv8Pb';
                } elseif ($bagian === 'link_grabfood') {
                    $nilai_input = 'https://r.grab.com/g/6-20260510_203031_8a7e66d9e9694765be4c04cda49c0859_MEXMPS-6-C2XANPEKCVDGNT';
                }
            }
            ?>
            <?php if (strlen($nilai_input) > 120): ?>
                <textarea name="isi" class="form-control" rows="4"><?= escapeHtml($nilai_input) ?></textarea>
            <?php else: ?>
                <input type="<?= in_array($bagian, ['telepon', 'whatsapp']) ? 'tel' : (in_array($bagian, ['link_gofood', 'link_grabfood', 'maps_url']) ? 'url' : 'text') ?>" name="isi" class="form-control" value="<?= escapeHtml($nilai_input) ?>" <?= $bagian === 'maps_url' ? 'placeholder="https://maps.app.goo.gl/..."' : '' ?> <?= in_array($bagian, ['telepon', 'whatsapp']) ? 'pattern="[0-9+\-\s]*" inputmode="numeric" placeholder="Contoh: 0812-8114-1923"' : '' ?>>
            <?php endif; ?>
            <?php if ($bagian === 'maps_url'): ?>
                <div id="admin-map-preview" style="height: 300px; border-radius: var(--radius-sm); margin-top: 0.75rem; border: 1px solid #e0e0e0;"></div>
            <?php endif; ?>
            <small style="color:#888;">Terakhir diperbarui: <?= escapeHtml(date('d M Y H:i', strtotime($data['updated_at']))) ?></small>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
    </form>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');
    
    const showToast = (message, type = 'success') => {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.style.cssText = 'background: #ffffff; color: #1c1917; border-left: 4px solid ' + (type === 'success' ? 'var(--success)' : 'var(--danger)') + '; padding: 12px 20px; border-radius: var(--radius-sm); box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; font-size: 0.875rem; font-weight: 600; opacity: 0; transform: translateY(-20px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); min-width: 300px; pointer-events: auto;';
        
        const icon = document.createElement('i');
        icon.className = type === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';
        icon.style.color = type === 'success' ? 'var(--success)' : 'var(--danger)';
        icon.style.fontSize = '1.1rem';
        
        const text = document.createElement('span');
        text.textContent = message;
        
        toast.appendChild(icon);
        toast.appendChild(text);
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3500);
    };

    const mapEl = document.getElementById('admin-map-preview');
    let adminMap = null;
    let adminMarker = null;
    let savedLat = <?= (float)($konten_db['kontak']['maps_lat']['isi'] ?? -6.6263927) ?>;
    let savedLng = <?= (float)($konten_db['kontak']['maps_lng']['isi'] ?? 106.8214916) ?>;

    const adminBrandIcon = L.divIcon({
        className: 'custom-marker',
        html: '<svg width="36" height="48" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 0C8.059 0 0 8.059 0 18c0 12.6 18 30 18 30s18-17.4 18-30C36 8.059 27.941 0 18 0z" fill="#8B6914"/><circle cx="18" cy="17" r="8" fill="#fff"/></svg>',
        iconSize: [36, 48],
        iconAnchor: [18, 48],
        popupAnchor: [0, -48]
    });
    const adminAlamat = <?= json_encode($konten_db['kontak']['alamat']['isi'] ?? '') ?>;
    const adminMapsUrl = <?= json_encode($konten_db['kontak']['maps_url']['isi'] ?? 'https://maps.app.goo.gl/J5wMuZqK8dnd5nu19') ?>;

    function initAdminMap(lat, lng) {
        if (adminMap) {
            adminMap.setView([lat, lng], 15);
            adminMarker.setLatLng([lat, lng]);
            return;
        }
        adminMap = L.map(mapEl, { attributionControl: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(adminMap);
        adminMarker = L.marker([lat, lng], { icon: adminBrandIcon }).addTo(adminMap);
        adminMarker.bindPopup(
            '<div style="text-align:center;padding:0.25rem 0">' +
            '<b style="font-size:1rem;color:#1C1C1C">Monalisa Resto</b><br>' +
            '<span style="font-size:0.85rem;color:#767676">' + adminAlamat + '</span><br>' +
            '<a href="' + adminMapsUrl + '" target="_blank" style="display:inline-block;margin-top:8px;padding:6px 16px;background:#8B6914;color:#fff;border-radius:6px;text-decoration:none;font-size:0.85rem;font-weight:600">Buka di Maps</a>' +
            '</div>'
        ).openPopup();
    }

    if (mapEl) {
        setTimeout(() => initAdminMap(savedLat, savedLng), 100);
    }

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> Menyimpan...';
            
            const formData = new FormData(form);
            formData.append('ajax', '1');
            
            fetch('konten.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = originalText;
                
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    const timeTag = form.querySelector('small');
                    if (timeTag) {
                        timeTag.textContent = 'Terakhir diperbarui: ' + data.updated_at;
                    }
                    const input = form.querySelector('[name="isi"]');
                    if (input) input.defaultValue = input.value;
                    const bagianInput = form.querySelector('input[name="bagian"]');
                    if (bagianInput && (bagianInput.value === 'maps_url' || bagianInput.value === 'alamat')) {
                        setTimeout(function() {
                            fetch('../api_kontak.php', { cache: 'no-store' })
                                .then(function(r) { return r.json(); })
                                .then(function(d) {
                                    if (d.maps_lat && d.maps_lng) {
                                        savedLat = parseFloat(d.maps_lat);
                                        savedLng = parseFloat(d.maps_lng);
                                        if (d.maps_url) adminMapsUrl = d.maps_url;
                                        if (d.alamat) adminAlamat = d.alamat;
                                        initAdminMap(savedLat, savedLng);
                                        if (adminMarker) {
                                            adminMarker.setPopupContent(
                                                '<div style="text-align:center;padding:0.25rem 0">' +
                                                '<b style="font-size:1rem;color:#1C1C1C">Monalisa Resto</b><br>' +
                                                '<span style="font-size:0.85rem;color:#767676">' + adminAlamat + '</span><br>' +
                                                '<a href="' + adminMapsUrl + '" target="_blank" style="display:inline-block;margin-top:8px;padding:6px 16px;background:#8B6914;color:#fff;border-radius:6px;text-decoration:none;font-size:0.85rem;font-weight:600">Buka di Maps</a>' +
                                                '</div>'
                                            );
                                        }
                                    }
                                })
                                .catch(function() {});
                        }, 1500);
                    }
                } else {
                    showToast(data.message, 'danger');
                }
            })
            .catch(() => {
                button.disabled = false;
                button.innerHTML = originalText;
                showToast('Terjadi kesalahan koneksi.', 'danger');
            });
        });
    });
});
</script>

<?php require_once '../includes/admin_footer.php'; ?>
