<?php
$page_title = 'Tambah Jadwal';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

// Ambil data untuk dropdown
$kelas_list = $koneksi->query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas");
$mapel_list = $koneksi->query("SELECT id_mapel, nama_mapel FROM mata_pelajaran ORDER BY nama_mapel");
$guru_list = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru");
$jam_list = $koneksi->query("SELECT jam_ke, waktu_mulai, waktu_selesai FROM jam_pelajaran ORDER BY jam_ke");
$hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Jadwal Baru</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500">&larr; Kembali</a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=tambah" method="POST" class="space-y-6">
        
        <div>
            <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
            <select id="hari" name="hari" required class="mt-1 block w-full ... (class css sama seperti form lain)">
                <?php foreach ($hari_list as $hari): ?>
                    <option value="<?= $hari ?>"><?= $hari ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="jam_ke" class="block text-sm font-medium text-gray-700">Jam Ke</label>
            <select id="jam_ke" name="jam_ke" required class="mt-1 block w-full ...">
                <?php while($jam = $jam_list->fetch_assoc()): ?>
                    <option value="<?= $jam['jam_ke'] ?>">Jam ke-<?= $jam['jam_ke'] ?> (<?= date('H:i', strtotime($jam['waktu_mulai'])) ?> - <?= date('H:i', strtotime($jam['waktu_selesai'])) ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="id_kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
            <select id="id_kelas" name="id_kelas" required class="mt-1 block w-full ...">
                <?php while($kelas = $kelas_list->fetch_assoc()): ?>
                    <option value="<?= $kelas['id_kelas'] ?>"><?= $kelas['nama_kelas'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="id_mapel" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
            <select id="id_mapel" name="id_mapel" required class="mt-1 block w-full ...">
                 <?php while($mapel = $mapel_list->fetch_assoc()): ?>
                    <option value="<?= $mapel['id_mapel'] ?>"><?= $mapel['nama_mapel'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="id_guru" class="block text-sm font-medium text-gray-700">Guru Pengajar</label>
            <select id="id_guru" name="id_guru" required class="mt-1 block w-full ...">
                 <?php while($guru = $guru_list->fetch_assoc()): ?>
                    <option value="<?= $guru['id_guru'] ?>"><?= $guru['nama_guru'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-2 px-4 ... (class css sama)">
                Simpan Jadwal
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>