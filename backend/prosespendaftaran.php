<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';

define('SECRET_KEY', 'e7b434689dac661d0c8fb8d192a36fec76649fc82c3f83e80d17c38d9c3d7320');
define('SECRET_IV', '2dee9400f5a55a4cbce6e5ed27f615e2');

function encryptData($string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    return base64_encode($output);
}

function decryptData($string)
{
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
    $nisn            = encryptData(mysqli_real_escape_string($conn, $_POST['nisn']));
    $alamat_asal_sekolah = encryptData(mysqli_real_escape_string($conn, $_POST['alamat_asal_sekolah']));
    $nik_ayah        = encryptData(mysqli_real_escape_string($conn, $_POST['nik_ayah']));
    $nik_ibu         = encryptData(mysqli_real_escape_string($conn, $_POST['nik_ibu']));
    $tanggal_lahir_ortu = encryptData(mysqli_real_escape_string($conn, $_POST['tanggal_lahir_ortu']));
    $alamat_ortu     = encryptData(mysqli_real_escape_string($conn, $_POST['alamat_ortu']));

    // Folder upload (Gunakan path absolut atau pastikan folder ini ada)
    $folderUpload = "uploads/";
    if (!is_dir($folderUpload)) {
        mkdir($folderUpload, 0777, true);
    }

    // Fungsi upload sederhana
  // Fungsi upload PDF
/*function uploadFile($file, $prefix, $folder)
{
    // Cek apakah file dipilih
    if (!empty($file['name'])) {

        // Ambil ekstensi file
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validasi ekstensi harus pdf
        if ($ext !== 'pdf') {
            return [
                'status' => false,
                'message' => 'File harus berformat PDF!'
            ];
        }

        // Validasi MIME type
        $mime = mime_content_type($file['tmp_name']);

        if ($mime !== 'application/pdf') {
            return [
                'status' => false,
                'message' => 'File tidak valid, hanya PDF yang diperbolehkan!'
            ];
        }

        // Generate nama file
        $namaFile = $prefix . "_" . time() . ".pdf";
        $target = $folder . $namaFile;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $target)) {
            return [
                'status' => true,
                'path' => $target
            ];
        }

        return [
            'status' => false,
            'message' => 'Gagal upload file!'
        ];
    }

    return [
        'status' => false,
        'message' => 'File wajib diupload!'
    ];
}*/
function uploadFile($file, $prefix, $folder)
{
    // Kalau tidak upload file
    if (empty($file['name'])) {
        return [
            'status' => true,
            'path' => null // atau ''
        ];
    }

    // Ambil ekstensi file
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Validasi ekstensi harus pdf
    if ($ext !== 'pdf') {
        return [
            'status' => false,
            'message' => 'File harus berformat PDF!'
        ];
    }

    // Validasi MIME type
    $mime = mime_content_type($file['tmp_name']);

    if ($mime !== 'application/pdf') {
        return [
            'status' => false,
            'message' => 'File tidak valid, hanya PDF yang diperbolehkan!'
        ];
    }

    // Generate nama file
    $namaFile = $prefix . "_" . time() . ".pdf";
    $target = $folder . $namaFile;

    // Upload file
    if (move_uploaded_file($file['tmp_name'], $target)) {
        return [
            'status' => true,
            'path' => $target
        ];
    }

    return [
        'status' => false,
        'message' => 'Gagal upload file!'
    ];
}

   $ijazah = uploadFile($_FILES['foto_ijazah'], "ijazah", $folderUpload);
if (!$ijazah['status']) {
    die($ijazah['message']);
}
$path_ijazah = $ijazah['path'];

$raport = uploadFile($_FILES['foto_raport'], "raport", $folderUpload);
if (!$raport['status']) {
    die($raport['message']);
}
$path_raport = $raport['path'];

$kk = uploadFile($_FILES['foto_kk'], "kk", $folderUpload);
if (!$kk['status']) {
    die($kk['message']);
}
$path_kk = $kk['path'];

$ktp = uploadFile($_FILES['ktp_ortu'], "ktp_ortu", $folderUpload);
if (!$ktp['status']) {
    die($ktp['message']);
}
$path_ktp_ortu = $ktp['path'];
    $status = "pending";

    // Sesuaikan kolom dengan struktur DB Anda
    $sql = "INSERT INTO pendaftaran (
        id_user, nama_lengkap, jenis_kelamin, no_hp, email, alamat, asal_sekolah, nik,
        nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu, pendidikan_ayah, pendidikan_ibu,
        penghasilan_ayah, penghasilan_ibu, foto_ijazah, foto_raport, foto_kk, agama, ktp_ortu, alamat_asal_sekolah, nik_ayah, nik_ibu, tanggal_lahir_ortu, alamat_ortu,nisn
    ) VALUES (
         '$id_user','$nama_lengkap', '$jenis_kelamin', '$no_hp', '$email', '$alamat', '$asal_sekolah', '$nik',
        '$nama_ayah', '$nama_ibu', '$pekerjaan_ayah', '$pekerjaan_ibu', '$pendidikan_ayah', '$pendidikan_ibu',
        '$penghasilan_ayah', '$penghasilan_ibu', '$path_ijazah', '$path_raport', '$path_kk', '$agama', '$path_ktp_ortu', '$alamat_asal_sekolah', '$nik_ayah', '$nik_ibu', '$tanggal_lahir_ortu', '$alamat_ortu', '$nisn'
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