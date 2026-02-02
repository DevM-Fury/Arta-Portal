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
				<div class="col-md-4">
					<div class="card p-4 text-center">
						<div class="card-icon"><i class="fa-solid fa-users"></i></div>
						<h5 class="card-title">Total Users</h5>
						<p class="display-6">128</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card p-4 text-center">
						<div class="card-icon"><i class="fa-solid fa-users"></i></div>
						<h5 class="card-title">Assigned System Admin</h5>
						<p class="display-6">12</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card p-4 text-center">
						<div class="card-icon"><i class="fa-solid fa-building"></i></div>
						<h5 class="card-title">System</h5>
						<p class="display-6">10</p>
					</div>
				</div>
			</div>

			<!-- Next Row -->
			<div class="row mt-5">
				<div class="col-md-4">
					<div class="card p-4 text-center">
						<div class="card-icon"><i class="fa-solid fa-building"></i></div>
						<h5 class="card-title">Office</h5>
						<p class="display-6">4</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card p-4 text-center">
						<div class="card-icon"><i class="fa-solid fa-building"></i></div>
						<h5 class="card-title">Division</h5>
						<p class="display-6">12</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card p-4 text-center">
						<div class="card-icon"><i class="fa-solid fa-building"></i></div>
						<h5 class="card-title">Unit</h5>
						<p class="display-6">10</p>
					</div>
				</div>
			</div>

			<!-- Next Row -->
			<div class="row mt-5">
				<div class="col-12">
					<div class="card p-4">
						<h5 class="card-title mb-3"><i class="fa-solid fa-list me-2"></i>Recent Activities</h5>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">User <b>Juan Dela Cruz</b> verified his account.</li>
							<li class="list-group-item">New registration: <b>Maria Santos</b>.</li>
							<li class="list-group-item">Pending request from <b>Pedro Reyes</b>.</li>
							<li class="list-group-item">Admin approved <b>3</b> accounts.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>