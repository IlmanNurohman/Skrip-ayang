<?php
session_start();
include '../../../backend/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    die('Akses ditolak');
}

if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    mysqli_query($conn, "
        INSERT INTO users (username, password, role)
        VALUES ('$username', '$password', '$role')
    ");

    header("Location: index.php");
}
?>