<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$is_ajax = isset($_GET['_ajax']) && $_GET['_ajax'] === '1';

if ($is_ajax) {
    ob_start();
} else {
    require_once 'includes/header.php';
}

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

<?php if (!$is_ajax): ?>
<main>

<div class="page-header">
    <div class="parallax-shape parallax-shape-ring parallax-element reveal" data-speed="0.25" style="top: 15%; right: 8%;"></div>
    <div class="parallax-shape parallax-shape-dot parallax-element reveal" data-speed="0.18" style="bottom: 25%; left: 5%;"></div>
    <div class="parallax-shape parallax-shape-line parallax-element reveal" data-speed="0.22" style="top: 50%; right: 3%;"></div>
    <div class="container parallax-element" data-speed="0.15">
        <span class="eyebrow reveal reveal-up">Cita Rasa Otentik</span>
        <h1 class="section-title reveal reveal-up delay-1">Eksplorasi Menu Kami</h1>
        
        <div class="search-container reveal reveal-up delay-2">
            <form action="katalog.php" method="GET">
                <?php if ($kategori_aktif !== 'Semua'): ?>
                    <input type="hidden" name="kategori" value="<?= escapeHtml($kategori_aktif) ?>">
                <?php endif; ?>
                <input type="text" name="q" value="<?= escapeHtml($search_query) ?>" 
                       class="search-input" 
                       placeholder="Cari hidangan favorit Anda...">
                <i class="ri-search-line search-icon"></i>
                <?php if (!empty($search_query)): ?>
                    <a href="katalog.php?kategori=<?= urlencode($kategori_aktif) ?>&cv=<?= time() ?>" class="search-clear">Hapus</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<div id="category-filter-bar" style="background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: var(--header-h-small); z-index: 100; padding: 0;">
    <div class="container">
        <div class="category-filter-wrapper">
            <button class="scroll-arrow left" id="scroll-left"><i class="ri-arrow-left-s-line"></i></button>
            <nav class="category-filter-desktop" id="category-nav" aria-label="Filter Kategori">
                <a href="katalog.php?cv=<?= time() ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>"
                   data-kategori=""
                   style="padding: 1.5rem 2rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: <?= ($kategori_aktif === 'Semua') ? 'var(--primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($kategori_aktif === 'Semua') ? 'var(--primary)' : 'transparent' ?>; white-space: nowrap; transition: all 0.3s;">
                    Semua
                </a>
                <?php foreach($categories as $cat): ?>
                <a href="katalog.php?kategori=<?= urlencode($cat) ?>&cv=<?= time() ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>"
                   data-kategori="<?= escapeHtml($cat) ?>"
                   style="padding: 1.5rem 2rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: <?= ($kategori_aktif === $cat) ? 'var(--primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($kategori_aktif === $cat) ? 'var(--primary)' : 'transparent' ?>; white-space: nowrap; transition: all 0.3s;">
                    <?= escapeHtml($cat) ?>
                </a>
                <?php endforeach; ?>
            </nav>
            <button class="scroll-arrow right" id="scroll-right"><i class="ri-arrow-right-s-line"></i></button>
        </div>

        <div class="category-filter-mobile">
            <select class="filter-select" id="mobile-category-filter">
                <option value="katalog.php<?= !empty($search_query) ? '?q='.urlencode($search_query) : '' ?>" <?= $kategori_aktif === 'Semua' ? 'selected' : '' ?>>Semua Kategori</option>
                <?php foreach($categories as $cat): ?>
                <option value="katalog.php?kategori=<?= urlencode($cat) ?><?= !empty($search_query) ? '&q='.urlencode($search_query) : '' ?>" <?= $kategori_aktif === $cat ? 'selected' : '' ?>>
                    <?= escapeHtml($cat) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<section class="section" style="padding-top: 5rem;">
    <div class="container" id="menu-content-container">
<?php endif; ?>
        <?php if(empty($menus)): ?>
        <div style="padding: 10rem 0; text-align: center;" class="reveal reveal-scale">
            <div style="font-size: 4rem; color: var(--primary-pale); margin-bottom: 2rem;">
                <i class="ri-restaurant-line"></i>
            </div>
            <h2 class="section-title" style="font-size: 2rem; color: var(--text-muted);">Hidangan tidak ditemukan</h2>
            <p style="color: var(--text-faint);">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
            <a href="katalog.php?cv=<?= time() ?>" class="btn btn-primary" style="margin-top: 2rem;">Lihat Semua Menu</a>
        </div>
        <?php else: ?>
        
        <?php if (!empty($search_query)): ?>
            <div style="margin-bottom: 3rem;" class="reveal reveal-up">
                <p style="color: var(--text-muted);">Menampilkan hasil pencarian untuk: <strong style="color: var(--charcoal);">"<?= escapeHtml($search_query) ?>"</strong></p>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 3rem;">
            <?php foreach($menus as $index => $menu): ?>
            <div class="menu-card reveal reveal-up delay-<?= ($index % 4) + 1 ?>">
                <div class="menu-card-img">
                    <img src="<?= escapeHtml($menu['foto_url']) ?>"
                         alt="<?= escapeHtml($menu['nama_menu']) ?>"
                         onerror="this.src='https://via.placeholder.com/600x450?text=<?= urlencode($menu['nama_menu']) ?>'">
                    <div style="position: absolute; top: 1.5rem; left: 1.5rem; background: rgba(255,255,255,0.95); padding: 6px 16px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: var(--primary); backdrop-filter: blur(4px);">
                        <?= escapeHtml($menu['kategori']) ?>
                    </div>
                </div>
                <div class="menu-card-body">
                    <h3 class="menu-card-title"><?= escapeHtml($menu['nama_menu']) ?></h3>
                    <p class="menu-card-desc"><?= escapeHtml($menu['deskripsi_singkat']) ?></p>
                    <div class="menu-card-footer">
                        <span class="menu-card-price"><?= format_rupiah($menu['harga']) ?></span>
                        <a href="detail.php?id=<?= $menu['id'] ?>&cv=<?= time() ?>" class="menu-card-link">Detail <i class="ri-arrow-right-s-line"></i></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagebar reveal reveal-up">
            <?php
            $base_url = "katalog.php?kategori=" . urlencode($kategori_aktif) . ($search_query ? "&q=" . urlencode($search_query) : "");
            $window   = 2;
            $rendered = [];

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i === 1 || $i === $total_pages || ($i >= $page - $window && $i <= $page + $window)) {
                    $rendered[] = $i;
                }
            }

            $prev = null;
            foreach ($rendered as $num):
                if ($prev !== null && $num - $prev > 1):
            ?>
                <span class="pagebar-ellipsis">&hellip;</span>
            <?php
                endif;
            ?>
                <a href="<?= $base_url ?>&page=<?= $num ?>"
                   class="btn <?= $num === $page ? 'btn-primary' : 'btn-outline' ?> pagebar-btn">
                    <?= $num ?>
                </a>
            <?php
                $prev = $num;
            endforeach;
            ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
<?php if (!$is_ajax): ?>
    </div>
</section>

</main>
<?php endif; ?>


<?php if ($is_ajax):
    $html = ob_get_clean();
    header('Content-Type: application/json');
    echo json_encode(['html' => $html, 'page' => $page, 'total_pages' => $total_pages]);
    exit;
endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('menu-content-container');
    if (container) container.dataset.inlineFilter = '1';
    const filterBar = document.getElementById('category-filter-bar');
    const categoryNav = document.getElementById('category-nav');
    const mobileFilter = document.getElementById('mobile-category-filter');

    function setActiveCategory(kategori) {
        categoryNav.querySelectorAll('a').forEach(function(a) {
            var linkKat = a.dataset.kategori;
            var isActive = (kategori === '' && linkKat === '') || (linkKat === kategori);
            a.style.color = isActive ? 'var(--primary)' : 'var(--text-muted)';
            a.style.borderBottomColor = isActive ? 'var(--primary)' : 'transparent';
        });
    }

    function fetchContent(url, pushUrl) {
        var ajaxUrl = new URL(url, window.location.href);
        ajaxUrl.searchParams.set('_ajax', '1');

        container.style.opacity = '0.4';
        container.style.pointerEvents = 'none';
        container.style.transition = 'opacity 0.25s ease';

        fetch(ajaxUrl.toString())
            .then(function(res) { return res.json(); })
            .then(function(data) {
                container.innerHTML = data.html;
                container.style.opacity = '1';
                container.style.pointerEvents = '';

                window.history.pushState({}, '', pushUrl);

                if (window.initReveals) window.initReveals(container);
                attachPagebarListeners();

                if (mobileFilter) {
                    var pushKat = new URL(pushUrl, window.location.href).searchParams.get('kategori') || '';
                    for (var i = 0; i < mobileFilter.options.length; i++) {
                        var optKat = new URL(mobileFilter.options[i].value, window.location.href).searchParams.get('kategori') || '';
                        if (optKat === pushKat) {
                            mobileFilter.selectedIndex = i;
                            break;
                        }
                    }
                }
            })
            .catch(function() {
                container.style.opacity = '1';
                container.style.pointerEvents = '';
                window.location.href = pushUrl;
            });
    }

    function attachCategoryListeners() {
        categoryNav.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var kategori = link.dataset.kategori || '';
                setActiveCategory(kategori);
                fetchContent(link.href, link.href);
            });
        });
    }

    function attachMobileCategoryListener() {
        if (!mobileFilter) return;
        mobileFilter.addEventListener('change', function() {
            var selectedUrl = mobileFilter.value;
            var kategori = '';
            var urlObj = new URL(selectedUrl, window.location.href);
            kategori = urlObj.searchParams.get('kategori') || '';
            setActiveCategory(kategori);
            fetchContent(selectedUrl, selectedUrl);
        });
    }

    function attachPagebarListeners() {
        container.querySelectorAll('.pagebar-btn').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchContent(link.href, link.href);
            });
        });
    }

    attachCategoryListeners();
    attachMobileCategoryListener();
    attachPagebarListeners();
});
</script>

<?php require_once 'includes/footer.php'; ?>
<script>
initRealtimePolling('/monalisa_resto/api_menu.php?count_only=1', 30000, function() {
    window.location.reload();
});
</script>
