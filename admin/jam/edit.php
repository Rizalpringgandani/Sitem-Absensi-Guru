<?php
$page_title = 'Edit Jam Pelajaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$jam_ke = $_GET['jam_ke'] ?? 0;
$query = $koneksi->prepare("SELECT waktu_mulai, waktu_selesai FROM jam_pelajaran WHERE jam_ke = ?");
$query->bind_param('i', $jam_ke);
$query->execute();
$result = $query->get_result();
$jam = $result->fetch_assoc();

if (!$jam) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data jam tidak ditemukan.'];
    header('Location: index.php');
    exit;
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Waktu untuk Jam Ke-<?= htmlspecialchars($jam_ke) ?></h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
        &larr; Kembali ke Pengaturan Jam
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=edit" method="POST">
        <input type="hidden" name="jam_ke" value="<?= htmlspecialchars($jam_ke) ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                <input type="time" id="waktu_mulai" name="waktu_mulai" required value="<?= htmlspecialchars(date('H:i', strtotime($jam['waktu_mulai']))) ?>"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
                <input type="time" id="waktu_selesai" name="waktu_selesai" required value="<?= htmlspecialchars(date('H:i', strtotime($jam['waktu_selesai']))) ?>"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>
        <div class="pt-6">
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>