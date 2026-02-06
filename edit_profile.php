<?php
session_start();
include 'backend/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username_session = $_SESSION['username'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username_session'");
$user = mysqli_fetch_assoc($query);

// Proses Update
if (isset($_POST['update'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_baru = $_POST['password'];
    $foto_lama = $user['foto'];
    
    // 1. Logika Password
    if (!empty($password_baru)) {
        // Jika password diisi, enkripsi password baru
        $password_fix = password_hash($password_baru, PASSWORD_DEFAULT);
    } else {
        // Jika kosong, gunakan password lama dari database
        $password_fix = $user['password'];
    }

    // 2. Logika Foto
    if ($_FILES['foto']['error'] === 0) {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp  = $_FILES['foto']['tmp_name'];
        $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        $nama_foto_baru = uniqid() . '.' . $ext;
        $folder = "assets/img/user/";

        // Upload file baru
        if (move_uploaded_file($foto_tmp, $folder . $nama_foto_baru)) {
            // Hapus foto lama jika bukan default
            if ($foto_lama != 'default-profile.png' && file_exists($folder . $foto_lama)) {
                unlink($folder . $foto_lama);
            }
            $foto_fix = $nama_foto_baru;
        } else {
            $foto_fix = $foto_lama;
        }
    } else {
        $foto_fix = $foto_lama;
    }

    // 3. Update ke Database
    $update = mysqli_query($conn, "UPDATE users SET 
        email = '$email', 
        password = '$password_fix', 
        foto = '$foto_fix' 
        WHERE username = '$username_session'");

    if ($update) {
        echo "<script>
                alert('Profil berhasil diperbarui!');
                window.location='profile.php';
              </script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - <?= $user['username']; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">Edit Profil</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="text-center mb-4">
                                <img src="assets/img/user/<?= $user['foto']; ?>" class="rounded-circle shadow-sm"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #eee;">
                                <p class="small text-muted mt-2">Foto saat ini</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control bg-light" value="<?= $user['username']; ?>"
                                    readonly>
                                <small class="text-muted italic">*Username tidak dapat diubah.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Baru</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Kosongkan jika tidak ingin merubah password">
                                <small class="text-danger">biarkan kosong jika tidak ingin ganti password.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Ganti Foto Profil</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="update" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                                <a href="profile.php" class="btn btn-secondary">Batal</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/core/bootstrap.min.js"></script>
</body>

</html>