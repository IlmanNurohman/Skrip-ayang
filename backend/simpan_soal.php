<?php
session_start();
include 'koneksi.php';

$aksi = $_GET['aksi'] ?? '';

if ($aksi == 'tambah') {
    $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($conn, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($conn, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($conn, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($conn, $_POST['opsi_d']);
    $jawaban = $_POST['jawaban_benar'];

    $sql = "INSERT INTO soal (pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar) 
            VALUES ('$pertanyaan','$a','$b','$c','$d','$jawaban')";
    
    execute_query($conn, $sql, "Soal berhasil disimpan");

} elseif ($aksi == 'update') {
    $id = $_POST['id'];
    $pertanyaan = mysqli_real_escape_string($conn, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($conn, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($conn, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($conn, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($conn, $_POST['opsi_d']);
    $jawaban = $_POST['jawaban_benar'];

    $sql = "UPDATE soal SET 
            pertanyaan='$pertanyaan', opsi_a='$a', opsi_b='$b', opsi_c='$c', opsi_d='$d', jawaban_benar='$jawaban' 
            WHERE id='$id'";

    execute_query($conn, $sql, "Soal berhasil diperbarui");

} elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    $sql = "DELETE FROM soal WHERE id='$id'";
    execute_query($conn, $sql, "Soal berhasil dihapus");
}

function execute_query($conn, $sql, $msg) {
    if ($conn->query($sql) === TRUE) {
        $_SESSION['swal'] = ['type' => 'success', 'title' => 'Berhasil!', 'text' => $msg];
    } else {
        $_SESSION['swal'] = ['type' => 'error', 'title' => 'Gagal!', 'text' => $conn->error];
    }
   header("Location: ../frontend/admin/soal/index.php");
    exit;
}
?>