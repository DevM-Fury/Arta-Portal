<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

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

					<div class="mb-3">
						<label class="form-label">Select Role</label>
						<select id="roleSelect" class="form-select">
							<option value="">Select Role</option>
							<option value="user">User</option>
							<option value="property">Property</option>
							<option value="inspector">Inspector</option>
							<option value="subadmin">Subadmin</option>
							<option value="admin">Admin</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button class="btn btn-primary" onclick="assignSystem()">
						<i class="fa-solid fa-check me-2"></i>Assign
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
	async function loadAssignedSystems() {
		const tbody = document.getElementById('assignedTableBody');
		tbody.innerHTML = `<tr><td colspan="6" class="text-center">Loading...</td></tr>`;

		try {
			const res = await fetch('../api/assignment/get_assigned_systems.php');
			const assigned = await res.json();

			if (!assigned.length) {
				tbody.innerHTML =
					`<tr><td colspan="6" class="text-center text-muted">No assigned systems yet.</td></tr>`;
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
                </tr>`;
			});

		} catch (err) {
			console.error(err);
			tbody.innerHTML =
				`<tr><td colspan="6" class="text-center text-danger">Error loading assigned systems.</td></tr>`;
		}
	}

	// Tawagin sa page load at pagkatapos mag-assign
	loadAssignedSystems();


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
			const res = await fetch(`../api/assignment/get_filter_values.php?type=${type}`);
			const data = await res.json();
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
			const res = await fetch(`../api/assignment/get_users.php?type=${type}&id=${id}`);
			const users = await res.json();

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
			tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading users.</td></tr>`;
		}
	}

	// // ===== Open Assign Modal =====
	// async function openAssignModal(portalId) {
	// 	document.getElementById('assignPortalId').value = portalId;
	// 	const modalEl = document.getElementById('assignSystemModal');
	// 	const modal = new bootstrap.Modal(modalEl);
	// 	modal.show();

	// 	try {
	// 		const res = await fetch(`../api/assignment/get_systems_subadmin.php`);
	// 		const systems = await res.json();
	// 		const select = document.getElementById('systemSelect');
	// 		select.innerHTML = '<option value="">Select System</option>';
	// 		systems.forEach(s => select.innerHTML += `<option value="${s.id}">${s.system_name}</option>`);
	// 	} catch (err) {
	// 		console.error('Error loading systems:', err);
	// 	}
	// }


	// ===== Open Assign Modal =====
	async function openAssignModal(portalId) {
		// store user to assign
		document.getElementById('assignPortalId').value = portalId;

		// show modal
		const modalEl = document.getElementById('assignSystemModal');
		const modal = new bootstrap.Modal(modalEl);
		modal.show();

		try {
			// fetch only systems assigned to logged-in subadmin (session-based)
			const res = await fetch(`../api/assignment/get_systems_subadmin.php`);
			const systems = await res.json();

			const select = document.getElementById('systemSelect');
			select.innerHTML = '<option value="">Select System</option>';

			// handle possible errors or unauthorized access
			if (systems.status === 401) {
				Swal.fire('Unauthorized', 'You are not logged in.', 'error');
				return;
			}
			if (systems.status === 500) {
				Swal.fire('Error', systems.message || 'Failed to fetch systems.', 'error');
				return;
			}

			// populate dropdown
			systems.forEach(sys => {
				select.innerHTML += `<option value="${sys.system_id}">${sys.system_name}</option>`;
			});
		} catch (err) {
			console.error('Error loading systems:', err);
			Swal.fire('Error', 'Could not load systems.', 'error');
		}
	}


	// ===== Assign System =====
	async function assignSystem() {
		const portalId = document.getElementById('assignPortalId').value;
		const systemId = document.getElementById('systemSelect').value;
		const role = document.getElementById('roleSelect').value;

		if (!systemId || !role) {
			Swal.fire('Error', 'Please select a system and role.', 'error');
			return;
		}

		try {
			const res = await fetch('../api/assignment/assign_system_subadmin.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					portal_id: portalId,
					system_id: systemId,
					role: role
				})
			});

			const result = await res.json();

			if (result.status === 200) {
				Swal.fire('Success', result.message, 'success');
				bootstrap.Modal.getInstance(document.getElementById('assignSystemModal')).hide();
				loadUsers(); // Refresh user table
				loadAssignedSystems(); // Refresh assigned systems table
			} else {
				Swal.fire(result.title || 'Error', result.message, 'error');
			}

		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Failed to assign system.', 'error');
		}
	}

	// ===== Init =====
	document.addEventListener('DOMContentLoaded', () => {
		// optional: preload users
	});
	</script>

	<style>
	/* Paliitin ang height ng select fields */
	#filterType,
	#filterValue {
		height: 38px;
		/* default ~40px, pwede palitan ng 35px kung gusto mas maliit */
		font-size: 14px;
	}

	/* Palakihin ang width ng Apply Filter button */
	.d-flex.gap-2.w-100.w-md-4 .btn-dark {
		min-width: 150px;
		/* pwede baguhin depende sa gusto mo */
		font-size: 14px;
		height: 38px;
		/* para same height sa select */
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
			padding: 10px;
			margin-bottom: 5px;
		}

		.expand-body {
			padding: 12px 16px;
			font-size: 13px;
		}

		#searchInput {
			width: 100%;
			margin-bottom: 5px;
		}
	}
	</style>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>