<?php
$page_title = 'Edit Kelas';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$id_kelas = $_GET['id'] ?? 0;
$query = $koneksi->prepare("SELECT nama_kelas FROM kelas WHERE id_kelas = ?");
$query->bind_param('i', $id_kelas);
$query->execute();
$result = $query->get_result();
$kelas = $result->fetch_assoc();

if (!$kelas) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data tidak ditemukan.'];
    header('Location: index.php');
    exit;
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Kelas</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
        &larr; Kembali ke Daftar Kelas
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=edit" method="POST">
        <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($id_kelas) ?>">
        <div>
            <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
            <input type="text" id="nama_kelas" name="nama_kelas" required value="<?= htmlspecialchars($kelas['nama_kelas']) ?>"
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

<?php require __DIR__ . '/../templates/footer.php'; ?>