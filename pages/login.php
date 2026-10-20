<?php
require_once __DIR__ . '/../includes/config_access.php';
require_once __DIR__ . '/../includes/sso_helper.php';

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'] ?? '';
    if ($role === 'admin') header('Location: dashboard_admin.php');
    elseif ($role === 'subadmin') header('Location: dashboard_subadmin_list.php');
    else header('Location: dashboard_user_list.php');
    exit();
}

if (isset($_GET['token'])) {
    sso_log_event($pdo, null, 'portal_unsigned_login_token_blocked', 'Blocked unsigned token auto-login attempt on Portal login page');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTA Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --portal-blue: #1877d2;
            --portal-blue-dark: #1664b2;
            --portal-bg: #f0f2f5;
            --portal-text: #1c1e21;
            --portal-muted: #606770;
            --portal-border: #dfe3e8;
            --portal-success: #0b133cc8;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Helvetica, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(24, 119, 210, 0.08), transparent 32%),
                linear-gradient(180deg, #f7f8fa 0%, var(--portal-bg) 100%);
            color: var(--portal-text);
        }
        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }
        .login-container {
            width: 100%;
            max-width: 1120px;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(360px, 420px);
            gap: 48px;
            align-items: center;
        }
        .brand-panel {
            padding: 12px 6px 12px 10px;
            position: relative;
        }
        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }
        .logo-circle {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--portal-blue), #2d8cff);
            color: white;
            box-shadow: 0 16px 34px rgba(24, 119, 210, 0.24);
            font-size: 28px;
        }
        .brand-mark span {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--portal-blue);
        }
        .brand-panel h1 {
            margin: 0 0 14px;
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: 0.95;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--portal-blue);
        }
        .brand-panel p {
            max-width: 560px;
            margin: 0;
            font-size: clamp(1.05rem, 1.8vw, 1.7rem);
            line-height: 1.35;
            color: #1f2937;
        }
        .hero-visual {
            position: relative;
            margin-top: 34px;
            min-height: 420px;
            border-radius: 32px;
            padding: 30px;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(24, 119, 210, 0.14), rgba(255, 255, 255, 0.9)),
                linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
            border: 1px solid rgba(24, 119, 210, 0.12);
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
        }
        .hero-visual::before {
            content: "";
            position: absolute;
            inset: -60px auto auto -80px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(24, 119, 210, 0.22), rgba(24, 119, 210, 0));
            pointer-events: none;
        }
        .hero-visual::after {
            content: "";
            position: absolute;
            right: -70px;
            bottom: -90px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(66, 183, 42, 0.18), rgba(66, 183, 42, 0));
            pointer-events: none;
        }
        .hero-grid {
            position: absolute;
            inset: 18px 18px auto auto;
            width: 260px;
            display: grid;
            grid-template-columns: repeat(3, 72px);
            gap: 14px 10px;
            opacity: 0.95;
        }
        .hex {
            width: 72px;
            height: 80px;
            clip-path: polygon(25% 6%, 75% 6%, 100% 50%, 75% 94%, 25% 94%, 0 50%);
            background: linear-gradient(145deg, rgba(24, 119, 210, 0.2), rgba(24, 119, 210, 0.06));
            border: 1px solid rgba(24, 119, 210, 0.14);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
        }
        .hex:nth-child(2),
        .hex:nth-child(5),
        .hex:nth-child(8) {
            transform: translateY(42px);
        }
        .hex.solid {
            background: linear-gradient(145deg, #1877d2, #57a7ff);
            color: white;
            border-color: transparent;
        }
        .hex.accent {
            background: linear-gradient(145deg, #ab0101, #c40f1e);
            color: white;
            border-color: transparent;
        }
        .hex img {
            max-width: 46px;
            max-height: 46px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(15, 23, 42, 0.12));
        }
        .hex i {
            font-size: 1.1rem;
        }
        .hero-image-card {
            position: relative;
            z-index: 1;
            width: min(100%, 520px);
            margin-top: 54px;
            border-radius: 28px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 20px 46px rgba(15, 23, 42, 0.18);
            border: 1px solid rgba(255,255,255,0.8);
        }
        .hero-image-card::after {
            content: "";
            position: absolute;
            inset: auto 0 0;
            height: 42%;
            background: linear-gradient(180deg, rgba(10, 25, 47, 0) 0%, rgba(10, 25, 47, 0.88) 100%);
            pointer-events: none;
        }
        .hero-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
        }
        .hero-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 1;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.92);
            color: #17406a;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }
        .hero-caption {
            position: absolute;
            left: 20px;
            right: 20px;
            bottom: 18px;
            z-index: 1;
            color: white;
        }
        .hero-caption strong {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 4px;
        }
        .hero-caption span {
            display: block;
            font-size: 0.92rem;
            line-height: 1.45;
            color: rgba(255,255,255,0.88);
        }
        .hero-mini-card {
            position: absolute;
            right: 26px;
            bottom: 24px;
            z-index: 2;
            width: 180px;
            padding: 14px 16px;
            border-radius: 22px;
            background: rgba(255,255,255,0.94);
            border: 1px solid rgba(24, 119, 210, 0.1);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.12);
        }
        .hero-mini-card strong {
            display: block;
            color: #0f2740;
            font-size: 1.4rem;
            line-height: 1;
        }
        .hero-mini-card span {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #5c6f83;
            line-height: 1.45;
        }
        .login-card {
            background: #fff;
            border: 1px solid var(--portal-border);
            border-radius: 18px;
            padding: 22px 22px 18px;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.12);
        }
        .login-card-head {
            text-align: center;
            margin-bottom: 18px;
        }
        .login-card-head h3 {
            margin: 0 0 6px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
        }
        .login-card-head p {
            margin: 0;
            color: var(--portal-muted);
            font-size: 0.95rem;
        }
        .form-label { 
            font-weight: 600; 
            color: #253245; 
            margin-bottom: 8px; 
            font-size: 13px; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }
        .form-label i { 
            color: var(--portal-blue); 
            width: 18px; 
        }
        .input-group-custom { 
            position: relative; 
            margin-bottom: 16px; 
        }
        .form-control-custom { 
            width: 100%; 
            padding: 14px 16px 14px 44px; 
            border: 1px solid #ccd0d5; 
            border-radius: 12px; 
            font-size: 15px; 
            background: #fff; 
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }
        .form-control-custom:focus { 
            border-color: var(--portal-blue); 
            box-shadow: 0 0 0 4px rgba(24, 119, 210, 0.12); 
            outline: none; 
        }
        .form-control-custom::placeholder {
            color: #8a94a6;
        }
        .input-icon { 
            position: absolute; 
            left: 14px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #98a2b3; 
        }
        .password-toggle { 
            position: absolute; 
            right: 14px; 
            top: 50%; 
            transform: translateY(-50%); 
            background: none; 
            border: none; 
            color: #9ca3af; 
            cursor: pointer; 
            z-index: 10;
        }
        .password-toggle:hover {
            color: var(--portal-blue);
        }
        .btn-login { 
            background: var(--portal-blue); 
            color: white; 
            border: none; 
            border-radius: 12px; 
            padding: 13px; 
            font-weight: 700; 
            font-size: 1rem; 
            width: 100%; 
            transition: background-color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease; 
            margin-top: 8px; 
            margin-bottom: 18px; 
        }
        .btn-login:hover { 
            background: var(--portal-blue-dark); 
            transform: translateY(-1px); 
            box-shadow: 0 10px 20px rgba(24, 119, 210, 0.2); 
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .register-link { 
            text-align: center; 
            margin-top: 18px; 
            padding-top: 18px; 
            border-top: 1px solid #dadde1; 
        }
        .register-link p { 
            color: var(--portal-muted); 
            margin-bottom: 5px; 
            font-size: 14px; 
        }
        .register-link a { 
            color: var(--portal-blue); 
            font-weight: 700; 
            text-decoration: none; 
        }
        .register-link a:hover { 
            text-decoration: underline; 
        }
        .security-notice { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            background: #f5faff; 
            padding: 10px 14px; 
            border-radius: 12px; 
            margin-bottom: 20px; 
            font-size: 13px; 
            color: #27415d; 
            border: 1px solid #dbe9f8;
        }
        .security-notice i { 
            color: var(--portal-blue); 
        }
        .footer-text { 
            text-align: center; 
            margin-top: 18px; 
            color: var(--portal-muted); 
            font-size: 12px; 
        }
        .attempts-counter {
            font-size: 12px;
            margin-top: 5px;
            color: #dc3545;
            display: none;
        }
        .attempts-counter i {
            margin-right: 5px;
        }
        .lock-timer {
            background: #fff7db;
            color: #856404;
            padding: 10px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 15px;
            display: none;
            border: 1px solid #ffeeba;
        }
        .lock-timer i {
            margin-right: 8px;
        }
        .modal-content { 
            border-radius:16px; 
            border:none; 
            box-shadow:0 20px 40px rgba(0,0,0,0.1); 
            padding:10px; 
        }
        .modal-header { 
            border-bottom:none; 
            padding-bottom:0; 
        }
        .modal-footer { 
            border-top:none; 
            padding-top:0; 
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }
        .modal-footer .btn {
            min-width: 140px;
            border-radius: 12px;
            font-weight: 700;
        }
        .modal-footer .btn-primary {
            background: linear-gradient(135deg, var(--portal-blue), var(--portal-blue-dark));
            border: none;
            color: #fff;
            box-shadow: 0 10px 20px rgba(24, 119, 210, 0.18);
        }
        .modal-footer .btn-primary:hover {
            background: linear-gradient(135deg, var(--portal-blue-dark), #114d8d);
        }
        .modal-title i { 
            color: var(--portal-blue); 
        }
        .support-note {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 12px;
            margin: 15px 0;
            border-left: 4px solid var(--portal-blue);
            font-size: 13px;
        }
        .support-note i {
            color: var(--portal-blue);
            margin-right: 8px;
        }
        .helper-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-top: 14px;
        }
        .helper-actions a {
            display: block;
            text-align: center;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
        }
        .helper-actions .btn-create {
            background: var(--portal-success);
            color: #fff;
        }
        .helper-actions .btn-create:hover {
            background: #4a0a0a;
        }
        .helper-actions .btn-forgot {
            color: var(--portal-blue);
            border: 1px solid #d9e2ec;
            background: #fff;
        }
        .helper-actions .btn-forgot:hover {
            background: #f7fbff;
        }
        @media (max-width: 960px) {
            .login-shell {
                padding: 24px 16px;
            }
            .login-container {
                grid-template-columns: 1fr;
                gap: 24px;
                max-width: 460px;
            }
            .brand-panel {
                text-align: center;
                padding: 0;
            }
            .brand-mark {
                justify-content: center;
            }
            .brand-panel p {
                margin-inline: auto;
                font-size: 1.05rem;
            }
            .hero-visual {
                min-height: 360px;
                padding: 22px;
                margin-top: 26px;
            }
            .hero-grid {
                right: 10px;
                width: 220px;
                grid-template-columns: repeat(3, 60px);
            }
            .hex {
                width: 60px;
                height: 68px;
            }
            .hex:nth-child(2),
            .hex:nth-child(5),
            .hex:nth-child(8) {
                transform: translateY(36px);
            }
            .hero-image-card {
                width: 100%;
                margin-top: 74px;
            }
            .hero-image {
                height: 260px;
            }
            .hero-mini-card {
                right: 16px;
                bottom: 16px;
            }
        }
        @media (max-width: 480px) {
            .login-card {
                padding: 18px 16px 14px;
                border-radius: 16px;
            }
            .brand-panel h1 {
                font-size: 2.3rem;
            }
            .logo-circle {
                width: 56px;
                height: 56px;
                font-size: 24px;
            }
            .hero-visual {
                min-height: 300px;
                padding: 18px;
                border-radius: 24px;
            }
            .hero-grid {
                inset: 12px 10px auto auto;
                width: 160px;
                grid-template-columns: repeat(2, 60px);
                gap: 8px;
            }
            .hex:nth-child(2),
            .hex:nth-child(5),
            .hex:nth-child(8) {
                transform: translateY(0);
            }
            .hero-image-card {
                margin-top: 84px;
                border-radius: 22px;
            }
            .hero-image {
                height: 220px;
            }
            .hero-mini-card {
                position: static;
                width: 100%;
                margin-top: 14px;
            }
        }
    </style>
</head>
<body>
<div class="login-shell">
    <div class="login-container">
        <section class="brand-panel">
            <div class="brand-mark">
                <div class="logo-circle"><i class="fas fa-shield-alt"></i></div>
                <span>ARTA PORTAL</span>
            </div>
            <!-- <h1>Connect to your government workspace.</h1> -->
            <!-- <p>Access your ARTA tools, system assignments, and account services from one secure sign-in experience.</p> -->

            <div class="hero-visual">
                <div class="hero-grid" aria-hidden="true">
                    <div class="hex solid"><i class="fas fa-shield-check"></i></div>
                    <div class="hex"><img src="../img/arta_website.png" alt=""></div>
                    <div class="hex accent"><i class="fas fa-lock"></i></div>
                    <div class="hex"><i class="fas fa-network-wired"></i></div>
                    <div class="hex solid"><img src="../img/arta_logo.png" alt=""></div>
                    <div class="hex"><i class="fas fa-id-card"></i></div>
                    <div class="hex accent"><i class="fas fa-user-shield"></i></div>
                    <div class="hex"><i class="fas fa-building-columns"></i></div>
                    <div class="hex solid"><i class="fas fa-key"></i></div>
                </div>

                <div class="hero-image-card">
                    <span class="hero-badge">Secure Access</span>
                    <img src="../img/photos/arta_logo.png" alt="Portal visual" class="hero-image">
                    <div class="hero-caption">
                        <strong>One portal for verified access</strong>
                        <span>Log in to manage user accounts, assignments, and ARTA-connected systems in one place.</span>
                    </div>
                </div>

                <div class="hero-mini-card">
                    <strong>24/7</strong>
                    <span>Protected login experience with account recovery and role-based access controls.</span>
                </div>
            </div>
        </section>

        <section class="login-card">
            <div class="login-card-head">
                <h3>Log in to ARTA Portal</h3>
                <p>Use your registered ARTA email and password.</p>
            </div>

            <div class="lock-timer" id="lockTimer">
                <i class="fas fa-clock"></i>
                <span id="lockMessage"></span>
            </div>

            <div class="security-notice">
                <i class="fas fa-lock"></i>
                <span>Your connection is secure. All data is encrypted.</span>
            </div>

            <form id="loginForm">
                <div class="input-group-custom">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="position-relative">
                        <input type="email" name="email" id="email" class="form-control-custom" 
                               placeholder="agency.user@arta.gov.ph" required autocomplete="email">
                        <div class="input-icon"><i class="fas fa-user"></i></div>
                    </div>
                </div>
                <div class="input-group-custom">
                    <label class="form-label"><i class="fas fa-key"></i> Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="passwordInput" class="form-control-custom" 
                               placeholder="Enter your password" required autocomplete="current-password">
                        <div class="input-icon"><i class="fas fa-lock"></i></div>
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="attempts-counter" id="attemptsCounter">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="attemptsMessage"></span>
                    </div>
                </div>

                <div class="support-note" id="supportNote" style="display: none;">
                    <i class="fas fa-headset"></i>
                    <span>For assistance, please contact ICTD Support at support@arta.gov.ph or call (02) 1234-5678</span>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <div class="helper-actions">
                <a href="#" class="btn-forgot" id="forgotPasswordLink" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot password?</a>
                <a href="registration.php" class="btn-create">Create New Account</a>
            </div>

            <div class="register-link">
                <p>Authorized access for ARTA personnel and approved portal users.</p>
            </div>

            <div class="footer-text">
                <p>© 2024 Anti-Red Tape Authority</p>
            </div>
        </section>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-key me-2"></i>Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Enter your registered email. If the account exists, a password reset link will be sent to your inbox.</p>
                <div class="input-group-custom mb-0">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="position-relative">
                        <input type="email" id="resetEmail" class="form-control-custom" 
                               placeholder="agency.user@arta.gov.ph" required>
                        <div class="input-icon"><i class="fas fa-user"></i></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="sendResetLinkBtn" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Password Toggle
    const pwInput = document.getElementById('passwordInput');
    const pwToggle = document.getElementById('passwordToggle');
    if (pwToggle && pwInput) {
        pwToggle.addEventListener('click', () => {
            pwInput.type = pwInput.type === 'password' ? 'text' : 'password';
            pwToggle.innerHTML = pwInput.type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }

    // Login Form
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('passwordInput').value;
        const btn = document.getElementById('loginBtn');
        const attemptsCounter = document.getElementById('attemptsCounter');
        const attemptsMessage = document.getElementById('attemptsMessage');
        const lockTimer = document.getElementById('lockTimer');
        const lockMessage = document.getElementById('lockMessage');
        const supportNote = document.getElementById('supportNote');
        
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...';
        btn.disabled = true;

        try {
            const res = await fetch("../api/auth/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });
            
            const data = await res.json();
            
            if (res.status === 200 && data.status === "success") {
                // Reset UI
                attemptsCounter.style.display = 'none';
                lockTimer.style.display = 'none';
                supportNote.style.display = 'none';
                
                // Redirect based on role
                let redirect = "dashboard_user_list.php";
                if (data.user?.role) {
                    const role = data.user.role.toLowerCase();
                    if (role === 'admin') redirect = "dashboard_admin.php";
                    else if (role === 'subadmin') redirect = "dashboard_subadmin_list.php";
                }
                
                Swal.fire({ 
                    title: "Welcome!", 
                    text: `Logged in as ${data.user.firstname || data.user.email}`, 
                    icon: "success", 
                    timer: 1500, 
                    showConfirmButton: false 
                }).then(() => window.location.href = redirect);
                
            } else if (res.status === 403) {
                // Account locked or status issue
                if (data.message.includes('locked')) {
                    lockTimer.style.display = 'block';
                    lockMessage.textContent = data.message;
                    supportNote.style.display = 'block';
                    
                    // Extract minutes from message if possible
                    const minutesMatch = data.message.match(/(\d+)\s*minute/);
                    if (minutesMatch) {
                        startLockTimer(parseInt(minutesMatch[1]));
                    }
                } else {
                    // Status issue (pending/inactive)
                    Swal.fire({ 
                        title: "Access Restricted", 
                        text: data.message, 
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    supportNote.style.display = 'block';
                }
                
            } else if (res.status === 401) {
                // Wrong password
                if (data.message.includes('attempt')) {
                    attemptsCounter.style.display = 'block';
                    attemptsMessage.textContent = data.message;
                } else {
                    Swal.fire({ 
                        title: "Login Failed", 
                        text: data.message, 
                        icon: "error",
                        confirmButtonText: "Try Again"
                    });
                }
            } else {
                Swal.fire({ 
                    title: "Login Failed", 
                    text: data.message || "Invalid email or password", 
                    icon: "error",
                    confirmButtonText: "Try Again"
                });
            }
            
        } catch (err) {
            console.error('Login error:', err);
            Swal.fire({ 
                title: "Connection Error", 
                text: "Unable to connect to server. Please check your internet connection.", 
                icon: "error" 
            });
        } finally {
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    });

    // Lock Timer Function
    function startLockTimer(minutes) {
        const lockTimer = document.getElementById('lockTimer');
        const lockMessage = document.getElementById('lockMessage');
        let totalSeconds = minutes * 60;
        
        const timer = setInterval(() => {
            if (totalSeconds <= 0) {
                clearInterval(timer);
                lockTimer.style.display = 'none';
                document.getElementById('supportNote').style.display = 'none';
            } else {
                const mins = Math.floor(totalSeconds / 60);
                const secs = totalSeconds % 60;
                lockMessage.textContent = `Account is locked. Please try again in ${mins}m ${secs}s or contact ICTD support.`;
                totalSeconds--;
            }
        }, 1000);
    }

    // Forgot Password
    const sendResetBtn = document.getElementById('sendResetLinkBtn');
    const resetEmail = document.getElementById('resetEmail');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');

    sendResetBtn.addEventListener('click', async function() {
        const email = resetEmail.value.trim();
        if (!email) {
            Swal.fire('Email Required', 'Please enter your email address.', 'warning');
            return;
        }
        if (!email.endsWith('@arta.gov.ph')) {
            Swal.fire('Invalid Domain', 'Only @arta.gov.ph email addresses are allowed.', 'warning');
            return;
        }

        const original = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
        this.disabled = true;

        try {
            const response = await fetch("../api/auth/forgot_password.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: email })
            });
            const data = await response.json();
            
            // Close modal
            const modalInstance = bootstrap.Modal.getInstance(forgotPasswordModal);
            if (modalInstance) modalInstance.hide();

            if (response.ok && data.status === 'success') {
                Swal.fire('Reset Link Sent', data.message, 'success');
                resetEmail.value = '';
            } else {
                Swal.fire('Request Failed', data.message || 'Please try again later.', 'error');
            }
        } catch (err) {
            console.error('Forgot password error:', err);
            const modalInstance = bootstrap.Modal.getInstance(forgotPasswordModal);
            if (modalInstance) modalInstance.hide();
            Swal.fire('Connection Error', 'Unable to connect to server.', 'error');
        } finally {
            this.innerHTML = original;
            this.disabled = false;
        }
    });

    // Clear modal on close
    forgotPasswordModal.addEventListener('hidden.bs.modal', function () {
        resetEmail.value = '';
    });

    // Check for existing lock status on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('locked') === 'true') {
            document.getElementById('supportNote').style.display = 'block';
        }
    });
</script>
</body>
</html>