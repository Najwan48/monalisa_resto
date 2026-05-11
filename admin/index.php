<?php
$page_title = 'Dashboard';
require_once '../includes/admin_header.php';

// Basic statistics
$jml_menu   = $pdo->query("SELECT COUNT(*) FROM menu WHERE status = 'aktif'")->fetchColumn();
$jml_galeri = $pdo->query("SELECT COUNT(*) FROM galeri")->fetchColumn();
$jml_konten = $pdo->query("SELECT COUNT(*) FROM konten_halaman")->fetchColumn();
$jml_users  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Menu category breakdown
$menu_kategori = $pdo->query(
    "SELECT kategori, COUNT(*) as jumlah 
     FROM menu 
     WHERE status = 'aktif' 
     GROUP BY kategori 
     ORDER BY jumlah DESC"
)->fetchAll();

// Recent menu items
$menu_terbaru = $pdo->query(
    "SELECT id, nama_menu, kategori, harga, foto_url, created_at, updated_at
     FROM menu 
     WHERE status = 'aktif' 
     ORDER BY updated_at DESC 
     LIMIT 5"
)->fetchAll();

// Recent gallery items
$galeri_terbaru = $pdo->query(
    "SELECT id, judul, foto_url, urutan, created_at
     FROM galeri 
     ORDER BY urutan ASC, created_at DESC 
     LIMIT 6"
)->fetchAll();

// Content pages overview
$konten_halaman = $pdo->query(
    "SELECT halaman, bagian, isi, updated_at
     FROM konten_halaman 
     ORDER BY updated_at DESC 
     LIMIT 8"
)->fetchAll();

// Recent activity logs
$log_terbaru = $pdo->query(
    "SELECT l.aksi, l.waktu, u.username
     FROM log_aktivitas l
     LEFT JOIN users u ON l.user_id = u.id
     ORDER BY l.waktu DESC
     LIMIT 10"
)->fetchAll();

// System info
$last_activity = $pdo->query(
    "SELECT waktu FROM log_aktivitas ORDER BY waktu DESC LIMIT 1"
)->fetchColumn();
?>

<!-- Enhanced Statistics Section -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="value"><?= $jml_menu ?></div>
            <i class="fas fa-utensils stat-icon"></i>
        </div>
        <div class="label">Menu Aktif</div>
        <div class="stat-trend">
            <?php $menu_total = $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn(); ?>
            <span class="trend-text"><?= $menu_total - $jml_menu ?> non-aktif</span>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #D4A373;">
        <div class="stat-header">
            <div class="value" style="color: #D4A373;"><?= $jml_galeri ?></div>
            <i class="fas fa-camera stat-icon" style="color: #D4A373;"></i>
        </div>
        <div class="label">Foto Galeri</div>
        <div class="stat-trend">
            <span class="trend-text"><?= $galeri_terbaru ? '+' . count($galeri_terbaru) : '0' ?> baru</span>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #2B2D42;">
        <div class="stat-header">
            <div class="value" style="color: #2B2D42;"><?= $jml_konten ?></div>
            <i class="fas fa-file-alt stat-icon" style="color: #2B2D42;"></i>
        </div>
        <div class="label">Entri Konten</div>
        <div class="stat-trend">
            <span class="trend-text"><?= $konten_halaman ? count($konten_halaman) : '0' ?> halaman</span>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #8B2635;">
        <div class="stat-header">
            <div class="value" style="color: #8B2635;"><?= $jml_users ?></div>
            <i class="fas fa-user-shield stat-icon" style="color: #8B2635;"></i>
        </div>
        <div class="label">Admin Users</div>
        <div class="stat-trend">
            <span class="trend-text">Sistem Aktif</span>
        </div>
    </div>
</div>

<!-- Quick Actions Panel -->
<div class="card">
    <div class="page-actions">
        <h2>Aksi Cepat</h2>
    </div>
    <div class="quick-actions">
        <a href="menu.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </a>
        <a href="galeri.php" class="btn btn-secondary">
            <i class="fas fa-camera"></i> Upload Galeri
        </a>
        <a href="konten.php" class="btn btn-secondary">
            <i class="fas fa-edit"></i> Edit Konten
        </a>
        <a href="akun.php" class="btn btn-secondary">
            <i class="fas fa-user-cog"></i> Kelola Akun
        </a>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Menu Categories Overview -->
    <div class="card">
        <div class="page-actions">
            <h2>Kategori Menu</h2>
        </div>
        <?php if (empty($menu_kategori)): ?>
            <p style="color: #666;">Belum ada data kategori menu.</p>
        <?php else: ?>
            <div class="category-list">
                <?php foreach ($menu_kategori as $kategori): ?>
                <div class="category-item">
                    <div class="category-name"><?= h($kategori['kategori']) ?></div>
                    <div class="category-count"><?= $kategori['jumlah'] ?> item</div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Menu Items -->
    <div class="card">
        <div class="page-actions">
            <h2>Menu Terbaru</h2>
        </div>
        <?php if (empty($menu_terbaru)): ?>
            <p style="color: #666;">Belum ada menu ditambahkan.</p>
        <?php else: ?>
            <div class="menu-preview">
                <?php foreach ($menu_terbaru as $menu): ?>
                <div class="menu-item">
                    <div class="menu-info">
                        <h3><?= h($menu['nama_menu']) ?></h3>
                        <div class="menu-meta">
                            <span class="badge"><?= h($menu['kategori']) ?></span>
                            <span class="price"><?= format_rupiah($menu['harga']) ?></span>
                        </div>
                        <div class="menu-date"><?= date('d M Y', strtotime($menu['updated_at'])) ?></div>
                    </div>
                    <div class="menu-actions">
                        <a href="menu.php?id=<?= $menu['id'] ?>&aksi=edit" class="btn btn-sm btn-primary">Edit</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Gallery Preview -->
    <div class="card">
        <div class="page-actions">
            <h2>Galeri Terbaru</h2>
        </div>
        <?php if (empty($galeri_terbaru)): ?>
            <p style="color: #666;">Belum ada foto galeri.</p>
        <?php else: ?>
            <div class="gallery-grid">
                <?php foreach ($galeri_terbaru as $foto): ?>
                <div class="gallery-item">
                    <img src="<?= h($foto['foto_url']) ?>" alt="<?= h($foto['judul']) ?>" class="gallery-thumb">
                    <div class="gallery-info">
                        <div class="gallery-title"><?= h($foto['judul']) ?></div>
                        <div class="gallery-actions">
                            <a href="galeri.php?id=<?= $foto['id'] ?>&aksi=edit" class="btn btn-sm btn-secondary">Edit</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content Management Overview -->
    <div class="card">
        <div class="page-actions">
            <h2>Pengelolaan Konten</h2>
        </div>
        <?php if (empty($konten_halaman)): ?>
            <p style="color: #666;">Belum ada konten halaman.</p>
        <?php else: ?>
            <div class="content-list">
                <?php foreach ($konten_halaman as $konten): ?>
                <div class="content-item">
                    <div class="content-info">
                        <h3><?= h($konten['halaman']) ?> - <?= h($konten['bagian']) ?></h3>
                        <div class="content-date"><?= date('d M Y H:i', strtotime($konten['updated_at'])) ?></div>
                    </div>
                    <div class="content-actions">
                        <a href="konten.php" class="btn btn-sm btn-secondary">Edit</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="page-actions">
            <h2>Aktivitas Terbaru</h2>
        </div>
        <?php if (empty($log_terbaru)): ?>
            <p style="color: #666;">Belum ada aktivitas tercatat.</p>
        <?php else: ?>
            <div class="activity-list">
                <?php foreach ($log_terbaru as $log): ?>
                <div class="activity-item">
                    <div class="activity-time"><?= h(date('d M Y H:i', strtotime($log['waktu']))) ?></div>
                    <div class="activity-user"><?= h($log['username'] ?? 'Sistem') ?></div>
                    <div class="activity-action"><?= h($log['aksi']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- System Status -->
    <div class="card">
        <div class="page-actions">
            <h2>Status Sistem</h2>
        </div>
        <div class="system-status">
            <div class="status-item">
                <div class="status-label">Status Database</div>
                <div class="status-value status-success"><i class="fas fa-check-circle"></i> Aktif</div>
            </div>
            <div class="status-item">
                <div class="status-label">Aktivitas Terakhir</div>
                <div class="status-value"><?= $last_activity ? date('d M Y H:i', strtotime($last_activity)) : 'Tidak ada' ?></div>
            </div>
            <div class="status-item">
                <div class="status-label">Total Konten</div>
                <div class="status-value"><?= $jml_konten ?> halaman</div>
            </div>
            <div class="status-item">
                <div class="status-label">Menu Tersedia</div>
                <div class="status-value"><?= $jml_menu ?> item</div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
