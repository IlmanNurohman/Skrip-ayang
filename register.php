<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Siswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, #4b79a1, #283e51);
        font-family: 'Segoe UI', sans-serif;
    }

    .register-container {
        width: 400px;
        padding: 35px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.20);
        animation: fadeIn 0.6s ease-in-out;
    }

    .register-container h3 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 25px;
        color: #283e51;
    }

    .form-control {
        height: 45px;
        border-radius: 10px;
    }

    .btn-primary-custom {
        background: #283e51;
        color: white;
        border: none;
        height: 45px;
        font-size: 16px;
        border-radius: 10px;
    }

    .btn-primary-custom:hover {
        background: #1f2f3c;
    }

    p a {
        text-decoration: none;
        color: #283e51;
        font-weight: 600;
    }

    p a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>

<body>

    <div class="register-container">
        <h3>Registrasi Siswa</h3>

        <form action="backend/proses_register.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="register" class="btn-primary-custom w-100 mt-2">
                Daftar
            </button>
        </form>

        <p class="mt-3 text-center text-dark">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>