<?php
session_start();
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$exists = $stmt->fetchColumn() > 0;

if ($exists) {
    header("Location: admin/login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $hash]);
        header("Location: admin/login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Monalisa Resto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B6914;
            --bg-warm: #FDF8F0;
            --surface: #FFFFFF;
            --charcoal: #2C2C2C;
            --text: #4A4A4A;
            --border: #E8E0D4;
            --radius: 16px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-warm);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .setup-card {
            background: var(--surface);
            padding: 3.5rem;
            border-radius: var(--radius);
            box-shadow: 0 40px 100px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 440px;
            border: 1px solid var(--border);
        }
        .setup-brand {
            text-align: center;
            margin-bottom: 3rem;
        }
        .setup-brand h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            color: var(--charcoal);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .setup-brand p {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--primary);
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--charcoal);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            color: var(--charcoal);
            background: var(--bg-warm);
            transition: border-color var(--transition);
            outline: none;
        }
        .form-control:focus {
            border-color: var(--primary);
        }
        .btn-setup {
            width: 100%;
            padding: 1rem;
            background: var(--charcoal);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition);
            margin-top: 1rem;
        }
        .btn-setup:hover {
            background: var(--primary);
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
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="setup-brand">
            <h2>Monalisa Resto</h2>
            <p>Setup Akun Admin</p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Kata Sandi</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi password" required minlength="8">
            </div>
            <button type="submit" class="btn-setup">Buat Akun Admin</button>
        </form>
    </div>
</body>
</html>
