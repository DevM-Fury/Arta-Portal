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
						<div class="d-flex gap-2 w-100 w-md-auto">
							<input id="searchInput" class="form-control shadow-sm border-primary flex-grow-1"
								placeholder="🔍 Search user...">
							<button class="btn btn-primary shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addUserModal">
								<i class="fa-solid fa-plus me-2"></i>Add New User
							</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead class="table-dark">
								<tr>
									<!-- <th>Portal ID</th> -->
									<th>National ID</th>
									<th>Firstname</th>
									<th>Lastname</th>
									<th>Contact</th>
									<th>Email</th>
									<th>Role</th>
									<th>Created</th>
									<th>Modified</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tableBody"></tbody>
						</table>
						<div class="d-flex justify-content-between align-items-center mt-3">
							<div>
								<label for="rowsPerPage" class="me-2 fw-semibold">Rows per page:</label>
								<select id="rowsPerPage" class="form-select form-select-sm d-inline-block"
									style="width: 100px;">
									<option value=5" selected>5</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="50">50</option>
								</select>
							</div>
							<div id="pagination"></div>
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
							<div class="col-md-6"><label class="form-label">National ID</label><input type="number"
									name="national_id" class="form-control" required></div>
							<div class="col-md-6"><label class="form-label">Firstname</label><input type="text"
									name="firstname" class="form-control" required></div>
							<div class="col-md-6"><label class="form-label">Lastname</label><input type="text"
									name="lastname" class="form-control" required></div>
							<div class="col-md-6"><label class="form-label">Contact</label><input type="text"
									name="contact" class="form-control" required></div>
							<div class="col-md-6"><label class="form-label">Email</label><input type="email"
									name="email" class="form-control" required></div>
							<div class="col-md-6"><label class="form-label">Password</label><input type="password"
									name="password" class="form-control" required></div>
							<div class="col-md-6"><label class="form-label">Role</label>
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
	<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="editUserForm">
					<input type="hidden" name="portal_id" id="editPortalId">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title">Edit User</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="row g-3">
							<div class="col-md-6">
								<label>First Name</label>
								<input type="text" name="firstname" id="editFirstname" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label>Last Name</label>
								<input type="text" name="lastname" id="editLastname" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label>Contact</label>
								<input type="text" name="contact" id="editContact" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label>Email</label>
								<input type="email" name="email" id="editEmail" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label>Role</label>
								<select name="role" id="editRole" class="form-select">
									<option value="user">User</option>
									<option value="admin">Admin</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
	// ====================== GLOBAL SETTINGS ======================
	let currentPage = 1;
	let limit = 8; // Default rows per page

	// ====================== ADD USER ======================
	document.getElementById('addUserForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const form = this;
		const data = Object.fromEntries(new FormData(form));

		try {
			const res = await fetch('../api/users/create_user.php', {
				method: 'POST',
				body: JSON.stringify(data)
			});
			const result = await res.json();

			if (result.status === 200) {
				Swal.fire({
					icon: 'success',
					title: 'Created',
					text: result.message,
					timer: 1400,
					showConfirmButton: false
				}).then(() => {
					bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
					form.reset();
					loadUsers(1);
				});
			} else {
				Swal.fire('Error', result.message || 'Failed', 'error');
			}
		} catch (err) {
			Swal.fire('Error', 'Request failed', 'error');
		}
	});

	// ====================== LOAD USERS ======================
	async function loadUsers(page = 1) {
		currentPage = page;
		try {
			const res = await fetch(`../api/users/get_users.php?page=${page}&limit=${limit}`);
			const result = await res.json();
			const tableBody = document.getElementById('tableBody');
			tableBody.innerHTML = '';

			if (result.status === 200) {
				const {
					users,
					totalPages
				} = result.data;

				if (users.length === 0) {
					tableBody.innerHTML =
						`<tr><td colspan="10" class="text-center text-muted">No users found</td></tr>`;
					renderPagination(1, 1);
					return;
				}

				users.forEach(u => {
					const row = `
					<tr>
						<td>${u.national_id}</td>
						<td>${u.firstname}</td>
						<td>${u.lastname}</td>
						<td>${u.contact}</td>
						<td>${u.email}</td>
						<td>${u.role}</td>
						<td>${new Date(u.date_created).toLocaleString('en-US', {month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true})}</td>
						<td>${new Date(u.modified_date).toLocaleString('en-US', {month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true})}</td>
						<td>
							<button class="btn btn-sm btn-outline-warning" onclick='openEditUserModal(${JSON.stringify(u)})'>Edit</button>
							<button class="btn btn-sm btn-outline-danger" onclick="deleteUser('${u.portal_id}')">Delete</button>
						</td>
					</tr>
				`;
					tableBody.innerHTML += row;
				});

				renderPagination(currentPage, totalPages);
			} else {
				tableBody.innerHTML =
					`<tr><td colspan="10" class="text-center text-danger">${result.message}</td></tr>`;
			}
		} catch (err) {
			console.error(err);
			document.getElementById('tableBody').innerHTML =
				`<tr><td colspan="10" class="text-center text-danger">Failed to fetch users</td></tr>`;
		}
	}

	// ====================== PAGINATION SYSTEM ======================
	function renderPagination(page, totalPages) {
		const pagination = document.getElementById('pagination');
		pagination.innerHTML = '';

		if (totalPages <= 1) return;

		let html = `
		<button class="btn btn-sm btn-outline-secondary me-1" ${page === 1 ? 'disabled' : ''} onclick="loadUsers(${page - 1})">
			<i class="fa-solid fa-chevron-left"></i> Prev
		</button>
	`;

		// show max 5 page buttons
		const maxPagesToShow = 5;
		let startPage = Math.max(1, page - Math.floor(maxPagesToShow / 2));
		let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

		if (endPage - startPage < maxPagesToShow - 1) {
			startPage = Math.max(1, endPage - maxPagesToShow + 1);
		}

		for (let i = startPage; i <= endPage; i++) {
			html += `
			<button class="btn btn-sm ${i === page ? 'btn-primary' : 'btn-outline-primary'} mx-1" onclick="loadUsers(${i})">${i}</button>
		`;
		}

		html += `
		<button class="btn btn-sm btn-outline-secondary ms-1" ${page === totalPages ? 'disabled' : ''} onclick="loadUsers(${page + 1})">
			Next <i class="fa-solid fa-chevron-right"></i>
		</button>
	`;

		pagination.innerHTML = html;
	}

	// ====================== ROWS PER PAGE DROPDOWN ======================
	document.getElementById('rowsPerPage').addEventListener('change', function() {
		limit = parseInt(this.value);
		loadUsers(1);
	});

	// UPDATE USER
	document.getElementById('editUserForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const data = Object.fromEntries(new FormData(this));

		try {
			const res = await fetch('../api/users/update_user.php', {
				method: 'POST',
				body: JSON.stringify(data)
			});
			const result = await res.json();

			if (result.status === 200) {
				Swal.fire({
					icon: 'success',
					title: 'Updated',
					text: result.message,
					timer: 1400,
					showConfirmButton: false
				}).then(() => {
					bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
					loadUsers(currentPage);
				});
			} else {
				Swal.fire('Error', result.message || 'Failed', 'error');
			}
		} catch (err) {
			Swal.fire('Error', 'Request failed', 'error');
		}
	});

	// OPEN EDIT MODAL
	function openEditUserModal(user) {
		document.getElementById('editPortalId').value = user.portal_id;
		document.getElementById('editFirstname').value = user.firstname;
		document.getElementById('editLastname').value = user.lastname;
		document.getElementById('editContact').value = user.contact;
		document.getElementById('editEmail').value = user.email;
		document.getElementById('editRole').value = user.role;
		new bootstrap.Modal(document.getElementById('editUserModal')).show();
	}


	// DELETE USER

	function deleteUser(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'This will permanently delete the user.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete',
			cancelButtonText: 'Cancel'
		}).then(async (r) => {
			if (r.isConfirmed) {
				try {
					const res = await fetch('../api/users/delete_user.php', {
						method: 'POST',
						body: JSON.stringify({
							portal_id: id
						})
					});
					const result = await res.json();
					if (result.status === 200) {
						Swal.fire('Deleted', result.message, 'success');
						loadUsers(currentPage);
					} else {
						Swal.fire('Error', result.message || 'Failed', 'error');
					}
				} catch (err) {
					Swal.fire('Error', 'Request failed', 'error');
				}
			}
		});
	}
	// ====================== INITIAL LOAD ======================
	document.addEventListener('DOMContentLoaded', () => {
		loadUsers(1);
	});
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


	<!-- ✅ Footer include -->
	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>