<?php
require_once 'includes/db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');

$halaman = $_GET['halaman'] ?? 'beranda';
$allowed = ['beranda', 'tentang_kami'];
if (!in_array($halaman, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid halaman']);
    exit;
}

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = ?");
$stmt->execute([$halaman]);
echo json_encode($stmt->fetchAll(PDO::FETCH_KEY_PAIR));
