<?php
// Panggil koneksi
require __DIR__ . '/../../config/database.php';

// --- Logika Filter (sama persis dengan di index.php) ---
$filter_bulan = $_GET['bulan'] ?? date('m');
$filter_tahun = $_GET['tahun'] ?? date('Y');
$filter_guru = $_GET['id_guru'] ?? 'semua';

$sql = "SELECT a.tanggal, j.jam_ke, g.nama_guru as nama_guru_utama, m.nama_mapel, k.nama_kelas, a.status_kehadiran, gp.nama_guru as nama_guru_pengganti, a.keterangan
        FROM absensi a
        JOIN jadwal_pelajaran j ON a.id_jadwal = j.id_jadwal
        JOIN guru g ON j.id_guru = g.id_guru
        JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel
        JOIN kelas k ON j.id_kelas = k.id_kelas
        LEFT JOIN guru gp ON a.id_guru_pengganti = gp.id_guru
        WHERE MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?";
$params = [$filter_bulan, $filter_tahun];
$types = "ii";
if ($filter_guru !== 'semua') {
    $sql .= " AND j.id_guru = ?";
    $params[] = $filter_guru;
    $types .= "i";
}
$sql .= " ORDER BY a.tanggal ASC, j.jam_ke ASC";
$query = $koneksi->prepare($sql);
$query->bind_param($types, ...$params);
$query->execute();
$result = $query->get_result();

// --- Logika Ekspor ke CSV ---
$nama_file = "laporan_harian_{$filter_bulan}_{$filter_tahun}.csv";

// Set header agar browser men-download file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $nama_file . '"');

// Buka output stream PHP
$output = fopen('php://output', 'w');

// Tulis header kolom di file CSV
fputcsv($output, ['Tanggal', 'Jam Ke', 'Nama Guru', 'Mata Pelajaran', 'Kelas', 'Status', 'Guru Pengganti', 'Keterangan']);

// Tulis data baris per baris
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        date('d-m-Y', strtotime($row['tanggal'])),
        $row['jam_ke'],
        $row['nama_guru_utama'],
        $row['nama_mapel'],
        $row['nama_kelas'],
        $row['status_kehadiran'],
        $row['nama_guru_pengganti'] ?? '-',
        $row['keterangan']
    ]);
}

fclose($output);
exit;