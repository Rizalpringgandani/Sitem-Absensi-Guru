<?php
session_start();
require __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../index.php');
    exit;
}

$aksi = $_GET['aksi'] ?? '';

// === AKSI TAMBAH ===
if ($aksi === 'tambah') {
    $hari = $_POST['hari'];
    $jam_ke = $_POST['jam_ke'];
    $id_kelas = $_POST['id_kelas'];
    $id_mapel = $_POST['id_mapel'];
    $id_guru = $_POST['id_guru'];
    
    $cek_stmt = $koneksi->prepare("SELECT id_jadwal FROM jadwal_pelajaran WHERE hari = ? AND jam_ke = ? AND id_kelas = ?");
    $cek_stmt->bind_param("sii", $hari, $jam_ke, $id_kelas);
    $cek_stmt->execute();
    if ($cek_stmt->get_result()->num_rows > 0) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Jadwal bentrok! Sudah ada pelajaran di kelas, hari, dan jam yang sama.'];
        header('Location: tambah.php');
        exit;
    }
    $cek_stmt->close();

    $stmt = $koneksi->prepare("INSERT INTO jadwal_pelajaran (hari, jam_ke, id_kelas, id_mapel, id_guru) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiii", $hari, $jam_ke, $id_kelas, $id_mapel, $id_guru);
    if ($stmt->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Jadwal berhasil ditambahkan.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menambahkan jadwal.'];
    }
    $stmt->close();
    header('Location: index.php');
    exit;
}

// === AKSI EDIT ===
if ($aksi === 'edit') {
    $id_jadwal = $_POST['id_jadwal'];
    $hari = $_POST['hari'];
    $jam_ke = $_POST['jam_ke'];
    $id_kelas = $_POST['id_kelas'];
    $id_mapel = $_POST['id_mapel'];
    $id_guru = $_POST['id_guru'];

    // Validasi bentrok, tapi kecualikan jadwal yang sedang diedit
    $cek_stmt = $koneksi->prepare("SELECT id_jadwal FROM jadwal_pelajaran WHERE hari = ? AND jam_ke = ? AND id_kelas = ? AND id_jadwal != ?");
    $cek_stmt->bind_param("siii", $hari, $jam_ke, $id_kelas, $id_jadwal);
    $cek_stmt->execute();
    if ($cek_stmt->get_result()->num_rows > 0) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Jadwal bentrok dengan jadwal lain.'];
        header('Location: edit.php?id=' . $id_jadwal);
        exit;
    }
    $cek_stmt->close();

    $stmt = $koneksi->prepare("UPDATE jadwal_pelajaran SET hari=?, jam_ke=?, id_kelas=?, id_mapel=?, id_guru=? WHERE id_jadwal=?");
    $stmt->bind_param("siiiii", $hari, $jam_ke, $id_kelas, $id_mapel, $id_guru, $id_jadwal);
    if ($stmt->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Jadwal berhasil diperbarui.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui jadwal.'];
    }
    $stmt->close();
    header('Location: index.php');
    exit;
}

// === AKSI HAPUS ===
if ($aksi === 'hapus') {
    $id_jadwal = $_GET['id'] ?? 0;
    $stmt = $koneksi->prepare("DELETE FROM jadwal_pelajaran WHERE id_jadwal = ?");
    $stmt->bind_param("i", $id_jadwal);
    if ($stmt->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Jadwal berhasil dihapus.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menghapus jadwal.'];
    }
    $stmt->close();
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>