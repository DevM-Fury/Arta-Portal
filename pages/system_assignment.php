<?php
// Include necessary components
include_once __DIR__ . '/../includes/head.php';
?>
<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-network-wired me-2"></i> System Assignment
		</div>
	</div>

	<div class="main-content">
		<div class="container-fluid">
			<div class="card shadow-lg border-0 mx-auto" style="width:100%; max-width:1800px; min-height:80vh;">
				<div class="card-body p-4">

					<!-- Filter Section -->
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
						<div class="d-flex gap-2 w-100 w-md-4">
							<select id="filterType" class="form-select flex-grow-1">
								<option value="">Filter By</option>
								<option value="office">Office</option>
								<option value="division">Division</option>
								<option value="unit">Unit</option>
								<option value="designation">Designation</option>
							</select>
							<select id="filterValue" class="form-select flex-grow-1">
								<option value="">Select...</option>
							</select>
							<button class="btn btn-dark" onclick="loadUsers()">
								<i class="fa-solid fa-filter me-2"></i> Apply Filter
							</button>
						</div>
					</div>

					<!-- Users Table -->
					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead class="table-dark">
								<tr>
									<th>#</th>
									<th>Full Name</th>
									<th>Office</th>
									<th>Division</th>
									<th>Unit</th>
									<th>Designation</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="userTableBody">
								<tr>
									<td colspan="7" class="text-center text-muted">Please select a filter...</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Assigned Systems Table -->
					<div class="mt-4">
						<h5>Assigned Systems</h5>
						<div class="table-responsive">
							<table class="table table-bordered align-middle text-center">
								<thead class="table-secondary">
									<tr>
										<th>#</th>
										<th>Full Name</th>
										<th>System</th>
										<th>Role</th>
										<th>Assigned By</th>
										<th>Date Assigned</th>
										<th>Action</th> <!-- BAGONG COLUMN -->
									</tr>
								</thead>
								<tbody id="assignedTableBody">
									<tr>
										<td colspan="7" class="text-center text-muted">No assigned systems yet.</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Assign System Modal -->
	<div class="modal fade" id="assignSystemModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title"><i class="fa-solid fa-share-nodes me-2"></i> Assign System</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="assignPortalId">
					<div class="mb-3">
						<label class="form-label">Select System</label>
						<select id="systemSelect" class="form-select">
							<option value="">Select System</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button class="btn btn-primary" onclick="assignSystem()"><i class="fa-solid fa-check me-2"></i>Assign</button>
				</div>
			</div>
		</div>
	</div>

	<!-- BAGONG MODAL: Edit Assignment Modal -->
	<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-warning text-dark">
					<h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i> Update Assignment</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="editAccessId">
					<div class="mb-3">
						<label class="form-label">User</label>
						<input type="text" id="editUserName" class="form-control" readonly>
					</div>
					<div class="mb-3">
						<label class="form-label">System</label>
						<select id="editSystemSelect" class="form-select">
							<option value="">Select System</option>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label">Role</label>
						<input type="text" id="editRole" class="form-control" placeholder="Enter role">
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button class="btn btn-warning" onclick="updateAssignment()"><i class="fa-solid fa-save me-2"></i>Update</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
	// ===== Enhanced fetch with error handling =====
	async function fetchJSON(url, options = {}) {
		const response = await fetch(url, options);
		const contentType = response.headers.get('content-type');
		if (!response.ok) {
			const text = await response.text();
			console.error(`HTTP ${response.status} from ${url}:`, text);
			throw new Error(`HTTP error ${response.status}`);
		}
		if (contentType && contentType.includes('application/json')) {
			return response.json();
		} else {
			const text = await response.text();
			console.error('Non-JSON response from', url, ':', text);
			throw new Error('Invalid JSON response (received HTML/plain text instead)');
		}
	}

	// ===== Load Assigned Systems (may access_id at Edit button) =====
	async function loadAssignedSystems() {
		const tbody = document.getElementById('assignedTableBody');
		tbody.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;

		try {
			const assigned = await fetchJSON('../api/assignment/get_assigned_systems.php');

			if (!assigned.length) {
				tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No assigned systems yet.</td></tr>`;
				return;
			}

			tbody.innerHTML = '';
			assigned.forEach((a, i) => {
				tbody.innerHTML += `
				<tr>
					<td>${i+1}</td>
					<td>${a.firstname} ${a.lastname}</td>
					<td>${a.system_name}</td>
					<td>${a.role}</td>
					<td>${a.assigned_by_name ?? '-'}</td>
					<td>${a.date_assigned}</td>
					<td>
						<button class="btn btn-sm btn-outline-warning" onclick="openEditModal(${a.access_id})">
							<i class="fa-solid fa-pen-to-square me-1"></i>Edit
						</button>
					</td>
				</tr>`;
			});

		} catch (err) {
			console.error(err);
			tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading assigned systems. Check console (F12).</td></tr>`;
		}
	}

	// ===== Populate Filter Values =====
	document.getElementById('filterType').addEventListener('change', async (e) => {
		const type = e.target.value;
		const select = document.getElementById('filterValue');
		select.innerHTML = '<option value="">Loading...</option>';

		if (!type) {
			select.innerHTML = '<option value="">Select...</option>';
			return;
		}

		try {
			const data = await fetchJSON(`../api/assignment/get_filter_values.php?type=${type}`);
			select.innerHTML = '<option value="">Select...</option>';
			data.forEach(d => select.innerHTML += `<option value="${d.id}">${d.name}</option>`);
		} catch (err) {
			console.error(err);
			select.innerHTML = '<option value="">Error loading data</option>';
		}
	});

	// ===== Load Users =====
	async function loadUsers() {
		const type = document.getElementById('filterType').value;
		const id = document.getElementById('filterValue').value;
		const tbody = document.getElementById('userTableBody');
		tbody.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;

		try {
			const users = await fetchJSON(`../api/assignment/get_users.php?type=${type}&id=${id}`);

			if (!users.length) {
				tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>`;
				return;
			}

			tbody.innerHTML = '';
			users.forEach((u, i) => {
				tbody.innerHTML += `
					<tr>
						<td>${i + 1}</td>
						<td>${u.firstname} ${u.lastname}</td>
						<td>${u.office_name ?? '-'}</td>
						<td>${u.division_name ?? '-'}</td>
						<td>${u.unit_name ?? '-'}</td>
						<td>${u.designation_name ?? '-'}</td>
						<td>
							<button class="btn btn-sm btn-outline-primary" onclick="openAssignModal('${u.portal_id}')">
								<i class="fa-solid fa-share-nodes me-1"></i>Assign
							</button>
						</td>
					</tr>`;
			});
		} catch (err) {
			console.error(err);
			tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading users. Check console.</td></tr>`;
		}
	}

	// ===== Open Assign Modal =====
	async function openAssignModal(portalId) {
		document.getElementById('assignPortalId').value = portalId;
		const modalEl = document.getElementById('assignSystemModal');
		const modal = new bootstrap.Modal(modalEl);
		modal.show();

		try {
			const systems = await fetchJSON(`../api/assignment/get_systems.php`);
			const select = document.getElementById('systemSelect');
			select.innerHTML = '<option value="">Select System</option>';
			systems.forEach(s => select.innerHTML += `<option value="${s.id}">${s.system_name}</option>`);
		} catch (err) {
			console.error('Error loading systems:', err);
			Swal.fire('Error', 'Failed to load systems. Check console.', 'error');
		}
	}

	// ===== Assign System =====
	async function assignSystem() {
		const portalId = document.getElementById('assignPortalId').value;
		const systemId = document.getElementById('systemSelect').value;

		if (!systemId) {
			Swal.fire('Error', 'Please select a system.', 'error');
			return;
		}

		try {
			const result = await fetchJSON('../api/assignment/assign_system.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ portal_id: portalId, system_id: systemId })
			});

			if (result.status === 200) {
				Swal.fire('Success', result.message, 'success');
				bootstrap.Modal.getInstance(document.getElementById('assignSystemModal')).hide();
				loadUsers(); // Refresh users table
				loadAssignedSystems(); // Also refresh assigned systems table
			} else {
				Swal.fire('Error', result.message, 'error');
			}
		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Failed to assign system. Check console.', 'error');
		}
	}

	// ===== Open Edit Modal =====
	async function openEditModal(accessId) {
		try {
			// Fetch assignment details
			const details = await fetchJSON(`../api/assignment/get_assignment_details.php?access_id=${accessId}`);
			
			// Populate modal
			document.getElementById('editAccessId').value = details.access_id;
			document.getElementById('editUserName').value = details.firstname + ' ' + details.lastname;
			document.getElementById('editRole').value = details.role;
			
			// Load systems dropdown
			const systems = await fetchJSON('../api/assignment/get_systems.php');
			const select = document.getElementById('editSystemSelect');
			select.innerHTML = '<option value="">Select System</option>';
			systems.forEach(s => {
				const selected = (s.id == details.system_id) ? 'selected' : '';
				select.innerHTML += `<option value="${s.id}" ${selected}>${s.system_name}</option>`;
			});
			
			// Show modal
			const modal = new bootstrap.Modal(document.getElementById('editAssignmentModal'));
			modal.show();
		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Failed to load assignment details.', 'error');
		}
	}

	// ===== Update Assignment =====
	async function updateAssignment() {
		const accessId = document.getElementById('editAccessId').value;
		const systemId = document.getElementById('editSystemSelect').value;
		const role = document.getElementById('editRole').value.trim();

		if (!systemId || !role) {
			Swal.fire('Error', 'Please fill all fields.', 'error');
			return;
		}

		try {
			const result = await fetchJSON('../api/assignment/update_assignment.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ access_id: accessId, system_id: systemId, role: role })
			});

			if (result.status === 200) {
				Swal.fire('Success', result.message, 'success');
				bootstrap.Modal.getInstance(document.getElementById('editAssignmentModal')).hide();
				loadAssignedSystems(); // Refresh table
			} else {
				Swal.fire('Error', result.message, 'error');
			}
		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Update failed. Check console.', 'error');
		}
	}

	// ===== Initialize on page load =====
	document.addEventListener('DOMContentLoaded', () => {
		loadAssignedSystems(); // Load existing assignments
	});
	</script>

	<!-- Styles (unchanged) -->
	<style>
	/* Paliitin ang height ng select fields */
	#filterType,
	#filterValue {
		height: 38px;
		font-size: 14px;
	}
	.d-flex.gap-2.w-100.w-md-4 .btn-dark {
		min-width: 150px;
		font-size: 14px;
		height: 38px;
	}
	</style>

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
		min-width: 1200px;
		width: 100%;
		table-layout: auto;
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
	}
	</style>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>