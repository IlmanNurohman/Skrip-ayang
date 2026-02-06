<?php
session_start();
// PERBAIKAN: Path disesuaikan menjadi dua tingkat (../..)
include '../../backend/koneksi.php'; 

// 1. Proteksi Siswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../login.php");
    exit();
}

// Pastikan variabel session user_id ada
if (!isset($_SESSION['user_id'])) {
    die("Sesi user tidak ditemukan. Silakan login kembali.");
}

$id_user = $_SESSION['user_id'];

// 2. Ambil data pendaftaran (Gunakan variabel $conn sesuai koneksi.php Anda)
$query = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_user = '$id_user'");

if (!$query) {
    die("Query Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Anda belum memiliki data pendaftaran.'); window.location='form.php';</script>";
    exit();
}
$query_user = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($query_user);

define('SECRET_KEY', 'ganti_dengan_kunci_rahasia_yang_sangat_panjang_123!@#');
define('SECRET_IV', 'iv_rahasia_456!@#');

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
    <title>Detail Pendaftaran</title>
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

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="dashboard_siswa.php" class="logo">
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
                                <i class="fas fa-th-list"></i>
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
                        <a href="dashboard_siswa.php" class="logo">
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
                        <h3 class="fw-bold mb-3">Pendaftaran</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="#">
                                    <i class="fas fa-layer-group"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="form_pendaftaran.php">Pendaftaran</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Detail Pendaftaran</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Detail Pendaftaran</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">
                                                    <div class="card-title text-white">Biodata Siswa
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th width="30%">Nama Lengkap</th>
                                                            <td><?= decryptData($data['nama_lengkap']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>NIK</th>
                                                            <td><?= decryptData($data['nik']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Jenis Kelamin</th>
                                                            <td><?= ucfirst(decryptData($data['jenis_kelamin'])) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Agama</th>
                                                            <td><?= ucfirst(decryptData($data['agama'])) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Asal Sekolah</th>
                                                            <td><?= decryptData($data['asal_sekolah']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Email</th>
                                                            <td><?= decryptData($data['email']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>No. HP</th>
                                                            <td><?= decryptData($data['no_hp']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Alamat</th>
                                                            <td><?= decryptData($data['alamat']) ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">
                                                    <div class="card-title text-white">Data Orang Tua
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-bordered">

                                                        <tr>
                                                            <th>Nama Ayah / Ibu</th>
                                                            <td>
                                                                <?= decryptData($data['nama_ayah']) ?> /
                                                                <?= decryptData($data['nama_ibu']) ?>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Pekerjaan Ayah / Ibu</th>
                                                            <td>
                                                                <?= decryptData($data['pekerjaan_ayah']) ?> /
                                                                <?= decryptData($data['pekerjaan_ibu']) ?>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Pendidikan Ayah / Ibu</th>
                                                            <td>
                                                                <?= decryptData($data['pendidikan_ayah']) ?> /
                                                                <?= decryptData($data['pendidikan_ibu']) ?>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Penghasilan Ayah / Ibu</th>
                                                            <td>
                                                                Rp
                                                                <?= number_format((int) decryptData($data['penghasilan_ayah']), 0, ',', '.') ?>
                                                                /
                                                                Rp
                                                                <?= number_format((int) decryptData($data['penghasilan_ibu']), 0, ',', '.') ?>
                                                            </td>


                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header bg-secondary text-white">
                                                    <div class="card-title text-white">Dokumen Lampiran</div>
                                                </div>
                                                <div class="card-body text-center">
                                                    <button type="button" class="btn btn-outline-info mb-2 w-100"
                                                        data-bs-toggle="modal" data-bs-target="#modalIjazah">
                                                        <i class="fas fa-file-image"></i> Lihat Ijazah
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info mb-2 w-100"
                                                        data-bs-toggle="modal" data-bs-target="#modalRaport">
                                                        <i class="fas fa-file-image"></i> Lihat Raport
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info mb-2 w-100"
                                                        data-bs-toggle="modal" data-bs-target="#modalKK">
                                                        <i class="fas fa-file-image"></i> Lihat Kartu Keluarga
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card mt-4">
                                                <div class="card-header bg-secondary text-white">
                                                    <div class="card-title text-white">Status Pendaftaran Saat Ini
                                                    </div>
                                                </div>
                                                <div class="card-body text-center">

                                                    <?php 
                                     $status = strtolower($data['status']);
                                     if ($status == 'lulus' || $status == 'diterima') {
                                     echo '<div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                    <b style="font-size: 1.2rem;">DITERIMA / LULUS</b>
                                    </div>';
                                    } elseif ($status == 'tidak lulus' || $status == 'ditolak') {
                                    echo '<div class="alert alert-danger mb-0">
                                    <i class="fas fa-times-circle fa-2x mb-2"></i><br>
                                    <b style="font-size: 1.2rem;">DITOLAK / TIDAK LULUS</b>
                                    </div>';
                                    } else {
                                    echo '<div class="alert alert-warning mb-0">
                                    <i class="fas fa-clock fa-2x mb-2"></i><br>
                                    <b style="font-size: 1.2rem;">PENDING / PROSES</b>
                                    </div>';
                                    }
                                    ?>
                                                    <div class="mt-4 text-start">
                                                        <label class="fw-bold text-uppercase small">Catatan</label>
                                                        <div class="p-3 bg-light border rounded shadow-sm">
                                                            <?php if(!empty($data['catatan_admin'])): ?>
                                                            <p class="mb-0 italic text-dark">
                                                                "<?= $data['catatan_admin'] ?>"</p>
                                                            <?php else: ?>
                                                            <p class="mb-0 text-muted italic small">Belum ada
                                                                catatan dari admin.</p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>



                                                </div>

                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>





                        <div class="modal fade" id="modalIjazah" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Foto Ijazah - <?= $data['nama_lengkap'] ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="../../<?= $data['foto_ijazah'] ?>" class="img-fluid rounded"
                                            alt="Ijazah">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalRaport" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Foto Raport - <?= $data['nama_lengkap'] ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="../../<?= $data['foto_raport'] ?>" class="img-fluid rounded"
                                            alt="Raport">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalKK" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Foto KK - <?= $data['nama_lengkap'] ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="../../<?= $data['foto_kk'] ?>" class="img-fluid rounded" alt="KK">
                                    </div>
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
        <!--   Core JS Files   -->
        <script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
        <script src="../../assets/js/core/popper.min.js"></script>
        <script src="../../assets/js/core/bootstrap.min.js"></script>

        <!-- jQuery Scrollbar -->
        <script src="../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

        <!-- Chart JS -->
        <script src="../../assets/js/plugin/chart.js/chart.min.js"></script>

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

        <!-- Datatables -->
        <script src="../../assets/js/plugin/datatables/datatables.min.js"></script>

        <!-- Kaiadmin JS -->
        <script src="../assets/js/kaiadmin.min.js"></script>



</body>

</html>