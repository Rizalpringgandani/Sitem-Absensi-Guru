<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Sistem Absensi Guru'; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Terapkan hanya untuk layar di bawah 768px (ukuran tablet/mobile) */
        @media screen and (max-width: 768px) {
            /* Sembunyikan header tabel standar */
            table thead {
                display: none;
            }
            /* Ubah baris menjadi kartu yang menumpuk */
            table tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                border: 1px solid #e5e7eb;
            }
            /* Ubah sel menjadi baris di dalam kartu */
            table td {
                display: flex;
                justify-content: space-between; /* Label di kiri, data di kanan */
                align-items: center;
                text-align: right; /* Ratakan data ke kanan */
                padding-left: 1rem;
                padding-right: 1rem;
                border-bottom: 1px solid #f3f4f6;
            }
            /* Hapus border bawah pada sel terakhir di setiap kartu */
            table tr td:last-child {
                border-bottom: none;
            }
            /* Tampilkan label dari atribut data-label */
            table td::before {
                content: attr(data-label); /* Ambil teks dari atribut data-label */
                font-weight: 600;
                text-align: left; /* Ratakan label ke kiri */
                padding-right: 1rem;
                color: #4b5563; /* Warna abu-abu untuk label */
            }
        }
    </style>
</head>
<body class="bg-gray-100">