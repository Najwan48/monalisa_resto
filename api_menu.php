<?php
require_once 'includes/db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');

if (isset($_GET['count_only'])) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM menu WHERE status = 'aktif'");
    echo json_encode(['count' => (int)$stmt->fetchColumn()]);
    exit;
}

$stmt = $pdo->query("SELECT id, nama_menu, asal_daerah, deskripsi_singkat, harga, foto_url, kategori FROM menu WHERE status = 'aktif' ORDER BY RAND() LIMIT 5");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
