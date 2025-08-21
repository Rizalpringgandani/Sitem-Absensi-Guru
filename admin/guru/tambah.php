<?php
$page_title = 'Tambah Data Guru';
require __DIR__ . '/../templates/header.php'; // Panggil header
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Guru Baru</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
        &larr; Kembali ke Daftar Guru
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=tambah" method="POST">
        <div class="space-y-6">
            <div>
                <label for="id_guru" class="block text-sm font-medium text-gray-700">ID Guru (6 Digit Angka)</label>
                <input type="text" id="id_guru" name="id_guru" required maxlength="6" pattern="\d{6}" title="ID Guru harus terdiri dari 6 digit angka."
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="nama_guru" class="block text-sm font-medium text-gray-700">Nama Lengkap Guru</label>
                <input type="text" id="nama_guru" name="nama_guru" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            
            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP (Nomor Induk Pegawai)</label>
                <input type="text" id="nip" name="nip"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            
            <div>
                <label for="kontak" class="block text-sm font-medium text-gray-700">Nomor Kontak (Telepon/WA)</label>
                <input type="text" id="kontak" name="kontak"
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password Default</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Guru dapat mengganti password ini nanti.</p>
            </div>
            
            <div class="pt-4">
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Data Guru
                </button>
            </div>
        </div>
    </form>
</div>

<?php
require __DIR__ . '/../templates/footer.php'; // Panggil footer
?>