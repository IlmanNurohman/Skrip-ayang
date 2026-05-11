<?php
session_start();
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

$query_cek_ujian = mysqli_query($conn, "SELECT * FROM nilai_seleksi WHERE siswa_id = '$id_user'");
$sudah_ujian = mysqli_num_rows($query_cek_ujian);

$soal = mysqli_query($conn, "SELECT * FROM soal");
$nav_numbers = [];
$no = 1;

$query_user = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($query_user);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Tes Seleksi</title>
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
    /* Sembunyikan radio button asli */
    .btn-check:checked+.btn-outline-primary {
        background-color: #1572e8 !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(21, 114, 232, 0.4);
    }

    .btn-nav.active {
        background-color: #1572e8 !important;
        color: white !important;
        border-color: #1572e8 !important;
    }

    /* Warna saat soal sudah dijawab (Hijau) */
    .btn-nav.answered {
        background-color: #28a745 !important;
        /* Warna Hijau Bootstrap */
        border-color: #28a745 !important;
        color: white !important;
    }

    .soal-container {
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .marquee-container {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        background: #fff9e6;
        /* Warna latar kuning pucat agar senada dengan peringatan */
        padding: 10px 0;
        border-radius: 5px;
    }

    .marquee-text {
        display: inline-block;
        padding-left: 100%;
        /* Durasi diubah menjadi 50 detik agar lebih lambat dan nyaman dibaca */
        animation: marquee 50s linear infinite;
        color: #856404;
        font-size: 16px;
        /* Ukuran sedikit diperbesar agar lebih jelas */
        font-weight: 500;
    }

    @keyframes marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Berhenti berjalan saat kursor diarahkan (hover) agar mudah dibaca jika perlu */
    .marquee-container:hover .marquee-text {
        animation-play-state: paused;
        cursor: pointer;
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
                            <img src="../../assets/img/logo_ict.png" alt=" navbar brand" class="navbar-brand"
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
                                <a href="#">Tes Seleksi</a>
                            </li>
                            <ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">

                                    <div class="card-body py-2 overflow-hidden">
                                        <div class="marquee-container">
                                            <span class="marquee-text">
                                                <b>PERHATIAN:</b> Kerjakan soal dengan teliti! Pastikan semua
                                                pertanyaan terjawab. Jika waktu pengerjaan habis, sistem akan otomatis
                                                mengirimkan jawaban Anda. Jangan menutup halaman ini sebelum menekan
                                                tombol "Kirim Semua Jawaban". Selamat mengerjakan!
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                </div>

                            </div>
                            <div class="row">
                                <?php if ($sudah_ujian > 0): ?>
                                <div class="col-md-12">
                                    <div class="card card-round shadow-sm">
                                        <div class="card-body text-center py-5">
                                            <div class="mb-4">
                                                <i class="fas fa-check-circle text-success"
                                                    style="font-size: 100px;"></i>
                                            </div>
                                            <h2 class="fw-bold">Terima Kasih!</h2>
                                            <p class="text-muted fs-5"> Anda telah menyelesaikan tes seleksi ini. Data
                                                jawaban Anda sudah tersimpan dengan aman di sistem kami. </p>
                                            <div class="mt-4">
                                                <a href="hasil.php" class="btn btn-primary btn-round btn-lg px-5">
                                                    <i class="fas fa-poll me-2"></i> Lihat Hasil Seleksi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php else: ?>
                                <div class="col-md-8">
                                    <div class="card card-round">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="card-title">Tes Seleksi</div>
                                            <div id="soal-counter" class="fw-bold text-primary">Soal 1 dari 0</div>
                                        </div>
                                        <div class="card-body">
                                            <form action="../../backend/simpan_jawaban.php" method="POST"
                                                id="formUjian">
                                                <?php 
                    $nav_numbers = [];
                    $total_soal = mysqli_num_rows($soal);
                    $no = 1;
                    mysqli_data_seek($soal, 0);
                    while ($row = mysqli_fetch_assoc($soal)) : 
                        $nav_numbers[] = $row['id'];
                    ?>
                                                <div class="soal-container d-none" id="container-<?= $row['id'] ?>"
                                                    data-index="<?= $no ?>">
                                                    <p class="fw-bold fs-5"><?= $no++ ?>.
                                                        <?= htmlspecialchars($row['pertanyaan']) ?></p>

                                                    <div class="ms-3 mt-3">
                                                        <div class="form-check mb-3 p-0">
                                                            <input class="btn-check" type="radio"
                                                                name="jawaban[<?= $row['id'] ?>]" value="A"
                                                                id="q<?= $row['id'] ?>a"
                                                                onchange="updateNavStatus(<?= $row['id'] ?>)">
                                                            <label class="btn btn-outline-primary w-100 text-start p-3"
                                                                for="q<?= $row['id'] ?>a">A.
                                                                <?= htmlspecialchars($row['opsi_a']) ?></label>
                                                        </div>
                                                        <div class="form-check mb-3 p-0">
                                                            <input class="btn-check" type="radio"
                                                                name="jawaban[<?= $row['id'] ?>]" value="B"
                                                                id="q<?= $row['id'] ?>b"
                                                                onchange="updateNavStatus(<?= $row['id'] ?>)">
                                                            <label class="btn btn-outline-primary w-100 text-start p-3"
                                                                for="q<?= $row['id'] ?>b">B.
                                                                <?= htmlspecialchars($row['opsi_b']) ?></label>
                                                        </div>
                                                        <div class="form-check mb-3 p-0">
                                                            <input class="btn-check" type="radio"
                                                                name="jawaban[<?= $row['id'] ?>]" value="C"
                                                                id="q<?= $row['id'] ?>c"
                                                                onchange="updateNavStatus(<?= $row['id'] ?>)">
                                                            <label class="btn btn-outline-primary w-100 text-start p-3"
                                                                for="q<?= $row['id'] ?>c">C.
                                                                <?= htmlspecialchars($row['opsi_c']) ?></label>
                                                        </div>
                                                        <div class="form-check mb-3 p-0">
                                                            <input class="btn-check" type="radio"
                                                                name="jawaban[<?= $row['id'] ?>]" value="D"
                                                                id="q<?= $row['id'] ?>d"
                                                                onchange="updateNavStatus(<?= $row['id'] ?>)">
                                                            <label class="btn btn-outline-primary w-100 text-start p-3"
                                                                for="q<?= $row['id'] ?>d">D.
                                                                <?= htmlspecialchars($row['opsi_d']) ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endwhile; ?>

                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary btn-round"
                                                        id="btn-prev" onclick="moveSoal(-1)">
                                                        <i class="fa fa-arrow-left me-1"></i> Sebelumnya
                                                    </button>

                                                    <button type="submit" class="btn btn-success btn-round d-none"
                                                        id="btn-submit">
                                                        Kirim Semua Jawaban <i class="fa fa-check ms-1"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-primary btn-round"
                                                        id="btn-next" onclick="moveSoal(1)">
                                                        Selanjutnya <i class="fa fa-arrow-right ms-1"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card card-round">
                                        <div class="card-header">
                                            <div class="card-title">Navigasi Soal</div>
                                            <div class="mt-2">
                                                <div class="alert alert-dark text-center py-2 mb-0">
                                                    <i class="fas fa-clock me-2"></i>Sisa Waktu: <span id="timer"
                                                        class="fw-bold">00:00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php 
                    $nav_no = 1;
                    foreach ($nav_numbers as $id) : ?>
                                                <button type="button" id="nav-<?= $id ?>"
                                                    onclick="showSoalByNo(<?= $nav_no ?>)"
                                                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center fw-bold btn-nav"
                                                    style="width: 40px; height: 40px; border-radius: 8px;">
                                                    <?= $nav_no++ ?>
                                                </button>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="mt-4 pt-3 border-top">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div style="width: 15px; height: 15px; background: #28a745; border-radius: 3px;"
                                                        class="me-2"></div>
                                                    <small>Sudah Dijawab</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 15px; height: 15px; border: 1px solid #6c757d; border-radius: 3px;"
                                                        class="me-2"></div>
                                                    <small>Belum Dijawab</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                                start: new Date().toISOString().split('T')[
                                    0]
                            }]
                        });
                        calendar.render();
                    }
                }, 300);
            });
        });
        </script>

        <script>
        let currentStep = 1;
        const totalSoal = <?= count($nav_numbers) ?>;

        function showSoalByNo(no) {
            // Sembunyikan semua soal
            document.querySelectorAll('.soal-container').forEach(el => el.classList.add('d-none'));

            // Tampilkan soal yang dipilih
            const targetSoal = document.querySelector(`.soal-container[data-index="${no}"]`);
            if (targetSoal) {
                targetSoal.classList.remove('d-none');
                currentStep = no;
                updateUI();
            }
        }

        function moveSoal(step) {
            let nextStep = currentStep + step;
            if (nextStep >= 1 && nextStep <= totalSoal) {
                showSoalByNo(nextStep);
            }
        }

        function updateUI() {
            // Update Counter
            document.getElementById('soal-counter').innerText = `Soal ${currentStep} dari ${totalSoal}`;

            // Update Button Visibility
            document.getElementById('btn-prev').disabled = (currentStep === 1);

            if (currentStep === totalSoal) {
                document.getElementById('btn-next').classList.add('d-none');
                document.getElementById('btn-submit').classList.remove('d-none');
            } else {
                document.getElementById('btn-next').classList.remove('d-none');
                document.getElementById('btn-submit').classList.add('d-none');
            }

            // Update Navigasi Aktif
            document.querySelectorAll('.btn-nav').forEach(btn => btn.classList.remove('active'));
            document.getElementsByClassName('btn-nav')[currentStep - 1].classList.add('active');
        }

        function updateNavStatus(soalId) {
            // Beri warna pada kotak navigasi jika sudah diisi
            document.getElementById('nav-' + soalId).classList.add('answered');
        }

        // Inisialisasi tampilan pertama
        document.addEventListener('DOMContentLoaded', () => {
            showSoalByNo(1);
        });

        // Konfigurasi Waktu: Total Soal * 1 Menit (60 detik)
        let totalTime = totalSoal * 60;
        const timerDisplay = document.getElementById('timer');

        function startTimer() {
            const countdown = setInterval(() => {
                let minutes = Math.floor(totalTime / 60);
                let seconds = totalTime % 60;

                // Format 00:00
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.innerText = `${minutes}:${seconds}`;

                if (totalTime <= 0) {
                    clearInterval(countdown);
                    swal("Waktu Habis!", "Jawaban Anda akan dikirim otomatis.", "warning").then(() => {
                        document.getElementById('formUjian').submit();
                    });
                }

                // Peringatan jika waktu sisa 1 menit
                if (totalTime === 60) {
                    timerDisplay.parentElement.classList.replace('alert-dark', 'alert-danger');
                }

                totalTime--;
            }, 1000);
        }

        // Jalankan timer saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', () => {
            startTimer();
            showSoalByNo(1);
        });
        </script>

</body>

</html>