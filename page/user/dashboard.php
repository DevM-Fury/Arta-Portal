<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php include('includes/head.php'); ?>

<body>
	<!-- Sidebar -->
	<?php include('includes/sidebar.php'); ?>

	<!-- HEADER BAR -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-building-columns"></i>
			ARTA Portal
		</div>
		<div class="header-user">
			<i class="fa-solid fa-user-circle"></i>
		</div>
	</div>

	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container mt-5">

			<h3 class="mb-4 fw-bold">User Access Management</h3>

			<div class="row g-4">
				<!-- CARD SAMPLE -->
				<div class="col-md-4">
					<div class="card access-card text-center shadow-sm" data-url="http://dms.arta.gov.ph">
						<div class="card-body">
							<img src="../../img/dms.png" class="mb-3" width="80" alt="System Logo">
							<h5 class="card-title fw-bold">DMS</h5>
							<p class="card-text">Click to access System 1</p>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card access-card text-center shadow-sm" data-url="https://arta.gov.ph">
						<div class="card-body">
							<img src="../../img/arta_website.png" class="mb-3" width="80" alt="System Logo">
							<h5 class="card-title fw-bold">System 2</h5>
							<p class="card-text">Click to access System 2</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- SSO Loader -->
	<div id="ssoLoader">
		<div class="loader-circle"></div>
		<p>Authenticating via SSO...</p>
	</div>

	<style>
	/* Main Content Adjust */
	.main-content {
		margin-top: 90px;
		padding: 20px;
	}

	/* Card Styling */
	.access-card {
		cursor: pointer;
		border-radius: 15px;
		transition: 0.3s;
	}

	.access-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
	}

	/* SSO Loader */
	#ssoLoader {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255, 255, 255, 0.95);
		z-index: 9999;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	#ssoLoader p {
		font-size: 18px;
		margin-top: 15px;
		font-weight: 600;
		color: #333;
	}

	.loader-circle {
		border: 6px solid #e0e0e0;
		border-top: 6px solid #4e73df;
		border-radius: 50%;
		width: 70px;
		height: 70px;
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
	</style>

	<script>
	// On card click, show loader then redirect
	document.querySelectorAll(".access-card").forEach(card => {
		card.addEventListener("click", function() {
			const url = this.getAttribute("data-url");
			document.getElementById("ssoLoader").style.display = "flex";
			setTimeout(() => {
				window.location.href = url;
			}, 2500); // delay para makita ung loader
		});
	});
	</script>
</body>

</html>