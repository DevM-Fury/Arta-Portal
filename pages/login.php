<?php
// pages/login.php
session_start();
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
        /* Paste your existing styles here – same as before */
        :root { --primary-blue: #1a56db; --light-blue: #e8f4ff; --text-dark: #1f2937; --text-light: #6b7280; }
        body { background: #f8fafc; font-family: system-ui; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; margin: 0; }
        .login-container { max-width: 420px; width: 100%; }
        .login-card { background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 40px 35px; border: 1px solid #e5e7eb; position: relative; overflow: hidden; }
        .login-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--primary-blue), #3b82f6); }
        .logo-section { text-align: center; margin-bottom: 30px; }
        .logo-circle { width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary-blue), #3b82f6); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 28px; }
        .logo-circle i { font-size: 32px; }
        .logo-section h3 { color: var(--text-dark); font-weight: 600; margin-bottom: 5px; font-size: 1.4rem; }
        .logo-section p { color: var(--text-light); font-size: 0.9rem; }
        .form-label { font-weight: 500; color: var(--text-dark); margin-bottom: 8px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .form-label i { color: var(--primary-blue); width: 18px; }
        .input-group-custom { position: relative; margin-bottom: 20px; }
        .form-control-custom { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; background: white; }
        .form-control-custom:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(26,86,219,0.1); outline: none; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .password-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; }
        .btn-login { background: linear-gradient(to right, var(--primary-blue), #3b82f6); color: white; border: none; border-radius: 8px; padding: 13px; font-weight: 600; font-size: 15px; width: 100%; transition: 0.2s; margin-top: 10px; margin-bottom: 20px; }
        .btn-login:hover { background: linear-gradient(to right, #1e40af, var(--primary-blue)); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26,86,219,0.2); }
        .btn-outline-primary { border-color: var(--primary-blue); color: var(--primary-blue); border-radius: 8px; padding: 10px 20px; font-weight: 500; }
        .btn-outline-primary:hover { background: var(--primary-blue); color: white; }
        .register-link { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .register-link p { color: var(--text-light); margin-bottom: 5px; font-size: 14px; }
        .register-link a { color: var(--primary-blue); font-weight: 500; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }
        .security-notice { display: flex; align-items: center; gap: 8px; background: var(--light-blue); padding: 10px 14px; border-radius: 8px; margin-bottom: 25px; font-size: 13px; color: var(--text-dark); }
        .security-notice i { color: var(--primary-blue); }
        .footer-text { text-align: center; margin-top: 30px; color: var(--text-light); font-size: 12px; }
        @media (max-width:480px) { .login-card { padding: 30px 25px; } .logo-circle { width:60px; height:60px; font-size:24px; } .logo-section h3 { font-size:1.2rem; } }
        .modal-content { border-radius:16px; border:none; box-shadow:0 20px 40px rgba(0,0,0,0.1); padding:10px; }
        .modal-header { border-bottom:none; padding-bottom:0; }
        .modal-footer { border-top:none; padding-top:0; }
        .modal-title i { color: var(--primary-blue); }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="logo-section">
            <div class="logo-circle"><i class="fas fa-shield-alt"></i></div>
            <h3>ARTA Portal Login</h3>
            <p>Secure access to government services</p>
        </div>
        <div class="security-notice"><i class="fas fa-lock"></i><span>Your connection is secure. All data is encrypted.</span></div>

        <form id="loginForm">
            <div class="input-group-custom">
                <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                <div class="position-relative">
                    <input type="email" name="email" class="form-control-custom" placeholder="agency.user@arta.gov.ph" required>
                    <div class="input-icon"><i class="fas fa-user"></i></div>
                </div>
            </div>
            <div class="input-group-custom">
                <label class="form-label"><i class="fas fa-key"></i> Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="passwordInput" class="form-control-custom" placeholder="Enter your password" required>
                    <div class="input-icon"><i class="fas fa-lock"></i></div>
                    <button type="button" class="password-toggle" id="passwordToggle"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt me-2"></i> Sign In</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="registration.php">Request access</a></p>
            <p><a href="#" id="forgotPasswordLink" style="font-size:13px;" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot password?</a></p>
        </div>
        <div class="footer-text"><p>© 2024 Anti-Red Tape Authority</p></div>
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
                <p class="text-muted mb-3">Enter your registered email. A new password will be sent to your inbox.</p>
                <div class="input-group-custom mb-0">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="position-relative">
                        <input type="email" id="resetEmail" class="form-control-custom" placeholder="agency.user@arta.gov.ph" required>
                        <div class="input-icon"><i class="fas fa-user"></i></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="sendResetLinkBtn" class="btn btn-primary" style="background:linear-gradient(to right, var(--primary-blue), #3b82f6); border:none;">
                    <i class="fas fa-paper-plane me-2"></i>Send New Password
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ===== Password Toggle =====
    const pwInput = document.getElementById('passwordInput');
    const pwToggle = document.getElementById('passwordToggle');
    if (pwToggle && pwInput) {
        pwToggle.addEventListener('click', () => {
            pwInput.type = pwInput.type === 'password' ? 'text' : 'password';
            pwToggle.innerHTML = pwInput.type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }

    // ===== Login Form =====
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(e.target));
        const btn = e.target.querySelector('.btn-login');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...';
        btn.disabled = true;
        try {
            const res = await fetch("../api/auth/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            });
            const data = await res.json();
            if (data.status === "success") {
                let redirect = "dashboard_user.php";
                if (data.user?.role) {
                    const role = data.user.role.toLowerCase();
                    if (role === 'admin') redirect = "dashboard_admin.php";
                    else if (role === 'subadmin') redirect = "dashboard_subadmin.php";
                }
                Swal.fire({ title: "Welcome!", text: `Logged in as ${data.user.firstname || data.user.email}`, icon: "success", timer: 1500, showConfirmButton: false })
                    .then(() => window.location.href = redirect);
            } else {
                Swal.fire({ title: "Login Failed", text: data.message || "Invalid email or password", icon: "error", confirmButtonText: "Try Again" });
            }
        } catch (err) {
            Swal.fire({ title: "Connection Error", text: "Unable to connect to server.", icon: "error" });
        } finally {
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    });

    // ===== Forgot Password – with DEBUGGING =====
    const sendResetBtn = document.getElementById('sendResetLinkBtn');
    const resetEmail = document.getElementById('resetEmail');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');

    // Remove any stray aria-hidden
    document.querySelectorAll('.login-container, .login-card').forEach(el => el.removeAttribute('aria-hidden'));

    sendResetBtn.addEventListener('click', async function() {
        const email = resetEmail.value.trim();
        console.log('📧 Email entered:', email); // <-- CHECK CONSOLE

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
            console.log('🚀 Sending fetch to ../api/auth/forgot_password.php');
            const response = await fetch("../api/auth/forgot_password.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: email })
            });
            console.log('📡 Response status:', response.status);

            const data = await response.json();
            console.log('📦 Response data:', data);

            const modalInstance = bootstrap.Modal.getInstance(forgotPasswordModal);
            if (modalInstance) modalInstance.hide();

            if (response.ok && data.status === 'success') {
                Swal.fire('Password Reset', data.message, 'success');
                resetEmail.value = '';
            } else if (response.status === 404 || data.status === 'not_found') {
                Swal.fire('Email Not Registered', data.message || 'Email not registered.', 'error');
            } else {
                Swal.fire('Request Failed', data.message || 'Please try again later.', 'error');
            }
        } catch (err) {
            console.error('❌ Fetch error:', err);
            const modalInstance = bootstrap.Modal.getInstance(forgotPasswordModal);
            if (modalInstance) modalInstance.hide();
            Swal.fire('Connection Error', 'Unable to connect to server.', 'error');
        } finally {
            this.innerHTML = original;
            this.disabled = false;
        }
    });

    // Reset modal on close
    forgotPasswordModal.addEventListener('hidden.bs.modal', function () {
        resetEmail.value = '';
    });
</script>
</body>
</html>