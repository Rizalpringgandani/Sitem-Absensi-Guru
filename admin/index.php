<?php
// Atur judul halaman
$page_title = 'Login Admin';

// Panggil header
require __DIR__ . '/templates/header.php';

// Jika admin sudah login, langsung arahkan ke dashboard admin
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-8 space-y-6">
        
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Panel Admin
            </h2>
        </div>

        <?php
        // Notifikasi logout atau error
        if (isset($_SESSION['notifikasi_logout'])) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative" role="alert">' . htmlspecialchars($_SESSION['notifikasi_logout']) . '</div>';
            unset($_SESSION['notifikasi_logout']);
        }
        if (isset($_GET['error'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative" role="alert">Username atau Password salah.</div>';
        }
        ?>

        <form class="space-y-6" action="proses_login.php" method="POST">
            
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1">
                    <input id="username" name="username" type="text" required autofocus
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700">
                    Login
                </button>
            </div>
            
        </form>
    </div>
</div>

<?php
// Panggil footer
require __DIR__ . '/templates/footer.php';
?>