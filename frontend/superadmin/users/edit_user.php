<?php
session_start();
include '../../../backend/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    die('Akses ditolak');
}

$id = (int)$_POST['id'];

if (isset($_POST['update'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = mysqli_query($conn, "
            UPDATE users
            SET username='$username',
                password='$password',
                role='$role'
            WHERE id=$id
        ");
    } else {
        $query = mysqli_query($conn, "
            UPDATE users
            SET username='$username',
                role='$role'
            WHERE id=$id
        ");
    }

    if ($query) {
        $_SESSION['swal'] = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data user berhasil diupdate.'
        ];
    } else {
        $_SESSION['swal'] = [
            'type' => 'error',
            'title' => 'Gagal Database!',
            'text' => mysqli_error($conn)
        ];
    }

    header("Location: index.php");
    exit();
}