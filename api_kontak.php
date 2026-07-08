<?php
require_once 'includes/db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store');

$stmt = $pdo->prepare("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak'");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$phone = $rows['telepon'] ?? '';
$wa_raw = $rows['whatsapp'] ?? '';

$whatsapp = '';
if (!empty($wa_raw)) {
    $whatsapp = preg_replace('/[^0-9]/', '', $wa_raw);
    if (strpos($whatsapp, '0') === 0) {
        $whatsapp = '62' . substr($whatsapp, 1);
    }
}

echo json_encode([
    'telepon'         => $phone,
    'whatsapp'        => $whatsapp,
    'alamat'          => $rows['alamat'] ?? '',
    'jam_operasional' => $rows['jam_operasional'] ?? '',
    'maps_url'        => $rows['maps_url'] ?? '',
    'maps_nama'       => $rows['maps_nama'] ?? '',
    'maps_lat'        => (float)($rows['maps_lat'] ?? -6.6263927),
    'maps_lng'        => (float)($rows['maps_lng'] ?? 106.8214916),
]);
