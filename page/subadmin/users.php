<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>User Access Management</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	/* Dropdown with checkboxes */
	.multiselect {
		position: relative;
	}

	.multiselect .selectBox {
		position: relative;
	}

	.multiselect .selectBox select {
		width: 100%;
		font-weight: bold;
	}

	.multiselect .checkboxes {
		display: none;
		border: 1px #dadada solid;
		position: absolute;
		background: #fff;
		width: 100%;
		z-index: 1;
		max-height: 200px;
		overflow-y: auto;
	}

	.multiselect .checkboxes label {
		display: block;
		padding: 5px;
		cursor: pointer;
	}

	.multiselect .checkboxes label:hover {
		background-color: #f1f1f1;
	}
	</style>
</head>

<?php include "includes/head.php"; ?>

``

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
					<h2 class="mb-4">User Access Management</h2>

					<!-- Assign Access Form -->
					<form method="POST" action="">
						<div class="mb-3">
							<label class="form-label">System</label>
							<select name="system" class="form-select" required>
								<option value="">-- Select System --</option>
								<option value="ARTA">ARTA</option>
								<option value="E-KYC">E-KYC</option>
								<option value="MCP">MCP</option>
							</select>
						</div>

						<div class="mb-3">
							<label class="form-label">Assign to Users</label>
							<div class="multiselect">
								<div class="selectBox" onclick="toggleCheckboxes()">
									<select class="form-select">
										<option>Select Users</option>
									</select>
								</div>
								<div class="checkboxes border rounded p-2">
									<label><input type="checkbox" id="selectAll"> <strong>Select All
											Users</strong></label>
									<hr>
									<!-- Example Users (should be fetched dynamically from DB) -->
									<label><input type="checkbox" name="users[]" value="1"> Juan Dela Cruz</label>
									<label><input type="checkbox" name="users[]" value="2"> Maria Santos</label>
									<label><input type="checkbox" name="users[]" value="3"> Pedro Reyes</label>
									<label><input type="checkbox" name="users[]" value="4"> Ana Lopez</label>
								</div>
							</div>
						</div>

						<button type="submit" name="assign" class="btn btn-primary">Assign Access</button>
					</form>

					<hr class="my-5">

					<!-- Filter & Table -->
					<h4>Assigned Access</h4>
					<form method="GET" class="row g-2 mb-3">
						<div class="col-md-4">
							<select name="filter_system" class="form-select" onchange="this.form.submit()">
								<option value="">-- Filter by System --</option>
								<option value="ARTA"
									<?= isset($_GET['filter_system']) && $_GET['filter_system']=="ARTA" ? "selected":"" ?>>
									ARTA</option>
								<option value="E-KYC"
									<?= isset($_GET['filter_system']) && $_GET['filter_system']=="E-KYC" ? "selected":"" ?>>
									E-KYC</option>
								<option value="MCP"
									<?= isset($_GET['filter_system']) && $_GET['filter_system']=="MCP" ? "selected":"" ?>>
									MCP</option>
							</select>
						</div>
					</form>

					<table class="table table-bordered">
						<thead class="table-light">
							<tr>
								<th>User</th>
								<th>System</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// Example static assignments (replace with DB query)
							$assignments = [
								['user'=>'Juan Dela Cruz','system'=>'ARTA'],
								['user'=>'Maria Santos','system'=>'E-KYC'],
								['user'=>'Pedro Reyes','system'=>'MCP'],
								['user'=>'Ana Lopez','system'=>'ARTA'],
							];

							if (!empty($_GET['filter_system'])) {
								$assignments = array_filter($assignments,function($a){
									return $a['system']===$_GET['filter_system'];
								});
							}

							if (count($assignments) > 0) {
								foreach($assignments as $a){
									echo "<tr><td>{$a['user']}</td><td>{$a['system']}</td></tr>";
								}
							} else {
								echo "<tr><td colspan='2' class='text-center text-muted'>No records found</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script>
	let expanded = false;

	function toggleCheckboxes() {
		let checkboxes = document.querySelector(".checkboxes");
		checkboxes.style.display = expanded ? "none" : "block";
		expanded = !expanded;
	}

	// Select all functionality
	document.getElementById("selectAll").addEventListener("change", function() {
		let checkboxes = document.querySelectorAll('input[name="users[]"]');
		checkboxes.forEach(cb => cb.checked = this.checked);
	});
	</script>
</body>

</html>