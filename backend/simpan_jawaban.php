<?php
session_start();
include 'koneksi.php';

// Pastikan menggunakan user_id dari session login Anda
if (!isset($_SESSION['user_id'])) {
    die('Sesi tidak ditemukan. Silakan login ulang.');
}

$siswa_id = (int) $_SESSION['user_id'];

if (!isset($_POST['jawaban'])) {
    die('Tidak ada jawaban dikirim.');
}

$jawaban_user = $_POST['jawaban']; // Berisi [soal_id => 'A/B/C/D']

// 1. Ambil total semua soal yang ada di database untuk pembagi nilai yang adil
$query_total = mysqli_query($conn, "SELECT id, jawaban_benar FROM soal");
$total_soal_db = mysqli_num_rows($query_total);

$benar = 0;
$salah = 0;

// Loop semua soal dari DB untuk membandingkan dengan jawaban user
while ($s = mysqli_fetch_assoc($query_total)) {
    $soal_id = $s['id'];
    
    // Gunakan trim untuk menghapus spasi yang tidak sengaja tersimpan
    $kunci = trim($s['jawaban_benar']); 
    
    // Ambil jawaban user dan bersihkan juga
    $pilihan_siswa = isset($jawaban_user[$soal_id]) ? trim($jawaban_user[$soal_id]) : '';
    
    // Tambahkan pengecekan case-insensitive dengan strtoupper jika perlu
    $is_benar = (strtoupper($pilihan_siswa) == strtoupper($kunci)) ? 1 : 0;
    
    if ($is_benar === 1) {
        $benar++;
    } else {
        $salah++;
    }

    // Simpan history jawaban per soal (Opsional: Update jika sudah ada, Insert jika belum)
    mysqli_query($conn, "INSERT INTO jawaban_siswa (siswa_id, soal_id, jawaban, is_benar) 
                         VALUES ($siswa_id, $soal_id, '$pilihan_siswa', $is_benar)
                         ON DUPLICATE KEY UPDATE jawaban='$pilihan_siswa', is_benar=$is_benar");
}

// 2. Perhitungan Nilai (Skala 100)
// Jika benar semua (misal 10 dari 10), maka (10/10)*100 = 100
$nilai = ($benar / $total_soal_db) * 100;
$nilai = round($nilai); // Membulatkan angka

// 3. Tentukan Status Lulus (Sesuai permintaan: >= 60 Lulus)
$status = ($nilai >= 60) ? 'Lulus' : 'Tidak Lulus';

// 4. Simpan ke database nilai_seleksi
$check_nilai = mysqli_query($conn, "SELECT * FROM nilai_seleksi WHERE siswa_id = $siswa_id");

if (mysqli_num_rows($check_nilai) > 0) {
    // Update jika sudah ada
    mysqli_query($conn, "UPDATE nilai_seleksi SET nilai = $nilai, status = '$status' WHERE siswa_id = $siswa_id");
} else {
    // Insert baru
    mysqli_query($conn, "INSERT INTO nilai_seleksi (siswa_id, nilai, status) VALUES ($siswa_id, $nilai, '$status')");
}

$id_kelas_terpilih = NULL;

if ($status == 'Lulus') {
    // 1. Ambil gender siswa dari tabel pendaftaran
    $q_gender = mysqli_query($conn, "SELECT jenis_kelamin FROM pendaftaran WHERE id_user = $siswa_id");
    $d_gender = mysqli_fetch_assoc($q_gender);
    $gender = $d_gender['jenis_kelamin'];

    // 2. Ambil semua kelas
    $q_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY id ASC");
    
    while ($k = mysqli_fetch_assoc($q_kelas)) {
        $id_k = $k['id'];
        $kuota_max = $k['kuota'];
        $min_perempuan = ceil($kuota_max * 0.3); // Minimal 30% cewe

        // Hitung isi kelas saat ini
        $q_count = mysqli_query($conn, "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN p.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) as total_cewe
            FROM nilai_seleksi n
            JOIN pendaftaran p ON n.siswa_id = p.id_user
            WHERE n.id_kelas = $id_k");
        $d_count = mysqli_fetch_assoc($q_count);
        
        $current_total = $d_count['total'];
        $current_cewe = $d_count['total_cewe'];

        // Cek apakah kuota masih ada
        if ($current_total < $kuota_max) {
            if ($gender == 'Perempuan') {
                // Jika cewe, langsung masuk selama kuota kelas tersedia
                $id_kelas_terpilih = $id_k;
                break;
            } else {
                // Jika cowo, cek apakah sisa kuota cukup untuk memenuhi syarat min cewe
                // Sisa slot cowo = Total Kuota - Minimal Perempuan
                $slot_cowo_max = $kuota_max - $min_perempuan;
                $current_cowo = $current_total - $current_cewe;

                if ($current_cowo < $slot_cowo_max) {
                    $id_kelas_terpilih = $id_k;
                    break;
                }
                // Jika slot cowo sudah penuh (karena harus sisa buat cewe), lanjut ke kelas berikutnya
            }
        }
    }
}

// 4. Simpan ke database nilai_seleksi (Tambahkan kolom id_kelas, benar, salah)
// Pastikan tabel nilai_seleksi sudah punya kolom: id_kelas, jml_benar, jml_salah
$check_nilai = mysqli_query($conn, "SELECT * FROM nilai_seleksi WHERE siswa_id = $siswa_id");

if (mysqli_num_rows($check_nilai) > 0) {
    mysqli_query($conn, "UPDATE nilai_seleksi SET 
        nilai = $nilai, 
        status = '$status', 
        id_kelas = ".($id_kelas_terpilih ?? 'NULL').",
        jml_benar = $benar,
        jml_salah = $salah 
        WHERE siswa_id = $siswa_id");
} else {
    mysqli_query($conn, "INSERT INTO nilai_seleksi (siswa_id, nilai, status, id_kelas, jml_benar, jml_salah) 
        VALUES ($siswa_id, $nilai, '$status', ".($id_kelas_terpilih ?? 'NULL').", $benar, $salah)");
}

// Arahkan ke halaman hasil
header("Location: ../frontend/siswa/ujian.php");
exit;