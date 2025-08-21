<?php
// Panggil koneksi database
require __DIR__ . '/../../config/database.php';

// --- Logika Filter ---
$filter_bulan = $_GET['bulan'] ?? date('m');
$filter_tahun = $_GET['tahun'] ?? date('Y');

// Ambil nilai gaji per jam dari tabel pengaturan
$query_gaji = $koneksi->query("SELECT setting_nilai FROM pengaturan WHERE setting_nama = 'gaji_per_jam'");
$gaji_per_jam = $query_gaji->fetch_assoc()['setting_nilai'] ?? 0;

// Query rekap gaji (sama persis dengan di gaji.php)
$sql = "SELECT 
            g.nama_guru, 
            g.nip,
            (SELECT COUNT(*) FROM absensi a JOIN jadwal_pelajaran j ON a.id_jadwal = j.id_jadwal WHERE j.id_guru = g.id_guru AND a.status_kehadiran = 'Hadir' AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?) as jam_utama,
            (SELECT COUNT(*) FROM absensi a WHERE a.id_guru_pengganti = g.id_guru AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?) as jam_pengganti
        FROM guru g ORDER BY g.nama_guru ASC";
        
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("iiii", $filter_bulan, $filter_tahun, $filter_bulan, $filter_tahun);
$stmt->execute();
$result = $stmt->get_result();

// --- Logika Ekspor ke CSV ---
$nama_file = "laporan_gaji_{$filter_bulan}_{$filter_tahun}.csv";

// Atur header HTTP agar browser men-download file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $nama_file . '"');

// Buka output stream PHP untuk menulis file
$output = fopen('php://output', 'w');

// Tulis baris header di file CSV
fputcsv($output, ['Nama Guru', 'NIP', 'Jam Utama', 'Jam Pengganti', 'Total Jam', 'Gaji Per Jam (Rp)', 'Total Gaji (Rp)']);

// Tulis data gaji baris per baris
while ($row = $result->fetch_assoc()) {
    $total_jam = $row['jam_utama'] + $row['jam_pengganti'];
    $total_gaji = $total_jam * $gaji_per_jam;

    fputcsv($output, [
        $row['nama_guru'],
        $row['nip'] ?? '-',
        $row['jam_utama'],
        $row['jam_pengganti'],
        $total_jam,
        $gaji_per_jam,
        $total_gaji
    ]);
}

// Tutup output stream
fclose($output);
exit;