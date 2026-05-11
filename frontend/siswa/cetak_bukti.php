<?php
session_start();
include '../../backend/koneksi.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit; }

$id_user = $_SESSION['user_id'];

// Ambil data (Sama dengan query di halaman hasil)
$query = mysqli_query($conn, "SELECT p.*, n.nilai, n.status as status_lulus, k.nama_kelas 
    FROM pendaftaran p
    LEFT JOIN nilai_seleksi n ON p.id_user = n.siswa_id
    LEFT JOIN kelas k ON n.id_kelas = k.id
    WHERE p.id_user = '$id_user'");
$data = mysqli_fetch_assoc($query);

// Fungsi Dekripsi (Pastikan kunci sama)
define('SECRET_KEY', 'e7b434689dac661d0c8fb8d192a36fec76649fc82c3f83e80d17c38d9c3d7320');
define('SECRET_IV', '2dee9400f5a55a4cbce6e5ed27f615e2');

function decryptData($string) {
    if ($string === null || $string === '') return '';
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bukti Kelulusan - <?= decryptData($data['nama_lengkap']) ?></title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    /* Area Sertifikat A4 */
    .sertifikat {
        width: 210mm;
        height: 297mm;
        margin: 0 auto;
        padding: 20mm;
        background: #fff;

        /* HILANGKAN BORDER */
        border: none;

        box-sizing: border-box;
        overflow: hidden;
    }


    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        border-bottom: 2px solid #000;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    /* Logo kiri & kanan */
    .header .logo {
        width: 80px;
    }

    .header .logo img {
        width: 100%;
        height: auto;
    }

    /* Judul di tengah */
    .header .header-text {
        text-align: center;
        flex: 1;
    }

    .header .header-text h1 {
        margin: 0;
        font-size: 20px;
        text-transform: uppercase;
    }

    .header .header-text p {
        margin: 4px 0;
        font-size: 12px;
        color: #555;
    }


    .content {
        text-align: center;
    }

    .content h2 {
        color: #27ae60;
        text-transform: uppercase;
        margin-top: 30px;
    }

    .biodata {
        margin: 30px auto;
        width: 80%;
        text-align: left;
        border-collapse: collapse;
    }

    .biodata td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    .label {
        font-weight: bold;
        width: 150px;
    }

    .footer-ket {
        margin-top: 80px;
        text-align: right;
    }

    .stempel {
        margin-top: -50px;
        opacity: 0.6;
    }

    /* Tombol Download (Akan hilang saat jadi PDF) */
    .no-print {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
</head>

<body>

    <button onclick="generatePDF()" class="no-print" id="btn-download">Unduh PDF Sekarang</button>

    <div class="sertifikat" id="printable-area">
        <div class="header">
            <!-- Logo Sekolah (KIRI) -->
            <div class="logo">
                <img src="../../assets/img/ict.png" alt="Logo Sekolah">
            </div>

            <!-- Teks Tengah -->
            <div class="header-text">
                <h1>PANITIA PENERIMAAN PESERTA DIDIK SISWA BARU</h1>
                <h1>SMA ICT PAKENJENG</h1>
                <p>Jl. Raya Pasir Langu No. 123, Kec. Pakenjeng Kab. Garut Telp: (+62) 8231234</p>
            </div>

            <!-- Logo OASIS (KANAN) -->
            <div class="logo">
                <img src="../../assets/img/osis.png" alt="Logo OASIS">
            </div>
        </div>


        <div class="content">
            <h3>SURAT KETERANGAN LULUS SELEKSI</h3>
            <p>Berdasarkan hasil seleksi ujian masuk, panitia menyatakan bahwa:</p>

            <table class="biodata">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td>: <?= decryptData($data['nama_lengkap']) ?></td>
                </tr>
                <tr>
                    <td class="label">Nomor NIK</td>
                    <td>: <?= decryptData($data['nik']) ?></td>
                </tr>
                <tr>
                    <td class="label">Asal Sekolah</td>
                    <td>: <?= decryptData($data['asal_sekolah']) ?></td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td>: <?= decryptData($data['email']) ?></td>
                </tr>
                <tr>
                    <td class="label">Skor Ujian</td>
                    <td>: <strong><?= $data['nilai'] ?></strong></td>
                </tr>
            </table>

            <h2>DINYATAKAN: LULUS</h2>
            <p>Ditempatkan pada kelas:</p>
            <h3 style="background: #f1f1f1; display: inline-block; padding: 10px 20px; border-radius: 5px;">
                <?= $data['nama_kelas'] ?>
            </h3>
        </div>

        <div class="footer-ket">
            <p>Dicetak pada: <?= date('d F Y') ?></p>
            <br><br>
            <p><strong>Panitia PPDB ICT Center</strong></p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    function generatePDF() {
        const element = document.getElementById('printable-area');
        const btn = document.getElementById('btn-download');

        btn.style.display = 'none';

        const opt = {
            margin: [0, 0, 0, 0], // BENAR-BENAR TANPA MARGIN
            filename: 'Bukti_Lulus_<?= decryptData($data['nama_lengkap']) ?>.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2, // JANGAN 3 (ini bikin kepotong)
                useCORS: true,
                scrollY: 0
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            btn.style.display = 'block';
        });

    }


    window.addEventListener('load', function() {
        generatePDF();
    });
    </script>

</body>

</html>