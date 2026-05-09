<?php
session_start();
include '../../../backend/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die('Akses ditolak');
}
$id = $_SESSION['user_id'] ?? null;

if (!$id) {
    die('User belum login');
}



// --- PROSES TAMBAH KELAS ---
if (isset($_POST['tambah_kelas'])) {
    $nama_kelas = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
    $kuota      = (int)$_POST['kuota'];
    mysqli_query($conn, "INSERT INTO kelas (nama_kelas, kuota) VALUES ('$nama_kelas', $kuota)");
    header("Location: index.php");
}

// --- PROSES EDIT KELAS ---
if (isset($_POST['edit_kelas'])) {
    $id         = (int)$_POST['id_kelas'];
    $nama_kelas = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
    $kuota      = (int)$_POST['kuota'];
    mysqli_query($conn, "UPDATE kelas SET nama_kelas = '$nama_kelas', kuota = $kuota WHERE id = $id");
    header("Location: index.php");
}

// --- PROSES HAPUS KELAS ---
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kelas WHERE id = $id");
    header("Location: index.php");
}

$query = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id'");
$user  = mysqli_fetch_assoc($query);

$query = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

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
    <title>Kelas</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../../../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../../../assets/js/plugin/webfont/webfont.min.js"></script>
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
            urls: ["../../../assets/css/fonts.min.css"],
        },
        active: function() {
            sessionStorage.fonts = true;
        },
    });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../../assets/css/kaiadmin.min.css" />

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="../dashboard_admin.php" class="logo">
                        <img src="../../../assets/img/logo_ict.png" alt="navbar brand"
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
                            <a href="../dashboard_admin.php" class="collapsed" aria-expanded="false">
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
                            <a href="../pendaftaran/index.php">
                                <i class="fas fa-layer-group"></i>
                                <p>Pendaftaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../bank_soal/index.php">
                                <i class="fas fa-th-list"></i>
                                <p>Bank Soal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../periode/tambah_periode.php">
                                <i class="fas fa-pen-square"></i>
                                <p>Periode</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelas/index.php">
                                <i class="fas fa-th-large"></i>
                                <p>Kelas</p>
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
                        <a href="../dashboard_admin.php" class="logo">
                            <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" />
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
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="../../../assets/img/user/<?= $user['foto']; ?>" alt="..."
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
                                            <a class="dropdown-item" href="../../../logout.php">Logout</a>
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
                        <h3 class="fw-bold mb-3">Kelas</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="#">
                                    <i class="fas fa-th-large"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Kelas</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Daftar Kelas</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                <i class="fa fa-plus"></i> Tambah Kelas
                            </button>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Daftar Kelas</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Kuota</th>
                                                    <th style="width: 10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                        $no = 1; 
                        while ($row = mysqli_fetch_assoc($query)) : 
                        ?>

                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row['nama_kelas']; ?></td>
                                                    <td><?= $row['kuota']; ?> Siswa</td>

                                                    <td>
                                                        <div class="form-button-action">

                                                            <button class="btn btn-link btn-info px-1 btn-view-siswa"
                                                                data-id="<?= $row['id']; ?>"
                                                                data-nama="<?= $row['nama_kelas']; ?>">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-link btn-primary px-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEdit<?= $row['id']; ?>">
                                                                <i class="fa fa-edit"></i>
                                                            </button>

                                                            <a href="index_kelas.php?hapus=<?= $row['id']; ?>"
                                                                class="btn btn-link btn-danger px-1"
                                                                onclick="return confirm('Hapus kelas ini?')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Kelas:
                                                                    <?= $row['nama_kelas']; ?></h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id_kelas"
                                                                        value="<?= $row['id']; ?>">
                                                                    <div class="form-group">
                                                                        <label>Nama Kelas</label>
                                                                        <input type="text" name="nama_kelas"
                                                                            class="form-control"
                                                                            value="<?= $row['nama_kelas']; ?>" required>
                                                                    </div>
                                                                    <div class="form-group mt-2">
                                                                        <label>Kuota Siswa</label>
                                                                        <input type="number" name="kuota"
                                                                            class="form-control"
                                                                            value="<?= $row['kuota']; ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" name="edit_kelas"
                                                                        class="btn btn-primary">Perbarui</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
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
                        2025, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="">Rahayu</a>
                    </div>

                </div>
            </footer>
        </div>
        <!-- End Custom template -->
    </div>
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: X IPA 1"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kuota Siswa</label>
                            <input type="number" name="kuota" class="form-control" placeholder="Contoh: 36" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_kelas" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalViewSiswa" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Daftar Siswa - <span id="namaKelas"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="loading">Loading...</div>

                    <table class="table table-striped table-hover" id="tableSiswa">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="resultSiswa"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="../../../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../../../assets/js/core/popper.min.js"></script>
    <script src="../../../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="../../../assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="../../../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="../../../assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="../../../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="../../../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="../../../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="../../../assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Google Maps Plugin -->
    <script src="../../../assets/js/plugin/gmaps/gmaps.js"></script>

    <!-- Sweet Alert -->
    <script src="../../../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Datatables -->
    <script src="../../../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="../../../assets/js/kaiadmin.min.js"></script>

    <script>
    $(document).ready(function() {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
            pageLength: 5,
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on("change", function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column
                                    .search(val ? "^" + val + "$" : "", true, false)
                                    .draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                select.append(
                                    '<option value="' + d + '">' + d + "</option>"
                                );
                            });
                    });
            },
        });

        // Add Row
        $("#add-row").DataTable({
            pageLength: 5,
        });

        var action =
            '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function() {
            $("#add-row")
                .dataTable()
                .fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action,
                ]);
            $("#addRowModal").modal("hide");
        });
    });
    </script>

    <script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-siswa');
        if (!btn) return;

        let id = btn.getAttribute('data-id');
        let nama = btn.getAttribute('data-nama');

        document.getElementById('namaKelas').innerText = nama;
        document.getElementById('loading').style.display = 'block';
        document.getElementById('loading').innerText = 'Loading...';
        document.getElementById('tableSiswa').classList.add('d-none');
        document.getElementById('resultSiswa').innerHTML = '';

        // Buka modal dulu
        const modal = new bootstrap.Modal(document.getElementById('modalViewSiswa'));
        modal.show();

        // Gunakan path relatif
        fetch('get_siswa.php?id_kelas=' + id)
            .then(res => {
                if (!res.ok) throw new Error('HTTP error: ' + res.status);
                return res.text();
            })
            .then(text => {
                console.log('RAW:', text);
                return JSON.parse(text);
            })
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html = `<tr><td colspan="5" class="text-center">Belum ada siswa</td></tr>`;
                } else {
                    data.forEach((item, index) => {
                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nama}</td>
                            <td>${item.email}</td>
                            <td>${item.nilai}</td>
                            <td>${item.status}</td>
                        </tr>`;
                    });
                }
                document.getElementById('resultSiswa').innerHTML = html;
                document.getElementById('loading').style.display = 'none';
                document.getElementById('tableSiswa').classList.remove('d-none');
            })
            .catch(err => {
                console.error('ERROR:', err);
                document.getElementById('loading').innerHTML =
                    '<span class="text-danger">Gagal load data: ' + err.message + '</span>';
            });
    });
    </script>
</body>

</html>