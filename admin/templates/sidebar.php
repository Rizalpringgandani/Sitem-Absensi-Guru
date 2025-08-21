<?php
/**
 * PENTING: Sesuaikan path ini dengan nama folder proyek Anda di htdocs.
 * Contoh: Jika proyek Anda ada di http://localhost/proyek_sekolah/,
 * maka path-nya adalah '/proyek_sekolah/admin/'.
 */
$base_path = '/Web_Absen_Guru/admin/';

// Dapatkan path URI saat ini untuk menandai menu aktif
$current_uri = $_SERVER['REQUEST_URI'];
?>
<div class="w-64 h-screen bg-gray-800 text-white flex flex-col fixed">
    <div class="px-6 py-4 border-b border-gray-700">
        <h2 class="text-xl font-bold">Admin Panel</h2>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-2">
        <a href="<?= $base_path ?>dashboard.php" class="<?= strpos($current_uri, 'dashboard.php') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Dashboard
        </a>
        <a href="<?= $base_path ?>guru/" class="<?= strpos($current_uri, 'guru') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Kelola Guru
        </a>
        <a href="<?= $base_path ?>mapel/" class="<?= strpos($current_uri, 'mapel') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Kelola Mapel
        </a>
        <a href="<?= $base_path ?>kelas/" class="<?= strpos($current_uri, 'kelas') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Kelola Kelas
        </a>
        <a href="<?= $base_path ?>jam/" class="<?= strpos($current_uri, 'jam') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Kelola Jam
        </a>
        <a href="<?= $base_path ?>pengaturan/" class="<?= strpos($current_uri, 'pengaturan') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Pengaturan Gaji
        </a>
        <a href="<?= $base_path ?>rekap/" class="<?= strpos($current_uri, 'rekap/index.php') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Laporan Harian
        </a>
        <a href="<?= $base_path ?>rekap/gaji.php" class="<?= strpos($current_uri, 'rekap/gaji.php') ? 'bg-gray-900' : '' ?> block px-4 py-2 rounded-md hover:bg-gray-700">
            Laporan Gaji
        </a>
    </nav>
    <div class="px-6 py-4 border-t border-gray-700">
        <a href="<?= $base_path ?>proses_logout.php" class="block text-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg">
            Logout
        </a>
    </div>
</div>