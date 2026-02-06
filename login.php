<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login PPDB</title>

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        margin: 0;
        height: 100vh;
        font-family: 'Inter', sans-serif;
        /* GANTI FONT */
        background: linear-gradient(135deg, #2b5876, #4e4376);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-wrapper {
        text-align: center;
    }

    .login-card {
        width: 370px;
        padding: 40px 28px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(14px);
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
        animation: fadeUp 0.7s ease-in-out;
    }

    @keyframes fadeUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .login-title {
        color: #fff;
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }

    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff, #dcdcdc);
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 20px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
    }

    .icon-circle i {
        font-size: 38px;
        color: #4e4376;
    }

    .form-label {
        color: #ffffffd9;
        font-weight: 500;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.3);
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 10px;
        font-size: 15px;
    }

    .form-control::placeholder {
        color: #eee;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.45);
        color: #fff;
        box-shadow: 0 0 0 1px #ffffff88;
    }

    .btn-login {
        margin-top: 10px;
        background: linear-gradient(135deg, #ffdd00, #ff9f00);
        border: none;
        padding: 10px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        color: #4e3500;
        transition: 0.3s;
        box-shadow: 0 6px 20px rgba(255, 196, 0, 0.5);
    }

    .btn-login:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 25px rgba(255, 196, 0, 0.7);
    }

    .register-link {
        display: block;
        margin-top: 12px;
        color: #fff;
        opacity: 0.85;
        font-size: 14px;
    }

    .register-link:hover {
        opacity: 1;
        text-decoration: underline;
    }
    </style>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <div class="login-wrapper">

        <div class="login-card">

            <div class="icon-circle">
                <img src="assets/img/ict.png" alt="User Icon" style="width: 55px; height: 55px; object-fit: cover;">
            </div>


            <div class="login-title">Login PPDB</div>

            <form action="backend/proses_login.php" method="POST">

                <div class="mb-3 text-start">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>

                <button type="submit" name="login" class="btn btn-login w-100">Masuk</button>

            </form>

            <a href="register.php" class="register-link">Belum punya akun? Daftar di sini</a>

        </div>

    </div>

</body>

</html>