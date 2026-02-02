<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<style>
/* --- FLIP CARD CONTAINER --- */
.system-card {
	perspective: 1000px;
	position: relative;
	height: 250px;
	border: none;
	background: transparent;
}

/* --- INNER WRAPPER --- */
.system-card-inner {
	position: relative;
	width: 100%;
	height: 100%;
	transition: transform 0.8s;
	transform-style: preserve-3d;
	cursor: pointer;
}

/* --- FRONT & BACK --- */
.system-card-front,
.system-card-back {
	position: absolute;
	width: 100%;
	height: 100%;
	backface-visibility: hidden;
	border-radius: 20px;
	box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	color: white;
	font-family: 'Poppins', sans-serif;
}

/* --- FRONT --- */
.system-card-front {
	background: radial-gradient(circle at center, rgba(13, 110, 253, 0.8), #002b7a);
}

/* --- ORB GLOW --- */
.orb {
	width: 70px;
	height: 70px;
	border-radius: 50%;
	margin-bottom: 15px;
	box-shadow: 0 0 20px 5px rgba(13, 110, 253, 0.6);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 30px;
}

/* --- BACK --- */
.system-card-back {
	background: rgba(2, 2, 108, 0.95);
	border: 2px solid rgba(13, 110, 253, 0.5);
	transform: rotateY(180deg);
}

.system-card:hover .system-card-inner {
	transform: rotateY(180deg);
}

.enter-btn {
	background: linear-gradient(135deg, #0d6efd, #6610f2);
	border: none;
	padding: 10px 20px;
	color: #fff;
	border-radius: 10px;
	font-weight: 600;
	transition: 0.3s;
}

.enter-btn:hover {
	background: linear-gradient(135deg, #6610f2, #0d6efd);
	transform: scale(1.05);
}

/* --- COLOR VARIANTS --- */
.orb.subadmin {
	box-shadow: 0 0 25px 6px rgba(255, 0, 0, 0.7);
	background: rgba(255, 0, 0, 0.2);
}

.orb.lto {
	box-shadow: 0 0 25px 6px rgba(13, 110, 253, 0.7);
	background: rgba(13, 110, 253, 0.2);
}

.orb.hr {
	box-shadow: 0 0 25px 6px rgba(40, 167, 69, 0.7);
	background: rgba(40, 167, 69, 0.2);
}

.orb.finance {
	box-shadow: 0 0 25px 6px rgba(255, 193, 7, 0.7);
	background: rgba(255, 193, 7, 0.2);
}

.system-card-front h5 {
	font-size: 1.2rem;
	font-weight: 600;
	margin-bottom: 5px;
}

.system-card-front p {
	font-size: 0.9rem;
	opacity: 0.8;
}
</style>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar_user.php'; ?>

	<!-- Header -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-network-wired me-2"></i> Dashboard
		</div>
	</div>

	<!-- Main Content -->
	<div class="main-content" style="margin-left:250px; padding-top:80px;">
		<div class="container-fluid mt-4">
			<div class="row" id="dashboardCards">
				<div class="col-12 text-center" id="loadingText">Loading systems...</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous">
	</script>

	<script>
	async function loadDashboardSystems() {
		const container = document.getElementById('dashboardCards');
		const loadingText = document.getElementById('loadingText');

		try {
			const res = await fetch('../api/assignment/get_dashboard_systems.php');
			const data = await res.json();

			container.innerHTML = '';

			if (!data.length) {
				container.innerHTML = `<div class="col-12 text-center text-muted">No systems assigned.</div>`;
				return;
			}

			data.forEach(system => {
				const card = document.createElement('div');
				card.className = 'col-md-3 mb-4';

				// Determine color class
				let orbClass = 'default';
				let iconClass = 'fa-cogs';
				const name = system.system_name.toLowerCase();
				if (name.includes('subadmin')) {
					iconClass = 'fa-shield-alt';
					orbClass = 'subadmin';
				} else if (name.includes('lto')) {
					iconClass = 'fa-network-wired';
					orbClass = 'lto';
				} else if (name.includes('hr')) {
					iconClass = 'fa-users';
					orbClass = 'hr';
				} else if (name.includes('finance')) {
					iconClass = 'fa-dollar-sign';
					orbClass = 'finance';
				}

				card.innerHTML = `
				<div class="system-card">
					<div class="system-card-inner">
						<!-- FRONT -->
						<div class="system-card-front">
							<div class="orb ${orbClass}">
								<i class="fa-solid ${iconClass}"></i>
							</div>
							<h5>${system.system_name}</h5>
							<p>${system.role.toUpperCase()}</p>
						</div>
						<!-- BACK -->
						<div class="system-card-back">
							<h6 class="mb-3">${system.system_name}</h6>
							<button class="enter-btn" onclick="goToSystem('${system.domain_url}', '${system.system_id}')">
								<i class="fa-solid fa-right-to-bracket me-2"></i> Enter System
							</button>
						</div>
					</div>
				</div>`;
				container.appendChild(card);
			});
		} catch (err) {
			console.error('Failed to load dashboard systems:', err);
			container.innerHTML = `<div class="col-12 text-center text-danger">Failed to load systems.</div>`;
		}
	}

	async function goToSystem(systemUrl, systemId) {
		if (!systemUrl || !systemId) {
			Swal.fire('Error', 'Missing system details.', 'error');
			return;
		}

		Swal.fire({
			title: 'Generating SSO token...',
			text: 'Please wait',
			allowOutsideClick: false,
			didOpen: () => Swal.showLoading()
		});

		try {
			const apiUrl =
				`../api/sso/generate_token.php?system_url=${encodeURIComponent(systemUrl)}&system_id=${encodeURIComponent(systemId)}`;
			const res = await fetch(apiUrl, {
				credentials: 'same-origin'
			});

			if (!res.ok) throw new Error(`Network error ${res.status}`);

			const data = await res.json();
			if (data.status === "success") {
				Swal.fire({
					title: 'Redirecting...',
					text: 'Opening target system',
					icon: 'success',
					timer: 5000,
					showConfirmButton: false
				});
				setTimeout(() => {
					window.location.href = data.redirect;
				}, 900);
			} else {
				Swal.fire('Error', data.message || 'Failed to generate SSO token.', 'error');
			}
		} catch (err) {
			console.error('SSO generation error', err);
			Swal.fire('Error', 'Server error: ' + err.message, 'error');
		}
	}

	window.addEventListener('DOMContentLoaded', loadDashboardSystems);
	</script>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>