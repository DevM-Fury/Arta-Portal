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
			<div class="card shadow-lg border-0 mx-auto" style="width:100%; max-width:1800px; min-height:80vh;">
				<div class="card-body p-2 p-md-3">

					<!-- Filter Section - Compact -->
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center mb-3 gap-2">
						<div class="d-flex flex-column flex-sm-row gap-2 w-100">
							<select id="filterType" class="form-select form-select-sm" style="min-width:100px;">
								<option value="">Filter By</option>
								<option value="office">Office</option>
								<option value="division">Division</option>
								<option value="unit">Unit</option>
								<option value="designation">Designation</option>
							</select>
							<select id="filterValue" class="form-select form-select-sm flex-grow-1">
								<option value="">Select...</option>
							</select>
							<button class="btn btn-dark btn-sm px-2" onclick="loadUsers()" style="white-space: nowrap;">
								<i class="fa-solid fa-filter me-1"></i>Apply
							</button>
						</div>
					</div>

					<!-- Two Column Layout - Maximized Space -->
					<div class="row g-2">
						<!-- Left Column: Available Users -->
						<div class="col-lg-6">
							<div class="h-100 d-flex flex-column">
								<h6 class="mb-2"><i class="fa-solid fa-users me-2"></i>Available Users</h6>
								<div class="flex-grow-1" style="height: calc(100vh - 250px); overflow-y: auto;">
									<!-- Desktop Table -->
									<div class="table-responsive d-none d-md-block">
										<table class="table table-sm align-middle text-center mb-0">
											<thead class="table-dark sticky-top" style="top: 0; z-index: 1;">
												<tr>
													<th style="width:5%">#</th>
													<th style="width:15%">Full Name</th>
													<th style="width:15%">Office</th>
													<th style="width:15%">Division</th>
													<th style="width:15%">Unit</th>
													<th style="width:15%">Designation</th>
													<th style="width:20%">Action</th>
												</tr>
											</thead>
											<tbody id="userTableBody">
												<tr>
													<td colspan="7" class="text-center text-muted">Please select a filter...</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- Mobile Cards -->
									<div id="mobileUserCards" class="d-block d-md-none"></div>
								</div>
							</div>
						</div>

						<!-- Right Column: Assigned Systems -->
						<div class="col-lg-6">
							<div class="h-100 d-flex flex-column">
								<h6 class="mb-2"><i class="fa-solid fa-share-nodes me-2"></i>Assigned Systems</h6>
								<div class="flex-grow-1" style="height: calc(100vh - 250px); overflow-y: auto;">
									<!-- Desktop Table -->
									<div class="table-responsive d-none d-md-block">
										<table class="table table-sm align-middle text-center mb-0">
											<thead class="table-dark sticky-top" style="top: 0; z-index: 1;">
												<tr>
													<th style="width:5%">#</th>
													<th style="width:15%">Full Name</th>
													<th style="width:15%">System</th>
													<th style="width:10%">Role</th>
													<th style="width:15%">Assigned By</th>
													<th style="width:20%">Date Assigned</th>
													<th style="width:20%">Action</th>
												</tr>
											</thead>
											<tbody id="assignedTableBody">
												<tr>
													<td colspan="7" class="text-center text-muted">No assigned systems yet.</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- Mobile Cards -->
									<div id="mobileAssignedCards" class="d-block d-md-none"></div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Assign System Modal - Dark Header -->
	<div class="modal fade" id="assignSystemModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header bg-dark text-white py-2">
					<h6 class="modal-title"><i class="fa-solid fa-share-nodes me-2"></i> Assign System</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="assignPortalId">
					<div class="mb-2">
						<label class="form-label small">Select System</label>
						<select id="systemSelect" class="form-select form-select-sm">
							<option value="">Select System</option>
						</select>
					</div>
				</div>
				<div class="modal-footer py-2">
					<button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button class="btn btn-sm btn-dark" onclick="assignSystem()">
						<i class="fa-solid fa-check me-1"></i>Assign
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Edit Assignment Modal - Dark Header -->
	<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header bg-dark text-white py-2">
					<h6 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i> Update Assignment</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="editAccessId">
					<div class="mb-2">
						<label class="form-label small">User</label>
						<input type="text" id="editUserName" class="form-control form-control-sm" readonly>
					</div>
					<div class="mb-2">
						<label class="form-label small">System</label>
						<select id="editSystemSelect" class="form-select form-select-sm">
							<option value="">Select System</option>
						</select>
					</div>
					<div class="mb-2">
						<label class="form-label small">Role</label>
						<input type="text" id="editRole" class="form-control form-control-sm" placeholder="Enter role">
					</div>
				</div>
				<div class="modal-footer py-2">
					<button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button class="btn btn-sm btn-dark" onclick="updateAssignment()">
						<i class="fa-solid fa-save me-1"></i>Update
					</button>
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

	// ===== Load Assigned Systems =====
	async function loadAssignedSystems() {
		const tbody = document.getElementById('assignedTableBody');
		const mobileCards = document.getElementById('mobileAssignedCards');
		tbody.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;
		mobileCards.innerHTML = `<div class="text-center py-3">Loading...</div>`;

		try {
			const assigned = await fetchJSON('../api/assignment/get_assigned_systems.php');

			if (!assigned.length) {
				tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No assigned systems yet.</td></tr>`;
				mobileCards.innerHTML = `<div class="alert alert-info text-center py-2">No assigned systems yet.</div>`;
				return;
			}

			// Desktop table
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
						<button class="btn btn-sm btn-outline-warning py-0 px-2" onclick="openEditModal(${a.access_id})">
							<i class="fa-solid fa-pen-to-square"></i>
						</button>
					</td>
				</tr>`;
			});

			// Mobile cards
			mobileCards.innerHTML = '';
			assigned.forEach((a, i) => {
				mobileCards.innerHTML += `
				<div class="assigned-card mb-2 p-2 border rounded shadow-sm">
					<div class="d-flex justify-content-between align-items-start mb-1">
						<span class="badge bg-dark">#${i+1}</span>
						<button class="btn btn-sm btn-outline-warning py-0 px-2" onclick="openEditModal(${a.access_id})">
							<i class="fa-solid fa-pen-to-square"></i>
						</button>
					</div>
					<div class="row g-1 small">
						<div class="col-12"><strong>Name:</strong> ${a.firstname} ${a.lastname}</div>
						<div class="col-6"><strong>System:</strong> ${a.system_name}</div>
						<div class="col-6"><strong>Role:</strong> ${a.role}</div>
						<div class="col-6"><strong>By:</strong> ${a.assigned_by_name ?? '-'}</div>
						<div class="col-6"><strong>Date:</strong> ${a.date_assigned}</div>
					</div>
				</div>`;
			});

		} catch (err) {
			console.error(err);
			tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading assigned systems.</td></tr>`;
			mobileCards.innerHTML = `<div class="alert alert-danger text-center py-2">Error loading assigned systems.</div>`;
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
		const mobileCards = document.getElementById('mobileUserCards');
		
		if (!type || !id) {
			Swal.fire('Info', 'Please select filter type and value.', 'info');
			return;
		}

		tbody.innerHTML = `<tr><td colspan="7" class="text-center">Loading...</td></tr>`;
		mobileCards.innerHTML = `<div class="text-center py-3">Loading...</div>`;

		try {
			const users = await fetchJSON(`../api/assignment/get_users.php?type=${type}&id=${id}`);

			if (!users.length) {
				tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>`;
				mobileCards.innerHTML = `<div class="alert alert-info text-center py-2">No users found.</div>`;
				return;
			}

			// Desktop table
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
							<button class="btn btn-sm btn-outline-primary py-0 px-2" onclick="openAssignModal('${u.portal_id}')">
								<i class="fa-solid fa-share-nodes"></i>
							</button>
						</td>
					</tr>`;
			});

			// Mobile cards
			mobileCards.innerHTML = '';
			users.forEach((u, i) => {
				mobileCards.innerHTML += `
				<div class="user-card mb-2 p-2 border rounded shadow-sm">
					<div class="d-flex justify-content-between align-items-start mb-1">
						<span class="badge bg-dark">#${i+1}</span>
						<button class="btn btn-sm btn-outline-primary py-0 px-2" onclick="openAssignModal('${u.portal_id}')">
							<i class="fa-solid fa-share-nodes"></i>
						</button>
					</div>
					<div class="row g-1 small">
						<div class="col-12"><strong>Name:</strong> ${u.firstname} ${u.lastname}</div>
						<div class="col-6"><strong>Office:</strong> ${u.office_name ?? '-'}</div>
						<div class="col-6"><strong>Division:</strong> ${u.division_name ?? '-'}</div>
						<div class="col-6"><strong>Unit:</strong> ${u.unit_name ?? '-'}</div>
						<div class="col-6"><strong>Designation:</strong> ${u.designation_name ?? '-'}</div>
					</div>
				</div>`;
			});

		} catch (err) {
			console.error(err);
			tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading users.</td></tr>`;
			mobileCards.innerHTML = `<div class="alert alert-danger text-center py-2">Error loading users.</div>`;
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
				Swal.fire({
					icon: 'success',
					title: 'Success',
					text: result.message,
					timer: 1500,
					showConfirmButton: false
				});
				bootstrap.Modal.getInstance(document.getElementById('assignSystemModal')).hide();
				loadUsers();
				loadAssignedSystems();
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
			const details = await fetchJSON(`../api/assignment/get_assignment_details.php?access_id=${accessId}`);
			
			document.getElementById('editAccessId').value = details.access_id;
			document.getElementById('editUserName').value = details.firstname + ' ' + details.lastname;
			document.getElementById('editRole').value = details.role;
			
			const systems = await fetchJSON('../api/assignment/get_systems.php');
			const select = document.getElementById('editSystemSelect');
			select.innerHTML = '<option value="">Select System</option>';
			systems.forEach(s => {
				const selected = (s.id == details.system_id) ? 'selected' : '';
				select.innerHTML += `<option value="${s.id}" ${selected}>${s.system_name}</option>`;
			});
			
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
				Swal.fire({
					icon: 'success',
					title: 'Success',
					text: result.message,
					timer: 1500,
					showConfirmButton: false
				});
				bootstrap.Modal.getInstance(document.getElementById('editAssignmentModal')).hide();
				loadAssignedSystems();
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
		loadAssignedSystems();
	});
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
		border-radius: 12px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
		overflow: hidden;
	}
	
	/* Maximized Space */
	.card-body {
		padding: 0.75rem !important;
	}
	
	/* Table Styles - Compact */
	.table {
		width: 100%;
		table-layout: fixed;
		border-collapse: collapse;
		font-size: 12px;
	}
	
	.table thead {
		background: var(--arta-blue3);
		color: #fff;
	}
	
	.table thead th {
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.3px;
		padding: 6px 4px !important;
		border: none !important;
		white-space: nowrap;
	}
	
	.table tbody td {
		padding: 4px 4px !important;
		font-size: 11px;
		vertical-align: middle;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	
	.table tbody tr:nth-child(even) {
		background-color: var(--arta-light);
	}
	
	/* Action Buttons - Minimal */
	.btn-sm {
		padding: 2px 6px !important;
		font-size: 11px;
	}
	
	.btn-outline-primary, .btn-outline-warning {
		border-width: 1px;
	}
	
	/* Scrollable Containers */
	.flex-grow-1 {
		scrollbar-width: thin;
		scrollbar-color: var(--arta-blue3) #e0e0e0;
	}
	
	.flex-grow-1::-webkit-scrollbar {
		width: 4px;
	}
	
	.flex-grow-1::-webkit-scrollbar-track {
		background: #e0e0e0;
	}
	
	.flex-grow-1::-webkit-scrollbar-thumb {
		background: var(--arta-blue3);
		border-radius: 4px;
	}
	
	/* Sticky Header */
	.sticky-top {
		position: sticky;
		top: 0;
		z-index: 1;
	}
	
	/* Mobile Card Styles - Compact */
	.user-card, .assigned-card {
		background: #fff;
		border-radius: 8px;
		font-size: 11px;
	}
	
	.user-card .badge, .assigned-card .badge {
		font-size: 10px;
		padding: 4px 6px;
	}
	
	/* Form Controls - Compact */
	.form-select-sm, .btn-sm {
		font-size: 12px;
		padding: 4px 8px;
	}
	
	.btn-dark {
		background-color: var(--arta-blue3);
		border: none;
	}
	
	.btn-dark:hover {
		background-color: #1a2c35;
	}
	
	/* Modal Styles - Compact */
	.modal-header {
		padding: 8px 12px;
	}
	
	.modal-body {
		padding: 12px;
	}
	
	.modal-footer {
		padding: 8px 12px;
	}
	
	.modal-header.bg-dark {
		background-color: var(--arta-blue3) !important;
	}
	
	.modal-title {
		font-size: 14px;
	}
	
	.form-label.small {
		font-size: 11px;
		margin-bottom: 2px;
	}
	
	/* Remove borders and shadows */
	.table-responsive {
		border: none;
	}
	
	/* Responsive */
	@media (max-width: 991px) {
		.col-lg-6 {
			margin-bottom: 10px;
		}
		
		.flex-grow-1 {
			height: 400px !important;
		}
	}
	
	@media (max-width: 576px) {
		.card-body {
			padding: 0.5rem !important;
		}
		
		h6 {
			font-size: 13px;
		}
	}
	</style>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>