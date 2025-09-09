<?php
// Atur judul halaman dinamis untuk template header
$page_title = 'Dashboard Guru';

// Memanggil template header (yang di dalamnya sudah ada session_start() dan CSS responsif)
require __DIR__ . '/src/templates/header.php';
// Memanggil file koneksi database
require __DIR__ . '/config/database.php';
date_default_timezone_set('Asia/Jakarta');
// Cek sesi login, jika tidak ada, redirect ke halaman login (index.php)
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: index.php");
    exit;
}

// Ambil data penting dari sesi dan sistem
$id_guru_login = $_SESSION['id_guru'];
$nama_guru_login = $_SESSION['nama_guru'];
$tanggal_ini = date("Y-m-d");
$waktu_sekarang = time(); // Waktu saat ini dalam format Unix timestamp

// Konversi nomor hari ke nama hari dalam Bahasa Indonesia
$nama_hari_arr = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
$nomor_hari = date("w", $waktu_sekarang);
$nama_hari_ini = $nama_hari_arr[$nomor_hari];

?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

<div class="flex justify-between items-center border-b border-gray-200 pb-5 mb-6">
    <h1 class="text-3xl font-bold text-gray-900">
        Dashboard: <?php echo htmlspecialchars($nama_guru_login); ?>
    </h1>
    <div class="flex items-center space-x-4">
        <a href="ganti_password.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
            Ganti Password
        </a>
        <a href="src/actions/proses_logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition duration-200">
            Logout
        </a>
    </div>
</div>

    <h3 class="text-xl font-semibold text-gray-800 mb-4">
        Jadwal Anda Hari Ini (<?php echo htmlspecialchars($nama_hari_ini) . ", " . date("d F Y"); ?>)
    </h3>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jam Ke-</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                <?php
                // Query untuk mengambil jadwal lengkap guru yang login pada hari ini
                $sql = "SELECT j.id_jadwal, j.jam_ke, m.nama_mapel, k.nama_kelas, jp.waktu_mulai, jp.waktu_selesai
                        FROM jadwal_pelajaran j
                        JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel
                        JOIN kelas k ON j.id_kelas = k.id_kelas
                        JOIN jam_pelajaran jp ON j.jam_ke = jp.jam_ke
                        WHERE j.id_guru = ? AND j.hari = ?
                        ORDER BY j.jam_ke ASC";
                
                $stmt = $koneksi->prepare($sql);
                $stmt->bind_param("is", $id_guru_login, $nama_hari_ini);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($jadwal = $result->fetch_assoc()) {
                        $waktu_mulai_kelas = strtotime($tanggal_ini . " " . $jadwal['waktu_mulai']);
                        $batas_awal_absen = strtotime('-30 minutes', $waktu_mulai_kelas);
                        $batas_akhir_absen = strtotime('+30 minutes', $waktu_mulai_kelas);

                        // Cek status absensi untuk jadwal ini
                        $sql_cek_absen = "SELECT status_kehadiran, TIME(waktu_absen) as jam_absen FROM absensi WHERE id_jadwal = ? AND tanggal = ?";
                        $stmt_cek = $koneksi->prepare($sql_cek_absen);
                        $stmt_cek->bind_param("is", $jadwal['id_jadwal'], $tanggal_ini);
                        $stmt_cek->execute();
                        $result_cek = $stmt_cek->get_result();
                        
                        echo "<tr class='hover:bg-gray-50'>";
                        echo "<td data-label='Jam Ke-' class='px-6 py-4 whitespace-nowrap font-medium'>" . htmlspecialchars($jadwal['jam_ke']) . "</td>";
                        echo "<td data-label='Waktu' class='px-6 py-4 whitespace-nowrap'>" . date('H:i', strtotime($jadwal['waktu_mulai'])) . " - " . date('H:i', strtotime($jadwal['waktu_selesai'])) . "</td>";
                        echo "<td data-label='Mata Pelajaran' class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($jadwal['nama_mapel']) . "</td>";
                        echo "<td data-label='Kelas' class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($jadwal['nama_kelas']) . "</td>";
                        
                        if ($result_cek->num_rows > 0) {
                            $absen = $result_cek->fetch_assoc();
                            $status_class = ($absen['status_kehadiran'] == 'Hadir') ? 'text-green-600' : 'text-yellow-600';
                            echo "<td data-label='Status' class='px-6 py-4 whitespace-nowrap font-bold $status_class'>" . htmlspecialchars($absen['status_kehadiran']) . "</td>";
                            echo "<td data-label='Aksi' class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>Diisi Pukul " . date('H:i', strtotime($absen['jam_absen'])) . "</td>";
                        } else {
                            echo "<td data-label='Status' class='px-6 py-4 whitespace-nowrap text-gray-500'>Belum Absen</td>";
                            echo "<td data-label='Aksi' class='px-6 py-4 whitespace-nowrap'>";
                            if ($waktu_sekarang >= $batas_awal_absen && $waktu_sekarang <= $batas_akhir_absen) {
                                echo "<a href='form_absensi.php?id_jadwal=" . $jadwal['id_jadwal'] . "' class='bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded-md text-sm transition'>Lakukan Absensi</a>";
                            } else {
                                echo "<span class='bg-gray-400 text-white font-semibold py-1 px-3 rounded-md text-sm cursor-not-allowed'>Di Luar Waktu</span>";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                        $stmt_cek->close();
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-10 text-gray-500'>Tidak ada jadwal mengajar untuk Anda hari ini.</td></tr>";
                }
                $stmt->close();
                $koneksi->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Memanggil template footer
require __DIR__ . '/src/templates/footer.php';
?>