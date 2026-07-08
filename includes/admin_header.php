<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', 1);
}
session_name('monalisa_admin');
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
set_no_cache_headers();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['last_activity'] = time();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? escapeHtml($page_title) . ' — ' : '' ?>Admin · Monalisa Resto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        let inactivityTime = function () {
            let time;
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;

            function logout() {
                window.location.href = 'logout.php';
            }
            function resetTimer() {
                clearTimeout(time);
                time = setTimeout(logout, 1800000);
            }
        };
        window.onload = inactivityTime;

        setInterval(function () {
            fetch('heartbeat.php').catch(function () {});
        }, 300000);
    </script>
    <style>
        :root {
            --primary:        #A67C52;
            --primary-dark:   #8B6543;
            --primary-pale:   #F5EDD6;
            --sidebar-bg:     #1C1917;
            --sidebar-hover:  rgba(255,255,255,0.06);
            --sidebar-active: rgba(166,124,82,0.15);
            --sidebar-width:  260px;
            --bg:             #F8F7F4;
            --surface:        #FFFFFF;
            --text:           #1C1C1C;
            --text-muted:     #767676;
            --border:         rgba(0,0,0,0.06);
            --border-mid:     rgba(0,0,0,0.12);
            --success:        #16A34A;
            --success-pale:   #DCFCE7;
            --danger:         #DC2626;
            --danger-pale:    #FEE2E2;
            --warning:        #D97706;
            --warning-pale:   #FEF3C7;
            --radius-xs:      2px;
            --radius-sm:      6px;
            --radius:         10px;
            --radius-lg:      16px;
            --shadow-xs:      0 1px 3px rgba(0,0,0,0.05);
            --shadow-sm:      0 4px 12px rgba(0,0,0,0.06);
            --shadow:         0 8px 24px rgba(0,0,0,0.08);
            --transition:     0.2s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        a { text-decoration: none; color: inherit; }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: rgba(255,255,255,0.7);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow: hidden;
            overscroll-behavior: contain;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 200px;
            background: linear-gradient(180deg, rgba(166,124,82,0.08) 0%, transparent 100%);
            pointer-events: none;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem 1.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }

        .sidebar-brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 0.2rem;
        }

        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1C1917;
        }

        ::-webkit-scrollbar-thumb {
            background: #A67C52;
            border-radius: var(--radius-sm);
            border: 2px solid #1C1917;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #8B6543;
        }


        .sidebar-section-label {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0 1rem;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255,255,255,0.55);
            transition: all var(--transition);
            border-left: 2px solid transparent;
            margin: 0 0.5rem;
            border-radius: var(--radius-sm);
        }

        .sidebar-nav a i {
            width: 18px;
            text-align: center;
            font-size: 0.875rem;
            opacity: 0.7;
            flex-shrink: 0;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            color: rgba(255,255,255,0.9);
        }

        .sidebar-nav a.active {
            background: var(--sidebar-active);
            color: var(--primary);
            border-left-color: var(--primary);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            margin-left: 0;
            padding-left: calc(1.5rem - 2px);
        }

        .sidebar-nav a.active i { opacity: 1; }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.4);
            transition: color var(--transition);
        }

        .sidebar-footer a:hover { color: var(--danger); }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: 100vh;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .topbar {
            background: var(--surface);
            padding: 0 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .topbar-avatar {
            width: 34px;
            height: 34px;
            background: var(--primary-pale);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .content-area {
            padding: 2rem;
            flex: 1;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .page-header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.2;
        }

        .page-header p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .page-actions h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            color: var(--text);
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-xs);
            border: 1px solid var(--border);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: box-shadow var(--transition);
        }

        .card:hover { box-shadow: var(--shadow-sm); }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-xs);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .stat-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-card .value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        .stat-card .label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            display: block;
        }

        .stat-trend { font-size: 0.78rem; color: var(--text-muted); }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all var(--transition);
            white-space: nowrap;
            font-family: 'Outfit', sans-serif;
        }

        .btn-primary   { background: var(--primary); color: #fff; border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-success   { background: var(--success); color: #fff; border-color: var(--success); }
        .btn-success:hover { filter: brightness(0.9); }
        .btn-danger    { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-danger:hover { filter: brightness(0.9); }
        .btn-warning   { background: var(--warning); color: #fff; border-color: var(--warning); }
        .btn-warning:hover { filter: brightness(0.9); }
        .btn-secondary { background: #F1F5F9; color: var(--text); border-color: var(--border-mid); }
        .btn-secondary:hover { background: #E2E8F0; }
        .btn-ghost-danger { background: transparent; color: var(--danger); border-color: var(--danger); }
        .btn-ghost-danger:hover { background: var(--danger); color: #fff; }
        .btn-sm { padding: 5px 12px; font-size: 0.77rem; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        table { width: 100%; border-collapse: collapse; }

        table thead tr { border-bottom: 2px solid var(--border-mid); }

        table th {
            padding: 0.85rem 1rem;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
            vertical-align: middle;
            color: var(--text);
        }

        table tbody tr:last-child td { border-bottom: none; }
        table tbody tr:hover td { background: #FAFAF8; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-mid);
            border-radius: var(--radius-sm);
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            background: var(--surface);
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(166,124,82,0.12);
        }

        select.form-control { background: var(--surface); }
        textarea.form-control { resize: vertical; min-height: 120px; }

        .alert {
            padding: 0.9rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success { background: var(--success-pale); color: var(--success); border: 1px solid #86EFAC; }
        .alert-danger  { background: var(--danger-pale);  color: var(--danger);  border: 1px solid #FCA5A5; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .badge-success   { background: var(--success-pale); color: var(--success); }
        .badge-secondary { background: #F1F5F9; color: var(--text-muted); }
        .badge-warning   { background: var(--warning-pale); color: var(--warning); }

        .category-list { display: flex; flex-direction: column; gap: 0.6rem; }

        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--bg);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--primary);
            transition: background var(--transition);
        }

        .category-item:hover { background: var(--primary-pale); }
        .category-name { font-size: 0.875rem; font-weight: 600; color: var(--text); }

        .category-count {
            background: var(--primary-pale);
            color: var(--primary);
            padding: 2px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .menu-preview { display: flex; flex-direction: column; gap: 0.85rem; }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 1rem;
            background: var(--bg);
            border-radius: var(--radius-sm);
            transition: background var(--transition);
        }

        .menu-item:hover { background: var(--primary-pale); }

        .menu-info h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            margin: 0 0 0.2rem;
        }

        .menu-meta { display: flex; gap: 0.6rem; align-items: center; }
        .menu-meta .price { font-weight: 700; color: var(--primary); font-size: 0.85rem; }
        .menu-date { font-size: 0.75rem; color: var(--text-muted); }
        .menu-actions { display: flex; gap: 0.4rem; }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .gallery-item {
            background: var(--surface);
            border-radius: var(--radius-sm);
            overflow: hidden;
            border: 1px solid var(--border-mid);
            transition: all var(--transition);
        }

        .gallery-item:hover { transform: translateY(-3px); box-shadow: var(--shadow-sm); border-color: var(--primary); }

        .gallery-thumb {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background: #F1F5F9;
            display: block;
        }

        .gallery-info { padding: 0.75rem; border-top: 1px solid var(--border); }

        .gallery-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.5rem;
        }

        .gallery-actions { display: flex; justify-content: flex-end; }

        .content-list { display: flex; flex-direction: column; gap: 0.6rem; }

        .content-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 1rem;
            background: var(--bg);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--success);
        }

        .content-info h3 { font-size: 0.875rem; font-weight: 600; color: var(--text); margin: 0 0 0.2rem; }
        .content-date { font-size: 0.75rem; color: var(--text-muted); }
        .content-actions { display: flex; gap: 0.4rem; }

        .stat-trend .trend-text { font-weight: 500; }

        .quick-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .system-status { display: flex; flex-direction: column; gap: 0.75rem; }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--bg);
            border-radius: var(--radius-sm);
        }

        .status-label { font-size: 0.875rem; font-weight: 600; }
        .status-value { font-size: 0.875rem; font-weight: 700; }
        .status-success { color: var(--success); }
        .status-warning { color: var(--warning); }
        .status-danger  { color: var(--danger); }

        .activity-list { display: flex; flex-direction: column; gap: 0.6rem; }

        .activity-item {
            display: grid;
            grid-template-columns: auto 1fr 1fr;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: var(--bg);
            border-radius: var(--radius-sm);
            align-items: center;
        }

        .activity-time { font-size: 0.75rem; color: var(--text-muted); white-space: nowrap; }
        .activity-user { font-size: 0.875rem; font-weight: 600; }
        .activity-action { font-size: 0.825rem; color: var(--text-muted); }

        @media (max-width: 1024px) {
            .form-grid { grid-template-columns: 1fr; }
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); }
            .activity-item { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
            .topbar-user span { display: none; }

            .galeri-form-grid {
                grid-template-columns: 1fr !important;
            }

            .topbar-breadcrumb span:first-child,
            .topbar-breadcrumb i {
                display: none !important;
            }

            .hide-mobile {
                display: none !important;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 199;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 18px;
            position: relative;
            border-radius: var(--radius-sm);
            transition: background var(--transition);
        }

        .hamburger-btn::before {
            content: '';
            position: absolute;
            inset: -10px;
        }

        .hamburger-btn:hover { background: var(--bg); }

        .hamburger-btn .bar {
            display: block;
            width: 100%;
            height: 2px;
            background: var(--text);
            border-radius: 1px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
            transform-origin: center;
        }

        .hamburger-btn.is-active .bar:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger-btn.is-active .bar:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger-btn.is-active .bar:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                z-index: 200;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .hamburger-btn {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .topbar {
                padding: 0 1.25rem;
            }

            .content-area {
                padding: 1.25rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .page-actions {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
                width: 100%;
            }

            .page-actions > div:last-child {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .page-actions form {
                width: 100%;
                display: flex;
                gap: 0.5rem;
            }

            .page-actions form input[type="text"] {
                flex: 1;
                width: auto !important;
            }

            .page-actions a.btn {
                width: 100%;
                justify-content: center;
            }

            .stats-grid { grid-template-columns: repeat(2, 1fr); }

            .card { padding: 1rem; }

            table thead { display: none; }

            table, table tbody {
                display: block;
                width: 100%;
            }

            table tr {
                display: grid;
                grid-template-columns: 88px 1fr;
                grid-template-rows: auto auto auto auto;
                column-gap: 0.85rem;
                border: 1px solid var(--border);
                border-radius: var(--radius);
                margin-bottom: 0.75rem;
                padding: 0.85rem;
                background: var(--surface);
                box-shadow: var(--shadow-xs);
                align-items: start;
            }

            table td {
                display: block;
                border-bottom: none;
                padding: 0;
                font-size: 0.875rem;
            }

            table td::before {
                content: attr(data-label);
                display: block;
                font-size: 0.62rem;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 0.2rem;
            }

            table td[data-label="Foto"] {
                grid-column: 1;
                grid-row: 1 / 5;
                display: flex;
                align-items: flex-start;
            }

            table td[data-label="Foto"]::before { display: none; }

            table td[data-label="Foto"] img,
            table td[data-label="Preview"] img {
                width: 80px;
                height: 80px;
                object-fit: cover;
                border-radius: var(--radius-sm);
                display: block;
            }

            table td[data-label="Preview"] {
                grid-column: 1;
                grid-row: 1 / 4;
            }

            table td[data-label="Preview"] > div {
                width: 80px !important;
                height: 80px !important;
            }

            table td[data-label="Nama Menu"],
            table td[data-label="Judul"] {
                grid-column: 2;
                grid-row: 1;
                padding-top: 0;
            }

            table td[data-label="Kategori"],
            table td[data-label="Urutan"] {
                grid-column: 2;
                grid-row: 2;
                margin-top: 0.35rem;
            }

            table td[data-label="Harga"] {
                grid-column: 2;
                grid-row: 3;
                margin-top: 0.35rem;
            }

            table td[data-label="Status"] {
                grid-column: 2;
                grid-row: 4;
                margin-top: 0.35rem;
            }

            table td[data-label="Aksi"] {
                grid-column: 1 / 3;
                grid-row: 5;
                border-top: 1px solid var(--border);
                padding-top: 0.75rem;
                margin-top: 0.75rem;
                display: flex;
                justify-content: flex-end;
                gap: 0.5rem;
                align-items: center;
            }

            table td[data-label="Aksi"]::before { display: none; }

            .activity-item {
                grid-template-columns: auto 1fr;
                grid-template-rows: auto auto;
            }

            .activity-action {
                grid-column: 2;
            }

            .galeri-form-grid {
                grid-template-columns: 1fr 1fr !important;
                grid-template-rows: auto auto;
            }

            .galeri-form-grid > div:nth-child(1) {
                grid-column: 1 / 3;
            }

            .galeri-form-grid > button {
                grid-column: 1 / 3;
                height: auto;
                padding: 12px !important;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
            .topbar-user span { display: none; }

            .galeri-form-grid {
                grid-template-columns: 1fr !important;
            }

            .topbar-breadcrumb span:first-child,
            .topbar-breadcrumb i {
                display: none !important;
            }

            .hide-mobile {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="sidebar-brand-name">Monalisa Resto</span>
        <span class="sidebar-brand-sub">Panel Administrasi</span>
    </div>

    <div class="sidebar-section-label">Utama</div>
    <nav class="sidebar-nav">
        <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">
            <i class="ri-dashboard-line"></i> Dashboard
        </a>
        <a href="menu.php" class="<?= $current_page === 'menu.php' ? 'active' : '' ?>">
            <i class="ri-restaurant-line"></i> Manajemen Menu
        </a>
        <a href="galeri.php" class="<?= $current_page === 'galeri.php' ? 'active' : '' ?>">
            <i class="ri-image-line"></i> Manajemen Galeri
        </a>
        <a href="konten.php" class="<?= $current_page === 'konten.php' ? 'active' : '' ?>">
            <i class="ri-file-text-line"></i> Manajemen Konten
        </a>
        <div class="sidebar-section-label" style="margin-top:0.5rem;">Sistem</div>
        <a href="akun.php" class="<?= $current_page === 'akun.php' ? 'active' : '' ?>">
            <i class="ri-shield-user-line"></i> Manajemen Akun
        </a>
        <a href="log.php" class="<?= $current_page === 'log.php' ? 'active' : '' ?>">
            <i class="ri-history-line"></i> Log Aktivitas
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php"><i class="ri-logout-box-line"></i> Keluar</a>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="main-content">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle Sidebar">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
            <div class="topbar-breadcrumb">
                <span>Admin</span>
                <i class="ri-arrow-right-s-line" style="font-size:0.6rem;"></i>
                <span class="topbar-title"><?= isset($page_title) ? escapeHtml($page_title) : 'Dashboard' ?></span>
            </div>
        </div>
        <div class="topbar-right">
            <a href="../index.php" target="_blank" style="font-size:0.8rem; color: var(--text-muted); display:flex; align-items:center; gap:0.4rem;">
                <i class="ri-external-link-line" style="font-size:0.7rem;"></i> <span class="hide-mobile">Lihat Website</span>
            </a>
            <div class="topbar-user">
                <div class="topbar-avatar">
                    <?= strtoupper(substr($_SESSION['admin_user'] ?? 'A', 0, 1)) ?>
                </div>
                <span style="font-weight: 600;"><?= escapeHtml($_SESSION['admin_user'] ?? '') ?></span>
            </div>
        </div>
    </header>
    <div class="content-area">
