<?php
require_once 'includes/header.php';

$kategori_aktif = $_GET['kategori'] ?? 'Semua';
$search_query   = $_GET['q'] ?? '';
$page           = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit          = 12;
$offset         = ($page - 1) * $limit;

$stmt_cat = $pdo->query("SELECT DISTINCT kategori FROM menu WHERE status = 'aktif' ORDER BY kategori");
$categories = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);

$sql_base = " FROM menu WHERE status = 'aktif'";
$params = [];

if ($kategori_aktif !== 'Semua') {
    $sql_base .= " AND kategori = ?";
    $params[] = $kategori_aktif;
}

if (!empty($search_query)) {
    $sql_base .= " AND (nama_menu LIKE ? OR deskripsi_singkat LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

$stmt_count = $pdo->prepare("SELECT COUNT(*) " . $sql_base);
$stmt_count->execute($params);
$total_items = $stmt_count->fetchColumn();
$total_pages = ceil($total_items / $limit);

$sql = "SELECT id, nama_menu, asal_daerah, deskripsi_singkat, harga, foto_url, kategori " . $sql_base . " ORDER BY nama_menu LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menus = $stmt->fetchAll();
?>

<main>

<div class="page-header">
    <div class="container">
        <span class="eyebrow reveal reveal-up">Cita Rasa Otentik</span>
        <h1 class="section-title reveal reveal-up delay-1">Eksplorasi Menu Kami</h1>
        
        <div class="search-container reveal reveal-up delay-2">
            <form action="katalog.php" method="GET">
                <?php if ($kategori_aktif !== 'Semua'): ?>
                    <input type="hidden" name="kategori" value="<?= h($kategori_aktif) ?>">
                <?php endif; ?>
                <input type="text" name="q" value="<?= h($search_query) ?>" 
                       class="search-input" 
                       placeholder="Cari hidangan favorit Anda...">
                <i class="fas fa-search search-icon"></i>
                <?php if (!empty($search_query)): ?>
                    <a href="katalog.php?kategori=<?= urlencode($kategori_aktif) ?>" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.1em;">Hapus</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<div style="background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: var(--header-h-small); z-index: 100; padding: 0;">
    <div class="container">
        <!-- Desktop Filter -->
        <div class="category-filter-wrapper">
            <button class="scroll-arrow left" id="scroll-left"><i class="fas fa-chevron-left"></i></button>
            <nav class="category-filter-desktop" id="category-nav" aria-label="Filter Kategori">
                <a href="katalog.php<?= !empty($search_query) ? '?q='.urlencode($search_query) : '' ?>"
                   style="padding: 1.5rem 2rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: <?= ($kategori_aktif === 'Semua') ? 'var(--primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($kategori_aktif === 'Semua') ? 'var(--primary)' : 'transparent' ?>; white-space: nowrap; transition: all 0.3s;">
                    Semua
                </a>
                <?php foreach($categories as $cat): ?>
                <a href="katalog.php?kategori=<?= urlencode($cat) ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>"
                   style="padding: 1.5rem 2rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: <?= ($kategori_aktif === $cat) ? 'var(--primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($kategori_aktif === $cat) ? 'var(--primary)' : 'transparent' ?>; white-space: nowrap; transition: all 0.3s;">
                    <?= h($cat) ?>
                </a>
                <?php endforeach; ?>
            </nav>
            <button class="scroll-arrow right" id="scroll-right"><i class="fas fa-chevron-right"></i></button>
        </div>

        <div class="category-filter-mobile">
            <select class="filter-select" id="mobile-category-filter">
                <option value="katalog.php<?= !empty($search_query) ? '?q='.urlencode($search_query) : '' ?>" <?= $kategori_aktif === 'Semua' ? 'selected' : '' ?>>Semua Kategori</option>
                <?php foreach($categories as $cat): ?>
                <option value="katalog.php?kategori=<?= urlencode($cat) ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>" <?= $kategori_aktif === $cat ? 'selected' : '' ?>>
                    <?= h($cat) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<section class="section" style="padding-top: 5rem;">
    <div class="container" id="menu-content-container">
        <?php if(empty($menus)): ?>
        <div style="padding: 10rem 0; text-align: center;" class="reveal reveal-scale">
            <div style="font-size: 4rem; color: var(--primary-pale); margin-bottom: 2rem;">
                <i class="fas fa-utensils"></i>
            </div>
            <h2 class="section-title" style="font-size: 2rem; color: var(--text-muted);">Hidangan tidak ditemukan</h2>
            <p style="color: var(--text-faint);">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            <a href="katalog.php" class="btn btn-primary" style="margin-top: 2rem;">Lihat Semua Menu</a>
        </div>
        <?php else: ?>
        
        <?php if (!empty($search_query)): ?>
            <div style="margin-bottom: 3rem;" class="reveal reveal-up">
                <p style="color: var(--text-muted);">Menampilkan hasil pencarian untuk: <strong style="color: var(--charcoal);">"<?= h($search_query) ?>"</strong></p>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 3rem;">
            <?php foreach($menus as $index => $menu): ?>
            <div class="menu-card reveal reveal-up delay-<?= ($index % 4) + 1 ?>">
                <div class="menu-card-img">
                    <img src="<?= h($menu['foto_url']) ?>"
                         alt="<?= h($menu['nama_menu']) ?>"
                         onerror="this.src='https://via.placeholder.com/600x450?text=<?= urlencode($menu['nama_menu']) ?>'">
                    <div style="position: absolute; top: 1.5rem; left: 1.5rem; background: rgba(255,255,255,0.95); padding: 6px 16px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: var(--primary); backdrop-filter: blur(4px);">
                        <?= h($menu['kategori']) ?>
                    </div>
                </div>
                <div class="menu-card-body">
                    <h3 class="menu-card-title"><?= h($menu['nama_menu']) ?></h3>
                    <p class="menu-card-desc"><?= h($menu['deskripsi_singkat']) ?></p>
                    <div class="menu-card-footer">
                        <span class="menu-card-price"><?= format_rupiah($menu['harga']) ?></span>
                        <a href="detail.php?id=<?= $menu['id'] ?>" class="menu-card-link">Detail <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div style="margin-top: 5rem; display: flex; justify-content: center; gap: 0.5rem;" class="reveal reveal-up">
            <?php 
            $base_url = "katalog.php?kategori=" . urlencode($kategori_aktif) . ($search_query ? "&q=" . urlencode($search_query) : "");
            ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?= $base_url ?>&page=<?= $i ?>" 
                   class="btn <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>" 
                   style="min-width: 44px; justify-content: center; border-radius: 50%;">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

</main>

<?php require_once 'includes/footer.php'; ?>
