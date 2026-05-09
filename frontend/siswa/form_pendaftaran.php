<?php 
session_start(); 
include '../../backend/koneksi.php'; 

// 1. Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$id_user = $_SESSION['user_id']; 

// 2. Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Ambil data pendaftaran
$query_cek = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_user = '$id_user'");
if (!$query_cek) {
    die("Query Gagal: " . mysqli_error($conn));
}

$sudah_daftar = mysqli_num_rows($query_cek);
$data_daftar = mysqli_fetch_assoc($query_cek);

// 4. Ambil data user (PERBAIKAN: Gunakan $id_user, bukan $id)
$query_user = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($query_user);

// Ambil data periode pendaftaran terbaru/aktif
$query_periode = mysqli_query($conn, "
    SELECT * FROM periode
    WHERE CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai
    LIMIT 1
");

$periode = mysqli_fetch_assoc($query_periode);
$periodeAktif = ($periode !== null);

if ($periodeAktif) {
    $catatan = $periode['catatan'] ?? 'Tidak ada catatan.';

    $tgl_mulai = date('d F Y', strtotime($periode['tanggal_mulai']));
    $tgl_selesai = date('d F Y', strtotime($periode['tanggal_selesai']));
} else {
    $catatan = 'Tidak ada periode aktif.';
    $tgl_mulai = '-';
    $tgl_selesai = '-';
}
$query = mysqli_query(
    $conn,
    "SELECT * FROM pendaftaran 
     WHERE id_user = '$id_user'
     AND status = 'lulus'
     LIMIT 1"
);

if (!$query) {
    die("Query Gagal: " . mysqli_error($conn));
}

$cek_daftar = mysqli_fetch_assoc($query);

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Forms Pendaftaran</title>
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
                        <?php if ($cek_daftar): ?>
                        <li class="nav-item">
                            <a href="ujian.php">
                                <i class=" fas fa-th-list"></i>
                                <p>Seleksi</p>
                            </a>
                        </li>
                        <?php endif; ?>
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
                                <a href="#">Form Pendaftaran</a>
                            </li>
                            <ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Form Pendaftaran</div>
                                </div>
                                <div class="card-body">
                                    <?php if ($sudah_daftar > 0): 
        $status_pendaftaran = strtolower($data_daftar['status'] ?? 'pending'); 
    ?>
                                    <div class="text-center py-5">
                                        <?php if ($status_pendaftaran == 'lulus' || $status_pendaftaran == 'diterima'): ?>
                                        <div class="mb-4">
                                            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                                        </div>
                                        <h2 class="fw-bold">Selamat! Pendaftaran Anda Diterima</h2>
                                        <p class="text-muted fs-5">
                                            Selamat, Anda telah dinyatakan <b>Lulus/Diterima</b>. <br>
                                            Silakan cek detail pendaftaran untuk langkah pendaftaran ulang.
                                        </p>
                                        <?php else: ?>
                                        <div class="mb-4">
                                            <i class="fas fa-clock text-warning" style="font-size: 80px;"></i>
                                        </div>
                                        <h2 class="fw-bold">Terima Kasih Telah Mendaftar!</h2>
                                        <p class="text-muted fs-5">
                                            Data Anda telah kami terima dan sedang dalam <b>proses peninjauan</b>. <br>
                                            Mohon menunggu untuk informasi seleksi selanjutnya.
                                        </p>
                                        <?php endif; ?>

                                        <div class="mt-4">
                                            <a href="detail_pendaftaran.php" class="btn btn-primary btn-round">
                                                <i class="fas fa-eye me-2"></i> Lihat Detail Pendaftaran
                                            </a>
                                        </div>
                                    </div>

                                    <?php else: ?>
                                    <form action="../../backend/prosespendaftaran.php" method="POST"
                                        enctype="multipart/form-data" id="formPendaftaran">

                                        <div class="row">
                                            <div class="col-md-6 col-lg-4">
                                                <label class="mb-3"><b>Biodata Siswa</b></label>

                                                <div class="form-group">
                                                    <label>Nama Lengkap<span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_lengkap"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>NISN<span class="text-danger">*</span></label>
                                                    <input type="number" name="nisn"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>NIK<span class="text-danger">*</span></label>
                                                    <input type="text" name="nik" class="form-control form-control-sm"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Tanggal Lahir<span class="text-danger">*</span></label>
                                                    <input type="date" name="tgl_lahir"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>No HP<span class="text-danger">*</span></label>
                                                    <input type="text" name="no_hp" class="form-control form-control-sm"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                                    <select name="jenis_kelamin" class="form-select form-control-sm"
                                                        required>
                                                        <option value="">-- Pilih --</option>
                                                        <option value="Laki-laki">Laki-laki</option>
                                                        <option value="Perempuan">Perempuan</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Agama<span class="text-danger">*</span></label>
                                                    <select name="agama" class="form-select form-control-sm" required>
                                                        <option value="">-- Pilih --</option>
                                                        <option value="Islam">Islam</option>
                                                        <option value="Kristen">Kristen</option>
                                                        <option value="Budha">Budha</option>
                                                        <option value="Hindu">Hindu</option>
                                                        <option value="Khonghucu">Khonghucu</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Asal Sekolah<span class="text-danger">*</span></label>
                                                    <input type="text" name="asal_sekolah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Alamat Asal Sekolah<span class="text-danger">*</span></label>
                                                    <input type="text" name="alamat_asal_sekolah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Alamat<span class="text-danger">*</span></label>
                                                    <textarea name="alamat" rows="3"
                                                        class="form-control form-control-sm" required></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-4">
                                                <label class="mb-3"><b>Data Orang Tua/Wali</b></label>

                                                <div class="form-group">
                                                    <label>Nama Ayah<span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_ayah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Nik Ayah<span class="text-danger">*</span></label>
                                                    <input type="number" name="nik_ayah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Tanggal Lahir Ayah<span class="text-danger">*</span></label>
                                                    <input type="date" name="tanggal_lahir_ortu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pendidikan Ayah<span class="text-danger">*</span></label>
                                                    <input type="text" name="pendidikan_ayah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pekerjaan Ayah<span class="text-danger">*</span></label>
                                                    <input type="text" name="pekerjaan_ayah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Penghasilan Ayah (per bulan)<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="penghasilan_ayah"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Nama Ibu<span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_ibu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Tanggal Lahir Ibu<span class="text-danger">*</span></label>
                                                    <input type="date" name="tanggal_lahir_ortu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Nik Ibu<span class="text-danger">*</span></label>
                                                    <input type="number" name="nik_ibu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pendidikan Ibu<span class="text-danger">*</span></label>
                                                    <input type="text" name="pendidikan_ibu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pekerjaan Ibu<span class="text-danger">*</span></label>
                                                    <input type="text" name="pekerjaan_ibu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Penghasilan Ibu (per bulan)<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="penghasilan_ibu"
                                                        class="form-control form-control-sm" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Alamat Orangtua/wali<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="alamat_ortu"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-4">
                                                <label class="mb-3"><b>File Pendukung</b></label>

                                                <div class="form-group">
                                                    <label>Ijazah<span class="text-danger">*</span></label>
                                                    <input type="file" name="foto_ijazah"
                                                        class="form-control form-control-sm" accept="pdf/*" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Raport<span class="text-danger">*</span></label>
                                                    <input type="file" name="foto_raport"
                                                        class="form-control form-control-sm" accept="pdf/*" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Kartu Keluarga<span class="text-danger">*</span></label>
                                                    <input type="file" name="foto_kk"
                                                        class="form-control form-control-sm" accept="pdf/*" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>KTP Ayah<span class="text-danger">*</span></label>
                                                    <input type="file" name="foto_ktp_ortu"
                                                        class=" form-control form-control-sm" accept="pdf/*" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>KTP Ibu<span class="text-danger">*</span></label>
                                                    <input type="file" name="foto_ktp_ortu"
                                                        class="form-control form-control-sm" accept="pdf/*" required>
                                                </div>

                                            </div>

                                        </div> <!-- row -->

                                        <div class="card-action text-end mt-3">
                                            <button type="button" onclick="confirmSubmit()" class="btn btn-success">
                                                Kirim
                                            </button>
                                            <button type="reset" class="btn btn-danger">Batal</button>

                                        </div>

                                    </form>
                                    <?php endif; ?>

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

        <!-- Kaiadmin JS -->
        <script src="../../assets/js/kaiadmin.min.js"></script>


        <script>
        function confirmSubmit() {
            swal({
                title: "Yakin ingin mengirim?",
                text: "Pastikan data yang Anda isi sudah benar.",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Batal",
                        visible: true,
                        className: "btn btn-danger"
                    },
                    confirm: {
                        text: "Ya, Kirim",
                        className: "btn btn-success"
                    }
                }
            }).then((willSubmit) => {
                if (willSubmit) {
                    // Gunakan ID form agar lebih spesifik
                    document.getElementById("formPendaftaran").submit();
                }
            });
        }
        </script>

        <?php if (isset($_SESSION['swal'])): ?>
        <script>
        swal({
            title: "<?= $_SESSION['swal']['title'] ?>",
            text: "<?= $_SESSION['swal']['text'] ?>",
            icon: "<?= $_SESSION['swal']['type'] ?>",
            button: "OK"
        });
        </script>
        <?php unset($_SESSION['swal']); endif; ?>


</body>

</html>