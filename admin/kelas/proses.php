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
    $nama_kelas = $_POST['nama_kelas'] ?? '';
    if (empty($nama_kelas)) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Nama kelas tidak boleh kosong.'];
        header('Location: tambah.php');
        exit;
    }
    
    $query = $koneksi->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
    $query->bind_param("s", $nama_kelas);
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data kelas berhasil ditambahkan.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menambahkan data.'];
    }
    header('Location: index.php');
    exit;
}

// === AKSI HAPUS ===
if ($aksi === 'hapus') {
    $id_kelas = $_GET['id'] ?? 0;
    $query = $koneksi->prepare("DELETE FROM kelas WHERE id_kelas = ?");
    $query->bind_param('i', $id_kelas);
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data kelas berhasil dihapus.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menghapus data.'];
    }
    header('Location: index.php');
    exit;
}

// === AKSI EDIT ===
if ($aksi === 'edit') {
    $id_kelas = $_POST['id_kelas'] ?? 0;
    $nama_kelas = $_POST['nama_kelas'] ?? '';

    if (empty($nama_kelas) || $id_kelas <= 0) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data tidak lengkap.'];
        header('Location: edit.php?id=' . $id_kelas);
        exit;
    }

    $query = $koneksi->prepare("UPDATE kelas SET nama_kelas = ? WHERE id_kelas = ?");
    $query->bind_param("si", $nama_kelas, $id_kelas);
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data kelas berhasil diperbarui.'];
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui data.'];
    }
    header('Location: index.php');
    exit;
}

header('Location: index.php');
exit;
?>