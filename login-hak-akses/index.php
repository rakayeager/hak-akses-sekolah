<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$error = isset($_GET['error']);
$logout = isset($_GET['logout']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'includes/head.php'; renderHead('Login'); ?>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card fade-in">
            <!-- Logo -->
            <div class="login-logo glass-shimmer">
                <i class="ri-stack-line"></i>
            </div>

            <h2>Selamat Datang</h2>
            <p class="login-subtitle">Masuk ke LiquidPanel untuk melanjutkan</p>

            <?php if ($error): ?>
            <div class="alert-glass alert-danger">
                <i class="ri-error-warning-line"></i>
                Username atau password salah!
            </div>
            <?php endif; ?>

            <?php if ($logout): ?>
            <div class="alert-glass alert-success">
                <i class="ri-check-line"></i>
                Anda telah berhasil logout.
            </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST" class="needs-validation">
                <div class="form-group">
                    <label class="form-label-glass">Username</label>
                    <div style="position: relative;">
                        <input type="text" name="username" class="glass-input" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label-glass">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="glass-input" placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login ripple">
                    <i class="ri-login-box-line"></i>
                    Masuk
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <p class="text-sm text-muted">
                    &copy; <?= date('Y') ?> LiquidPanel. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
