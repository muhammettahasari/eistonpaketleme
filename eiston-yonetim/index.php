<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

require_login();

$msg = '';
$msgType = 'success';
$activeTab = $_GET['tab'] ?? 'settings';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrf)) {
        $msg = 'Güvenlik hatası (CSRF): İşlem geçersiz veya süresi dolmuş.';
        $msgType = 'danger';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. UPDATE COMPANY SETTINGS
        if ($action === 'update_settings') {
            $fields = [
                'company_name'  => sanitize_input($_POST['company_name'] ?? ''),
                'slogan'        => sanitize_input($_POST['slogan'] ?? ''),
                'address'       => sanitize_input($_POST['address'] ?? ''),
                'phone1'        => sanitize_input($_POST['phone1'] ?? ''),
                'phone2'        => sanitize_input($_POST['phone2'] ?? ''),
                'whatsapp'      => sanitize_input($_POST['whatsapp'] ?? ''),
                'email_info'    => sanitize_input($_POST['email_info'] ?? ''),
                'email_ambalaj' => sanitize_input($_POST['email_ambalaj'] ?? ''),
                'email_export'  => sanitize_input($_POST['email_export'] ?? '')
            ];

            // Handle Custom Logo Upload
            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadRes = handle_secure_upload($_FILES['logo_file'], 'uploads');
                if ($uploadRes['success']) {
                    $fields['custom_logo'] = $uploadRes['filepath'];
                } else {
                    $msg = 'Logo yükleme hatası: ' . $uploadRes['error'];
                    $msgType = 'danger';
                }
            }

            if ($msgType !== 'danger') {
                $stmtUpdate = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                foreach ($fields as $k => $v) {
                    $stmtUpdate->execute([$v, $k]);
                }
                $msg = 'Firma bilgileri ve Google Haritalar konumu başarıyla güncellendi!';
                $msgType = 'success';
                $activeTab = 'settings';
            }
        }

        // 2. ADD PRODUCT
        elseif ($action === 'add_product') {
            $code = strtoupper(sanitize_input($_POST['code'] ?? ''));
            $title = sanitize_input($_POST['title'] ?? '');
            $category = sanitize_input($_POST['category'] ?? 'l-kesim');
            $badge = sanitize_input($_POST['badge'] ?? '');
            $description = sanitize_input($_POST['description'] ?? '');
            $rawFeatures = sanitize_input($_POST['features'] ?? '');

            // Parse features
            $featureArr = array_filter(array_map('trim', explode(',', $rawFeatures)));
            $featuresJson = json_encode(array_values($featureArr), JSON_UNESCAPED_UNICODE);

            $imagePath = 'img/placeholder.jpg';
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadRes = handle_secure_upload($_FILES['product_image'], 'uploads');
                if ($uploadRes['success']) {
                    $imagePath = $uploadRes['filepath'];
                }
            }

            if (empty($code) || empty($title)) {
                $msg = 'Ürün kodu ve ürün başlığı zorunludur.';
                $msgType = 'danger';
            } else {
                // Check code unique
                $checkStmt = $pdo->prepare("SELECT id FROM products WHERE code = ?");
                $checkStmt->execute([$code]);
                if ($checkStmt->fetch()) {
                    $msg = "{$code} kodlu ürün zaten mevcut!";
                    $msgType = 'danger';
                } else {
                    $stmtIns = $pdo->prepare("INSERT INTO products (code, title, category, badge, description, features, image_path, sort_order, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 99, ?)");
                    $stmtIns->execute([$code, $title, $category, $badge, $description, $featuresJson, $imagePath, date('Y-m-d H:i:s')]);
                    $msg = "Yeni ürün ({$code}) başarıyla eklendi!";
                    $msgType = 'success';
                }
            }
            $activeTab = 'products';
        }

        // 3. EDIT PRODUCT
        elseif ($action === 'edit_product') {
            $id = (int)($_POST['id'] ?? 0);
            $code = strtoupper(sanitize_input($_POST['code'] ?? ''));
            $title = sanitize_input($_POST['title'] ?? '');
            $category = sanitize_input($_POST['category'] ?? 'l-kesim');
            $badge = sanitize_input($_POST['badge'] ?? '');
            $description = sanitize_input($_POST['description'] ?? '');
            $rawFeatures = sanitize_input($_POST['features'] ?? '');

            $featureArr = array_filter(array_map('trim', explode(',', $rawFeatures)));
            $featuresJson = json_encode(array_values($featureArr), JSON_UNESCAPED_UNICODE);

            // Fetch current product
            $stmtProd = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmtProd->execute([$id]);
            $currentProd = $stmtProd->fetch();

            if (!$currentProd) {
                $msg = 'Düzenlenecek ürün bulunamadı.';
                $msgType = 'danger';
            } else {
                $imagePath = $currentProd['image_path'];
                if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadRes = handle_secure_upload($_FILES['product_image'], 'uploads');
                    if ($uploadRes['success']) {
                        $imagePath = $uploadRes['filepath'];
                    }
                }

                $stmtUpd = $pdo->prepare("UPDATE products SET code = ?, title = ?, category = ?, badge = ?, description = ?, features = ?, image_path = ? WHERE id = ?");
                $stmtUpd->execute([$code, $title, $category, $badge, $description, $featuresJson, $imagePath, $id]);
                $msg = "Ürün ({$code}) başarıyla güncellendi!";
                $msgType = 'success';
            }
            $activeTab = 'products';
        }

        // 4. DELETE PRODUCT
        elseif ($action === 'delete_product') {
            $id = (int)($_POST['id'] ?? 0);
            $stmtDel = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmtDel->execute([$id]);
            $msg = "Ürün sistemden başarıyla silindi.";
            $msgType = 'success';
            $activeTab = 'products';
        }

        // 5. CHANGE PASSWORD
        elseif ($action === 'change_password') {
            $currentPass = (string)($_POST['current_password'] ?? '');
            $newPass = (string)($_POST['new_password'] ?? '');
            $confirmPass = (string)($_POST['confirm_password'] ?? '');

            $adminId = (int)$_SESSION['admin_id'];
            $stmtAdmin = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
            $stmtAdmin->execute([$adminId]);
            $adminData = $stmtAdmin->fetch();

            if (!$adminData || !password_verify($currentPass, $adminData['password_hash'])) {
                $msg = 'Mevcut şifrenizi hatalı girdiniz!';
                $msgType = 'danger';
            } elseif (strlen($newPass) < 8) {
                $msg = 'Yeni şifre en az 8 karakter uzunluğunda olmalıdır.';
                $msgType = 'danger';
            } elseif ($newPass !== $confirmPass) {
                $msg = 'Yeni şifre ve şifre tekrarı uyuşmuyor!';
                $msgType = 'danger';
            } else {
                $newHash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmtPassUp = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
                $stmtPassUp->execute([$newHash, $adminId]);
                $msg = 'Şifreniz başarıyla değiştirildi!';
                $msgType = 'success';
            }
            $activeTab = 'security';
        }
    }
}

// Fetch all settings and products for view
$settings = get_all_settings($pdo);
$products = $pdo->query("SELECT * FROM products ORDER BY id ASC")->fetchAll();

// Dynamic Google Maps URL based on address
$mapsUrl = get_maps_embed_url($settings['address'] ?? '');

$csrfToken = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eiston Paketleme | Yönetim Paneli</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../img/logo-horizontal-white.svg" alt="Eiston" class="sidebar-logo">
            </div>

            <nav class="sidebar-nav">
                <a href="?tab=settings" class="nav-item <?= $activeTab === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>Firma Bilgileri & Harita</span>
                </a>
                <a href="?tab=products" class="nav-item <?= $activeTab === 'products' ? 'active' : '' ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Ürün Yönetimi (<?= count($products) ?>)</span>
                </a>
                <a href="?tab=security" class="nav-item <?= $activeTab === 'security' ? 'active' : '' ?>">
                    <i class="fas fa-shield-alt"></i>
                    <span>Güvenlik & Şifre</span>
                </a>
                <a href="../index.php" target="_blank" class="nav-item" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px;">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Siteyi Görüntüle</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-badge">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= e($_SESSION['admin_user']) ?></div>
                        <div class="user-role">Sistem Yöneticisi</div>
                    </div>
                </div>
                <a href="logout.php" title="Güvenli Çıkış" style="color: #EF4444; font-size: 16px;">
                    <i class="fas fa-power-off"></i>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Topbar -->
            <header class="admin-topbar">
                <div class="topbar-title">
                    <h2>
                        <?php if ($activeTab === 'settings'): ?>
                            <i class="fas fa-building" style="color: var(--primary);"></i> Firma Bilgileri ve İletişim Ayarları
                        <?php elseif ($activeTab === 'products'): ?>
                            <i class="fas fa-boxes" style="color: var(--primary);"></i> Ürün Kataloğu Yönetimi
                        <?php else: ?>
                            <i class="fas fa-shield-alt" style="color: var(--primary);"></i> Güvenlik ve Şifre Ayarları
                        <?php endif; ?>
                    </h2>
                </div>
                <div class="topbar-actions">
                    <span style="font-size: 12px; color: var(--gray-500);">
                        <i class="fas fa-lock" style="color: var(--success);"></i> Güvenli Bağlantı
                    </span>
                    <a href="logout.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Çıkış
                    </a>
                </div>
            </header>

            <div class="admin-content">
                <?php if (!empty($msg)): ?>
                    <div class="alert alert-<?= $msgType ?>">
                        <i class="fas <?= $msgType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
                        <div><?= e($msg) ?></div>
                    </div>
                <?php endif; ?>

                <!-- ================= TAB 1: FIRMA BILGILERI & HARITA ================= -->
                <?php if ($activeTab === 'settings'): ?>
                    <form method="POST" action="index.php?tab=settings" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                        <input type="hidden" name="action" value="update_settings">

                        <!-- Logo & Marka -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-palette"></i> Kurumsal Logo & Slogan</h3>
                            </div>
                            <div class="card-body">
                                <div class="grid-2">
                                    <div>
                                        <label style="font-weight: 700; font-size: 13px; margin-bottom: 8px; display: block;">Mevcut Logo Önizleme</label>
                                        <div class="logo-preview-container">
                                            <?php if (!empty($settings['custom_logo'])): ?>
                                                <img src="../<?= e($settings['custom_logo']) ?>" alt="Logo">
                                            <?php else: ?>
                                                <img src="../img/logo-horizontal-white.svg" alt="Logo">
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="logo_file"><i class="fas fa-upload"></i> Yeni Logo Yükle (SVG, PNG, WEBP, JPG)</label>
                                            <input type="file" name="logo_file" id="logo_file" class="form-control" accept=".png,.jpg,.jpeg,.webp,.svg">
                                            <small style="color: var(--gray-500); font-size: 11px;">Maksimum 8MB. Güvenli virüs/kod taraması yapılır.</small>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="form-group">
                                            <label for="company_name"><i class="fas fa-building"></i> Firma Resmi Adı</label>
                                            <input type="text" id="company_name" name="company_name" class="form-control" value="<?= e($settings['company_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="slogan"><i class="fas fa-tag"></i> Firma Sloganı / Başlık</label>
                                            <input type="text" id="slogan" name="slogan" class="form-control" value="<?= e($settings['slogan'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Adres ve Harita -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-map-marker-alt"></i> Firma Adresi ve Canlı Google Harita</h3>
                            </div>
                            <div class="card-body">
                                <div class="grid-2">
                                    <div>
                                        <div class="form-group">
                                            <label for="addressInput"><i class="fas fa-map-marked-alt"></i> Açık Adres</label>
                                            <textarea id="addressInput" name="address" class="form-control" rows="4" required><?= e($settings['address'] ?? '') ?></textarea>
                                            <small style="color: var(--primary); font-size: 12px; font-weight: 600; margin-top: 6px; display: block;">
                                                <i class="fas fa-sync-alt"></i> Adresi değiştirdiğinizde Google Haritalar konumu otomatik olarak güncellenir!
                                            </small>
                                        </div>
                                    </div>

                                    <div>
                                        <label style="font-weight: 700; font-size: 13px; margin-bottom: 8px; display: block;">
                                            <i class="fas fa-map"></i> Google Harita Canlı Önizleme
                                        </label>
                                        <div class="map-preview-box">
                                            <iframe id="mapPreviewFrame" src="<?= e($mapsUrl) ?>" allowfullscreen="" loading="lazy"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Telefon & WhatsApp -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-phone-alt"></i> Telefon ve İletişim Hatları</h3>
                            </div>
                            <div class="card-body">
                                <div class="grid-3">
                                    <div class="form-group">
                                        <label for="phone1"><i class="fas fa-mobile-alt"></i> Birincil Cep Telefonu (Üst Menü & Arama)</label>
                                        <input type="text" id="phone1" name="phone1" class="form-control" value="<?= e($settings['phone1'] ?? '') ?>" placeholder="+90 507 715 04 91" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone2"><i class="fas fa-mobile-alt"></i> İkincil Cep Telefonu</label>
                                        <input type="text" id="phone2" name="phone2" class="form-control" value="<?= e($settings['phone2'] ?? '') ?>" placeholder="+90 535 561 65 41">
                                    </div>
                                    <div class="form-group">
                                        <label for="whatsapp"><i class="fab fa-whatsapp" style="color: #25D366;"></i> WhatsApp Numarası (Ülke koduyla)</label>
                                        <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?= e($settings['whatsapp'] ?? '') ?>" placeholder="905077150491" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- E-Posta Adresleri -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-envelope"></i> E-Posta Adresleri</h3>
                            </div>
                            <div class="card-body">
                                <div class="grid-3">
                                    <div class="form-group">
                                        <label for="email_info"><i class="fas fa-inbox"></i> Genel Bilgi (Info)</label>
                                        <input type="email" id="email_info" name="email_info" class="form-control" value="<?= e($settings['email_info'] ?? '') ?>" placeholder="info@eistonpaketleme.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email_ambalaj"><i class="fas fa-box"></i> Ambalaj Departmanı</label>
                                        <input type="email" id="email_ambalaj" name="email_ambalaj" class="form-control" value="<?= e($settings['email_ambalaj'] ?? '') ?>" placeholder="ambalaj@eistonpaketleme.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="email_export"><i class="fas fa-globe"></i> İhracat Departmanı (Export)</label>
                                        <input type="email" id="email_export" name="email_export" class="form-control" value="<?= e($settings['email_export'] ?? '') ?>" placeholder="export@eistonpaketleme.com">
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                                    <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">
                                        <i class="fas fa-save"></i> Tüm Değişiklikleri Kaydet
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- ================= TAB 2: URUN YONETIMI ================= -->
                <?php if ($activeTab === 'products'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-boxes"></i> Ürün Listesi (Toplam: <?= count($products) ?> Makine)</h3>
                            <button type="button" class="btn btn-primary btn-sm" onclick="openModal('addProductModal')">
                                <i class="fas fa-plus"></i> Yeni Ürün Ekle
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Görsel</th>
                                            <th>Ürün Kodu</th>
                                            <th>Ürün Başlığı</th>
                                            <th>Kategori</th>
                                            <th>Tip/Rozet</th>
                                            <th style="text-align: right;">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $prod): 
                                            $features = json_decode($prod['features'] ?? '[]', true) ?: [];
                                            $catName = match($prod['category']) {
                                                'l-kesim' => 'L Kesim Shrink',
                                                'poset' => 'Poşet Ağzı Kapama',
                                                'skinpack' => 'Skinpack Vakum',
                                                'shrink' => 'Polietilen Shrink',
                                                default => $prod['category']
                                            };
                                        ?>
                                            <tr>
                                                <td style="width: 70px;">
                                                    <img src="../<?= e($prod['image_path']) ?>" alt="<?= e($prod['code']) ?>" class="prod-thumb">
                                                </td>
                                                <td>
                                                    <strong style="color: var(--primary);"><?= e($prod['code']) ?></strong>
                                                </td>
                                                <td>
                                                    <strong><?= e($prod['title']) ?></strong>
                                                    <div style="font-size: 11px; color: var(--gray-500); max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <?= e($prod['description']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary"><?= e($catName) ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary"><?= e($prod['badge'] ?: 'Standart') ?></span>
                                                </td>
                                                <td style="text-align: right; white-space: nowrap;">
                                                    <button type="button" class="btn btn-secondary btn-sm" onclick='editProduct(<?= json_encode($prod, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                                        <i class="fas fa-edit"></i> Düzenle
                                                    </button>
                                                    <form method="POST" action="index.php?tab=products" style="display: inline-block;" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                                        <input type="hidden" name="action" value="delete_product">
                                                        <input type="hidden" name="id" value="<?= (int)$prod['id'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Sil
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ================= TAB 3: GUVENLIK & SIFRE ================= -->
                <?php if ($activeTab === 'security'): ?>
                    <!-- Security Checklist -->
                    <div class="security-status-grid">
                        <div class="security-box">
                            <div class="security-icon ok"><i class="fas fa-shield-alt"></i></div>
                            <div>
                                <div class="security-title">CSRF Token Korumalı</div>
                                <div class="security-desc">Her istek kriptografik olarak doğrulanır.</div>
                            </div>
                        </div>
                        <div class="security-box">
                            <div class="security-icon ok"><i class="fas fa-ban"></i></div>
                            <div>
                                <div class="security-title">Brute-Force Koruması</div>
                                <div class="security-desc">5 hatalı denemede 15 dk kilitleme aktif.</div>
                            </div>
                        </div>
                        <div class="security-box">
                            <div class="security-icon ok"><i class="fas fa-code"></i></div>
                            <div>
                                <div class="security-title">XSS & Reklam Engelleme</div>
                                <div class="security-desc">Tüm girdiler temizlenir ve filtrelenir.</div>
                            </div>
                        </div>
                        <div class="security-box">
                            <div class="security-icon ok"><i class="fas fa-file-upload"></i></div>
                            <div>
                                <div class="security-title">Güvenli Upload Koruması</div>
                                <div class="security-desc">MIME kontrolü ve kod engelleme aktif.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="max-width: 600px;">
                        <div class="card-header">
                            <h3><i class="fas fa-key"></i> Yönetici Şifresini Değiştir</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?tab=security">
                                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                <input type="hidden" name="action" value="change_password">

                                <div class="form-group">
                                    <label for="current_password"><i class="fas fa-lock"></i> Mevcut Şifre</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="new_password"><i class="fas fa-key"></i> Yeni Şifre (En az 8 karakter)</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" minlength="8" required>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password"><i class="fas fa-check-circle"></i> Yeni Şifre Tekrar</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="8" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Şifreyi Güncelle
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- ================= ADD PRODUCT MODAL ================= -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4><i class="fas fa-plus-circle" style="color: var(--primary);"></i> Yeni Ürün Ekle</h4>
                <button type="button" class="modal-close" onclick="closeModal('addProductModal')">&times;</button>
            </div>
            <form method="POST" action="index.php?tab=products" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="add_product">

                <div class="modal-body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="add_code">Ürün Kodu * (Örn: ETN018)</label>
                            <input type="text" id="add_code" name="code" class="form-control" placeholder="ETN018" required>
                        </div>
                        <div class="form-group">
                            <label for="add_category">Kategori *</label>
                            <select id="add_category" name="category" class="form-control" required>
                                <option value="l-kesim">L Kesim Shrink Makineleri</option>
                                <option value="poset">Poşet Ağzı Kapama Makineleri</option>
                                <option value="skinpack">Skinpack Vakum Ambalaj</option>
                                <option value="shrink">Shrink Makineleri (Polietilen)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="add_title">Ürün Başlığı *</label>
                        <input type="text" id="add_title" name="title" class="form-control" placeholder="Örn: 60x50 Otomatik Shrink Makinesi" required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="add_badge">Tip / Rozet (Örn: Manuel, Pnömatik)</label>
                            <input type="text" id="add_badge" name="badge" class="form-control" placeholder="Pnömatik">
                        </div>
                        <div class="form-group">
                            <label for="add_image">Ürün Görseli</label>
                            <input type="file" id="add_image" name="product_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="add_description">Ürün Açıklaması</label>
                        <textarea id="add_description" name="description" class="form-control" rows="3" placeholder="Ürün hakkında kısa tanıtım açıklaması..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="add_features">Özellikler (Virgülle ayırın)</label>
                        <input type="text" id="add_features" name="features" class="form-control" placeholder="60x50 cm, Pnömatik, PE Film">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">İptal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Ürünü Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= EDIT PRODUCT MODAL ================= -->
    <div id="editProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4><i class="fas fa-edit" style="color: var(--primary);"></i> Ürünü Düzenle</h4>
                <button type="button" class="modal-close" onclick="closeModal('editProductModal')">&times;</button>
            </div>
            <form method="POST" action="index.php?tab=products" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="edit_product">
                <input type="hidden" id="edit_id" name="id" value="">

                <div class="modal-body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="edit_code">Ürün Kodu *</label>
                            <input type="text" id="edit_code" name="code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_category">Kategori *</label>
                            <select id="edit_category" name="category" class="form-control" required>
                                <option value="l-kesim">L Kesim Shrink Makineleri</option>
                                <option value="poset">Poşet Ağzı Kapama Makineleri</option>
                                <option value="skinpack">Skinpack Vakum Ambalaj</option>
                                <option value="shrink">Shrink Makineleri (Polietilen)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_title">Ürün Başlığı *</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="edit_badge">Tip / Rozet</label>
                            <input type="text" id="edit_badge" name="badge" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit_image">Görseli Değiştir (İsteğe bağlı)</label>
                            <input type="file" id="edit_image" name="product_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Ürün Açıklaması</label>
                        <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_features">Özellikler (Virgülle ayırın)</label>
                        <input type="text" id="edit_features" name="features" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editProductModal')">İptal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Real-time Google Maps iframe updater when typing address
        const addrInput = document.getElementById('addressInput');
        const mapFrame = document.getElementById('mapPreviewFrame');
        if (addrInput && mapFrame) {
            let debounceTimer;
            addrInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const clean = encodeURIComponent(addrInput.value.trim());
                    if (clean.length > 3) {
                        mapFrame.src = 'https://maps.google.com/maps?q=' + clean + '&t=&z=15&ie=UTF8&iwloc=&output=embed';
                    }
                }, 500);
            });
        }

        // Modals
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // Fill Edit Modal
        function editProduct(prod) {
            document.getElementById('edit_id').value = prod.id;
            document.getElementById('edit_code').value = prod.code;
            document.getElementById('edit_title').value = prod.title;
            document.getElementById('edit_category').value = prod.category;
            document.getElementById('edit_badge').value = prod.badge || '';
            document.getElementById('edit_description').value = prod.description || '';

            let feat = [];
            try {
                feat = JSON.parse(prod.features);
            } catch(e) {}
            document.getElementById('edit_features').value = Array.isArray(feat) ? feat.join(', ') : '';

            openModal('editProductModal');
        }

        // Close modal on click outside
        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('active');
            }
        });
    </script>
</body>
</html>
