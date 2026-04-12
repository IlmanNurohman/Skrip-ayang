<?php
session_start();
include '../../../backend/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    die('Akses ditolak');
}


$id = $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $role     = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "
            UPDATE users SET username='$username', password='$password', role='$role'
            WHERE id=$id
        ");
    } else {
        mysqli_query($conn, "
            UPDATE users SET username='$username', role='$role'
            WHERE id=$id
        ");
    }

    header("Location: index.php");
}
?>