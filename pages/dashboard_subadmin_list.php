<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

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

	<!-- <style>
	.system-card {
		perspective: 1000px;
		cursor: pointer;
	}

	.flip-card-inner {
		position: relative;
		width: 100%;
		height: 220px;
		transition: transform 0.6s;
		transform-style: preserve-3d;
	}

	/* flipped state */
	.system-card.flipped .flip-card-inner {
		transform: rotateY(180deg);
	}

	.flip-card-front,
	.flip-card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
		border-radius: 15px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.flip-card-front {
		background: white;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 20px;
	}

	.flip-card-back {
		background: linear-gradient(135deg, #0d6efd, #6610f2);
		color: white;
		transform: rotateY(180deg);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
	}

	.flip-card-back button {
		background: white;
		color: #0d6efd;
		border: none;
		padding: 8px 16px;
		border-radius: 50px;
		font-weight: 600;
		transition: 0.3s;
	}

	.flip-card-back button:hover {
		background: #f8f9fa;
		transform: scale(1.05);
	}
	</style> -->

	<style>
	.system-card {
		perspective: 1000px;
		cursor: pointer;
	}

	.flip-card-inner {
		position: relative;
		width: 100%;
		height: 220px;
		transition: transform 0.6s;
		transform-style: preserve-3d;
	}

	.system-card.flipped .flip-card-inner {
		transform: rotateY(180deg);
	}

	.flip-card-front,
	.flip-card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
		border-radius: 15px;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
	}

	/* FRONT CARD */
	.flip-card-front {
		background: white;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 20px;
		transition: transform 0.3s ease;
	}

	.flip-card-front i {
		font-size: 45px;
		color: #0d6efd;
		margin-bottom: 10px;
	}

	.flip-card-front h5 {
		font-size: 18px;
		font-weight: 600;
		margin-bottom: 5px;
	}

	.flip-card-front p {
		color: #6c757d;
		font-size: 14px;
	}

	/* BACK CARD */
	.flip-card-back {
		transform: rotateY(180deg);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		background: radial-gradient(circle at top left, #002b45, #005f91, #001f33);
		color: #fff;
		overflow: hidden;
		position: relative;
	}

	/* Decorative hex pattern effect */
	.flip-card-back::before {
		content: "";
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
		background-size: 40px 40px;
		opacity: 0.3;
	}

	.flip-card-back h5 {
		font-size: 18px;
		font-weight: 600;
		margin-bottom: 5px;
		z-index: 1;
	}

	.flip-card-back p {
		font-size: 13px;
		color: #d9e3f0;
		margin-bottom: 15px;
		z-index: 1;
	}

	/* SSO BUTTON */
	.flip-card-back button {
		background: white;
		color: #0d6efd;
		border: none;
		padding: 8px 16px;
		border-radius: 50px;
		font-weight: 600;
		z-index: 1;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.flip-card-back button:hover {
		background: #f8f9fa;
		transform: scale(1.08);
	}
	</style>


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

				let iconClass = 'fa-cogs';
				const name = system.system_name.toLowerCase();
				if (name.includes('subadmin')) iconClass = 'fa-shield-alt';
				else if (name.includes('lto')) iconClass = 'fa-network-wired';
				else if (name.includes('hr')) iconClass = 'fa-users';
				else if (name.includes('finance')) iconClass = 'fa-dollar-sign';

				card.innerHTML = `
					<div class="system-card">
						<div class="flip-card-inner">
							<div class="flip-card-front">
								<i class="fa-solid ${iconClass} mb-3" style="font-size:40px; color:#0d6efd;"></i>
								<h5 class="card-title">${system.system_name}</h5>
								<p class="text-muted mb-0">${system.role.toUpperCase()}</p>
							</div>
							<div class="flip-card-back">
								<h5>${system.system_name}</h5>
								<p class="mb-3">${system.role.toUpperCase()}</p>
								<button onclick="goToSystem('${system.domain_url}', '${system.system_id}')">
									Access via SSO
								</button>
							</div>
						</div>
					</div>
				`;

				const cardEl = card.querySelector('.system-card');
				cardEl.addEventListener('click', (e) => {
					// prevent flip when clicking the button
					if (e.target.tagName.toLowerCase() === 'button') return;
					cardEl.classList.toggle('flipped');
				});

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
			if (!res.ok) throw new Error(`Network response was not ok (${res.status})`);
			const data = await res.json();

			if (data.status === "success") {
				Swal.fire({
					title: 'Redirecting...',
					text: 'Opening target system',
					icon: 'success',
					timer: 900,
					showConfirmButton: false
				});
				setTimeout(() => window.location.href = data.redirect, 900);
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