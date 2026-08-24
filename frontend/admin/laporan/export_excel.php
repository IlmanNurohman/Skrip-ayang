<?php
session_start();
include '../../../backend/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

define('SECRET_KEY', 'e7b434689dac661d0c8fb8d192a36fec76649fc82c3f83e80d17c38d9c3d7320');
define('SECRET_IV', '2dee9400f5a55a4cbce6e5ed27f615e2');

function decryptData($string)
{
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}

// --- Filter (sama seperti index.php) ---
$tanggal_awal  = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$status_filter = $_GET['status'] ?? '';

$where = [];
if (!empty($tanggal_awal)) {
    $ta = mysqli_real_escape_string($conn, $tanggal_awal);
    $where[] = "DATE(p.created_at) >= '$ta'";
}
if (!empty($tanggal_akhir)) {
    $tk = mysqli_real_escape_string($conn, $tanggal_akhir);
    $where[] = "DATE(p.created_at) <= '$tk'";
}
if (!empty($status_filter)) {
    $sf = mysqli_real_escape_string($conn, $status_filter);
    $where[] = "n.status = '$sf'";
}
$where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

$query_detail = "
    SELECT 
        p.nama_lengkap,
        p.jenis_kelamin,
        p.created_at,
        u.username,
        k.nama_kelas,
        n.nilai,
        n.status
    FROM pendaftaran p
    JOIN users u ON p.id_user = u.id
    LEFT JOIN nilai_seleksi n ON n.siswa_id = p.id_user
    LEFT JOIN kelas k ON n.id_kelas = k.id
    $where_sql
    ORDER BY p.created_at DESC
";

$result = mysqli_query($conn, $query_detail);
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$filename = "Laporan_Pendaftaran_" . date('Y-m-d_His') . ".xls";

// Header agar file terunduh sebagai Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// BOM agar karakter UTF-8 (misalnya nama dengan huruf khusus) tampil benar di Excel
echo "\xEF\xBB\xBF";
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size:16px; font-weight:bold;">Laporan Pendaftaran &amp; Seleksi Siswa</th>
        </tr>
        <tr>
            <th colspan="7">
                Periode: <?= !empty($tanggal_awal) ? htmlspecialchars($tanggal_awal) : '(semua)' ?>
                s/d <?= !empty($tanggal_akhir) ? htmlspecialchars($tanggal_akhir) : '(semua)' ?>
                <?php if (!empty($status_filter)): ?> | Status: <?= htmlspecialchars($status_filter) ?><?php endif; ?>
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Jenis Kelamin</th>
            <th>Kelas</th>
            <th>Tanggal Daftar</th>
            <th>Nilai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $gender_decoded = decryptData($row['jenis_kelamin']);
            $gender_text = $gender_decoded ? ucwords(strtolower(trim($gender_decoded))) : '-';

            $nama_decoded = decryptData($row['nama_lengkap']);
            $nama = ($nama_decoded !== false && trim($nama_decoded) !== '')
                ? $nama_decoded
                : ($row['nama_lengkap'] ?: $row['username']);
            $kelas = $row['nama_kelas'] ?? '-';
            $nilai = $row['nilai'] ?? '-';
            $status = $row['status'] ?? '-';
            $tanggal = date('d M Y, H:i', strtotime($row['created_at']));
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($nama) ?></td>
            <td><?= htmlspecialchars($gender_text) ?></td>
            <td><?= htmlspecialchars($kelas) ?></td>
            <td><?= $tanggal ?></td>
            <td><?= $nilai ?></td>
            <td><?= htmlspecialchars($status) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>