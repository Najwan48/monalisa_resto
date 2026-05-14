<?php
$page_title = 'Log Aktivitas';
require_once '../includes/admin_header.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 50;
$offset = ($page - 1) * $limit;

$total_logs = $pdo->query("SELECT COUNT(*) FROM log_aktivitas")->fetchColumn();
$total_pages = ceil($total_logs / $limit);

$logs = $pdo->prepare(
    "SELECT l.aksi, l.waktu, u.username
     FROM log_aktivitas l
     LEFT JOIN users u ON l.user_id = u.id
     ORDER BY l.waktu DESC
     LIMIT ? OFFSET ?"
);
$logs->bindValue(1, $limit, PDO::PARAM_INT);
$logs->bindValue(2, $offset, PDO::PARAM_INT);
$logs->execute();
$log_data = $logs->fetchAll();
?>

<div class="page-actions">
    <h2>Riwayat Aktivitas Sistem</h2>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <?php if (empty($log_data)): ?>
        <p style="padding: 1.5rem; color: #666;">Belum ada aktivitas tercatat.</p>
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
                <?php foreach ($log_data as $log): ?>
                <tr>
                    <td data-label="Waktu"><?= h(date('d M Y H:i:s', strtotime($log['waktu']))) ?></td>
                    <td data-label="Pengguna"><?= h($log['username'] ?? 'Sistem') ?></td>
                    <td data-label="Aksi"><?= h($log['aksi']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($total_pages > 1): ?>
        <div style="padding: 1rem 1.5rem; display: flex; gap: 0.5rem;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="log.php?page=<?= $i ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
