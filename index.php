<?php
// Atur judul halaman untuk template header
$page_title = 'Login Guru';

// Memanggil template header
require __DIR__ . '/src/templates/header.php';

// Jika pengguna sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['is_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 space-y-6">
        
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Login Sistem Absensi
            </h2>
        </div>

        <?php
        // === BAGIAN BARU UNTUK NOTIFIKASI LOGOUT ===
        // Cek apakah ada notifikasi logout di session
        if (isset($_SESSION['notifikasi_logout'])) {
            echo '
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">' . htmlspecialchars($_SESSION['notifikasi_logout']) . '</span>
            </div>';
            
            // Hapus notifikasi dari session agar tidak muncul lagi
            unset($_SESSION['notifikasi_logout']);
        }

        // Tampilkan pesan error jika ada dari proses_login.php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 1) {
                echo '
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative" role="alert">
                    <strong class="font-bold">Login Gagal!</strong>
                    <span class="block sm:inline">ID Guru atau Password salah.</span>
                </div>';
            }
        }
        ?>

        <form class="space-y-6" action="src/actions/proses_login.php" method="POST">
            
            <div>
                <label for="id_guru" class="block text-sm font-medium text-gray-700">
                    ID Guru
                </label>
                <div class="mt-1">
                    <input id="id_guru" name="id_guru" type="text" required autofocus
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Contoh: 100001">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login
                </button>
            </div>
        </form>

    </div>
</div>

<?php
require __DIR__ . '/src/templates/footer.php';
?>