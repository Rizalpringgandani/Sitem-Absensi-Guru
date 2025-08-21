<?php
session_start();
// Set timezone default
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Sistem Absensi Guru'; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Anda bisa menambahkan sedikit custom CSS di sini jika diperlukan */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">