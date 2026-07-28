<?php
session_start();
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}
$system_id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>System Users</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		body {
			min-height: 100vh;
			background:
				radial-gradient(circle at top left, rgba(24, 119, 210, 0.14), transparent 28%),
				radial-gradient(circle at bottom right, rgba(66, 183, 42, 0.1), transparent 24%),
				linear-gradient(180deg, #f4f8fc 0%, #eef4fb 52%, #f9fbff 100%);
			padding: 20px;
		}
		.system-shell {
			max-width: 1040px;
			margin: 24px auto;
			background: rgba(255,255,255,0.9);
			border: 1px solid rgba(15, 23, 42, 0.08);
			border-radius: 28px;
			box-shadow: 0 24px 56px rgba(15, 23, 42, 0.1);
			padding: 28px;
			backdrop-filter: blur(16px);
		}
		h3 {
			font-weight: 700;
			color: #16304a;
		}
		.table {
			--bs-table-bg: transparent;
			--bs-table-striped-bg: rgba(24, 119, 210, 0.03);
		}
		.table thead th {
			background: linear-gradient(135deg, #10345f, #1877d2);
			color: #fff;
			border-bottom: none;
		}
	</style>
</head>

<body>
	<div class="system-shell">
		<h3>Users Assigned to System</h3>
		<table class="table table-striped mt-3">
			<thead>
				<tr>
					<th>#</th>
					<th>Full Name</th>
					<th>Role</th>
					<th>Date Assigned</th>
				</tr>
			</thead>
			<tbody id="userTableBody">
				<tr>
					<td colspan="4" class="text-center">Loading...</td>
				</tr>
			</tbody>
		</table>
	</div>

	<script>
	const systemId = <?= json_encode($system_id) ?>;

	async function loadSystemUsers() {
		try {
			const res = await fetch(`../api/assignment/get_assigned_users.php?system_id=${systemId}`);
			const users = await res.json();
			const tbody = document.getElementById('userTableBody');

			if (!users.length) {
				tbody.innerHTML =
					`<tr><td colspan="4" class="text-center text-muted">No users assigned to this system.</td></tr>`;
				return;
			}

			tbody.innerHTML = '';
			users.forEach((u, i) => {
				tbody.innerHTML += `
                <tr>
                    <td>${i+1}</td>
                    <td>${u.user_firstname} ${u.user_lastname}</td>
                    <td>${u.role}</td>
                    <td>${u.date_assigned}</td>
                </tr>
            `;
			});
		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Failed to load users.', 'error');
		}
	}

	document.addEventListener('DOMContentLoaded', loadSystemUsers);
	</script>
</body>

</html>