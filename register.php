<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register PPDB</title>

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
        --text-head: #f0f6fc;
        --text-body: #8b949e;
        --text-label: #c9d1d9;
        --radius-card: 20px;
        --radius-input: 10px;
    }

    body {
        min-height: 100vh;
        font-family: 'DM Sans', sans-serif;
        background: var(--bg-deep);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        overflow-y: auto;
        overflow-x: hidden;
        position: relative;
        padding: 40px 16px;
    }

    /* ── Ambient blobs ── */
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
    .register-card {
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

    /* ── Badge ── */
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

    .divider {
        height: 1px;
        background: var(--border);
        margin-bottom: 28px;
    }

    /* ── Heading ── */
    .register-heading {
        margin-bottom: 6px;
        animation: cardIn 0.65s 0.15s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .register-heading h1 {
        font-family: 'Syne', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--text-head);
        letter-spacing: -0.3px;
    }

    .register-heading p {
        font-size: 13.5px;
        color: var(--text-body);
        margin-top: 4px;
    }

    /* ── Step indicator ── */
    .step-bar {
        display: flex;
        align-items: center;
        gap: 0;
        margin: 22px 0 26px;
        animation: cardIn 0.65s 0.18s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-body);
    }

    .step.active {
        color: #58a6ff;
    }

    .step.done {
        color: #3fb950;
    }

    .step-dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid var(--border);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 11px;
        font-weight: 700;
        transition: all 0.3s;
    }

    .step.active .step-dot {
        border-color: #58a6ff;
        color: #58a6ff;
        background: rgba(88, 166, 255, 0.1);
    }

    .step.done .step-dot {
        border-color: #3fb950;
        color: #3fb950;
        background: rgba(63, 185, 80, 0.1);
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: var(--border);
        margin: 0 8px;
        border-radius: 2px;
        transition: background 0.3s;
    }

    .step-line.active {
        background: #3fb950;
    }

    /* ── Form ── */
    form {
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

    /* strength meter */
    .strength-wrap {
        margin-top: 8px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .strength-bars {
        display: flex;
        gap: 4px;
    }

    .sbar {
        flex: 1;
        height: 3px;
        border-radius: 3px;
        background: var(--border);
        transition: background 0.3s;
    }

    .strength-label {
        font-size: 11.5px;
        color: var(--text-body);
        min-height: 14px;
    }

    /* password rules */
    .pw-rules {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 6px;
    }

    .rule {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-body);
        transition: color 0.2s;
    }

    .rule i {
        font-size: 10px;
        width: 12px;
        text-align: center;
    }

    .rule.ok {
        color: #3fb950;
    }

    .rule.ok i {
        color: #3fb950;
    }

    /* confirm match hint */
    .match-hint {
        font-size: 12px;
        margin-top: 5px;
        min-height: 14px;
        transition: color 0.2s;
    }

    .match-hint.ok {
        color: #3fb950;
    }

    .match-hint.err {
        color: #f85149;
    }

    /* ── Button ── */
    .btn-register {
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

    .btn-register:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(37, 99, 235, 0.55);
    }

    .btn-register:active {
        transform: translateY(0);
        opacity: 1;
    }

    .btn-register:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ── Footer ── */
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

    @media (max-width: 460px) {
        .register-card {
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

    <div class="register-card">

        <div class="school-badge">
            <div class="logo-wrap">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="school-info">
                <div class="school-name">Portal PPDB</div>
                <div class="school-sub">Penerimaan Peserta Didik Baru</div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="register-heading">
            <h1>Buat akun baru ✨</h1>
            <p>Isi data di bawah untuk mendaftar sebagai siswa</p>
        </div>

        <!-- Step indicator -->
        <div class="step-bar">
            <div class="step active" id="step1">
                <div class="step-dot">1</div>
                <span>Akun</span>
            </div>
            <div class="step-line" id="line1"></div>
            <div class="step" id="step2">
                <div class="step-dot">2</div>
                <span>Password</span>
            </div>
            <div class="step-line" id="line2"></div>
            <div class="step" id="step3">
                <div class="step-dot">3</div>
                <span>Konfirmasi</span>
            </div>
        </div>

        <form action="backend/proses_register.php" method="POST" id="registerForm">

            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Buat username unik"
                        autocomplete="username" required>
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Buat password kuat"
                        autocomplete="new-password" required>
                    <button type="button" class="toggle-pw" id="togglePw1" aria-label="Tampilkan password">
                        <i class="fa-regular fa-eye" id="eyeIcon1"></i>
                    </button>
                </div>
                <!-- Strength meter -->
                <div class="strength-wrap">
                    <div class="strength-bars">
                        <div class="sbar" id="s1"></div>
                        <div class="sbar" id="s2"></div>
                        <div class="sbar" id="s3"></div>
                        <div class="sbar" id="s4"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel"></div>
                </div>
                <!-- Rules -->
                <div class="pw-rules">
                    <div class="rule" id="ruleLen"><i class="fa-solid fa-circle"></i> Minimal 8 karakter</div>
                    <div class="rule" id="ruleUpper"><i class="fa-solid fa-circle"></i> Mengandung huruf kapital</div>
                    <div class="rule" id="ruleNum"><i class="fa-solid fa-circle"></i> Mengandung angka</div>
                </div>
            </div>

            <div class="field">
                <label for="confirm_password">Konfirmasi Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-shield-halved input-icon"></i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password"
                        autocomplete="new-password" required>
                    <button type="button" class="toggle-pw" id="togglePw2" aria-label="Tampilkan password">
                        <i class="fa-regular fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>
                <div class="match-hint" id="matchHint"></div>
            </div>

            <button type="submit" name="register" class="btn-register" id="submitBtn" disabled>
                Daftar Sekarang
            </button>

        </form>

        <p class="footer-note">
            Sudah punya akun?
            <a href="login.php">Login di sini</a>
        </p>

    </div>

    <script>
    // ── Toggle show/hide password ──
    function bindToggle(btnId, inputId, iconId) {
        document.getElementById(btnId).addEventListener('click', () => {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    }
    bindToggle('togglePw1', 'password', 'eyeIcon1');
    bindToggle('togglePw2', 'confirm_password', 'eyeIcon2');

    // ── Strength meter ──
    const pwInput = document.getElementById('password');
    const confInput = document.getElementById('confirm_password');
    const bars = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document
        .getElementById('s4')
    ];
    const strengthLabel = document.getElementById('strengthLabel');
    const ruleLen = document.getElementById('ruleLen');
    const ruleUpper = document.getElementById('ruleUpper');
    const ruleNum = document.getElementById('ruleNum');
    const matchHint = document.getElementById('matchHint');
    const submitBtn = document.getElementById('submitBtn');

    const levels = [{
            color: '#f85149',
            label: 'Sangat Lemah'
        },
        {
            color: '#e3b341',
            label: 'Lemah'
        },
        {
            color: '#f59e0b',
            label: 'Cukup'
        },
        {
            color: '#3fb950',
            label: 'Kuat'
        },
    ];

    function getStrength(pw) {
        let score = 0;
        if (pw.length >= 8) score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }

    function updateStep() {
        const pw = pwInput.value;
        const conf = confInput.value;
        const str = getStrength(pw);
        const ok1 = pw.length >= 8;
        const ok2 = /[A-Z]/.test(pw);
        const ok3 = /[0-9]/.test(pw);
        const match = pw && conf && pw === conf;

        // step indicator
        const s1 = document.getElementById('step1');
        const s2 = document.getElementById('step2');
        const s3 = document.getElementById('step3');
        const l1 = document.getElementById('line1');
        const l2 = document.getElementById('line2');

        s1.className = 'step ' + (pw.length > 0 ? 'done' : 'active');
        s2.className = 'step ' + (pw.length > 0 && conf.length === 0 ? 'active' : pw.length > 0 ? 'done' : '');
        s3.className = 'step ' + (match ? 'done' : conf.length > 0 ? 'active' : '');
        l1.className = 'step-line ' + (pw.length > 0 ? 'active' : '');
        l2.className = 'step-line ' + (match ? 'active' : '');

        // bars
        bars.forEach((b, i) => {
            b.style.background = i < str ? levels[str - 1].color : '';
        });
        strengthLabel.textContent = pw.length > 0 ? levels[str - 1]?.label ?? '' : '';
        strengthLabel.style.color = pw.length > 0 ? levels[str - 1]?.color ?? '' : '';

        // rules
        ruleLen.className = 'rule ' + (ok1 ? 'ok' : '');
        ruleUpper.className = 'rule ' + (ok2 ? 'ok' : '');
        ruleNum.className = 'rule ' + (ok3 ? 'ok' : '');

        // match
        if (!conf) {
            matchHint.textContent = '';
            matchHint.className = 'match-hint';
        } else if (match) {
            matchHint.textContent = '✓ Password cocok';
            matchHint.className = 'match-hint ok';
        } else {
            matchHint.textContent = '✗ Password tidak cocok';
            matchHint.className = 'match-hint err';
        }

        // enable submit
        submitBtn.disabled = !(ok1 && ok2 && ok3 && match);
    }

    pwInput.addEventListener('input', updateStep);
    confInput.addEventListener('input', updateStep);

    // ── Prevent submit if mismatch (extra safety) ──
    document.getElementById('registerForm').addEventListener('submit', (e) => {
        if (pwInput.value !== confInput.value) {
            e.preventDefault();
            matchHint.textContent = '✗ Password tidak cocok';
            matchHint.className = 'match-hint err';
        }
    });
    </script>

</body>

</html>