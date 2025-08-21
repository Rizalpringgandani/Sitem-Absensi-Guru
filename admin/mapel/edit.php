<?php
$page_title = 'Edit Mata Pelajaran';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$id_mapel = $_GET['id'] ?? 0;
$query = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE id_mapel = ?");
$query->bind_param('i', $id_mapel);
$query->execute();
$result = $query->get_result();
$mapel = $result->fetch_assoc();

if (!$mapel) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data tidak ditemukan.'];
    header('Location: index.php');
    exit;
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Mata Pelajaran</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
        &larr; Kembali ke Daftar Mapel
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=edit" method="POST">
        <input type="hidden" name="id_mapel" value="<?= htmlspecialchars($id_mapel) ?>">
        <div>
            <label for="nama_mapel" class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran</label>
            <input type="text" id="nama_mapel" name="nama_mapel" required value="<?= htmlspecialchars($mapel['nama_mapel']) ?>"
                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="pt-6">
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php
require __DIR__ . '/../templates/footer.php';
?>