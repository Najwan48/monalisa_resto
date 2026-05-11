<?php
$page_title = 'Manajemen Konten';
require_once '../includes/admin_header.php';

$pesan      = '';
$tipe_pesan = '';
$csrf_token = generate_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $pesan      = "Validasi keamanan gagal.";
        $tipe_pesan = 'danger';
    } else {
        $halaman = $_POST['halaman'] ?? '';
        $bagian  = $_POST['bagian'] ?? '';
        $isi     = $_POST['isi'] ?? '';

        if (!empty($halaman) && !empty($bagian)) {
            $stmt = $pdo->prepare(
                "INSERT INTO konten_halaman (halaman, bagian, isi) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE isi = VALUES(isi)"
            );
            $stmt->execute([$halaman, $bagian, $isi]);
            log_aktivitas($pdo, $_SESSION['admin_id'], "Edit konten: halaman=$halaman, bagian=$bagian");
            $pesan      = "Konten halaman '$halaman' - bagian '$bagian' berhasil disimpan.";
            $tipe_pesan = 'success';
        }
    }
}

$semua_konten = $pdo->query(
    "SELECT halaman, bagian, isi, updated_at FROM konten_halaman ORDER BY halaman, bagian"
)->fetchAll();

$konten_per_halaman = [];
foreach ($semua_konten as $k) {
    $konten_per_halaman[$k['halaman']][$k['bagian']] = $k;
}

$label_halaman = [
    'beranda'      => 'Beranda',
    'tentang_kami' => 'Tentang Kami',
    'kontak'       => 'Kontak & Lokasi',
];

$label_bagian = [
    'tagline'         => 'Tagline Hero',
    'pengantar'       => 'Teks Pengantar',
    'sejarah'         => 'Sejarah Restoran',
    'visi'            => 'Visi Restoran',
    'alamat'          => 'Alamat',
    'telepon'         => 'Telepon',
    'jam_operasional' => 'Jam Operasional',
];
?>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= h($pesan) ?></div>
<?php endif; ?>

<?php foreach ($konten_per_halaman as $halaman => $bagian_list): ?>
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.5rem; color: var(--primary); border-bottom: 2px solid #f0f0f0; padding-bottom: 0.75rem;">
        Halaman: <?= h($label_halaman[$halaman] ?? ucfirst($halaman)) ?>
    </h3>
    <?php foreach ($bagian_list as $bagian => $data): ?>
    <form method="POST" style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0;">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="halaman" value="<?= h($halaman) ?>">
        <input type="hidden" name="bagian" value="<?= h($bagian) ?>">
        <div class="form-group" style="margin-bottom: 0.75rem;">
            <label><?= h($label_bagian[$bagian] ?? ucfirst($bagian)) ?></label>
            <?php if (strlen($data['isi']) > 120): ?>
                <textarea name="isi" class="form-control" rows="4"><?= h($data['isi']) ?></textarea>
            <?php else: ?>
                <input type="text" name="isi" class="form-control" value="<?= h($data['isi']) ?>">
            <?php endif; ?>
            <small style="color:#888;">Terakhir diperbarui: <?= h(date('d M Y H:i', strtotime($data['updated_at']))) ?></small>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
    </form>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<?php require_once '../includes/admin_footer.php'; ?>
