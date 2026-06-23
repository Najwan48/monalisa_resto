<?php
$page_title = 'Manajemen Galeri';
require_once '../includes/admin_header.php';
require_once '../includes/validation.php';

$pesan      = '';
$tipe_pesan = '';
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $pesan      = "Validasi keamanan gagal.";
        $tipe_pesan = 'danger';
    } else {
        $action_post = $_POST['action'] ?? '';

        if ($action_post === 'unggah') {
            $judul  = validate_input($_POST['judul'] ?? '', 'string', 100);
            $urutan = validate_input($_POST['urutan'] ?? 0, 'int');

            if (!$judul || $urutan === false || empty($_FILES['foto']['name'])) {
                $pesan      = "Data tidak valid atau file foto belum dipilih.";
                $tipe_pesan = 'danger';
            } else {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $file_type = $finfo->file($_FILES['foto']['tmp_name']);
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                $max_size      = 3 * 1024 * 1024;

                if (!in_array($file_type, $allowed_types)) {
                    $pesan      = "Tipe file tidak diizinkan. Gunakan JPG, PNG, atau WebP.";
                    $tipe_pesan = 'danger';
                } elseif ($_FILES['foto']['size'] > $max_size) {
                    $pesan      = "Ukuran file melebihi batas 3MB.";
                    $tipe_pesan = 'danger';
                } else {
                    $upload_dir = '../assets/images/galeri/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    $filename = 'galeri_' . time() . '_' . bin2hex(random_bytes(4)) . '.webp';

                    if (process_and_save_image($_FILES['foto']['tmp_name'], $upload_dir . $filename)) {
                        $foto_url = 'assets/images/galeri/' . $filename;
                        $stmt     = $pdo->prepare("INSERT INTO galeri (judul, foto_url, urutan) VALUES (?, ?, ?)");
                        $stmt->execute([$judul, $foto_url, $urutan]);
                        log_aktivitas($pdo, $_SESSION['admin_id'], "Unggah foto galeri: $judul");
                        $pesan      = "Foto berhasil diunggah.";
                        $tipe_pesan = 'success';
                    } else {
                        $pesan      = "Gagal memproses foto.";
                        $tipe_pesan = 'danger';
                    }
                }
            }
        }

        if ($action_post === 'urutan') {
            $urutan_id  = validate_input($_POST['urutan_id'] ?? 0, 'int');
            $urutan_val = validate_input($_POST['urutan_val'] ?? 0, 'int');

            if ($urutan_id !== false && $urutan_val !== false) {
                $stmt       = $pdo->prepare("UPDATE galeri SET urutan = ? WHERE id = ?");
                $stmt->execute([$urutan_val, $urutan_id]);
                log_aktivitas($pdo, $_SESSION['admin_id'], "Update urutan galeri ID: $urutan_id");
                $pesan      = "Urutan berhasil diperbarui.";
                $tipe_pesan = 'success';
            }
        }
    }
}

$galeri = $pdo->query("SELECT * FROM galeri ORDER BY urutan ASC, created_at DESC")->fetchAll();
?>

<div class="page-header">
    <div>
        <h1>Manajemen Galeri</h1>
        <p>Kelola koleksi foto untuk ditampilkan di halaman galeri website.</p>
    </div>
</div>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= h($pesan) ?></div>
<?php endif; ?>

<div class="card">
    <h3 style="margin-bottom: 1.5rem; font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-muted);">Unggah Foto Baru</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="action" value="unggah">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1.5rem; align-items: end;" class="galeri-form-grid">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="judul">Judul/Keterangan Foto</label>
                <input type="text" id="judul" name="judul" class="form-control" placeholder="Contoh: Suasana Ruang Utama" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="urutan">Urutan</label>
                <input type="number" id="urutan" name="urutan" class="form-control" value="0" min="0">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="foto">Pilih File</label>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp" required>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 2rem;">Unggah</button>
        </div>
    </form>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--border);">
        <h3 style="font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text-muted);">Daftar Foto</h3>
    </div>
    <?php if (empty($galeri)): ?>
        <p style="padding: 2rem; color: var(--text-muted); text-align: center; font-style: italic;">Belum ada foto yang diunggah.</p>
    <?php else: ?>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th style="padding-left: 1.75rem;">Preview</th>
                    <th>Judul/Keterangan</th>
                    <th>Urutan</th>
                    <th style="padding-right: 1.75rem; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($galeri as $item): ?>
                <tr>
                    <td data-label="Preview">
                        <div style="width: 100px; height: 70px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border);">
                            <img src="<?= get_image_url($item['foto_url'], true) ?>" alt="<?= h($item['judul']) ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/100x70?text=No+Image'">
                        </div>
                    </td>
                    <td data-label="Judul" style="font-weight: 500; color: var(--text);"><?= h($item['judul']) ?></td>
                    <td data-label="Urutan">
                        <form method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="action" value="urutan">
                            <input type="hidden" name="urutan_id" value="<?= $item['id'] ?>">
                            <input type="number" name="urutan_val" value="<?= $item['urutan'] ?>"
                                   class="form-control" style="width:70px; padding: 5px 10px;" min="0">
                            <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                        </form>
                    </td>
                    <td data-label="Aksi">
                        <form method="POST" onsubmit="return confirm('Yakin hapus foto ini?')" style="display: inline-block;">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="action" value="hapus">
                            <input type="hidden" name="hapus_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-ghost-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
@media (max-width: 900px) {
    table tr {
        grid-template-columns: 88px 1fr;
        grid-template-rows: auto auto auto;
    }

    table td[data-label="Preview"] {
        grid-column: 1;
        grid-row: 1 / 3;
    }

    table td[data-label="Judul"] {
        grid-column: 2;
        grid-row: 1;
    }

    table td[data-label="Urutan"] {
        grid-column: 2;
        grid-row: 2;
        margin-top: 0.35rem;
    }

    table td[data-label="Aksi"] {
        grid-column: 1 / 3;
        grid-row: 3;
    }
}
</style>

<?php require_once '../includes/admin_footer.php'; ?>
