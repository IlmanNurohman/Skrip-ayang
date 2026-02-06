<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'siswa'; // siswa hanya bisa daftar dirinya sendiri

    // Cek apakah username sudah ada
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location='../register.php';</script>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
        if ($insert) {
            echo "<script>alert('Registrasi berhasil! Silakan login'); window.location='../login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!'); window.location='../register.php';</script>";
        }
    }
}
?>