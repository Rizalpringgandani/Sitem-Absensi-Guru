<?php
$page_title = 'Edit Jadwal';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

// Ambil ID jadwal dari URL
$id_jadwal = $_GET['id'] ?? 0;

// Ambil data jadwal yang akan diedit
$stmt = $koneksi->prepare("SELECT * FROM jadwal_pelajaran WHERE id_jadwal = ?");
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$jadwal = $stmt->get_result()->fetch_assoc();
if (!$jadwal) {
    // Jika tidak ada data, redirect dengan error
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Jadwal tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

// Ambil semua data untuk dropdown (sama seperti di tambah.php)
$kelas_list = $koneksi->query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas");
$mapel_list = $koneksi->query("SELECT id_mapel, nama_mapel FROM mata_pelajaran ORDER BY nama_mapel");
$guru_list = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru");
$jam_list = $koneksi->query("SELECT jam_ke FROM jam_pelajaran ORDER BY jam_ke");
$hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Jadwal</h1>
    <a href="index.php" class="text-sm font-medium text-gray-600 hover:text-blue-500">&larr; Kembali</a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="proses.php?aksi=edit" method="POST" class="space-y-6">
        <input type="hidden" name="id_jadwal" value="<?= htmlspecialchars($jadwal['id_jadwal']) ?>">
        
        <div>
            <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
            <select id="hari" name="hari" required class="mt-1 block w-full px-3 py-2 bg-white ... (class css sama)">
                <?php foreach ($hari_list as $hari): ?>
                    <option value="<?= $hari ?>" <?= ($hari == $jadwal['hari']) ? 'selected' : '' ?>><?= $hari ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="jam_ke" class="block text-sm font-medium text-gray-700">Jam Ke</label>
            <select id="jam_ke" name="jam_ke" required class="mt-1 block w-full ...">
                <?php while($jam = $jam_list->fetch_assoc()): ?>
                    <option value="<?= $jam['jam_ke'] ?>" <?= ($jam['jam_ke'] == $jadwal['jam_ke']) ? 'selected' : '' ?>>Jam ke-<?= $jam['jam_ke'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="id_kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
            <select id="id_kelas" name="id_kelas" required class="mt-1 block w-full ...">
                <?php while($kelas = $kelas_list->fetch_assoc()): ?>
                    <option value="<?= $kelas['id_kelas'] ?>" <?= ($kelas['id_kelas'] == $jadwal['id_kelas']) ? 'selected' : '' ?>><?= $kelas['nama_kelas'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="id_mapel" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
            <select id="id_mapel" name="id_mapel" required class="mt-1 block w-full ...">
                 <?php while($mapel = $mapel_list->fetch_assoc()): ?>
                    <option value="<?= $mapel['id_mapel'] ?>" <?= ($mapel['id_mapel'] == $jadwal['id_mapel']) ? 'selected' : '' ?>><?= $mapel['nama_mapel'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="id_guru" class="block text-sm font-medium text-gray-700">Guru Pengajar</label>
            <select id="id_guru" name="id_guru" required class="mt-1 block w-full ...">
                 <?php while($guru = $guru_list->fetch_assoc()): ?>
                    <option value="<?= $guru['id_guru'] ?>" <?= ($guru['id_guru'] == $jadwal['id_guru']) ? 'selected' : '' ?>><?= $guru['nama_guru'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-2 px-4 ... (class css sama)">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>