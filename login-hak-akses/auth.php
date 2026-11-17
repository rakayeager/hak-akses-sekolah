<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include shared components
require_once __DIR__ . '/includes/head.php';

// Memeriksa status login
function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

// Memeriksa role pengguna
function cekRole($role) {
    cekLogin();
    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        echo '<!DOCTYPE html><html lang="id"><head>';
        echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>403 - Akses Ditolak</title>';
        echo '<link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">';
        echo '<link rel="stylesheet" href="assets/css/liquid-glass.css">';
        echo '<link rel="stylesheet" href="assets/css/animations.css">';
        echo '</head><body>';
        echo '<div class="login-wrapper">';
        echo '<div class="login-card fade-in" style="text-align:center;">';
        echo '<div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#FF3B30,#FF453A);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:1.75rem;color:white;box-shadow:0 8px 24px rgba(255,59,48,0.3);">';
        echo '<i class="ri-lock-line"></i></div>';
        echo '<h2>403 - Akses Ditolak</h2>';
        echo '<p class="login-subtitle">Anda tidak memiliki hak akses ke halaman ini.</p>';
        echo '<a href="dashboard.php" class="btn-glass btn-glass-primary ripple" style="display:inline-flex;margin-top:1rem;">';
        echo '<i class="ri-arrow-left-line"></i> Kembali ke Dashboard</a>';
        echo '</div></div></body></html>';
        exit;
    }
}
?>
