<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-users me-2"></i> Users Management
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
						<div class="d-flex gap-2 w-100 w-md-auto align-items-center">
							<input id="searchInput" class="form-control shadow-sm border-primary flex-grow-1"
								placeholder="🔍 Search user...">
							<select id="rowsPerPage" class="form-select form-select-sm ms-2" style="width:110px;">
								<option value="5" selected>5</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
							</select>
							<button class="btn btn-dark shadow-sm ms-2" data-bs-toggle="modal"
								data-bs-target="#addUserModal">
								<i class="fa-solid fa-plus me-2"></i>Add New User
							</button>
						</div>
					</div>

					<div class="table-responsive table-responsive-sm">
						<table class="table custom-table align-middle text-center mb-0 table-striped">
							<thead class="table-dark">
								<tr>
									<th>ID</th>
									<th>National ID</th>
									<th>Firstname</th>
									<th>Lastname</th>
									<th>Contact</th>
									<th>Email</th>
									<th>Role</th>
									<th>Created</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tableBody"></tbody>
						</table>

						<div
							class="d-flex justify-content-between align-items-center mt-3 flex-column flex-md-row gap-2">
							<div class="text-muted" id="tableInfo"></div>
							<div id="pagination" class="d-flex align-items-center"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- ADD USER MODAL -->
	<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="addUserForm">
					<div class="modal-header">
						<h5 class="modal-title">Add New User</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">National ID</label>
								<input type="text" name="national_id" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Firstname</label>
								<input type="text" name="firstname" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Lastname</label>
								<input type="text" name="lastname" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Contact</label>
								<input type="text" name="contact" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Email</label>
								<input type="email" name="email" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Password</label>
								<input type="password" name="password" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Role</label>
								<select name="role" class="form-select">
									<option value="user" selected>User</option>
									<option value="admin">Admin</option>
								</select>
							</div>
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

	<!-- EDIT USER MODAL -->
	<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title"><i class="fa-solid fa-user-pen me-2"></i>Edit User</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="editForm">
						<input type="hidden" id="editPortalId">
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">First Name</label>
								<input type="text" id="editFirstname" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Last Name</label>
								<input type="text" id="editLastname" class="form-control" required>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Contact</label>
								<input type="text" id="editContact" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label">Email</label>
								<input type="email" id="editEmail" class="form-control" required>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Role</label>
								<select id="editRole" class="form-select" required>
									<option value="">Select role</option>
									<option value="admin">Admin</option>
									<option value="subadmin">Subadmin</option>
									<option value="user">User</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">New Password (optional)</label>
								<input type="password" id="editPassword" class="form-control"
									placeholder="Leave blank to keep current password">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="fa-solid fa-xmark me-1"></i> Cancel
					</button>
					<button type="button" class="btn btn-success" onclick="updateUser()">
						<i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- ASSIGN TO MODAL -->
	<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-dark text-white">
					<h5 class="modal-title"><i class="fa-solid fa-building-user me-2"></i>Assign User To</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="assignForm">
						<input type="hidden" id="assignPortalId">

						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">Office</label>
								<select id="officeSelect" class="form-select" required>
									<option value="">Select Office</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">Division</label>
								<select id="divisionSelect" class="form-select" required disabled>
									<option value="">Select Division</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">Unit</label>
								<select id="unitSelect" class="form-select" required disabled>
									<option value="">Select Unit</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">Designation</label>
								<select id="designationSelect" class="form-select" required disabled>
									<option value="">Select Designation</option>
								</select>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-success" onclick="saveAssignment()">
						<i class="fa-solid fa-floppy-disk me-1"></i>Save Assignment
					</button>
				</div>
			</div>
		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
	let currentPage = 1;
	let limit = parseInt(document.getElementById('rowsPerPage').value || 5);
	const searchInput = document.getElementById('searchInput');
	const tableBody = document.getElementById('tableBody');
	const tableInfo = document.getElementById('tableInfo');
	const paginationEl = document.getElementById('pagination');

	// helper to avoid double event binding
	function once(el, event, fn) {
		el.removeEventListener(event, fn);
		el.addEventListener(event, fn, {
			once: false
		});
	}

	// ============ LOAD USERS ============
	async function loadUsers(page = 1, query = '') {
		currentPage = page;
		limit = parseInt(document.getElementById('rowsPerPage').value || 5);

		try {
			const res = await fetch(
				`../api/users/get_users.php?page=${page}&limit=${limit}&q=${encodeURIComponent(query)}`);
			const result = await res.json();

			tableBody.innerHTML = '';
			if (result.status !== 200) {
				const msg = result.message || 'Failed to fetch users';
				tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">${msg}</td></tr>`;
				tableInfo.textContent = `No records`;
				return;
			}

			const {
				users,
				totalPages,
				totalRecords,
				currentPage: current
			} = result.data;
			if (!users || users.length === 0) {
				tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">No users found</td></tr>`;
				tableInfo.textContent = `No records`;
				return;
			}

			users.forEach(u => {
				const created = u.date_created ? new Date(u.date_created).toLocaleString() : '';
				tableBody.innerHTML += `
        <tr>
          <td class="text-start" style="max-width:220px;overflow:hidden;text-overflow:ellipsis">${u.portal_id}</td>
          <td>${u.national_id}</td>
          <td>${u.firstname}</td>
          <td>${u.lastname}</td>
          <td>${u.contact}</td>
          <td class="text-start" style="max-width:200px;overflow:hidden;text-overflow:ellipsis">${u.email}</td>
          <td>${u.role}</td>
          <td>${created}</td>
         <td>
			<div class="d-flex justify-content-center gap-1 flex-wrap">
				<button class="btn btn-sm btn-outline-info" onclick="viewTransactions('${u.portal_id}')">
				<i class="fa-solid fa-eye me-1"></i>View
				</button>
				<button class="btn btn-sm btn-outline-primary" onclick="openAssignModal('${u.portal_id}')">
				<i class="fa-solid fa-building-user me-1"></i>Assign To
				</button>
				<button class="btn btn-sm btn-outline-warning" onclick="openEditModal('${u.portal_id}')">Edit</button>
				<button class="btn btn-sm btn-outline-danger" onclick="deleteUser('${u.portal_id}')">Delete</button>
			</div>
			</td>

        </tr>`;
			});

			tableInfo.textContent = `Showing ${users.length} of ${totalRecords} (page ${current})`;
			renderPagination(current, totalPages, query);
		} catch (err) {
			console.error(err);
			tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error loading users</td></tr>`;
		}
	}

	// ============ RENDER PAGINATION ============
	function renderPagination(page, totalPages, query = '') {
		paginationEl.innerHTML = '';
		if (totalPages <= 1) return;
		const btn = (p, label = p) =>
			`<button class="btn btn-sm ${p === page ? 'btn-primary' : 'btn-outline-primary'} mx-1" onclick="loadUsers(${p}, '${query}')">${label}</button>`;
		paginationEl.innerHTML += btn(Math.max(1, page - 1), 'Prev');
		for (let i = 1; i <= totalPages; i++) paginationEl.innerHTML += btn(i);
		paginationEl.innerHTML += btn(Math.min(totalPages, page + 1), 'Next');
	}

	// ============ ADD USER ============
	document.getElementById('addUserForm').addEventListener('submit', async e => {
		e.preventDefault();
		const formData = Object.fromEntries(new FormData(e.target).entries());
		try {
			const res = await fetch('../api/users/create_user.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(formData)
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire({
					icon: 'success',
					title: 'User Added',
					text: result.message,
					timer: 1500,
					showConfirmButton: false
				});
				e.target.reset();
				bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
				loadUsers(1, searchInput.value.trim());
			} else {
				Swal.fire('Error', result.message || 'Failed to add user', 'error');
			}
		} catch (err) {
			Swal.fire('Error', 'Server error', 'error');
		}
	});

	// ============ OPEN EDIT MODAL ============
	async function openEditModal(id) {
		try {
			const res = await fetch(`../api/users/get_users.php?id=${id}`);
			const result = await res.json();
			if (result.status !== 200) return Swal.fire('Error', 'User not found', 'error');
			const d = result.data;
			document.getElementById('editPortalId').value = d.portal_id;
			document.getElementById('editFirstname').value = d.firstname;
			document.getElementById('editLastname').value = d.lastname;
			document.getElementById('editContact').value = d.contact;
			document.getElementById('editEmail').value = d.email;
			document.getElementById('editRole').value = d.role;
			document.getElementById('editPassword').value = '';
			new bootstrap.Modal(document.getElementById('editModal')).show();
		} catch (err) {
			Swal.fire('Error', 'Server error', 'error');
		}
	}

	// ============ ASSIGN: open modal & load offices ============
	async function openAssignModal(portal_id) {
		// reset selects
		document.getElementById('assignPortalId').value = portal_id;
		resetAssignModal();

		const modalEl = document.getElementById('assignModal');
		const modal = new bootstrap.Modal(modalEl);
		modal.show();

		// load offices
		await loadOffices();

		// prebind change listeners once
		bindAssignEvents();
	}

	function resetAssignModal() {
		const office = document.getElementById('officeSelect');
		const division = document.getElementById('divisionSelect');
		const unit = document.getElementById('unitSelect');
		const designation = document.getElementById('designationSelect');

		office.innerHTML = `<option value="">Select Office</option>`;
		division.innerHTML = `<option value="">Select Division</option>`;
		unit.innerHTML = `<option value="">Select Unit</option>`;
		designation.innerHTML = `<option value="">Select Designation</option>`;

		division.disabled = true;
		unit.disabled = true;
		designation.disabled = true;
	}

	function bindAssignEvents() {
		// remove previous listeners by cloning nodes (clean way)
		['officeSelect', 'divisionSelect', 'unitSelect'].forEach(id => {
			const old = document.getElementById(id);
			const parent = old.parentNode;
			const clone = old.cloneNode(true);
			parent.replaceChild(clone, old);
		});

		document.getElementById('officeSelect').addEventListener('change', async (e) => {
			const officeId = e.target.value;
			if (!officeId) {
				// reset downstream
				document.getElementById('divisionSelect').innerHTML =
					`<option value="">Select Division</option>`;
				document.getElementById('divisionSelect').disabled = true;
				document.getElementById('unitSelect').innerHTML = `<option value="">Select Unit</option>`;
				document.getElementById('unitSelect').disabled = true;
				document.getElementById('designationSelect').innerHTML =
					`<option value="">Select Designation</option>`;
				document.getElementById('designationSelect').disabled = true;
				return;
			}
			await loadDivisions(officeId);
		});

		document.getElementById('divisionSelect').addEventListener('change', async (e) => {
			const divisionId = e.target.value;
			if (!divisionId) {
				document.getElementById('unitSelect').innerHTML = `<option value="">Select Unit</option>`;
				document.getElementById('unitSelect').disabled = true;
				document.getElementById('designationSelect').innerHTML =
					`<option value="">Select Designation</option>`;
				document.getElementById('designationSelect').disabled = true;
				return;
			}
			await loadUnits(divisionId);
		});

		document.getElementById('unitSelect').addEventListener('change', async (e) => {
			const unitId = e.target.value;
			if (!unitId) {
				document.getElementById('designationSelect').innerHTML =
					`<option value="">Select Designation</option>`;
				document.getElementById('designationSelect').disabled = true;
				return;
			}
			await loadDesignations(unitId);
		});
	}

	// ============ loadOffices uses your existing api/offices/get_offices.php ============
	async function loadOffices() {
		const select = document.getElementById('officeSelect');
		select.innerHTML = `<option value="">Loading...</option>`;
		try {
			const res = await fetch('../api/offices/get_offices.php?limit=1000&page=1');
			const result = await res.json();
			if (result.status === 200 && result.data && result.data.offices) {
				select.innerHTML = `<option value="">Select Office</option>`;
				result.data.offices.forEach(o => {
					// adjust fields if your office row uses different keys (id vs office_id)
					const id = o.id ?? o.office_id ?? o.officeId;
					const name = o.office_name ?? o.name ?? o.officeName;
					select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
				});
			} else {
				select.innerHTML = `<option value="">No offices found</option>`;
			}
		} catch (err) {
			console.error('Error loading offices:', err);
			select.innerHTML = `<option value="">Error loading</option>`;
		}
	}

	// ============ loadDivisions (placeholder endpoint path matches earlier suggestion) ============
	async function loadDivisions(officeId) {
		const select = document.getElementById('divisionSelect');
		select.disabled = true;
		select.innerHTML = `<option>Loading...</option>`;
		try {
			// endpoint returns { data: { divisions: [...] } }
			const res = await fetch(`../api/divisions/get_divisions.php?office_id=${officeId}&limit=1000&page=1`);
			const result = await res.json();
			select.innerHTML = `<option value="">Select Division</option>`;
			if (result.status === 200 && result.data && result.data.divisions && result.data.divisions.length) {
				result.data.divisions.forEach(d => {
					const id = d.id ?? d.division_id;
					const name = d.division_name ?? d.name ?? d.divisionName;
					select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
				});
				select.disabled = false;
			} else {
				select.innerHTML = `<option value="">No divisions found</option>`;
				select.disabled = true;
			}
		} catch (err) {
			console.error('Error loading divisions:', err);
			select.innerHTML = `<option value="">Error loading</option>`;
			select.disabled = true;
		}
	}

	// ============ loadUnits ============
	async function loadUnits(divisionId) {
		const select = document.getElementById('unitSelect');
		select.disabled = true;
		select.innerHTML = `<option>Loading...</option>`;
		try {
			const res = await fetch(`../api/units/get_units.php?division_id=${divisionId}&limit=1000&page=1`);
			const result = await res.json();
			select.innerHTML = `<option value="">Select Unit</option>`;
			if (result.status === 200 && result.data && result.data.units && result.data.units.length) {
				result.data.units.forEach(u => {
					const id = u.id ?? u.unit_id;
					const name = u.unit_name ?? u.name ?? u.unitName;
					select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
				});
				select.disabled = false;
			} else {
				select.innerHTML = `<option value="">No units found</option>`;
				select.disabled = true;
			}
		} catch (err) {
			console.error('Error loading units:', err);
			select.innerHTML = `<option value="">Error loading</option>`;
			select.disabled = true;
		}
	}

	// ============ loadDesignations ============
	async function loadDesignations(unitId) {
		const select = document.getElementById('designationSelect');
		select.disabled = true;
		select.innerHTML = `<option>Loading...</option>`;
		try {
			const res = await fetch(`../api/designations/get_designations.php?unit_id=${unitId}&limit=1000&page=1`);
			const result = await res.json();
			select.innerHTML = `<option value="">Select Designation</option>`;
			if (result.status === 200 && result.data && result.data.designations && result.data.designations.length) {
				result.data.designations.forEach(d => {
					const id = d.id ?? d.designation_id;
					const name = d.designation_name ?? d.name ?? d.designationName;
					select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
				});
				select.disabled = false;
			} else {
				select.innerHTML = `<option value="">No designations found</option>`;
				select.disabled = true;
			}
		} catch (err) {
			console.error('Error loading designations:', err);
			select.innerHTML = `<option value="">Error loading</option>`;
			select.disabled = true;
		}
	}

	// ============ SAVE ASSIGNMENT ============
	async function saveAssignment() {
		const portal_id = document.getElementById('assignPortalId').value;
		const office_id = document.getElementById('officeSelect').value;
		const division_id = document.getElementById('divisionSelect').value;
		const unit_id = document.getElementById('unitSelect').value;
		const designation_id = document.getElementById('designationSelect').value;

		if (!office_id || !division_id || !unit_id || !designation_id) {
			Swal.fire('Warning', 'Please complete all fields', 'warning');
			return;
		}

		try {
			const res = await fetch('../api/users/assign_user.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					portal_id,
					office_id,
					division_id,
					unit_id,
					designation_id
				})
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire('Assigned!', result.message || 'Assignment saved', 'success');
				bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
				loadUsers(currentPage, searchInput.value.trim());
			} else {
				Swal.fire('Error', result.message || 'Failed to assign', 'error');
			}
		} catch (err) {
			console.error('Error saving assignment:', err);
			Swal.fire('Error', 'Server error', 'error');
		}
	}

	// ============ UPDATE USER ============
	async function updateUser() {
		const data = {
			portal_id: document.getElementById('editPortalId').value,
			firstname: document.getElementById('editFirstname').value,
			lastname: document.getElementById('editLastname').value,
			contact: document.getElementById('editContact').value,
			email: document.getElementById('editEmail').value,
			role: document.getElementById('editRole').value,
			password: document.getElementById('editPassword').value
		};
		try {
			const res = await fetch('../api/users/update_user.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire({
					icon: 'success',
					title: 'Updated',
					text: result.message,
					timer: 1200,
					showConfirmButton: false
				});
				bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
				loadUsers(currentPage, searchInput.value.trim());
			} else {
				Swal.fire('Error', result.message || 'Failed to update', 'error');
			}
		} catch {
			Swal.fire('Error', 'Server error', 'error');
		}
	}

	// ============ VIEW TRANSACTIONS (placeholder) ============
	async function viewTransactions(portal_id) {
		// simple modal UI (you can replace with a full modal as earlier suggested)
		Swal.fire('Info', 'Open transactions modal or page for: ' + portal_id, 'info');
		// Implement the modal and API call as needed
	}

	// ============ DELETE USER ============
	async function deleteUser(id) {
		const confirm = await Swal.fire({
			title: 'Are you sure?',
			text: 'This action cannot be undone!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'Cancel'
		});
		if (!confirm.isConfirmed) return;
		try {
			const res = await fetch('../api/users/delete_user.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					portal_id: id
				})
			});
			const result = await res.json();
			if (result.status === 200) {
				Swal.fire('Deleted!', result.message, 'success');
				loadUsers(currentPage, searchInput.value.trim());
			} else {
				Swal.fire('Error', result.message || 'Failed to delete', 'error');
			}
		} catch {
			Swal.fire('Error', 'Server error', 'error');
		}
	}

	// ============ EVENTS ============
	document.getElementById('rowsPerPage').addEventListener('change', () => loadUsers(1, searchInput.value.trim()));
	searchInput.addEventListener('input', () => {
		clearTimeout(window.searchTimer);
		window.searchTimer = setTimeout(() => loadUsers(1, searchInput.value.trim()), 350);
	});
	document.addEventListener('DOMContentLoaded', () => loadUsers(1));

	// ============ small helpers ============
	function escapeHtml(s) {
		if (!s) return '';
		return String(s).replace(/[&<>"'`=\/]/g, function(c) {
			return {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#39;',
				'/': '&#x2F;',
				'`': '&#x60;',
				'=': '&#x3D;'
			} [c];
		});
	}
	</script>

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

	#pagination button {
		margin: 2px;
		border-radius: 6px;
		font-size: 13px;
	}

	#pagination {
		display: flex;
		align-items: center;
		justify-content: center;
	}
	</style>


	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>