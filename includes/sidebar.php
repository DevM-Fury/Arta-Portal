<!-- ✅ Responsive Sidebar with Overlay + Floating Toggle Button (Dark Elegant) -->
<div id="sidebar-overlay"></div>

<nav id="sidebar" class="sidebar d-flex flex-column">
	<div class="sidebar-title mb-4">
		<i class="fa-solid fa-gauge-high me-2"></i>ARTA Dashboard
	</div>

	<a href="dashboard.php" class="nav-link active"><i class="fa-solid fa-house"></i>Home</a>
	<a href="users.php" class="nav-link"><i class="fa-solid fa-users"></i>Users</a>

	<div class="nav-item">
		<a href="#assignmentsSubMenu" class="nav-link" data-bs-toggle="collapse" aria-expanded="false">
			<i class="fa-solid fa-id-card"></i>Assignments
		</a>
		<div class="collapse sub-menu" id="assignmentsSubMenu">
			<a href="office.php" class="nav-link sub-item"><i class="fa-regular fa-file"></i>Office</a>
			<a href="divisions.php" class="nav-link sub-item"><i class="fa-regular fa-file"></i>Division</a>
			<a href="units.php" class="nav-link sub-item"><i class="fa-regular fa-file"></i>Unit</a>
		</div>
	</div>

	<a href="designation.php" class="nav-link"><i class="fa-solid fa-person"></i>Designation</a>
	<a href="system.php" class="nav-link"><i class="fa-solid fa-desktop"></i>System</a>
	<a href="system_assignment.php" class="nav-link"><i class="fa-solid fa-list"></i> System Access </a>
    <a href="system_logs.php" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i>System Logs</a>
    <a href="transaction_logs.php" class="nav-link"><i class="fa-solid fa-file-invoice"></i>Transaction Logs</a>
	<a href="dashboard.php" class="nav-link"><i class="fa-solid fa-gear"></i>Assigned System</a>
	<a href="profile_admin.php" class="nav-link"><i class="fa-solid fa-user"></i>My Profile</a>
	<a href="logout.php" class="nav-link"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>





</nav>

<!-- ✅ FLOATING BUTTON – DARK ELEGANT DESIGN -->
<button id="sidebarToggleBtn" class="sidebar-toggle-btn" aria-label="Toggle menu">
    <i class="fa-solid fa-bars"></i>
</button>

<style>
/* ===== SIDEBAR – ORIGINAL DESIGN (WALANG BINAGO) ===== */
.sidebar {
    background: linear-gradient(180deg, #07182f 0%, #0b2d59 58%, #123d77 100%);
    color: #fff;
    min-height: 100vh;
    padding: 1.6rem 1rem 1rem;
    position: fixed;
    left: 0;
    top: 0;
    width: 220px;
    border-right: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 14px 0 40px rgba(5, 19, 43, 0.28);
    backdrop-filter: blur(16px);
    transition: left 0.3s ease;
    z-index: 150;
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.92) !important;
    font-weight: 600;
    padding: 13px 16px;
    border-radius: 16px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background .2s, color .2s, transform .2s;
}

.sidebar .nav-link.active,
.sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff !important;
    transform: translateX(2px);
}

.sidebar .sidebar-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
    padding: 0.9rem 1rem;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
}

/* ===== OVERLAY – ORIGINAL ===== */
#sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 140;
    transition: opacity 0.3s ease;
}

/* ===== FLOATING BUTTON – DARK ELEGANT (BAGONG DESIGN) ===== */
.sidebar-toggle-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #07182f, #0b2d59);
    backdrop-filter: blur(4px);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.22);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.28);
    font-size: 26px;
    display: none; /* itatago sa desktop, ipapakita sa mobile */
    align-items: center;
    justify-content: center;
    z-index: 9999;
    cursor: pointer;
    transition: all 0.2s ease;
}

.sidebar-toggle-btn:hover {
    background: linear-gradient(135deg, #0b2d59, #17477f);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
}

/* ===== MOBILE VIEW ===== */
@media (max-width: 991px) {
    .sidebar {
        left: -240px; /* nakatago sa simula */
    }

    .sidebar.show {
        left: 0; /* lilitaw kapag may .show class */
    }

    #sidebar-overlay.show {
        display: block;
        opacity: 1;
    }

    /* Ipakita ang floating button sa mobile */
    .sidebar-toggle-btn {
        display: flex;
    }

    /* Kapag bukas ang sidebar, itago ang button */
    .sidebar.show ~ .sidebar-toggle-btn {
        display: none;
    }
}

/* ===== DESKTOP – itago ang floating button ===== */
@media (min-width: 992px) {
    .sidebar-toggle-btn {
        display: none;
    }
}

/* ===== SOBRANG LIIT NA MOBILE – adjust size ===== */
@media (max-width: 480px) {
    .sidebar-toggle-btn {
        width: 50px;
        height: 50px;
        font-size: 22px;
        bottom: 20px;
        right: 20px;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    const toggleBtn = document.getElementById("sidebarToggleBtn");

    // Toggle function
    function toggleSidebar(show) {
        if (show === undefined) {
            sidebar.classList.toggle("show");
            overlay.classList.toggle("show");
        } else {
            if (show) {
                sidebar.classList.add("show");
                overlay.classList.add("show");
            } else {
                sidebar.classList.remove("show");
                overlay.classList.remove("show");
            }
        }
    }

    // Click sa floating button
    if (toggleBtn) {
        toggleBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    // Click sa overlay para isara
    if (overlay) {
        overlay.addEventListener("click", function() {
            toggleSidebar(false);
        });
    }

    // Kapag nag-resize pabalik sa desktop, tanggalin ang 'show'
    function handleResize() {
        if (window.innerWidth > 991) {
            sidebar.classList.remove("show");
            overlay.classList.remove("show");
        }
    }
    window.addEventListener("resize", handleResize);
    handleResize();
});
</script>