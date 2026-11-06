<?php
/**
 * Eiston Paketleme - Security & Authentication Library
 * Advanced Protection Against XSS, CSRF, Brute Force, Malicious Uploads & Injection
 */

if (!defined('EISTON_SECURE')) {
    define('EISTON_SECURE', true);
}

// Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

function start_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        $cookieParams = [
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Strict'
        ];
        session_set_cookie_params($cookieParams);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);
        session_start();
    }

    // Session Hijacking / Expiry Protection (30 minutes inactivity timeout)
    $timeout = 1800; // 30 mins
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        session_unset();
        session_destroy();
        header("Location: login.php?msg=timeout");
        exit;
    }
    $_SESSION['last_activity'] = time();
}

function is_logged_in() {
    return isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true && !empty($_SESSION['admin_id']);
}

function require_login() {
    start_secure_session();
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function generate_csrf_token() {
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    start_secure_session();
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = '127.0.0.1';
    }
    return $ip;
}

function check_rate_limit($pdo, $ip, $username) {
    $window = 900; // 15 minutes
    $maxAttempts = 5;
    $timeLimit = date('Y-m-d H:i:s', time() - $window);

    $stmt = $pdo->prepare("SELECT COUNT(*) AS failed_count FROM login_attempts WHERE (ip_address = ? OR username = ?) AND attempted_at > ? AND success = 0");
    $stmt->execute([$ip, $username, $timeLimit]);
    $result = $stmt->fetch();

    $failedCount = (int)($result['failed_count'] ?? 0);
    return [
        'allowed' => $failedCount < $maxAttempts,
        'remaining' => max(0, $maxAttempts - $failedCount),
        'attempts' => $failedCount
    ];
}

function record_login_attempt($pdo, $ip, $username, $success) {
    $now = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, username, attempted_at, success) VALUES (?, ?, ?, ?)");
    $stmt->execute([$ip, $username, $now, $success ? 1 : 0]);

    if ($success) {
        // Clear old failed attempts on successful login
        $stmtClean = $pdo->prepare("DELETE FROM login_attempts WHERE username = ? OR ip_address = ?");
        $stmtClean->execute([$username, $ip]);
    }
}

function sanitize_input($str) {
    if (is_null($str)) return '';
    // Strip all tags to prevent any script or ad insertion
    $clean = strip_tags((string)$str);
    return trim($clean);
}

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Strict file upload validator and saver
 * Prevents shell upload, double extension, MIME spoofing and executable scripts
 */
function handle_secure_upload($fileArray, $targetSubDir = 'uploads') {
    if (!isset($fileArray['error']) || is_array($fileArray['error'])) {
        return ['success' => false, 'error' => 'Geçersiz dosya verisi.'];
    }

    if ($fileArray['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'Dosya seçilmedi.'];
    }

    if ($fileArray['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Dosya yükleme hatası kodu: ' . $fileArray['error']];
    }

    // Size limit: 8MB
    if ($fileArray['size'] > 8 * 1024 * 1024) {
        return ['success' => false, 'error' => 'Dosya boyutu 8MB sınırını aşamaz.'];
    }

    // Extension validation
    $originalName = $fileArray['name'];
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

    if (!in_array($ext, $allowedExts, true)) {
        return ['success' => false, 'error' => 'Yalnızca JPG, PNG, WEBP ve SVG resim dosyaları yüklenebilir.'];
    }

    // MIME type check
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($fileArray['tmp_name']);
    $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/svg+xml'
    ];

    if (!in_array($mime, $allowedMimes, true)) {
        return ['success' => false, 'error' => 'Dosya içeriği geçerli bir resim formatında değil.'];
    }

    // Deep content check: Block any embedded PHP or script tokens
    $contentSample = file_get_contents($fileArray['tmp_name'], false, null, 0, 8192);
    if (preg_match('/<\?php|<\?=|<script|<\/script|base64_decode|eval\(|shell_exec|system\(|passthru\(/i', $contentSample)) {
        return ['success' => false, 'error' => 'Güvenlik Uyarısı: Dosya içinde izin verilmeyen zararlı kod tespit edildi.'];
    }

    // Generate random secure filename
    $rootDir = dirname(dirname(__DIR__));
    $destDir = $rootDir . DIRECTORY_SEPARATOR . $targetSubDir;

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $newFilename = 'img_' . bin2hex(random_bytes(12)) . '.' . $ext;
    $destination = $destDir . DIRECTORY_SEPARATOR . $newFilename;

    if (!move_uploaded_file($fileArray['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Dosya sunucuya kaydedilemedi.'];
    }

    return [
        'success' => true,
        'filepath' => $targetSubDir . '/' . $newFilename,
        'filename' => $newFilename
    ];
}
