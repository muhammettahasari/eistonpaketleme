<?php
require_once __DIR__ . '/eiston-yonetim/includes/db.php';
$settings = get_all_settings($pdo);

$phone1 = $settings['phone1'] ?? '+90 507 715 04 91';
$phone1Digits = preg_replace('/[^0-9]/', '', $phone1);
$phone2 = $settings['phone2'] ?? '+90 535 561 65 41';
$whatsapp = $settings['whatsapp'] ?? '905077150491';
$address = $settings['address'] ?? 'Hacıyusufmescit Mah., Aslım Sanayi Sitesi, Koruluk Sk. No: 15/J, 42030 Karatay / Konya';
$emailInfo = $settings['email_info'] ?? 'info@eistonpaketleme.com';
$emailAmbalaj = $settings['email_ambalaj'] ?? 'ambalaj@eistonpaketleme.com';
$emailExport = $settings['email_export'] ?? 'export@eistonpaketleme.com';
$logoLight = !empty($settings['custom_logo']) ? $settings['custom_logo'] : 'img/logo-horizontal-white.svg';
$logoDark = !empty($settings['custom_logo']) ? $settings['custom_logo'] : 'img/logo-horizontal.svg';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY sort_order ASC, id ASC");
$allProducts = $stmt->fetchAll();

$productsByCategory = [
    'l-kesim'  => [],
    'poset'    => [],
    'skinpack' => [],
    'shrink'   => []
];

foreach ($allProducts as $p) {
    $cat = $p['category'];
    if (isset($productsByCategory[$cat])) {
        $productsByCategory[$cat][] = $p;
    } else {
        $productsByCategory['l-kesim'][] = $p;
    }
}

$totalProductsCount = count($allProducts);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Eiston Paketleme Makineleri ürün kataloğu. L Kesim Shrink, Poşet Ağzı Kapama, Skinpack Vakum ve Polietilen Shrink Makineleri.">
    <title>Ürünlerimiz | Eiston Paketleme Makineleri</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader">
            <div class="loader-inner"></div>
            <img src="img/logo-emblem.svg" alt="Eiston" class="loader-emblem">
        </div>
    </div>

    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo" aria-label="Eiston Paketleme">
                    <img src="<?= htmlspecialchars($logoLight) ?>" alt="Eiston Paketleme Makineleri" class="logo-img logo-light">
                    <img src="<?= htmlspecialchars($logoDark) ?>" alt="Eiston Paketleme Makineleri" class="logo-img logo-dark">
                </a>
                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php">Ana Sayfa</a></li>
                    <li><a href="index.php#hakkimizda">Hakkımızda</a></li>
                    <li><a href="urunler.php" class="active">Ürünler</a></li>
                    <li><a href="index.php#hizmetler">Hizmetler</a></li>
                    <li><a href="index.php#neden-biz">Neden Biz?</a></li>
                    <li><a href="index.php#iletisim">İletişim</a></li>
                </ul>
                <div class="nav-right">
                    <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>" class="nav-phone">
                        <i class="fas fa-phone"></i>
                        <span><?= htmlspecialchars($phone1) ?></span>
                    </a>
                    <button class="hamburger" id="hamburger" aria-label="Menü">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Products Hero -->
    <section class="products-hero">
        <div class="container">
            <div class="breadcrumb animate-fade-up">
                <a href="index.php"><i class="fas fa-home"></i> Ana Sayfa</a>
                <span>/</span>
                <span class="current">Ürünler</span>
            </div>
            <h1 class="animate-fade-up delay-1">Ürün <span class="gradient-text">Kataloğumuz</span></h1>
            <p class="animate-fade-up delay-2"><?= $totalProductsCount ?> farklı model ve 4 ana kategoride, işletmenizin paketleme ihtiyaçlarına yönelik profesyonel makine çözümleri sunuyoruz.</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="fas fa-th-large"></i> Tümü (<?= $totalProductsCount ?>)
                </button>
                <button class="filter-tab" data-filter="l-kesim">
                    <i class="fas fa-cut"></i> L Kesim Shrink (<?= count($productsByCategory['l-kesim']) ?>)
                </button>
                <button class="filter-tab" data-filter="poset">
                    <i class="fas fa-shopping-bag"></i> Poşet Ağzı Kapama (<?= count($productsByCategory['poset']) ?>)
                </button>
                <button class="filter-tab" data-filter="skinpack">
                    <i class="fas fa-compress-alt"></i> Skinpack Vakum (<?= count($productsByCategory['skinpack']) ?>)
                </button>
                <button class="filter-tab" data-filter="shrink">
                    <i class="fas fa-fire-alt"></i> Shrink Makineleri (<?= count($productsByCategory['shrink']) ?>)
                </button>
            </div>
        </div>
    </section>

    <!-- ============ L KESİM SHRİNK MAKİNELERİ ============ -->
    <section id="l-kesim" class="product-category" data-category="l-kesim">
        <div class="container">
            <div class="category-header animate-fade-up">
                <h2><i class="fas fa-cut"></i> L Kesim Shrink Makineleri</h2>
                <p>Tek gövdede L kesim ve shrink işlemini bir arada gerçekleştiren, PVC ve POF shrink filmlerle uyumlu, yüksek verimli makineler.</p>
            </div>

            <div class="products-grid">
                <?php foreach ($productsByCategory['l-kesim'] as $p): 
                    $features = json_decode($p['features'] ?? '[]', true) ?: [];
                ?>
                    <div class="product-card animate-fade-up">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['code'] . ' ' . $p['title']) ?>" loading="lazy">
                            <?php if (!empty($p['badge'])): ?>
                                <span class="product-badge"><?= htmlspecialchars($p['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="product-code"><?= htmlspecialchars($p['code']) ?></span>
                            <h3><?= htmlspecialchars($p['title']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <?php if (!empty($features)): ?>
                                <div class="product-features">
                                    <?php foreach ($features as $f): ?>
                                        <span class="product-feature-tag"><?= htmlspecialchars($f) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-actions">
                                <a href="mailto:<?= htmlspecialchars($emailInfo) ?>?subject=<?= urlencode($p['code'] . ' Hakkında Bilgi') ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Teklif Al
                                </a>
                                <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>" class="btn btn-outline">
                                    <i class="fas fa-phone"></i> Ara
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="category-features animate-fade-up">
                <h4><i class="fas fa-star"></i> L Kesim Shrink Makineleri Ortak Özellikleri</h4>
                <ul class="features-list">
                    <li><i class="fas fa-check"></i> Tek gövdede L kesim ve shrink işlemi (ayrı tünel gerekmez)</li>
                    <li><i class="fas fa-check"></i> PVC ve POF (Poliolefin) shrink filmlerle uyumlu</li>
                    <li><i class="fas fa-check"></i> Manyetik kapak sistemi - otomatik açılma</li>
                    <li><i class="fas fa-check"></i> Ayarlanabilir kesim süresi ve ısı kontrolü</li>
                    <li><i class="fas fa-check"></i> Ayarlanabilir çalışma masası ve besleme platformu</li>
                    <li><i class="fas fa-check"></i> Sadece kesim modunda kullanım imkanı</li>
                    <li><i class="fas fa-check"></i> Porselen kesim teli yatağı</li>
                    <li><i class="fas fa-check"></i> Tekerlekli gövde tasarımı - kolay taşınabilirlik</li>
                    <li><i class="fas fa-check"></i> Film altı hava tahliye aparatı</li>
                    <li><i class="fas fa-check"></i> Ayarlanabilir film yuvası</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ============ POŞET AĞZI KAPAMA MAKİNELERİ ============ -->
    <section id="poset" class="product-category" data-category="poset">
        <div class="container">
            <div class="category-header animate-fade-up">
                <h2><i class="fas fa-shopping-bag"></i> Poşet Ağzı Kapama Makineleri</h2>
                <p>Masaüstü ve ayaklı modelleriyle her türlü poşet, torba ve çuval ağzı kapatma çözümleri. Manuel ve pnömatik seçenekler.</p>
            </div>

            <div class="products-grid">
                <?php foreach ($productsByCategory['poset'] as $p): 
                    $features = json_decode($p['features'] ?? '[]', true) ?: [];
                ?>
                    <div class="product-card animate-fade-up">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['code'] . ' ' . $p['title']) ?>" loading="lazy">
                            <?php if (!empty($p['badge'])): ?>
                                <span class="product-badge"><?= htmlspecialchars($p['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="product-code"><?= htmlspecialchars($p['code']) ?></span>
                            <h3><?= htmlspecialchars($p['title']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <?php if (!empty($features)): ?>
                                <div class="product-features">
                                    <?php foreach ($features as $f): ?>
                                        <span class="product-feature-tag"><?= htmlspecialchars($f) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-actions">
                                <a href="mailto:<?= htmlspecialchars($emailInfo) ?>?subject=<?= urlencode($p['code'] . ' Hakkında Bilgi') ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Teklif Al
                                </a>
                                <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>" class="btn btn-outline">
                                    <i class="fas fa-phone"></i> Ara
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ SKINPACK VAKUM MAKİNELERİ ============ -->
    <section id="skinpack" class="product-category" data-category="skinpack">
        <div class="container">
            <div class="category-header animate-fade-up">
                <h2><i class="fas fa-compress-alt"></i> Skinpack Vakum Ambalaj Makineleri</h2>
                <p>Ürünleri termo-lak kaplı karton üzerine şeffaf filmle vakumlu şekilde ambalajlayan, şık sunum sağlayan makineler. Hırdavat, yedek parça, cam eşya ve oyuncak gibi ürünler için idealdir.</p>
            </div>

            <div class="products-grid">
                <?php foreach ($productsByCategory['skinpack'] as $p): 
                    $features = json_decode($p['features'] ?? '[]', true) ?: [];
                ?>
                    <div class="product-card animate-fade-up">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['code'] . ' ' . $p['title']) ?>" loading="lazy">
                            <?php if (!empty($p['badge'])): ?>
                                <span class="product-badge"><?= htmlspecialchars($p['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="product-code"><?= htmlspecialchars($p['code']) ?></span>
                            <h3><?= htmlspecialchars($p['title']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <?php if (!empty($features)): ?>
                                <div class="product-features">
                                    <?php foreach ($features as $f): ?>
                                        <span class="product-feature-tag"><?= htmlspecialchars($f) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-actions">
                                <a href="mailto:<?= htmlspecialchars($emailInfo) ?>?subject=<?= urlencode($p['code'] . ' Hakkında Bilgi') ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Teklif Al
                                </a>
                                <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>" class="btn btn-outline">
                                    <i class="fas fa-phone"></i> Ara
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="category-features animate-fade-up">
                <h4><i class="fas fa-star"></i> Skinpack Vakum Makineleri Avantajları</h4>
                <ul class="features-list">
                    <li><i class="fas fa-check"></i> Ürünleri termo-lak kaplı karton üzerine vakumlu ambalajlama</li>
                    <li><i class="fas fa-check"></i> Şeffaf film ile şık ve profesyonel sunum</li>
                    <li><i class="fas fa-check"></i> Hırdavat, yedek parça, cam eşya ve oyuncaklar için ideal</li>
                    <li><i class="fas fa-check"></i> Teşhir kolaylığı ve zarif görünüm</li>
                    <li><i class="fas fa-check"></i> Ürünü dış etkenlere karşı koruma</li>
                    <li><i class="fas fa-check"></i> Kolay ve hızlı ambalajlama işlemi</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ============ SHRİNK MAKİNELERİ (POLİETİLEN) ============ -->
    <section id="shrink" class="product-category" data-category="shrink">
        <div class="container">
            <div class="category-header animate-fade-up">
                <h2><i class="fas fa-fire-alt"></i> Shrink Makineleri (Polietilen)</h2>
                <p>Daha kalın ve dayanıklı polietilen film kullanan, önden beslemeli shrink makineleri. Şişe, kutu, kavanoz, bidon ve ağır ürün grupları için idealdir.</p>
            </div>

            <div class="products-grid">
                <?php foreach ($productsByCategory['shrink'] as $p): 
                    $features = json_decode($p['features'] ?? '[]', true) ?: [];
                ?>
                    <div class="product-card animate-fade-up">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['code'] . ' ' . $p['title']) ?>" loading="lazy">
                            <?php if (!empty($p['badge'])): ?>
                                <span class="product-badge"><?= htmlspecialchars($p['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="product-code"><?= htmlspecialchars($p['code']) ?></span>
                            <h3><?= htmlspecialchars($p['title']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                            <?php if (!empty($features)): ?>
                                <div class="product-features">
                                    <?php foreach ($features as $f): ?>
                                        <span class="product-feature-tag"><?= htmlspecialchars($f) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-actions">
                                <a href="mailto:<?= htmlspecialchars($emailInfo) ?>?subject=<?= urlencode($p['code'] . ' Hakkında Bilgi') ?>" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Teklif Al
                                </a>
                                <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>" class="btn btn-outline">
                                    <i class="fas fa-phone"></i> Ara
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="category-features animate-fade-up">
                <h4><i class="fas fa-star"></i> Polietilen Shrink Makineleri Avantajları</h4>
                <ul class="features-list">
                    <li><i class="fas fa-check"></i> Daha kalın ve dayanıklı polietilen (PE) film kullanımı</li>
                    <li><i class="fas fa-check"></i> Önden beslemeli sistem ile kolay kullanım</li>
                    <li><i class="fas fa-check"></i> Ürün gruplama ve toplu paketleme imkanı</li>
                    <li><i class="fas fa-check"></i> Dış etkenlere karşı güçlü koruma</li>
                    <li><i class="fas fa-check"></i> Şişe, kutu, kavanoz, bidon için ideal</li>
                    <li><i class="fas fa-check"></i> Ağır ürün grupları için uygun</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content animate-fade-up">
                <h2>İhtiyacınıza Uygun <span class="gradient-text">Makineyi Birlikte Belirleyelim</span></h2>
                <p>Uzman ekibimiz, işletmenizin paketleme ihtiyaçlarını analiz ederek size en uygun makineyi önerir.</p>
                <div class="cta-buttons">
                    <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-phone"></i>
                        <span>Hemen Arayın: <?= htmlspecialchars($phone1) ?></span>
                    </a>
                    <a href="mailto:<?= htmlspecialchars($emailInfo) ?>" class="btn btn-outline btn-lg">
                        <i class="fas fa-envelope"></i>
                        <span>E-posta Gönderin</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="index.php" class="footer-logo" aria-label="Eiston Paketleme">
                        <img src="<?= htmlspecialchars($logoLight) ?>" alt="Eiston Paketleme Makineleri" class="footer-logo-img">
                    </a>
                    <p>İşletmelerin ürünlerini daha estetik, güvenli, dayanıklı ve düzenli bir şekilde tüketiciye sunabilmesi amacıyla yüksek teknolojili paketleme makineleri üretmekteyiz.</p>
                </div>
                <div class="footer-links">
                    <h4>Hızlı Bağlantılar</h4>
                    <ul>
                        <li><a href="index.php">Ana Sayfa</a></li>
                        <li><a href="index.php#hakkimizda">Hakkımızda</a></li>
                        <li><a href="urunler.php">Ürünler</a></li>
                        <li><a href="index.php#hizmetler">Hizmetler</a></li>
                        <li><a href="index.php#iletisim">İletişim</a></li>
                    </ul>
                </div>
                <div class="footer-products">
                    <h4>Ürün Kategorileri</h4>
                    <ul>
                        <li><a href="urunler.php#l-kesim">L Kesim Shrink Makineleri</a></li>
                        <li><a href="urunler.php#shrink">Shrink Makineleri</a></li>
                        <li><a href="urunler.php#poset">Poşet Ağzı Kapama</a></li>
                        <li><a href="urunler.php#skinpack">Skinpack Vakum Makineleri</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>İletişim</h4>
                    <ul>
                        <li><i class="fas fa-mobile-alt"></i> <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>"><?= htmlspecialchars($phone1) ?></a></li>
                        <?php if (!empty($phone2)): ?>
                            <li><i class="fas fa-mobile-alt"></i> <a href="tel:+<?= preg_replace('/[^0-9]/', '', $phone2) ?>"><?= htmlspecialchars($phone2) ?></a></li>
                        <?php endif; ?>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:<?= htmlspecialchars($emailInfo) ?>"><?= htmlspecialchars($emailInfo) ?></a></li>
                        <li><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($address) ?></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Eiston Paketleme Makineleri. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="back-to-top" aria-label="Yukarı Çık">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?= htmlspecialchars($whatsapp) ?>" target="_blank" class="whatsapp-btn" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="js/main.js"></script>
</body>
</html>
