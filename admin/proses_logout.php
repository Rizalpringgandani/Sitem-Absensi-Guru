<?php
session_start();
$pesan_logout = "Anda telah berhasil logout.";

session_unset();
session_destroy();

session_start();
$_SESSION['notifikasi_logout'] = $pesan_logout;

// Arahkan kembali ke login admin
header("Location: index.php");
exit;
?>