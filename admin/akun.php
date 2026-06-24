<?php
$page_title = 'Manajemen Akun';
require_once '../includes/admin_header.php';
require_once '../includes/validation.php';

$pesan      = '';
$tipe_pesan = '';
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $pesan      = "Validasi keamanan gagal.";
        $tipe_pesan = 'danger';
    } else {
        $username_baru = validate_input($_POST['username'] ?? '', 'string', 50);
        $password_lama = $_POST['password_lama'] ?? '';
        $password_baru = $_POST['password_baru'] ?? '';
        $konfirmasi    = $_POST['konfirmasi'] ?? '';

        if ($username_baru && $password_lama && $password_baru && $konfirmasi) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
            $user = $stmt->fetch();

            if ($user && password_verify($password_lama, $user['password_hash'])) {
                if (strlen($password_baru) >= 8 && $password_baru === $konfirmasi) {
                    $hash = password_hash($password_baru, PASSWORD_BCRYPT);
                    $update = $pdo->prepare("UPDATE users SET username = ?, password_hash = ? WHERE id = ?");
                    if ($update->execute([$username_baru, $hash, $_SESSION['admin_id']])) {
                        $_SESSION['admin_user'] = $username_baru;
                        log_aktivitas($pdo, $_SESSION['admin_id'], "Ubah kredensial akun admin");
                        $pesan      = "Informasi akun berhasil diperbarui.";
                        $tipe_pesan = 'success';
                    } else {
                        $pesan      = "Gagal memperbarui akun.";
                        $tipe_pesan = 'danger';
                    }
                } else {
                    $pesan      = "Konfirmasi kata sandi baru tidak cocok atau terlalu pendek (min 8 karakter).";
                    $tipe_pesan = 'danger';
                }
            } else {
                $pesan      = "Kata sandi lama salah.";
                $tipe_pesan = 'danger';
            }
        } else {
            $pesan      = "Silakan lengkapi semua bidang.";
            $tipe_pesan = 'danger';
        }
    }
}
?>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= escapeHtml($pesan) ?></div>
<?php endif; ?>

<div class="page-actions">
    <h2>Pengaturan Akun</h2>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <div class="form-group">
            <label for="username">Username Baru</label>
            <input type="text" id="username" name="username" class="form-control" value="<?= escapeHtml($_SESSION['admin_user']) ?>" required>
        </div>
        <div class="form-group">
            <label for="password_lama">Kata Sandi Lama</label>
            <input type="password" id="password_lama" name="password_lama" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_baru">Kata Sandi Baru</label>
            <input type="password" id="password_baru" name="password_baru" class="form-control" required>
        </div>
        <div class="form-group" style="margin-bottom: 2rem;">
            <label for="konfirmasi">Konfirmasi Kata Sandi Baru</label>
            <input type="password" id="konfirmasi" name="konfirmasi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui Akun</button>
    </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
