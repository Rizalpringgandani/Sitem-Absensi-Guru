<?php
session_start();
require __DIR__ . '/../../config/database.php';

// Pastikan guru sudah login
if (!isset($_SESSION['is_logged_in'])) {
    header('Location: ../../index.php');
    exit;
}

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../ganti_password.php');
    exit;
}

$id_guru = $_SESSION['id_guru'];
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// 1. Validasi input dasar
if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Semua kolom harus diisi.'];
    header('Location: ../../ganti_password.php');
    exit;
}

// 2. Cek apakah password baru dan konfirmasi cocok
if ($password_baru !== $konfirmasi_password) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Password baru dan konfirmasi tidak cocok.'];
    header('Location: ../../ganti_password.php');
    exit;
}

// 3. Ambil hash password saat ini dari database
$stmt = $koneksi->prepare("SELECT password FROM guru WHERE id_guru = ?");
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$hashed_password_from_db = $user['password'];

// 4. Verifikasi password lama
if (!password_verify($password_lama, $hashed_password_from_db)) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Password lama yang Anda masukkan salah.'];
    header('Location: ../../ganti_password.php');
    exit;
}

// 5. Jika semua validasi lolos, hash password baru dan update database
$hashed_password_baru = password_hash($password_baru, PASSWORD_DEFAULT);

$update_stmt = $koneksi->prepare("UPDATE guru SET password = ? WHERE id_guru = ?");
$update_stmt->bind_param("si", $hashed_password_baru, $id_guru);

if ($update_stmt->execute()) {
    $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Password Anda telah berhasil diperbarui!'];
    header('Location: ../../ganti_password.php');
} else {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan saat memperbarui password.'];
    header('Location: ../../ganti_password.php');
}

$stmt->close();
$update_stmt->close();
$koneksi->close();
exit;