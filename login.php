<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login PPDB</title>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --bg-deep: #0d1117;
        --bg-card: #161b22;
        --bg-input: #21262d;
        --border: #30363d;
        --border-focus: #58a6ff;
        --accent: #2563eb;
        --accent-2: #1d4ed8;
        --gold: #f59e0b;
        --gold-2: #d97706;
        --text-head: #f0f6fc;
        --text-body: #8b949e;
        --text-label: #c9d1d9;
        --error: #f85149;
        --radius-card: 20px;
        --radius-input: 10px;
    }

    body {
        min-height: 100vh;
        font-family: 'DM Sans', sans-serif;
        background: var(--bg-deep);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        position: relative;
    }

    /* ── Ambient background blobs ── */
    .blob {
        position: fixed;
        border-radius: 50%;
        filter: blur(90px);
        opacity: 0.18;
        pointer-events: none;
        animation: drift 12s ease-in-out infinite alternate;
    }

    .blob-1 {
        width: 480px;
        height: 480px;
        background: #2563eb;
        top: -120px;
        left: -100px;
        animation-delay: 0s;
    }

    .blob-2 {
        width: 360px;
        height: 360px;
        background: #7c3aed;
        bottom: -80px;
        right: -80px;
        animation-delay: -6s;
    }

    .blob-3 {
        width: 260px;
        height: 260px;
        background: #f59e0b;
        bottom: 20%;
        left: 10%;
        animation-delay: -3s;
        opacity: 0.09;
    }

    @keyframes drift {
        from {
            transform: translate(0, 0) scale(1);
        }

        to {
            transform: translate(30px, 20px) scale(1.06);
        }
    }

    /* ── Noise grain overlay ── */
    body::after {
        content: '';
        position: fixed;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
        opacity: 0.5;
    }

    /* ── Card ── */
    .login-card {
        position: relative;
        z-index: 1;
        width: 420px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-card);
        padding: 44px 40px 40px;
        box-shadow:
            0 0 0 1px rgba(255, 255, 255, 0.04),
            0 24px 60px rgba(0, 0, 0, 0.6),
            0 2px 8px rgba(0, 0, 0, 0.3);
        animation: cardIn 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes cardIn {
        from {
            opacity: 0;
            transform: translateY(28px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ── Top badge ── */
    .school-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 28px;
        animation: cardIn 0.65s 0.1s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .logo-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1e3a5f, #2563eb);
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
    }

    .logo-wrap img {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .logo-wrap i {
        font-size: 22px;
        color: #93c5fd;
    }

    .school-info {
        line-height: 1.3;
    }

    .school-name {
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-head);
        letter-spacing: 0.2px;
    }

    .school-sub {
        font-size: 12px;
        color: var(--text-body);
        margin-top: 1px;
    }

    /* ── Divider ── */
    .divider {
        height: 1px;
        background: var(--border);
        margin-bottom: 28px;
    }

    /* ── Heading ── */
    .login-heading {
        margin-bottom: 6px;
        animation: cardIn 0.65s 0.15s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .login-heading h1 {
        font-family: 'Syne', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--text-head);
        letter-spacing: -0.3px;
    }

    .login-heading p {
        font-size: 13.5px;
        color: var(--text-body);
        margin-top: 4px;
    }

    /* ── Form ── */
    form {
        margin-top: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        animation: cardIn 0.65s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-label);
        letter-spacing: 0.2px;
    }

    .input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        font-size: 14px;
        color: var(--text-body);
        pointer-events: none;
        transition: color 0.2s;
    }

    .input-wrap:focus-within .input-icon {
        color: var(--border-focus);
    }

    .toggle-pw {
        position: absolute;
        right: 14px;
        background: none;
        border: none;
        color: var(--text-body);
        cursor: pointer;
        font-size: 14px;
        padding: 0;
        transition: color 0.2s;
        line-height: 1;
    }

    .toggle-pw:hover {
        color: var(--text-label);
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: var(--radius-input);
        color: var(--text-head);
        font-family: 'DM Sans', sans-serif;
        font-size: 14.5px;
        padding: 11px 14px 11px 40px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        -webkit-appearance: none;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: var(--border-focus);
        background: #1c2230;
        box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.12);
    }

    input::placeholder {
        color: #484f58;
    }

    /* ── Submit button ── */
    .btn-login {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: var(--radius-input);
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        letter-spacing: 0.3px;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 18px rgba(37, 99, 235, 0.45);
        margin-top: 4px;
    }

    .btn-login:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(37, 99, 235, 0.55);
    }

    .btn-login:active {
        transform: translateY(0);
        opacity: 1;
    }

    /* ── Footer link ── */
    .footer-note {
        text-align: center;
        margin-top: 20px;
        font-size: 13.5px;
        color: var(--text-body);
        animation: cardIn 0.65s 0.25s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .footer-note a {
        color: #58a6ff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .footer-note a:hover {
        color: #93c5fd;
        text-decoration: underline;
    }

    /* ── Responsive ── */
    @media (max-width: 460px) {
        .login-card {
            width: 92vw;
            padding: 32px 24px 28px;
        }
    }
    </style>
</head>

<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="login-card">

        <div class="school-badge">
            <div class="logo-wrap">
                <!-- Ganti img src jika aset tersedia, fallback ke icon -->
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="school-info">
                <div class="school-name">Portal PPDB</div>
                <div class="school-sub">Penerimaan Peserta Didik Baru</div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="login-heading">
            <h1>Selamat datang di Portal PPDB 👋</h1>
            <p>Masuk untuk melanjutkan ke akun Anda dan melakukan pendaftaran PPDB</p>
        </div>

        <form action="backend/proses_login.php" method="POST">

            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Masukkan username"
                        autocomplete="username" required>
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password"
                        autocomplete="current-password" required>
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Tampilkan password">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn-login">
                Masuk
            </button>

        </form>

        <p class="footer-note">
            Belum punya akun?
            <a href="register.php">Daftar di sini</a> <br>
            Jika ada kendala, jangan ragu untuk
            <a
                href="https://wa.me/6282324279284?text=Halo%20Admin,%20saya%20ingin%20bertanya%20mengenai%20pendaftaran.">Hubungi
                admin.</a>

        </p>

    </div>

    <script>
    const togglePw = document.getElementById('togglePw');
    const pwInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePw.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type = isHidden ? 'text' : 'password';
        eyeIcon.className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    });
    </script>

</body>

</html>