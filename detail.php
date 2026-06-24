<?php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ? AND status = 'aktif'");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if (!$menu) {
    echo "<div class='container section text-center'><h2>Menu tidak ditemukan.</h2><a href='katalog.php' class='btn btn-primary'>Kembali ke Katalog</a></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<main style="padding-top: var(--header-h);">
    <div style="background: var(--bg-warm); border-bottom: 1px solid var(--border); padding: 1.25rem 0; margin-bottom: 2rem;">
        <div class="container">
            <a href="katalog.php?cv=<?= time() ?>" class="btn-ghost" style="font-size: 0.75rem;">
                <i class="ri-arrow-left-line"></i> Kembali ke Katalog Menu
            </a>
        </div>
    </div>

    <section class="section" style="padding-top: 1rem; position: relative; overflow: hidden;">
        <div class="parallax-shape parallax-shape-ring parallax-element reveal" data-speed="0.25" style="top: 10%; right: 3%;"></div>
        <div class="parallax-shape parallax-shape-dot parallax-element reveal" data-speed="0.18" style="bottom: 15%; left: 5%;"></div>
        <div class="container">
            <div class="split-section" style="align-items: start; gap: clamp(2rem, 5vw, 4rem);">
                <div class="reveal reveal-up">
                    <div class="parallax-wrap">
                        <img src="<?= escapeHtml($menu['foto_url']) ?>" alt="<?= escapeHtml($menu['nama_menu']) ?>"
                             class="parallax-img"
                             onerror="this.src='https://via.placeholder.com/600x600?text=Foto+Menu'">
                    </div>
                </div>
                <div class="reveal reveal-up delay-2 parallax-element" data-speed="0.12">
                    <span class="eyebrow"><?= escapeHtml($menu['kategori']) ?></span>
                    <h1 class="section-title" style="margin-bottom: 1rem;"><?= escapeHtml($menu['nama_menu']) ?></h1>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="ri-map-pin-line" style="color: var(--primary);"></i> Khas: <?= escapeHtml($menu['asal_daerah']) ?>
                    </p>
                    
                    <div style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); font-weight: 600; margin-bottom: 3rem; line-height: 1;">
                        <?= format_rupiah($menu['harga']) ?>
                    </div>
                    
                    <div class="reveal reveal-up delay-3" style="margin-bottom: 3rem;">
                        <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; font-family: var(--font-body); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted);">Deskripsi</h3>
                        <p style="line-height: 1.9; color: var(--text); font-size: 1.05rem;"><?= nl2br(escapeHtml($menu['deskripsi_lengkap'])) ?></p>
                    </div>

                    <div class="reveal reveal-up delay-4" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                        <div style="background: var(--surface); padding: 1.75rem; border: 1px solid var(--border); border-radius: var(--radius-sm);">
                            <h4 style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary); margin-bottom: 0.75rem;">Komposisi Utama</h4>
                            <p style="font-size: 0.95rem;"><?= escapeHtml($menu['bahan_utama']) ?></p>
                        </div>
                        
                        <?php if(!empty($menu['info_alergen']) && $menu['info_alergen'] !== 'Tidak ada'): ?>
                        <div style="background: #FFF5F5; padding: 1.75rem; border: 1px solid #FED7D7; border-radius: var(--radius-sm);">
                            <h4 style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #C53030; margin-bottom: 0.75rem;">Informasi Alergen</h4>
                            <p style="font-size: 0.95rem; color: #742A2A;"><?= escapeHtml($menu['info_alergen']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    $stmt_konten = $pdo->query("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'beranda'");
                    $konten_beranda = $stmt_konten->fetchAll(PDO::FETCH_KEY_PAIR);

                    $stmt_kontak = $pdo->query("SELECT bagian, isi FROM konten_halaman WHERE halaman = 'kontak'");
                    $kontak_data = $stmt_kontak->fetchAll(PDO::FETCH_KEY_PAIR);

                    $link_gofood = $konten_beranda['link_gofood'] ?? '';
                    $link_grabfood = $konten_beranda['link_grabfood'] ?? '';

                    $telepon = $kontak_data['telepon'] ?? '';
                    $telepon_link = 'tel:' . str_replace(['-', ' ', '(', ')'], '', $telepon);

                    $raw_wa = $kontak_data['whatsapp'] ?? '';
                    $clean_wa = preg_replace('/[^0-9]/', '', $raw_wa);
                    $whatsapp_phone = $clean_wa;
                    if (strpos($clean_wa, '0') === 0) {
                        $whatsapp_phone = '62' . substr($clean_wa, 1);
                    }
                    $wa_text = rawurlencode("Halo Monalisa Resto, saya ingin memesan menu: " . $menu['nama_menu'] . " (" . format_rupiah($menu['harga']) . ").");
                    $wa_link = "https://wa.me/" . $whatsapp_phone . "?text=" . $wa_text;
                    ?>
                    <div class="reveal reveal-up delay-5" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; font-family: var(--font-body); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted);">Pesan Menu Ini</h3>
                        
                        <?php
                        $detail_buttons = [];
                        if (!empty($link_gofood)) $detail_buttons[] = '<a href="' . escapeHtml($link_gofood) . '" class="btn btn-gofood" target="_blank" rel="noopener noreferrer"><img src="assets/images/logo gofood.webp" alt="GoFood"> GoFood</a>';
                        if (!empty($link_grabfood)) $detail_buttons[] = '<a href="' . escapeHtml($link_grabfood) . '" class="btn btn-grabfood" target="_blank" rel="noopener noreferrer"><img src="assets/images/logo grabfood.webp" alt="GrabFood"> GrabFood</a>';
                        if (!empty($raw_wa)) $detail_buttons[] = '<a href="' . escapeHtml($wa_link) . '" class="btn btn-whatsapp" target="_blank" rel="noopener noreferrer"><i class="ri-whatsapp-line"></i> WhatsApp</a>';
                        if (!empty($telepon)) $detail_buttons[] = '<a href="' . escapeHtml($telepon_link) . '" class="btn btn-phone"><i class="ri-phone-line"></i> Telepon</a>';
                        $btn_count = count($detail_buttons);
                        if ($btn_count > 0):
                        ?>
                        <div class="detail-order-grid" style="display: grid; grid-template-columns: repeat(<?= $btn_count >= 2 ? 2 : 1 ?>, 1fr); gap: 0.75rem;">
                            <?= implode('', $detail_buttons) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
