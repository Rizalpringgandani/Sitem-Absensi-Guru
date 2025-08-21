<?php
session_start();
require __DIR__ . '/../../config/database.php';

// Ambil data dari form dan LANGSUNG HAPUS SPASI ekstra dengan trim()
$id_guru = trim($_POST['id_guru'] ?? '');
$password_input = trim($_POST['password'] ?? ''); // <--- PERBAIKAN DI SINI

if (empty($id_guru) || empty($password_input)) {
    header("Location: ../../index.php?error=1");
    exit;
}

$sql = "SELECT id_guru, nama_guru, password FROM guru WHERE id_guru = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $hashed_password_from_db = $user['password'];

    if (password_verify($password_input, $hashed_password_from_db)) {
        // --- LOGIN BERHASIL ---
        session_regenerate_id(true);
        
        $_SESSION['is_logged_in'] = true;
        $_SESSION['id_guru'] = $user['id_guru'];
        $_SESSION['nama_guru'] = $user['nama_guru'];

        header("Location: ../../dashboard.php");
        exit;
    }
}

header("Location: ../../index.php?error=1");
exit;
?>