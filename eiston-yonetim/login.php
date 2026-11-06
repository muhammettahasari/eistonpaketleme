<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

start_secure_session();

// If already logged in, redirect to dashboard
if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = '';
$info = '';
$ip = get_client_ip();

// Rate limiting check
$rateLimit = check_rate_limit($pdo, $ip, '');

if (isset($_GET['msg']) && $_GET['msg'] === 'timeout') {
    $info = 'Oturum süreniz dolduğu için güvenliğiniz amacıyla çıkış yapıldı. Lütfen tekrar giriş yapın.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $username = sanitize_input($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (!verify_csrf_token($csrf)) {
        $error = 'Güvenlik doğrulaması (CSRF) başarısız oldu. Lütfen formu tekrar gönderin.';
    } elseif (!$rateLimit['allowed']) {
        $error = 'Çok fazla hatalı giriş denemesi yapıldı! Güvenlik nedeniyle hesabınız 15 dakika kilitlenmiştir.';
    } elseif (empty($username) || empty($password)) {
        $error = 'Lütfen kullanıcı adı ve şifrenizi girin.';
    } else {
        // Query admin
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Login Success
            record_login_attempt($pdo, $ip, $username, true);
            session_regenerate_id(true);

            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            $_SESSION['last_activity'] = time();

            // Update last_login
            $stmtUp = $pdo->prepare("UPDATE admins SET last_login = ? WHERE id = ?");
            $stmtUp->execute([date('Y-m-d H:i:s'), $admin['id']]);

            header("Location: index.php");
            exit;
        } else {
            // Login Failed
            record_login_attempt($pdo, $ip, $username, false);
            $rateLimit = check_rate_limit($pdo, $ip, $username);
            $rem = $rateLimit['remaining'];
            if ($rem > 0) {
                $error = "Hatalı kullanıcı adı veya şifre! (Kalan deneme hakkı: {$rem})";
            } else {
                $error = "Çok fazla başarısız deneme yapıldı. Sistem 15 dakika süreyle kilitlendi.";
            }
        }
    }
}

$csrfToken = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi | Eiston Paketleme</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="login-body">
    <div class="login-card">
        <div class="login-header">
            <img src="../img/logo-horizontal-white.svg" alt="Eiston Paketleme" class="login-logo">
            <h1>Yönetim Paneli</h1>
            <p>Güvenli Yönetici Doğrulama Sistemi</p>
        </div>
        <div class="login-body-content">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div><?= e($error) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($info)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <div><?= e($info) ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

                <div class="form-group">
                    <label for="username"><i class="fas fa-user-shield"></i> Kullanıcı Adı</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Kullanıcı adınız" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Şifre</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 13px;">
                    <i class="fas fa-sign-in-alt"></i> Güvenli Giriş Yap
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; font-size: 12px; color: #64748B;">
                <i class="fas fa-shield-alt" style="color: #10B981;"></i> 256-Bit SSL / BCRYPT Güvenlik Korumalı
            </div>
        </div>
    </div>
</body>
</html>
