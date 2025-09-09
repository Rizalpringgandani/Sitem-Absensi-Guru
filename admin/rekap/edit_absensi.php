<?php
$page_title = 'Edit Data Absensi';
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../../config/database.php';

$id_absensi = $_GET['id'] ?? 0;

// Ambil data absensi yang akan diedit
$stmt = $koneksi->prepare("SELECT a.*, g.nama_guru, m.nama_mapel, k.nama_kelas 
                           FROM absensi a 
                           JOIN jadwal_pelajaran j ON a.id_jadwal = j.id_jadwal
                           JOIN guru g ON j.id_guru = g.id_guru
                           JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel
                           JOIN kelas k ON j.id_kelas = k.id_kelas
                           WHERE a.id_absensi = ?");
$stmt->bind_param("i", $id_absensi);
$stmt->execute();
$absensi = $stmt->get_result()->fetch_assoc();

if (!$absensi) {
    $_SESSION['notifikasi'] = ['type' => 'error', 'pesan' => 'Data absensi tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

// Ambil daftar guru untuk dropdown guru pengganti
$guru_list = $koneksi->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Data Absensi</h1>

<div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 space-y-2">
        <p><strong>Tanggal:</strong> <?= htmlspecialchars(date('d F Y', strtotime($absensi['tanggal']))) ?></p>
        <p><strong>Guru Utama:</strong> <?= htmlspecialchars($absensi['nama_guru']) ?></p>
        <p><strong>Mapel / Kelas:</strong> <?= htmlspecialchars($absensi['nama_mapel']) ?> / <?= htmlspecialchars($absensi['nama_kelas']) ?></p>
    </div>

    <form action="proses_absensi.php?aksi=edit" method="POST" class="space-y-6">
        <input type="hidden" name="id_absensi" value="<?= htmlspecialchars($absensi['id_absensi']) ?>">

        <div>
            <label for="status_kehadiran" class="block text-sm font-medium text-gray-700">Status Kehadiran</label>
            <select id="status_kehadiran" name="status_kehadiran" required class="mt-1 block w-full ... (class css sama)">
                <?php $status_list = ['Hadir', 'Sakit', 'Izin', 'Alpha']; ?>
                <?php foreach ($status_list as $status): ?>
                    <option value="<?= $status ?>" <?= ($status == $absensi['status_kehadiran']) ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="id_guru_pengganti" class="block text-sm font-medium text-gray-700">Guru Pengganti (Opsional)</label>
            <select id="id_guru_pengganti" name="id_guru_pengganti" class="mt-1 block w-full ... (class css sama)">
                <option value="">-- Tidak Ada --</option>
                <?php while($guru = $guru_list->fetch_assoc()): ?>
                    <option value="<?= $guru['id_guru'] ?>" <?= ($guru['id_guru'] == $absensi['id_guru_pengganti']) ? 'selected' : '' ?>><?= $guru['nama_guru'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full ..."><?= htmlspecialchars($absensi['keterangan']) ?></textarea>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-2 px-4 ... (class css sama)">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>