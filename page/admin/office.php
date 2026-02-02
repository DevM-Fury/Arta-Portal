<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
	<!-- Sidebar -->
	<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

	<!-- HEADER -->
	<div class="header-bar">
		<div class="header-title">
			<i class="fa-solid fa-building-columns me-2"></i> User Dashboard
		</div>
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

	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">

			<!-- CARD -->
			<div class="card shadow-lg border-0 rounded-3 mx-auto"
				style="width: 100%; max-width: 1800px; min-height: 85vh;">
				<div class="card-body p-4">

					<!-- Header Section -->
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
						<div class="d-flex gap-2 w-100 w-md-auto">
							<input type="text" id="searchInput" class="form-control shadow-sm border-primary"
								placeholder="🔍 Search system...">
							<button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal"
								data-bs-target="#addSystemModal">
								<i class="fa-solid fa-plus me-2"></i>Add New System
							</button>
						</div>
					</div>

					<!-- Rows per page and pagination -->
					<div class="d-flex justify-content-between align-items-center mb-3">
						<div>
							<label>Show
								<select id="rowsPerPage" class="form-select d-inline-block w-auto">
									<option value="5">5</option>
									<option value="10" selected>10</option>
									<option value="25">25</option>
									<option value="50">50</option>
								</select>
								entries
							</label>
						</div>
						<nav>
							<ul class="pagination mb-0" id="pagination"></ul>
						</nav>
					</div>

					<!-- TABLE -->
					<div class="table-responsive">
						<table class="table custom-table align-middle text-center mb-0">
							<thead>
								<tr>
									<th>System ID</th>
									<th>System Name</th>
									<th>Logo</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tableBody">
								<!-- Data fetched dynamically -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- ADD SYSTEM MODAL -->
	<div class="modal fade" id="addSystemModal" tabindex="-1" aria-labelledby="addSystemModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="addSystemForm" enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addSystemModalLabel">Add New System</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label for="system_name" class="form-label">System Name</label>
							<input type="text" class="form-control" id="system_name" name="system_name" required>
						</div>
						<div class="mb-3">
							<label for="system_logo" class="form-label">Logo</label>
							<input type="file" class="form-control" id="system_logo" name="system_logo"
								accept="image/*">
						</div>
						<div class="mb-3">
							<label for="status" class="form-label">Status</label>
							<select id="status" name="status" class="form-select">
								<option value="Active">Active</option>
								<option value="Pending">Pending</option>
							</select>
						</div>
						<div class="mb-3">
							<label for="description" class="form-label">Description</label>
							<textarea class="form-control" id="description" name="description"></textarea>
						</div>
						<div class="mb-3">
							<label for="owner" class="form-label">Owner</label>
							<input type="text" class="form-control" id="owner" name="owner" value="Admin">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Add System</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- JS -->
	<script>
	let currentPage = 1;
	let rowsPerPage = parseInt(document.getElementById('rowsPerPage').value);
	let systemsData = [];

	async function fetchSystems() {
		const res = await fetch(`../../api/fetch_systems.php`);
		systemsData = await res.json();
		renderTable();
		renderPagination();
	}

	function renderTable() {
		const tbody = document.getElementById('tableBody');
		const search = document.getElementById('searchInput').value.toLowerCase();

		let filtered = systemsData.filter(sys =>
			sys.system_id.toLowerCase().includes(search) ||
			sys.system_name.toLowerCase().includes(search) ||
			sys.status.toLowerCase().includes(search)
		);

		const totalPages = Math.ceil(filtered.length / rowsPerPage);
		if (currentPage > totalPages) currentPage = 1;

		const start = (currentPage - 1) * rowsPerPage;
		const paginatedData = filtered.slice(start, start + rowsPerPage);

		tbody.innerHTML = '';
		paginatedData.forEach(system => {
			tbody.innerHTML += `
                <tr class="expandable-row">
                    <td>${system.system_id}</td>
                    <td>${system.system_name}</td>
                    <td><img src="../../uploads/${system.logo}" width="50" class="rounded"></td>
                    <td><span class="badge ${system.status==='Active'?'bg-success':'bg-warning text-dark'}">${system.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary editBtn" data-id="${system.system_id}">Edit</button>
                        <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${system.system_id}">Delete</button>
                    </td>
                </tr>
                <tr class="expandable-content">
                    <td colspan="5">
                        <div class="expand-body">
                            <p><strong>Description:</strong> ${system.description}</p>
                            <p><strong>Created:</strong> ${system.created_at}</p>
                            <p><strong>Owner:</strong> ${system.owner}</p>
                        </div>
                    </td>
                </tr>
            `;
		});

		addRowExpandListeners();
		addEditDeleteListeners();
	}

	function renderPagination() {
		const pagination = document.getElementById('pagination');
		const search = document.getElementById('searchInput').value.toLowerCase();
		const filtered = systemsData.filter(sys =>
			sys.system_id.toLowerCase().includes(search) ||
			sys.system_name.toLowerCase().includes(search) ||
			sys.status.toLowerCase().includes(search)
		);
		const totalPages = Math.ceil(filtered.length / rowsPerPage);

		pagination.innerHTML = '';
		for (let i = 1; i <= totalPages; i++) {
			pagination.innerHTML += `<li class="page-item ${i===currentPage?'active':''}">
                <a class="page-link" href="#">${i}</a>
            </li>`;
		}

		document.querySelectorAll('.page-link').forEach(link => {
			link.addEventListener('click', e => {
				e.preventDefault();
				currentPage = parseInt(e.target.textContent);
				renderTable();
			});
		});
	}

	document.getElementById('rowsPerPage').addEventListener('change', function() {
		rowsPerPage = parseInt(this.value);
		currentPage = 1;
		renderTable();
		renderPagination();
	});

	document.getElementById("searchInput").addEventListener("keyup", function() {
		currentPage = 1;
		renderTable();
		renderPagination();
	});

	function addRowExpandListeners() {
		document.querySelectorAll('.expandable-row').forEach(row => {
			row.addEventListener('click', () => {
				row.classList.toggle('active');
				const nextRow = row.nextElementSibling;
				if (nextRow && nextRow.classList.contains('expandable-content')) {
					if (nextRow.style.display === 'table-row') {
						nextRow.querySelector('.expand-body').style.maxHeight = '0px';
						setTimeout(() => nextRow.style.display = 'none', 300);
					} else {
						nextRow.style.display = 'table-row';
						setTimeout(() => nextRow.querySelector('.expand-body').style.maxHeight = '300px',
							10);
					}
				}
			});
		});
	}

	function addEditDeleteListeners() {
		// DELETE
		document.querySelectorAll('.deleteBtn').forEach(btn => {
			btn.addEventListener('click', async e => {
				e.stopPropagation();
				if (confirm('Are you sure you want to delete this system?')) {
					const id = btn.getAttribute('data-id');
					const formData = new FormData();
					formData.append('system_id', id);
					await fetch('../../api/delete_system.php', {
						method: 'POST',
						body: formData
					});
					fetchSystems();
				}
			});
		});

		// EDIT - simple alert for now, can be replaced with modal prefill
		document.querySelectorAll('.editBtn').forEach(btn => {
			btn.addEventListener('click', e => {
				e.stopPropagation();
				const id = btn.getAttribute('data-id');
				alert('Implement Edit modal for system ID: ' + id);
			});
		});
	}

	// ADD SYSTEM FORM SUBMIT
	document.getElementById('addSystemForm').addEventListener('submit', async e => {
		e.preventDefault();
		const formData = new FormData(e.target);
		await fetch('../../api/create_system.php', {
			method: 'POST',
			body: formData
		});
		e.target.reset();
		const modalEl = document.getElementById('addSystemModal');
		bootstrap.Modal.getInstance(modalEl).hide();
		fetchSystems();
	});

	// INITIALIZE
	fetchSystems();
	</script>

	<!-- STYLE -->
	<style>
	:root {
		--arta-red: #8B0000;
		--arta-light: #fff8e1;
		--arta-gray: #f7f7f7;
		--arta-blue1: #23395d;
		--arta-blue2: #1e2a3a;
		--arta-blue3: #0f2027;
	}

	body {
		background-color: var(--arta-gray);
		font-family: 'Poppins', sans-serif;
		color: #333;
	}

	.card {
		background: #fff;
		border-radius: 16px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
		overflow: hidden;
	}

	.table {
		width: 100%;
		min-width: 100%;
		table-layout: fixed;
		border-collapse: collapse;
		background-color: #fff;
		border-radius: 8px;
		overflow: hidden;
	}

	.table thead {
		background: var(--arta-blue3);
		color: #fff;
		text-transform: uppercase;
	}

	.table thead th {
		padding: 14px;
		border: none !important;
		font-weight: 600;
		font-size: 14px;
		letter-spacing: 0.5px;
	}

	.table tbody td {
		padding: 12px;
		font-size: 14px;
		vertical-align: middle;
	}

	.table tbody tr:nth-child(even) {
		background-color: var(--arta-light);
	}

	.table tbody tr:hover {
		background-color: rgba(15, 32, 39, 0.08);
		transition: 0.2s;
	}

	.expandable-row {
		cursor: pointer;
	}

	.expandable-row.active {
		background-color: rgba(15, 32, 39, 0.1);
	}

	.expandable-content {
		display: none;
		background-color: #f9f9f9;
	}

	.expand-body {
		text-align: left;
		padding: 18px 24px;
		background: #fff;
		border-left: 4px solid var(--arta-blue1);
		border-radius: 8px;
		box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.05);
		font-size: 14px;
		line-height: 1.6;
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.3s ease-in-out;
	}

	#searchInput {
		border-radius: 6px;
		padding: 6px 10px;
		font-size: 14px;
		width: 250px;
		transition: all 0.3s ease;
	}

	@media(max-width:768px) {
		#searchInput {
			width: 100%;
			padding: 8px 12px;
		}
	}

	#searchInput:focus {
		border-color: var(--arta-blue1);
		box-shadow: 0 0 0 0.2rem rgba(35, 57, 93, 0.25);
	}

	.btn-primary {
		background-color: var(--arta-blue1);
		border: none;
	}

	.btn-primary:hover {
		background-color: var(--arta-blue3);
	}

	.badge.bg-success {
		background-color: #28a745 !important;
	}

	.badge.bg-warning {
		background-color: #ffc107 !important;
		color: #000 !important;
	}
	</style>

</body>

</html>