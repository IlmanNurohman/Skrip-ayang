<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kelas = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
    $kuota      = (int) $_POST['kuota'];

    $query = "INSERT INTO kelas (nama_kelas, kuota) 
              VALUES ('$nama_kelas', $kuota)";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data kelas berhasil disimpan');
                window.location='tambah_kelas.php';
              </script>";
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}