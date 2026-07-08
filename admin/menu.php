<?php
$page_title = 'Manajemen Menu';
require_once '../includes/admin_header.php';
require_once '../includes/validation.php';

$pesan    = '';
$tipe_pesan = '';
$aksi     = $_GET['aksi'] ?? 'list';
$edit_id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$kategori_list = ['Soto & Sup', 'Nasi & Utama', 'Camilan', 'Minuman', 'Lainnya'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $pesan = "Validasi keamanan gagal.";
        $tipe_pesan = 'danger';
    } else {
        $action_post = $_POST['action'] ?? '';

        if ($action_post === 'simpan') {
            $nama_menu        = validate_input($_POST['nama_menu'] ?? '', 'string', 100);
            $asal_daerah      = validate_input($_POST['asal_daerah'] ?? '', 'string', 100);
            $deskripsi_singkat = validate_input($_POST['deskripsi_singkat'] ?? '', 'string', 150);
            $deskripsi_lengkap = validate_input($_POST['deskripsi_lengkap'] ?? '', 'string');
            $bahan_utama      = validate_input($_POST['bahan_utama'] ?? '', 'string');
            $info_alergen     = validate_input($_POST['info_alergen'] ?? '', 'string');
            $kategori_raw     = $_POST['kategori'] ?? '';
            $kategori         = validate_input($kategori_raw, 'string');
            $harga            = (float)($_POST['harga'] ?? 0);
            $status           = in_array($_POST['status'] ?? '', ['aktif', 'nonaktif']) ? $_POST['status'] : 'aktif';
            $id_edit          = (int)($_POST['id_edit'] ?? 0);

            if (!$nama_menu || !$asal_daerah || !$deskripsi_singkat || !$deskripsi_lengkap || !$bahan_utama || !$kategori || !in_array($kategori_raw, $kategori_list)) {
                $pesan = "Data tidak valid atau tidak lengkap.";
                $tipe_pesan = 'danger';
            } else {
                $foto_url_lama = $_POST['foto_url_lama'] ?? '';
                $foto_url      = $foto_url_lama;

                if (!empty($_FILES['foto']['name'])) {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $file_type = $finfo->file($_FILES['foto']['tmp_name']);
                    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                    $max_size      = 5 * 1024 * 1024;

                    if (!in_array($file_type, $allowed_types)) {
                        $pesan = "Tipe file tidak diizinkan. Gunakan JPG, PNG, atau WebP.";
                        $tipe_pesan = 'danger';
                    } elseif ($_FILES['foto']['size'] > $max_size) {
                        $pesan = "Ukuran file melebihi batas 5MB.";
                        $tipe_pesan = 'danger';
                    } else {
                        $upload_dir = '../assets/images/menu/';
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        $filename   = 'menu_' . time() . '_' . bin2hex(random_bytes(4)) . '.webp';
                        $saved_path = process_and_save_image($_FILES['foto']['tmp_name'], $upload_dir . $filename);
                        if ($saved_path) {
                            $foto_url = 'assets/images/menu/' . basename($saved_path);
                        } else {
                            $pesan = "Gagal memproses foto.";
                            $tipe_pesan = 'danger';
                        }
                    }
                }

                if (empty($pesan)) {
                    if ($id_edit > 0) {
                        $stmt = $pdo->prepare(
                            "UPDATE menu SET nama_menu=?, asal_daerah=?, deskripsi_singkat=?, deskripsi_lengkap=?,
                             bahan_utama=?, info_alergen=?, kategori=?, harga=?, foto_url=?, status=?
                             WHERE id=?"
                        );
                        $stmt->execute([
                            $nama_menu, $asal_daerah, $deskripsi_singkat, $deskripsi_lengkap,
                            $bahan_utama, $info_alergen, $kategori_raw, $harga, $foto_url, $status, $id_edit
                        ]);
                        log_aktivitas($pdo, $_SESSION['admin_id'], "Edit menu: $nama_menu");
                        $pesan = "Menu berhasil diperbarui.";
                    } else {
                        $stmt = $pdo->prepare(
                            "INSERT INTO menu (nama_menu, asal_daerah, deskripsi_singkat, deskripsi_lengkap,
                             bahan_utama, info_alergen, kategori, harga, foto_url, status)
                             VALUES (?,?,?,?,?,?,?,?,?,?)"
                        );
                        $stmt->execute([
                            $nama_menu, $asal_daerah, $deskripsi_singkat, $deskripsi_lengkap,
                            $bahan_utama, $info_alergen, $kategori, $harga, $foto_url, $status
                        ]);
                        log_aktivitas($pdo, $_SESSION['admin_id'], "Tambah menu baru: $nama_menu");
                        $pesan = "Menu baru berhasil ditambahkan.";
                    }
                    $tipe_pesan = 'success';
                    $aksi = 'list';
                }
            }
        }

        if ($action_post === 'hapus') {
            $hapus_id = (int)($_POST['hapus_id'] ?? 0);
            $row = $pdo->prepare("SELECT nama_menu FROM menu WHERE id = ?");
            $row->execute([$hapus_id]);
            $menu_lama = $row->fetch();

            $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
            $stmt->execute([$hapus_id]);

            if ($menu_lama && !empty($menu_lama['foto_url'])) {
                $foto_path = '../' . $menu_lama['foto_url'];
                if (file_exists($foto_path) && strpos($menu_lama['foto_url'], 'assets/images/menu/') === 0) {
                    unlink($foto_path);
                }
            }

            log_aktivitas($pdo, $_SESSION['admin_id'], "Hapus menu: " . ($menu_lama['nama_menu'] ?? $hapus_id));
            $pesan = "Menu berhasil dihapus.";
            $tipe_pesan = 'success';
            $aksi = 'list';
        }

        if ($action_post === 'toggle_status') {
            $toggle_id = (int)($_POST['toggle_id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE menu SET status = IF(status='aktif','nonaktif','aktif') WHERE id = ?");
            $stmt->execute([$toggle_id]);
            log_aktivitas($pdo, $_SESSION['admin_id'], "Toggle status menu ID: $toggle_id");
            $pesan = "Status menu diperbarui.";
            $tipe_pesan = 'success';
            $aksi = 'list';
        }
    }
}

$edit_data = null;
if ($aksi === 'edit' && $edit_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_data = $stmt->fetch();
    if (!$edit_data) {
        $aksi = 'list';
    }
}

$csrf_token = generate_csrf_token();
$kategori_list = ['Soto & Sup', 'Nasi & Utama', 'Camilan', 'Minuman', 'Lainnya'];
?>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= escapeHtml($pesan) ?></div>
<?php endif; ?>

<?php if ($aksi === 'list'): ?>

<div class="page-actions">
    <h2>Daftar Menu</h2>
    <div style="display: flex; gap: 1rem;">
        <form method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="q" class="form-control" placeholder="Cari menu..." value="<?= escapeHtml($_GET['q'] ?? '') ?>" style="padding: 6px 12px; font-size: 0.85rem; width: 200px;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="ri-search-line"></i></button>
        </form>
        <a href="menu.php?aksi=tambah" class="btn btn-primary">+ Tambah Menu</a>
    </div>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = $_GET['q'] ?? '';
            if ($search) {
                $stmt_list = $pdo->prepare("SELECT * FROM menu WHERE nama_menu LIKE ? OR kategori LIKE ? ORDER BY created_at DESC");
                $stmt_list->execute(["%$search%", "%$search%"]);
                $menus = $stmt_list->fetchAll();
            } else {
                $menus = $pdo->query("SELECT * FROM menu ORDER BY created_at DESC")->fetchAll();
            }
            foreach ($menus as $menu):
            ?>
            <tr>
                <td data-label="Foto">
                    <img src="<?= get_image_url($menu['foto_url'], true) ?>" alt="<?= escapeHtml($menu['nama_menu']) ?>"
                         style="width:56px; height:44px; object-fit:cover; border-radius: var(--radius-sm);"
                         onerror="this.src='https://via.placeholder.com/56x44?text=N/A'">
                </td>
                <td data-label="Nama Menu">
                    <strong style="font-size:0.875rem;"><?= escapeHtml($menu['nama_menu']) ?></strong><br>
                    <small style="color: var(--text-muted);"><?= escapeHtml($menu['asal_daerah']) ?></small>
                </td>
                <td data-label="Kategori"><span class="badge badge-secondary"><?= escapeHtml($menu['kategori']) ?></span></td>
                <td data-label="Harga" style="font-weight: 600; color: var(--primary);"><?= format_rupiah($menu['harga']) ?></td>
                <td data-label="Status">
                    <span class="badge <?= $menu['status'] === 'aktif' ? 'badge-success' : 'badge-secondary' ?>">
                        <?= escapeHtml($menu['status']) ?>
                    </span>
                </td>
                <td data-label="Aksi" style="text-align: center; white-space: nowrap;">
                    <a href="menu.php?aksi=edit&id=<?= $menu['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="ri-edit-line"></i></a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Toggle status menu ini?')">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="toggle_id" value="<?= $menu['id'] ?>">
                        <button type="submit" class="btn btn-secondary btn-sm" title="Toggle Status"><i class="ri-arrow-left-right-line"></i></button>
                    </form>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus menu ini?')">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="action" value="hapus">
                        <input type="hidden" name="hapus_id" value="<?= $menu['id'] ?>">
                        <button type="submit" class="btn btn-ghost-danger btn-sm" title="Hapus"><i class="ri-delete-bin-line"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php elseif ($aksi === 'tambah' || $aksi === 'edit'): ?>

<div class="page-actions">
    <h2><?= $aksi === 'edit' ? 'Edit Menu' : 'Tambah Menu Baru' ?></h2>
    <a href="menu.php" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="action" value="simpan">
        <input type="hidden" name="id_edit" value="<?= $edit_data['id'] ?? 0 ?>">
        <input type="hidden" name="foto_url_lama" value="<?= escapeHtml($edit_data['foto_url'] ?? '') ?>">

        <div class="form-grid" style="gap: 1.5rem;">
            <div class="form-group">
                <label for="nama_menu">Nama Menu</label>
                <input type="text" id="nama_menu" name="nama_menu" class="form-control"
                       value="<?= escapeHtml($edit_data['nama_menu'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="asal_daerah">Asal Daerah</label>
                <input type="text" id="asal_daerah" name="asal_daerah" class="form-control"
                       value="<?= escapeHtml($edit_data['asal_daerah'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" class="form-control" required>
                    <?php foreach ($kategori_list as $kat): ?>
                        <option value="<?= escapeHtml($kat) ?>" <?= ($edit_data['kategori'] ?? '') === $kat ? 'selected' : '' ?>>
                            <?= escapeHtml($kat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" id="harga" name="harga" class="form-control"
                       value="<?= escapeHtml($edit_data['harga'] ?? '') ?>" required min="0" step="500">
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi_singkat">Deskripsi Singkat (maks. 150 karakter)</label>
            <input type="text" id="deskripsi_singkat" name="deskripsi_singkat" class="form-control"
                   value="<?= escapeHtml($edit_data['deskripsi_singkat'] ?? '') ?>" maxlength="150" required>
        </div>

        <div class="form-group">
            <label for="deskripsi_lengkap">Deskripsi Lengkap</label>
            <textarea id="deskripsi_lengkap" name="deskripsi_lengkap" class="form-control" rows="4" required><?= escapeHtml($edit_data['deskripsi_lengkap'] ?? '') ?></textarea>
        </div>

        <div class="form-grid" style="gap: 1.5rem;">
            <div class="form-group">
                <label for="bahan_utama">Bahan Utama</label>
                <input type="text" id="bahan_utama" name="bahan_utama" class="form-control"
                       value="<?= escapeHtml($edit_data['bahan_utama'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="info_alergen">Info Alergen (opsional)</label>
                <input type="text" id="info_alergen" name="info_alergen" class="form-control"
                       value="<?= escapeHtml($edit_data['info_alergen'] ?? 'Tidak ada') ?>">
            </div>
        </div>

        <div class="form-grid" style="gap: 1.5rem;">
            <div class="form-group">
                <label for="foto">Foto Menu (JPG/PNG/WebP, maks. 2MB)</label>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                <?php if (!empty($edit_data['foto_url'])): ?>
                    <small style="color:#666;">Foto saat ini: <?= escapeHtml($edit_data['foto_url']) ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="status">Status Tampil</label>
                <select id="status" name="status" class="form-control">
                    <option value="aktif" <?= ($edit_data['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= ($edit_data['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
            <a href="menu.php" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding: 11px 36px;">
                <i class="ri-save-line"></i> Simpan Menu
            </button>
        </div>
    </form>
</div>

<?php endif; ?>

<?php require_once '../includes/admin_footer.php'; ?>
