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

	<style>
	.system-card {
		perspective: 1000px;
		cursor: pointer;
		height: 220px;
	}

	.flip-card-inner {
		position: relative;
		width: 100%;
		height: 100%;
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

	/* System Logo Styles */
	.system-logo {
		width: 60px;
		height: 60px;
		object-fit: contain;
		margin-bottom: 15px;
		border-radius: 8px;
	}

	.system-icon {
		font-size: 40px;
		color: #0d6efd;
		margin-bottom: 15px;
	}

	.flip-card-front h5 {
		font-size: 18px;
		font-weight: 600;
		margin-bottom: 5px;
		color: #333;
	}

	.role-badge {
		font-size: 14px;
		color: #6c757d;
		background: #f8f9fa;
		padding: 4px 12px;
		border-radius: 20px;
		margin-top: 5px;
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

        // Debug: Check API response
        console.log('Dashboard Systems:', data);

        if (!data || !data.length) {
            container.innerHTML = `<div class="col-12 text-center text-muted">No systems assigned.</div>`;
            return;
        }

        data.forEach(system => {
            const card = document.createElement('div');
            card.className = 'col-md-3 mb-4';

            // Determine whether to show logo or icon
            let logoOrIcon = '';
            
            // Check if logo_path exists and is not empty
            if (system.logo_path && system.logo_path.trim() !== '' && system.logo_path !== 'null') {
                // Use the logo from database
                let logoPath = system.logo_path.trim();
                
                // Adjust the path based on current location
                // Current page: arta-portal/pages/dashboard_subadmin_list.php
                // Logo path from DB: uploads/filename.png
                // We need to go up one level to access uploads folder
                
                // If it's a relative path (not starting with http:// or https://)
                if (!logoPath.startsWith('http://') && !logoPath.startsWith('https://') && !logoPath.startsWith('../')) {
                    logoPath = '../' + logoPath;
                }
                
                // Encode the URL to handle special characters like spaces
                const encodedLogoPath = encodeURI(logoPath);
                
                logoOrIcon = `
                    <img src="${encodedLogoPath}" 
                         alt="${system.system_name}" 
                         class="system-logo"
                         onerror="this.onerror=null; 
                                  this.style.display='none';
                                  const fallbackIcon = document.createElement('i');
                                  fallbackIcon.className = 'fa-solid ${getFallbackIcon(system.system_name)} system-icon';
                                  this.parentNode.insertBefore(fallbackIcon, this);">`;
            } else {
                // Use fallback icon if no logo
                const iconClass = getFallbackIcon(system.system_name);
                logoOrIcon = `<i class="fa-solid ${iconClass} system-icon"></i>`;
            }

            // Escape single quotes and backslashes to prevent JavaScript errors in onclick
            const safeSystemName = system.system_name || '';
            const safeDomainUrl = system.domain_url || '';
            const safeSystemId = system.system_id ? String(system.system_id) : '';
            const safeRole = system.role || '';

            // Instead of using onclick attribute, we'll attach event listener later
            const buttonId = `sso-btn-${safeSystemId}`;
            
            card.innerHTML = `
                <div class="system-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            ${logoOrIcon}
                            <h5 class="card-title">${safeSystemName}</h5>
                            <div class="role-badge">${safeRole.toUpperCase()}</div>
                        </div>
                        <div class="flip-card-back">
                            <h5>${safeSystemName}</h5>
                            <p class="mb-3">${safeRole.toUpperCase()}</p>
                            <button id="${buttonId}">
                                Access via SSO
                            </button>
                        </div>
                    </div>
                </div>
            `;

            const cardEl = card.querySelector('.system-card');
            cardEl.addEventListener('click', (e) => {
                // Prevent flip when clicking the button
                if (e.target.tagName.toLowerCase() === 'button') return;
                cardEl.classList.toggle('flipped');
            });

            // Attach event listener to the button
            const button = card.querySelector(`#${buttonId}`);
            button.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent card flip
                goToSystem(safeDomainUrl, safeSystemId);
            });

            container.appendChild(card);
        });
    } catch (err) {
        console.error('Failed to load dashboard systems:', err);
        container.innerHTML = `<div class="col-12 text-center text-danger">Failed to load systems. Please try again.</div>`;
    }
}

// Helper function to get fallback icon class
function getFallbackIcon(systemName) {
    if (!systemName) return 'fa-cogs';
    
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
        const apiUrl = `../api/sso/generate_token.php?system_url=${encodeURIComponent(systemUrl)}&system_id=${encodeURIComponent(systemId)}`;
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

// Test if images are loading correctly
function testImageLoading() {
    console.log('Testing image paths...');
    
    // Test the exact path we expect
    const testPath = '../uploads/1759723058_company_logo (1).png';
    const encodedPath = encodeURI(testPath);
    
    console.log('Test path:', encodedPath);
    
    const img = new Image();
    img.onload = function() {
        console.log('✓ Test image loaded successfully!');
        console.log('  Path:', this.src);
        console.log('  Dimensions:', this.width, 'x', this.height);
    };
    img.onerror = function() {
        console.log('✗ Test image failed to load!');
        console.log('  Tried path:', this.src);
        console.log('  Please check:');
        console.log('  1. File exists in uploads folder');
        console.log('  2. Correct filename (case-sensitive)');
        console.log('  3. File permissions (644)');
        console.log('  4. Folder permissions (755)');
    };
    img.src = encodedPath;
}

// Load dashboard when page loads
window.addEventListener('DOMContentLoaded', function() {
    loadDashboardSystems();
    // Run test after a short delay
    setTimeout(testImageLoading, 1000);
});
</script>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>