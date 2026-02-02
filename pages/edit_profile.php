<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Edit Profile</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="p-4">
	<div class="container col-md-6">
		<h2>Edit Profile</h2>
		<form id="profileForm">
			<div class="mb-3">
				<label>First Name</label>
				<input type="text" name="firstname" class="form-control" required>
			</div>
			<div class="mb-3">
				<label>Last Name</label>
				<input type="text" name="lastname" class="form-control" required>
			</div>
			<div class="mb-3">
				<label>Contact</label>
				<input type="text" name="contact" class="form-control">
			</div>
			<div class="mb-3">
				<label>Email (readonly)</label>
				<input type="email" name="email" class="form-control" readonly>
			</div>
			<div class="mb-3">
				<label>New Password (leave blank if not changing)</label>
				<input type="password" name="password" class="form-control">
			</div>
			<button class="btn btn-primary">Save Changes</button>
			<a href="dashboard_user.php" class="btn btn-secondary">Back</a>
		</form>
	</div>

	<script>
	async function loadProfile() {
		const res = await fetch("../api/user_profile.php");
		const data = await res.json();
		if (data.status === "success") {
			for (let key in data.data) {
				if (document.querySelector(`[name="${key}"]`)) {
					document.querySelector(`[name="${key}"]`).value = data.data[key];
				}
			}
		}
	}

	document.getElementById("profileForm").addEventListener("submit", async (e) => {
		e.preventDefault();
		const formData = Object.fromEntries(new FormData(e.target));

		const res = await fetch("../api/user_profile.php", {
			method: "PUT",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(formData)
		});

		const data = await res.json();
		if (data.status === "success") {
			Swal.fire("Updated!", data.message, "success").then(() => {
				window.location.href = "dashboard_user.php";
			});
		} else {
			Swal.fire("Error!", data.message, "error");
		}
	});

	loadProfile();
	</script>
</body>

</html>