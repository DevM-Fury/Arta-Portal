<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>User Profile</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	.profile-header {
		display: flex;
		align-items: center;
		gap: 15px;
		margin-bottom: 20px;
	}

	.profile-header img {
		width: 80px;
		height: 80px;
		object-fit: cover;
		border-radius: 50%;
		border: 3px solid #ddd;
	}
	</style>
</head>

<?php include "includes/head.php"; ?>

<body class="p-4">
	<?php include "includes/sidebar.php"; ?>

	<!-- HEADER BAR -->
	<div class="header-bar d-flex justify-content-between align-items-center mb-4">
		<div class="header-title">
			<i class="fa-solid fa-building-columns"></i> ARTA Portal
		</div>
		<div class="header-user">
			<i class="fa-solid fa-user-circle"></i> Admin
		</div>
	</div>

	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container">
			<div class="card shadow-sm border-0 rounded-4">
				<div class="card-body">

					<!-- Profile Header -->
					<div class="profile-header">
						<img src="../../img/profile.jpg" alt="User Avatar">
						<div>
							<h4 class="mb-0">Mark Abayan</h4>
							<small class="text-muted">My Profile</small>
						</div>
					</div>

					<hr>

					<!-- Profile Information Form -->
					<h5 class="mb-3">Profile Information</h5>
					<form method="POST" action="">
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">First Name</label>
								<input type="text" class="form-control" name="first_name" value="Mark" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Last Name</label>
								<input type="text" class="form-control" name="last_name" value="Abayan" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Email</label>
								<input type="email" class="form-control" name="email" value="mark.abayan@email.com"
									required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Contact Number</label>
								<input type="text" class="form-control" name="contact" value="09171234567">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Role</label>
								<select class="form-select" name="role">
									<option>Admin</option>
									<option selected>User</option>
									<option>Reviewer</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">Password</label>
								<input type="password" class="form-control" name="password"
									placeholder="Change password">
							</div>
						</div>

						<button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
					</form>

					<hr class="my-5">

					<!-- System Access Table -->
					<h5 class="mb-3">System Access</h5>
					<table class="table table-bordered">
						<thead class="table-light">
							<tr>
								<th>System</th>
								<th>Access Level</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// Example static system access (replace with DB query)
							$systems = [
								['system'=>'ARTA','level'=>'Full Access'],
								['system'=>'E-KYC','level'=>'Read Only'],
								['system'=>'MCP','level'=>'No Access'],
							];

							if (count($systems) > 0) {
								foreach($systems as $s){
									echo "<tr><td>{$s['system']}</td><td>{$s['level']}</td></tr>";
								}
							} else {
								echo "<tr><td colspan='2' class='text-center text-muted'>No system access assigned</td></tr>";
							}
							?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>

</body>

</html>