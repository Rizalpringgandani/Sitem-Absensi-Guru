<?php
session_start();
// Set timezone default
date_default_timezone_set('Asia/Jakarta');

// Cek jika admin belum login, kecuali di halaman login itu sendiri
if (!isset($_SESSION['admin_logged_in']) && basename($_SERVER['PHP_SELF']) != 'index.php' && basename($_SERVER['PHP_SELF']) != 'proses_login.php') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Panel Admin'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex">
    <?php
    // Jangan tampilkan sidebar di halaman login
    if (isset($_SESSION['admin_logged_in'])) {
        require __DIR__ . '/sidebar.php';
    }
    ?>

    <main class="flex-1 ml-64">
        <div class="p-8">