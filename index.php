<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ARTA Portal</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

	<style>
	/* Global */
	body {
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		scroll-behavior: smooth;
		color: #333;
		line-height: 1.6;
		background: #f9fafb;
	}

	section {
		padding: 80px 0;
	}

	h2.section-title {
		font-size: 2.4rem;
		font-weight: 700;
		text-align: center;
		margin-bottom: 40px;
		color: #161387;
		position: relative;
	}

	h2.section-title::after {
		content: "";
		width: 80px;
		height: 4px;
		background: #2d7065;
		display: block;
		margin: 12px auto 0;
		border-radius: 2px;
	}

	/* Navbar */
	.navbar {
		background: #1a1a2e;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
	}

	.navbar-brand {
		font-weight: bold;
		font-size: 1.7rem;
		color: #fff !important;
	}

	.navbar-nav .nav-link {
		color: #ddd !important;
		margin: 0 12px;
		font-weight: 500;
		position: relative;
	}

	.navbar-nav .nav-link::after {
		content: "";
		position: absolute;
		left: 0;
		bottom: -4px;
		width: 0;
		height: 2px;
		background: #7bfafa;
		transition: 0.3s;
	}

	.navbar-nav .nav-link:hover::after {
		width: 100%;
	}

	/* Hero */
	.hero {
		background: linear-gradient(rgba(26, 26, 46, 0.85), rgba(26, 26, 46, 0.85)),
			url('https://images.unsplash.com/photo-1521791055366-0d553872125f') center/cover no-repeat;
		color: #fff;
		text-align: center;
		padding: 160px 20px;
	}

	.hero h1 {
		font-size: 3.2rem;
		font-weight: 700;
	}

	.hero p {
		font-size: 1.25rem;
		margin: 20px 0 30px;
	}

	.btn-main {
		background: linear-gradient(135deg, #6366f1, #2d7065);
		color: #fff;
		font-weight: 600;
		padding: 12px 30px;
		border-radius: 30px;
		transition: 0.3s;
		border: none;
	}

	.btn-main:hover {
		transform: translateY(-3px);
		box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
	}

	/* Process Section */
	.card {
		border: none;
		border-radius: 15px;
		transition: 0.3s;
		box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
	}

	.card:hover {
		transform: translateY(-10px);
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
	}

	.icon-box i {
		font-size: 2.2rem;
		color: #161387;
		margin-bottom: 15px;
	}

	/* Contact Section */
	#contact {
		background: linear-gradient(135deg, #f9f9f9, #eefdfb);
	}

	#contact form {
		background: #fff;
		border-radius: 15px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
		transition: 0.3s;
	}

	#contact .form-control {
		border-radius: 10px;
		transition: 0.3s;
	}

	#contact .form-control:focus {
		border-color: #6366f1;
		box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
	}

	#contact button {
		border-radius: 10px;
		font-weight: 600;
		background: #040651ff;
		border: none;
	}

	#contact button:hover {
		background: #2d7065;
	}

	/* Footer */
	footer {
		background: linear-gradient(to right, #05051cff, #00000038);
		color: #bbb;
		text-align: center;
		padding: 30px 15px;
		margin-top: 30px;
	}

	footer a {
		color: #055c5cff;
		margin: 0 12px;
		font-size: 1.3rem;
		transition: 0.3s;
	}

	footer a:hover {
		color: #fff;
		transform: scale(1.2);
	}

	/* Loader (Filament-like) */
	#loader {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: #f9fafb;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		z-index: 9999;
		transition: opacity 0.6s ease, visibility 0.6s ease;
	}

	.spinner {
		width: 70px;
		height: 70px;
		border: 6px solid #e5e7eb;
		border-top: 6px solid #6366f1;
		border-radius: 50%;
		animation: spin 1s linear infinite;
		position: relative;
	}

	.spinner::after {
		content: "";
		position: absolute;
		top: 10px;
		left: 10px;
		width: 50px;
		height: 50px;
		border: 4px solid transparent;
		border-top: 4px solid #2d7065;
		border-radius: 50%;
		animation: spin 1.5s linear infinite reverse;
	}

	.loading-text {
		margin-top: 18px;
		font-size: 1.1rem;
		font-weight: 600;
		background: linear-gradient(90deg, #06072cff, #2d7065, #03042fff);
		background-size: 200% 100%;
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		animation: shimmer 2s infinite;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}

	@keyframes shimmer {
		0% {
			background-position: -200% 0;
		}

		100% {
			background-position: 200% 0;
		}
	}

	#loader.hidden {
		opacity: 0;
		visibility: hidden;
	}
	</style>
</head>

<body>
	<!-- Loader -->
	<div id="loader">
		<div class="spinner"></div>
		<div class="loading-text">Loading ARTA Portal...</div>
	</div>

	<!-- NAVBAR -->
	<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#">ARTA Portal</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="#about">About</a></li>
					<li class="nav-item"><a class="nav-link" href="#process">Process</a></li>
					<!-- <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li> -->
				</ul>
			</div>
		</div>
	</nav>

	<!-- HERO -->
	<section id="home" class="hero">
		<div class="container">
			<h1>Welcome to ARTA Portal</h1>
			<p>Streamlining processes, empowering businesses, and enabling seamless services.</p>
			<a href="start.php" class="btn btn-main">Login</a>
		</div>
	</section>

	<!-- ABOUT -->
	<section class="bg-light" id="about">
		<div class="container">
			<h2 class="section-title">About Us</h2>
			<div class="row justify-content-center">
				<div class="col-md-8 text-center">
					<p class="lead">ARTA Portal is a centralized system designed to streamline applications,
						registrations, and compliance processes for various establishments. Our mission is to provide a
						fast, reliable, and transparent service for businesses and government agencies.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- PROCESS -->
	<section id="process">
		<div class="container">
			<h2 class="section-title">Our Process</h2>
			<div class="row g-4">
				<div class="col-md-3">
					<div class="card text-center p-4">
						<div class="icon-box"><i class="fa-solid fa-file-circle-plus"></i></div>
						<h5 class="fw-bold">Step 1</h5>
						<p>Register and submit the necessary application documents.</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card text-center p-4">
						<div class="icon-box"><i class="fa-solid fa-spinner"></i></div>
						<h5 class="fw-bold">Step 2</h5>
						<p>Our system verifies and processes your application securely.</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card text-center p-4">
						<div class="icon-box"><i class="fa-solid fa-user-check"></i></div>
						<h5 class="fw-bold">Step 3</h5>
						<p>Officials review and approve your submitted documents.</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card text-center p-4">
						<div class="icon-box"><i class="fa-solid fa-circle-check"></i></div>
						<h5 class="fw-bold">Step 4</h5>
						<p>Receive confirmation and your official registration status.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- CONTACT -->
	<!-- <section id="contact">
		<div class="container">
			<h2 class="section-title">Contact Us</h2>
			<div class="row">
				<div class="col-md-6 mb-4">
					<h5>Get in Touch</h5>
					<p>If you have any questions, feel free to reach out to us through the following channels:</p>
					<p class="contact-info"><i class="fa-solid fa-envelope"></i> support@artaportal.com</p>
					<p class="contact-info"><i class="fa-solid fa-phone"></i> +63 912 345 6789</p>
					<p class="contact-info"><i class="fa-solid fa-location-dot"></i> Quezon City, Philippines</p>
				</div>
				<div class="col-md-6">
					<form class="p-4">
						<div class="mb-3">
							<label class="form-label">Full Name</label>
							<input type="text" class="form-control" placeholder="Enter your name">
						</div>
						<div class="mb-3">
							<label class="form-label">Email</label>
							<input type="email" class="form-control" placeholder="Enter your email">
						</div>
						<div class="mb-3">
							<label class="form-label">Message</label>
							<textarea class="form-control" rows="4" placeholder="Type your message"></textarea>
						</div>
						<button type="submit" class="btn btn-primary w-100">Send Message</button>
					</form>
				</div>
			</div>
		</div>
	</section> -->

	<!-- FOOTER -->
	<footer>
		<p>&copy; 2025 ARTA Portal. All Rights Reserved.</p>
		<p>
			<a href="#"><i class="fab fa-facebook"></i></a>
			<a href="#"><i class="fab fa-twitter"></i></a>
			<a href="#"><i class="fab fa-linkedin"></i></a>
		</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script>
	window.addEventListener("load", function() {
		const loader = document.getElementById("loader");
		setTimeout(() => {
			loader.classList.add("hidden");
		}, 800);
	});
	</script>
</body>

</html>