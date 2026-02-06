<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ARTA Portal Login - Secure Access</title>

	<!-- ✅ Bootstrap & SweetAlert -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- ✅ Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<style>
	:root {
		--primary-blue: #003366;
		--cyber-blue: #0066cc;
		--dark-bg: #0a1929;
		--card-bg: rgba(255, 255, 255, 0.05);
		--border-glow: rgba(0, 102, 204, 0.3);
		--text-light: #f0f4f8;
		--text-muted: #a0b4c8;
	}
	
	body {
		font-family: 'Inter', sans-serif;
		background-color: var(--dark-bg);
		background-image: 
			radial-gradient(circle at 15% 50%, rgba(0, 102, 204, 0.1) 0%, transparent 20%),
			radial-gradient(circle at 85% 30%, rgba(0, 102, 204, 0.1) 0%, transparent 20%),
			radial-gradient(circle at 50% 80%, rgba(0, 102, 204, 0.1) 0%, transparent 20%);
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 20px;
		margin: 0;
		color: var(--text-light);
		perspective: 1000px;
	}

	.container {
		width: 100%;
		max-width: 1200px;
		display: flex;
		align-items: stretch;
		gap: 60px;
	}

	.hero-section {
		flex: 1;
		padding-right: 40px;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}

	.hero-title {
		font-size: 2.8rem;
		font-weight: 800;
		line-height: 1.2;
		margin-bottom: 20px;
		background: linear-gradient(135deg, #ffffff, var(--cyber-blue));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.hero-subtitle {
		font-size: 1.2rem;
		color: var(--text-muted);
		margin-bottom: 30px;
		line-height: 1.6;
	}

	.security-badge {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		background: rgba(0, 102, 204, 0.1);
		border: 1px solid var(--border-glow);
		border-radius: 20px;
		padding: 8px 20px;
		margin-bottom: 30px;
		font-size: 0.9rem;
		color: var(--cyber-blue);
	}

	.security-badge i {
		font-size: 1.2rem;
	}

	.features {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
		margin-top: 40px;
	}

	.feature-item {
		display: flex;
		align-items: center;
		gap: 15px;
	}

	.feature-icon {
		width: 40px;
		height: 40px;
		background: rgba(0, 102, 204, 0.1);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: var(--cyber-blue);
		font-size: 1.2rem;
	}

	.feature-text {
		font-size: 0.95rem;
		color: var(--text-muted);
	}

	.card-container {
		width: 100%;
		max-width: 450px;
		position: relative;
		transform-style: preserve-3d;
		transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
		display: flex;
		align-items: stretch;
	}

	.card-container.flipped {
		transform: rotateY(180deg);
	}

	.login-card {
		background: rgba(16, 30, 50, 0.8);
		backdrop-filter: blur(10px);
		border-radius: 20px;
		border: 1px solid var(--border-glow);
		box-shadow: 
			0 8px 32px rgba(0, 0, 0, 0.3),
			0 0 0 1px rgba(0, 102, 204, 0.1),
			inset 0 1px 0 rgba(255, 255, 255, 0.1);
		width: 100%;
		height: 100%;
		min-height: 550px;
		position: absolute;
		backface-visibility: hidden;
		padding: 35px 40px 30px;
		overflow: hidden;
		display: flex;
		flex-direction: column;
	}

	.login-card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, var(--cyber-blue), #00ccff);
		border-radius: 20px 20px 0 0;
	}

	.card-back {
		background: rgba(16, 30, 50, 0.8);
		transform: rotateY(180deg);
		text-align: center;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		padding: 35px 40px 30px;
	}

	.login-header {
		text-align: center;
		margin-bottom: 1.5rem;
	}

	.logo-container {
		width: 80px;
		height: 80px;
		background: linear-gradient(135deg, var(--cyber-blue), #00ccff);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto 15px;
		box-shadow: 0 0 30px rgba(0, 102, 204, 0.4);
	}

	.logo-container img {
		width: 50px;
		height: 50px;
		object-fit: contain;
		filter: brightness(0) invert(1);
	}

	.login-header h3 {
		font-weight: 700;
		color: var(--text-light);
		letter-spacing: 0.5px;
		font-size: 1.5rem;
		margin-bottom: 6px;
	}

	.login-header p {
		color: var(--text-muted);
		font-size: 0.9rem;
		margin-bottom: 0;
	}

	.form-label {
		font-weight: 600;
		color: var(--text-light);
		margin-bottom: 8px;
		font-size: 14px;
		display: flex;
		align-items: center;
		gap: 8px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.form-label i {
		color: var(--cyber-blue);
		width: 20px;
	}

	.input-group {
		position: relative;
		margin-bottom: 1.2rem;
	}

	.form-control {
		background: rgba(255, 255, 255, 0.05);
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: 10px;
		padding: 14px 16px 14px 45px;
		font-size: 14px;
		transition: all 0.3s ease;
		color: var(--text-light);
	}

	.form-control:focus {
		background: rgba(255, 255, 255, 0.08);
		border-color: var(--cyber-blue);
		box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
		color: var(--text-light);
		outline: none;
	}

	.form-control::placeholder {
		color: rgba(255, 255, 255, 0.4);
	}

	.input-icon {
		position: absolute;
		left: 15px;
		top: 50%;
		transform: translateY(-50%);
		color: var(--text-muted);
		z-index: 2;
	}

	.btn-primary {
		background: linear-gradient(135deg, var(--cyber-blue), #0052a3);
		border: none;
		border-radius: 10px;
		padding: 14px;
		font-weight: 600;
		font-size: 15px;
		letter-spacing: 0.5px;
		transition: all 0.3s ease;
		width: 100%;
		color: white;
		position: relative;
		overflow: hidden;
		margin-top: 8px;
		margin-bottom: 10px;
	}

	.btn-primary:hover {
		background: linear-gradient(135deg, #0052a3, var(--cyber-blue));
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(0, 102, 204, 0.4);
	}

	.btn-primary:active {
		transform: translateY(0);
	}

	.register-link {
		text-align: center;
		margin-top: 1rem;
		padding-top: 1rem;
		border-top: 1px solid rgba(255, 255, 255, 0.1);
	}

	.register-link p {
		color: var(--text-muted);
		margin-bottom: 0;
		font-size: 14px;
		line-height: 1.4;
	}

	.register-link a {
		color: var(--cyber-blue);
		font-weight: 600;
		text-decoration: none;
		transition: all 0.3s ease;
		display: inline-block;
		margin-top: 2px;
	}

	.register-link a:hover {
		color: #00ccff;
	}

	.flip-btn {
		margin-top: 1.2rem;
		color: var(--cyber-blue);
		font-weight: 600;
		cursor: pointer;
		text-align: center;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.flip-btn:hover {
		color: #00ccff;
	}

	/* SSO Section */
	.sso-icon-container {
		width: 100px;
		height: 100px;
		background: linear-gradient(135deg, var(--cyber-blue), #00ccff);
		border-radius: 20px;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 20px;
		box-shadow: 0 0 30px rgba(0, 102, 204, 0.4);
	}

	.sso-icon {
		width: 60px;
		height: 60px;
		filter: brightness(0) invert(1);
	}

	.card-back h4 {
		font-weight: 700;
		color: var(--text-light);
		margin-bottom: 8px;
		font-size: 1.3rem;
	}

	.card-back p {
		color: var(--text-muted);
		margin-bottom: 25px;
		font-size: 0.9rem;
		max-width: 300px;
		line-height: 1.5;
	}

	.card-back .btn-primary {
		width: auto;
		padding: 12px 40px;
		font-size: 1rem;
		margin-top: 0;
		margin-bottom: 20px;
	}

	/* Password toggle */
	.password-toggle {
		position: absolute;
		right: 15px;
		top: 50%;
		transform: translateY(-50%);
		background: none;
		border: none;
		color: var(--text-muted);
		cursor: pointer;
		z-index: 2;
	}

	.password-toggle:hover {
		color: var(--cyber-blue);
	}

	/* Animated background elements */
	.cyber-grid {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-image: 
			linear-gradient(rgba(0, 102, 204, 0.05) 1px, transparent 1px),
			linear-gradient(90deg, rgba(0, 102, 204, 0.05) 1px, transparent 1px);
		background-size: 50px 50px;
		z-index: -1;
		animation: gridMove 20s linear infinite;
	}

	@keyframes gridMove {
		0% { transform: translateY(0); }
		100% { transform: translateY(50px); }
	}

	/* Responsive */
	@media (max-width: 992px) {
		.container {
			flex-direction: column;
			gap: 40px;
			align-items: center;
		}
		
		.hero-section {
			padding-right: 0;
			text-align: center;
			justify-content: flex-start;
		}
		
		.hero-title {
			font-size: 2.2rem;
		}
		
		.features {
			grid-template-columns: 1fr;
			max-width: 400px;
			margin: 30px auto 0;
		}
		
		.card-container {
			align-items: center;
		}
		
		.login-card {
			height: auto;
			min-height: 520px;
		}
	}

	@media (max-width: 576px) {
		.login-card {
			padding: 25px 20px;
			min-height: 500px;
		}
		
		.card-back {
			padding: 25px 20px;
		}
		
		.hero-title {
			font-size: 1.8rem;
		}
		
		.card-back .btn-primary {
			padding: 12px 30px;
			margin-bottom: 15px;
		}
		
		.logo-container {
			width: 70px;
			height: 70px;
			margin-bottom: 12px;
		}
		
		.logo-container img {
			width: 45px;
			height: 45px;
		}
		
		.login-header h3 {
			font-size: 1.3rem;
		}
	}
	</style>
</head>

<body>
	<div class="cyber-grid"></div>
	
	<div class="container">
		<!-- Hero Section (Left) -->
		<div class="hero-section">
			<div class="security-badge">
				<i class="fas fa-shield-alt"></i>
				<span>SECURE ACCESS PROTECTED</span>
			</div>
			
			<h1 class="hero-title">ANTI-RED TAPE AUTHORITY SYSTEM PORTAL</h1>
			
			<p class="hero-subtitle">
				The ARTA cybersecurity platform provides government agencies and authorized personnel 
				with advanced protection for sensitive data and critical infrastructure access.
			</p>
			
			<div class="features">
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-user-shield"></i>
					</div>
					<div class="feature-text">Multi-factor authentication</div>
				</div>
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-lock"></i>
					</div>
					<div class="feature-text">End-to-end encryption</div>
				</div>
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-eye-slash"></i>
					</div>
					<div class="feature-text">Zero-knowledge protocol</div>
				</div>
				<div class="feature-item">
					<div class="feature-icon">
						<i class="fas fa-server"></i>
					</div>
					<div class="feature-text">Secure infrastructure</div>
				</div>
			</div>
		</div>

		<!-- Login Card (Right) -->
		<div class="card-container" id="flipCard">
			<!-- FRONT: Normal Login -->
			<div class="login-card">
				<div class="login-header">
					<div class="logo-container">
						<img src="../img/arta_logo.png" alt="ARTA Logo">
					</div>
					<h3>SECURE PORTAL LOGIN</h3>
					<p>Access ARTA Government Services</p>
				</div>

				<form id="loginForm">
					<div class="input-group">
						<label class="form-label">
							<i class="fas fa-id-card"></i> Government Email
						</label>
						<div class="position-relative">
							<input type="email" name="email" class="form-control" placeholder="agency.user@gov.ph" required>
							<div class="input-icon">
								<i class="fas fa-envelope"></i>
							</div>
						</div>
					</div>

					<div class="input-group">
						<label class="form-label">
							<i class="fas fa-key"></i> Security Key
						</label>
						<div class="position-relative">
							<input type="password" name="password" id="passwordInput" class="form-control" placeholder="Enter security key" required>
							<div class="input-icon">
								<i class="fas fa-lock"></i>
							</div>
							<button type="button" class="password-toggle" id="passwordToggle">
								<i class="fas fa-eye"></i>
							</button>
						</div>
					</div>

					<button class="btn btn-primary w-100">Authenticate & Access</button>

					<div class="register-link">
						<p class="mb-0">New government personnel?<br>
							<a href="registration.php">Request access here</a>
						</p>
					</div>
				</form>

				<div class="flip-btn" id="flipToSSO">
					<i class="fas fa-exchange-alt text-danger"></i> Secure SSO Authentication
				</div>
			</div>

			<!-- BACK: SSO Login -->
			<div class="login-card card-back" id="cardBack">
				<div class="sso-icon-container">
					<img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Hexagon_icon.svg" alt="SSO" class="sso-icon">
				</div>
				<h4>FEDERATED IDENTITY ACCESS</h4>
				<p>Use your government single sign-on credentials for unified secure access</p>
				<button id="ssoLogin" class="btn btn-primary px-4">Proceed to SSO Gateway</button>
				<div class="flip-btn" id="flipBack">
					<i class="fas fa-arrow-left"></i> Standard Authentication
				</div>
			</div>
		</div>
	</div>

	<!-- Font Awesome for icons -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

	<script>
	// Flip card logic
	const flipCard = document.getElementById('flipCard');
	document.getElementById('flipToSSO').addEventListener('click', () => flipCard.classList.add('flipped'));
	document.getElementById('flipBack').addEventListener('click', () => flipCard.classList.remove('flipped'));

	// Password toggle
	const passwordInput = document.getElementById('passwordInput');
	const passwordToggle = document.getElementById('passwordToggle');
	if (passwordToggle && passwordInput) {
		passwordToggle.addEventListener('click', () => {
			const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
			passwordInput.setAttribute('type', type);
			passwordToggle.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
		});
	}

	// ✅ Handle SSO Click
	document.getElementById('ssoLogin').addEventListener('click', () => {
		window.location.href = "/sso/login";
	});

	// ✅ Normal Login (SIMPLE VERSION WITHOUT CSRF)
	const form = document.getElementById('loginForm');
	form.addEventListener('submit', async (e) => {
		e.preventDefault();
		const formData = Object.fromEntries(new FormData(form));
		
		try {
			const res = await fetch("../api/auth/login.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify(formData)
			});
			const data = await res.json();

			if (data.status === "success") {
				let redirectUrl = "dashboard_user.php";
				if (data.user && data.user.role) {
					switch (data.user.role.toLowerCase()) {
						case "admin":
							redirectUrl = "dashboard_admin.php";
							break;
						case "subadmin":
							redirectUrl = "dashboard_subadmin.php";
							break;
					}
				}
				Swal.fire({
					title: "Access Granted!",
					text: `Welcome ${data.user.firstname || ''}! Authentication successful.`,
					icon: "success",
					timer: 1500,
					showConfirmButton: false,
					background: 'rgba(16, 30, 50, 0.95)',
					color: '#f0f4f8'
				}).then(() => window.location.href = redirectUrl);
			} else {
				Swal.fire({
					title: "Access Denied",
					text: data.message || "Authentication failed. Please check your credentials.",
					icon: "error",
					background: 'rgba(16, 30, 50, 0.95)',
					color: '#f0f4f8'
				});
			}
		} catch (err) {
			console.error(err);
			Swal.fire({
				title: "Security Alert!",
				text: "Something went wrong. Please try again or contact system administrator.",
				icon: "error",
				background: 'rgba(16, 30, 50, 0.95)',
				color: '#f0f4f8'
			});
		}
	});
	</script>
</body>

</html>