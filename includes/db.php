<?php
// includes/db.php

$host = '127.0.0.1';
$db   = 'monalisa_resto';
$user = 'root'; // Sesuaikan jika ada user khusus
$pass = '';     // Sesuaikan password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Penting untuk keamanan (prepared statements sesungguhnya)
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Pada production, jangan tampilkan pesan error detail
    error_log($e->getMessage());
    die('Koneksi database gagal. Silakan coba beberapa saat lagi.');
}
?>
