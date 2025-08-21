<?php
$page_title = 'Pengaturan Gaji';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

// Ambil nilai gaji saat ini dari database
$query = $koneksi->prepare("SELECT setting_nilai FROM pengaturan WHERE setting_nama = ?");
$setting_nama = 'gaji_per_jam';
$query->bind_param('s', $setting_nama);
$query->execute();
$result = $query->get_result();
$gaji_per_jam = $result->fetch_assoc()['setting_nilai'] ?? 0;
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Pengaturan Gaji Guru</h1>

<?php
// Tampilkan notifikasi jika ada
if (isset($_SESSION['notifikasi'])) {
    $notifikasi = $_SESSION['notifikasi'];
    $alert_class = $notifikasi['type'] === 'sukses' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    echo "<div class='{$alert_class} border px-4 py-3 rounded-md relative mb-4' role='alert'>". htmlspecialchars($notifikasi['pesan']) ."</div>";
    unset($_SESSION['notifikasi']);
}
?>

<div class="bg-white p-8 rounded-lg shadow-md max-w-md">
    <form action="proses.php" method="POST">
        <div>
            <label for="gaji_per_jam" class="block text-sm font-medium text-gray-700">Nominal Gaji per Jam Pelajaran (Rp)</label>
            <input type="number" id="gaji_per_jam" name="gaji_per_jam" required value="<?= htmlspecialchars($gaji_per_jam) ?>"
                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            <p class="mt-1 text-xs text-gray-500">Masukkan angka saja, tanpa titik atau koma. Contoh: 50000</p>
        </div>
        <div class="pt-6">
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>