<?php
$host = "localhost";
$db   = "db_login_hakakses";
$user = "root";
$pass = "";

try {
    // 1. Hubungkan ke MySQL Server
    $koneksi = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $pass
    );
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Buat database jika belum ada
    $koneksi->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $koneksi->exec("USE `$db`");

    // 3. Buat tabel users secara otomatis jika belum ada
    $koneksi->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'petugas', 'siswa') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 4. Periksa apakah sudah ada user di dalam tabel
    $cekUser = $koneksi->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($cekUser == 0) {
        // Otomatis isi default user
        $defaultUsers = [
            ['Administrator', 'admin', 'admin123', 'admin'],
            ['Petugas Sekolah', 'petugas', 'petugas123', 'petugas'],
            ['Siswa SMK', 'siswa', 'siswa123', 'siswa']
        ];
        $insert = $koneksi->prepare("INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)");
        foreach ($defaultUsers as $u) {
            $hashed = password_hash($u[2], PASSWORD_DEFAULT);
            $insert->execute([$u[0], $u[1], $hashed, $u[3]]);
        }
    }

} catch (PDOException $e) {
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Koneksi Database Gagal</title>
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/liquid-glass.css">
    </head>
    <body style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding:1rem;">
        <div class="glass-card" style="max-width:540px;width:100%;padding:2rem;text-align:center;">
            <div style="width:60px;height:60px;border-radius:18px;background:rgba(255,59,48,0.15);color:var(--accent-danger);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 1.25rem;">
                <i class="ri-database-2-line"></i>
            </div>
            <h2 style="font-size:1.4rem;margin-bottom:0.5rem;color:var(--text-primary);">Koneksi MySQL Gagal</h2>
            <p style="font-size:0.9rem;color:var(--text-secondary);margin-bottom:1.5rem;">
                Pastikan service MySQL di XAMPP Control Panel sudah dalam kondisi <b>Start / Running</b>.
            </p>
            <div style="font-size:0.75rem;color:var(--text-muted);word-break:break-all;margin-bottom:1rem;">
                Pesan Error: ' . htmlspecialchars($e->getMessage()) . '
            </div>
            <a href="index.php" class="btn-glass btn-glass-primary" style="display:inline-flex;">
                <i class="ri-refresh-line"></i> Refresh Halaman
            </a>
        </div>
    </body>
    </html>';
    exit;
}
?>
