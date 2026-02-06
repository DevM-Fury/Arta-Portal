<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ARTA Portal Registration - Secure Access</title>

	<!-- ✅ Bootstrap & SweetAlert -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- ✅ Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<!-- ✅ Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<style>
	:root {
		--primary-blue: #003366;
		--cyber-blue: #0066cc;
		--dark-bg: #0a1929;
		--card-bg: rgba(16, 30, 50, 0.8);
		--border-glow: rgba(0, 102, 204, 0.3);
		--text-light: #f0f4f8;
		--text-muted: #a0b4c8;
		--success-green: #28a745;
		--error-red: #dc3545;
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
	}

	.container {
		width: 100%;
		max-width: 1200px;
	}

	/* Hero Section */
	.hero-section {
		text-align: center;
		margin-bottom: 40px;
	}

	.security-badge {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		background: rgba(0, 102, 204, 0.1);
		border: 1px solid var(--border-glow);
		border-radius: 20px;
		padding: 8px 20px;
		margin-bottom: 20px;
		font-size: 0.9rem;
		color: var(--cyber-blue);
	}

	.security-badge i {
		font-size: 1.2rem;
	}

	.hero-title {
		font-size: 2.5rem;
		font-weight: 800;
		line-height: 1.2;
		margin-bottom: 15px;
		background: linear-gradient(135deg, #ffffff, var(--cyber-blue));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.hero-subtitle {
		font-size: 1.1rem;
		color: var(--text-muted);
		margin-bottom: 30px;
		line-height: 1.6;
		max-width: 800px;
		margin-left: auto;
		margin-right: auto;
	}

	/* Registration Card */
	.registration-card {
		background: var(--card-bg);
		backdrop-filter: blur(10px);
		border-radius: 20px;
		border: 1px solid var(--border-glow);
		box-shadow: 
			0 8px 32px rgba(0, 0, 0, 0.3),
			0 0 0 1px rgba(0, 102, 204, 0.1),
			inset 0 1px 0 rgba(255, 255, 255, 0.1);
		max-width: 900px;
		margin: 0 auto;
		padding: 40px;
		position: relative;
		overflow: hidden;
	}

	.registration-card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, var(--cyber-blue), #00ccff);
		border-radius: 20px 20px 0 0;
	}

	.card-header {
		text-align: center;
		margin-bottom: 2.5rem;
	}

	.logo-container {
		width: 80px;
		height: 80px;
		background: linear-gradient(135deg, var(--cyber-blue), #00ccff);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto 20px;
		box-shadow: 0 0 30px rgba(0, 102, 204, 0.4);
	}

	.logo-container i {
		font-size: 3rem;
		color: white;
	}

	.card-header h3 {
		font-weight: 700;
		color: var(--text-light);
		letter-spacing: 0.5px;
		font-size: 1.5rem;
		margin-bottom: 8px;
	}

	.card-header p {
		color: var(--text-muted);
		font-size: 0.9rem;
	}

	/* Form Styles */
	.form-label {
		font-weight: 600;
		color: var(--text-light);
		margin-bottom: 10px;
		font-size: 14px;
		display: flex;
		align-items: center;
		gap: 8px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		height: 24px;
	}

	.form-label i {
		color: var(--cyber-blue);
		width: 20px;
		font-size: 14px;
	}

	.input-group {
		position: relative;
		margin-bottom: 25px;
		min-height: 100px;
		display: flex;
		flex-direction: column;
	}

	.form-control {
		background: rgba(255, 255, 255, 0.05);
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: 10px;
		padding: 14px 16px 14px 45px;
		font-size: 14px;
		transition: all 0.3s ease;
		color: var(--text-light);
		width: 100%;
		height: 48px;
		box-sizing: border-box;
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
		height: 20px;
		width: 20px;
		display: flex;
		align-items: center;
		justify-content: center;
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
		height: 20px;
		width: 20px;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0;
	}

	.password-toggle:hover {
		color: var(--cyber-blue);
	}

	/* Button Styles */
	.btn-primary {
		background: linear-gradient(135deg, var(--cyber-blue), #0052a3);
		border: none;
		border-radius: 10px;
		padding: 15px;
		font-weight: 600;
		font-size: 15px;
		letter-spacing: 0.5px;
		transition: all 0.3s ease;
		width: 100%;
		color: white;
		position: relative;
		overflow: hidden;
		margin-top: 10px;
		height: 52px;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
	}

	.btn-primary:hover {
		background: linear-gradient(135deg, #0052a3, var(--cyber-blue));
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(0, 102, 204, 0.4);
	}

	.btn-primary:active {
		transform: translateY(0);
	}

	.btn-secondary {
		background: transparent;
		border: 1px solid var(--cyber-blue);
		border-radius: 10px;
		padding: 15px;
		font-weight: 600;
		font-size: 15px;
		letter-spacing: 0.5px;
		transition: all 0.3s ease;
		width: 100%;
		color: var(--cyber-blue);
		margin-top: 10px;
		height: 52px;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
	}

	.btn-secondary:hover {
		background: rgba(0, 102, 204, 0.1);
		border-color: #00ccff;
		color: #00ccff;
	}

	/* Requirements */
	.requirements {
		background: rgba(0, 102, 204, 0.05);
		border: 1px solid var(--border-glow);
		border-radius: 10px;
		padding: 20px;
		margin-top: 30px;
	}

	.requirements h5 {
		color: var(--cyber-blue);
		font-size: 1rem;
		margin-bottom: 15px;
		display: flex;
		align-items: center;
		gap: 10px;
		height: 24px;
	}

	.requirements ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.requirements li {
		color: var(--text-muted);
		font-size: 0.85rem;
		margin-bottom: 8px;
		display: flex;
		align-items: center;
		gap: 8px;
		line-height: 1.4;
	}

	.requirements li i {
		color: var(--cyber-blue);
		font-size: 0.8rem;
		min-width: 16px;
	}

	/* Back link */
	.back-link {
		text-align: center;
		margin-top: 2rem;
		padding-top: 1.5rem;
		border-top: 1px solid rgba(255, 255, 255, 0.1);
	}

	.back-link a {
		color: var(--cyber-blue);
		font-weight: 600;
		text-decoration: none;
		transition: all 0.3s ease;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		height: 24px;
	}

	.back-link a:hover {
		color: #00ccff;
	}

	/* Form row for two columns */
	.form-row {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 25px;
		align-items: start;
	}

	/* Validation messages */
	.validation-message {
		font-size: 0.8rem;
		margin-top: 8px;
		display: flex;
		align-items: center;
		gap: 5px;
		min-height: 20px;
		line-height: 1.3;
	}

	.valid {
		color: var(--success-green);
	}

	.invalid {
		color: var(--error-red);
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
		.registration-card {
			padding: 30px 25px;
		}
		
		.form-row {
			grid-template-columns: 1fr;
			gap: 25px;
		}
		
		.input-group {
			min-height: 90px;
		}
		
		.hero-title {
			font-size: 2rem;
		}
		
		.hero-subtitle {
			font-size: 1rem;
		}
	}

	@media (max-width: 576px) {
		.registration-card {
			padding: 25px 20px;
		}
		
		.hero-title {
			font-size: 1.8rem;
		}
		
		.card-header h3 {
			font-size: 1.3rem;
		}
		
		.logo-container {
			width: 70px;
			height: 70px;
		}
		
		.logo-container i {
			font-size: 2.5rem;
		}
		
		.input-group {
			min-height: 85px;
			margin-bottom: 20px;
		}
		
		.form-control {
			height: 46px;
			padding: 12px 16px 12px 45px;
		}
		
		.btn-primary, .btn-secondary {
			height: 48px;
			padding: 12px;
		}
	}

	/* Additional styles for consistent alignment */
	.form-field-container {
		flex: 1;
		display: flex;
		flex-direction: column;
	}
	
	.input-wrapper {
		position: relative;
		flex: 1;
	}
	
	.field-group {
		display: flex;
		flex-direction: column;
		height: 100%;
	}
	
	/* Loading spinner */
	.fa-spinner {
		animation: spin 1s linear infinite;
	}
	
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
	</style>
</head>

<body>
	<div class="cyber-grid"></div>
	
	<div class="container">
		<!-- Hero Section -->
		<div class="hero-section">
			<div class="security-badge">
				<i class="fas fa-shield-alt"></i>
				<span>SECURE REGISTRATION PORTAL</span>
			</div>
			
			<h1 class="hero-title">ARTA PORTAL REGISTRATION</h1>
			
			<p class="hero-subtitle">
				Register for secure access to the Anti-Red Tape Authority system portal. 
				Please ensure all information is accurate and follows the required format.
			</p>
		</div>

		<!-- Registration Card -->
		<div class="registration-card">
			<div class="card-header">
				<div class="logo-container">
					<i class="fas fa-shield-alt"></i>
				</div>
				<h3>NEW USER REGISTRATION</h3>
				<p>Government Personnel Registration Form</p>
			</div>

			<form id="registerForm">
				<!-- Row 1: National ID (Full Width) -->
				<div class="input-group field-group">
					<label class="form-label">
						<i class="fas fa-id-card"></i> National ID (PhilSys)
					</label>
					<div class="input-wrapper">
						<input type="text" name="national_id" id="national_id" class="form-control" 
							placeholder="0000-0000-0000 (12 digits)" required>
						<div class="input-icon">
							<i class="fas fa-address-card"></i>
						</div>
					</div>
					<div id="nationalIdFeedback" class="validation-message">
						<i class="fas fa-info-circle"></i>
						<span>Format: 12-digit PhilSys Number (xxxx-xxxx-xxxx)</span>
					</div>
				</div>

				<!-- Row 2: Name Fields -->
				<div class="form-row">
					<div class="input-group field-group">
						<label class="form-label">
							<i class="fas fa-user"></i> First Name
						</label>
						<div class="input-wrapper">
							<input type="text" name="firstname" id="firstname" class="form-control" 
								placeholder="Enter first name" required>
							<div class="input-icon">
								<i class="fas fa-user-circle"></i>
							</div>
						</div>
						<div id="firstnameFeedback" class="validation-message"></div>
					</div>

					<div class="input-group field-group">
						<label class="form-label">
							<i class="fas fa-user"></i> Last Name
						</label>
						<div class="input-wrapper">
							<input type="text" name="lastname" id="lastname" class="form-control" 
								placeholder="Enter last name" required>
							<div class="input-icon">
								<i class="fas fa-user-circle"></i>
							</div>
						</div>
						<div id="lastnameFeedback" class="validation-message"></div>
					</div>
				</div>

				<!-- Row 3: Contact & Email -->
				<div class="form-row">
					<div class="input-group field-group">
						<label class="form-label">
							<i class="fas fa-phone"></i> Contact Number
						</label>
						<div class="input-wrapper">
							<input type="text" name="contact" id="contact" class="form-control" 
								placeholder="09123456789" required>
							<div class="input-icon">
								<i class="fas fa-mobile-alt"></i>
							</div>
						</div>
						<div id="contactFeedback" class="validation-message">
							<i class="fas fa-info-circle"></i>
							<span>11 digits starting with 9 (e.g., 09123456789)</span>
						</div>
					</div>

					<div class="input-group field-group">
						<label class="form-label">
							<i class="fas fa-envelope"></i> Government Email
						</label>
						<div class="input-wrapper">
							<input type="email" name="email" id="email" class="form-control" 
								placeholder="name@arta.gov.ph" required>
							<div class="input-icon">
								<i class="fas fa-at"></i>
							</div>
						</div>
						<div id="emailFeedback" class="validation-message">
							<i class="fas fa-info-circle"></i>
							<span>Only @arta.gov.ph domain allowed</span>
						</div>
					</div>
				</div>

				<!-- Row 4: Passwords -->
				<div class="form-row">
					<div class="input-group field-group">
						<label class="form-label">
							<i class="fas fa-key"></i> Security Key
						</label>
						<div class="input-wrapper">
							<input type="password" name="password" id="password" class="form-control" 
								placeholder="Create security key" required>
							<div class="input-icon">
								<i class="fas fa-lock"></i>
							</div>
							<button type="button" class="password-toggle" id="togglePassword">
								<i class="fas fa-eye"></i>
							</button>
						</div>
						<div id="passwordFeedback" class="validation-message">
							<i class="fas fa-info-circle"></i>
							<span>Minimum 6 characters, alphanumeric</span>
						</div>
					</div>

					<div class="input-group field-group">
						<label class="form-label">
							<i class="fas fa-key"></i> Confirm Key
						</label>
						<div class="input-wrapper">
							<input type="password" name="confirm_password" id="confirm_password" class="form-control" 
								placeholder="Re-enter security key" required>
							<div class="input-icon">
								<i class="fas fa-lock"></i>
							</div>
							<button type="button" class="password-toggle" id="toggleConfirmPassword">
								<i class="fas fa-eye"></i>
							</button>
						</div>
						<div id="confirmFeedback" class="validation-message"></div>
					</div>
				</div>

				<!-- Requirements -->
				<div class="requirements">
					<h5><i class="fas fa-clipboard-check"></i> REGISTRATION REQUIREMENTS</h5>
					<ul>
						<li><i class="fas fa-check-circle"></i> Email must end with <strong>@arta.gov.ph</strong></li>
						<li><i class="fas fa-check-circle"></i> Password must be minimum 6 alphanumeric characters</li>
						<li><i class="fas fa-check-circle"></i> National ID must be 12-digit PhilSys format</li>
						<li><i class="fas fa-check-circle"></i> Contact number must be valid Philippine format (11 digits, starts with 9)</li>
						<li><i class="fas fa-check-circle"></i> All fields are mandatory for verification</li>
					</ul>
				</div>

				<!-- Buttons -->
				<div class="mt-4">
					<button type="submit" class="btn btn-primary" id="submitBtn">
						<i class="fas fa-user-plus"></i> REGISTER & REQUEST ACCESS
					</button>

					<button type="button" class="btn btn-secondary mt-3" onclick="window.location.href='login.php'">
						<i class="fas fa-arrow-left"></i> BACK TO LOGIN
					</button>
				</div>
			</form>

			<div class="back-link">
				<a href="login.php">
					<i class="fas fa-sign-in-alt"></i> Already have an account? Login here
				</a>
			</div>
		</div>
	</div>

	<script>
	// Initialize when page loads
	document.addEventListener('DOMContentLoaded', function() {
		// Get all form elements
		const form = document.getElementById('registerForm');
		const passwordInput = document.getElementById('password');
		const confirmPasswordInput = document.getElementById('confirm_password');
		const togglePassword = document.getElementById('togglePassword');
		const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
		const emailInput = document.getElementById('email');
		const passwordFeedback = document.getElementById('passwordFeedback');
		const emailFeedback = document.getElementById('emailFeedback');
		const nationalIdInput = document.getElementById('national_id');
		const confirmFeedback = document.getElementById('confirmFeedback');
		const contactInput = document.getElementById('contact');
		const contactFeedback = document.getElementById('contactFeedback');
		const submitBtn = document.getElementById('submitBtn');

		// 1. Toggle password visibility
		if (togglePassword && passwordInput) {
			togglePassword.addEventListener('click', () => {
				const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
				passwordInput.setAttribute('type', type);
				togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
			});
		}

		if (toggleConfirmPassword && confirmPasswordInput) {
			toggleConfirmPassword.addEventListener('click', () => {
				const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
				confirmPasswordInput.setAttribute('type', type);
				toggleConfirmPassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
			});
		}

		// 2. Real-time validation
		// Email validation
		emailInput.addEventListener('input', function() {
			const email = this.value;
			const isValid = email.endsWith('@arta.gov.ph');
			
			if (email && !isValid) {
				emailFeedback.innerHTML = '<i class="fas fa-times-circle invalid"></i> Only @arta.gov.ph emails allowed';
				emailFeedback.className = 'validation-message invalid';
			} else if (email && isValid) {
				emailFeedback.innerHTML = '<i class="fas fa-check-circle valid"></i> Valid government email';
				emailFeedback.className = 'validation-message valid';
			} else {
				emailFeedback.innerHTML = '<i class="fas fa-info-circle"></i> Only @arta.gov.ph domain allowed';
				emailFeedback.className = 'validation-message';
			}
		});

		// Password validation
		passwordInput.addEventListener('input', function() {
			const password = this.value;
			const hasMinLength = password.length >= 6;
			const isAlphanumeric = /^[a-zA-Z0-9]+$/.test(password);
			
			if (password && (!hasMinLength || !isAlphanumeric)) {
				passwordFeedback.innerHTML = '<i class="fas fa-times-circle invalid"></i> Must be 6+ alphanumeric characters';
				passwordFeedback.className = 'validation-message invalid';
			} else if (password && hasMinLength && isAlphanumeric) {
				passwordFeedback.innerHTML = '<i class="fas fa-check-circle valid"></i> Strong security key';
				passwordFeedback.className = 'validation-message valid';
			} else {
				passwordFeedback.innerHTML = '<i class="fas fa-info-circle"></i> Minimum 6 characters, alphanumeric';
				passwordFeedback.className = 'validation-message';
			}
			
			checkPasswordMatch();
		});

		// Password confirmation
		confirmPasswordInput.addEventListener('input', checkPasswordMatch);

		function checkPasswordMatch() {
			const password = passwordInput.value;
			const confirm = confirmPasswordInput.value;
			
			if (confirm && password !== confirm) {
				confirmFeedback.innerHTML = '<i class="fas fa-times-circle invalid"></i> Keys do not match';
				confirmFeedback.className = 'validation-message invalid';
			} else if (confirm && password === confirm) {
				confirmFeedback.innerHTML = '<i class="fas fa-check-circle valid"></i> Keys match';
				confirmFeedback.className = 'validation-message valid';
			} else {
				confirmFeedback.innerHTML = '';
				confirmFeedback.className = 'validation-message';
			}
		}

		// National ID validation
		nationalIdInput.addEventListener('input', function() {
			const nationalId = this.value.replace(/-/g, '');
			const isValid = /^\d{12}$/.test(nationalId);
			
			// Auto format
			if (nationalId.length <= 12) {
				let formatted = nationalId;
				if (nationalId.length > 4) {
					formatted = nationalId.substring(0, 4) + '-' + nationalId.substring(4);
				}
				if (nationalId.length > 8) {
					formatted = formatted.substring(0, 9) + '-' + formatted.substring(9);
				}
				this.value = formatted;
			}
			
			const feedbackElement = document.getElementById('nationalIdFeedback');
			if (nationalId && !isValid) {
				feedbackElement.innerHTML = '<i class="fas fa-times-circle invalid"></i> Must be 12 digits (xxxx-xxxx-xxxx)';
				feedbackElement.className = 'validation-message invalid';
			} else if (nationalId && isValid) {
				feedbackElement.innerHTML = '<i class="fas fa-check-circle valid"></i> Valid PhilSys format';
				feedbackElement.className = 'validation-message valid';
			} else {
				feedbackElement.innerHTML = '<i class="fas fa-info-circle"></i> Format: 12-digit PhilSys Number (xxxx-xxxx-xxxx)';
				feedbackElement.className = 'validation-message';
			}
		});

		// Contact validation
		contactInput.addEventListener('input', function() {
			// Remove any non-digit characters
			let contact = this.value.replace(/\D/g, '');
			
			// Only update if it's different
			if (this.value !== contact) {
				this.value = contact;
			}
			
			// Validate
			const isValid = /^9\d{10}$/.test(contact);
			if (contact && !isValid) {
				contactFeedback.innerHTML = '<i class="fas fa-times-circle invalid"></i> Must be 11 digits starting with 9';
				contactFeedback.className = 'validation-message invalid';
			} else if (contact && isValid) {
				contactFeedback.innerHTML = '<i class="fas fa-check-circle valid"></i> Valid Philippine number';
				contactFeedback.className = 'validation-message valid';
			} else {
				contactFeedback.innerHTML = '<i class="fas fa-info-circle"></i> 11 digits starting with 9 (e.g., 09123456789)';
				contactFeedback.className = 'validation-message';
			}
		});

		// 3. Form submission
		form.addEventListener('submit', async (e) => {
			e.preventDefault();
			
			// Show loading state
			const originalText = submitBtn.innerHTML;
			submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> PROCESSING...';
			submitBtn.disabled = true;
			
			try {
				// Collect form data
				const formData = {
					national_id: document.getElementById('national_id').value.replace(/-/g, ''),
					firstname: document.getElementById('firstname').value.trim(),
					lastname: document.getElementById('lastname').value.trim(),
					contact: document.getElementById('contact').value,
					email: document.getElementById('email').value,
					password: document.getElementById('password').value,
					confirm_password: document.getElementById('confirm_password').value
				};
				
				// Validate all fields
				let isValid = true;
				let errorMessage = '';
				
				// Check required fields
				for (const [key, value] of Object.entries(formData)) {
					if (!value && key !== 'confirm_password') {
						isValid = false;
						errorMessage = 'All fields are required';
						break;
					}
				}
				
				// Validate email
				if (isValid && !formData.email.endsWith('@arta.gov.ph')) {
					isValid = false;
					errorMessage = 'Only @arta.gov.ph government emails are allowed';
				}
				
				// Validate password
				if (isValid && (formData.password.length < 6 || !/^[a-zA-Z0-9]+$/.test(formData.password))) {
					isValid = false;
					errorMessage = 'Password must be at least 6 alphanumeric characters';
				}
				
				// Validate password match
				if (isValid && formData.password !== formData.confirm_password) {
					isValid = false;
					errorMessage = 'Security keys do not match';
				}
				
				// Validate National ID
				if (isValid && !/^\d{12}$/.test(formData.national_id)) {
					isValid = false;
					errorMessage = 'National ID must be 12 digits';
				}
				
				// Validate Contact Number
				if (isValid && !/^9\d{10}$/.test(formData.contact)) {
					isValid = false;
					errorMessage = 'Contact must be 11 digits starting with 9';
				}
				
				if (!isValid) {
					Swal.fire({
						title: "Validation Error",
						text: errorMessage,
						icon: "error",
						background: 'rgba(16, 30, 50, 0.95)',
						color: '#f0f4f8'
					});
					submitBtn.innerHTML = originalText;
					submitBtn.disabled = false;
					return;
				}
				
				// Simulate API call (remove this in production)
				await new Promise(resolve => setTimeout(resolve, 2000));
				
				// Success message
				Swal.fire({
					title: "Registration Submitted!",
					html: `
						<div style="text-align: center;">
							<p>Your registration request has been submitted for review.</p>
							<p><strong>Reference ID:</strong> ARTA-${Date.now().toString().slice(-8)}</p>
							<p class="text-muted" style="font-size: 0.9rem; margin-top: 10px;">
								An administrator will review your application within 24-48 hours.
								You will receive an email notification once your account is approved.
							</p>
						</div>
					`,
					icon: "success",
					background: 'rgba(16, 30, 50, 0.95)',
					color: '#f0f4f8',
					confirmButtonText: 'Go to Login'
				}).then(() => {
					// Redirect to login page
					window.location.href = "login.php";
				});
				
			} catch (err) {
				console.error(err);
				Swal.fire({
					title: "System Error",
					text: "Something went wrong. Please try again or contact system administrator.",
					icon: "error",
					background: 'rgba(16, 30, 50, 0.95)',
					color: '#f0f4f8'
				});
			} finally {
				// Reset button state
				submitBtn.innerHTML = originalText;
				submitBtn.disabled = false;
			}
		});
	});
	</script>
</body>

</html>