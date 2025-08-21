
<?php
$page_title = 'Rekap Gaji Guru';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

// --- Logika Filter ---
$filter_bulan = $_GET['bulan'] ?? date('m');
$filter_tahun = $_GET['tahun'] ?? date('Y');

// 1. Ambil nilai gaji per jam dari pengaturan
$query_gaji = $koneksi->query("SELECT setting_nilai FROM pengaturan WHERE setting_nama = 'gaji_per_jam'");
$gaji_per_jam = $query_gaji->fetch_assoc()['setting_nilai'] ?? 0;


// 2. Ambil semua data guru beserta rekap jam mengajarnya
// Query ini menggunakan subquery untuk menghitung jam utama dan jam pengganti
$sql = "SELECT
            g.id_guru,
            g.nama_guru,
             g.nip,
            (SELECT COUNT(*) 
             FROM absensi a 
             JOIN jadwal_pelajaran j ON a.id_jadwal = j.id_jadwal 
             WHERE j.id_guru = g.id_guru AND a.status_kehadiran = 'Hadir' AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?) as jam_utama,
            (SELECT COUNT(*) 
             FROM absensi a 
             WHERE a.id_guru_pengganti = g.id_guru AND MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?) as jam_pengganti
        FROM guru g
        ORDER BY g.nama_guru ASC";

$stmt = $koneksi->prepare($sql);
// Binding parameter: i (integer), i, i, i
$stmt->bind_param("iiii", $filter_bulan, $filter_tahun, $filter_bulan, $filter_tahun);
$stmt->execute();
$result = $stmt->get_result();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Rekapitulasi Gaji Guru</h1>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
            <select id="bulan" name="bulan" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php for ($i = 1; $i <= 12; $i++): $bulan_val = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                    <option value="<?= $bulan_val ?>" <?= $filter_bulan == $bulan_val ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
            <select id="tahun" name="tahun" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                    <option value="<?= $i ?>" <?= $filter_tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="self-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                Filter
            </button>
        </div>
        <div class="self-end">
             <a href="export_gaji.php?bulan=<?= $filter_bulan ?>&tahun=<?= $filter_tahun ?>"
               class="w-full block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                Ekspor Excel
            </a>
        </div>
    </form>
</div>

<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Guru</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">NIP</th>
                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Jam Utama</th>
                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Jam Pengganti</th>
                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Total Jam</th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">Gaji / Jam</th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">Total Gaji</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
            <?php if ($result->num_rows > 0): while ($row = $result->fetch_assoc()): 
                $total_jam = $row['jam_utama'] + $row['jam_pengganti'];
                $total_gaji = $total_jam * $gaji_per_jam;
            ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium"><?= htmlspecialchars($row['nama_guru']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['nip'] ?? '-') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-center"><?= $row['jam_utama'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-center"><?= $row['jam_pengganti'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-lg"><?= $total_jam ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">Rp <?= number_format($gaji_per_jam, 0, ',', '.') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-blue-600 text-lg">Rp <?= number_format($total_gaji, 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">Tidak ada data guru.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>