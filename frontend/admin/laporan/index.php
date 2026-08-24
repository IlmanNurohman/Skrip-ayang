<?php
session_start();
include '../../../backend/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['user_id'] ?? null;
if (!$id) {
    die('User belum login');
}

// --- Enkripsi/Dekripsi (sama seperti di dashboard) ---
define('SECRET_KEY', 'e7b434689dac661d0c8fb8d192a36fec76649fc82c3f83e80d17c38d9c3d7320');
define('SECRET_IV', '2dee9400f5a55a4cbce6e5ed27f615e2');

function decryptData($string)
{
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', SECRET_KEY);
    $iv  = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
}

// ============================================================
// FILTER (tanggal awal - tanggal akhir - status)
// ============================================================
$tanggal_awal  = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$status_filter = $_GET['status'] ?? '';

$where = [];

if (!empty($tanggal_awal)) {
    $ta = mysqli_real_escape_string($conn, $tanggal_awal);
    $where[] = "DATE(p.created_at) >= '$ta'";
}
if (!empty($tanggal_akhir)) {
    $tk = mysqli_real_escape_string($conn, $tanggal_akhir);
    $where[] = "DATE(p.created_at) <= '$tk'";
}
if (!empty($status_filter)) {
    $sf = mysqli_real_escape_string($conn, $status_filter);
    $where[] = "n.status = '$sf'";
}

$where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

// ============================================================
// RINGKASAN (mengikuti filter tanggal yang sama seperti dashboard)
// ============================================================
$where_pendaftaran = [];

if (!empty($tanggal_awal)) {
    $ta = mysqli_real_escape_string($conn, $tanggal_awal);
    $where_pendaftaran[] = "DATE(p.created_at) >= '$ta'";
}

if (!empty($tanggal_akhir)) {
    $tk = mysqli_real_escape_string($conn, $tanggal_akhir);
    $where_pendaftaran[] = "DATE(p.created_at) <= '$tk'";
}

$where_pendaftaran_sql = count($where_pendaftaran) > 0
    ? "WHERE " . implode(" AND ", $where_pendaftaran)
    : "";

// Total pendaftaran
$q_total_pendaftaran = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM pendaftaran p
    $where_pendaftaran_sql
");

$total_pendaftaran = mysqli_fetch_assoc($q_total_pendaftaran)['total'];

// Total diterima
$q_total_diterima = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM nilai_seleksi n 
    JOIN pendaftaran p ON n.siswa_id = p.id_user 
    WHERE n.status = 'Lulus'
    " . (count($where_pendaftaran) > 0
        ? " AND " . implode(" AND ", $where_pendaftaran)
        : "")
);

$total_diterima = mysqli_fetch_assoc($q_total_diterima)['total'];

// Total sudah tes
$q_total_tes = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM nilai_seleksi n 
    JOIN pendaftaran p ON n.siswa_id = p.id_user 
    " . (count($where_pendaftaran) > 0
        ? "WHERE " . implode(" AND ", $where_pendaftaran)
        : "")
);

$total_sudah_tes = mysqli_fetch_assoc($q_total_tes)['total'];

$total_lolos = $total_diterima;

// ============================================================
// DATA DETAIL LAPORAN
// ============================================================
$query_detail = "
    SELECT 
        p.nama_lengkap,
        p.jenis_kelamin,
        p.created_at,
        u.username,
        k.nama_kelas,
        n.nilai,
        n.status
    FROM pendaftaran p
    JOIN users u ON p.id_user = u.id
    LEFT JOIN nilai_seleksi n ON n.siswa_id = p.id_user
    LEFT JOIN kelas k ON n.id_kelas = k.id
    $where_sql
    ORDER BY p.created_at DESC
";

$result_detail = mysqli_query($conn, $query_detail);
if (!$result_detail) {
    die("Query Error: " . mysqli_error($conn));
}

$data_laporan = [];
while ($row = mysqli_fetch_assoc($result_detail)) {
    $gender_decoded = decryptData($row['jenis_kelamin']);
    $row['jenis_kelamin_text'] = $gender_decoded ? ucwords(strtolower(trim($gender_decoded))) : '-';

    $nama_decoded = decryptData($row['nama_lengkap']);
    $row['nama_lengkap_text'] = ($nama_decoded !== false && trim($nama_decoded) !== '')
        ? $nama_decoded
        : ($row['nama_lengkap'] ?: $row['username']);

    $data_laporan[] = $row;
}

// Query untuk dropdown foto/username admin (header)
$query = mysqli_query($conn, "SELECT username, foto FROM users WHERE id='$id'");
$user  = mysqli_fetch_assoc($query);

// Query string untuk dipakai tombol export (agar filter ikut terbawa)
$export_query_string = http_build_query([
    'tanggal_awal'  => $tanggal_awal,
    'tanggal_akhir' => $tanggal_akhir,
    'status'        => $status_filter,
]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Laporan - Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

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

    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../../assets/css/kaiadmin.min.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
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
                        <!-- MENU LAPORAN (BARU) -->
                        <li class="nav-item active">
                            <a href="index.php">
                                <i class="fas fa-chart-bar"></i>
                                <p>Laporan</p>
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
                    <div class="logo-header" data-background-color="dark">
                        <a href="../index.html" class="logo">
                            <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
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
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
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
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="../../../logout.php">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Laporan Pendaftaran &amp; Seleksi</h3>
                            <h6 class="op-7 mb-2">Rekap data pendaftaran, tes, dan hasil seleksi siswa</h6>
                        </div>
                    </div>

                    <!-- FILTER -->
                    <div class="card card-round">
                        <div class="card-body">
                            <form method="GET" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" class="form-control"
                                        value="<?= htmlspecialchars($tanggal_awal) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control"
                                        value="<?= htmlspecialchars($tanggal_akhir) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status Seleksi</label>
                                    <select name="status" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="Lulus" <?= $status_filter == 'Lulus' ? 'selected' : '' ?>>Lulus
                                        </option>
                                        <option value="Tidak Lulus"
                                            <?= $status_filter == 'Tidak Lulus' ? 'selected' : '' ?>>Tidak Lulus
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-filter"></i> Terapkan
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RINGKASAN -->
                    <div class="row mt-2">
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Pendaftaran</p>
                                                <h4 class="card-title"><?= number_format($total_pendaftaran) ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                                <i class="fas fa-file-signature"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Sudah Tes</p>
                                                <h4 class="card-title"><?= number_format($total_sudah_tes) ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Diterima</p>
                                                <h4 class="card-title"><?= number_format($total_diterima) ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                                <i class="far fa-check-circle"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Lolos Tes</p>
                                                <h4 class="card-title"><?= number_format($total_lolos) ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABEL DETAIL + EXPORT -->
                    <div class="card card-round mt-2">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Detail Data Laporan</div>
                                <div class="card-tools">
                                    <a href="export_excel.php?<?= $export_query_string ?>"
                                        class="btn btn-label-success btn-round btn-sm me-2" target="_blank">
                                        <span class="btn-label"><i class="fa fa-file-excel"></i></span>
                                        Export Excel
                                    </a>
                                    <a href="cetak_pdf.php?<?= $export_query_string ?>"
                                        class="btn btn-label-danger btn-round btn-sm" target="_blank">
                                        <span class="btn-label"><i class="fa fa-file-pdf"></i></span>
                                        Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Siswa</th>
                                            <th scope="col">Jenis Kelamin</th>
                                            <th scope="col">Kelas</th>
                                            <th scope="col">Tanggal Daftar</th>
                                            <th scope="col" class="text-end">Nilai</th>
                                            <th scope="col" class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($data_laporan) > 0): ?>
                                        <?php $no = 1;
                                            foreach ($data_laporan as $row):
                                                $badge_class = 'badge-secondary';
                                                $status_text = '-';
                                                if (!empty($row['status'])) {
                                                    $badge_class = ($row['status'] == 'Lulus') ? 'badge-success' : 'badge-danger';
                                                    $status_text = $row['status'];
                                                }
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama_lengkap_text']) ?></td>
                                            <td><?= htmlspecialchars($row['jenis_kelamin_text']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                                            <td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                            <td class="text-end fw-bold"><?= $row['nilai'] ?? '-' ?></td>
                                            <td class="text-end">
                                                <span class="badge <?= $badge_class ?>"><?= $status_text ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">Tidak ada data untuk filter ini.
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-center">
                    <div class="copyright">2026, by Rahayu</div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../../../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../../../assets/js/core/popper.min.js"></script>
    <script src="../../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="../../../assets/js/kaiadmin.min.js"></script>
</body>

</html>