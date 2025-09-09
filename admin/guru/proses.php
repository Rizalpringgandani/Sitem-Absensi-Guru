<?php
session_start();
require __DIR__ . '/../../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../index.php');
    exit;
}

// Ambil aksi dari URL
$aksi = $_GET['aksi'] ?? '';

// =================================================================
// LOGIKA TAMBAH DATA (Sudah ada, tidak berubah)
// =================================================================
if ($aksi === 'tambah') {
    
    // ... (kode untuk tambah data guru tidak perlu diubah) ...
    $id_guru = $_POST['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'] ?? NULL;
    $kontak = $_POST['kontak'] ?? NULL;
    $password = $_POST['password'];

    if (empty($id_guru) || empty($nama_guru) || empty($password)) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Semua kolom yang wajib diisi harus diisi.'];
        header('Location: tambah.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = $koneksi->prepare("INSERT INTO guru (id_guru, nama_guru, nip, kontak, password) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("issss", $id_guru, $nama_guru, $nip, $kontak, $hashed_password);

    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data guru berhasil ditambahkan.'];
        header('Location: index.php');
    } else {
        if ($koneksi->errno == 1062) {
             $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menambahkan data. ID Guru atau NIP sudah terdaftar.'];
        } else {
             $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan saat menyimpan data: ' . $koneksi->error];
        }
        header('Location: tambah.php');
    }
    $query->close();
    $koneksi->close();
    exit;
}

// =================================================================
// LOGIKA HAPUS DATA (Tambahkan bagian ini)
// =================================================================
if ($aksi === 'hapus') {
    $id_guru = $_GET['id'] ?? 0;

    if ($id_guru > 0) {
        $query = $koneksi->prepare("DELETE FROM guru WHERE id_guru = ?");
        $query->bind_param('i', $id_guru);

        if ($query->execute()) {
            $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data guru berhasil dihapus.'];
        } else {
            $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal menghapus data guru.'];
        }
        $query->close();
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'ID guru tidak valid.'];
    }
    $koneksi->close();
    header('Location: index.php');
    exit;
}


// ... (kode untuk aksi 'hapus' ada di atasnya) ...

// =================================================================
// LOGIKA EDIT DATA
// =================================================================
if ($aksi === 'edit') {
    $id_guru = $_POST['id_guru'];
    $nama_guru = $_POST['nama_guru'];
    $nip = $_POST['nip'] ?? NULL;
    $kontak = $_POST['kontak'] ?? NULL;
    $password = $_POST['password'];

    // Validasi dasar
    if (empty($id_guru) || empty($nama_guru)) {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Nama Guru tidak boleh kosong.'];
        header('Location: edit.php?id=' . $id_guru);
        exit;
    }

    // Cek apakah password diisi atau tidak
    if (!empty($password)) {
        // Jika password diisi, update password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = $koneksi->prepare("UPDATE guru SET nama_guru = ?, nip = ?, kontak = ?, password = ? WHERE id_guru = ?");
        $query->bind_param("ssssi", $nama_guru, $nip, $kontak, $hashed_password, $id_guru);
    } else {
        // Jika password kosong, jangan update password
        $query = $koneksi->prepare("UPDATE guru SET nama_guru = ?, nip = ?, kontak = ? WHERE id_guru = ?");
        $query->bind_param("sssi", $nama_guru, $nip, $kontak, $id_guru);
    }

    // Eksekusi query
    if ($query->execute()) {
        $_SESSION['notifikasi'] = ['type' => 'sukses', 'pesan' => 'Data guru berhasil diperbarui.'];
        header('Location: index.php');
    } else {
        $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Gagal memperbarui data. NIP mungkin sudah digunakan.'];
        header('Location: edit.php?id=' . $id_guru);
    }
    $query->close();
    $koneksi->close();
    exit;
}


// Jika tidak ada aksi yang cocok, kembalikan ke halaman utama
header('Location: index.php');
exit;
?>