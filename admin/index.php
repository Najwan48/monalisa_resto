<?php
// admin/index.php
session_start();

// Cek apakah sudah login (sederhana)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Jika belum login dan ada file login.php
    // header("Location: login.php");
    // exit;
    // Sementara, untuk testing kita tampilkan pesan
    die("Akses ditolak. Silakan login terlebih dahulu. (Buat admin/login.php untuk form login)");
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

// Ambil summary untuk dashboard
$jml_menu = $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn();
$jml_galeri = $pdo->query("SELECT COUNT(*) FROM galeri")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Restaurant Monalisa</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; display: flex; background: #f4f4f4; }
        .sidebar { width: 250px; background: #2B2D42; color: white; height: 100vh; padding: 20px 0; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 15px 20px; border-bottom: 1px solid #3f4156; }
        .sidebar a:hover { background: #8B2635; }
        .content { flex-grow: 1; padding: 30px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: inline-block; width: 200px; margin-right: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Monalisa Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="menu.php">Manajemen Menu</a>
    <a href="galeri.php">Manajemen Galeri</a>
    <a href="konten.php">Manajemen Konten</a>
    <a href="akun.php">Manajemen Akun</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <h1>Dashboard</h1>
    <p>Selamat datang di Panel Admin Restaurant Monalisa.</p>
    
    <div style="margin-top: 30px;">
        <div class="card">
            <h3>Total Menu</h3>
            <p style="font-size: 24px; font-weight: bold; color: #8B2635;"><?= $jml_menu ?></p>
        </div>
        <div class="card">
            <h3>Foto Galeri</h3>
            <p style="font-size: 24px; font-weight: bold; color: #8B2635;"><?= $jml_galeri ?></p>
        </div>
    </div>
</div>

</body>
</html>
