<?php
session_start();
include __DIR__ . '/../../config/database.php';

// Cek sesi login
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: ../../index.php");
    exit;
}

// Hanya proses jika metodenya POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../dashboard.php");
    exit;
}

// Ambil semua data dari form
$id_jadwal = $_POST['id_jadwal'];
$tanggal = $_POST['tanggal'];
$status_kehadiran = $_POST['status_kehadiran'];
$keterangan = trim($_POST['keterangan']); // Hapus spasi ekstra
$id_guru_pengganti = !empty($_POST['id_guru_pengganti']) ? $_POST['id_guru_pengganti'] : NULL;

// --- Validasi Input Status ---
$status_valid = ['Hadir', 'Sakit', 'Izin', 'Alpha'];
if (!in_array($status_kehadiran, $status_valid)) {
    $_SESSION['notifikasi'] = ['error' => 'Status kehadiran tidak valid.'];
    header("Location: ../../dashboard.php");
    exit;
}

// --- Validasi Batas Waktu Absen ---
date_default_timezone_set('Asia/Jakarta');
$waktu_sekarang = time();
$sql_get_time = "SELECT jp.waktu_mulai FROM jadwal_pelajaran j JOIN jam_pelajaran jp ON j.jam_ke = jp.jam_ke WHERE j.id_jadwal = ?";
$stmt_time = $koneksi->prepare($sql_get_time);
$stmt_time->bind_param("i", $id_jadwal);
$stmt_time->execute();
$result_time = $stmt_time->get_result();
$jadwal_time = $result_time->fetch_assoc();

if ($jadwal_time) {
    $waktu_mulai_kelas = strtotime($tanggal . " " . $jadwal_time['waktu_mulai']);
    $batas_awal_absen = strtotime('-30 minutes', $waktu_mulai_kelas);
    $batas_akhir_absen = strtotime('+30 minutes', $waktu_mulai_kelas);

    if (!($waktu_sekarang >= $batas_awal_absen && $waktu_sekarang <= $batas_akhir_absen)) {
        $_SESSION['notifikasi'] = ['error' => 'Gagal! Anda melakukan absensi di luar waktu yang ditentukan.'];
        header("Location: ../../dashboard.php");
        exit;
    }
}
$stmt_time->close();

// --- Validasi Duplikat Absensi ---
$sql_cek = "SELECT id_absensi FROM absensi WHERE id_jadwal = ? AND tanggal = ?";
$stmt_cek = $koneksi->prepare($sql_cek);
$stmt_cek->bind_param("is", $id_jadwal, $tanggal);
$stmt_cek->execute();
if ($stmt_cek->get_result()->num_rows > 0) {
    $_SESSION['notifikasi'] = ['error' => 'Gagal! Absensi untuk jadwal ini sudah diisi hari ini.'];
    header("Location: ../../dashboard.php");
    exit;
}
$stmt_cek->close();

// --- Proses Simpan ke Database ---
$sql_insert = "INSERT INTO absensi (id_jadwal, tanggal, status_kehadiran, id_guru_pengganti, keterangan) VALUES (?, ?, ?, ?, ?)";
$stmt_insert = $koneksi->prepare($sql_insert);
// Tipe data: i=integer, s=string, s, i, s
$stmt_insert->bind_param("isssi", $id_jadwal, $tanggal, $status_kehadiran, $id_guru_pengganti, $keterangan);

if ($stmt_insert->execute()) {
    // Jika berhasil, kirim notifikasi sukses
    $_SESSION['notifikasi'] = ['sukses' => 'Absensi berhasil disimpan!'];
} else {
    // Jika gagal, kirim notifikasi error
    $_SESSION['notifikasi'] = ['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'];
}

$stmt_insert->close();
$koneksi->close();

// Arahkan kembali ke dashboard
header("Location: ../../dashboard.php");
exit;