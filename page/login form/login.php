<?php

session_start();

// Example login logic (replace with your own authentication)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['arta_email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Default credentials
    if ($email === 'admin@arta.gov.ph' && $password === 'admin123') {
        // Show SweetAlert then redirect
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome, admin!',
                confirmButtonColor: '#00308F',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '../../pages/admin/dashboard.php';
            });
            setTimeout(function(){
                window.location.href = '../../pages/admin/dashboard.php';
            }, 1600);
        </script>
        </body>
        </html>";
        exit;
    } else {
        $error_message = "Incorrect email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>ARTA Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	body {
		background: #e6e9f2;
		/* Light gray background */
		font-family: 'Poppins', sans-serif;
	}

	.card {
		border: none;
		border-radius: 20px;
		box-shadow: 0 12px 48px rgba(0, 48, 143, 0.45), 0 2px 16px rgba(0, 0, 0, 0.35);
		/* Navy blue shadow */
		background: #fff;
		padding: 2.5rem 2rem;
	}

	.card .arta-logo {
		width: 150px;
		height: 150px;
		object-fit: cover;
		/* border-radius: 50%;  */
		/* box-shadow: 0 2px 12px rgba(0, 48, 143, 0.18); */
		/* Navy blue shadow */
		background: #fff;
		margin-bottom: 10px;
	}

	.card .fa-user-circle {
		font-size: 1.5rem;
		color: #00308F;
		/* Navy blue */
		margin-bottom: 10px;
	}

	.card .fa-lock {
		font-size: 1.5rem;
		color: #111;
		margin-bottom: 10px;
	}

	.btn-custom {
		background-color: #00308F;
		/* Navy blue */
		color: #fff;
		font-weight: 700;
		border-radius: 8px;
		border: none;
		box-shadow: 0 4px 18px rgba(0, 48, 143, 0.18);
		/* Navy blue shadow */
		transition: background .2s, color .2s;
	}

	.btn-custom:hover {
		background-color: #6699CC;
		/* Light blue */
		color: #00308F;
		/* Navy blue */
	}

	.text-red {
		color: #00308F !important;
		/* Navy blue */
		font-weight: 700;
		letter-spacing: 1px;
	}

	.form-label {
		color: #121212ff;
		font-weight: 500;
	}

	.card .text-muted {
		color: #111 !important;
	}

	a.text-red {
		color: #00308F !important;
		/* Navy blue */
		font-weight: 500;
	}

	a.text-red:hover {
		text-decoration: underline;
		color: #111 !important;
	}

	a.text-danger {
		color: #c73f3f !important;
	}
	</style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-4">
				<div class="card shadow-lg">
					<div class="text-center">
						<img src="../../img/ekyc-portal.png" alt="ARTA Logo" class="arta-logo mb-8">
						<h2 class="mb-2 text-red">ARTA Portal </h2>
						<!-- <img src="../../img/arta_logo.png" alt="ARTA Logo"
							style="width: 30px; height: 30px; vertical-align: middle;"> -->
						<p class="text-muted mb-4">Sign in to your account</p>
					</div>
					<?php if (isset($error_message)) : ?>
					<script>
					document.addEventListener('DOMContentLoaded', function() {
						Swal.fire({
							icon: 'error',
							title: 'Login Failed',
							html: `<b>Default Credentials:</b><br>
                                Username: <span style='color:#00308F;'>admin@arta.gov.ph</span><br>
                                Password: <span style='color:#00308F;'>admin123</span><br><br>
                                <?= addslashes($error_message) ?>`,
							confirmButtonColor: '#00308F'
						});
					});
					</script>
					<?php endif; ?>
					<form action="login.php" method="POST" id="loginForm">
						<div class="mb-3">
							<label class="form-label"><i class="fa-solid fa-user-circle me-2"></i>ARTA Email</label>
							<input type="text" class="form-control" name="arta_email"
								placeholder="Enter your ARTA Email" required>
						</div>
						<div class="mb-3">
							<label class="form-label"><i class="fa-solid fa-lock me-2"></i>Password</label>
							<input type="password" class="form-control" name="password"
								placeholder="Enter your password" required>
						</div>
						<div class="d-grid">
							<button type="submit" class="btn btn-custom">Sign in</button>
						</div>
						<div class="text-center mt-3">
							<a href="registration.php" class="text-red"><i
									class="fa-solid fa-user-plus me-1"></i>Signup</a>
							<br>
							<a href="reset.php" class="text-primary"><i class="fa-solid fa-key me-1"></i>Forgot
								Password?</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>