<?php
$page_title = 'Ganti Password';
require __DIR__ . '/src/templates/header.php';

// Cek sesi login guru
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: index.php");
    exit;
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 space-y-6">
        
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Ubah Password Anda
            </h2>
        </div>

        <?php
        // Tampilkan notifikasi jika ada
        if (isset($_SESSION['notifikasi'])) {
            $notifikasi = $_SESSION['notifikasi'];
            $alert_class = $notifikasi['type'] === 'sukses' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            echo "<div class='{$alert_class} border px-4 py-3 rounded-md relative mb-4' role='alert'>". htmlspecialchars($notifikasi['pesan']) ."</div>";
            unset($_SESSION['notifikasi']);
        }
        ?>

        <form class="space-y-6" action="src/actions/proses_ganti_password.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php /* Nanti kita tambahkan CSRF di sini */ ?>">

            <div>
                <label for="password_lama" class="block text-sm font-medium text-gray-700">Password Lama</label>
                <input id="password_lama" name="password_lama" type="password" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm ...">
            </div>

            <div>
                <label for="password_baru" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input id="password_baru" name="password_baru" type="password" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm ...">
            </div>

            <div>
                <label for="konfirmasi_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input id="konfirmasi_password" name="konfirmasi_password" type="password" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm ...">
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 ...">
                    Simpan Password Baru
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

<?php require __DIR__ . '/src/templates/footer.php'; ?>