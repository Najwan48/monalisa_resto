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

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= h($pesan) ?></div>
<?php endif; ?>

<div class="page-actions">
    <h2>Daftar Foto Galeri</h2>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.25rem; font-size: 1rem; color: #333;">Unggah Foto Baru</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="action" value="unggah">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="judul">Judul Foto</label>
                <input type="text" id="judul" name="judul" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="urutan">Urutan Tampil</label>
                <input type="number" id="urutan" name="urutan" class="form-control" value="0" min="0">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="foto">File Foto (maks. 3MB)</label>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp" required>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">Unggah</button>
        </div>
    </form>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <?php if (empty($galeri)): ?>
        <p style="padding: 1.5rem; color: #666;">Belum ada foto di galeri.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Judul</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($galeri as $item): ?>
                <tr>
                    <td>
                        <img src="../<?= h($item['foto_url']) ?>" alt="<?= h($item['judul']) ?>"
                             style="width:80px; height:60px; object-fit:cover; border-radius:10px;"
                             onerror="this.src='https://via.placeholder.com/80x60?text=N/A'">
                    </td>
                    <td><?= h($item['judul']) ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="action" value="urutan">
                            <input type="hidden" name="urutan_id" value="<?= $item['id'] ?>">
                            <input type="number" name="urutan_val" value="<?= $item['urutan'] ?>"
                                   class="form-control" style="width:70px;" min="0">
                            <button type="submit" class="btn btn-secondary btn-sm">Simpan</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Yakin hapus foto ini?')">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="action" value="hapus">
                            <input type="hidden" name="hapus_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
