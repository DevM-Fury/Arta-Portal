<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<div class="header-bar">
		<div class="header-title"><i class="fa-solid fa-boxes-stacked me-2"></i> Unit Dashboard</div>
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
								placeholder="🔍 Search unit...">
							<button class="btn btn-primary shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addUnitModal">
								<i class="fa-solid fa-plus me-2"></i>Add New Unit
							</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead class="table-dark">
								<tr>
									<th>Unit ID</th>
									<th>Unit Name</th>
									<th>Description</th>
									<th>Status</th>
									<th>Assigned Division</th>
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
	<div class="modal fade" id="addUnitModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="addUnitForm">
					<div class="modal-header">
						<h5 class="modal-title">Add New Unit</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Unit Name</label>
							<input type="text" name="unit_name" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="unit_description" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="unit_status" class="form-select">
								<option value="Active" selected>Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label">Assigned Division</label>
							<select name="division_id" id="unitDivisionSelect" class="form-select">
								<option value="">Select Division</option>
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
	<div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="editUnitForm">
					<input type="hidden" name="id" id="editUnitId">
					<div class="modal-header">
						<h5 class="modal-title">Edit Unit</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Unit Name</label>
							<input type="text" name="unit_name" id="editUnitName" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea name="unit_description" id="editUnitDescription" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="unit_status" id="editUnitStatus" class="form-select">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label">Assigned Division</label>
							<select name="division_id" id="editUnitDivisionSelect" class="form-select">
								<option value="">Select Division</option>
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

	<script>
	// populate division lists for add & edit
	async function populateDivisionSelects() {
		try {
			const res = await fetch('../api/divisions/get_division_list.php');
			const result = await res.json();
			if (result.status === 200) {
				const divisions = result.data.divisions || [];
				const addSel = document.getElementById('unitDivisionSelect');
				const editSel = document.getElementById('editUnitDivisionSelect');
				[addSel, editSel].forEach(sel => {
					if (!sel) return;
					sel.innerHTML = '<option value="">Select Division</option>';
					divisions.forEach(d => {
						const opt = document.createElement('option');
						opt.value = d.id;
						opt.textContent = d.division_name;
						sel.appendChild(opt);
					});
				});
			}
		} catch (err) {
			console.error('Failed to load divisions', err);
		}
	}

	// CREATE
	document.getElementById('addUnitForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const form = this;
		const formData = new FormData(form);
		try {
			const res = await fetch('../api/units/create_unit.php', {
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
						bootstrap.Modal.getInstance(document.getElementById('addUnitModal')).hide();
						form.reset();
						loadUnits(1);
					});
			} else {
				Swal.fire('Error', result.message || 'Failed', 'error');
			}
		} catch (err) {
			Swal.fire('Error', 'Request failed', 'error');
		}
	});

	// OPEN EDIT
	function openEditUnitModal(unit) {
		document.getElementById('editUnitId').value = unit.id;
		document.getElementById('editUnitName').value = unit.unit_name;
		document.getElementById('editUnitDescription').value = unit.unit_description || '';
		document.getElementById('editUnitStatus').value = unit.unit_status || 'Active';
		document.getElementById('editUnitDivisionSelect').value = unit.division_id || '';
		new bootstrap.Modal(document.getElementById('editUnitModal')).show();
	}

	// UPDATE
	document.getElementById('editUnitForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const btn = this.querySelector("button[type='submit']");
		btn.disabled = true;
		const formData = new FormData(this);
		try {
			const res = await fetch('../api/units/update_unit.php', {
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
						bootstrap.Modal.getInstance(document.getElementById('editUnitModal')).hide();
						loadUnits(1);
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
	function deleteUnit(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'This will permanently delete the unit.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete',
			cancelButtonText: 'Cancel'
		}).then(async (r) => {
			if (r.isConfirmed) {
				try {
					const res = await fetch('../api/units/delete_unit.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body: `id=${encodeURIComponent(id)}`
					});
					const result = await res.json();
					if (result.status === 200) {
						Swal.fire('Deleted', result.message, 'success');
						loadUnits(1);
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
	async function loadUnits(page = 1) {
		try {
			const res = await fetch(`../api/units/get_units.php?page=${page}&limit=${limit}`);
			const result = await res.json();
			const tableBody = document.getElementById('tableBody');
			tableBody.innerHTML = '';

			if (result.status === 200) {
				const units = result.data.units || [];
				if (units.length === 0) {
					tableBody.innerHTML =
						`<tr><td colspan="8" class="text-center text-muted">No units found</td></tr>`;
					renderPagination(1, 1);
					return;
				}

				units.forEach(u => {
					const divisionName = u.division_name || '-';
					const row = `
              <tr class="expandable-row unit-row">
                <td>UNI-${String(u.id).padStart(4,'0')}</td>
                <td>${escapeHtml(u.unit_name)}</td>
                <td>${escapeHtml(u.unit_description ?? '')}</td>
                <td><span class="badge ${u.unit_status === 'Active' ? 'bg-success' : 'bg-warning text-dark'}">${u.unit_status}</span></td>
                <td>${escapeHtml(divisionName)}</td>
                <td>${u.created_at ? new Date(u.created_at).toLocaleString() : ''}</td>
                <td>${u.updated_at ? new Date(u.updated_at).toLocaleString() : ''}</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary" onclick="viewUnit(${u.id})">View</button>
                  <button class="btn btn-sm btn-outline-warning" onclick='openEditUnitModal(${JSON.stringify(u)})'>Edit</button>
                  <button class="btn btn-sm btn-outline-danger" onclick="deleteUnit(${u.id})">Delete</button>
                </td>
              </tr>
              <tr class="expandable-content" style="display:none;">
                <td colspan="8">
                  <div class="expand-body">
                    <p><strong>Description:</strong> ${escapeHtml(u.unit_description ?? 'No description')}</p>
                    <p><strong>Assigned Division:</strong> ${escapeHtml(divisionName)}</p>
                    <p><strong>Created:</strong> ${u.created_at ? new Date(u.created_at).toLocaleString() : ''}</p>
                    <p><strong>Updated:</strong> ${u.updated_at ? new Date(u.updated_at).toLocaleString() : ''}</p>
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
				`<tr><td colspan="8" class="text-center text-danger">Failed to fetch units</td></tr>`;
		}
	}

	function renderPagination(page, totalPages) {
		const pagination = document.getElementById('pagination');
		pagination.innerHTML = '';
		if (totalPages > 1) {
			pagination.innerHTML = `
          <button class="btn btn-sm btn-outline-secondary" ${page===1?'disabled':''} onclick="loadUnits(${page-1})">Prev</button>
          <span class="mx-2">Page ${page} of ${totalPages}</span>
          <button class="btn btn-sm btn-outline-secondary" ${page===totalPages?'disabled':''} onclick="loadUnits(${page+1})">Next</button>
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

	document.getElementById('searchInput').addEventListener('keyup', function() {
		const q = this.value.toLowerCase();
		document.querySelectorAll('#tableBody .unit-row').forEach(row => {
			row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
		});
	});

	// escape helper
	function escapeHtml(text) {
		if (text === null || text === undefined) return '';
		return String(text)
			.replaceAll('&', '&amp;')
			.replaceAll('<', '&lt;')
			.replaceAll('>', '&gt;')
			.replaceAll('"', '&quot;')
			.replaceAll("'", '&#039;');
	}

	function viewUnit(id) {
		Swal.fire('Unit', 'Open details for unit ID: ' + id, 'info');
	}

	// init
	document.addEventListener('DOMContentLoaded', () => {
		populateDivisionSelects();
		loadUnits(1);
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