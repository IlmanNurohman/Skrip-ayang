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

$query = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id'");
$user  = mysqli_fetch_assoc($query);
// Ambil data soal dari database
$query = mysqli_query($conn, "SELECT * FROM soal ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Bank Soal</title>
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
                            <a href="../soal/index.php">
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
                            <a href="../kelas/index.php">
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
                        <a href="../index.html" class="logo">
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
                        <h3 class="fw-bold mb-3">Seleksi</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="#">
                                    <i class="fas fa-th-list"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Seleksi</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Daftar Bank Soal</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <a href="tambah.php" class="btn btn-primary mb-3">
                                <i class="fa fa-plus"></i> Tambah Soal
                            </a>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Daftar Bank Soal</h4>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pertanyaan</th>
                                                    <th>Kunci</th>
                                                    <th style="width: 10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                        $no = 1;
                        while ($s = mysqli_fetch_assoc($query)) : 
                        ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= (strlen($s['pertanyaan']) > 100) ? substr($s['pertanyaan'], 0, 100) . "..." : $s['pertanyaan']; ?>
                                                    </td>
                                                    <td><span
                                                            class="badge badge-success"><?= $s['jawaban_benar'] ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="form-button-action">
                                                            <button type="button" class="btn btn-link btn-info px-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalView<?= $s['id'] ?>">
                                                                <i class="fa fa-eye"></i>
                                                            </button>

                                                            <button type="button" class="btn btn-link btn-primary px-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEdit<?= $s['id'] ?>">
                                                                <i class="fa fa-edit"></i>
                                                            </button>

                                                            <a href="../../../backend/simpan_soal.php?aksi=hapus&id=<?= $s['id'] ?>"
                                                                class="btn btn-link btn-danger px-2"
                                                                onclick="return confirm('Hapus soal ini?')">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="modalView<?= $s['id'] ?>" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Detail Soal</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Pertanyaan:</strong></p>
                                                                <p><?= nl2br($s['pertanyaan']) ?></p>
                                                                <hr>
                                                                <ul class="list-group">
                                                                    <li
                                                                        class="list-group-item <?= ($s['jawaban_benar'] == 'A') ? 'list-group-item-success' : '' ?>">
                                                                        <strong>A:</strong> <?= $s['opsi_a'] ?>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item <?= ($s['jawaban_benar'] == 'B') ? 'list-group-item-success' : '' ?>">
                                                                        <strong>B:</strong> <?= $s['opsi_b'] ?>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item <?= ($s['jawaban_benar'] == 'C') ? 'list-group-item-success' : '' ?>">
                                                                        <strong>C:</strong> <?= $s['opsi_c'] ?>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item <?= ($s['jawaban_benar'] == 'D') ? 'list-group-item-success' : '' ?>">
                                                                        <strong>D:</strong> <?= $s['opsi_d'] ?>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="modalEdit<?= $s['id'] ?>" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <form action="../../../backend/simpan_soal.php?aksi=update"
                                                                method="POST">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit Soal</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id"
                                                                        value="<?= $s['id'] ?>">
                                                                    <div class="form-group">
                                                                        <label>Pertanyaan</label>
                                                                        <textarea name="pertanyaan" class="form-control"
                                                                            rows="4"
                                                                            required><?= $s['pertanyaan'] ?></textarea>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group"><label>Opsi
                                                                                    A</label><input type="text"
                                                                                    name="opsi_a" class="form-control"
                                                                                    value="<?= $s['opsi_a'] ?>"
                                                                                    required></div>
                                                                            <div class="form-group"><label>Opsi
                                                                                    B</label><input type="text"
                                                                                    name="opsi_b" class="form-control"
                                                                                    value="<?= $s['opsi_b'] ?>"
                                                                                    required></div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group"><label>Opsi
                                                                                    C</label><input type="text"
                                                                                    name="opsi_c" class="form-control"
                                                                                    value="<?= $s['opsi_c'] ?>"
                                                                                    required></div>
                                                                            <div class="form-group"><label>Opsi
                                                                                    D</label><input type="text"
                                                                                    name="opsi_d" class="form-control"
                                                                                    value="<?= $s['opsi_d'] ?>"
                                                                                    required></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Jawaban Benar</label>
                                                                        <select name="jawaban_benar"
                                                                            class="form-select">
                                                                            <option value="A"
                                                                                <?= ($s['jawaban_benar'] == 'A') ? 'selected' : '' ?>>
                                                                                A</option>
                                                                            <option value="B"
                                                                                <?= ($s['jawaban_benar'] == 'B') ? 'selected' : '' ?>>
                                                                                B</option>
                                                                            <option value="C"
                                                                                <?= ($s['jawaban_benar'] == 'C') ? 'selected' : '' ?>>
                                                                                C</option>
                                                                            <option value="D"
                                                                                <?= ($s['jawaban_benar'] == 'D') ? 'selected' : '' ?>>
                                                                                D</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Simpan
                                                                        Perubahan</button>
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
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="http://www.themekita.com">
                                    ThemeKita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Help </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Licenses </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright">
                        2024, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="http://www.themekita.com">ThemeKita</a>
                    </div>
                    <div>
                        Distributed by
                        <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
                    </div>
                </div>
            </footer>
        </div>
        <!-- End Custom template -->
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

</body>

</html>