<?php
$host = "localhost";
$user = "root"; // sesuaikan dengan user Anda
$pass = ""; // sesuaikan dengan password Anda
$db   = "db_absenguru"; // sesuaikan dengan nama database Anda

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>