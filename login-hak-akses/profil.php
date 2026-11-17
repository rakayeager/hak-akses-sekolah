<?php
require 'auth.php';
require 'config.php';
cekLogin();

$activePage = 'profil';
$pageTitle  = 'Profil Saya';
$breadcrumb = 'Profil';

// Get current user data
$stmt = $koneksi->prepare("SELECT id, nama, username, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Process profile update
if (isset($_POST['update_profil'])) {
    $nama     = trim($_POST['nama']);
    $username = trim($_POST['username']);

    // Check duplicate username
    $check = $koneksi->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
    $check->execute([$username, $user['id']]);
    if ($check->fetchColumn() > 0) {
        header("Location: profil.php?error=duplikat");
        exit;
    }

    if (!empty($_POST['password'])) {
        if (strlen($_POST['password']) < 6) {
            header("Location: profil.php?error=password");
            exit;
        }
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=?, password=? WHERE id=?");
        $stmt->execute([$nama, $username, $hash, $user['id']]);
    } else {
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=? WHERE id=?");
        $stmt->execute([$nama, $username, $user['id']]);
    }

    // Update session
    $_SESSION['nama']     = $nama;
    $_SESSION['username'] = $username;

    header("Location: profil.php?success=1");
    exit;
}

$avatarGradients = [
    'admin'   => 'var(--gradient-card-1)',
    'petugas' => 'var(--gradient-card-3)',
    'siswa'   => 'var(--gradient-card-4)',
];
$avatarBg = $avatarGradients[$user['role']] ?? 'var(--gradient-card-1)';
$initials = strtoupper(mb_substr($user['nama'], 0, 2));
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
                <div class="mb-3">
                    <h1 class="page-title">Profil Saya</h1>
                    <p class="text-muted text-sm">Lihat dan edit informasi akun Anda</p>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert-glass alert-success">
                    <i class="ri-check-line"></i>
                    Profil berhasil diperbarui!
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                <div class="alert-glass alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <?php
                    if ($_GET['error'] === 'duplikat') echo 'Username sudah digunakan!';
                    elseif ($_GET['error'] === 'password') echo 'Password minimal 6 karakter!';
                    else echo 'Terjadi kesalahan!';
                    ?>
                </div>
                <?php endif; ?>

                <div class="grid-2">
                    <!-- Profile Card -->
                    <div class="glass-card" style="padding: 0; overflow: hidden;">
                        <div style="height: 100px; background: <?= $avatarBg ?>; opacity: 0.3;"></div>
                        <div style="padding: 0 1.5rem 1.5rem; margin-top: -50px; position: relative;">
                            <div class="profile-avatar" style="background: <?= $avatarBg ?>;">
                                <?= $initials ?>
                            </div>
                            <div class="text-center">
                                <div class="profile-name"><?= htmlspecialchars($user['nama']) ?></div>
                                <div class="profile-username">@<?= htmlspecialchars($user['username']) ?></div>
                                <span class="role-badge role-<?= $user['role'] ?>">
                                    <i class="ri-shield-line"></i> <?= ucfirst($user['role']) ?>
                                </span>
                            </div>

                            <div class="profile-info-grid mt-3">
                                <div class="profile-info-item">
                                    <label>ID Pengguna</label>
                                    <span>#<?= $user['id'] ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <label>Username</label>
                                    <span>@<?= htmlspecialchars($user['username']) ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <label>Nama Lengkap</label>
                                    <span><?= htmlspecialchars($user['nama']) ?></span>
                                </div>
                                <div class="profile-info-item">
                                    <label>Hak Akses</label>
                                    <span><?= ucfirst($user['role']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h3 style="font-size: 1.1rem; margin-bottom: 1.25rem;">
                            <i class="ri-edit-line" style="color: var(--accent-primary);"></i> Edit Profil
                        </h3>
                        <form action="profil.php" method="POST" class="needs-validation">
                            <div class="form-group">
                                <label class="form-label-glass">Nama Lengkap</label>
                                <input type="text" name="nama" class="glass-input" value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-glass">Username</label>
                                <input type="text" name="username" class="glass-input" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label-glass">Password Baru <span class="text-muted">(kosongkan jika tidak diubah)</span></label>
                                <div style="position: relative;">
                                    <input type="password" name="password" class="glass-input" placeholder="Minimal 6 karakter" minlength="6">
                                    <button type="button" class="password-toggle">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label-glass">Role</label>
                                <input type="text" class="glass-input" value="<?= ucfirst($user['role']) ?>" disabled style="opacity: 0.7;">
                                <small class="text-muted text-sm">Role hanya bisa diubah oleh admin</small>
                            </div>
                            <button type="submit" name="update_profil" class="btn-glass btn-glass-primary ripple" style="width: 100%;">
                                <i class="ri-save-line"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
