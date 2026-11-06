<?php
/**
 * ==========================================================
 * EISTON PAKETLEME - VERİTABANI BAĞLANTISI (PDO)
 * ==========================================================
 * 
 * Veritabanı Adı (Database Name): eiston_db
 * Karakter Seti: utf8mb4
 */

// 'mysql' (phpMyAdmin) veya 'sqlite'
$dbType = 'mysql';

// MySQL Bilgileri:
$mysqlHost = 'localhost';
$mysqlDb   = 'eiston_db';
$mysqlUser = 'root';
$mysqlPass = '';

$dbDir = __DIR__ . '/../data';
$dbPath = $dbDir . '/eiston.db';

try {
    if ($dbType === 'mysql') {
        $pdo = new PDO("mysql:host={$mysqlHost};dbname={$mysqlDb};charset=utf8mb4", $mysqlUser, $mysqlPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } else {
        throw new Exception("SQLite");
    }
} catch (Exception $e) {
    // Fallback to SQLite if MySQL is not reachable
    try {
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $sqle) {
        die("Veritabanı bağlantı hatası: Sunucuya erişilemedi.");
    }
}

/**
 * Helper: Load all settings into an associative array
 */
function get_all_settings($pdo) {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

/**
 * Helper: Generate Google Maps dynamic embed URL from the current address
 */
function get_maps_embed_url($address) {
    $cleanAddress = trim(strip_tags($address));
    if (empty($cleanAddress)) {
        $cleanAddress = "Karatay Konya";
    }
    return "https://maps.google.com/maps?q=" . urlencode($cleanAddress) . "&t=&z=15&ie=UTF8&iwloc=&output=embed";
}
