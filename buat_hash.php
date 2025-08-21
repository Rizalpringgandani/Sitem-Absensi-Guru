<?php

// Tulis password yang ingin Anda enkripsi di sini
$passwordToHash = '123456';

// Proses enkripsi menggunakan standar PHP
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

// Tampilkan hasilnya agar bisa kita salin
echo "<h1>Pembuat Hash Password</h1>";
echo "<p>Gunakan hash di bawah ini untuk di-paste ke database Anda.</p>";
echo "<b>Password Asli:</b> " . htmlspecialchars($passwordToHash) . "<br><br>";
echo "<b>Hasil Hash:</b><br>";
echo '<textarea rows="4" cols="70" readonly onclick="this.select()">' . htmlspecialchars($hashedPassword) . '</textarea>';
echo "<p><i>Klik di dalam kotak untuk memilih semua, lalu salin (Ctrl+C).</i></p>";

?>