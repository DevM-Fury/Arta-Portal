<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-sitemap me-2"></i> List of Users
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
	<div class="main-content">
		<div class="container-fluid">
			<div class="card shadow-lg border-0 rounded-3 mx-auto" style="width:100%;max-width:1800px;min-height:80vh;">
				<div class="card-body p-4">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
						<div class="d-flex gap-2 w-100 w-md-auto">
							<input id="searchInput" class="form-control shadow-sm border-primary flex-grow-1"
								placeholder="🔍 Search division...">
							<button class="btn btn-dark shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addDivisionModal">
								<i class="fa-solid fa-plus me-2"></i>Add New Division
							</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead class="table-dark">
								<tr>
									<th>Division ID</th>
									<th>Division Name</th>
									<th>Description</th>
									<th>Status</th>
									<th>Assigned Office</th>
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
	<div class="modal fade" id="addDivisionModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="addDivisionForm">
					<div class="modal-header">
						<h5 class="modal-title">Add New Division</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Division Name</label>
							<input type="text" name="division_name" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="division_description" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="division_status" class="form-select">
								<option value="Active" selected>Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label">Assigned Office</label>
							<select name="office_id" id="divisionOfficeSelect" class="form-select">
								<option value="">Select Office</option>
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
	<div class="modal fade" id="editDivisionModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="editDivisionForm">
					<input type="hidden" name="id" id="editDivisionId">
					<div class="modal-header">
						<h5 class="modal-title">Edit Division</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Division Name</label>
							<input type="text" name="division_name" id="editDivisionName" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="division_description" id="editDivisionDescription"
								class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="division_status" id="editDivisionStatus" class="form-select">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label">Assigned Office</label>
							<select name="office_id" id="editDivisionOfficeSelect" class="form-select">
								<option value="">Select Office</option>
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
	// Populate office selects (both add & edit)
	async function populateOfficeSelects() {
		try {
			const res = await fetch('../api/offices/get_office_list.php');
			const result = await res.json();
			if (result.status === 200) {
				const offices = result.data.offices || [];
				const addSel = document.getElementById('divisionOfficeSelect');
				const editSel = document.getElementById('editDivisionOfficeSelect');
				[addSel, editSel].forEach(sel => {
					if (!sel) return;
					sel.innerHTML = '<option value="">Select Office</option>';
					offices.forEach(o => {
						const opt = document.createElement('option');
						opt.value = o.id;
						opt.textContent = o.office_name;
						sel.appendChild(opt);
					});
				});
			} else {
				console.warn('Failed to load offices for dropdown', result.message);
			}
		} catch (err) {
			console.error('Error loading offices', err);
		}
	}

	// CREATE
	document.getElementById('addDivisionForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const form = this;
		const formData = new FormData(form);
		try {
			const res = await fetch('../api/divisions/create_division.php', {
				method: 'POST',
				body: formData
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire({
						icon: 'success',
						title: 'Created',
						text: result.message,
						timer: 1400,
						showConfirmButton: false
					})
					.then(() => {
						bootstrap.Modal.getInstance(document.getElementById('addDivisionModal')).hide();
						form.reset();
						loadDivisions(1);
					});
			} else {
				Swal.fire('Error', result.message || 'Failed', 'error');
			}
		} catch (err) {
			Swal.fire('Error', 'Request failed', 'error');
		}
	});

	// OPEN EDIT modal
	function openEditDivisionModal(division) {
		document.getElementById('editDivisionId').value = division.id;
		document.getElementById('editDivisionName').value = division.division_name;
		document.getElementById('editDivisionDescription').value = division.division_description || '';
		document.getElementById('editDivisionStatus').value = division.division_status || 'Active';
		document.getElementById('editDivisionOfficeSelect').value = division.office_id || '';
		new bootstrap.Modal(document.getElementById('editDivisionModal')).show();
	}

	// UPDATE
	document.getElementById('editDivisionForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const btn = this.querySelector("button[type='submit']");
		btn.disabled = true;
		const formData = new FormData(this);
		try {
			const res = await fetch('../api/divisions/update_division.php', {
				method: 'POST',
				body: formData
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire({
						icon: 'success',
						title: 'Updated',
						text: result.message,
						timer: 1400,
						showConfirmButton: false
					})
					.then(() => {
						bootstrap.Modal.getInstance(document.getElementById('editDivisionModal')).hide();
						loadDivisions(1);
					});
			} else {
				Swal.fire('Error', result.message || 'Failed', 'error');
			}
		} catch (err) {
			Swal.fire('Error', 'Request failed', 'error');
		} finally {
			btn.disabled = false;
		}
	});

	// DELETE
	function deleteDivision(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'This will permanently delete the division.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete',
			cancelButtonText: 'Cancel'
		}).then(async (r) => {
			if (r.isConfirmed) {
				try {
					const res = await fetch('../api/divisions/delete_division.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body: `id=${encodeURIComponent(id)}`
					});
					const result = await res.json();
					if (result.status === 200) {
						Swal.fire('Deleted', result.message, 'success');
						loadDivisions(1);
					} else {
						Swal.fire('Error', result.message || 'Failed', 'error');
					}
				} catch (err) {
					Swal.fire('Error', 'Request failed', 'error');
				}
			}
		});
	}

	// LOAD (READ)
	let currentPage = 1,
		limit = 8;
	async function loadDivisions(page = 1) {
		try {
			const res = await fetch(`../api/divisions/get_divisions.php?page=${page}&limit=${limit}`);
			const result = await res.json();
			const tableBody = document.getElementById('tableBody');
			tableBody.innerHTML = '';

			if (result.status === 200) {
				const divisions = result.data.divisions || [];
				if (divisions.length === 0) {
					tableBody.innerHTML =
						`<tr><td colspan="8" class="text-center text-muted">No divisions found</td></tr>`;
					renderPagination(1, 1);
					return;
				}

				divisions.forEach(d => {
					const officeName = d.office_name || '-';
					const row = `
              <tr class="expandable-row division-row">
                <td>DIV-${String(d.id).padStart(4,'0')}</td>
                <td>${escapeHtml(d.division_name)}</td>
                <td>${escapeHtml(d.division_description ?? '')}</td>
                <td><span class="badge ${d.division_status === 'Active' ? 'bg-success' : 'bg-warning text-dark'}">${d.division_status}</span></td>
                <td>${escapeHtml(officeName)}</td>
                <td>${d.created_at ? new Date(d.created_at).toLocaleString() : ''}</td>
                <td>${d.updated_at ? new Date(d.updated_at).toLocaleString() : ''}</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary" onclick="viewDivision(${d.id})">View</button>
                  <button class="btn btn-sm btn-outline-warning" onclick='openEditDivisionModal(${JSON.stringify(d)})'>Edit</button>
                  <button class="btn btn-sm btn-outline-danger" onclick="deleteDivision(${d.id})">Delete</button>
                </td>
              </tr>
              <tr class="expandable-content" style="display:none;">
                <td colspan="8">
                  <div class="expand-body">
                    <p><strong>Description:</strong> ${escapeHtml(d.division_description ?? 'No description')}</p>
                    <p><strong>Assigned Office:</strong> ${escapeHtml(officeName)}</p>
                    <p><strong>Created:</strong> ${d.created_at ? new Date(d.created_at).toLocaleString() : ''}</p>
                    <p><strong>Updated:</strong> ${d.updated_at ? new Date(d.updated_at).toLocaleString() : ''}</p>
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
					`<tr><td colspan="8" class="text-center text-danger">${result.message}</td></tr>`;
			}
		} catch (err) {
			console.error(err);
			document.getElementById('tableBody').innerHTML =
				`<tr><td colspan="8" class="text-center text-danger">Failed to fetch divisions</td></tr>`;
		}
	}

	function renderPagination(page, totalPages) {
		const pagination = document.getElementById('pagination');
		pagination.innerHTML = '';
		if (totalPages > 1) {
			pagination.innerHTML = `
          <button class="btn btn-sm btn-outline-secondary" ${page===1?'disabled':''} onclick="loadDivisions(${page-1})">Prev</button>
          <span class="mx-2">Page ${page} of ${totalPages}</span>
          <button class="btn btn-sm btn-outline-secondary" ${page===totalPages?'disabled':''} onclick="loadDivisions(${page+1})">Next</button>
        `;
		}
	}

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

	// Basic search/filter (client-side)
	document.getElementById('searchInput').addEventListener('keyup', function() {
		const q = this.value.toLowerCase();
		document.querySelectorAll('#tableBody .division-row').forEach(row => {
			row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
		});
	});

	// small helper to escape text for insertion
	function escapeHtml(text) {
		if (text === null || text === undefined) return '';
		return String(text)
			.replaceAll('&', '&amp;')
			.replaceAll('<', '&lt;')
			.replaceAll('>', '&gt;')
			.replaceAll('"', '&quot;')
			.replaceAll("'", '&#039;');
	}

	function viewDivision(id) {
		Swal.fire('Division', 'Open details for division ID: ' + id, 'info');
	}

	// Init
	document.addEventListener('DOMContentLoaded', () => {
		populateOfficeSelects();
		loadDivisions(1);
	});
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

	.table-responsive {
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.table {
		min-width: 600px;
		width: 100%;
		table-layout: auto;
	}

	.table img {
		max-width: 50px;
		height: auto;
	}

	.table thead {
		background: var(--arta-blue3);
		color: #fff;
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
		font-size: 14px;
		line-height: 1.6;
	}

	/* 📱 Mobile-friendly card layout */
	@media (max-width: 768px) {
		.table {
			border: 0;
			min-width: unset;
		}

		.table thead {
			display: none;
		}

		.table tbody tr {
			display: block;
			margin-bottom: 15px;
			border: 1px solid #ddd;
			border-radius: 10px;
			background: #fff;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
			overflow: hidden;
		}

		.table tbody td {
			display: flex;
			justify-content: space-between;
			align-items: center;
			text-align: left;
			padding: 10px 15px;
			font-size: 13px;
			border-bottom: 1px solid #f0f0f0;
		}

		.table tbody td:last-child {
			border-bottom: none;
		}

		.table tbody td::before {
			content: attr(data-label);
			font-weight: 600;
			color: var(--arta-blue1);
			flex-basis: 50%;
			text-align: left;
		}

		.table tbody td img {
			max-width: 40px;
		}

		.action-buttons {
			display: flex;
			flex-direction: column;
			width: 100%;
		}

		.action-buttons .btn {
			font-size: 12px;
			padding: 6px;
			margin-bottom: 5px;
		}

		.expand-body {
			padding: 12px 16px;
			font-size: 13px;
		}

		#searchInput {
			width: 100%;
			margin-bottom: 10px;
		}
	}
	</style>

	<!-- ✅ Footer include -->
	<?php include_once __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>