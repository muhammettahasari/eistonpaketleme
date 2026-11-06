<?php
require_once __DIR__ . '/eiston-yonetim/includes/db.php';
$settings = get_all_settings($pdo);

$phone1 = $settings['phone1'] ?? '+90 507 715 04 91';
$phone1Digits = preg_replace('/[^0-9]/', '', $phone1);
$phone2 = $settings['phone2'] ?? '+90 535 561 65 41';
$whatsapp = $settings['whatsapp'] ?? '905077150491';
$address = $settings['address'] ?? 'Hacıyusufmescit Mah., Aslım Sanayi Sitesi, Koruluk Sk. No: 15/J, 42030 Karatay / Konya';
$mapsUrl = get_maps_embed_url($address);
$emailInfo = $settings['email_info'] ?? 'info@eistonpaketleme.com';
$emailAmbalaj = $settings['email_ambalaj'] ?? 'ambalaj@eistonpaketleme.com';
$emailExport = $settings['email_export'] ?? 'export@eistonpaketleme.com';
$logoLight = !empty($settings['custom_logo']) ? $settings['custom_logo'] : 'img/logo-horizontal-white.svg';
$logoDark = !empty($settings['custom_logo']) ? $settings['custom_logo'] : 'img/logo-horizontal.svg';

// Count total products
$prodCount = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Eiston Paketleme Makineleri - Endüstriyel ambalaj ve paketleme çözümleri. L Kesim Shrink, Poşet Ağzı Kapama, Skinpack Vakum Makineleri.">
    <meta name="keywords" content="paketleme makineleri, shrink makinesi, poşet ağzı kapama, skinpack vakum, ambalaj makineleri, Konya">
    <title>Eiston Paketleme Makineleri | Endüstriyel Ambalaj Çözümleri</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
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
                    <li><a href="#anasayfa" class="active">Ana Sayfa</a></li>
                    <li><a href="#hakkimizda">Hakkımızda</a></li>
                    <li><a href="urunler.php">Ürünler</a></li>
                    <li><a href="#hizmetler">Hizmetler</a></li>
                    <li><a href="#neden-biz">Neden Biz?</a></li>
                    <li><a href="#iletisim">İletişim</a></li>
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

    <!-- Hero Section -->
    <section id="anasayfa" class="hero">
        <div class="hero-bg">
            <div class="hero-pattern"></div>
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
            </div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge animate-fade-up">
                    <i class="fas fa-industry"></i>
                    <span><?= htmlspecialchars($settings['slogan'] ?? 'Endüstriyel Ambalaj Çözümleri') ?></span>
                </div>
                <h1 class="hero-title animate-fade-up delay-1">
                    Paketleme Teknolojisinde
                    <span class="gradient-text">Güvenilir Çözüm Ortağınız</span>
                </h1>
                <p class="hero-desc animate-fade-up delay-2">
                    Yüksek teknolojili paketleme makineleri ile ürünlerinizi daha estetik, güvenli ve profesyonel şekilde ambalajlayın. Üretimden satış sonrası desteğe kadar yanınızdayız.
                </p>
                <div class="hero-buttons animate-fade-up delay-3">
                    <a href="urunler.php" class="btn btn-primary">
                        <span>Ürünlerimizi İnceleyin</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#iletisim" class="btn btn-outline">
                        <span>Teklif Alın</span>
                        <i class="fas fa-paper-plane"></i>
                    </a>
                </div>
                <div class="hero-stats animate-fade-up delay-4">
                    <div class="stat-item">
                        <div class="stat-number" data-count="<?= $prodCount ?>">0</div>
                        <div class="stat-label">Farklı Ürün</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="4">0</div>
                        <div class="stat-label">Ürün Kategorisi</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="100">0</div>
                        <div class="stat-label">% Müşteri Memnuniyeti</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-scroll">
            <a href="#hakkimizda">
                <div class="mouse">
                    <div class="wheel"></div>
                </div>
                <span>Aşağı Kaydır</span>
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section id="hakkimizda" class="about section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag animate-fade-up">Hakkımızda</span>
                <h2 class="section-title animate-fade-up delay-1">Paketleme Sektöründe <br><span class="gradient-text">Güvenilir Çözüm Ortağı</span></h2>
            </div>
            <div class="about-grid">
                <div class="about-content animate-fade-right">
                    <p class="about-text">
                        <strong>Eiston Paketleme Makineleri</strong>, endüstriyel alanda ürünlerin estetik, güvenli ve düzenli bir şekilde ambalajlanmasını sağlayan yüksek teknolojili makinelerin üretimini ve tedariğini gerçekleştirmektedir.
                    </p>
                    <p class="about-text">
                        Konya Karatay'daki üretim merkezimizden hem yurt içine hem de yurt dışına geniş bir kullanıcı ağına hizmet vermekteyiz. Alanında uzman kadromuzla; L kesim shrink makineleri, poşet ağzı kapama makineleri, shrink makineleri ve skinpack vakum makineleri gibi geniş bir ürün yelpazesi sunuyoruz.
                    </p>
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                            <span>Yüksek Kalite Standartları</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                            <span>Uzman Teknik Kadro</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                            <span>Yurt İçi ve Yurt Dışı Hizmet</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                            <span>Satış Sonrası Destek</span>
                        </div>
                    </div>
                </div>
                <div class="about-cards animate-fade-left">
                    <div class="info-card vision-card">
                        <div class="card-icon"><i class="fas fa-eye"></i></div>
                        <h3>Vizyonumuz</h3>
                        <p>Paketleme ve ambalaj teknolojileri sektöründe yenilikçi yaklaşımlarla fark yaratan, ulusal ve uluslararası pazarda güvenilirliği ve kalitesiyle ön plana çıkan, küresel ölçekli öncü bir marka olmak.</p>
                    </div>
                    <div class="info-card mission-card">
                        <div class="card-icon"><i class="fas fa-bullseye"></i></div>
                        <h3>Misyonumuz</h3>
                        <p>İşletmelerin paketleme süreçlerindeki verimliliğini artıracak güvenilir, dayanıklı ve yüksek performanslı makineler üretmek. Müşteri beklentilerini titizlikle analiz ederek en doğru çözümleri sunmak.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Categories Preview -->
    <section id="urunler-onizleme" class="products-preview section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag animate-fade-up">Ürünlerimiz</span>
                <h2 class="section-title animate-fade-up delay-1">Ürün <span class="gradient-text">Kategorilerimiz</span></h2>
                <p class="section-desc animate-fade-up delay-2">Her işletmenin ihtiyacına uygun, geniş ürün yelpazemizle yanınızdayız.</p>
            </div>
            <div class="categories-grid">
                <a href="urunler.php#l-kesim" class="category-card animate-fade-up delay-1">
                    <div class="category-icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <div class="category-content">
                        <h3>L Kesim Shrink Makineleri</h3>
                        <p>Manuel ve yarı otomatik L kesim shrink makineleri ile ürünlerinizi profesyonelce ambalajlayın.</p>
                        <span class="category-count">4 Model</span>
                    </div>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
                <a href="urunler.php#shrink" class="category-card animate-fade-up delay-2">
                    <div class="category-icon">
                        <i class="fas fa-fire-alt"></i>
                    </div>
                    <div class="category-content">
                        <h3>Shrink Makineleri</h3>
                        <p>Polietilen shrink makineleri ile yüksek verimli, önden beslemeli paketleme çözümleri.</p>
                        <span class="category-count">3 Model</span>
                    </div>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
                <a href="urunler.php#poset" class="category-card animate-fade-up delay-3">
                    <div class="category-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="category-content">
                        <h3>Poşet Ağzı Kapama Makineleri</h3>
                        <p>Masaüstü ve ayaklı modelleriyle her türlü poşet ve çuval ağzı kapatma çözümleri.</p>
                        <span class="category-count">7 Model</span>
                    </div>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
                <a href="urunler.php#skinpack" class="category-card animate-fade-up delay-4">
                    <div class="category-icon">
                        <i class="fas fa-compress-alt"></i>
                    </div>
                    <div class="category-content">
                        <h3>Skinpack Vakum Makineleri</h3>
                        <p>Manuel ve pnömatik skinpack vakum ambalaj makineleri ile güvenli paketleme.</p>
                        <span class="category-count">3 Model</span>
                    </div>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            </div>
            <div class="section-cta animate-fade-up">
                <a href="urunler.php" class="btn btn-primary">
                    <span>Tüm Ürünleri Görüntüle</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="hizmetler" class="services section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag animate-fade-up">Hizmetlerimiz</span>
                <h2 class="section-title animate-fade-up delay-1">Kapsamlı <span class="gradient-text">Hizmet Anlayışı</span></h2>
                <p class="section-desc animate-fade-up delay-2">Üretimden montaja, eğitimden satış sonrası desteğe kadar her aşamada yanınızdayız.</p>
            </div>
            <div class="services-grid">
                <div class="service-card animate-fade-up delay-1">
                    <div class="service-icon-wrapper">
                        <div class="service-icon"><i class="fas fa-cogs"></i></div>
                    </div>
                    <h3>Makine Üretimi</h3>
                    <p>Yüksek teknoloji ve kalite kontrol standartlarında, uzun ömürlü paketleme makineleri üretiyoruz.</p>
                </div>
                <div class="service-card animate-fade-up delay-2">
                    <div class="service-icon-wrapper">
                        <div class="service-icon"><i class="fas fa-clipboard-check"></i></div>
                    </div>
                    <h3>İhtiyaç Analizi</h3>
                    <p>İşletmenizin paketleme ihtiyaçlarını detaylı analiz ederek en uygun makine ve çözümü öneriyoruz.</p>
                </div>
                <div class="service-card animate-fade-up delay-3">
                    <div class="service-icon-wrapper">
                        <div class="service-icon"><i class="fas fa-tools"></i></div>
                    </div>
                    <h3>Montaj & Kurulum</h3>
                    <p>Makinelerimizin teslimatı, kurulumu ve devreye alınması süreçlerini profesyonel ekibimizle gerçekleştiriyoruz.</p>
                </div>
                <div class="service-card animate-fade-up delay-4">
                    <div class="service-icon-wrapper">
                        <div class="service-icon"><i class="fas fa-graduation-cap"></i></div>
                    </div>
                    <h3>Eğitim</h3>
                    <p>Personellerinize makine kullanımı konusunda kapsamlı eğitim vererek verimli bir başlangıç sağlıyoruz.</p>
                </div>
                <div class="service-card animate-fade-up delay-5">
                    <div class="service-icon-wrapper">
                        <div class="service-icon"><i class="fas fa-headset"></i></div>
                    </div>
                    <h3>Teknik Destek</h3>
                    <p>Teknik destek hattımızla arıza, bakım ve operasyonel sorunlarınıza hızlı çözümler sunuyoruz.</p>
                </div>
                <div class="service-card animate-fade-up delay-6">
                    <div class="service-icon-wrapper">
                        <div class="service-icon"><i class="fas fa-truck"></i></div>
                    </div>
                    <h3>Yedek Parça</h3>
                    <p>Orijinal yedek parça tedariğiyle makinelerinizin uzun ömürlü ve verimli çalışmasını garanti ediyoruz.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section id="neden-biz" class="why-us section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag animate-fade-up">Neden Eiston?</span>
                <h2 class="section-title animate-fade-up delay-1">Bizi Tercih Etmeniz İçin <br><span class="gradient-text">Güçlü Nedenler</span></h2>
            </div>
            <div class="why-us-grid">
                <div class="why-card animate-fade-up delay-1">
                    <div class="why-number">01</div>
                    <h3>Kalite Güvencesi</h3>
                    <p>Üretim süreçlerinin her aşamasında titizlikle gerçekleştirilen kalite kontrol aşamaları sayesinde uzun ömürlü ve sorunsuz çalışan makineler üretiyoruz.</p>
                </div>
                <div class="why-card animate-fade-up delay-2">
                    <div class="why-number">02</div>
                    <h3>Uzman Kadro</h3>
                    <p>Ar-Ge ve inovasyona önem veren uzman ekibimizle sektörel gelişmeleri yakından takip ederek her geçen gün daha verimli çözümler sunuyoruz.</p>
                </div>
                <div class="why-card animate-fade-up delay-3">
                    <div class="why-number">03</div>
                    <h3>Müşteri Odaklılık</h3>
                    <p>Müşteri beklentilerini titizlikle analiz ederek, her işletmenin özel ihtiyaçlarına yönelik en doğru ve ekonomik çözümleri sunuyoruz.</p>
                </div>
                <div class="why-card animate-fade-up delay-4">
                    <div class="why-number">04</div>
                    <h3>İş Etiği</h3>
                    <p>Şeffaflık, dürüstlük ve iş etiği değerlerinden ödün vermeden %100 müşteri memnuniyeti hedefiyle çalışıyoruz.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content animate-fade-up">
                <h2>Paketleme İhtiyaçlarınız İçin <span class="gradient-text">Hemen Bize Ulaşın</span></h2>
                <p>İşletmenize özel paketleme çözümlerimiz hakkında bilgi almak ve ücretsiz teklif almak için bize ulaşın.</p>
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

    <!-- Contact Section -->
    <section id="iletisim" class="contact section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag animate-fade-up">İletişim</span>
                <h2 class="section-title animate-fade-up delay-1">Bizimle <span class="gradient-text">İletişime Geçin</span></h2>
            </div>
            <div class="contact-grid">
                <div class="contact-info animate-fade-right">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-detail">
                            <h4>Adresimiz</h4>
                            <p><?= nl2br(htmlspecialchars($address)) ?></p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-mobile-alt"></i></div>
                        <div class="contact-detail">
                            <h4>Telefon (Cep)</h4>
                            <p>
                                <a href="tel:+<?= htmlspecialchars($phone1Digits) ?>"><?= htmlspecialchars($phone1) ?></a><br>
                                <?php if (!empty($phone2)): ?>
                                    <a href="tel:+<?= preg_replace('/[^0-9]/', '', $phone2) ?>"><?= htmlspecialchars($phone2) ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-detail">
                            <h4>E-posta</h4>
                            <p>
                                <a href="mailto:<?= htmlspecialchars($emailInfo) ?>"><?= htmlspecialchars($emailInfo) ?></a><br>
                                <?php if (!empty($emailAmbalaj)): ?>
                                    <a href="mailto:<?= htmlspecialchars($emailAmbalaj) ?>"><?= htmlspecialchars($emailAmbalaj) ?></a><br>
                                <?php endif; ?>
                                <?php if (!empty($emailExport)): ?>
                                    <a href="mailto:<?= htmlspecialchars($emailExport) ?>"><?= htmlspecialchars($emailExport) ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="contact-form-wrapper animate-fade-left">
                    <form class="contact-form" id="contactForm">
                        <h3>Bize Mesaj Gönderin</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Ad Soyad</label>
                                <input type="text" id="name" name="name" placeholder="Adınızı yazın" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-posta</label>
                                <input type="email" id="email" name="email" placeholder="E-posta adresinizi yazın" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input type="tel" id="phone" name="phone" placeholder="Telefon numaranızı yazın">
                        </div>
                        <div class="form-group">
                            <label for="subject">Konu</label>
                            <select id="subject" name="subject">
                                <option value="">Konu seçin</option>
                                <option value="bilgi">Bilgi Almak İstiyorum</option>
                                <option value="teklif">Fiyat Teklifi</option>
                                <option value="destek">Teknik Destek</option>
                                <option value="diger">Diğer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Mesajınız</label>
                            <textarea id="message" name="message" rows="5" placeholder="Mesajınızı yazın..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-full">
                            <span>Mesajı Gönder</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            <!-- Dynamic Google Map (Auto-updates with current address) -->
            <div class="map-wrapper animate-fade-up">
                <iframe 
                    src="<?= htmlspecialchars($mapsUrl) ?>"
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius: 16px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
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
                        <li><a href="#anasayfa">Ana Sayfa</a></li>
                        <li><a href="#hakkimizda">Hakkımızda</a></li>
                        <li><a href="urunler.php">Ürünler</a></li>
                        <li><a href="#hizmetler">Hizmetler</a></li>
                        <li><a href="#iletisim">İletişim</a></li>
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
