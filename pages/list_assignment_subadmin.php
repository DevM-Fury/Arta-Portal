<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

	<!-- ✅ Header Bar -->
	<div
		class="header-bar bg-gradient-primary text-white shadow-sm py-3 px-4 d-flex align-items-center justify-content-between">
		<h4 class="mb-0 fw-semibold"><i class="fa-solid fa-key me-2"></i> System Assignment</h4>
	</div>

	<!-- ✅ Main Content -->
	<div class="main-content py-6">
		<!-- Increased from py-4 to py-5 -->
		<div class="container-fluid mt-4">
			<!-- Added mt-4 for more space below header -->
			<div class="card shadow-lg border-0 mx-auto rounded-4" style="width:100%; max-width:1800px;">
				<div class="card-body p-4">

					<!-- 🔍 Search -->
					<div class="d-flex justify-content-end mb-4">
						<div class="input-group shadow-sm" style="max-width: 350px;">
							<span class="input-group-text bg-primary text-white">
								<i class="fa-solid fa-search"></i>
							</span>
							<input type="text" id="searchBox" class="form-control border-0"
								placeholder="Search name or system...">
						</div>
					</div>

					<!-- 📋 Table -->
					<div class="table-responsive rounded-4 shadow-sm">
						<table id="assignedUsersTable" class="table table-hover align-middle text-center mb-0">
							<thead class="bg-dark text-white">
								<tr>
									<th>#</th>
									<th>Full Name</th>
									<th>Office</th>
									<th>Division</th>
									<th>Unit</th>
									<th>Designation</th>
									<th>Assignment</th>
									<th>Role</th>
									<th>Date Assigned</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<!-- 📄 Pagination -->
					<div class="pagination-container mt-4 d-flex justify-content-center"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- 👤 User Modal -->
	<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content rounded-4 border-0 shadow">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title"><i class="fa-solid fa-user-circle me-2"></i>User Details</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<table class="table table-borderless align-middle">
						<tbody>
							<tr>
								<th>Full Name:</th>
								<td id="modalFullName"></td>
							</tr>
							<tr>
								<th>Office:</th>
								<td id="modalOffice"></td>
							</tr>
							<tr>
								<th>Division:</th>
								<td id="modalDivision"></td>
							</tr>
							<tr>
								<th>Unit:</th>
								<td id="modalUnit"></td>
							</tr>
							<tr>
								<th>Designation:</th>
								<td id="modalDesignation"></td>
							</tr>
							<tr>
								<th>System:</th>
								<td id="modalSystem"></td>
							</tr>
							<tr>
								<th>Role:</th>
								<td id="modalRole"></td>
							</tr>
							<tr>
								<th>Date Assigned:</th>
								<td id="modalDateAssigned"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- ✅ Styles -->
	<style>
	body {
		background: #f5f7fa;
		font-family: 'Poppins', sans-serif;
	}

	.bg-gradient-primary {
		background: linear-gradient(135deg, #003366, #007bff);
	}

	.table thead {
		font-weight: 600;
		letter-spacing: 0.3px;
	}

	.table-hover tbody tr:hover {
		background-color: #eaf4ff !important;
	}

	.btn {
		border-radius: 8px;
	}

	.btn-outline-primary:hover {
		background-color: #007bff;
		color: #fff;
	}

	th {
		width: 200px;
	}
	</style>

	<!-- ✅ Script -->
	<script>
	let assignedUsersData = [];
	let currentPage = 1;
	const rowsPerPage = 5;

	document.addEventListener('DOMContentLoaded', fetchAssignedUsers);

	async function fetchAssignedUsers() {
		const tableBody = document.querySelector('#assignedUsersTable tbody');
		tableBody.innerHTML = `<tr><td colspan="10">⏳ Loading...</td></tr>`;

		try {
			const res = await fetch('../api/list/fetch_assigned_users.php');
			const text = await res.text();
			const data = JSON.parse(text);

			if (data.status === 'error') {
				tableBody.innerHTML = `<tr><td colspan="10" class="text-danger">${data.message}</td></tr>`;
				return;
			}

			if (!data.data.length) {
				tableBody.innerHTML = `<tr><td colspan="10" class="text-muted">No assigned users found.</td></tr>`;
				return;
			}

			assignedUsersData = data.data;
			displayPage(1);

		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Failed to fetch assigned users', 'error');
		}
	}

	function displayPage(page) {
		const tableBody = document.querySelector('#assignedUsersTable tbody');
		tableBody.innerHTML = '';

		const start = (page - 1) * rowsPerPage;
		const pageData = assignedUsersData.slice(start, start + rowsPerPage);

		pageData.forEach((row, index) => {
			tableBody.innerHTML += `
				<tr>
					<td>${start + index + 1}</td>
					<td>${row.full_name || '-'}</td>
					<td>${row.office_name || '-'}</td>
					<td>${row.division_name || '-'}</td>
					<td>${row.unit_name || '-'}</td>
					<td>${row.designation_name || '-'}</td>
					<td>${row.system_name || '-'}</td>
					<td>${row.role || '-'}</td>
					<td>${row.date_assigned || '-'}</td>
					<td>
						<button class="btn btn-sm btn-outline-primary me-1 viewBtn" data-portal-id="${row.portal_id}" data-system-id="${row.system_id}">
							<i class="fa-solid fa-eye"></i>
						</button>
						<button class="btn btn-sm btn-outline-warning me-1 editBtn" data-portal-id="${row.portal_id}" data-system-id="${row.system_id}">
							<i class="fa-solid fa-pen"></i>
						</button>
						<button class="btn btn-sm btn-outline-danger removeBtn" data-portal-id="${row.portal_id}" data-system-id="${row.system_id}">
							<i class="fa-solid fa-trash"></i>
						</button>
					</td>
				</tr>`;
		});

		renderPagination();
	}

	function renderPagination() {
		const totalPages = Math.ceil(assignedUsersData.length / rowsPerPage);
		const paginationDiv = document.querySelector('.pagination-container');
		paginationDiv.innerHTML = '';

		for (let i = 1; i <= totalPages; i++) {
			const btn = document.createElement('button');
			btn.classList.add('btn', 'btn-sm', i === currentPage ? 'btn-primary' : 'btn-outline-primary', 'me-1');
			btn.innerText = i;
			btn.addEventListener('click', () => {
				currentPage = i;
				displayPage(currentPage);
			});
			paginationDiv.appendChild(btn);
		}
	}

	document.addEventListener('click', function(e) {
		const btn = e.target.closest('button');
		if (!btn) return;
		const portalId = btn.dataset.portalId;
		const systemId = btn.dataset.systemId;

		if (btn.classList.contains('viewBtn')) viewUser(portalId, systemId);
		if (btn.classList.contains('editBtn')) editUser(portalId, systemId);
		if (btn.classList.contains('removeBtn')) removeAccess(portalId, systemId);
	});
	</script>

	<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>