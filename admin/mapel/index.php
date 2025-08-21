<?php
$page_title = 'Kelola Mata Pelajaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$query = $koneksi->query("SELECT id_mapel, nama_mapel FROM mata_pelajaran ORDER BY nama_mapel ASC");
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Data Mata Pelajaran</h1>
    <a href="tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition duration-200">
        + Tambah Mapel
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

<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID Mapel</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Mata Pelajaran</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
            <?php if ($query->num_rows > 0): ?>
                <?php while ($row = $query->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?= htmlspecialchars($row['id_mapel']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['nama_mapel']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="edit.php?id=<?= $row['id_mapel'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <a href="proses.php?aksi=hapus&id=<?= $row['id_mapel'] ?>" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center py-10 text-gray-500">Tidak ada data mata pelajaran.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require __DIR__ . '/../templates/footer.php';
?>