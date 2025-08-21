<?php
$page_title = 'Kelola Jam Pelajaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$query = $koneksi->query("SELECT jam_ke, waktu_mulai, waktu_selesai FROM jam_pelajaran ORDER BY jam_ke ASC");
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Pengaturan Jam Pelajaran</h1>
</div>

<?php
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jam Ke-</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Waktu Mulai</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Waktu Selesai</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
            <?php if ($query->num_rows > 0): while ($row = $query->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium"><?= htmlspecialchars($row['jam_ke']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-lg"><?= htmlspecialchars(date('H:i', strtotime($row['waktu_mulai']))) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-lg"><?= htmlspecialchars(date('H:i', strtotime($row['waktu_selesai']))) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="edit.php?jam_ke=<?= $row['jam_ke'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="4" class="text-center py-10 text-gray-500">Tidak ada data jam pelajaran.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>