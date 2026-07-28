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
	<style>
		body {
			min-height: 100vh;
			background:
				radial-gradient(circle at top left, rgba(24, 119, 210, 0.14), transparent 28%),
				radial-gradient(circle at bottom right, rgba(66, 183, 42, 0.1), transparent 24%),
				linear-gradient(180deg, #f4f8fc 0%, #eef4fb 52%, #f9fbff 100%);
			padding: 24px 20px;
		}
		.profile-shell {
			max-width: 720px;
			margin: 24px auto;
			background: rgba(255,255,255,0.9);
			border: 1px solid rgba(15, 23, 42, 0.08);
			border-radius: 28px;
			box-shadow: 0 24px 56px rgba(15, 23, 42, 0.1);
			padding: 28px;
			backdrop-filter: blur(16px);
		}
		h2 {
			font-weight: 700;
			color: #16304a;
			margin-bottom: 20px;
		}
		.form-control {
			border-radius: 14px;
			border: 1px solid rgba(15, 23, 42, 0.12);
			padding: 12px 14px;
		}
		.form-control:focus {
			border-color: rgba(24, 119, 210, 0.5);
			box-shadow: 0 0 0 4px rgba(24, 119, 210, 0.12);
		}
		.btn {
			border-radius: 14px;
			font-weight: 700;
		}
	</style>
</head>

<body>
	<div class="profile-shell">
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