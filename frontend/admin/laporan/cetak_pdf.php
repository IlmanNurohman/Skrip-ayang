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

$data_laporan = [];
while ($row = mysqli_fetch_assoc($result)) {
    $gender_decoded = decryptData($row['jenis_kelamin']);
    $row['jenis_kelamin_text'] = $gender_decoded ? ucwords(strtolower(trim($gender_decoded))) : '-';

    $nama_decoded = decryptData($row['nama_lengkap']);
    $row['nama_lengkap_text'] = ($nama_decoded !== false && trim($nama_decoded) !== '')
        ? $nama_decoded
        : ($row['nama_lengkap'] ?: $row['username']);

    $data_laporan[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Pendaftaran</title>
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
        margin: 30px;
    }

    .kop {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    .kop h2 {
        margin: 0;
    }

    .kop p {
        margin: 2px 0;
        font-size: 13px;
        color: #555;
    }

    .info-filter {
        margin-bottom: 16px;
        font-size: 13px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #444;
    }

    th,
    td {
        padding: 6px 8px;
        font-size: 12px;
        text-align: left;
    }

    th {
        background: #f0f0f0;
    }

    .text-end {
        text-align: right;
    }

    .ringkasan {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
    }

    .ringkasan div {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px 16px;
        flex: 1;
        text-align: center;
    }

    .ringkasan h3 {
        margin: 0;
        font-size: 20px;
    }

    .ringkasan p {
        margin: 4px 0 0 0;
        font-size: 12px;
        color: #666;
    }

    .no-print {
        margin-bottom: 20px;
    }

    .ttd {
        margin-top: 60px;
        text-align: right;
        font-size: 13px;
    }

    @media print {
        .no-print {
            display: none;
        }

        body {
            margin: 10px;
        }
    }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>
    </div>

    <div class="kop">
        <h2>LAPORAN PENDAFTARAN &amp; SELEKSI SISWA</h2>
        <p>ICT Boarding School Pakenjeng</p>
        <p>Dicetak pada: <?= date('d M Y, H:i') ?></p>
    </div>

    <div class="info-filter">
        <strong>Periode:</strong>
        <?= !empty($tanggal_awal) ? htmlspecialchars($tanggal_awal) : '(semua)' ?>
        s/d
        <?= !empty($tanggal_akhir) ? htmlspecialchars($tanggal_akhir) : '(semua)' ?>
        <?php if (!empty($status_filter)): ?>
        | <strong>Status:</strong> <?= htmlspecialchars($status_filter) ?>
        <?php endif; ?>
    </div>

    <div class="ringkasan">
        <div>
            <h3><?= count($data_laporan) ?></h3>
            <p>Total Data</p>
        </div>
        <div>
            <h3><?= count(array_filter($data_laporan, fn($r) => ($r['status'] ?? '') == 'Lulus')) ?></h3>
            <p>Lulus</p>
        </div>
        <div>
            <h3><?= count(array_filter($data_laporan, fn($r) => !empty($r['status']) && $r['status'] != 'Lulus')) ?>
            </h3>
            <p>Tidak Lulus</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>Tanggal Daftar</th>
                <th class="text-end">Nilai</th>
                <th class="text-end">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data_laporan) > 0): ?>
            <?php $no = 1;
                foreach ($data_laporan as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap_text']) ?></td>
                <td><?= htmlspecialchars($row['jenis_kelamin_text']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                <td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                <td class="text-end"><?= $row['nilai'] ?? '-' ?></td>
                <td class="text-end"><?= htmlspecialchars($row['status'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">Tidak ada data untuk filter ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Admin ICT Boarding School</strong></p>
    </div>

    <script>
    // Otomatis membuka dialog cetak saat halaman terbuka.
    // Pada dialog print, pilih "Save as PDF" untuk menyimpan sebagai file PDF.
    window.onload = function() {
        window.print();
    };
    </script>
</body>

</html>