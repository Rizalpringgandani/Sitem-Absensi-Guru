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
    $nama_mapel = $_POST['nama_mapel'] ?? '';
    if (empty($nama_mapel)) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Nama mata pelajaran tidak boleh kosong.'];
        header('Location: tambah.php');
        exit;
    }
    
    $query = $koneksi->prepare("INSERT INTO mata_pelajaran (nama_mapel) VALUES (?)");
    $query->bind_param("s", $nama_mapel);
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Mata pelajaran berhasil ditambahkan.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menambahkan data.'];
    }
    header('Location: index.php');
    exit;
}

// === AKSI HAPUS ===
if ($aksi === 'hapus') {
    $id_mapel = $_GET['id'] ?? 0;
    $query = $koneksi->prepare("DELETE FROM mata_pelajaran WHERE id_mapel = ?");
    $query->bind_param('i', $id_mapel);
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Mata pelajaran berhasil dihapus.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menghapus data.'];
    }
    header('Location: index.php');
    exit;
}

// === AKSI EDIT ===
if ($aksi === 'edit') {
    $id_mapel = $_POST['id_mapel'] ?? 0;
    $nama_mapel = $_POST['nama_mapel'] ?? '';

    if (empty($nama_mapel) || $id_mapel <= 0) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data tidak lengkap.'];
        header('Location: edit.php?id=' . $id_mapel);
        exit;
    }

    $query = $koneksi->prepare("UPDATE mata_pelajaran SET nama_mapel = ? WHERE id_mapel = ?");
    $query->bind_param("si", $nama_mapel, $id_mapel);
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Mata pelajaran berhasil diperbarui.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui data.'];
    }
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>