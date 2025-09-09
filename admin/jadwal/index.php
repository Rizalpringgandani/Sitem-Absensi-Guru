<?php
$page_title = 'Kelola Jadwal Pelajaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Jadwal Pelajaran</h1>
    <a href="tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm">
        + Tambah Jadwal
    </a>
</div>

<?php
// Tampilkan notifikasi jika ada
if (isset($_SESSION['notifikasi'])) {
    $notifikasi = $_SESSION['notifikasi'];
    $alert_class = $notifikasi['type'] === 'sukses' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    echo "<div class='{$alert_class} border px-4 py-3 rounded-md relative mb-4' role='alert'>". htmlspecialchars($notifikasi['pesan']) ."</div>";
    unset($_SESSION['notifikasi']);
}
?>

<div class="space-y-8">
    <?php foreach ($days as $day): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800"><?= $day ?></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Ke-</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $stmt = $koneksi->prepare(
                        "SELECT j.id_jadwal, j.jam_ke, k.nama_kelas, m.nama_mapel, g.nama_guru, jp.waktu_mulai, jp.waktu_selesai
                         FROM jadwal_pelajaran j
                         JOIN kelas k ON j.id_kelas = k.id_kelas
                         JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel
                         JOIN guru g ON j.id_guru = g.id_guru
                         JOIN jam_pelajaran jp ON j.jam_ke = jp.jam_ke
                         WHERE j.hari = ? ORDER BY j.jam_ke ASC"
                    );
                    $stmt->bind_param("s", $day);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-bold"><?= $row['jam_ke'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono"><?= date('H:i', strtotime($row['waktu_mulai'])) ?> - <?= date('H:i', strtotime($row['waktu_selesai'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $row['nama_kelas'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $row['nama_mapel'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $row['nama_guru'] ?></td>
                     
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <a href="edit.php?id=<?= $row['id_jadwal'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
    <a href="proses.php?aksi=hapus&id=<?= $row['id_jadwal'] ?>" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Anda yakin ingin menghapus jadwal ini?')">Hapus</a>
</td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center px-6 py-4 text-gray-500">Tidak ada jadwal untuk hari ini.</td></tr>
                    <?php endif; $stmt->close(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>