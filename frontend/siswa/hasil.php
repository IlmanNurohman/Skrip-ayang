<?php
session_start();
include '../../backend/koneksi.php';

// Proteksi: Pastikan hanya siswa yang login bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../login.php");
    exit();
}

$id_user = $_SESSION['user_id'];

// Ambil data nilai dari database
$query = mysqli_query($conn, "SELECT n.*, u.username 
                              FROM nilai_seleksi n 
                              JOIN users u ON n.siswa_id = u.id 
                              WHERE n.siswa_id = '$id_user' 
                              ORDER BY n.id DESC LIMIT 1");
$data = mysqli_fetch_assoc($query);

// Jika belum ujian, arahkan kembali ke halaman ujian
if (!$data) {
    header("Location: ujian.php");
    exit();
}

$status = $data['status']; // 'Lulus' atau 'Tidak Lulus'
$nilai = $data['nilai'];

$query_user = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($query_user);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Hasil Tes</title>
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
    <style>
    .result-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .score-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 8px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px auto;
        background: #fff;
    }

    .score-value {
        font-size: 3rem;
        font-weight: 800;
    }

    .status-badge {
        font-size: 1.2rem;
        padding: 10px 30px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    </style>


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
                        <li class="nav-item active">
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
                        <h3 class="fw-bold mb-3">Seleksi</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="#">
                                    <i class=" fas fa-th-list"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="ujian.php">Seleksi</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Hasil Tes</a>
                            </li>
                            <ul>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Hasil Tes</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="card result-card text-center">
                                                <div
                                                    class="card-header <?= ($status == 'Lulus') ? 'bg-success' : 'bg-danger' ?> py-4">
                                                    <h3 class="text-white mb-0 fw-bold">HASIL SELEKSI COMPUTER BASED
                                                        TEST
                                                    </h3>
                                                </div>


                                                <p class="text-muted">Halo, <b><?= $data['username'] ?></b>.
                                                    Berikut
                                                    adalah
                                                    hasil ujian Anda:</p>

                                                <div class="score-circle"
                                                    style="border-color: <?= ($status == 'Lulus') ? '#28a745' : '#dc3545' ?>;">
                                                    <span class="score-value"
                                                        style="color: <?= ($status == 'Lulus') ? '#28a745' : '#dc3545' ?>;">
                                                        <?= $nilai ?>
                                                    </span>
                                                </div>

                                                <div class="mb-4">
                                                    <?php if ($status == 'Lulus'): ?>
                                                    <span class="badge bg-success status-badge">
                                                        <i class="fas fa-check-circle me-2"></i> LULUS
                                                    </span>
                                                    <div class="mt-3 text-success fw-bold">
                                                        Selamat! Anda memenuhi kriteria kelulusan (Minimum 60).
                                                    </div>
                                                    <?php else: ?>
                                                    <span class="badge bg-danger status-badge">
                                                        <i class="fas fa-times-circle me-2"></i> TIDAK LULUS
                                                    </span>
                                                    <div class="mt-3 text-danger fw-bold">
                                                        Maaf, nilai Anda di bawah standar kelulusan (60).
                                                    </div>
                                                    <?php endif; ?>
                                                </div>






                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action text-end mt-3">
                                    <a href="hasil_pendaftaran.php" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i> Lihat Detail
                                    </a>
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

            <!-- Custom template | don't include it in your project! -->
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