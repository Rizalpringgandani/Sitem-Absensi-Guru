<?php
session_start();
require __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../index.php');
    exit;
}

$aksi = $_GET['aksi'] ?? '';

// === AKSI EDIT ===
if ($aksi === 'edit') {
    $jam_ke = $_POST['jam_ke'] ?? 0;
    $waktu_mulai = $_POST['waktu_mulai'] ?? '';
    $waktu_selesai = $_POST['waktu_selesai'] ?? '';

    if (empty($waktu_mulai) || empty($waktu_selesai) || $jam_ke <= 0) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Semua kolom harus diisi.'];
        header('Location: edit.php?jam_ke=' . $jam_ke);
        exit;
    }

    $query = $koneksi->prepare("UPDATE jam_pelajaran SET waktu_mulai = ?, waktu_selesai = ? WHERE jam_ke = ?");
    $query->bind_param("ssi", $waktu_mulai, $waktu_selesai, $jam_ke);
    
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Waktu pelajaran berhasil diperbarui.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui data.'];
    }
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>