<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<!-- Sidebar -->
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<!-- HEADER -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-building-columns me-2"></i> LIST OF OFFICE
		</div>

		<div class="header-user dropdown">
			<i class="fa-solid fa-user dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
				aria-expanded="false"></i>
			<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
				<li><a class="dropdown-item" href="#">Profile Settings</a></li>
				<li>
					<hr class="dropdown-divider">
				</li>
				<li><a class="dropdown-item" href="#">Logout</a></li>
			</ul>
		</div>
	</div>

	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">

			<!-- CARD -->
			<div class="card shadow-lg border-0 rounded-3 mx-auto"
				style="width: 100%; max-width: 1800px; min-height: 85vh;">
				<div class="card-body p-4">

					<!-- Header Section -->
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
						<div class="d-flex gap-2 w-100 w-md-auto">
							<input type="text" id="searchInput"
								class="form-control shadow-sm border-primary flex-grow-1"
								placeholder="🔍 Search office...">
							<button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addOfficeModal">
								<i class="fa-solid fa-plus me-2"></i>Add New Office
							</button>
						</div>
					</div>

					<!-- TABLE -->
					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead>
								<tr>
									<th>Office ID</th>
									<th>Office Name</th>
									<th>Description</th>
									<th>Status</th>
									<th>Created</th>
									<th>Modified</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tableBody"></tbody>
						</table>
						<div id="pagination" class="mt-3 text-center"></div>
					</div>

				</div>
			</div>

		</div>
	</div>

	<!-- ADD MODAL -->
	<div class="modal fade" id="addOfficeModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="addOfficeForm">
					<div class="modal-header">
						<h5 class="modal-title">Add New Office</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Office Name</label>
							<input type="text" name="office_name" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="description" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" class="form-select">
								<option value="Active" selected>Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- EDIT MODAL -->
	<div class="modal fade" id="editOfficeModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="editOfficeForm">
					<input type="hidden" name="id" id="editOfficeId">

					<div class="modal-header">
						<h5 class="modal-title">Edit Office</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>

					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Office Name</label>
							<input type="text" name="office_name" id="editOfficeName" class="form-control" required>
						</div>

						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="description" id="editOfficeDescription" class="form-control"></textarea>
						</div>

						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" id="editOfficeStatus" class="form-select">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
	// CREATE
	document.getElementById("addOfficeForm").addEventListener("submit", function(e) {
		e.preventDefault();
		let form = this;
		let formData = new FormData(form);

		fetch("../api/offices/create_office.php", {
				method: "POST",
				body: formData
			})
			.then(res => res.json())
			.then(result => {
				if (result.status === 200) {
					Swal.fire({
							icon: "success",
							title: "Success",
							text: result.message,
							timer: 1400,
							showConfirmButton: false
						})
						.then(() => {
							bootstrap.Modal.getInstance(document.getElementById("addOfficeModal")).hide();
							form.reset();
							loadOffices(1);
						});
				} else {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: result.message || "Something went wrong"
					});
				}
			})
			.catch(err => {
				console.error(err);
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Request failed. Please try again."
				});
			});
	});

	// OPEN EDIT
	function openEditOfficeModal(office) {
		document.getElementById("editOfficeId").value = office.id;
		document.getElementById("editOfficeName").value = office.office_name;
		document.getElementById("editOfficeDescription").value = office.description;
		document.getElementById("editOfficeStatus").value = office.status;
		new bootstrap.Modal(document.getElementById("editOfficeModal")).show();
	}

	// UPDATE
	document.getElementById("editOfficeForm").addEventListener("submit", function(e) {
		e.preventDefault();
		const submitBtn = this.querySelector("button[type='submit']");
		submitBtn.disabled = true;

		const formData = new FormData(this);

		fetch("../api/offices/update_office.php", {
				method: "POST",
				body: formData
			})
			.then(res => res.json())
			.then(result => {
				if (result.status === 200) {
					Swal.fire({
							icon: "success",
							title: "Updated!",
							text: result.message,
							timer: 1400,
							showConfirmButton: false
						})
						.then(() => {
							bootstrap.Modal.getInstance(document.getElementById("editOfficeModal")).hide();
							loadOffices(1);
						});
				} else {
					Swal.fire({
						icon: "error",
						title: "Error",
						text: result.message
					});
				}
			})
			.catch(() => {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Request failed. Please try again."
				});
			})
			.finally(() => submitBtn.disabled = false);
	});

	// DELETE
	function deleteOffice(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: "This will permanently delete the office!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				fetch("../api/offices/delete_office.php", {
						method: "POST",
						headers: {
							"Content-Type": "application/x-www-form-urlencoded"
						},
						body: `id=${id}`
					})
					.then(res => res.json())
					.then(resp => {
						if (resp.status === 200) {
							Swal.fire("Deleted!", resp.message, "success");
							loadOffices(1);
						} else {
							Swal.fire("Error!", resp.message, "error");
						}
					})
					.catch(err => {
						console.error(err);
						Swal.fire("Error!", "Request failed.", "error");
					});
			}
		});
	}

	// LOAD (READ)
	let currentPage = 1;
	const limit = 5;

	function loadOffices(page = 1) {
		fetch(`../api/offices/get_offices.php?page=${page}&limit=${limit}`)
			.then(res => res.json())
			.then(result => {
				const tableBody = document.getElementById("tableBody");
				tableBody.innerHTML = "";

				if (result.status === 200) {
					const offices = result.data.offices || [];

					if (offices.length === 0) {
						tableBody.innerHTML =
							`<tr><td colspan="7" class="text-center text-muted">No offices found</td></tr>`;
						renderPagination(1, 1);
						return;
					}

					offices.forEach(office => {
						let row = `
                            <tr class="expandable-row office-row">
                                <td>OFF-${String(office.id).padStart(4,'0')}</td>
                                <td>${office.office_name}</td>
                                <td>${office.description ?? ''}</td>
                                <td>
                                    <span class="badge ${office.status === "Active" ? "bg-success" : "bg-warning text-dark"}">
                                        ${office.status}
                                    </span>
                                </td>
                                <td>${office.created_at ? new Date(office.created_at).toLocaleString() : ''}</td>
                                <td>${office.updated_at ? new Date(office.updated_at).toLocaleString() : ''}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewOffice(${office.id})">View</button>
                                    <button class="btn btn-sm btn-outline-warning" onclick='openEditOfficeModal(${JSON.stringify(office)})'>Edit</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteOffice(${office.id})">Delete</button>
                                </td>
                            </tr>
                            <tr class="expandable-content" style="display:none;">
                                <td colspan="7">
                                    <div class="expand-body">
                                        <p><strong>Description:</strong> ${office.description ?? "No description"}</p>
                                        <p><strong>Created:</strong> ${office.created_at ? new Date(office.created_at).toLocaleString() : ''}</p>
                                        <p><strong>Updated:</strong> ${office.updated_at ? new Date(office.updated_at).toLocaleString() : ''}</p>
                                    </div>
                                </td>
                            </tr>
                        `;
						tableBody.innerHTML += row;
					});

					renderPagination(result.data.currentPage, result.data.totalPages);
					attachRowEvents();

				} else {
					tableBody.innerHTML =
						`<tr><td colspan="7" class="text-center text-danger">${result.message}</td></tr>`;
				}
			})
			.catch(err => {
				console.error("Error fetching offices:", err);
				document.getElementById("tableBody").innerHTML =
					`<tr><td colspan="7" class="text-center text-danger">Failed to fetch offices</td></tr>`;
			});
	}

	function renderPagination(page, totalPages) {
		const pagination = document.getElementById("pagination");
		pagination.innerHTML = "";
		if (totalPages > 1) {
			let prevDisabled = page === 1 ? "disabled" : "";
			let nextDisabled = page === totalPages ? "disabled" : "";
			pagination.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary" ${prevDisabled} onclick="loadOffices(${page - 1})">Prev</button>
                <span class="mx-2">Page ${page} of ${totalPages}</span>
                <button class="btn btn-sm btn-outline-secondary" ${nextDisabled} onclick="loadOffices(${page + 1})">Next</button>
            `;
		}
	}

	// Expandable rows
	function attachRowEvents() {
		const rows = document.querySelectorAll('.expandable-row');
		rows.forEach(row => {
			row.addEventListener('click', () => {
				row.classList.toggle('active');
				const nextRow = row.nextElementSibling;
				if (nextRow && nextRow.classList.contains('expandable-content')) {
					if (nextRow.style.display === 'table-row') {
						nextRow.querySelector('.expand-body').style.maxHeight = '0px';
						setTimeout(() => nextRow.style.display = 'none', 300);
					} else {
						nextRow.style.display = 'table-row';
						setTimeout(() => nextRow.querySelector('.expand-body').style.maxHeight = '300px',
							10);
					}
				}
			});
		});
	}

	// Search filter
	document.getElementById("searchInput").addEventListener("keyup", function() {
		let filter = this.value.toLowerCase();
		let rows = document.querySelectorAll("#tableBody .office-row");
		rows.forEach(row => {
			let text = row.textContent.toLowerCase();
			row.style.display = text.includes(filter) ? "" : "none";
		});
	});

	// initial load
	document.addEventListener("DOMContentLoaded", () => {
		loadOffices(currentPage);
	});

	// dummy view
	function viewOffice(id) {
		Swal.fire('View', 'Open details for office ID: ' + id, 'info');
	}
	</script>

	<!-- Bootstrap Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<style>
	:root {
		--arta-red: #8B0000;
		--arta-light: #fff8e1;
		--arta-gray: #f7f7f7;
		--arta-blue1: #23395d;
		--arta-blue2: #1e2a3a;
		--arta-blue3: #0f2027;
	}

	body {
		background-color: var(--arta-gray);
		font-family: 'Poppins', sans-serif;
		color: #333;
	}

	.card {
		background: #fff;
		border-radius: 16px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
		overflow: hidden;
	}

	/* Table Styles */
	.table {
		width: 100%;
		min-width: 100%;
		table-layout: fixed;
		border-collapse: collapse;
		border-spacing: 0;
		background-color: #fff;
		border-radius: 8px;
		overflow: hidden;
	}


	.table thead {
		background: var(--arta-blue3);
		color: #fff;
		text-transform: uppercase;
	}

	.table thead th {
		font-size: 14px;
		letter-spacing: 0.5px;
		padding: 14px;
		border: none !important;
	}

	.table tbody td {
		padding: 12px;
		font-size: 14px;
		vertical-align: middle;
	}

	.table tbody tr:nth-child(even) {
		background-color: var(--arta-light);
	}

	.table tbody tr:hover {
		background-color: rgba(15, 32, 39, 0.08);
		transition: 0.2s;
	}

	.expandable-row {
		cursor: pointer;
	}

	.expandable-row.active {
		background-color: rgba(15, 32, 39, 0.1);
	}

	.expandable-content {
		display: none;
		background-color: #f9f9f9;
	}

	.expand-body {
		text-align: left;
		padding: 18px 24px;
		background: #ffffff;
		border-left: 4px solid var(--arta-blue1);
		border-radius: 8px;
		box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.05);
		font-size: 14px;
		line-height: 1.6;
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.3s ease-in-out;
	}

	.expandable-row.active+.expandable-content .expand-body {
		max-height: 300px;
	}

	#searchInput {
		border-radius: 6px;
		padding: 6px 10px;
		font-size: 14px;
		width: 250px;
		transition: all 0.3s ease;
	}

	@media (max-width: 768px) {
		#searchInput {
			width: 100%;
			padding: 8px 12px;
		}
	}


	#searchInput:focus {
		border-color: var(--arta-blue1);
		box-shadow: 0 0 0 0.2rem rgba(35, 57, 93, 0.25);
	}

	.btn-primary {
		background-color: var(--arta-blue1);
		border: none;
	}

	.table thead th {
		background-color: var(--arta-blue3);
		color: #ffffff;
		border: none !important;
		padding: 14px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		font-size: 14px;
	}


	.btn-primary:hover {
		background-color: var(--arta-blue3);
	}

	h4.fw-bold {
		color: var(--arta-blue1);
	}
	</style>

	<!-- ✅ Footer include -->
	<?php include_once __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>