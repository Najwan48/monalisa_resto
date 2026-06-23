<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', 1);
}
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';
$ip = $_SERVER['REMOTE_ADDR'];

// Rate limiting: check failed attempts in last 30 minutes
$stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND attempt_time > NOW() - INTERVAL 30 MINUTE");
$stmt->execute([$ip]);
$attempts = $stmt->fetchColumn();

if ($attempts >= 5) {
    $error = "Terlalu banyak percobaan gagal. Silakan tunggu 30 menit.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Validasi form gagal. Silakan muat ulang halaman.";
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Success: clear attempts
                $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
                $stmt->execute([$ip]);

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $user['id'];
                $_SESSION['admin_user']      = $user['username'];
                log_aktivitas($pdo, $user['id'], "Login ke panel admin");
                header("Location: index.php");
                exit;
            } else {
                // Failure: record attempt
                $stmt = $pdo->prepare("INSERT INTO login_attempts (ip_address) VALUES (?)");
                $stmt->execute([$ip]);
                $error = "Username atau password salah.";
            }
        } else {
            $error = "Silakan isi semua kolom.";
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin · Monalisa Resto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #8B6914;
            --primary-dark:  #6A5010;
            --charcoal:      #1C1C1C;
            --text:          #2C2C2C;
            --text-muted:    #767676;
            --bg-warm:       #F4F1EA;
            --surface:       #FFFFFF;
            --border:        rgba(0, 0, 0, 0.08);
            --radius:        12px;
            --transition:    0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-warm);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 70% 20%, rgba(139, 105, 20, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at 10% 80%, rgba(139, 105, 20, 0.05) 0%, transparent 40%);
            z-index: -1;
        }

        .login-card {
            background: var(--surface);
            padding: 3.5rem;
            border-radius: var(--radius);
            box-shadow: 0 40px 100px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 440px;
            border: 1px solid var(--border);
            opacity: 0;
            transform: translateY(20px);
            animation: revealUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes revealUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .login-brand {
            text-align: center;
            margin-bottom: 3rem;
        }

        .login-brand h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            color: var(--charcoal);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .login-brand p {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--primary);
            font-weight: 600;
        }

        .form-group { margin-bottom: 1.5rem; }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: #FAFAF8;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            color: var(--charcoal);
            transition: all var(--transition);
        }

        .form-control:focus {
            outline: none;
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(139, 105, 20, 0.08);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background-color: var(--charcoal);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all var(--transition);
            margin-top: 1rem;
        }

        .btn-login:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 105, 20, 0.2);
        }

        .error-msg {
            background-color: #FEF2F2;
            color: #DC2626;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 0.875rem;
            border: 1px solid #FEE2E2;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-brand">
        <h2>Monalisa Resto</h2>
        <p>Panel Administrasi</p>
    </div>

    <?php if ($error): ?>
        <div class="error-msg"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <div class="form-group">
            <label for="username">Nama Pengguna</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <button type="submit" class="btn-login">Masuk ke Dashboard</button>
    </form>
</div>

</body>
</html>
