<?php
$page_title = 'Dashboard';
require_once '../includes/admin_header.php';

$jml_menu   = $pdo->query("SELECT COUNT(*) FROM menu WHERE status = 'aktif'")->fetchColumn();
$jml_galeri = $pdo->query("SELECT COUNT(*) FROM galeri")->fetchColumn();
$jml_konten = $pdo->query("SELECT COUNT(*) FROM konten_halaman")->fetchColumn();

$log_terbaru = $pdo->query(
    "SELECT l.aksi, l.waktu, u.username
     FROM log_aktivitas l
     LEFT JOIN users u ON l.user_id = u.id
     ORDER BY l.waktu DESC
     LIMIT 10"
)->fetchAll();
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="value"><?= $jml_menu ?></div>
        <div class="label">Menu Aktif</div>
    </div>
    <div class="stat-card" style="border-left-color: #D4A373;">
        <div class="value" style="color: #D4A373;"><?= $jml_galeri ?></div>
        <div class="label">Foto Galeri</div>
    </div>
    <div class="stat-card" style="border-left-color: #2B2D42;">
        <div class="value" style="color: #2B2D42;"><?= $jml_konten ?></div>
        <div class="label">Entri Konten</div>
    </div>
</div>

<div class="card">
    <div class="page-actions">
        <h2>Aktivitas Terbaru</h2>
    </div>
    <?php if (empty($log_terbaru)): ?>
        <p style="color: #666;">Belum ada aktivitas tercatat.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($log_terbaru as $log): ?>
                <tr>
                    <td><?= h(date('d M Y H:i', strtotime($log['waktu']))) ?></td>
                    <td><?= h($log['username'] ?? 'Sistem') ?></td>
                    <td><?= h($log['aksi']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
