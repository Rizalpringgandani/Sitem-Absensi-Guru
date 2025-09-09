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
    $id_absensi = $_POST['id_absensi'];
    $status_kehadiran = $_POST['status_kehadiran'];
    $id_guru_pengganti = !empty($_POST['id_guru_pengganti']) ? $_POST['id_guru_pengganti'] : NULL;
    $keterangan = $_POST['keterangan'];

    $stmt = $koneksi->prepare("UPDATE absensi SET status_kehadiran=?, id_guru_pengganti=?, keterangan=? WHERE id_absensi=?");
    $stmt->bind_param("sisi", $status_kehadiran, $id_guru_pengganti, $keterangan, $id_absensi);

    if ($stmt->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data absensi berhasil diperbarui.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui data absensi.'];
    }
    $stmt->close();
    header('Location: index.php');
    exit;
}

// === AKSI HAPUS ===
if ($aksi === 'hapus') {
    $id_absensi = $_GET['id'] ?? 0;
    $stmt = $koneksi->prepare("DELETE FROM absensi WHERE id_absensi = ?");
    $stmt->bind_param("i", $id_absensi);

    if ($stmt->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data absensi berhasil dihapus.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menghapus data absensi.'];
    }
    $stmt->close();
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>