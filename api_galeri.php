<?php
require_once 'includes/db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');

$stmt = $pdo->query("SELECT judul, foto_url FROM galeri ORDER BY urutan ASC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
