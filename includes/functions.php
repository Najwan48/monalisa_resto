<?php

function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

function log_aktivitas($pdo, $user_id, $aksi) {
    $stmt = $pdo->prepare("INSERT INTO log_aktivitas (user_id, aksi) VALUES (?, ?)");
    $stmt->execute([$user_id, $aksi]);
}
?>
