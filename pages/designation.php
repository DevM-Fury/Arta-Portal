<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<div class="header-bar">
		<div class="header-title"><i class="fa-solid fa-id-badge me-2"></i> Designation Dashboard</div>
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

	<div class="main-content">
		<div class="container-fluid">
			<div class="card shadow-lg border-0 rounded-3 mx-auto" style="width:100%;max-width:1800px;min-height:80vh;">
				<div class="card-body p-4">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
						<div class="d-flex gap-2 w-100 w-md-auto">
							<input id="searchInput" class="form-control shadow-sm border-primary flex-grow-1"
								placeholder="🔍 Search designation...">
							<button class="btn btn-primary shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addDesignationModal">
								<i class="fa-solid fa-plus me-2"></i>Add New Designation
							</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead class="table-dark">
								<tr>
									<th>Designation ID</th>
									<th>Designation Name</th>
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
	<div class="modal fade" id="addDesignationModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="addDesignationForm">
					<div class="modal-header">
						<h5 class="modal-title">Add New Designation</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Designation Name</label>
							<input type="text" name="designation_name" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="designation_description" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="designation_status" class="form-select">
								<option value="Active" selected>Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
						<button class="btn btn-primary" type="submit">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- EDIT MODAL -->
	<div class="modal fade" id="editDesignationModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="editDesignationForm">
					<input type="hidden" name="id" id="editDesignationId">
					<div class="modal-header">
						<h5 class="modal-title">Edit Designation</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Designation Name</label>
							<input type="text" name="designation_name" id="editDesignationName" class="form-control"
								required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="designation_description" id="editDesignationDescription"
								class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="designation_status" id="editDesignationStatus" class="form-select">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
						<button class="btn btn-primary" type="submit">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<script>
	let currentPage = 1,
		limit = 8;

	// CREATE
	document.getElementById('addDesignationForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const formData = new FormData(this);
		try {
			const res = await fetch('../api/designations/create_designation.php', {
				method: 'POST',
				body: formData
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire('Created', result.message, 'success');
				bootstrap.Modal.getInstance(document.getElementById('addDesignationModal')).hide();
				this.reset();
				loadDesignations(1);
			} else Swal.fire('Error', result.message, 'error');
		} catch {
			Swal.fire('Error', 'Request failed', 'error');
		}
	});

	// OPEN EDIT
	function openEditDesignationModal(d) {
		document.getElementById('editDesignationId').value = d.id;
		document.getElementById('editDesignationName').value = d.designation_name;
		document.getElementById('editDesignationDescription').value = d.designation_description || '';
		document.getElementById('editDesignationStatus').value = d.designation_status;
		new bootstrap.Modal(document.getElementById('editDesignationModal')).show();
	}

	// UPDATE
	document.getElementById('editDesignationForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const formData = new FormData(this);
		try {
			const res = await fetch('../api/designations/update_designation.php', {
				method: 'POST',
				body: formData
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire('Updated', result.message, 'success');
				bootstrap.Modal.getInstance(document.getElementById('editDesignationModal')).hide();
				loadDesignations(1);
			} else Swal.fire('Error', result.message, 'error');
		} catch {
			Swal.fire('Error', 'Request failed', 'error');
		}
	});

	// DELETE
	function deleteDesignation(id) {
		Swal.fire({
				title: 'Delete?',
				text: 'This cannot be undone.',
				icon: 'warning',
				showCancelButton: true
			})
			.then(async (r) => {
				if (!r.isConfirmed) return;
				const res = await fetch('../api/designations/delete_designation.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: 'id=' + id
				});
				const result = await res.json();
				if (result.status === 200) {
					Swal.fire('Deleted', result.message, 'success');
					loadDesignations(1);
				} else Swal.fire('Error', result.message, 'error');
			});
	}

	// READ
	async function loadDesignations(page = 1) {
		const res = await fetch(`../api/designations/get_designations.php?page=${page}&limit=${limit}`);
		const result = await res.json();
		const tbody = document.getElementById('tableBody');
		tbody.innerHTML = '';
		if (result.status === 200) {
			const data = result.data.designations;
			if (data.length === 0) {
				tbody.innerHTML = `<tr><td colspan="7" class="text-muted">No records found</td></tr>`;
				return;
			}
			data.forEach(d => {
				tbody.innerHTML += `
					<tr>
						<td>DES-${String(d.id).padStart(4,'0')}</td>
						<td>${escapeHtml(d.designation_name)}</td>
						<td>${escapeHtml(d.designation_description ?? '')}</td>
						<td><span class="badge ${d.designation_status === 'Active' ? 'bg-success' : 'bg-warning text-dark'}">${d.designation_status}</span></td>
						<td>${new Date(d.created_at).toLocaleString()}</td>
						<td>${new Date(d.updated_at).toLocaleString()}</td>
						<td>
							<button class="btn btn-sm btn-outline-warning" onclick='openEditDesignationModal(${JSON.stringify(d)})'>Edit</button>
							<button class="btn btn-sm btn-outline-danger" onclick='deleteDesignation(${d.id})'>Delete</button>
						</td>
					</tr>`;
			});
		}
	}

	function escapeHtml(text) {
		if (text == null) return '';
		return text.replace(/[&<>"']/g, m => ({
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		} [m]));
	}

	document.addEventListener('DOMContentLoaded', () => loadDesignations(1));
	</script>

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