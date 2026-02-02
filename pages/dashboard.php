<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

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

	<style>
	.system-card {
		perspective: 1000px;
		cursor: pointer;
	}

	.card-inner {
		position: relative;
		width: 100%;
		height: 220px;
		text-align: center;
		transition: transform 0.6s;
		transform-style: preserve-3d;
	}

	.system-card.flipped .card-inner {
		transform: rotateY(180deg);
	}

	.card-front,
	.card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
		border-radius: 15px;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
	}

	.card-front {
		background: white;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	}

	.card-back {
		background: linear-gradient(135deg, #002b45, #005f91);
		color: white;
		transform: rotateY(180deg);
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}

	.card-back i {
		font-size: 50px;
		color: white;
		margin-bottom: 10px;
	}

	.card-back p {
		margin: 0;
		font-size: 14px;
		color: #e0e0e0;
	}

	.system-icon {
		font-size: 45px;
		color: #0d6efd;
		margin-bottom: 10px;
	}

	.card-title {
		font-size: 18px;
		font-weight: 600;
		margin-bottom: 5px;
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
							<div class="card-inner">
								<div class="card-front">
									<i class="fa-solid ${iconClass} system-icon"></i>
									<h5 class="card-title">${system.system_name}</h5>
									<p class="text-muted mb-0">${system.role.toUpperCase()}</p>
								</div>
								<div class="card-back">
									<i class="fa-solid fa-right-to-bracket"></i>
									<p>Click for SSO Access</p>
								</div>
							</div>
						</div>
					`;

				const systemCard = card.querySelector('.system-card');
				systemCard.addEventListener('click', () => {
					systemCard.classList.toggle('flipped');
				});

				const cardBack = card.querySelector('.card-back');
				cardBack.addEventListener('click', (e) => {
					e.stopPropagation();
					goToSystem(system.domain_url);
				});

				container.appendChild(card);
			});
		} catch (err) {
			console.error('Failed to load dashboard systems:', err);
			container.innerHTML = `<div class="col-12 text-center text-danger">Failed to load systems.</div>`;
		}
	}

	function goToSystem(url) {
		if (!url) {
			Swal.fire('Error', 'System URL not available.', 'error');
			return;
		}
		window.location.href = url;
	}

	window.addEventListener('DOMContentLoaded', loadDashboardSystems);
	</script>
</body>

</html>