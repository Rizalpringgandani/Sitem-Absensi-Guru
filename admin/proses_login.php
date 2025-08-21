<?php
session_start();
// Panggil koneksi database dari folder config di root
require __DIR__ . '/../config/database.php';

// Ambil data dari form dan bersihkan
$username = trim($_POST['username'] ?? '');
$password_input = trim($_POST['password'] ?? '');

if (empty($username) || empty($password_input)) {
    header("Location: index.php?error=1");
    exit;
}

// Query ke tabel `admin`
$sql = "SELECT id_admin, username, nama_lengkap, password FROM admin WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username); // 's' untuk string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password_input, $admin['password'])) {
        // --- LOGIN BERHASIL ---
        session_regenerate_id(true);
        
        // Buat session khusus untuk admin
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_nama'] = $admin['nama_lengkap'];

        // Arahkan ke dashboard admin
        header("Location: dashboard.php");
        exit;
    }
}

// Jika username tidak ditemukan atau password salah
header("Location: index.php?error=1");
exit;
?>