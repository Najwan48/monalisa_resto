<?php
require_once 'includes/db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak' AND bagian IN ('telepon', 'whatsapp')");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$phone = $rows['telepon'] ?? '';
$wa_raw = $rows['whatsapp'] ?? '';
if (empty($wa_raw)) {
    $wa_raw = $phone;
}

$whatsapp = preg_replace('/[^0-9]/', '', $wa_raw);
if (strpos($whatsapp, '0') === 0) {
    $whatsapp = '62' . substr($whatsapp, 1);
}

echo json_encode([
    'telepon' => $phone,
    'whatsapp' => $whatsapp
]);
