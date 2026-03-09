<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ARTA PORTAL - System Assignment</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap JS (required for modal) -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

	<style>
	:root {
		--arta-red: #8B0000;
		--arta-light: #fff8e1;
		--arta-gray: #f7f7f7;
		--arta-blue1: #23395d;
		--arta-blue2: #1e2a3a;
		--arta-blue3: #0f2027;
	}

	/* ==============================
   MOBILE-FIRST / FLUID STYLES
============================== */
	body {
		background: linear-gradient(120deg, var(--arta-gray) 60%, var(--arta-light) 100%);
		font-family: 'Poppins', sans-serif;
		margin: 0;
		padding: 0;
	}

	/* HEADER */
	.header-bar {
		background: linear-gradient(90deg, #23395d 0%, #271d5dff 60%, #150e3dff 100%);
		color: #fff;
		padding: clamp(0.4rem, 1vw, 0.8rem) clamp(0.8rem, 2vw, 2rem);
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		display: flex;
		flex-direction: row;
		/* single row mobile */
		justify-content: space-between;
		align-items: center;
		flex-wrap: nowrap;
		z-index: 100;
		box-shadow: 0 4px 20px rgba(0,0,0,0.15);
	}

	.header-title {
		font-size: clamp(0.9rem, 2vw, 1.2rem);
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: clamp(6px, 1vw, 18px);
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		text-shadow: 0 2px 4px rgba(0,0,0,0.2);
	}

	.header-title i {
		font-size: clamp(1rem, 2.5vw, 1.5rem);
		color: #f9fafc;
	}

	/* USER ICON + DROPDOWN */
	.header-user {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		font-size: clamp(0.85rem, 1.8vw, 1.2rem);
		padding: clamp(5px, 1vw, 12px) clamp(10px, 2vw, 24px);
		background: rgba(2, 2, 41, 1);
		border-radius: clamp(6px, 1vw, 10px);
		flex-wrap: nowrap;
		box-shadow: inset 0 1px 3px rgba(255,255,255,0.1), 0 4px 8px rgba(0,0,0,0.2);
	}

	.header-user i {
		font-size: clamp(1rem, 2.5vw, 1.5rem);
		color: #f0eded;
		cursor: pointer;
	}

	.header-user span {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	/* Mobile: collapse username & logout */
	@media (max-width: 991px) {

		.header-user span,
		.header-user a {
			display: none;
		}
	}

	/* Extra small mobile: compact user */
	@media (max-width: 400px) {
		.header-user {
			font-size: 0.45rem;
			padding: 2px 6px;
			gap: 2px;
		}

		.header-user i {
			font-size: 1.2rem;
		}
	}

	/* MAIN CONTENT */
	.main-content {
		margin: 0 auto;
		padding-top: clamp(5rem, 10vw, 7rem);
		padding-left: clamp(0.8rem, 3vw, 2rem);
		padding-right: clamp(0.8rem, 3vw, 2rem);
		padding-bottom: 5rem; /* Added space for footer */
	}

	/* Card Container */
	.card-container {
		display: flex;
		flex-wrap: wrap;
		gap: 1rem;
		justify-content: center;
	}

	/* Individual Card */
	.card {
		flex: 1 1 calc(33.333% - 1rem);
		/* 3 per row desktop */
		max-width: 300px;
		min-width: 250px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		/* vertical center */
		align-items: center;
		/* horizontal center */
		border-radius: 15px;
		box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
		transition: transform 0.2s, box-shadow 0.2s;
		padding: 1.5rem;
	}

	/* Hover Effect */
	.card:hover {
		transform: translateY(-3px) scale(1.02);
		box-shadow: 0 12px 36px rgba(35, 57, 93, 0.18);
	}

	/* Card Icon */
	.card-icon {
		font-size: 2.5rem;
		color: #23395d;
		margin-bottom: 8px;
	}

	/* Card Title */
	.card-title {
		font-size: 1rem;
		font-weight: 700;
		color: #23395d;
		margin-bottom: 5px;
	}

	/* Card Value */
	.card p.display-6 {
		font-size: 2rem;
		font-weight: 600;
		margin: 0;
	}

	/* Responsive Adjustments */

	/* Tablets */
	@media (max-width: 991px) {
		.card {
			flex: 1 1 calc(50% - 1rem);
			/* 2 per row */
		}
	}

	/* Mobile */
	@media (max-width: 576px) {
		.card {
			flex: 1 1 100%;
			/* 1 per row */
		}
	}


	/* ==============================
   DESKTOP / LAPTOP FIXED LAYOUT
============================== */
	@media (min-width: 992px) {
		.header-bar {
			flex-direction: row;
			align-items: center;
			justify-content: space-between;
			padding: 0.5rem 2rem;
			left: 260px;
			right: 20px;
			top: 10px;
			border-radius: 10px;
			max-width: calc(100% - 280px);
		}

		.header-title {
			font-size: 1.2rem;
			gap: 18px;
		}

		.header-title i {
			font-size: 1.5rem;
		}

		.header-user {
			font-size: 0.5rem;
			padding: 5px 10px;
			width: auto;
			gap: 5px;
			flex-wrap: nowrap;
		}

		.main-content {
			margin-left: 240px;
			padding-top: 7rem;
			padding-left: 2rem;
			padding-right: 2rem;
			padding-bottom: 5rem;
		}

		.card-container .card {
			flex: 1 1 calc(25% - 1rem);
			max-width: 300px;
			min-width: 250px;
			border-radius: 22px;
		}

		.card-title {
			font-size: 1rem;
		}

		.card-icon {
			font-size: 3rem;
		}
	}


	/* ✅ FOOTER - Fixed look, but no overlap with sidebar */
	.footer {
		position: fixed;
		bottom: 0;
		left: 220px;
		/* same width as your sidebar */
		width: calc(100% - 220px);
		/* adjust width */
		background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
		box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.08);
		font-size: 0.9rem;
		z-index: 1000;
		padding: 0.75rem 1rem;
		text-align: center;
		border-top: 1px solid rgba(0,0,0,0.05);
	}

	/* ✅ Mobile View - sidebar collapses, so footer spans full width */
	@media (max-width: 768px) {
		.footer {
			left: 0;
			width: 100%;
			font-size: 0.8rem;
			padding: 0.75rem;
		}
		
		.main-content {
			padding-bottom: 4rem;
		}
	}

	/* Enhanced Table Container */
	.table-container {
		background: white;
		border-radius: 20px;
		padding: 1.5rem;
		box-shadow: 0 8px 30px rgba(0,0,0,0.12);
		margin-top: 1rem;
		width: 100%;
		overflow: hidden;
		border: 1px solid rgba(255,255,255,0.1);
		backdrop-filter: blur(10px);
	}

	.table-responsive-custom {
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
		margin: 0 -0.5rem;
		padding: 0 0.5rem;
		width: calc(100% + 1rem);
		border-radius: 12px;
	}

	/* Enhanced Table Styles */
	.table {
		border-collapse: separate;
		border-spacing: 0 4px;
		width: 100%;
	}

	.table thead th {
		background: linear-gradient(135deg, var(--arta-blue3) 0%, #1a2c35 100%);
		color: white;
		font-weight: 600;
		font-size: 0.85rem;
		padding: 0.9rem 0.5rem;
		white-space: nowrap;
		border-bottom: none;
		text-shadow: 0 1px 2px rgba(0,0,0,0.2);
		box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	}

	.table thead th:first-child {
		border-top-left-radius: 12px;
		border-bottom-left-radius: 12px;
	}

	.table thead th:last-child {
		border-top-right-radius: 12px;
		border-bottom-right-radius: 12px;
	}

	.table tbody td {
		padding: 0.8rem 0.5rem;
		font-size: 0.9rem;
		vertical-align: middle;
		background: white;
		box-shadow: 0 2px 8px rgba(0,0,0,0.02);
		border: none;
	}

	.table tbody tr {
		transition: all 0.2s ease;
		border-radius: 12px;
	}

	.table tbody tr:hover td {
		background-color: #f8faff;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(35, 57, 93, 0.08);
	}

	.table tbody tr:nth-child(even) td {
		background-color: #fafafa;
	}

	/* Enhanced Action buttons */
	.btn-action {
		padding: 0.35rem 0.6rem;
		font-size: 0.8rem;
		border-radius: 8px;
		margin: 0 2px;
		transition: all 0.2s ease;
		box-shadow: 0 2px 4px rgba(0,0,0,0.05);
	}

	.btn-outline-primary.btn-action {
		color: var(--arta-blue1);
		border-color: var(--arta-blue1);
		background: linear-gradient(to bottom, #fff, #f8f9fa);
	}

	.btn-outline-primary.btn-action:hover {
		background: linear-gradient(135deg, var(--arta-blue1) 0%, #2a4570 100%);
		border-color: var(--arta-blue1);
		transform: translateY(-1px);
		box-shadow: 0 4px 8px rgba(35, 57, 93, 0.3);
		color: white;
	}

	.btn-outline-warning.btn-action {
		color: #b85e00;
		border-color: #b85e00;
		background: linear-gradient(to bottom, #fff, #f8f9fa);
	}

	.btn-outline-warning.btn-action:hover {
		background: linear-gradient(135deg, #b85e00 0%, #8a4700 100%);
		border-color: #b85e00;
		transform: translateY(-1px);
		box-shadow: 0 4px 8px rgba(184, 94, 0, 0.3);
		color: white;
	}

	.btn-outline-danger.btn-action {
		color: #b02a37;
		border-color: #b02a37;
		background: linear-gradient(to bottom, #fff, #f8f9fa);
	}

	.btn-outline-danger.btn-action:hover {
		background: linear-gradient(135deg, #b02a37 0%, #8b1e28 100%);
		border-color: #b02a37;
		transform: translateY(-1px);
		box-shadow: 0 4px 8px rgba(176, 42, 55, 0.3);
		color: white;
	}

	.action-group {
		display: flex;
		gap: 4px;
		flex-wrap: wrap;
		justify-content: center;
	}

	/* Enhanced Badge styles */
	.role-badge {
		padding: 0.35rem 0.75rem;
		border-radius: 30px;
		font-size: 0.75rem;
		font-weight: 600;
		background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
		color: #1976d2;
		box-shadow: 0 2px 4px rgba(25, 118, 210, 0.2);
		display: inline-block;
	}

	/* Text truncation */
	.text-cell {
		max-width: 150px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	@media (max-width: 768px) {
		.text-cell {
			max-width: 100px;
		}
		
		.table thead th {
			font-size: 0.75rem;
			padding: 0.6rem 0.25rem;
		}
		
		.table tbody td {
			font-size: 0.75rem;
			padding: 0.6rem 0.25rem;
		}
		
		.btn-action {
			padding: 0.25rem 0.4rem;
			font-size: 0.7rem;
		}
	}

	/* Enhanced Search box */
	.search-wrapper {
		position: relative;
		max-width: 300px;
		width: 100%;
	}

	.search-wrapper input {
		padding-right: 40px;
		border-radius: 30px;
		border: 1px solid rgba(0,0,0,0.1);
		box-shadow: 0 2px 8px rgba(0,0,0,0.04);
		transition: all 0.2s ease;
	}

	.search-wrapper input:focus {
		border-color: var(--arta-blue1);
		box-shadow: 0 0 0 3px rgba(35, 57, 93, 0.15);
	}

	.search-wrapper i {
		position: absolute;
		right: 15px;
		top: 50%;
		transform: translateY(-50%);
		color: #6c757d;
	}

	/* Enhanced Pagination */
	.pagination-custom {
		display: flex;
		flex-wrap: wrap;
		gap: 5px;
		justify-content: center;
		margin-top: 1rem;
	}

	.pagination-custom .btn {
		min-width: 35px;
		padding: 0.35rem 0.6rem;
		font-size: 0.85rem;
		border-radius: 8px;
		transition: all 0.2s ease;
		box-shadow: 0 2px 4px rgba(0,0,0,0.05);
	}

	.pagination-custom .btn-primary {
		background: linear-gradient(135deg, var(--arta-blue3) 0%, #1a2c35 100%);
		border: none;
		box-shadow: 0 4px 8px rgba(0,0,0,0.15);
	}

	.pagination-custom .btn-outline-primary {
		color: var(--arta-blue1);
		border-color: var(--arta-blue1);
		background: white;
	}

	.pagination-custom .btn-outline-primary:hover {
		background: linear-gradient(135deg, var(--arta-blue1) 0%, #2a4570 100%);
		color: white;
		transform: translateY(-1px);
		box-shadow: 0 4px 8px rgba(35, 57, 93, 0.3);
	}

	/* Table info */
	.table-info {
		font-size: 0.85rem;
		color: #6c757d;
		padding: 0.35rem 0;
		text-shadow: 0 1px 1px rgba(255,255,255,0.5);
	}

	/* Enhanced Modal Styles */
	.modal-content {
		border: none;
		border-radius: 24px;
		box-shadow: 0 20px 40px rgba(0,0,0,0.3);
		overflow: hidden;
	}

	.modal-header {
		padding: 1rem 1.5rem;
		background: linear-gradient(135deg, var(--arta-blue3) 0%, #1a2c35 100%) !important;
		border-bottom: 1px solid rgba(255,255,255,0.1);
	}

	.modal-header.bg-warning {
		background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
	}

	.modal-title {
		font-size: 1.1rem;
		font-weight: 600;
		text-shadow: 0 1px 2px rgba(0,0,0,0.2);
	}

	.modal-body {
		padding: 1.5rem;
		background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
	}

	.modal-footer {
		padding: 1rem 1.5rem;
		background: #f8f9fa;
		border-top: 1px solid rgba(0,0,0,0.05);
	}

	/* View Modal Details */
	.modal-body .row {
		background: white;
		padding: 1rem;
		border-radius: 16px;
		box-shadow: inset 0 1px 3px rgba(0,0,0,0.02), 0 4px 12px rgba(0,0,0,0.03);
	}

	.modal-body .col-6 {
		padding: 0.5rem;
		font-size: 0.95rem;
	}

	.modal-body .col-6:first-child {
		font-weight: 600;
		color: var(--arta-blue1);
		border-right: 1px solid rgba(0,0,0,0.05);
	}

	/* Form Controls */
	.form-control, .form-select {
		border-radius: 12px;
		border: 1px solid rgba(0,0,0,0.1);
		box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
		padding: 0.6rem 1rem;
		font-size: 0.95rem;
	}

	.form-control:focus, .form-select:focus {
		border-color: var(--arta-blue1);
		box-shadow: 0 0 0 3px rgba(35, 57, 93, 0.15);
	}

	.form-control[readonly] {
		background-color: #f8f9fa;
		border: 1px solid rgba(0,0,0,0.05);
	}

	.form-label.small {
		font-size: 0.8rem;
		margin-bottom: 0.25rem;
		color: #495057;
		font-weight: 600;
	}

	/* Custom Scrollbar */
	.table-responsive-custom::-webkit-scrollbar {
		height: 6px;
		width: 6px;
	}

	.table-responsive-custom::-webkit-scrollbar-track {
		background: #f0f0f0;
		border-radius: 10px;
	}

	.table-responsive-custom::-webkit-scrollbar-thumb {
		background: linear-gradient(135deg, var(--arta-blue3) 0%, #1a2c35 100%);
		border-radius: 10px;
	}

	.table-responsive-custom::-webkit-scrollbar-thumb:hover {
		background: linear-gradient(135deg, #1a2c35 0%, #0f2027 100%);
	}
	</style>
</head>
<body>
	<?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

	<!-- ✅ Header Bar -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-key"></i>
			<span>System Assignment</span>
		</div>
		<div class="header-user">
			<i class="fa-regular fa-user"></i>
			<span>Admin User</span>
			<a href="logout.php" class="text-white text-decoration-none"><i class="fa-solid fa-sign-out-alt"></i></a>
		</div>
	</div>

	<!-- ✅ Main Content -->
	<div class="main-content">
		<div class="table-container">
			<!-- 🔍 Search -->
			<div class="d-flex justify-content-end mb-3">
				<div class="search-wrapper">
					<input type="text" id="searchBox" class="form-control" placeholder="Search...">
					<i class="fa-solid fa-search"></i>
				</div>
			</div>

			<!-- 📋 Table -->
			<div class="table-responsive-custom">
				<table class="table align-middle mb-0 w-100">
					<thead>
						<tr>
							<th style="width: 5%;">#</th>
							<th style="width: 15%;">Full Name</th>
							<th style="width: 15%;">Assignment</th>
							<th style="width: 10%;">Role</th>
							<th style="width: 12%;">Office</th>
							<th style="width: 12%;">Division</th>
							<th style="width: 10%;">Unit</th>
							<th style="width: 10%;">Date</th>
							<th style="width: 11%;">Action</th>
						</tr>
					</thead>
					<tbody id="tableBody">
						<tr>
							<td colspan="9" class="text-center py-4">Loading...</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- 📄 Pagination & Info -->
			<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
				<div class="table-info" id="tableInfo"></div>
				<div class="pagination-custom" id="pagination"></div>
			</div>
		</div>
	</div>

	<!-- 👤 View User Modal - Dark Header -->
	<div class="modal fade" id="viewUserModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content rounded-4 border-0 shadow">
				<div class="modal-header bg-dark text-white">
					<h5 class="modal-title"><i class="fa-solid fa-user-circle me-2"></i>User Details</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="row g-3">
						<div class="col-6"><strong>Full Name:</strong></div><div class="col-6" id="modalFullName"></div>
						<div class="col-6"><strong>Office:</strong></div><div class="col-6" id="modalOffice"></div>
						<div class="col-6"><strong>Division:</strong></div><div class="col-6" id="modalDivision"></div>
						<div class="col-6"><strong>Unit:</strong></div><div class="col-6" id="modalUnit"></div>
						<div class="col-6"><strong>Designation:</strong></div><div class="col-6" id="modalDesignation"></div>
						<div class="col-6"><strong>System:</strong></div><div class="col-6" id="modalSystem"></div>
						<div class="col-6"><strong>Role:</strong></div><div class="col-6" id="modalRole"></div>
						<div class="col-6"><strong>Date Assigned:</strong></div><div class="col-6" id="modalDateAssigned"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- 👤 Edit User Modal - Dark Header -->
	<div class="modal fade" id="editUserModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content rounded-4 border-0 shadow">
				<div class="modal-header bg-dark text-white">
					<h5 class="modal-title"><i class="fa-solid fa-pen me-2"></i>Edit Assignment</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<form id="editForm">
						<input type="hidden" id="editPortalId">
						<input type="hidden" id="editSystemId">
						
						<div class="mb-3">
							<label class="form-label small fw-bold">Full Name</label>
							<input type="text" class="form-control" id="editFullName" readonly>
						</div>
						
						<div class="mb-3">
							<label class="form-label small fw-bold">System</label>
							<select class="form-select" id="editSystem">
								<option value="">Select System</option>
							</select>
						</div>
						
						<div class="mb-3">
							<label class="form-label small fw-bold">Role</label>
							<select class="form-select" id="editRole">
								<option value="user">User</option>
								<option value="admin">Admin</option>
								<option value="supervisor">Supervisor</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-dark" id="saveEditBtn">Save Changes</button>
				</div>
			</div>
		</div>
	</div>

	<!-- ✅ Footer -->
	<?php include_once __DIR__ . '/../includes/footer.php'; ?>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
	<script>
	let assignedUsersData = [];
	let currentPage = 1;
	const rowsPerPage = 10;

	// Sample data for testing - remove this when using actual API
	const sampleData = [
		{ portal_id: 1, system_id: 1, full_name: 'Rex Tomas', system_name: 'ARTA-VRS', role: 'user', office_name: 'OODDGAF', division_name: 'N/A', unit_name: 'Dev', date_assigned: '2024-01-15' },
		{ portal_id: 2, system_id: 1, full_name: 'Mark Ferdinand Abay', system_name: 'ARTA-VRS', role: 'user', office_name: 'OODDGAF', division_name: 'ICT', unit_name: 'Dev', date_assigned: '2024-01-16' },
		{ portal_id: 1, system_id: 2, full_name: 'Rex Tomas', system_name: 'NRRT', role: 'user', office_name: 'OODDGAF', division_name: 'N/A', unit_name: 'Dev', date_assigned: '2024-01-17' },
		{ portal_id: 3, system_id: 2, full_name: 'Dexter Santiago', system_name: 'NRRT', role: 'user', office_name: 'OODDGAF', division_name: 'GSD', unit_name: 'PLU', date_assigned: '2024-01-18' },
		{ portal_id: 4, system_id: 2, full_name: 'Guevara Ronnel', system_name: 'NRRT', role: 'user', office_name: 'OODDGAF', division_name: 'ICT', unit_name: 'Network', date_assigned: '2024-01-19' },
		{ portal_id: 5, system_id: 3, full_name: 'John Doe', system_name: 'ARTA Government System v2.1', role: 'admin', office_name: 'OODDGAF', division_name: 'GSD', unit_name: 'PLU', date_assigned: '2024-01-20' },
	];

	document.addEventListener('DOMContentLoaded', function() {
		// Use sample data for now - replace with actual API call
		assignedUsersData = sampleData;
		displayPage(1);
		loadSystems();
	});

	async function loadSystems() {
		try {
			// Sample systems - replace with actual API
			const systems = [
				{ system_id: 1, system_name: 'ARTA-VRS' },
				{ system_id: 2, system_name: 'NRRT' },
				{ system_id: 3, system_name: 'ARTA Government System v2.1' }
			];
			
			const select = document.getElementById('editSystem');
			select.innerHTML = '<option value="">Select System</option>';
			systems.forEach(system => {
				select.innerHTML += `<option value="${system.system_id}">${system.system_name}</option>`;
			});
		} catch (err) {
			console.error('Error loading systems:', err);
		}
	}

	function displayPage(page) {
		const tbody = document.getElementById('tableBody');
		tbody.innerHTML = '';

		const start = (page - 1) * rowsPerPage;
		const end = Math.min(start + rowsPerPage, assignedUsersData.length);
		const pageData = assignedUsersData.slice(start, end);

		if (pageData.length === 0) {
			tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4">No data available</td></tr>`;
			document.getElementById('tableInfo').innerHTML = '';
			document.getElementById('pagination').innerHTML = '';
			return;
		}

		pageData.forEach((row, index) => {
			tbody.innerHTML += `
				<tr>
					<td>${start + index + 1}</td>
					<td><span class="text-cell" title="${row.full_name || ''}">${row.full_name || '-'}</span></td>
					<td><span class="text-cell" title="${row.system_name || ''}">${row.system_name || '-'}</span></td>
					<td><span class="role-badge">${row.role || 'user'}</span></td>
					<td><span class="text-cell" title="${row.office_name || ''}">${row.office_name || '-'}</span></td>
					<td><span class="text-cell" title="${row.division_name || ''}">${row.division_name || '-'}</span></td>
					<td><span class="text-cell" title="${row.unit_name || ''}">${row.unit_name || '-'}</span></td>
					<td>${row.date_assigned || '-'}</td>
					<td>
						<div class="action-group">
							<button class="btn btn-outline-primary btn-action viewBtn" 
								data-portal-id="${row.portal_id}" data-system-id="${row.system_id}" title="View">
								<i class="fa-solid fa-eye"></i>
							</button>
							<button class="btn btn-outline-warning btn-action editBtn" 
								data-portal-id="${row.portal_id}" data-system-id="${row.system_id}" title="Edit">
								<i class="fa-solid fa-pen"></i>
							</button>
							<button class="btn btn-outline-danger btn-action removeBtn" 
								data-portal-id="${row.portal_id}" data-system-id="${row.system_id}" title="Remove">
								<i class="fa-solid fa-trash"></i>
							</button>
						</div>
					</td>
				</tr>`;
		});

		document.getElementById('tableInfo').innerHTML = 
			`Showing ${start + 1} to ${end} of ${assignedUsersData.length} entries`;

		renderPagination();
	}

	function renderPagination() {
		const totalPages = Math.ceil(assignedUsersData.length / rowsPerPage);
		const paginationDiv = document.getElementById('pagination');
		paginationDiv.innerHTML = '';

		if (totalPages <= 1) return;

		// Previous button
		if (currentPage > 1) {
			const prevBtn = document.createElement('button');
			prevBtn.className = 'btn btn-outline-primary btn-sm';
			prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
			prevBtn.onclick = () => {
				currentPage--;
				displayPage(currentPage);
			};
			paginationDiv.appendChild(prevBtn);
		}

		// Page numbers
		for (let i = 1; i <= totalPages; i++) {
			if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
				const btn = document.createElement('button');
				btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
				btn.innerText = i;
				btn.onclick = () => {
					currentPage = i;
					displayPage(currentPage);
				};
				paginationDiv.appendChild(btn);
			} else if (i === currentPage - 3 || i === currentPage + 3) {
				const span = document.createElement('span');
				span.className = 'btn btn-sm btn-outline-secondary disabled';
				span.innerText = '...';
				paginationDiv.appendChild(span);
			}
		}

		// Next button
		if (currentPage < totalPages) {
			const nextBtn = document.createElement('button');
			nextBtn.className = 'btn btn-outline-primary btn-sm';
			nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
			nextBtn.onclick = () => {
				currentPage++;
				displayPage(currentPage);
			};
			paginationDiv.appendChild(nextBtn);
		}
	}

	// Search functionality
	let searchTimeout;
	document.getElementById('searchBox').addEventListener('input', function(e) {
		clearTimeout(searchTimeout);
		searchTimeout = setTimeout(() => {
			const term = e.target.value.toLowerCase();
			if (!term) {
				assignedUsersData = [...sampleData];
				currentPage = 1;
				displayPage(1);
				return;
			}

			const filtered = sampleData.filter(user => 
				(user.full_name && user.full_name.toLowerCase().includes(term)) ||
				(user.system_name && user.system_name.toLowerCase().includes(term)) ||
				(user.office_name && user.office_name.toLowerCase().includes(term)) ||
				(user.division_name && user.division_name.toLowerCase().includes(term)) ||
				(user.unit_name && user.unit_name.toLowerCase().includes(term)) ||
				(user.role && user.role.toLowerCase().includes(term))
			);

			if (filtered.length > 0) {
				assignedUsersData = filtered;
				currentPage = 1;
				displayPage(1);
			} else {
				document.getElementById('tableBody').innerHTML = 
					`<tr><td colspan="9" class="text-center py-4">No results found for "${term}"</td></tr>`;
				document.getElementById('tableInfo').innerHTML = '';
				document.getElementById('pagination').innerHTML = '';
			}
		}, 300);
	});

	// Event delegation for buttons
	document.addEventListener('click', function(e) {
		const btn = e.target.closest('button');
		if (!btn) return;

		const portalId = btn.dataset.portalId;
		const systemId = btn.dataset.systemId;

		if (btn.classList.contains('viewBtn')) {
			viewUser(portalId, systemId);
		}
		else if (btn.classList.contains('editBtn')) {
			editUser(portalId, systemId);
		}
		else if (btn.classList.contains('removeBtn')) {
			removeAccess(portalId, systemId);
		}
	});

	function viewUser(portalId, systemId) {
		const user = assignedUsersData.find(u => u.portal_id == portalId && u.system_id == systemId);
		if (user) {
			document.getElementById('modalFullName').innerText = user.full_name || '-';
			document.getElementById('modalOffice').innerText = user.office_name || '-';
			document.getElementById('modalDivision').innerText = user.division_name || '-';
			document.getElementById('modalUnit').innerText = user.unit_name || '-';
			document.getElementById('modalDesignation').innerText = user.designation_name || '-';
			document.getElementById('modalSystem').innerText = user.system_name || '-';
			document.getElementById('modalRole').innerText = user.role || '-';
			document.getElementById('modalDateAssigned').innerText = user.date_assigned || '-';
			
			new bootstrap.Modal(document.getElementById('viewUserModal')).show();
		}
	}

	function editUser(portalId, systemId) {
		const user = assignedUsersData.find(u => u.portal_id == portalId && u.system_id == systemId);
		if (user) {
			document.getElementById('editPortalId').value = portalId;
			document.getElementById('editSystemId').value = systemId;
			document.getElementById('editFullName').value = user.full_name || '';
			document.getElementById('editSystem').value = systemId;
			document.getElementById('editRole').value = user.role || 'user';
			
			new bootstrap.Modal(document.getElementById('editUserModal')).show();
		}
	}

	document.getElementById('saveEditBtn')?.addEventListener('click', function() {
		const systemId = document.getElementById('editSystem').value;
		const role = document.getElementById('editRole').value;

		if (!systemId) {
			Swal.fire('Warning', 'Please select a system', 'warning');
			return;
		}

		Swal.fire({
			title: 'Success!',
			text: 'Assignment updated successfully',
			icon: 'success',
			timer: 1500,
			showConfirmButton: false
		});
		
		bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
	});

	function removeAccess(portalId, systemId) {
		Swal.fire({
			title: 'Remove Access?',
			text: "This user will lose access to this system",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Yes, remove'
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'Removed!',
					text: 'Access has been removed.',
					icon: 'success',
					timer: 1500,
					showConfirmButton: false
				});
				
				// Remove from local array
				assignedUsersData = assignedUsersData.filter(u => !(u.portal_id == portalId && u.system_id == systemId));
				if (assignedUsersData.length === 0) {
					displayPage(1);
				} else {
					if (currentPage > Math.ceil(assignedUsersData.length / rowsPerPage)) {
						currentPage = Math.ceil(assignedUsersData.length / rowsPerPage);
					}
					displayPage(currentPage);
				}
			}
		});
	}
	</script>
</body>
</html>