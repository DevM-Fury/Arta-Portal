<?php
// pages/dashboard_user.php
// session configs bago session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

session_start(); // Start session dito


require_once __DIR__ . '/../includes/configs.php'; // Sundan ang existing config

// ✅ Get portal_id from session
$portalId = $_SESSION['user']['portal_id'] ?? null;
if (!$portalId) {
    die('Portal ID not set in session. Please login.');
}

try {
    // ✅ Total Users
    $stmt = $conn->prepare("SELECT COUNT(*) FROM public.user_tbl WHERE role='user' AND portal_id = :portal_id");
    $stmt->execute(['portal_id' => $portalId]);
    $totalUsers = $stmt->fetchColumn();

    // ✅ Total Active Subadmins
    $stmt = $conn->prepare("SELECT COUNT(*) FROM public.user_system_access WHERE portal_id = :portal_id");
    $stmt->execute(['portal_id' => $portalId]);
    $totalActiveUsers = $stmt->fetchColumn();

} catch (PDOException $e) {
    die("Failed to fetch dashboard counts: " . $e->getMessage());
}

// ✅ Example HTML Output
?>

<!DOCTYPE html>
<html lang="en">

<!-- ✅ Head include -->
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<!-- ✅ Sidebar include -->
	<?php include_once __DIR__ . '/../includes/sidebar_user.php'; ?>

	<!-- HEADER -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-building-columns"></i> User Dashboard
		</div>

		<!-- USER ICON + DROPDOWN -->
		<div class="header-user dropdown">
			<i class="fa-solid fa-user dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
				aria-expanded="false"></i>
			<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
				<li><a class="dropdown-item" href="#">Profile Settings</a></li>
				<li>
					<hr class="dropdown-divider">
				</li>
				<li><a class="dropdown-item" href="#">Logout</a></li>
			</ul>
		</div>
	</div>

	<div class="main-content">
		<div class="container-fluid">
			<!-- Top Cards -->
			<div class="card-container mb-5">


				<div class="card text-center">
					<div class="card-icon"><i class="fa-solid fa-user-check"></i></div>
					<h5 class="card-title">Assigned system as Admin</h5>
					<p class="display-6"><?= htmlspecialchars($totalActiveUsers) ?></p>
				</div>

				<!-- <div class="card text-center">
					<div class="card-icon"><i class="fa-solid fa-gears"></i></div>
					<h5 class="card-title">Total Users</h5>
					<p class="display-6"><?= htmlspecialchars($totalActiveUsers) ?></p>

				</div> -->

			</div>
		</div>
	</div>


	<!-- ✅ Footer include -->
	<?php include_once __DIR__ . '/../includes/footer.php'; ?>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>