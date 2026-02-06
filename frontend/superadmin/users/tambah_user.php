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

<form method="POST">
    <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

    <select name="role" class="form-control mb-2" required>
        <option value="admin">Admin</option>
        <option value="siswa">Siswa</option>
        <option value="super_admin">Super Admin</option>
    </select>

    <button name="simpan" class="btn btn-success">Simpan</button>
</form>