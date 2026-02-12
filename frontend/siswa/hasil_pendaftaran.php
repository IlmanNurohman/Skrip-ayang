<?php
session_start();
include '../../backend/koneksi.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit; }

$id_user = $_SESSION['user_id'];

// Ambil data lengkap (Pendaftaran + Nilai + Kelas)
$query = mysqli_query($conn, "SELECT p.*, n.nilai, n.status as status_lulus, n.jml_benar, n.jml_salah, k.nama_kelas 
    FROM pendaftaran p
    LEFT JOIN nilai_seleksi n ON p.id_user = n.siswa_id
    LEFT JOIN kelas k ON n.id_kelas = k.id
    WHERE p.id_user = '$id_user'");
$data = mysqli_fetch_assoc($query);

if (!$data || $data['status_lulus'] != 'Lulus') {
    echo "<script>alert('Anda belum seleksi atau dinyatakan tidak lulus'); window.location='dashboard_siswa.php';</script>";
    exit;
}

$query_user = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($query_user);

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Hasil Pendaftaran</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../../assets/img/ict.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
    WebFont.load({
        google: {
            families: ["Public Sans:300,400,500,600,700"]
        },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["../../assets/css/fonts.min.css"],
        },
        active: function() {
            sessionStorage.fonts = true;
        },
    });
    </script>



    <!-- CSS Files -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">


</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.html" class="logo">
                        <img src="../../assets/img/logo_ict.png" alt="navbar brand"
                            style="height: 30px; margin-right: 10px;" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item">
                            <a href="dashboard_siswa.php" class="collapsed" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>

                            </a>

                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Menu</h4>
                        </li>
                        <li class="nav-item">
                            <a href="form_pendaftaran.php">
                                <i class="fas fa-layer-group"></i>
                                <p>Pendaftaran</p>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="ujian.php">
                                <i class=" fas fa-th-list"></i>
                                <p>Seleksi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="hasil_pendaftaran.php">
                                <i class="fas fa-pen-square"></i>
                                <p>Hasil Pendaftaran</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="../../assets/img/logo_ict.png" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </nav>

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="../../assets/img/user/<?= $user['foto']; ?>" alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">

                                        <span class="fw-bold"><?= $_SESSION['username']; ?></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="                                        
                                                        ../../assets/img/user/<?= $user['foto']; ?>" alt="..."
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?= $_SESSION['username']; ?></h4>
                                                    <p class="text-muted"><?= $_SESSION['email']; ?></p>

                                                    <a href="../../profile.php"
                                                        class="btn btn-xs btn-secondary btn-sm">View
                                                        Profile</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="../../logout.php">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Hasil Pendaftaran</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="#">
                                    <i class="fas fa-pen-square"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="hasil_pendaftaran.php">Hasil Pendaftaran</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Detail Hasil Pembayaran</a>
                            </li>
                            <ul>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Detail Hasil Pendaftaran</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="card-header bg-success py-3 rounded">
                                                <h2 class="text-white text-center mb-0">PENGUMUMAN KELULUSAN</h2>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center mb-4">
                                                    <h3 class="fw-bold">Selamat!
                                                        <?= decryptData($data['nama_lengkap']) ?></h3>
                                                    <p class="badge badge-success px-4 py-2" style="font-size:1.2rem">
                                                        Dinyatakan: LULUS SELEKSI
                                                    </p>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 border-right">
                                                        <h4 class="fw-bold border-bottom pb-2">Biodata Siswa</h4>
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td>NIK</td>
                                                                <td class="fw-bold">: <?= decryptData($data['nik']) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td class="fw-bold">:
                                                                    <?=  decryptData($data['email'])  ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Gender</td>
                                                                <td class="fw-bold">:
                                                                    <?= decryptData($data['jenis_kelamin']) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Asal Sekolah</td>
                                                                <td class="fw-bold">:
                                                                    <?= decryptData($data['asal_sekolah'])  ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <h4 class="fw-bold border-bottom pb-2">Hasil Seleksi</h4>
                                                        <div class="row text-center mb-3">
                                                            <div class="col-4">
                                                                <div class="card bg-info text-white p-2">
                                                                    <small>Benar</small>
                                                                    <h3><?= $data['jml_benar'] ?></h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="card bg-danger text-white p-2">
                                                                    <small>Salah</small>
                                                                    <h3><?= $data['jml_salah'] ?></h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="card bg-primary text-white p-2">
                                                                    <small>Skor</small>
                                                                    <h3><?= $data['nilai'] ?></h3>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="alert alert-warning text-center">
                                                            <h5 class="mb-1">Penempatan Kelas:</h5>
                                                            <h4 class="display-6 fw-bold text-dark">
                                                                <?= $data['nama_kelas'] ?? 'Belum Ditentukan' ?>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action text-end mt-3">
                                    <a href="cetak_bukti.php" target="_blank" class="btn btn-secondary px-4">
                                        <i class="fas fa-print"></i> Cetak Bukti Resmi
                                    </a>
                                    <a href="dashboard_siswa.php" class="btn btn-primary px-4">Kembali ke Dashboard</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-center">

                        <div class="copyright ">
                            2025, made with <i class="fa fa-heart heart text-danger"></i> by
                            <a href="">Rahayu</a>
                        </div>

                    </div>
                </footer>
            </div>

            <!-- End Custom template -->
        </div>


        <div class="modal fade" id="calendarModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kalender</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!--   Core JS Files   -->
        <script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
        <script src="../../assets/js/core/popper.min.js"></script>
        <script src="../../assets/js/core/bootstrap.min.js"></script>

        <!-- jQuery Scrollbar -->
        <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

        <!-- Chart JS -->
        <script src="assets/js/plugin/chart.js/chart.min.js"></script>

        <!-- jQuery Sparkline -->
        <script src="../../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

        <!-- Chart Circle -->
        <script src="../../assets/js/plugin/chart-circle/circles.min.js"></script>

        <!-- Datatables -->
        <script src="../../assets/js/plugin/datatables/datatables.min.js"></script>

        <!-- Bootstrap Notify -->
        <script src="../../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

        <!-- jQuery Vector Maps -->
        <script src="../../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
        <script src="../../assets/js/plugin/jsvectormap/world.js"></script>

        <!-- Google Maps Plugin -->
        <script src="../../assets/js/plugin/gmaps/gmaps.js"></script>

        <!-- Sweet Alert -->
        <script src="../../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

        <!-- Kaiadmin JS -->
        <script src="../../ assets/js/kaiadmin.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {

            const calendarEl = document.getElementById('calendar');
            let calendar;

            document.getElementById('openCalendar').addEventListener('click', function(e) {
                e.preventDefault();

                const modal = new bootstrap.Modal(document.getElementById('calendarModal'));
                modal.show();

                setTimeout(() => {
                    if (!calendar) {
                        calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            height: 500,
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            events: [{
                                title: 'Pendaftaran',
                                start: new Date().toISOString().split(
                                    'T')[
                                    0]
                            }]
                        });
                        calendar.render();
                    }
                }, 300);
            });
        });
        </script>

</body>

</html>