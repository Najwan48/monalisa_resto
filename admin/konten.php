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

        if ($halaman && $bagian && array_key_exists($halaman, $label_halaman) && array_key_exists($bagian, $label_bagian)) {
            // Additional validation for URL fields
            if (strpos($bagian, 'link_') === 0 && !validate_input($isi, 'url')) {
                $pesan = "Format link tidak valid.";
                $status = 'error';
            } else {
                $stmt = $pdo->prepare(
                    "INSERT INTO konten_halaman (halaman, bagian, isi) VALUES (?, ?, ?)
                     ON DUPLICATE KEY UPDATE isi = VALUES(isi)"
                );
                $stmt->execute([$halaman, $bagian, $isi]);
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
    'kontak' => ['alamat', 'telepon', 'whatsapp', 'jam_operasional']
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

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= h($pesan) ?></div>
<?php endif; ?>

<?php foreach ($konten_per_halaman as $halaman => $bagian_list): ?>
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.5rem; color: var(--primary); border-bottom: 2px solid #f0f0f0; padding-bottom: 0.75rem;">
        Halaman: <?= h($label_halaman[$halaman] ?? ucfirst($halaman)) ?>
    </h3>
    <?php foreach ($bagian_list as $bagian => $data): ?>
    <form method="POST" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="halaman" value="<?= h($halaman) ?>">
        <input type="hidden" name="bagian" value="<?= h($bagian) ?>">
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <label><?= h($label_bagian[$bagian] ?? ucfirst($bagian)) ?></label>
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
                <textarea name="isi" class="form-control" rows="4"><?= h($nilai_input) ?></textarea>
            <?php else: ?>
                <input type="text" name="isi" class="form-control" value="<?= h($nilai_input) ?>">
            <?php endif; ?>
            <small style="color:#888;">Terakhir diperbarui: <?= h(date('d M Y H:i', strtotime($data['updated_at']))) ?></small>
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
        icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
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

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
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
                    // Optional: reload the page or fetch new data to ensure state consistency
                    // window.location.reload();
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
