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
    $required = [
    'nama_lengkap' => 'Nama Lengkap',
    'jenis_kelamin' => 'Jenis Kelamin',
    'no_hp' => 'No HP',
    'email' => 'Email',
    'alamat' => 'Alamat',
    'asal_sekolah' => 'Asal Sekolah',
    'nik' => 'NIK',
    'nisn' => 'NISN',
    'agama' => 'Agama',
    'nama_ayah' => 'Nama Ayah',
    'nama_ibu' => 'Nama Ibu',
    'pekerjaan_ayah' => 'Pekerjaan Ayah',
    'pekerjaan_ibu' => 'Pekerjaan Ibu',
    'pendidikan_ayah' => 'Pendidikan Ayah',
    'pendidikan_ibu' => 'Pendidikan Ibu',
    'penghasilan_ayah' => 'Penghasilan Ayah',
    'penghasilan_ibu' => 'Penghasilan Ibu',
    'alamat_asal_sekolah' => 'Alamat Asal Sekolah',
    'nik_ayah' => 'NIK Ayah',
    'nik_ibu' => 'NIK Ibu',
    'tanggal_lahir_ortu' => 'Tanggal Lahir Orang Tua',
    'alamat_ortu' => 'Alamat Orang Tua'
];

foreach ($required as $field => $label) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) == '') {
        $_SESSION['swal'] = [
            'type' => 'error',
            'title' => 'Data Belum Lengkap',
            'text' => "$label wajib diisi."
        ];

        header("Location: ../frontend/siswa/form_pendaftaran.php");
        exit();
    }
}

    // --- Jenis Pendaftaran (Reguler / Pindahan) ---
    $jenis_pendaftaran = in_array($_POST['jenis_pendaftaran'] ?? '', ['reguler', 'pindahan'])
        ? $_POST['jenis_pendaftaran']
        : 'reguler';

    // Validasi tambahan khusus pindahan
    if ($jenis_pendaftaran === 'pindahan') {
        if (empty(trim($_POST['asal_sekolah_pindahan'] ?? ''))) {
            $_SESSION['swal'] = [
                'type' => 'error',
                'title' => 'Data Belum Lengkap',
                'text' => 'Asal Sekolah Pindahan wajib diisi.'
            ];
            header("Location: ../frontend/siswa/form_pendaftaran.php");
            exit();
        }
        if (empty($_POST['kelas_pindahan'] ?? '')) {
            $_SESSION['swal'] = [
                'type' => 'error',
                'title' => 'Data Belum Lengkap',
                'text' => 'Kelas Tujuan wajib dipilih.'
            ];
            header("Location: ../frontend/siswa/form_pendaftaran.php");
            exit();
        }
        if (!isset($_FILES['surat_pindahan']) || $_FILES['surat_pindahan']['error'] == UPLOAD_ERR_NO_FILE) {
            $_SESSION['swal'] = [
                'type' => 'error',
                'title' => 'Upload Gagal',
                'text' => 'Surat Pindahan wajib diupload.'
            ];
            header("Location: ../frontend/siswa/form_pendaftaran.php");
            exit();
        }
    }

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

    // --- Data khusus pindahan ---
    $asal_sekolah_pindahan = $jenis_pendaftaran === 'pindahan'
        ? encryptData(mysqli_real_escape_string($conn, $_POST['asal_sekolah_pindahan']))
        : null;

    $kelas_pindahan = $jenis_pendaftaran === 'pindahan'
        ? (int) $_POST['kelas_pindahan']
        : null;

    // Folder upload (Gunakan path absolut atau pastikan folder ini ada)
    $folderUpload = "uploads/";
    if (!is_dir($folderUpload)) {
        mkdir($folderUpload, 0777, true);
    }
    $requiredFiles = [
    'foto_ijazah' => 'Ijazah',
    'foto_raport' => 'Raport',
    'foto_kk' => 'Kartu Keluarga',
    'ktp_ortu' => 'KTP Orang Tua'
];

foreach ($requiredFiles as $field => $label) {
    if (
        !isset($_FILES[$field]) ||
        $_FILES[$field]['error'] == UPLOAD_ERR_NO_FILE
    ) {
        $_SESSION['swal'] = [
            'type' => 'error',
            'title' => 'Upload Gagal',
            'text' => "$label wajib diupload."
        ];

        header("Location: ../frontend/siswa/form_pendaftaran.php");
        exit();
    }
}

    // Fungsi upload sederhana

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

    // --- Upload surat pindahan (kalau jenis pendaftaran = pindahan) ---
    $path_surat_pindahan = null;
    if ($jenis_pendaftaran === 'pindahan') {
        $surat = uploadFile($_FILES['surat_pindahan'], "surat_pindahan", $folderUpload);
        if (!$surat['status']) {
            die($surat['message']);
        }
        $path_surat_pindahan = $surat['path'];
    }

    $status = "pending";

    // Nilai untuk kolom khusus pindahan (NULL jika reguler)
    $kelas_pindahan_sql = $kelas_pindahan !== null ? "'$kelas_pindahan'" : "NULL";
    $asal_pindahan_sql  = $asal_sekolah_pindahan !== null ? "'$asal_sekolah_pindahan'" : "NULL";
    $surat_pindahan_sql = $path_surat_pindahan !== null ? "'$path_surat_pindahan'" : "NULL";

    // Sesuaikan kolom dengan struktur DB Anda
    $sql = "INSERT INTO pendaftaran (
        id_user, jenis_pendaftaran, nama_lengkap, jenis_kelamin, no_hp, email, alamat, asal_sekolah, nik,
        nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu, pendidikan_ayah, pendidikan_ibu,
        penghasilan_ayah, penghasilan_ibu, foto_ijazah, foto_raport, foto_kk, agama, ktp_ortu,
        alamat_asal_sekolah, nik_ayah, nik_ibu, tanggal_lahir_ortu, alamat_ortu, nisn,
        asal_sekolah_pindahan, kelas_pindahan, surat_pindahan, status
    ) VALUES (
         '$id_user', '$jenis_pendaftaran', '$nama_lengkap', '$jenis_kelamin', '$no_hp', '$email', '$alamat', '$asal_sekolah', '$nik',
        '$nama_ayah', '$nama_ibu', '$pekerjaan_ayah', '$pekerjaan_ibu', '$pendidikan_ayah', '$pendidikan_ibu',
        '$penghasilan_ayah', '$penghasilan_ibu', '$path_ijazah', '$path_raport', '$path_kk', '$agama', '$path_ktp_ortu',
        '$alamat_asal_sekolah', '$nik_ayah', '$nik_ibu', '$tanggal_lahir_ortu', '$alamat_ortu', '$nisn',
        $asal_pindahan_sql, $kelas_pindahan_sql, $surat_pindahan_sql, '$status'
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