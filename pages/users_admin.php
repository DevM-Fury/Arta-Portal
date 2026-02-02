<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Admin - Manage Users</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="p-4">
	<h2>Manage Users</h2>
	<table class="table table-bordered" id="usersTable">
		<thead>
			<tr>
				<th>Portal ID</th>
				<th>Name</th>
				<th>Email</th>
				<th>Role</th>
				<th>Date Created</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<a href="dashboard_admin.php" class="btn btn-secondary mt-3">Back</a>

	<script>
	async function loadUsers() {
		const res = await fetch("../api/admin_users.php");
		const data = await res.json();
		if (data.status === "success") {
			const tbody = document.querySelector("#usersTable tbody");
			tbody.innerHTML = "";
			data.data.forEach(user => {
				tbody.innerHTML += `
                <tr>
                    <td>${user.portal_id}</td>
                    <td>${user.firstname} ${user.lastname}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>${user.date_created}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="updateRole('${user.portal_id}', '${user.role}')">Change Role</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser('${user.portal_id}')">Delete</button>
                    </td>
                </tr>
            `;
			});
		}
	}

	async function updateRole(id, currentRole) {
		const {
			value: role
		} = await Swal.fire({
			title: "Update Role",
			input: "select",
			inputOptions: {
				user: "User",
				admin: "Admin"
			},
			inputValue: currentRole,
			showCancelButton: true
		});

		if (role) {
			const res = await fetch("../api/admin_users.php", {
				method: "PUT",
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify({
					portal_id: id,
					role
				})
			});
			const data = await res.json();
			Swal.fire(data.status, data.message, data.status);
			loadUsers();
		}
	}

	async function deleteUser(id) {
		const confirm = await Swal.fire({
			title: "Are you sure?",
			text: "This action cannot be undone!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes, delete"
		});

		if (confirm.isConfirmed) {
			const res = await fetch("../api/admin_users.php", {
				method: "DELETE",
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify({
					portal_id: id
				})
			});
			const data = await res.json();
			Swal.fire(data.status, data.message, data.status);
			loadUsers();
		}
	}

	loadUsers();
	</script>
</body>

</html>