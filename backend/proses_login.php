<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

         $_SESSION['user_id'] = $user['id'];
         $_SESSION['email']    = $user['email'];


        // Arahkan sesuai role
        switch ($user['role']) {
            case 'super_admin':
                header("Location:../frontend/superadmin/dashboard_superadmin.php");
                break;
            case 'admin':
                header("Location: ../frontend/admin/dashboard_admin.php");
                break;
            case 'siswa':
                header("Location: ../frontend/siswa/dashboard_siswa.php");
                break;
        }
    } else {
        echo "<script>alert('Username atau password salah'); window.location='../login.php';</script>";
    }
}
?>