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

			<div class="d-flex justify-content-end mb-3">
				<button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
					data-bs-target="#addUnitModal"><i class="fa-solid fa-plus me-2"></i>Add New Unit</button>
				<button type="button" class="btn btn-success" data-bs-toggle="modal"
					data-bs-target="#assignmentModal"><i class="fa-solid fa-plus me-2"></i>Assignment Form</button>
			</div>

			<!-- Add Unit Modal -->
			<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addUnitModalLabel">Add New Unit</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="addUnitForm">
								<div class="mb-3">
									<label for="unit_name" class="form-label">Unit Name</label>
									<input type="text" class="form-control" id="unit_name"
										placeholder="Enter Unit Name">
								</div>
								<div class="mb-3">
									<label for="unit_id" class="form-label">Unit ID</label>
									<input type="text" class="form-control" id="unit_id" readonly>
									<small class="text-muted">Note: Auto-generated Unit ID</small>
								</div>
								<button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-2"></i>Save
									Unit</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Assignment Modal -->
			<div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="assignmentModalLabel">Assignment Form</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="assignmentForm">
								<div class="mb-3">
									<label class="form-label">Division List</label>
									<select class="form-select tom-select option-with-checkbox" id="officeList"
										placeholder="Select Offices">
										<option value="office1">Select Office </option>
										<option value="office1">Information and Comunication Technology Division
										</option>
										<option value="office2">General Services Division</option>
										<option value="office2">Internal Audit Division</option>
										<!-- More offices -->
									</select>
								</div>

								<div class="mb-3">
									<label class="form-label">Division List</label>
									<select id="divisionList" multiple placeholder="Select Divisions">
										<option value="division1">Accounting Unit
										</option>
										<option value="division2">Budget Unit</option>
										<!-- More divisions -->
									</select>
								</div>
								<button type="submit" class="btn btn-primary">Save Assignments</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Tom Select CSS & JS -->
			<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
			<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

			<style>
			.ts-dropdown .option {
				display: flex;
				align-items: center;
			}

			.ts-dropdown .option input[type="checkbox"] {
				margin-right: 8px;
			}
			</style>

			<script>
			document.addEventListener("DOMContentLoaded", function() {
				const selectIds = [
					'divisionList'
				];

				selectIds.forEach(function(id) {
					new TomSelect("#" + id, {
						plugins: ['remove_button'],
						hideSelected: true,
						closeAfterSelect: false,
						render: {
							option: function(data, escape) {
								let checked = this.items.includes(data.value) ? 'checked' : '';
								return `
                        <div class="option">
                            <input type="checkbox" ${checked} />
                            <span>${escape(data.text)}</span>
                        </div>
                    `;
							},
							item: function(data, escape) {
								return `<div>${escape(data.text)}</div>`;
							}
						}
					});
				});

				document.getElementById("assignmentForm").addEventListener("submit", function(e) {
					e.preventDefault();
					let result = {};
					selectIds.forEach(function(id) {
						result[id] = TomSelect.instances[id].getValue();
					});
					console.log(result);
					alert("Selections: " + JSON.stringify(result));
				});

				// Auto-generate Unit ID
				document.getElementById("unit_name").addEventListener("input", function() {
					const unitName = this.value.trim();
					const randomNumber = Math.floor(1000 + Math.random() *
						9000); // Generate random 4-digit number
					const unitId = "unit-" + randomNumber;
					document.getElementById("unit_id").value = unitId;
				});

				// SweetAlert for Add Unit Form
				document.getElementById("addUnitForm").addEventListener("submit", function(e) {
					e.preventDefault();
					Swal.fire({
						title: 'Success',
						text: 'Unit added successfully!',
						icon: 'success',
						confirmButtonText: 'OK'
					});
				});
			});
			</script>


			<div class="card shadow-sm border-0 rounded-4">
				<div class="card-body">

					<!-- Search Bar -->
					<div class="row mb-3">
						<div class="col-md-6">
							<h5 class="fw-bold">List of Unit with Assigned Division
							</h5>
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

									<th>UNIT</th>
									<th> ASSIGNED DIVISION</th>
									<th>ACTION</th>
								</tr>
							</thead>
							<tbody id="tableBody">
								<tr>
									<td>Planing Unit</td>
									<td>General Services</td>

									<td>
										<button class="btn btn-sm btn-primary">View</button>
										<button class="btn btn-sm btn-danger">Delete</button>
									</td>
								</tr>
								<tr>
									<td>General and Services</td>
									<td>Property Management Unit</td>
									<td>
										<button class="btn btn-sm btn-primary">View</button>
										<button class="btn btn-sm btn-danger">Delete</button>
									</td>
								</tr>
								<tr>
									<td>Cyber Security Unit</td>
									<td><span class="badge bg-warning text-dark">For Assignment</span></td>
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
	document.getElementById("assignmentForm").addEventListener("submit", function(e) {
		e.preventDefault(); // Prevent page refresh

		// Function to get values from checkboxes in a container
		function getCheckboxValues(containerId) {
			let values = [];
			let checkboxes = document.querySelectorAll(`#${containerId} input[type="checkbox"]:checked`);
			checkboxes.forEach(checkbox => {
				values.push(checkbox.value);
			});
			return values;
		}

		// Get selected values from all checkbox groups
		let userList = getCheckboxValues("userListContainer");
		let officeList = getCheckboxValues("officeListContainer");
		let divisionList = getCheckboxValues("divisionListContainer");
		let unitList = getCheckboxValues("unitListContainer");
		let designationList = getCheckboxValues("designationListContainer");
		let roleList = getCheckboxValues("roleListContainer");

		// You can now process these selected values (e.g., send them to the server)
		console.log("Selected Users:", userList);
		console.log("Selected Offices:", officeList);
		console.log("Selected Divisions:", divisionList);
		console.log("Selected Units:", unitList);
		console.log("Selected Designations:", designationList);
		console.log("Selected Roles:", roleList);

		Swal.fire({
			title: 'Success',
			text: 'Assignments have been saved successfully!',
			icon: 'success',
			confirmButtonText: 'OK'
		});
	});
	</script>

</body>

</html>