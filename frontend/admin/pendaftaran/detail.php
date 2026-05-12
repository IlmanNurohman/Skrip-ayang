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
        // Ambil ID dari URL
        $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

        if (empty($id)) {
            header("Location: tampil.php");
            exit();
        }

        // Query ambil data detail
        $query = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id = '$id'");
        $data = mysqli_fetch_assoc($query);

        if (!$data) {
            die("Data tidak ditemukan.");
        }
        define('SECRET_KEY', 'e7b434689dac661d0c8fb8d192a36fec76649fc82c3f83e80d17c38d9c3d7320');
        define('SECRET_IV', '2dee9400f5a55a4cbce6e5ed27f615e2');

        function decryptData($string)
        {
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
          <title>Detail Penddaftaran </title>
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
                                  <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand"
                                      class="navbar-brand" />
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
                                      <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"
                                          role="button" aria-expanded="false" aria-haspopup="true">
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
                                                      <a class="col-6 col-md-4 p-0" href="#">
                                                          <div class="quick-actions-item">
                                                              <div class="avatar-item bg-danger rounded-circle">
                                                                  <i class="far fa-calendar-alt"></i>
                                                              </div>
                                                              <span class="text">Calendar</span>
                                                          </div>
                                                      </a>
                                                      <a class="col-6 col-md-4 p-0" href="#">
                                                          <div class="quick-actions-item">
                                                              <div class="avatar-item bg-warning rounded-circle">
                                                                  <i class="fas fa-map"></i>
                                                              </div>
                                                              <span class="text">Maps</span>
                                                          </div>
                                                      </a>
                                                      <a class="col-6 col-md-4 p-0" href="#">
                                                          <div class="quick-actions-item">
                                                              <div class="avatar-item bg-info rounded-circle">
                                                                  <i class="fas fa-file-excel"></i>
                                                              </div>
                                                              <span class="text">Reports</span>
                                                          </div>
                                                      </a>
                                                      <a class="col-6 col-md-4 p-0" href="#">
                                                          <div class="quick-actions-item">
                                                              <div class="avatar-item bg-success rounded-circle">
                                                                  <i class="fas fa-envelope"></i>
                                                              </div>
                                                              <span class="text">Emails</span>
                                                          </div>
                                                      </a>
                                                      <a class="col-6 col-md-4 p-0" href="#">
                                                          <div class="quick-actions-item">
                                                              <div class="avatar-item bg-primary rounded-circle">
                                                                  <i class="fas fa-file-invoice-dollar"></i>
                                                              </div>
                                                              <span class="text">Invoice</span>
                                                          </div>
                                                      </a>
                                                      <a class="col-6 col-md-4 p-0" href="#">
                                                          <div class="quick-actions-item">
                                                              <div class="avatar-item bg-secondary rounded-circle">
                                                                  <i class="fas fa-credit-card"></i>
                                                              </div>
                                                              <span class="text">Payments</span>
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
                                                        ../../../assets/img/user/<?= $user['foto']; ?>" alt="..."
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
                                      <a href="#">Pendaftaran</a>
                                  </li>
                                  <li class="separator">
                                      <i class="icon-arrow-right"></i>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#">Detail Pendaftaran Siswa</a>
                                  </li>
                              </ul>
                          </div>
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="card">
                                      <div class="card-header">
                                          <h4 class="card-title">Detail Pendaftaran Siswa</h4>
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
                                                                  <td><?= ucfirst(decryptData($data['jenis_kelamin'])) ?>
                                                                  </td>
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
                                                                  <th>Nik Ayah / Ibu</th>
                                                                  <td>
                                                                      <?= decryptData($data['nik_ayah']) ?> /
                                                                      <?= decryptData($data['nik_ibu']) ?>
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

                                                              <tr>
                                                                  <th>Alamat Orang Tua</th>
                                                                  <td>
                                                                      <?= decryptData($data['alamat_ortu']) ?>
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

                                                          <button type="button" class="btn btn-outline-info mb-2 w-100"
                                                              data-bs-toggle="modal" data-bs-target="#modalKTPOrtu">
                                                              <i class="fas fa-file-pdf"></i> Lihat KTP Orang Tua
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

                                                          <?php if (!empty($data['catatan_admin'])): ?>
                                                          <div class="mt-3 text-start">
                                                              <label class="fw-bold">Catatan Sebelumnya:</label>
                                                              <p class="text-muted italic">
                                                                  "<?= $data['catatan_admin'] ?>"
                                                              </p>
                                                          </div>
                                                          <?php endif; ?>
                                                      </div>
                                                  </div>
                                                  <?php if ($status == 'pending' || empty($status)) : ?>

                                                  <div class="card mb-4">
                                                      <div class="card-header bg-secondary">
                                                          <div class="card-title text-white">Form Validasi</div>
                                                      </div>
                                                      <div class="card-body">
                                                          <form action="../../../backend/validasi_pendaftaran.php"
                                                              method="POST">
                                                              <input type="hidden" name="id" value="<?= $data['id'] ?>">

                                                              <div class="form-group p-0 mb-3">
                                                                  <label class="fw-bold">Berikan Catatan:</label>
                                                                  <textarea name="catatan_admin" class="form-control"
                                                                      rows="3"
                                                                      placeholder="Alasan terima/tolak..."></textarea>
                                                              </div>

                                                              <div class="row g-2">
                                                                  <div class="col-6">
                                                                      <button type="submit" name="aksi" value="lulus"
                                                                          class="btn btn-success w-100 shadow-sm"
                                                                          onclick="return confirm('Terima pendaftaran ini?')">
                                                                          <i class="fas fa-check"></i> Terima
                                                                      </button>
                                                                  </div>
                                                                  <div class="col-6">
                                                                      <button type="submit" name="aksi"
                                                                          value="tidak lulus"
                                                                          class="btn btn-danger w-100 shadow-sm"
                                                                          onclick="return confirm('Tolak pendaftaran ini?')">
                                                                          <i class="fas fa-times"></i> Tolak
                                                                      </button>
                                                                  </div>
                                                                  <div class="col-12 mt-3">
                                                                      <a href="index.php"
                                                                          class="btn btn-secondary w-100">
                                                                          <i class="fas fa-arrow-left"></i> Kembali ke
                                                                          Daftar
                                                                      </a>
                                                                  </div>
                                                              </div>
                                                          </form>
                                                      </div>
                                                  </div>
                                                  <?php endif; ?>
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
                                          <h5 class="modal-title">Ijazah - <?= decryptData($data['nama_lengkap']) ?>
                                          </h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                              aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                          <iframe src="../../backend/<?= $data['foto_ijazah'] ?>" width="100%"
                                              height="500px"></iframe>



                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="modal fade" id="modalRaport" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Raport - <?= decryptData($data['nama_lengkap']) ?>
                                          </h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                              aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                          <iframe src="../../backend/<?= $data['foto_raport'] ?>" width="100%"
                                              height="500px"></iframe>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="modal fade" id="modalKK" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Kartu Keluarga -
                                              <?= decryptData($data['nama_lengkap']) ?>
                                          </h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                              aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                          <iframe src="../../backend/<?= $data['foto_kk'] ?>" width="100%"
                                              height="500px"></iframe>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="modal fade" id="modalKTPOrtu" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">KTP Orang Tua -
                                              <?= decryptData($data['nama_lengkap']) ?>
                                          </h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                                              aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                          <iframe src="../../backend/<?= $data['ktp_ortu'] ?>" width="100%"
                                              height="500px"></iframe>
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
                                      var val = $.fn.dataTable.util.escapeRegex(
                                          $(this)
                                          .val());

                                      column
                                          .search(val ? "^" + val + "$" : "",
                                              true, false)
                                          .draw();
                                  });

                              column
                                  .data()
                                  .unique()
                                  .sort()
                                  .each(function(d, j) {
                                      select.append(
                                          '<option value="' + d + '">' + d +
                                          "</option>"
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