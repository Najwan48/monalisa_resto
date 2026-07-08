<?php
$page_title = 'Dashboard';
require_once '../includes/admin_header.php';

$jml_menu   = $pdo->query("SELECT COUNT(*) FROM menu WHERE status = 'aktif'")->fetchColumn();
$jml_galeri = $pdo->query("SELECT COUNT(*) FROM galeri")->fetchColumn();
$jml_konten = $pdo->query("SELECT COUNT(*) FROM konten_halaman")->fetchColumn();
$jml_users  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$menu_total = $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn();

$menu_kategori = $pdo->query(
    "SELECT kategori, COUNT(*) as jumlah FROM menu WHERE status = 'aktif' GROUP BY kategori ORDER BY jumlah DESC"
)->fetchAll();

$menu_terbaru = $pdo->query(
    "SELECT id, nama_menu, kategori, harga, foto_url, updated_at FROM menu WHERE status = 'aktif' ORDER BY updated_at DESC LIMIT 5"
)->fetchAll();

$galeri_terbaru = $pdo->query(
    "SELECT id, judul, foto_url, urutan, created_at FROM galeri ORDER BY urutan ASC, created_at DESC LIMIT 6"
)->fetchAll();

$konten_halaman = $pdo->query(
    "SELECT halaman, bagian, isi, updated_at FROM konten_halaman ORDER BY updated_at DESC LIMIT 8"
)->fetchAll();

$log_terbaru = $pdo->query(
    "SELECT l.aksi, l.waktu, u.username FROM log_aktivitas l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.waktu DESC LIMIT 10"
)->fetchAll();

$last_activity = $pdo->query("SELECT waktu FROM log_aktivitas ORDER BY waktu DESC LIMIT 1")->fetchColumn();
?>

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Ringkasan aktivitas dan status konten Monalisa Resto.</p>
    </div>
    <a href="menu.php?aksi=tambah" class="btn btn-primary">
        <i class="ri-add-line"></i> Tambah Menu
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="value"><?= $jml_menu ?></div>
                <div class="label">Menu Aktif</div>
            </div>
            <div class="stat-icon-wrap" style="background: var(--primary-pale); color: var(--primary);">
                <i class="ri-restaurant-line"></i>
            </div>
        </div>
        <div class="stat-trend">
            <span class="trend-text"><?= $menu_total - $jml_menu ?> non-aktif dari <?= $menu_total ?> total</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="value"><?= $jml_galeri ?></div>
                <div class="label">Foto Galeri</div>
            </div>
            <div class="stat-icon-wrap" style="background: #EFF6FF; color: #3B82F6;">
                <i class="ri-image-line"></i>
            </div>
        </div>
        <div class="stat-trend">
            <span class="trend-text"><?= count($galeri_terbaru) ?> foto tampil terbaru</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="value"><?= $jml_konten ?></div>
                <div class="label">Entri Konten</div>
            </div>
            <div class="stat-icon-wrap" style="background: var(--success-pale); color: var(--success);">
                <i class="ri-file-text-line"></i>
            </div>
        </div>
        <div class="stat-trend">
            <span class="trend-text">Tersebar di <?= count(array_unique(array_column($konten_halaman, 'halaman'))) ?> halaman</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="value"><?= $jml_users ?></div>
                <div class="label">Admin Users</div>
            </div>
            <div class="stat-icon-wrap" style="background: #FEF3C7; color: var(--warning);">
                <i class="ri-shield-user-line"></i>
            </div>
        </div>
        <div class="stat-trend">
            <span class="trend-text">Sistem berjalan normal</span>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Menu Terbaru</span>
            <a href="menu.php" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <?php if (empty($menu_terbaru)): ?>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Belum ada menu.</p>
        <?php else: ?>
        <div class="menu-preview">
            <?php foreach ($menu_terbaru as $menu): ?>
            <div class="menu-item">
                <div class="menu-thumb" style="width: 50px; height: 50px; overflow: hidden; margin-right: 1rem; border-radius: 4px;">
                    <img src="<?= get_image_url($menu['foto_url'], true) ?>" alt="<?= escapeHtml($menu['nama_menu']) ?>" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" decoding="async">
                </div>
                <div class="menu-info">
                    <h3><?= escapeHtml($menu['nama_menu']) ?></h3>
                    <div class="menu-meta">
                        <span class="badge badge-secondary"><?= escapeHtml($menu['kategori']) ?></span>
                        <span class="price"><?= format_rupiah($menu['harga']) ?></span>
                    </div>
                    <div class="menu-date"><?= date('d M Y', strtotime($menu['updated_at'])) ?></div>
                </div>
                <div class="menu-actions">
                    <a href="menu.php?id=<?= $menu['id'] ?>&aksi=edit" class="btn btn-sm btn-primary">
                        <i class="ri-edit-line"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Kategori Menu</span>
            <a href="menu.php" class="btn btn-sm btn-secondary">Kelola</a>
        </div>
        <?php if (empty($menu_kategori)): ?>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Belum ada data kategori.</p>
        <?php else: ?>
        <div class="category-list">
            <?php foreach ($menu_kategori as $kategori): ?>
            <div class="category-item">
                <div class="category-name"><?= escapeHtml($kategori['kategori']) ?></div>
                <div class="category-count"><?= $kategori['jumlah'] ?> item</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Galeri Terbaru</span>
            <a href="galeri.php" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <?php if (empty($galeri_terbaru)): ?>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Belum ada foto galeri.</p>
        <?php else: ?>
        <div class="gallery-grid" style="grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
            <?php foreach ($galeri_terbaru as $foto): ?>
            <div class="gallery-item" style="border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; background: var(--surface);">
                <div style="height: 120px; overflow: hidden;">
                    <img src="<?= get_image_url($foto['foto_url'], true) ?>" alt="<?= escapeHtml($foto['judul']) ?>"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/200x150?text=No+Image'" loading="lazy" decoding="async">
                </div>
                <div style="padding: 0.75rem;">
                    <div style="font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text);">
                        <?= escapeHtml($foto['judul']) ?>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <a href="galeri.php" class="btn btn-sm btn-secondary">Kelola</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Aktivitas Terbaru</span>
            <a href="log.php" class="btn btn-sm btn-secondary">Log Lengkap</a>
        </div>
        <?php if (empty($log_terbaru)): ?>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Belum ada aktivitas tercatat.</p>
        <?php else: ?>
        <div class="activity-list">
            <?php foreach (array_slice($log_terbaru, 0, 6) as $log): ?>
            <div class="activity-item">
                <div class="activity-time"><?= date('d/m H:i', strtotime($log['waktu'])) ?></div>
                <div class="activity-user"><?= escapeHtml($log['username'] ?? 'Sistem') ?></div>
                <div class="activity-action"><?= escapeHtml($log['aksi']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Status Sistem</span>
        </div>
        <div class="system-status">
            <div class="status-item">
                <div class="status-label">Database</div>
                <div class="status-value status-success"><i class="ri-checkbox-blank-circle-fill" style="font-size:0.5rem;"></i> Aktif</div>
            </div>
            <div class="status-item">
                <div class="status-label">Aktivitas Terakhir</div>
                <div class="status-value" style="font-size:0.8rem;"><?= $last_activity ? date('d M Y, H:i', strtotime($last_activity)) : 'Tidak ada' ?></div>
            </div>
            <div class="status-item">
                <div class="status-label">Total Konten</div>
                <div class="status-value"><?= $jml_konten ?> entri</div>
            </div>
            <div class="status-item">
                <div class="status-label">Menu Tersedia</div>
                <div class="status-value"><?= $jml_menu ?> aktif</div>
            </div>
        </div>

        <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--border);">
            <p class="card-title" style="margin-bottom: 1rem;">Aksi Cepat</p>
            <div class="quick-actions">
                <a href="menu.php?aksi=tambah" class="btn btn-primary btn-sm"><i class="ri-add-line"></i> Menu Baru</a>
                <a href="galeri.php" class="btn btn-secondary btn-sm"><i class="ri-camera-line"></i> Upload Foto</a>
                <a href="konten.php" class="btn btn-secondary btn-sm"><i class="ri-edit-2-line"></i> Edit Konten</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
