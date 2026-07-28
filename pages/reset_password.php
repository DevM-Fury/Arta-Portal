<?php
$token = trim($_GET['token'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | ARTA Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --portal-blue: #1877d2;
            --portal-blue-dark: #0f4ea8;
            --portal-border: rgba(15, 23, 42, 0.08);
            --portal-shadow: 0 26px 56px rgba(15, 23, 42, 0.16);
        }
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top left, rgba(24, 119, 210, 0.16), transparent 28%),
                radial-gradient(circle at bottom right, rgba(66, 183, 42, 0.12), transparent 24%),
                linear-gradient(180deg, #f4f8fc 0%, #eef4fb 52%, #f9fbff 100%);
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            top: 80px;
            right: 10%;
            width: 200px;
            height: 200px;
            background: linear-gradient(145deg, rgba(24, 119, 210, 0.16), rgba(24, 119, 210, 0.02));
            clip-path: polygon(25% 6%, 75% 6%, 100% 50%, 75% 94%, 25% 94%, 0 50%);
            filter: blur(8px);
            pointer-events: none;
        }
        .reset-card {
            width: 100%;
            max-width: 460px;
            border: 1px solid var(--portal-border);
            border-radius: 24px;
            box-shadow: var(--portal-shadow);
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
        }
        .reset-card .card-body {
            padding: 32px;
        }
        .reset-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
        }
        .reset-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }
        .form-control {
            border-radius: 14px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            padding: 12px 14px;
        }
        .form-control:focus {
            border-color: rgba(24,119,210,0.5);
            box-shadow: 0 0 0 4px rgba(24,119,210,0.12);
        }
        .btn {
            border-radius: 14px;
            font-weight: 700;
            padding: 12px 16px;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--portal-blue), var(--portal-blue-dark));
            border-color: transparent;
            box-shadow: 0 14px 28px rgba(24,119,210,0.18);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1466b4, #0d438f);
            border-color: transparent;
        }
    </style>
</head>
<body>
    <div class="card reset-card">
        <div class="card-body">
            <h1 class="reset-title mb-2">Create a new password</h1>
            <p class="reset-subtitle mb-4">Enter a new password for your ARTA Portal account.</p>

            <form id="resetPasswordForm">
                <input type="hidden" id="resetToken" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" id="newPassword" class="form-control" minlength="8" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="form-control" minlength="8" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" id="resetSubmitBtn" class="btn btn-primary">Reset Password</button>
                    <a href="login.php" class="btn btn-outline-secondary">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const token = document.getElementById('resetToken').value.trim();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const submitButton = document.getElementById('resetSubmitBtn');

        if (!token) {
            Swal.fire('Invalid Link', 'The reset link is missing or invalid.', 'error');
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Updating...';

        try {
            const response = await fetch('../api/auth/reset_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    token,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                })
            });

            const payload = await response.json();
            if (!response.ok || payload.status !== 'success') {
                throw new Error(payload.message || 'Unable to reset password.');
            }

            await Swal.fire('Password Updated', payload.message, 'success');
            window.location.href = 'login.php';
        } catch (error) {
            Swal.fire('Reset Failed', error.message || 'Unable to reset password.', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Reset Password';
        }
    });
    </script>
</body>
</html>
