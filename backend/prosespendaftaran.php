<?php
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';

define('SECRET_KEY', 'ganti_dengan_kunci_rahasia_yang_sangat_panjang_123!@#');
define('SECRET_IV', 'iv_rahasia_456!@#');

function encryptData($string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    return base64_encode($output);
}

function decryptData($string) {
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $id_user = $_SESSION['user_id'];
   $nama_lengkap     = encryptData(mysqli_real_escape_string($conn, $_POST['nama_lengkap']));
$jenis_kelamin    = encryptData($_POST['jenis_kelamin']);
$no_hp            = encryptData(mysqli_real_escape_string($conn, $_POST['no_hp']));
$email            = encryptData(mysqli_real_escape_string($conn, $_POST['email']));
$alamat           = encryptData(mysqli_real_escape_string($conn, $_POST['alamat']));
$asal_sekolah     = encryptData(mysqli_real_escape_string($conn, $_POST['asal_sekolah']));
$nik              = encryptData(mysqli_real_escape_string($conn, $_POST['nik']));
$nama_ayah        = encryptData(mysqli_real_escape_string($conn, $_POST['nama_ayah']));
$nama_ibu         = encryptData(mysqli_real_escape_string($conn, $_POST['nama_ibu']));
$pekerjaan_ayah   = encryptData(mysqli_real_escape_string($conn, $_POST['pekerjaan_ayah']));
$pekerjaan_ibu    = encryptData(mysqli_real_escape_string($conn, $_POST['pekerjaan_ibu']));
$penghasilan_ayah = encryptData(mysqli_real_escape_string($conn, $_POST['penghasilan_ayah']));
$penghasilan_ibu  = encryptData(mysqli_real_escape_string($conn, $_POST['penghasilan_ibu']));
$pendidikan_ayah  = encryptData(mysqli_real_escape_string($conn, $_POST['pendidikan_ayah']));
$pendidikan_ibu   = encryptData(mysqli_real_escape_string($conn, $_POST['pendidikan_ibu']));
$agama            = encryptData(mysqli_real_escape_string($conn, $_POST['agama']));


    // Folder upload (Gunakan path absolut atau pastikan folder ini ada)
    $folderUpload = "uploads/";
    if (!is_dir($folderUpload)) {
        mkdir($folderUpload, 0777, true);
    }

    // Fungsi upload sederhana
    function uploadFile($file, $prefix, $folder) {
        if (!empty($file['name'])) {
            $namaFile = $prefix . "_" . time() . "_" . basename($file['name']);
            $target = $folder . $namaFile;
            if (move_uploaded_file($file['tmp_name'], $target)) {
                return $target;
            }
        }
        return "";
    }

    $path_ijazah = uploadFile($_FILES['foto_ijazah'], "ijazah", $folderUpload);
    $path_raport = uploadFile($_FILES['foto_raport'], "raport", $folderUpload);
    $path_kk     = uploadFile($_FILES['foto_kk'], "kk", $folderUpload);
    $status = "pending";

    // Sesuaikan kolom dengan struktur DB Anda
    $sql = "INSERT INTO pendaftaran (
        id_user, nama_lengkap, jenis_kelamin, no_hp, email, alamat, asal_sekolah, nik,
        nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu, pendidikan_ayah, pendidikan_ibu,
        penghasilan_ayah, penghasilan_ibu, foto_ijazah, foto_raport, foto_kk, agama
    ) VALUES (
         '$id_user','$nama_lengkap', '$jenis_kelamin', '$no_hp', '$email', '$alamat', '$asal_sekolah', '$nik',
        '$nama_ayah', '$nama_ibu', '$pekerjaan_ayah', '$pekerjaan_ibu', '$pendidikan_ayah', '$pendidikan_ibu',
        '$penghasilan_ayah', '$penghasilan_ibu', '$path_ijazah', '$path_raport', '$path_kk', '$agama'
    )";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['swal'] = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data pendaftaran berhasil disimpan.'
        ];
        header("Location: ../frontend/siswa/form_pendaftaran.php");
    } else {
        $_SESSION['swal'] = [
            'type' => 'error',
            'title' => 'Gagal Database!',
            'text' => 'Pesan Error: ' . mysqli_error($conn)
        ];
        header("Location: ../frontend/siswa/form_pendaftaran.php");
    }
    exit();
}