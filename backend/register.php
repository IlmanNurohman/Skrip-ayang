<?php
include 'koneksi.php';

if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
     $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Validasi role
    $allowed_roles = ['super_admin', 'admin', 'siswa'];
    if (!in_array($role, $allowed_roles)) {
        echo "<script>swal('Error','Role tidak valid!','error');</script>";
        exit;
    }

    // Cek username
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>swal('Gagal','Username sudah digunakan!','error');</script>";
        exit;
    }

    // ================= UPLOAD FOTO =================
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
        echo "<script>swal('Error','Foto wajib diupload!','error');</script>";
        exit;
    }

    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_size = $_FILES['foto']['size'];

    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        echo "<script>swal('Error','Format foto harus JPG / PNG!','error');</script>";
        exit;
    }

    if ($foto_size > 2000000) {
        echo "<script>swal('Error','Ukuran foto maksimal 2MB!','error');</script>";
        exit;
    }

   $nama_foto_baru = uniqid() . '.' . $ext;

// path ke: C:\laragon\www\ppdb\assets\img\user\
$folder = realpath(__DIR__ . '/../assets/img/user') . DIRECTORY_SEPARATOR;

// buat folder jika belum ada
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

if (!move_uploaded_file($foto_tmp, $folder . $nama_foto_baru)) {
    echo "<script>swal('Error','Upload foto gagal!','error');</script>";
    exit;
}


    // ================= INSERT DATABASE =================
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $insert = mysqli_query($conn, "
        INSERT INTO users (username, email, password, role, foto)
        VALUES ('$username','$email', '$password_hash', '$role', '$nama_foto_baru')
    ");

    if ($insert) {
        echo "
        <script>
            swal({
                title: 'Berhasil!',
                text: 'Registrasi berhasil.',
                icon: 'success'
            }).then(() => {
                window.location = 'login.php';
            });
        </script>";
    } else {
        echo "<script>swal('Error','Registrasi gagal!','error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Register Akun</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="admin">Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Foto Profil</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                            </div>

                            <button type="submit" name="register" class="btn btn-primary w-100">
                                Daftar
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>
</body>

</html>