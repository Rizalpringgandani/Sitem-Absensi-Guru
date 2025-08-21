<?php
$page_title = 'Dashboard Admin';
require __DIR__ . '/templates/header.php';
require __DIR__ . '/../config/database.php'; // Koneksi ke database

// Ambil data ringkasan dari database
$total_guru = $koneksi->query("SELECT COUNT(id_guru) as total FROM guru")->fetch_assoc()['total'];
$total_kelas = $koneksi->query("SELECT COUNT(id_kelas) as total FROM kelas")->fetch_assoc()['total'];
$total_mapel = $koneksi->query("SELECT COUNT(id_mapel) as total FROM mata_pelajaran")->fetch_assoc()['total'];
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Jumlah Guru</p>
                <p class="text-3xl font-bold text-gray-800"><?= $total_guru ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Jumlah Kelas</p>
                <p class="text-3xl font-bold text-gray-800"><?= $total_kelas ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Jumlah Mata Pelajaran</p>
                <p class="text-3xl font-bold text-gray-800"><?= $total_mapel ?></p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, <?= htmlspecialchars($_SESSION['admin_nama']) ?>!</h2>
    <p class="mt-2 text-gray-600">Anda login sebagai administrator. Gunakan menu di samping untuk mengelola data sistem absensi.</p>
</div>

<?php
require __DIR__ . '/templates/footer.php';
?>