<?php
require 'auth.php';
require 'config.php';
cekRole('admin');

$activePage = 'kelola_user';
$pageTitle = 'Kelola Pengguna';
$breadcrumb = 'Kelola Pengguna';

// ---- Proses Tambah Pengguna ----
if (isset($_POST['tambah'])) {
    $nama     = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $role     = $_POST['role'];
    $hash     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek duplikat username
    $check = $koneksi->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->fetchColumn() > 0) {
        header("Location: kelola_user.php?error=duplikat");
        exit;
    }

    $stmt = $koneksi->prepare("INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $username, $hash, $role]);
    header("Location: kelola_user.php?success=tambah");
    exit;
}

// ---- Proses Edit Pengguna ----
if (isset($_POST['edit'])) {
    $id       = (int) $_POST['id'];
    $nama     = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $role     = $_POST['role'];

    // Cek duplikat username (selain diri sendiri)
    $check = $koneksi->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
    $check->execute([$username, $id]);
    if ($check->fetchColumn() > 0) {
        header("Location: kelola_user.php?error=duplikat");
        exit;
    }

    if (!empty($_POST['password'])) {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=?, password=?, role=? WHERE id=?");
        $stmt->execute([$nama, $username, $hash, $role, $id]);
    } else {
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=?, role=? WHERE id=?");
        $stmt->execute([$nama, $username, $role, $id]);
    }
    header("Location: kelola_user.php?success=edit");
    exit;
}

// ---- Proses Hapus Pengguna ----
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    if ($id !== (int) $_SESSION['user_id']) {
        $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: kelola_user.php?success=hapus");
    exit;
}

// ---- Ambil semua pengguna ----
$users = $koneksi->query("SELECT id, nama, username, role FROM users ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

$avatarGradients = [
    'admin'   => 'var(--gradient-card-1)',
    'petugas' => 'var(--gradient-card-3)',
    'siswa'   => 'var(--gradient-card-4)',
];

// Untuk auto-open modal tambah
$autoOpenTambah = isset($_GET['action']) && $_GET['action'] === 'tambah';
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
                <div class="flex-between mb-3">
                    <div>
                        <h1 class="page-title">Kelola Pengguna</h1>
                        <p class="text-muted text-sm"><?= count($users) ?> pengguna terdaftar</p>
                    </div>
                    <button class="btn-glass btn-glass-primary ripple" onclick="openModal('addUserModal')">
                        <i class="ri-user-add-line"></i>
                        Tambah Pengguna
                    </button>
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

                <?php if (isset($_GET['error']) && $_GET['error'] === 'duplikat'): ?>
                <div class="alert-glass alert-danger">
                    <i class="ri-error-warning-line"></i>
                    Username sudah digunakan! Silakan pilih username lain.
                </div>
                <?php endif; ?>

                <!-- Users Table -->
                <div class="glass-table-wrapper">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pengguna</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="ri-user-line"></i>
                                        <h4>Belum ada pengguna</h4>
                                        <p>Klik "Tambah Pengguna" untuk menambahkan pengguna baru.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td><span class="text-muted">#<?= $u['id'] ?></span></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <div style="width:34px;height:34px;border-radius:10px;background:<?= $avatarGradients[$u['role']] ?? 'var(--gradient-card-1)' ?>;display:flex;align-items:center;justify-content:center;color:white;font-size:0.7rem;font-weight:600;flex-shrink:0;">
                                            <?= strtoupper(mb_substr($u['nama'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 0.88rem;"><?= htmlspecialchars($u['nama']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><code style="font-size:0.82rem; background: rgba(0,0,0,0.04); padding: 0.15rem 0.5rem; border-radius: 4px;">@<?= htmlspecialchars($u['username']) ?></code></td>
                                <td><span class="role-badge role-<?= $u['role'] ?>"><i class="ri-shield-line"></i> <?= ucfirst($u['role']) ?></span></td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 0.35rem; justify-content: flex-end;">
                                        <button class="btn-glass btn-glass-sm btn-glass-icon" data-tooltip="Edit" onclick="editUser(<?= htmlspecialchars(json_encode($u)) ?>)">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                        <button class="btn-glass btn-glass-sm btn-glass-icon btn-glass-danger" data-tooltip="Hapus" onclick="confirmDelete('?hapus=<?= $u['id'] ?>', '<?= htmlspecialchars($u['nama'], ENT_QUOTES) ?>')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Tambah Pengguna -->
    <div class="modal-overlay" id="addUserModal">
        <div class="modal-glass">
            <div class="modal-header">
                <h3><i class="ri-user-add-line" style="color: var(--accent-primary);"></i> Tambah Pengguna</h3>
                <button class="modal-close" onclick="closeModal('addUserModal')">&times;</button>
            </div>
            <form action="kelola_user.php" method="POST" class="needs-validation">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label-glass">Nama Lengkap</label>
                        <input type="text" name="nama" class="glass-input" placeholder="Contoh: John Doe" required>
                    </div>
                    <div class="edit-form-row">
                        <div class="form-group">
                            <label class="form-label-glass">Username</label>
                            <input type="text" name="username" class="glass-input" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-glass">Role</label>
                            <select name="role" class="glass-input glass-select" required>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                                <option value="siswa" selected>Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-glass">Password</label>
                        <input type="password" name="password" class="glass-input" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-glass" onclick="closeModal('addUserModal')">Batal</button>
                    <button type="submit" name="tambah" class="btn-glass btn-glass-primary ripple">
                        <i class="ri-check-line"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Pengguna -->
    <div class="modal-overlay" id="editUserModal">
        <div class="modal-glass">
            <div class="modal-header">
                <h3><i class="ri-edit-line" style="color: var(--accent-primary);"></i> Edit Pengguna</h3>
                <button class="modal-close" onclick="closeModal('editUserModal')">&times;</button>
            </div>
            <form action="kelola_user.php" method="POST" class="needs-validation">
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label-glass">Nama Lengkap</label>
                        <input type="text" name="nama" id="editNama" class="glass-input" required>
                    </div>
                    <div class="edit-form-row">
                        <div class="form-group">
                            <label class="form-label-glass">Username</label>
                            <input type="text" name="username" id="editUsername" class="glass-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-glass">Role</label>
                            <select name="role" id="editRole" class="glass-input glass-select" required>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                                <option value="siswa">Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-glass">Password <span class="text-muted">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="glass-input" placeholder="Password baru" minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-glass" onclick="closeModal('editUserModal')">Batal</button>
                    <button type="submit" name="edit" class="btn-glass btn-glass-primary ripple">
                        <i class="ri-check-line"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Hapus Konfirmasi -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-glass">
            <div class="modal-header">
                <h3><i class="ri-error-warning-line" style="color: var(--accent-danger);"></i> Konfirmasi Hapus</h3>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirm-dialog">
                    <div class="confirm-icon">
                        <i class="ri-delete-bin-line"></i>
                    </div>
                    <h4>Hapus Pengguna?</h4>
                    <p>Anda akan menghapus pengguna <strong class="delete-name"></strong>. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-glass" onclick="closeModal('deleteModal')">Batal</button>
                <a href="#" class="btn-glass btn-glass-danger delete-confirm-btn ripple">
                    <i class="ri-delete-bin-line"></i> Hapus
                </a>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        function editUser(user) {
            document.getElementById('editId').value = user.id;
            document.getElementById('editNama').value = user.nama;
            document.getElementById('editUsername').value = user.username;
            document.getElementById('editRole').value = user.role;
            openModal('editUserModal');
        }

        <?php if ($autoOpenTambah): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openModal('addUserModal');
        });
        <?php endif; ?>
    </script>
</body>
</html>
