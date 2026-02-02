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

			<!-- Add Office Form -->
			<div class="card p-4 mb-4">
				<h5 class="card-title mb-3"><i class="fa-solid fa-plus me-2"></i>Add New Designation</h5>
				<form class="row g-3">
					<div class="col-md-3">
						<label for="designation_id" class="form-label">Designation ID</label>
						<input type="text" class="form-control" id="designation_id" placeholder="Enter Designation ID">
					</div>
					<div class="col-md-5">
						<label for="designation_name" class="form-label">Designation Name</label>
						<input type="text" class="form-control" id="designation_name"
							placeholder="Enter Designation Name">
					</div>
					<div class="col-md-4 d-flex align-items-end">
						<button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save me-2"></i>Save
							Designation</button>
					</div>
				</form>
			</div>


			<div class="card shadow-sm border-0 rounded-4">
				<div class="card-body">

					<!-- Search Bar -->
					<div class="row mb-3">
						<div class="col-md-6">
							<h5 class="fw-bold">Designation List</h5>
						</div>
						<div class="col-md-6 text-end">
							<input type="text" id="searchInput" class="form-control" placeholder="Search...">
						</div>
					</div>

					<!-- Table -->
					<div class="table-responsive">
						<table class="table table-hover align-middle">
							<thead class="table-primary">
								<tr>

									<th>Designation ID</th>
									<th>Designation Name</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tableBody">
								<tr>
									<td>Designation-001</td>
									<td>Information System Analyst I</td>

									<td><span class="badge bg-success">Contract of Service</span></td>
									<td>
										<button class="btn btn-sm btn-primary">View</button>
										<button class="btn btn-sm btn-danger">Delete</button>
									</td>
								</tr>
								<tr>
									<td>Designation-002</td>
									<td>Information Technology Officer I</td>
									<td><span class="badge bg-primary ">Permanent Position</span></td>
									<td>
										<button class="btn btn-sm btn-primary">View</button>
										<button class="btn btn-sm btn-danger">Delete</button>
									</td>
								</tr>
								<!-- More rows here -->
							</tbody>
						</table>
					</div>

					<!-- Pagination -->
					<nav>
						<ul class="pagination justify-content-center">
							<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
							<li class="page-item active"><a class="page-link" href="#">1</a></li>
							<li class="page-item"><a class="page-link" href="#">2</a></li>
							<li class="page-item"><a class="page-link" href="#">3</a></li>
							<li class="page-item"><a class="page-link" href="#">Next</a></li>
						</ul>
					</nav>

				</div>
			</div>


			<!-- JS for Search -->
			<script>
			document.getElementById("searchInput").addEventListener("keyup", function() {
				let filter = this.value.toLowerCase();
				let rows = document.querySelectorAll("#tableBody tr");
				rows.forEach(row => {
					let text = row.textContent.toLowerCase();
					row.style.display = text.includes(filter) ? "" : "none";
				});
			});
			</script>


		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
	document.querySelector("form").addEventListener("submit", function(e) {
		e.preventDefault(); // Para hindi agad mag-refresh ang page

		// Kunin values (optional validation)
		let officeID = document.getElementById("office_id").value.trim();
		let officeName = document.getElementById("office_name").value.trim();

		if (officeID === "" || officeName === "") {
			Swal.fire({
				title: 'Error',
				text: 'Please fill in all required fields.',
				icon: 'error',
				confirmButtonText: 'OK'
			});
			return;
		}

		// Kapag OK ang inputs, mag SweetAlert muna
		Swal.fire({
			title: 'Success',
			text: 'Office has been saved successfully!',
			icon: 'success',
			confirmButtonText: 'OK'
		}).then(() => {
			// Kung gusto mo i-submit sa backend:
			e.target.submit();
		});
	});
	</script>


</body>

</html>