<?php
// Selalu mulai sesi di awal script
session_start();

// Siapkan pesan notifikasi
$pesan_logout = "Anda telah berhasil logout.";

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Mulai sesi baru HANYA untuk membawa pesan notifikasi
session_start();
$_SESSION['notifikasi_logout'] = $pesan_logout;

// Arahkan pengguna kembali ke halaman login
header("Location: ../../index.php");
exit;
?>