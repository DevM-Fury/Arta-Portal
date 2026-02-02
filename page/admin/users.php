<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Management</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	body {
		background-color: #f8f9fa;
	}

	.container {
		margin-top: 40px;
	}
	</style>
</head>

<body>
	<div class="container">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h3>Users Management</h3>
			<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add User</button>
		</div>

		<table class="table table-bordered table-striped">
			<thead class="table-dark">
				<tr>
					<th>National ID</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Contact</th>
					<th>Email</th>
					<th>Role</th>
					<th>Date Created</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id="userTableBody">
				<tr>
					<td colspan="8" class="text-center text-muted">Loading...</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="userModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalTitle">Add User</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="userForm">
						<input type="hidden" id="portal_id">

						<div class="mb-2">
							<label>National ID</label>
							<input type="text" id="national_id" class="form-control" required>
						</div>
						<div class="mb-2">
							<label>First Name</label>
							<input type="text" id="firstname" class="form-control" required>
						</div>
						<div class="mb-2">
							<label>Last Name</label>
							<input type="text" id="lastname" class="form-control" required>
						</div>
						<div class="mb-2">
							<label>Contact</label>
							<input type="text" id="contact" class="form-control" required>
						</div>
						<div class="mb-2">
							<label>Email</label>
							<input type="email" id="email" class="form-control" required>
						</div>
						<div class="mb-2">
							<label>Password</label>
							<input type="password" id="password" class="form-control" required>
						</div>
						<div class="mb-2">
							<label>Role</label>
							<select id="role" class="form-select">
								<option value="user">User</option>
								<option value="admin">Admin</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button class="btn btn-success" id="saveBtn">Save</button>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
	document.addEventListener("DOMContentLoaded", loadUsers);

	async function loadUsers() {
		const tbody = document.getElementById("userTableBody");
		tbody.innerHTML = '<tr><td colspan="8" class="text-center">Loading...</td></tr>';

		const res = await fetch("api/users/get_users.php");
		const data = await res.json();

		if (!data.success) {
			tbody.innerHTML = '<tr><td colspan="8" class="text-danger text-center">Failed to fetch users</td></tr>';
			return;
		}

		tbody.innerHTML = "";
		data.users.forEach(user => {
			tbody.innerHTML += `
			<tr>
				<td>${user.national_id}</td>
				<td>${user.firstname}</td>
				<td>${user.lastname}</td>
				<td>${user.contact}</td>
				<td>${user.email}</td>
				<td>${user.role}</td>
				<td>${user.date_created}</td>
				<td>
					<button class="btn btn-warning btn-sm me-1" onclick='editUser(${JSON.stringify(user)})'>Edit</button>
					<button class="btn btn-danger btn-sm" onclick="deleteUser('${user.portal_id}')">Delete</button>
				</td>
			</tr>
		`;
		});
	}

	document.getElementById("saveBtn").addEventListener("click", async () => {
		const data = {
			portal_id: document.getElementById("portal_id").value,
			national_id: document.getElementById("national_id").value,
			firstname: document.getElementById("firstname").value,
			lastname: document.getElementById("lastname").value,
			contact: document.getElementById("contact").value,
			email: document.getElementById("email").value,
			password: document.getElementById("password").value,
			role: document.getElementById("role").value,
		};

		const endpoint = data.portal_id ? "api/users/update_user.php" : "api/users/create_user.php";

		const res = await fetch(endpoint, {
			method: "POST",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(data),
		});
		const result = await res.json();
		Swal.fire(result.title, result.message, result.icon);

		if (result.success) {
			document.getElementById("userForm").reset();
			const modal = bootstrap.Modal.getInstance(document.getElementById("userModal"));
			modal.hide();
			loadUsers();
		}
	});

	function editUser(user) {
		document.getElementById("modalTitle").innerText = "Edit User";
		document.getElementById("portal_id").value = user.portal_id;
		document.getElementById("national_id").value = user.national_id;
		document.getElementById("firstname").value = user.firstname;
		document.getElementById("lastname").value = user.lastname;
		document.getElementById("contact").value = user.contact;
		document.getElementById("email").value = user.email;
		document.getElementById("password").value = "";
		document.getElementById("role").value = user.role;
		new bootstrap.Modal(document.getElementById("userModal")).show();
	}

	async function deleteUser(portal_id) {
		const confirm = await Swal.fire({
			title: "Are you sure?",
			text: "This will permanently delete the user.",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes, delete it!",
		});
		if (!confirm.isConfirmed) return;

		const res = await fetch("api/users/delete_user.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify({
				portal_id
			}),
		});
		const result = await res.json();
		Swal.fire(result.title, result.message, result.icon);
		if (result.success) loadUsers();
	}
	</script>
</body>

</html>