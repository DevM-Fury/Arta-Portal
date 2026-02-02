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
</head>

<body>
	<div class="container py-4">
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