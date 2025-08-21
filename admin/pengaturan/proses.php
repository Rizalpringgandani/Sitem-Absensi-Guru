<?php
session_start();
require __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gaji_per_jam = $_POST['gaji_per_jam'] ?? 0;

    // Validasi input harus angka
    if (!is_numeric($gaji_per_jam) || $gaji_per_jam < 0) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Nominal gaji harus berupa angka positif.'];
        header('Location: index.php');
        exit;
    }

    $setting_nama = 'gaji_per_jam';
    $query = $koneksi->prepare("UPDATE pengaturan SET setting_nilai = ? WHERE setting_nama = ?");
    $query->bind_param("ss", $gaji_per_jam, $setting_nama);
    
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Pengaturan gaji berhasil diperbarui.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui pengaturan.'];
    }
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>