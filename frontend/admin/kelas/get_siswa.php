<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// 🔥 koneksi (pakai path aman)
include '../../../backend/koneksi.php';

// 🔐 KEY HARUS SAMA DENGAN SAAT ENCRYPT
define('SECRET_KEY', 'e7b434689dac661d0c8fb8d192a36fec76649fc82c3f83e80d17c38d9c3d7320');
define('SECRET_IV', '2dee9400f5a55a4cbce6e5ed27f615e2');

function decryptData($string) {
    if (!$string) return '';
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}

// ambil parameter
$id_kelas = intval($_GET['id_kelas'] ?? 0);

if (!$id_kelas) {
    echo json_encode([]);
    exit;
}

// query
$sql = "SELECT p.nama_lengkap, p.email, n.nilai, n.status
        FROM nilai_seleksi n
        JOIN pendaftaran p ON n.siswa_id = p.id_user
        WHERE n.id_kelas = $id_kelas";

$query = mysqli_query($conn, $sql);

if (!$query) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

// proses data + decrypt
$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = [
        'nama'   => decryptData($row['nama_lengkap']) ?: '-',
        'email'  => decryptData($row['email']) ?: '-',
        'nilai'  => $row['nilai'],
        'status' => $row['status']
    ];
}

// 🔥 pastikan output bersih
if (ob_get_length()) ob_clean();

echo json_encode($data);