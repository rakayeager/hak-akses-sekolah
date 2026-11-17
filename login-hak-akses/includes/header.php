<?php
/**
 * Top header component
 * Expects: $pageTitle, $breadcrumb
 */
$pageTitle  = $pageTitle ?? 'Dashboard';
$breadcrumb = $breadcrumb ?? $pageTitle;
?>
<header class="top-header">
    <div class="header-left">
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Menu">
            <i class="ri-menu-line"></i>
        </button>
        <div class="breadcrumb-text">
            <span><?= htmlspecialchars($breadcrumb) ?></span>
        </div>
    </div>
    <div class="header-right">
        <div class="search-box" style="margin-right: 0.5rem;">
            <i class="ri-search-line"></i>
            <input type="text" class="glass-input search-filter" placeholder="Cari...">
        </div>
        <div style="position: relative;">
            <button class="header-btn notif-trigger" data-tooltip="Notifikasi">
                <i class="ri-notification-3-line"></i>
                <span class="badge-dot"></span>
            </button>
            <div class="notification-dropdown">
                <div class="notif-header">Notifikasi</div>
                <div class="notif-item">
                    <i class="ri-checkbox-circle-fill" style="color: var(--accent-success); font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 500;">Sistem berjalan normal</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Baru saja</div>
                    </div>
                </div>
                <div class="notif-item">
                    <i class="ri-user-add-fill" style="color: var(--accent-primary); font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 500;">Pengguna baru terdaftar</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">5 menit lalu</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="profil.php" class="header-btn" data-tooltip="Profil">
            <i class="ri-user-line"></i>
        </a>
    </div>
</header>
