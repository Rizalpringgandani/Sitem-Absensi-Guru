<?php
$page_title = 'Rekap Absensi Guru';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

// --- Logika Filter ---
$filter_bulan = $_GET['bulan'] ?? date('m');
$filter_tahun = $_GET['tahun'] ?? date('Y');
$filter_guru = $_GET['id_guru'] ?? 'semua';

// Siapkan query dasar
$sql = "SELECT 
            a.tanggal, a.status_kehadiran, a.keterangan, TIME(a.waktu_absen) as jam_absen,
            g.nama_guru as nama_guru_utama,
            gp.nama_guru as nama_guru_pengganti,
            m.nama_mapel,
            k.nama_kelas,
            j.jam_ke
        FROM absensi a
        JOIN jadwal_pelajaran j ON a.id_jadwal = j.id_jadwal
        JOIN guru g ON j.id_guru = g.id_guru
        JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel
        JOIN kelas k ON j.id_kelas = k.id_kelas
        LEFT JOIN guru gp ON a.id_guru_pengganti = gp.id_guru
        WHERE MONTH(a.tanggal) = ? AND YEAR(a.tanggal) = ?";

$params = [$filter_bulan, $filter_tahun];
$types = "ii";

// Tambahkan filter guru jika dipilih
if ($filter_guru !== 'semua') {
    $sql .= " AND j.id_guru = ?";
    $params[] = $filter_guru;
    $types .= "i";
}

$sql .= " ORDER BY a.tanggal DESC, j.jam_ke ASC";

$query = $koneksi->prepare($sql);
$query->bind_param($types, ...$params);
$query->execute();
$result = $query->get_result();

// Ambil daftar guru untuk dropdown filter
$guru_list = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Rekap Laporan Absensi</h1>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
            <select id="bulan" name="bulan" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php for ($i = 1; $i <= 12; $i++): 
                    $bulan_val = str_pad($i, 2, '0', STR_PAD_LEFT);
                ?>
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
        <div>
            <label for="id_guru" class="block text-sm font-medium text-gray-700">Guru</label>
            <select id="id_guru" name="id_guru" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="semua">-- Semua Guru --</option>
                <?php while($guru = $guru_list->fetch_assoc()): ?>
                    <option value="<?= $guru['id_guru'] ?>" <?= $filter_guru == $guru['id_guru'] ? 'selected' : '' ?>><?= htmlspecialchars($guru['nama_guru']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="self-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                Filter
            </button>
        </div>
        <div class="self-end">
            <a href="export_harian.php?bulan=<?= $filter_bulan ?>&tahun=<?= $filter_tahun ?>&id_guru=<?= $filter_guru ?>"
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jam Ke-</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Guru</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mapel / Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Guru Pengganti</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
            <?php if ($result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['jam_ke']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['nama_guru_utama']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['nama_mapel']) ?> <br> <small class="text-gray-500"><?= htmlspecialchars($row['nama_kelas']) ?></small></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                            $status_class = 'bg-gray-200 text-gray-800';
                            if ($row['status_kehadiran'] == 'Hadir') $status_class = 'bg-green-200 text-green-800';
                            if ($row['status_kehadiran'] == 'Izin') $status_class = 'bg-blue-200 text-blue-800';
                            if ($row['status_kehadiran'] == 'Sakit') $status_class = 'bg-yellow-200 text-yellow-800';
                            if ($row['status_kehadiran'] == 'Alpha') $status_class = 'bg-red-200 text-red-800';
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $status_class ?>">
                            <?= htmlspecialchars($row['status_kehadiran']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['nama_guru_pengganti'] ?? '-') ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">Tidak ada data absensi untuk periode yang dipilih.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>