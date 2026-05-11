<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? h($page_title) . ' - ' : '' ?>Admin Panel - Restaurant Monalisa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B2635;
            --primary-dark: #6a1a26;
            --dark: #2B2D42;
            --sidebar-width: 260px;
            --bg: #F0F2F5;
            --white: #ffffff;
            --text: #333333;
            --border: #e0e0e0;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --radius: 16px;
            --radius-sm: 10px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--dark);
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand h2 {
            font-size: 1.2rem;
            color: #D4A373;
            margin: 0;
        }
        .sidebar-brand p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin-top: 0.25rem;
        }
        .sidebar-nav { flex: 1; padding: 1rem 0; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.08);
            color: white;
            border-left-color: #D4A373;
        }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-footer a {
            display: block;
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            padding: 0.5rem 0;
        }
        .sidebar-footer a:hover { color: #dc3545; }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: var(--white);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar h1 { font-size: 1.5rem; color: var(--dark); }
        .topbar-user { font-size: 0.9rem; color: #666; }
        .topbar-user strong { color: var(--primary); }

        .content-area { padding: 2rem; flex: 1; }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            border-left: 4px solid var(--primary);
        }
        .stat-card .value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        .stat-card .label { color: #666; font-size: 0.9rem; margin-top: 0.25rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-warning { background: var(--warning); color: #333; }
        .btn-warning:hover { background: #e0a800; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }

        table { width: 100%; border-collapse: collapse; }
        table th {
            background: var(--dark);
            color: white;
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.9rem;
            font-weight: 600;
        }
        table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
            vertical-align: middle;
        }
        table tr:hover td { background: #f9f9f9; }

        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            color: var(--dark);
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        select.form-control { background-color: white; }

        .alert {
            padding: 0.9rem 1.2rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }

        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .page-actions h2 { font-size: 1.2rem; color: var(--dark); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <h2>Monalisa Admin</h2>
        <p>Panel Pengelola Konten</p>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="menu.php" class="<?= $current_page === 'menu.php' ? 'active' : '' ?>">Manajemen Menu</a>
        <a href="galeri.php" class="<?= $current_page === 'galeri.php' ? 'active' : '' ?>">Manajemen Galeri</a>
        <a href="konten.php" class="<?= $current_page === 'konten.php' ? 'active' : '' ?>">Manajemen Konten</a>
        <a href="akun.php" class="<?= $current_page === 'akun.php' ? 'active' : '' ?>">Manajemen Akun</a>
        <a href="log.php" class="<?= $current_page === 'log.php' ? 'active' : '' ?>">Log Aktivitas</a>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php">Keluar</a>
    </div>
</aside>

<div class="main-content">
    <div class="topbar">
        <h1><?= isset($page_title) ? h($page_title) : 'Dashboard' ?></h1>
        <span class="topbar-user">Login sebagai: <strong><?= h($_SESSION['admin_user']) ?></strong></span>
    </div>
    <div class="content-area">
