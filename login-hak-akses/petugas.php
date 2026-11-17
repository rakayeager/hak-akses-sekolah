<?php
require 'auth.php';
require 'config.php';
cekRole('petugas');

$activePage = 'petugas';
$pageTitle = 'Dashboard Petugas';
$breadcrumb = 'Dashboard';

// Count data
$totalUsers = $koneksi->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalSiswa = $koneksi->query("SELECT COUNT(*) FROM users WHERE role='siswa'")->fetchColumn();
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
                    <div class="stat-card stat-success hover-lift">
                        <div class="stat-icon icon-success">
                            <i class="ri-user-star-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Petugas</h3>
                            <p>Hak Akses Anda</p>
                            <span class="stat-change up"><i class="ri-check-line"></i> Aktif</span>
                        </div>
                    </div>
                    <div class="stat-card stat-primary hover-lift">
                        <div class="stat-icon icon-primary">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 data-count="<?= $totalSiswa ?>"><?= $totalSiswa ?></h3>
                            <p>Total Siswa</p>
                        </div>
                    </div>
                    <div class="stat-card stat-purple hover-lift">
                        <div class="stat-icon icon-purple">
                            <i class="ri-group-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 data-count="<?= $totalUsers ?>"><?= $totalUsers ?></h3>
                            <p>Total Pengguna</p>
                        </div>
                    </div>
                    <div class="stat-card stat-warning hover-lift">
                        <div class="stat-icon icon-warning">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= date('d') ?></h3>
                            <p><?= date('F Y') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Featured Cards -->
                <div class="section-title">Informasi</div>
                <div class="grid-2 stagger-children mb-4">
                    <div class="featured-card hover-lift">
                        <div class="card-gradient gradient-3"></div>
                        <div class="card-meta">
                            <span><i class="ri-shield-check-line"></i> Hak Akses</span>
                        </div>
                        <div class="card-title">Halaman Petugas</div>
                        <div class="card-desc">
                            Anda memiliki akses sebagai petugas sekolah. Anda dapat mengelola data operasional dan melihat informasi siswa yang terdaftar dalam sistem.
                        </div>
                        <div class="card-footer-info">
                            <span class="role-badge role-petugas"><i class="ri-shield-line"></i> Petugas</span>
                            <div class="avatar-stack">
                                <div class="avatar" style="background: var(--gradient-card-3);"><?= strtoupper(mb_substr($nama, 0, 1)) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="featured-card hover-lift">
                        <div class="card-gradient gradient-4"></div>
                        <div class="card-meta">
                            <span><i class="ri-information-line"></i> Panduan</span>
                        </div>
                        <div class="card-title">Panduan Penggunaan</div>
                        <div class="card-desc">
                            Gunakan menu di sebelah kiri untuk navigasi. Anda dapat melihat profil Anda dan mengubah informasi pribadi melalui halaman Profil.
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
</body>
</html>
