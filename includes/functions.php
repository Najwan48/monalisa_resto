<?php

function escapeHtml($string) {
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

function get_image_url($path, $is_admin = false) {
    if (empty($path)) return 'https://via.placeholder.com/300x200?text=No+Image';
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return ($is_admin ? '../' : '') . $path;
}

function process_and_save_image($tmp_path, $target_path, $max_width = 1200) {
    $info = getimagesize($tmp_path);
    if (!$info) return false;

    $mime = $info['mime'];
    switch ($mime) {
        case 'image/jpeg': $img = imagecreatefromjpeg($tmp_path); break;
        case 'image/png': $img = imagecreatefrompng($tmp_path); break;
        case 'image/webp': $img = imagecreatefromwebp($tmp_path); break;
        default: return false;
    }

    $width = $info[0];
    $height = $info[1];

    if ($width > $max_width) {
        $ratio = $max_width / $width;
        $new_width = $max_width;
        $new_height = $height * $ratio;
        $new_img = imagecreatetruecolor($new_width, $new_height);

        if ($mime == 'image/png') {
            imagealphablending($new_img, false);
            imagesavealpha($new_img, true);
        }

        imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $img = $new_img;
    }

    $target_path = preg_replace('/\.[^.]+$/', '.webp', $target_path);
    imagewebp($img, $target_path, 80);
    imagedestroy($img);
    return $target_path;
}

function set_no_cache_headers() {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');
}
?>
