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
	<style>
		body {
			min-height: 100vh;
			background:
				radial-gradient(circle at top left, rgba(24, 119, 210, 0.14), transparent 28%),
				radial-gradient(circle at bottom right, rgba(66, 183, 42, 0.1), transparent 24%),
				linear-gradient(180deg, #f4f8fc 0%, #eef4fb 52%, #f9fbff 100%);
			padding: 40px 20px;
			color: #16304a;
		}
		.admin-shell {
			max-width: 1180px;
			margin: 0 auto;
			background: rgba(255,255,255,0.88);
			border: 1px solid rgba(15, 23, 42, 0.08);
			border-radius: 28px;
			box-shadow: 0 24px 56px rgba(15, 23, 42, 0.1);
			backdrop-filter: blur(16px);
			padding: 28px;
		}
		h2 {
			font-weight: 700;
			margin-bottom: 20px;
		}
		.table {
			--bs-table-bg: transparent;
			--bs-table-hover-bg: rgba(24, 119, 210, 0.05);
		}
		.table thead th {
			background: linear-gradient(135deg, #10345f, #1877d2);
			color: #fff;
			border-bottom: none;
		}
		.table td,
		.table th {
			vertical-align: middle;
			border-color: rgba(15, 23, 42, 0.06);
		}
		.btn {
			border-radius: 14px;
			font-weight: 700;
		}
		.btn-secondary {
			border-color: rgba(15, 23, 42, 0.12);
		}
	</style>
</head>

<body>
	<div class="admin-shell">
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
	</div>