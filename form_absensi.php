<?php
$page_title = 'Form Absensi Mengajar';
require __DIR__ . '/src/templates/header.php';
require __DIR__ . '/config/database.php';

// Cek sesi login
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: index.php");
    exit;
}
if (!isset($_GET['id_jadwal'])) {
    header("Location: dashboard.php");
    exit;
}

$id_jadwal = $_GET['id_jadwal'];
$id_guru_login = $_SESSION['id_guru'];

// Ambil data detail jadwal
$sql_detail = "SELECT j.jam_ke, j.hari, g.nama_guru, m.nama_mapel, k.nama_kelas
               FROM jadwal_pelajaran j
               JOIN guru g ON j.id_guru = g.id_guru
               JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel
               JOIN kelas k ON j.id_kelas = k.id_kelas
               WHERE j.id_jadwal = ?";
$stmt_detail = $koneksi->prepare($sql_detail);
$stmt_detail->bind_param("i", $id_jadwal);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();
$detail = $result_detail->fetch_assoc();

if (!$detail) {
    echo "Jadwal tidak ditemukan.";
    exit;
}

// Ambil daftar guru untuk dropdown pengganti
$sql_guru = "SELECT id_guru, nama_guru FROM guru WHERE id_guru != ?";
$stmt_guru = $koneksi->prepare($sql_guru);
$stmt_guru->bind_param("i", $id_guru_login);
$stmt_guru->execute();
$result_guru = $stmt_guru->get_result();
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8 space-y-6">

        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Form Absensi Mengajar
            </h2>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">
            <div class="flex justify-between text-sm"><strong class="text-gray-600 font-medium">Guru:</strong> <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($detail['nama_guru']); ?></span></div>
            <div class="flex justify-between text-sm"><strong class="text-gray-600 font-medium">Pelajaran:</strong> <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($detail['nama_mapel']); ?></span></div>
            <div class="flex justify-between text-sm"><strong class="text-gray-600 font-medium">Kelas:</strong> <span class="text-gray-900 font-semibold"><?php echo htmlspecialchars($detail['nama_kelas']); ?></span></div>
            <div class="flex justify-between text-sm"><strong class="text-gray-600 font-medium">Waktu:</strong> <span class="text-gray-900 font-semibold">Hari <?php echo htmlspecialchars($detail['hari']); ?>, Jam ke-<?php echo htmlspecialchars($detail['jam_ke']); ?></span></div>
        </div>

        <form action="src/actions/proses_absensi.php" method="POST" class="space-y-6">
            <input type="hidden" name="id_jadwal" value="<?php echo $id_jadwal; ?>">
            <input type="hidden" name="tanggal" value="<?php echo date('Y-m-d'); ?>">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <div class="text-center p-2 bg-gray-100 rounded-md">
                <p id="lokasi-status" class="text-sm font-medium text-gray-600 transition-colors duration-300">📍 Mendeteksi lokasi Anda...</p>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Kehadiran</label>
                <select id="status" name="status_kehadiran" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Alpha">Alpha</option>
                </select>
            </div>

            <div id="guru_pengganti_div" class="hidden">
                <label for="id_guru_pengganti" class="block text-sm font-medium text-gray-700">Pilih Guru Pengganti (Opsional)</label>
                <select id="id_guru_pengganti" name="id_guru_pengganti" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Tidak Ada Pengganti --</option>
                    <?php while ($guru = $result_guru->fetch_assoc()) { ?>
                        <option value="<?php echo $guru['id_guru']; ?>"><?php echo htmlspecialchars($guru['nama_guru']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                    Simpan Absensi
                </button>
            </div>
        </form>

        <div class="text-center">
            <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-blue-500 transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const statusElement = document.getElementById('lokasi-status');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const submitButton = document.querySelector('button[type="submit"]');

    submitButton.disabled = true;
    submitButton.textContent = 'Menunggu Lokasi...';
    submitButton.classList.add('bg-gray-400', 'cursor-not-allowed', 'hover:bg-gray-400');
    submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;
                statusElement.textContent = '✅ Lokasi berhasil dideteksi.';
                statusElement.classList.remove('text-gray-600');
                statusElement.classList.add('text-green-600');
                
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Absensi';
                submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed', 'hover:bg-gray-400');
                submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            },
            function(error) {
                let errorMessage = 'Gagal mendeteksi lokasi.';
                if (error.code === error.PERMISSION_DENIED) errorMessage = "Anda tidak mengizinkan akses lokasi.";
                
                statusElement.textContent = '⚠️ ' + errorMessage;
                statusElement.classList.remove('text-gray-600');
                statusElement.classList.add('text-red-600');

                submitButton.disabled = false;
                submitButton.textContent = 'Simpan Absensi (Tanpa Lokasi)';
                submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed', 'hover:bg-gray-400');
                submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
        );
    } else {
        statusElement.textContent = "Geolocation tidak didukung browser ini.";
        submitButton.disabled = false;
        submitButton.textContent = 'Simpan Absensi';
        submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed', 'hover:bg-gray-400');
        submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }
});
</script>

<?php require __DIR__ . '/src/templates/footer.php'; ?>