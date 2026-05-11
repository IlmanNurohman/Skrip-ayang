<?php
session_start();
include '../../backend/koneksi.php'; // sesuaikan path

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../../login.php");
    exit;
}

$id_user = $_SESSION['user_id'] ?? null;

if (!$id_user) {
    die('User belum login');
}

$query = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id_user'");
$user  = mysqli_fetch_assoc($query);
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



function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    
    $minutes      = round($seconds / 60);           // value 60 is seconds  
    $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec  
    $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 min * 60 sec  
    $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 min * 60 sec  
    $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365+366)/5/12)*24*60*60  
    $years        = round($seconds / 31553280);     // value 31553280 is ((365+365+365+365+366)/5)*24*60*60  

    if ($seconds <= 60) return "Baru saja";
    else if ($minutes <= 60) return "$minutes menit yang lalu";
    else if ($hours <= 24) return "$hours jam yang lalu";
    else if ($days <= 7) return "$days hari yang lalu";
    else if ($weeks <= 4.3) return "$weeks minggu yang lalu";
    else if ($months <= 12) return "$months bulan yang lalu";
    else return "$years tahun yang lalu";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard</title>
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
                            <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
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
                        <?php if ($periodeAktif): ?>
                        <li class="nav-item">
                            <a href="form_pendaftaran.php">
                                <i class="fas fa-layer-group"></i>
                                <p>Pendaftaran</p>

                            </a>
                        </li>
                        <?php endif; ?>
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
                            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                    aria-expanded="false" aria-haspopup="true">
                                    <i class="fa fa-search"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search ..." class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <i class="fas fa-layer-group"></i>
                                </a>
                                <div class="dropdown-menu quick-actions animated fadeIn">
                                    <div class="quick-actions-header">
                                        <span class="title mb-1">Quick Actions</span>
                                        <span class="subtitle op-7">Shortcuts</span>
                                    </div>
                                    <div class="quick-actions-scroll scrollbar-outer">
                                        <div class="quick-actions-items">
                                            <div class="row m-0">
                                                <a class="col-6 col-md-4 p-0" href="#" id="openCalendar">

                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-danger rounded-circle">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </div>
                                                        <span class="text">Calendar</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0" href="#" id="openMaps">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-warning rounded-circle">
                                                            <i class="fas fa-map"></i>
                                                        </div>
                                                        <span class="text">Maps</span>
                                                    </div>
                                                </a>
                                                <a class="col-6 col-md-4 p-0"
                                                    href="https://wa.me/6282324279284?text=Halo%20Admin,%20saya%20ingin%20bertanya%20mengenai%20pendaftaran."
                                                    target="_blank">
                                                    <div class="quick-actions-item">
                                                        <div class="avatar-item bg-success rounded-circle">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </div>
                                                        <span class="text">WhatsApp</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Dashboard</h3>
                            <h6 class="op-7 mb-2">
                                Halo, selamat datang <?= $_SESSION['username']; ?>
                            </h6>
                            <p class="mb-0 text-muted">
                                di sistem penerimaan peserta didik baru SMA ICT Pakenjeng . Apabila
                                ada pertanyaan atau kendala, silakan hubungi admin melalui menu WhatsApp yang tersedia
                                di quik action di pojok atas.
                                Terimakasih telah menggunakan sistem ini, semoga proses pendaftaran berjalan lancar!
                            </p>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Pengumuman</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if ($periode): ?>

                                    <p class="mb-0" style="font-size: 1.1rem;">
                                        <i class="fas fa-bullhorn me-2"></i>
                                        Pendaftaran dibuka mulai tanggal <b><?= $tgl_mulai; ?></b>
                                        sampai tanggal <b><?= $tgl_selesai; ?></b>.
                                        Lebih dari itu pendaftaran <b>ditutup</b>.
                                    </p>
                                    <hr>
                                    <?php if (!empty($periode['catatan'])): ?>
                                    <span>Catatan : <?= $periode['catatan']; ?></span>
                                    <?php else: ?>
                                    <span>Catatan : -</span>
                                    <?php endif; ?>

                                    <?php if (!empty($periode['catatan'])): ?>
                                    <?php endif; ?>

                                    <?php else: ?>
                                    <p class="text-muted">Belum ada informasi periode pendaftaran.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="card card-round">
                                        <div class="card-header">
                                            <div class="card-head-row card-tools-still-right">
                                                <div class="card-title">Hasil Seleksi</div>
                                                <div class="card-tools">
                                                    <div class="dropdown">
                                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                                            id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="#">Action</a>
                                                            <a class="dropdown-item" href="#">Another action</a>
                                                            <a class="dropdown-item" href="#">Something else here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col">Nama Siswa</th>
                                                            <th scope="col" class="text-end">Waktu Selesai</th>
                                                            <th scope="col" class="text-end">Skor</th>
                                                            <th scope="col" class="text-end">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
            // Query untuk mengambil data hasil seleksi
            // Kita gabungkan (JOIN) dengan tabel users untuk mengambil kolom 'username'
            $query_hasil = mysqli_query($conn, "SELECT n.*, u.username 
                                                FROM nilai_seleksi n 
                                                JOIN users u ON n.siswa_id = u.id 
                                                ORDER BY n.id DESC LIMIT 5");

            if (mysqli_num_rows($query_hasil) > 0) {
                while ($row = mysqli_fetch_assoc($query_hasil)) :
                    // Tentukan warna badge berdasarkan status
                    $badge_class = ($row['status'] == 'Lulus') ? 'badge-success' : 'badge-danger';
                    $icon_class = ($row['status'] == 'Lulus') ? 'fa-check' : 'fa-times';
                    $btn_class = ($row['status'] == 'Lulus') ? 'btn-success' : 'btn-danger';
            ?>
                                                        <tr>
                                                            <th scope="row">
                                                                <button
                                                                    class="btn btn-icon btn-round <?= $btn_class ?> btn-sm me-2">
                                                                    <i class="fa <?= $icon_class ?>"></i>
                                                                </button>
                                                                <?= htmlspecialchars($row['username']) ?>
                                                            </th>
                                                            <td class="text-end">
                                                                <?= date('d M Y, H:i', strtotime('now')) ?>
                                                            </td>
                                                            <td class="text-end fw-bold">
                                                                <?= $row['nilai'] ?>
                                                            </td>
                                                            <td class="text-end">
                                                                <span
                                                                    class="badge <?= $badge_class ?>"><?= $row['status'] ?></span>
                                                            </td>
                                                        </tr>
                                                        <?php 
                endwhile; 
            } else {
                echo '<tr><td colspan="4" class="text-center py-4">Belum ada data seleksi.</td></tr>';
            }
            ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-round">
                                        <div class="card-body">
                                            <div class="card-head-row card-tools-still-right">
                                                <div class="card-title">Pendaftaran Baru</div>
                                            </div>
                                            <div class="card-list py-4">
                                                <?php
    // Query mengambil 10 pendaftar terbaru
    $query_recent = mysqli_query($conn, "SELECT p.nama_lengkap, p.created_at, u.username 
                                         FROM pendaftaran p 
                                         JOIN users u ON p.id_user = u.id 
                                         ORDER BY p.id DESC LIMIT 10");

    if (mysqli_num_rows($query_recent) > 0) {
        while ($row = mysqli_fetch_assoc($query_recent)) {
            // Ambil inisial huruf pertama dari username
            $initial = strtoupper(substr($row['username'], 0, 1));
            
            // Array warna acak untuk avatar agar menarik
            $colors = ['bg-primary', 'bg-info', 'bg-success', 'bg-danger', 'bg-warning', 'bg-secondary'];
            $random_color = $colors[array_rand($colors)];
    ?>
                                                <div class="item-list mb-3">
                                                    <div class="avatar">
                                                        <span
                                                            class="avatar-title rounded-circle border border-white <?php echo $random_color; ?>">
                                                            <?php echo $initial; ?>
                                                        </span>
                                                    </div>
                                                    <div class="info-user ms-3">
                                                        <div class="username fw-bold">
                                                            <?php echo htmlspecialchars($row['username']); ?></div>
                                                        <div class="status text-muted" style="font-size: 0.85rem;">
                                                            Melakukan pendaftaran
                                                            <?php echo time_ago($row['created_at']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php 
        } 
    } else {
        echo '<div class="text-center text-muted">Belum ada pendaftaran baru</div>';
    }
    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-center">

                        <div class="copyright ">
                            2026, by Rahayu
                        </div>

                    </div>
                </footer>
            </div>
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

        <div class="modal fade" id="mapsModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lokasi ICT Boarding School Pakenjeng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.0490064280293!2d107.63090179999999!3d-7.459830900000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e66210076a60459%3A0x6a5343201d23b2c1!2sICT%20BOARDING%20SCHOOL!5e0!3m2!1sid!2sid!4v1767444462801!5m2!1sid!2sid"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>

                    </div>
                </div>
            </div>
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
                                start: new Date().toISOString().split('T')[
                                    0]
                            }]
                        });
                        calendar.render();
                    }
                }, 300);
            });
        });

        document.getElementById('openMaps').addEventListener('click', function(e) {
            e.preventDefault();
            const modalMaps = new bootstrap.Modal(document.getElementById('mapsModal'));
            modalMaps.show();
        });
        </script>

</body>

</html>