<?php
session_start();
include '../../../backend/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    die('Akses ditolak');
}


$id = $_GET['id'];

// Cegah hapus diri sendiri
if ($id == $_SESSION['user_id']) {
    die('Tidak boleh menghapus akun sendiri');
}

mysqli_query($conn, "DELETE FROM users WHERE id=$id");
header("Location: index.php");