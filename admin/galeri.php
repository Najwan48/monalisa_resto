<?php
$page_title = 'Manajemen Galeri';
require_once '../includes/admin_header.php';

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
            $judul  = trim($_POST['judul'] ?? '');
            $urutan = (int)($_POST['urutan'] ?? 0);

            if (empty($_FILES['foto']['name'])) {
                $pesan      = "Pilih file foto terlebih dahulu.";
                $tipe_pesan = 'danger';
            } else {
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                $max_size      = 3 * 1024 * 1024;

                if (!in_array($_FILES['foto']['type'], $allowed_types)) {
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
                    $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $filename = 'galeri_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $filename);

                    $foto_url = 'assets/images/galeri/' . $filename;
                    $stmt     = $pdo->prepare("INSERT INTO galeri (judul, foto_url, urutan) VALUES (?, ?, ?)");
                    $stmt->execute([$judul, $foto_url, $urutan]);
                    log_aktivitas($pdo, $_SESSION['admin_id'], "Unggah foto galeri: $judul");
                    $pesan      = "Foto berhasil diunggah.";
                    $tipe_pesan = 'success';
                }
            }
        }

        if ($action_post === 'hapus') {
            $hapus_id = (int)($_POST['hapus_id'] ?? 0);
            $row = $pdo->prepare("SELECT judul, foto_url FROM galeri WHERE id = ?");
            $row->execute([$hapus_id]);
            $galeri_lama = $row->fetch();

            if ($galeri_lama) {
                $file_path = '../' . $galeri_lama['foto_url'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
                $stmt->execute([$hapus_id]);
                log_aktivitas($pdo, $_SESSION['admin_id'], "Hapus foto galeri: " . $galeri_lama['judul']);
                $pesan      = "Foto berhasil dihapus.";
                $tipe_pesan = 'success';
            }
        }

        if ($action_post === 'urutan') {
            $urutan_id  = (int)($_POST['urutan_id'] ?? 0);
            $urutan_val = (int)($_POST['urutan_val'] ?? 0);
            $stmt       = $pdo->prepare("UPDATE galeri SET urutan = ? WHERE id = ?");
            $stmt->execute([$urutan_val, $urutan_id]);
            log_aktivitas($pdo, $_SESSION['admin_id'], "Update urutan galeri ID: $urutan_id");
            $pesan      = "Urutan berhasil diperbarui.";
            $tipe_pesan = 'success';
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
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1.5rem; align-items: end;">
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
                    <td style="padding-left: 1.75rem; width: 120px;">
                        <div style="width: 100px; height: 70px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border);">
                            <img src="../<?= h($item['foto_url']) ?>" alt="<?= h($item['judul']) ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/100x70?text=No+Image'">
                        </div>
                    </td>
                    <td style="font-weight: 500; color: var(--text);"><?= h($item['judul']) ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="action" value="urutan">
                            <input type="hidden" name="urutan_id" value="<?= $item['id'] ?>">
                            <input type="number" name="urutan_val" value="<?= $item['urutan'] ?>"
                                   class="form-control" style="width:70px; padding: 5px 10px;" min="0">
                            <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                        </form>
                    </td>
                    <td style="padding-right: 1.75rem; text-align: right;">
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

<?php require_once '../includes/admin_footer.php'; ?>
