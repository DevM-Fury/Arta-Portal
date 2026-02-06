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
		padding: 20px;
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

	.system-logo {
		width: 70px;
		height: 70px;
		object-fit: contain;
		margin-bottom: 15px;
		border-radius: 8px;
	}

	.system-icon-placeholder {
		font-size: 45px;
		color: #0d6efd;
		margin-bottom: 15px;
	}

	.card-title {
		font-size: 18px;
		font-weight: 600;
		margin-bottom: 5px;
		color: #333;
	}
	
	.card-role {
		font-size: 14px;
		color: #6c757d;
		background: #f8f9fa;
		padding: 3px 10px;
		border-radius: 20px;
		margin-top: 5px;
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

				let logoHtml = '';
				
				// Gumamit ng logo_path kung available
				if (system.logo_path && system.logo_path.trim() !== '') {
					// Direct path - nasa same directory (pages) ang dashboard.php
					// at logo files (assuming logo_path includes uploads/systems/)
					const logoPath = system.logo_path;
					
					logoHtml = `
						<img src="${logoPath}" 
							 alt="${system.system_name}" 
							 class="system-logo" 
							 onerror="this.onerror=null; 
									  this.classList.add('system-icon-placeholder');
									  this.outerHTML = '<i class=\"fa-solid fa-cogs system-icon-placeholder\"></i>';">`;
				} else {
					// Fallback sa icon kung walang logo
					let iconClass = getSystemIcon(system.system_name);
					logoHtml = `<i class="fa-solid ${iconClass} system-icon-placeholder"></i>`;
				}

				card.innerHTML = `
						<div class="system-card">
							<div class="card-inner">
								<div class="card-front">
									${logoHtml}
									<h5 class="card-title">${system.system_name}</h5>
									<div class="card-role">${system.role.toUpperCase()}</div>
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

	// Function para sa fallback icons
	function getSystemIcon(systemName) {
		const name = systemName.toLowerCase();
		if (name.includes('subadmin') || name.includes('admin')) return 'fa-shield-alt';
		else if (name.includes('lto') || name.includes('license')) return 'fa-id-card';
		else if (name.includes('hr') || name.includes('human')) return 'fa-users';
		else if (name.includes('finance') || name.includes('payroll')) return 'fa-dollar-sign';
		else if (name.includes('inventory') || name.includes('stock')) return 'fa-boxes';
		else if (name.includes('sales') || name.includes('customer')) return 'fa-shopping-cart';
		else if (name.includes('portal') || name.includes('gateway')) return 'fa-door-open';
		else return 'fa-cogs';
	}

	function goToSystem(url) {
		if (!url) {
			Swal.fire('Error', 'System URL not available.', 'error');
			return;
		}
		// Dagdagan ng loading indicator bago mag-redirect
		Swal.fire({
			title: 'Redirecting...',
			text: 'Please wait while we connect you to the system',
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});
		
		// Mag-redirect after ng konting delay
		setTimeout(() => {
			window.location.href = url;
		}, 1000);
	}

	window.addEventListener('DOMContentLoaded', loadDashboardSystems);
	</script>
</body>

</html>