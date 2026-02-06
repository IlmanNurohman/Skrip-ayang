<?php
session_start();
require 'koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die('Akses ditolak');
}

$id = (int) $_POST['id'];
$status = $_POST['aksi'];
$catatan = mysqli_real_escape_string($conn, $_POST['catatan_admin']);

$query = mysqli_query($conn, "
    UPDATE pendaftaran 
    SET status='$status', catatan_admin='$catatan'
    WHERE id=$id
");

if ($query) {
    $_SESSION['swal'] = [
        'type' => 'success',
        'title' => 'Berhasil',
        'text' => "Pendaftaran $status"
    ];
} else {
    $_SESSION['swal'] = [
        'type' => 'error',
        'title' => 'Gagal',
        'text' => 'Validasi gagal'
    ];
}

header("Location: ../frontend/admin/pendaftaran/index.php");
exit;