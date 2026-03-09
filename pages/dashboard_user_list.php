<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            background: #f4f6f9;
        }

        /* ----- CARD STYLING (base) ----- */
        .system-card {
            cursor: pointer;
            height: 240px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #ffffff, #e9eff6);
        }

        .system-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        /* ----- LOGO / ICON ----- */
        .system-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 12px;
        }

        .system-icon {
            font-size: 45px;
            color: #0d6efd;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #333;
        }

        /* ----- ROLE BADGE (text-muted) ----- */
        .role-badge {
            font-size: 13px;
            color: #6c757d;
            background: none;
            padding: 0;
            border-radius: 0;
            letter-spacing: normal;
            margin-top: 5px;
            font-weight: 400;
            display: inline-block;
        }

        /* ----- STATUS BADGE (top-left) ----- */
        .status-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 30px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .status-active {
            background: linear-gradient(45deg, #198754, #20c997);
        }

        .status-inactive {
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
        }

        /* ----- INACTIVE SYSTEM ----- */
        .system-card.inactive-system {
            pointer-events: none;
            opacity: 0.7;
            filter: grayscale(0.7);
            background: #f1f3f6;
            cursor: default;
        }

        /* ----- TOOLTIP (inactive only) ----- */
        .system-card.inactive-system {
            position: relative;
        }

        .system-card.inactive-system:hover::after {
            content: attr(data-description);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            background: #212529;
            color: #fff;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
            z-index: 9999;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            pointer-events: none;
            line-height: 1.4;
        }

        .system-card.inactive-system:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent #212529 transparent;
            z-index: 9999;
        }

        /* ========== RESPONSIVE MEDIA QUERIES ========== */

        /* --- Tablet (768px - 991px) --- */
        @media (max-width: 991px) {
            .system-card {
                height: 220px;
                padding: 18px;
            }
            .system-logo {
                width: 60px;
                height: 60px;
            }
            .system-icon {
                font-size: 40px;
            }
            .card-title {
                font-size: 18px;
            }
            .role-badge {
                font-size: 12px;
            }
            .status-badge {
                font-size: 10px;
                padding: 4px 10px;
                top: 10px;
                left: 10px;
            }
        }

        /* --- Mobile (max-width: 767px) --- */
        @media (max-width: 767px) {
            /* Para sa sobrang liit, gawing 2 cards per row */
            .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .system-card {
                height: auto;           /* auto height, lalaki depende sa content */
                min-height: 200px;
                padding: 15px;
                margin-bottom: 20px;
            }
            .system-logo {
                width: 55px;
                height: 55px;
                margin-bottom: 12px;
            }
            .system-icon {
                font-size: 38px;
                margin-bottom: 12px;
            }
            .card-title {
                font-size: 16px;
                margin-bottom: 5px;
            }
            .role-badge {
                font-size: 11px;
                margin-top: 3px;
            }
            .status-badge {
                font-size: 9px;
                padding: 3px 8px;
                top: 8px;
                left: 8px;
            }
            /* I-adjust ang tooltip para hindi lumabas sa screen */
            .system-card.inactive-system:hover::after {
                max-width: 200px;
                font-size: 12px;
                white-space: normal;
                left: 50%;
                transform: translateX(-50%) translateY(-8px);
            }
        }

        /* --- Maliit na mobile (max-width: 480px) --- */
        @media (max-width: 480px) {
            .col-md-3 {
                flex: 0 0 100%;        /* 1 card per row */
                max-width: 100%;
            }
            .system-card {
                min-height: 180px;
                padding: 12px;
            }
            .system-logo {
                width: 50px;
                height: 50px;
                margin-bottom: 10px;
            }
            .system-icon {
                font-size: 35px;
                margin-bottom: 10px;
            }
            .card-title {
                font-size: 16px;
            }
            .role-badge {
                font-size: 11px;
            }
        }

        /* --- Para sa malalaking desktop, puwedeng dagdagan ang cards per row --- */
        @media (min-width: 1400px) {
            .col-md-3 {
                flex: 0 0 20%;         /* 5 cards per row sa sobrang laki */
                max-width: 20%;
            }
        }

        /* --- Siguraduhing hindi mag-ooverflow ang sidebar sa mobile --- */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;  /* kung naka-collapse ang sidebar, adjust */
                padding-top: 70px;
            }
        }
    </style>

    <script>
        // ------------------------------------------------------------
        //  HELPER: Fallback icon
        // ------------------------------------------------------------
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

        // ------------------------------------------------------------
        //  SSO REDIRECT (active systems only)
        // ------------------------------------------------------------
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
                const res = await fetch(apiUrl, { credentials: 'same-origin' });
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

        // ------------------------------------------------------------
        //  LOAD DASHBOARD SYSTEMS (CORE)
        // ------------------------------------------------------------
        async function loadDashboardSystems() {
            const container = document.getElementById('dashboardCards');
            const loadingText = document.getElementById('loadingText');

            try {
                const res = await fetch('../api/assignment/get_dashboard_systems.php');
                const data = await res.json();
                container.innerHTML = '';

                console.log('Dashboard Systems:', data);

                if (!data || !data.length) {
                    container.innerHTML = `<div class="col-12 text-center text-muted">No systems assigned.</div>`;
                    return;
                }

                data.forEach(system => {
                    // ----- DETECT ACTIVE STATUS -----
                    const isActive = system.status && system.status.toLowerCase() === 'active';
                    const active = isActive || !system.status;

                    const card = document.createElement('div');
                    card.className = 'col-md-3 mb-4';

                    // ----- LOGO / FALLBACK ICON -----
                    let logoOrIcon = '';
                    if (system.logo_path && system.logo_path.trim() !== '' && system.logo_path !== 'null') {
                        let logoPath = system.logo_path.trim();
                        if (!logoPath.startsWith('http://') && !logoPath.startsWith('https://') && !logoPath.startsWith('../')) {
                            logoPath = '../' + logoPath;
                        }
                        const encodedLogoPath = encodeURI(logoPath);
                        logoOrIcon = `<img src="${encodedLogoPath}" 
                                        alt="${system.system_name}" 
                                        class="system-logo"
                                        onerror="this.onerror=null; 
                                                this.style.display='none';
                                                const fallbackIcon = document.createElement('i');
                                                fallbackIcon.className = 'fa-solid ${getFallbackIcon(system.system_name)} system-icon';
                                                this.parentNode.insertBefore(fallbackIcon, this);">`;
                    } else {
                        const iconClass = getFallbackIcon(system.system_name);
                        logoOrIcon = `<i class="fa-solid ${iconClass} system-icon"></i>`;
                    }

                    const safeSystemName = system.system_name || '';
                    const safeDomainUrl = system.domain_url || '';
                    const safeSystemId = system.system_id ? String(system.system_id) : '';
                    const safeRole = system.role || '';

                    // ----- STATUS BADGE -----
                    const statusText = active ? 'Active' : 'Inactive';
                    const statusClass = active ? 'status-active' : 'status-inactive';
                    const statusBadge = `<span class="status-badge ${statusClass}">${statusText}</span>`;

                    // ----- TOOLTIP CONTENT (inactive only) -----
                    const tooltipDescription = system.description 
                        ? system.description 
                        : `${safeSystemName} – ${safeRole} (Inactive)` 
                        || 'System is currently unavailable';
                    
                    // ----- CARD HTML -----
                    card.innerHTML = `
                        <div class="system-card ${!active ? 'inactive-system' : ''}" 
                             data-system-id="${safeSystemId}"
                             data-system-url="${safeDomainUrl}"
                             ${!active ? `data-description="${tooltipDescription.replace(/"/g, '&quot;')}"` : ''}>
                            ${statusBadge}
                            ${logoOrIcon}
                            <h5 class="card-title">${safeSystemName}</h5>
                            <div class="role-badge">${safeRole.toUpperCase()}</div>
                        </div>
                    `;

                    const cardEl = card.querySelector('.system-card');

                    // ----- CLICK EVENT (ACTIVE LANG) -----
                    if (active) {
                        cardEl.addEventListener('click', function(e) {
                            const systemId = this.dataset.systemId;
                            const systemUrl = this.dataset.systemUrl;
                            goToSystem(systemUrl, systemId);
                        });
                    }

                    container.appendChild(card);
                });

            } catch (err) {
                console.error('Failed to load dashboard systems:', err);
                container.innerHTML = `<div class="col-12 text-center text-danger">Failed to load systems. Please try again.</div>`;
            }
        }

        window.addEventListener('DOMContentLoaded', function () {
            loadDashboardSystems();
        });
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>