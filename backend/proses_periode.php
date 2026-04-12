<?php
session_start();
include 'koneksi.php';

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {
    // Gunakan real_escape_string agar karakter spesial tidak merusak query
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $tanggal_mulai = mysqli_real_escape_string($conn, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($conn, $_POST['tanggal_selesai']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    $sql = "INSERT INTO periode (tahun, tanggal_mulai, tanggal_selesai, catatan) 
            VALUES ('$tahun', '$tanggal_mulai', '$tanggal_selesai', '$catatan')";
    
    execute_query($conn, $sql, "Data berhasil ditambah");

} elseif ($aksi == 'update') {
    // Pastikan ID ada dan valid
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $tanggal_mulai = mysqli_real_escape_string($conn, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($conn, $_POST['tanggal_selesai']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    $sql = "UPDATE periode SET 
            tahun='$tahun', 
            tanggal_mulai='$tanggal_mulai', 
            tanggal_selesai='$tanggal_selesai', 
            catatan='$catatan' 
            WHERE id='$id'";

    execute_query($conn, $sql, "Data berhasil diupdate");

} elseif ($aksi == 'hapus') {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "DELETE FROM periode WHERE id='$id'";
    execute_query($conn, $sql, "Data berhasil dihapus");
}

function execute_query($conn, $sql, $msg) {
    if (mysqli_query($conn, $sql)) {
        $_SESSION['swal'] = ['type' => 'success', 'title' => 'Berhasil!', 'text' => $msg];
    } else {
        // Jika gagal, simpan pesan error database ke session untuk didebug
        $_SESSION['swal'] = ['type' => 'error', 'title' => 'Gagal!', 'text' => mysqli_error($conn)];
    }
    // Pastikan nama file redirect sudah benar (tambah_periode.php atau index.php)
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit;
}
?>