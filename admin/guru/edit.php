<?php
$page_title = 'Edit Data Guru';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

// Ambil ID dari URL dan pastikan valid
$id_guru = $_GET['id'] ?? 0;
if ($id_guru <= 0) {
    // Jika ID tidak valid, beri notifikasi dan redirect
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'ID Guru tidak valid.'];
    header('Location: index.php');
    exit;
}

// Ambil data guru yang akan diedit dari database
$query = $koneksi->prepare("SELECT id_guru, nama_guru, nip, kontak FROM guru WHERE id_guru = ?");
$query->bind_param('i', $id_guru);
$query->execute();
$result = $query->get_result();
$guru = $result->fetch_assoc();

// Jika data guru tidak ditemukan
if (!$guru) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data Guru tidak ditemukan.'];
    header('Location: index.php');
    exit;
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Data Guru</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
        &larr; Kembali ke Daftar Guru
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=edit" method="POST">
        <input type="hidden" name="id_guru" value="<?= htmlspecialchars($guru['id_guru']) ?>">

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">ID Guru</label>
                <input type="text" value="<?= htmlspecialchars($guru['id_guru']) ?>" disabled
                       class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="nama_guru" class="block text-sm font-medium text-gray-700">Nama Lengkap Guru</label>
                <input type="text" id="nama_guru" name="nama_guru" required value="<?= htmlspecialchars($guru['nama_guru']) ?>"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            
            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                <input type="text" id="nip" name="nip" value="<?= htmlspecialchars($guru['nip'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="kontak" class="block text-sm font-medium text-gray-700">Nomor Kontak</label>
                <input type="text" id="kontak" name="kontak" value="<?= htmlspecialchars($guru['kontak'] ?? '') ?>"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Ubah Password</label>
                <input type="password" id="password" name="password"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
            </div>
            
            <div class="pt-4">
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<?php
require __DIR__ . '/../templates/footer.php';
?>