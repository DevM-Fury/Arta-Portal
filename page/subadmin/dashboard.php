<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php include ('includes/head.php');?>

<body>
	<!-- Sidebar -->
	<?php include ('includes/sidebar.php');?>
	<!-- HEADER BAR -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-building-columns"></i>
			ARTA Portal
		</div>
		<div class="header-user">
			<i class="fa-solid fa-user-circle"></i>
			Admin
		</div>
	</div>

	<div class="main-content">
		<div class="container-fluid">
			<div class="row g-4">
				<!-- AMS Card -->
				<div class="col-md-4">
					<div class="card p-4 text-center shadow-sm border-0 rounded-4">
						<div class="card-icon"><i class="fa-solid fa-users fa-2x mb-2"></i></div>
						<h5 class="card-title">AMS Total Access</h5>
						<p class="display-6">128</p>
					</div>
				</div>
			</div>

			<br><br>
			<p class="display-12">System that you have access too!</p>
			<!-- NEXT ROW for external links -->
			<div class="row g-4 mt-2">
				<!-- ARTA.GOV.PH Card -->
				<div class="col-md-2 col-sm-4 col-6">
					<a href="https://arta.gov.ph" target="_blank" class="text-decoration-none text-dark">
						<div class="card p-3 text-center shadow-sm border-0 rounded-4 hover-card small-card">
							<img src="../../img/arta_website.png" alt="ARTA Logo" class="mb-2"
								style="width:40px; height:auto;">
							<h6 class="card-title">ARTA</h6>
						</div>
					</a>
				</div>

				<!-- DMS.ARATA.GOV.PH Card -->
				<div class="col-md-2 col-sm-4 col-6">
					<a href="http://dms.arta.gov.ph" target="_blank" class="text-decoration-none text-dark">
						<div class="card p-3 text-center shadow-sm border-0 rounded-4 hover-card small-card">
							<img src="../../img/dms.png" alt="DMS Logo" class="mb-2" style="width:40px; height:auto;">
							<h6 class="card-title">DMS</h6>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>

	<style>
	.hover-card {
		transition: transform 0.2s, box-shadow 0.2s;
	}

	.hover-card:hover {
		transform: translateY(-5px);
		box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
	}

	.small-card {
		min-height: 120px;
	}

	.small-card h6 {
		font-size: 14px;
		margin: 0;
	}
	</style>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>