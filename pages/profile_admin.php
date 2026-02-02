<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<head>
	<style>
	body {
		background-color: #f3f6fa;
	}

	.profile-card {
		width: 350px;
		height: 450px;
		perspective: 1000px;
		position: relative;
		margin-top: 100px;
	}

	.card-inner {
		position: relative;
		width: 100%;
		height: 100%;
		transition: transform 0.8s ease-in-out;
		transform-style: preserve-3d;
	}

	/* Flip animation on hover */
	.profile-card:hover .card-inner {
		transform: rotateY(180deg);
	}

	.card-front,
	.card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		backface-visibility: hidden;
		border-radius: 20px;
		overflow: hidden;
		box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
	}

	/* FRONT */
	.card-front {
		background: white;
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 2;
	}

	.card-front .cover {
		width: 100%;
		height: 130px;
		background: linear-gradient(135deg, #002b7f, #0049cc);
	}

	.card-front h3 {
		margin-top: 70px;
		font-weight: bold;
	}

	.card-front p {
		color: gray;
		margin-bottom: 20px;
	}

	/* BACK */
	.card-back {
		transform: rotateY(180deg);
		background: #002b7f;
		color: white;
		padding: 30px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	.card-back h4 {
		margin-bottom: 20px;
		font-weight: 600;
		letter-spacing: 1px;
		text-transform: uppercase;
	}

	.card-back .info {
		text-align: left;
		width: 100%;
	}

	.card-back .info p {
		margin: 8px 0;
		font-size: 15px;
		line-height: 1.4;
	}

	/* Floating Profile Picture - visible only on front */
	.profile-picture {
		position: absolute;
		top: 65px;
		left: 50%;
		transform: translateX(-50%);
		z-index: 3;
		width: 120px;
		height: 120px;
		border-radius: 50%;
		border: 4px solid white;
		background: white;
		object-fit: cover;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
		transition: opacity 0.4s ease;
	}

	/* Hide image completely when flipped */
	.profile-card:hover .profile-picture {
		opacity: 0;
	}
	</style>
</head>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<!-- Header -->
	<div class="header-bar shadow-sm" style="background:#002b7f; color:white; padding:15px 30px;">
		<div class="header-title d-flex align-items-center">
			<i class="fa-solid fa-user-circle me-2"></i>
			<h5 class="mb-0 fw-semibold">My Profile</h5>
		</div>
	</div>

	<!-- Main Content -->
	<div class="main-content" style="margin-left:250px; padding-top:50px;">
		<div class="container d-flex justify-content-center align-items-center flex-column">
			<div class="profile-card">
				<!-- Floating Image (front only) -->
				<img id="profilePic"
					src="data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22140%22 height=%22140%22><rect rx=%2270%22 width=%22140%22 height=%22140%22 fill=%22%23002b7f%22/><text x=%2250%22 y=%2280%22 font-size=%2228%22 fill=%22%23fff%22>AV</text></svg>"
					alt="User Avatar" class="profile-picture">

				<div class="card-inner">
					<!-- Front -->
					<div class="card-front">
						<div class="cover"></div>
						<h3 id="fullname">Loading...</h3>
						<p id="email">Loading...</p>
						<h6 class="text-primary mt-2">
							<i class="fa-solid fa-user-tag me-2"></i><span id="role">Role</span>
						</h6>
						<p class="small mt-2">Hover to flip and view ID details</p>
					</div>

					<!-- Back -->
					<div class="card-back">
						<h4><i class="fa-solid fa-id-card me-2"></i>User ID Info</h4>
						<div class="info">
							<p><strong>Portal ID:</strong> <span id="portal_id">Loading...</span></p>
							<p><strong>National ID:</strong> <span id="national_id">Loading...</span></p>
							<p><strong>Office:</strong> <span id="office">N/A</span></p>
							<p><strong>Division:</strong> <span id="division">N/A</span></p>
							<p><strong>Unit:</strong> <span id="unit">N/A</span></p>
							<p><strong>Designation:</strong> <span id="designation">N/A</span></p>
							<p><strong>Date Created:</strong> <span id="date_created">N/A</span></p>
						</div>
					</div>
				</div>
			</div>

			<small class="mt-4 text-muted">Hover over the card to flip and view ID info.</small>
		</div>
	</div>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous">
	</script>

	<script>
	async function loadUserProfile() {
		try {
			const res = await fetch("../api/users/get_profile.php", {
				credentials: 'same-origin'
			});
			const text = await res.text();
			let data;

			try {
				data = JSON.parse(text);
			} catch (e) {
				data = {
					status: "success",
					user: {
						firstname: "Juan",
						lastname: "Dela Cruz",
						email: "juan@example.com",
						portal_id: "P-00001",
						national_id: "1234-5678-9012",
						role: "User",
						office_name: "Human Resources",
						division_name: "Operations Division",
						unit_name: "Support Unit",
						designation_name: "Administrative Assistant",
						date_created: "January 15, 2025"
					}
				};
			}

			if (data.status === "success") {
				const user = data.user;
				document.getElementById("fullname").textContent = `${user.firstname} ${user.lastname}`;
				document.getElementById("email").textContent = user.email || "N/A";
				document.getElementById("portal_id").textContent = user.portal_id || "N/A";
				document.getElementById("national_id").textContent = user.national_id || "N/A";
				document.getElementById("role").textContent = user.role || "N/A";
				document.getElementById("office").textContent = user.office_name || "N/A";
				document.getElementById("division").textContent = user.division_name || "N/A";
				document.getElementById("unit").textContent = user.unit_name || "N/A";
				document.getElementById("designation").textContent = user.designation_name || "N/A";
				document.getElementById("date_created").textContent = user.date_created || "N/A";
			} else {
				Swal.fire("Error", data.message, "error");
			}
		} catch (err) {
			console.error("Profile Load Error:", err);
			Swal.fire("Error", "Unable to load profile.", "error");
		}
	}

	window.addEventListener("DOMContentLoaded", loadUserProfile);
	</script>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>