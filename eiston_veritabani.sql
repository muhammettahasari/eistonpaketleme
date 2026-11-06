-- ==========================================================
-- Eiston Paketleme Makineleri - MySQL Veritabanı Yedeği
-- Tüm Ürünler, Firma Bilgileri ve Yönetici Hesabı Dahildir
-- Karakter Seti: UTF8MB4
-- ==========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admins` (`id`, `username`, `password_hash`, `email`, `created_at`, `last_login`) VALUES (1, 'eiston_admin', '$2y$12$YUtuYKlGrz0/tfvpyB8juOIDSaxdIuKFXOV7vRiDzq.Ggdlo4z2nm', 'info@eistonpaketleme.com', '2026-11-06 17:00:15', '2026-11-06 17:11:16');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (1, 'company_name', 'Eiston Paketleme Makineleri');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (2, 'slogan', 'Endüstriyel Ambalaj Çözümleri');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (3, 'address', 'Hacıyusufmescit Mah., Aslım Sanayi Sitesi, Koruluk Sk. No: 15/J, 42030 Karatay / Konya');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (4, 'phone1', '+90 507 715 04 91');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (5, 'phone2', '+90 535 561 65 41');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (6, 'whatsapp', '905077150491');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (7, 'email_info', 'info@eistonpaketleme.com');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (8, 'email_ambalaj', 'ambalaj@eistonpaketleme.com');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (9, 'email_export', 'export@eistonpaketleme.com');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (10, 'logo_light', 'img/logo-horizontal-white.svg');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (11, 'logo_dark', 'img/logo-horizontal.svg');
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES (12, 'custom_logo', '');

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (1, 'ETN001', '40x30 L Kesim Manuel Shrink Makinesi', 'l-kesim', 'Manuel', 'Küçük boyutlu ürünlerin ambalajlanması için ideal, kompakt tasarımlı manuel L kesim shrink makinesi.', '[\"40x30 cm\",\"Manuel\",\"PVC\\/POF\"]', 'img/etn001.jpg', 1, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (2, 'ETN002', '45x40 L Kesim Manuel Shrink Makinesi', 'l-kesim', 'Manuel', 'Orta boy ürünler için tasarlanmış, geniş çalışma alanına sahip manuel L kesim shrink makinesi.', '[\"45x40 cm\",\"Manuel\",\"PVC\\/POF\"]', 'img/etn002.jpg', 2, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (3, 'ETN003', '75x55 L Kesim Manuel Shrink Makinesi', 'l-kesim', 'Manuel', 'Büyük boyutlu ürünlerin ambalajlanması için geniş çalışma alanı sunan manuel L kesim shrink makinesi.', '[\"75x55 cm\",\"Manuel\",\"PVC\\/POF\"]', 'img/etn003.jpg', 3, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (4, 'ETN004', '56x42 L Kesim Yarı Otomatik Shrink Makinesi', 'l-kesim', 'Yarı Otomatik', 'Yüksek üretim kapasitesi gerektiren işletmeler için yarı otomatik L kesim shrink makinesi.', '[\"56x42 cm\",\"Yar\\u0131 Otomatik\",\"PVC\\/POF\"]', 'img/etn004.jpg', 4, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (5, 'ETN005', '40 cm Ayaklı Poşet Ağzı Kapatma Makinesi', 'poset', 'Ayaklı', '40 cm kaynak genişliğine sahip, ayaklı tasarımlı poşet ağzı kapatma makinesi.', '[\"40 cm\",\"Ayakl\\u0131\",\"Manuel\"]', 'img/etn005.jpg', 5, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (6, 'ETN006', 'Masaüstü Poşet Ağzı Kapatma Makinesi', 'poset', 'Masaüstü', 'Kompakt tasarımlı, masaüstü kullanıma uygun poşet ağzı kapatma makinesi.', '[\"Masa\\u00fcst\\u00fc\",\"Kompakt\",\"Manuel\"]', 'img/etn006.jpg', 6, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (7, 'ETN007', '40 cm Ayaklı Pnömatik Poşet Ağzı Kapatma Makinesi', 'poset', 'Pnömatik', 'Pnömatik sistem ile daha güçlü ve hızlı kapatma sağlayan 40 cm ayaklı model.', '[\"40 cm\",\"Pn\\u00f6matik\",\"Ayakl\\u0131\"]', 'img/etn007.jpg', 7, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (8, 'ETN008', '60 cm Ayaklı Pnömatik Poşet Ağzı Kapatma Makinesi', 'poset', 'Pnömatik', 'Geniş ağızlı poşetler için 60 cm kaynak genişliğine sahip pnömatik kapatma makinesi.', '[\"60 cm\",\"Pn\\u00f6matik\",\"Ayakl\\u0131\"]', 'img/etn008.jpg', 8, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (9, 'ETN009', '40-60 cm Çift Yönlü Ayaklı Pnömatik Poşet Ağzı Kapatma Makinesi', 'poset', 'Çift Yönlü', 'Çift yönlü çalışma özelliğiyle yüksek verimlilik sağlayan pnömatik kapatma makinesi.', '[\"40-60 cm\",\"\\u00c7ift Y\\u00f6nl\\u00fc\",\"Pn\\u00f6matik\"]', 'img/etn009.jpg', 9, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (10, 'ETN010', '40-60 cm Dik Pnömatik Çuval (Torba) Ağzı Kapama Makinesi', 'poset', 'Dikey', 'Çuval ve torba ağzı kapatma için dikey tasarımlı pnömatik makine.', '[\"40-60 cm\",\"Dikey\",\"\\u00c7uval\\/Torba\"]', 'img/etn010.jpg', 10, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (11, 'ETN011', '60 cm Ayaklı Manuel Poşet Ağzı Kapatma Makinesi', 'poset', 'Manuel', '60 cm kaynak genişliğine sahip, manuel kullanımlı ayaklı poşet ağzı kapatma makinesi.', '[\"60 cm\",\"Manuel\",\"Ayakl\\u0131\"]', 'img/etn011.jpg', 11, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (12, 'ETN012', 'Manuel Skinpack Vakum Ambalaj Makinesi', 'skinpack', 'Manuel', 'Manuel kullanımlı, kompakt tasarımlı skinpack vakum ambalaj makinesi. Küçük üretimler için ideal.', '[\"Manuel\",\"Vakum\",\"Skinpack\"]', 'img/etn012.jpg', 12, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (13, 'ETN013', '70x50 Pnömatik Skinpack Vakum Ambalaj Makinesi', 'skinpack', 'Pnömatik', '70x50 cm çalışma alanına sahip, pnömatik sistemli yüksek kapasiteli skinpack vakum makinesi.', '[\"70x50 cm\",\"Pn\\u00f6matik\",\"Vakum\"]', 'img/etn013.jpg', 13, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (14, 'ETN014', '50x35 Manuel Skinpack Vakum Ambalaj Makinesi', 'skinpack', 'Manuel', '50x35 cm çalışma alanına sahip, manuel kullanımlı skinpack vakum ambalaj makinesi.', '[\"50x35 cm\",\"Manuel\",\"Vakum\"]', 'img/etn014.jpg', 14, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (15, 'ETN015', '60×40 Önden Beslemeli Polietilen Shrink Makinesi', 'shrink', 'Önden Beslemeli', '60x40 cm çalışma alanına sahip, önden beslemeli polietilen shrink makinesi.', '[\"60x40 cm\",\"\\u00d6nden Beslemeli\",\"PE Film\"]', 'img/etn015.jpg', 15, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (16, 'ETN016', '60×100 Önden Beslemeli Polietilen Shrink Makinesi', 'shrink', 'Geniş Alan', '60x100 cm geniş çalışma alanı ile büyük ürün grupları için polietilen shrink makinesi.', '[\"60x100 cm\",\"\\u00d6nden Beslemeli\",\"PE Film\"]', 'img/etn016.jpg', 16, '2026-11-06 17:00:16');
INSERT INTO `products` (`id`, `code`, `title`, `category`, `badge`, `description`, `features`, `image_path`, `sort_order`, `created_at`) VALUES (17, 'ETN017', '60×40 Önden Beslemeli Polietilen Pnömatik Shrink Makinesi', 'shrink', 'Pnömatik', 'Pnömatik sistem ile daha hızlı ve verimli çalışan önden beslemeli polietilen shrink makinesi.', '[\"60x40 cm\",\"Pn\\u00f6matik\",\"PE Film\"]', 'img/etn017.jpg', 17, '2026-11-06 17:00:16');

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `attempted_at` datetime NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
