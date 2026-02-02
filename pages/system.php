<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<!-- Sidebar -->
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<!-- HEADER -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-building-columns me-2"></i> User Dashboard
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
								placeholder="🔍 Search system...">
							<button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addSystemModal">
								<i class="fa-solid fa-plus me-2"></i>Add New System
							</button>
						</div>
					</div>


					<!-- TABLE -->
					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead>
								<tr>
									<th>System ID</th>
									<th>System Name</th>
									<th>Logo</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tableBody">



							</tbody>
						</table>
						<div id="pagination" class="mt-3 text-center"></div>
					</div>



				</div>
			</div>

		</div>
	</div>

	<!--  ADD MODAL -->
	<div class="modal fade" id="addSystemModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="addSystemForm" enctype="multipart/form-data">
					<div class="modal-header">
						<h5 class="modal-title">Add New System</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">System Name</label>
							<input type="text" name="system_name" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Domain URL</label>
							<input type="url" name="domain_url" class="form-control" placeholder="https://example.com">
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
						<div class="mb-3">
							<label class="form-label">Logo</label>
							<input type="file" name="logo" class="form-control" accept="image/*">
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

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
	document.getElementById("addSystemForm").addEventListener("submit", function(e) {
		e.preventDefault();

		let formData = new FormData(this);

		fetch("../api/systems/create_system.php", { // ✅ singular filename
				method: "POST",
				body: formData
			})
			.then(res => res.json())
			.then(result => {
				console.log("Create Response:", result);

				if (result.status === 200) {
					Swal.fire({
						icon: "success",
						title: "Success",
						text: result.message,
						timer: 1500,
						showConfirmButton: false
					}).then(() => {
						// Close modal
						let modal = bootstrap.Modal.getInstance(document.getElementById(
							"addSystemModal"));
						modal.hide();

						// Reset form
						document.getElementById("addSystemForm").reset();

						// Reload systems table
						loadSystems(1);
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
				console.error("Error:", err);
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Request failed. Please try again."
				});
			});
	});
	</script>


	<!-- ✅ Edit System Modal -->
	<div class="modal fade" id="editSystemModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="editSystemForm" enctype="multipart/form-data">
					<input type="hidden" name="id" id="editSystemId">
					<input type="hidden" name="current_logo" id="editCurrentLogo">

					<div class="modal-header">
						<h5 class="modal-title">Edit System</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>

					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">System Name</label>
							<input type="text" name="system_name" id="editSystemName" class="form-control" required>
						</div>

						<div class="mb-3">
							<label class="form-label">Domain URL</label>
							<input type="url" name="domain_url" id="editSystemURL" class="form-control"
								placeholder="https://example.com">
						</div>

						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="description" id="editSystemDescription" class="form-control"></textarea>
						</div>

						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" id="editSystemStatus" class="form-select">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>

						<div class="mb-3">
							<label class="form-label">Logo (optional)</label>
							<input type="file" name="logo" class="form-control" accept="image/*">
							<small class="text-muted">Leave empty if you don't want to change the logo.</small>
							<div class="mt-2">
								<img id="currentLogoPreview" src="" alt="Current Logo"
									style="max-height:80px; display:none;" class="border p-1">
							</div>
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
	function openEditSystemModal(system) {
		document.getElementById("editSystemId").value = system.id;
		document.getElementById("editSystemName").value = system.system_name;
		document.getElementById("editSystemURL").value = system.domain_url;
		document.getElementById("editSystemDescription").value = system.description;
		document.getElementById("editSystemStatus").value = system.status;
		document.getElementById("editCurrentLogo").value = system.logo_path || "";

		const logoPreview = document.getElementById("currentLogoPreview");
		if (system.logo_path) {
			logoPreview.src = "../" + system.logo_path;
			logoPreview.style.display = "block";
		} else {
			logoPreview.style.display = "none";
		}

		new bootstrap.Modal(document.getElementById("editSystemModal")).show();
	}

	// Preview logo instantly
	document.querySelector('#editSystemForm input[name="logo"]').addEventListener('change', function() {
		const file = this.files[0];
		const preview = document.getElementById("currentLogoPreview");
		if (file) {
			preview.src = URL.createObjectURL(file);
			preview.style.display = "block";
		}
	});

	// Submit update
	document.getElementById("editSystemForm").addEventListener("submit", function(e) {
		e.preventDefault();
		const submitBtn = this.querySelector("button[type='submit']");
		submitBtn.disabled = true;

		const formData = new FormData(this);

		fetch("../api/systems/update_system.php", {
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
						timer: 1500,
						showConfirmButton: false
					}).then(() => {
						bootstrap.Modal.getInstance(document.getElementById("editSystemModal")).hide();
						loadSystems(1);
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

	// Reset form on close
	document.getElementById("editSystemModal").addEventListener('hidden.bs.modal', function() {
		this.querySelector('form').reset();
		document.getElementById("currentLogoPreview").style.display = "none";
	});
	</script>




	<script>
	let currentPage = 1;
	const limit = 5; // records per page

	function loadSystems(page = 1) {
		fetch(`../api/systems/get_systems.php?page=${page}&limit=${limit}`)
			.then(res => res.json())
			.then(result => {
				console.log("API Response:", result); // check structure

				const tableBody = document.getElementById("tableBody");
				tableBody.innerHTML = "";

				if (result.status === 200) {
					const systems = result.data.systems;

					if (!systems || systems.length === 0) {
						tableBody.innerHTML =
							`<tr><td colspan="5" class="text-center text-muted">No systems found</td></tr>`;
						return;
					}

					systems.forEach(system => {
						let row = `
                        <tr class="expandable-row system-row">
                            <td>System-${system.id.toString().padStart(4,'0')}</td>
                            <td>${system.system_name}</td>
                            <td><img src="${system.logo_path || '../img/placeholder.png'}" width="50" class="rounded"></td>
                            <td>
                                <span class="badge ${system.status === "Active" ? "bg-success" : "bg-warning text-dark"}">
                                    ${system.status}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewSystem(${system.id})">View</button>

								<button class="btn btn-sm btn-outline-warning" onclick='openEditSystemModal(${JSON.stringify(system)})'>Edit</button>

								<button class="btn btn-sm btn-outline-danger" onclick="deleteSystem(${system.id})">Delete</button>

                            </td>
                        </tr>
                        <tr class="expandable-content" style="display:none;">
                            <td colspan="5">
                                <div class="expand-body">
                                    <p><strong>Description:</strong> ${system.description ?? "No description"}</p>
                                    <p><strong>Created:</strong> ${new Date(system.created_at).toLocaleString()}</p>
                                    <p><strong>Updated:</strong> ${new Date(system.updated_at).toLocaleString()}</p>
                                </div>
                            </td>
                        </tr>
                    `;
						tableBody.innerHTML += row;
					});

					// pagination
					renderPagination(result.data.currentPage, result.data.totalPages);

					// attach expandable row events
					attachRowEvents();

				} else {
					tableBody.innerHTML =
						`<tr><td colspan="5" class="text-center text-danger">${result.message}</td></tr>`;
				}
			})
			.catch(err => {
				console.error("Error fetching systems:", err);
				tableBody.innerHTML =
					`<tr><td colspan="5" class="text-center text-danger">Failed to fetch systems</td></tr>`;
			});
	}

	function renderPagination(page, totalPages) {
		const pagination = document.getElementById("pagination");
		pagination.innerHTML = "";

		if (totalPages > 1) {
			let prevDisabled = page === 1 ? "disabled" : "";
			let nextDisabled = page === totalPages ? "disabled" : "";

			pagination.innerHTML = `
            <button class="btn btn-sm btn-outline-secondary" ${prevDisabled} onclick="loadSystems(${page - 1})">Prev</button>
            <span class="mx-2">Page ${page} of ${totalPages}</span>
            <button class="btn btn-sm btn-outline-secondary" ${nextDisabled} onclick="loadSystems(${page + 1})">Next</button>
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
		let rows = document.querySelectorAll("#tableBody .system-row");

		rows.forEach(row => {
			let text = row.textContent.toLowerCase();
			row.style.display = text.includes(filter) ? "" : "none";
		});
	});

	// Initial load
	document.addEventListener("DOMContentLoaded", () => {
		loadSystems(currentPage);
	});

	// Dummy functions for View/Delete buttons
	// function viewSystem(id) {
	// 	alert("View system " + id);
	// }

	function deleteSystem(id) {
		if (confirm("Are you sure?")) {
			fetch("../api/systems/delete_system.php", {
					method: "DELETE",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						id
					})
				})
				.then(res => res.json())
				.then(result => {
					alert(result.message);
					loadSystems(currentPage);
				})
				.catch(err => console.error(err));
		}
	}


	// Delete BTN
	function deleteSystem(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: "This will permanently delete the system!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				// Send POST request
				fetch("../api/systems/delete_system.php", {
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
							loadSystems(1); // reload table
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
	</script>



	<!-- 🔍 SEARCH -->
	<script>
	document.getElementById("searchInput").addEventListener("keyup", function() {
		let filter = this.value.toLowerCase();
		let rows = document.querySelectorAll("#tableBody .system-row");

		rows.forEach(row => {
			let text = row.textContent.toLowerCase();
			row.style.display = text.includes(filter) ? "" : "none";
		});
	});
	</script>


	<!-- Bootstrap Bundle with Popper (for Modal, Dropdown, etc.) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<!-- SweetAlert2 (kung gagamit ka) -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- STYLE -->
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

</body>

</html>