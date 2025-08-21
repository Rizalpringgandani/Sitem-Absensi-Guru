<?php
$page_title = 'Tambah Kelas';
require __DIR__ . '/../templates/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Kelas Baru</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
        &larr; Kembali ke Daftar Kelas
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=tambah" method="POST">
        <div>
            <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas (Contoh: X-A, XI-IPA-1)</label>
            <input type="text" id="nama_kelas" name="nama_kelas" required
                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>
        <div class="pt-6">
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan Kelas
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>