<?php
require 'auth.php';
require 'config.php';
cekRole('admin');

$activePage = 'admin';
$pageTitle = 'Dashboard Admin';
$breadcrumb = 'Dashboard';

// Stats
$totalUsers = $koneksi->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmin = $koneksi->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$totalPetugas = $koneksi->query("SELECT COUNT(*) FROM users WHERE role='petugas'")->fetchColumn();
$totalSiswa = $koneksi->query("SELECT COUNT(*) FROM users WHERE role='siswa'")->fetchColumn();

// Recent users
$recentUsers = $koneksi->query("SELECT id, nama, username, role FROM users ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
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

                <?php if (isset($_GET['success'])): ?>
                <div class="alert-glass alert-success">
                    <i class="ri-check-line"></i>
                    <?php
                    $msgs = [
                        'tambah' => 'Pengguna berhasil ditambahkan!',
                        'hapus'  => 'Pengguna berhasil dihapus!',
                        'edit'   => 'Data pengguna berhasil diperbarui!',
                    ];
                    echo $msgs[$_GET['success']] ?? 'Operasi berhasil!';
                    ?>
                </div>
                <?php endif; ?>

                <!-- Stats Grid -->
                <div class="grid-4 stagger-children mb-4">
                    <div class="stat-card stat-primary hover-lift">
                        <div class="stat-icon icon-primary">
                            <i class="ri-group-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 data-count="<?= $totalUsers ?>"><?= $totalUsers ?></h3>
                            <p>Total Pengguna</p>
                            <span class="stat-change up"><i class="ri-arrow-up-s-line"></i> Aktif</span>
                        </div>
                    </div>
                    <div class="stat-card stat-purple hover-lift">
                        <div class="stat-icon icon-purple">
                            <i class="ri-shield-user-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 data-count="<?= $totalAdmin ?>"><?= $totalAdmin ?></h3>
                            <p>Administrator</p>
                        </div>
                    </div>
                    <div class="stat-card stat-success hover-lift">
                        <div class="stat-icon icon-success">
                            <i class="ri-user-star-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 data-count="<?= $totalPetugas ?>"><?= $totalPetugas ?></h3>
                            <p>Petugas</p>
                        </div>
                    </div>
                    <div class="stat-card stat-warning hover-lift">
                        <div class="stat-icon icon-warning">
                            <i class="ri-graduation-cap-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 data-count="<?= $totalSiswa ?>"><?= $totalSiswa ?></h3>
                            <p>Siswa</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="section-title">Aksi Cepat</div>
                <div class="quick-actions mb-4 stagger-children">
                    <a href="kelola_user.php" class="quick-action-item hover-lift">
                        <i class="ri-user-settings-line" style="background: rgba(0,122,255,0.1); color: var(--accent-primary);"></i>
                        <span>Kelola User</span>
                    </a>
                    <a href="kelola_user.php?action=tambah" class="quick-action-item hover-lift">
                        <i class="ri-user-add-line" style="background: rgba(52,199,89,0.1); color: var(--accent-success);"></i>
                        <span>Tambah User</span>
                    </a>
                    <a href="profil.php" class="quick-action-item hover-lift">
                        <i class="ri-user-line" style="background: rgba(175,82,222,0.1); color: var(--accent-purple);"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="logout.php" class="quick-action-item hover-lift" onclick="return confirm('Yakin ingin logout?')">
                        <i class="ri-logout-box-line" style="background: rgba(255,59,48,0.1); color: var(--accent-danger);"></i>
                        <span>Logout</span>
                    </a>
                </div>

                <!-- Recent Users -->
                <div class="grid-2">
                    <div>
                        <div class="flex-between mb-2">
                            <div class="section-title mb-0">Pengguna Terbaru</div>
                            <a href="kelola_user.php" class="btn-glass btn-glass-sm">
                                Lihat Semua <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </div>
                        <div class="glass-table-wrapper">
                            <table class="glass-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentUsers as $u): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div class="user-avatar" style="width:30px;height:30px;border-radius:8px;background:<?= $avatarGradients[$u['role']] ?? 'var(--gradient-card-1)' ?>;display:flex;align-items:center;justify-content:center;color:white;font-size:0.65rem;font-weight:600;flex-shrink:0;">
                                                    <?= strtoupper(mb_substr($u['nama'], 0, 2)) ?>
                                                </div>
                                                <?= htmlspecialchars($u['nama']) ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($u['username']) ?></td>
                                        <td><span class="role-badge role-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <div class="section-title">Aktivitas Terkini</div>
                        <div class="glass-card" style="padding: 1.25rem;">
                            <ul class="activity-list">
                                <li class="activity-item">
                                    <div class="activity-dot" style="background: var(--accent-success);"></div>
                                    <div class="activity-content">
                                        <p><strong><?= htmlspecialchars($_SESSION['nama']) ?></strong> telah login ke sistem</p>
                                        <div class="activity-time">Baru saja</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-dot" style="background: var(--accent-primary);"></div>
                                    <div class="activity-content">
                                        <p>Sistem berjalan dengan normal</p>
                                        <div class="activity-time">Hari ini</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-dot" style="background: var(--accent-teal);"></div>
                                    <div class="activity-content">
                                        <p>Database terhubung sukses</p>
                                        <div class="activity-time">Hari ini</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="activity-dot" style="background: var(--accent-purple);"></div>
                                    <div class="activity-content">
                                        <p>Total <?= $totalUsers ?> pengguna terdaftar</p>
                                        <div class="activity-time">Realtime</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
