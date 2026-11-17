<?php
require 'auth.php';
require 'config.php';
cekRole('siswa');

$activePage = 'siswa';
$pageTitle = 'Dashboard Siswa';
$breadcrumb = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php renderHead($pageTitle); ?>
</head>
<body>
    <div class="app-container">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <?php include 'includes/header.php'; ?>

            <div class="page-content page-enter">
                <!-- Greeting -->
                <div class="mb-3">
                    <h1 class="page-title"><span class="greeting-text">Selamat Datang</span>, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</h1>
                    <p class="text-muted text-sm realtime-clock"></p>
                </div>

                <!-- Stats -->
                <div class="grid-4 stagger-children mb-4">
                    <div class="stat-card stat-warning hover-lift">
                        <div class="stat-icon icon-warning">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Siswa</h3>
                            <p>Hak Akses Anda</p>
                            <span class="stat-change up"><i class="ri-check-line"></i> Aktif</span>
                        </div>
                    </div>
                    <div class="stat-card stat-primary hover-lift">
                        <div class="stat-icon icon-primary">
                            <i class="ri-book-open-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3>—</h3>
                            <p>Materi Tersedia</p>
                        </div>
                    </div>
                    <div class="stat-card stat-success hover-lift">
                        <div class="stat-icon icon-success">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= date('d') ?></h3>
                            <p><?= strftime('%B %Y') ?: date('F Y') ?></p>
                        </div>
                    </div>
                    <div class="stat-card stat-purple hover-lift">
                        <div class="stat-icon icon-purple">
                            <i class="ri-time-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="liveTime">--:--</h3>
                            <p>Waktu Sekarang</p>
                        </div>
                    </div>
                </div>

                <!-- Featured Cards -->
                <div class="section-title">Informasi Siswa</div>
                <div class="grid-2 stagger-children mb-4">
                    <div class="featured-card hover-lift">
                        <div class="card-gradient gradient-4"></div>
                        <div class="card-meta">
                            <span><i class="ri-information-line"></i> Info</span>
                        </div>
                        <div class="card-title">Halaman Siswa</div>
                        <div class="card-desc">
                            Anda hanya dapat melihat informasi yang diperuntukkan bagi siswa. Gunakan menu navigasi untuk berpindah halaman.
                        </div>
                        <div class="card-footer-info">
                            <span class="role-badge role-siswa"><i class="ri-graduation-cap-line"></i> Siswa</span>
                        </div>
                    </div>
                    <div class="featured-card hover-lift">
                        <div class="card-gradient gradient-1"></div>
                        <div class="card-meta">
                            <span><i class="ri-lightbulb-line"></i> Tips</span>
                        </div>
                        <div class="card-title">Tips Penggunaan</div>
                        <div class="card-desc">
                            Kunjungi halaman Profil untuk melihat informasi akun Anda. Pastikan untuk logout setelah selesai menggunakan sistem.
                        </div>
                        <div class="card-footer-info">
                            <span class="text-sm text-muted">Diperbarui hari ini</span>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="section-title">Aksi Cepat</div>
                <div class="quick-actions stagger-children">
                    <a href="profil.php" class="quick-action-item hover-lift">
                        <i class="ri-user-line" style="background: rgba(0,122,255,0.1); color: var(--accent-primary);"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="logout.php" class="quick-action-item hover-lift" onclick="return confirm('Yakin ingin logout?')">
                        <i class="ri-logout-box-line" style="background: rgba(255,59,48,0.1); color: var(--accent-danger);"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        // Live clock for siswa
        function updateLiveTime() {
            const el = document.getElementById('liveTime');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        updateLiveTime();
        setInterval(updateLiveTime, 1000);
    </script>
</body>
</html>
