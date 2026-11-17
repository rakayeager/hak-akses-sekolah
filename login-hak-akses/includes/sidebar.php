<?php
/**
 * Sidebar component
 * Expects: $_SESSION['nama'], $_SESSION['role'], $activePage
 */
$activePage = $activePage ?? '';
$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? 'User';
$initials = strtoupper(mb_substr($nama, 0, 2));

// Role-based gradient
$avatarGradients = [
    'admin'   => 'var(--gradient-card-1)',
    'petugas' => 'var(--gradient-card-3)',
    'siswa'   => 'var(--gradient-card-4)',
];
$avatarBg = $avatarGradients[$role] ?? 'var(--gradient-card-1)';

// Menu items per role
$menuItems = [];

if ($role === 'admin') {
    $menuItems = [
        ['label' => 'Dashboard',       'icon' => 'ri-dashboard-line',   'href' => 'admin.php',       'id' => 'admin'],
        ['label' => 'Kelola Pengguna', 'icon' => 'ri-user-settings-line', 'href' => 'kelola_user.php', 'id' => 'kelola_user'],
        ['label' => 'Profil',          'icon' => 'ri-user-line',        'href' => 'profil.php',      'id' => 'profil'],
    ];
} elseif ($role === 'petugas') {
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'ri-dashboard-line', 'href' => 'petugas.php', 'id' => 'petugas'],
        ['label' => 'Profil',    'icon' => 'ri-user-line',      'href' => 'profil.php',  'id' => 'profil'],
    ];
} else {
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'ri-dashboard-line', 'href' => 'siswa.php', 'id' => 'siswa'],
        ['label' => 'Profil',    'icon' => 'ri-user-line',      'href' => 'profil.php',  'id' => 'profil'],
    ];
}
?>
<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        <div class="brand-icon">
            <i class="ri-stack-line"></i>
        </div>
        <span class="brand-text">LiquidPanel</span>
    </a>

    <div class="sidebar-section-label">Menu Utama</div>

    <ul class="sidebar-nav">
        <?php foreach ($menuItems as $item): ?>
        <li class="nav-item">
            <a href="<?= $item['href'] ?>" class="nav-link <?= $activePage === $item['id'] ? 'active' : '' ?>">
                <i class="<?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="sidebar-section-label">Lainnya</div>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="logout.php" class="nav-link" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                <i class="ri-logout-box-r-line"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="profil.php" class="sidebar-user">
            <div class="user-avatar" style="background: <?= $avatarBg ?>">
                <?= $initials ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($nama) ?></div>
                <div class="user-role"><?= htmlspecialchars($role) ?></div>
            </div>
        </a>
    </div>
</aside>
