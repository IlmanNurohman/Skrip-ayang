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

<form method="POST">
    <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control mb-2" required>

    <input type="password" name="password" class="form-control mb-2" placeholder="Kosongkan jika tidak diubah">

    <select name="role" class="form-control mb-2">
        <option value="siswa" <?= $user['role']=='siswa'?'selected':'' ?>>Siswa</option>
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        <option value="super_admin" <?= $user['role']=='super_admin'?'selected':'' ?>>Super Admin</option>
    </select>

    <button name="update" class="btn btn-primary">Update</button>
</form>