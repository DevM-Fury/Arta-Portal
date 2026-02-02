<!DOCTYPE html>
<html lang="en">

<head>
	<title>ARTA Registration</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	body {
		background: #e6e9f2;
		font-family: 'Poppins', sans-serif;
	}

	.card {
		border: none;
		border-radius: 20px;
		box-shadow: 0 12px 48px rgba(0, 48, 143, 0.45), 0 2px 16px rgba(0, 0, 0, 0.35);
		background: #fff;
		padding: 2.5rem 2rem;
	}

	.btn-custom {
		background-color: #00308F;
		color: #fff;
		font-weight: 700;
		border-radius: 8px;
		border: none;
		box-shadow: 0 4px 18px rgba(0, 48, 143, 0.18);
	}

	.btn-custom:hover {
		background-color: #6699CC;
		color: #00308F;
	}
	</style>
</head>

<body>
	<div class="container d-flex align-items-center justify-content-center vh-100">
		<div class="card col-md-6">
			<h3 class="text-center mb-4">ARTA Portal Registration</h3>
			<form id="registerForm">
				<div class="mb-3">
					<label class="form-label">National ID</label>
					<input type="text" name="national_id" class="form-control" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Firstname</label>
					<input type="text" name="firstname" class="form-control" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Lastname</label>
					<input type="text" name="lastname" class="form-control" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Contact</label>
					<input type="text" name="contact" class="form-control" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Email</label>
					<input type="email" name="email" class="form-control" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Password</label>
					<input type="password" name="password" class="form-control" required>
				</div>
				<div class="mb-3">
					<label class="form-label">Confirm Password</label>
					<input type="password" name="confirm_password" class="form-control" required>
				</div>
				<button type="submit" class="btn btn-custom w-100">Register</button>
			</form>
		</div>
	</div>

	<script>
	const form = document.getElementById('registerForm');

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		try {
			// 1. Fetch token
			const tokenRes = await fetch("../../api/auth/get_csrf.php");
			const tokenData = await tokenRes.json();

			// 2. Collect form data
			const formData = Object.fromEntries(new FormData(form));

			// 3. Send request
			const res = await fetch("../api/auth/register.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"X-REG-TOKEN": tokenData.token
				},
				body: JSON.stringify(formData)
			});

			const data = await res.json();

			if (data.status === "success") {
				Swal.fire("Success!", "Registration complete.", "success");
				form.reset();
			} else {
				Swal.fire("Error!", data.message, "error");
			}
		} catch (err) {
			Swal.fire("Error!", "Something went wrong.", "error");
			console.error(err);
		}
	});
	</script>
</body>

</html>