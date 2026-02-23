<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
    <!-- Sidebar -->
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- HEADER -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-users me-2"></i> Users Management
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
        <div class="content-container">

            <!-- SEARCH SECTION -->
            <div class="search-section">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search users...">
                    <span class="search-count" id="recordCount">0</span>
                </div>
                <div class="d-flex gap-2">
                    <select id="rowsPerPage" class="form-select" style="width: auto; min-width: 70px;">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fa-solid fa-plus me-2"></i>Add
                    </button>
                </div>
            </div>

            <!-- DESKTOP TABLE VIEW -->
            <div class="table-view">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-white bg-dark">ID</th>
                                <th class="text-white bg-dark">NATIONAL ID</th>
                                <th class="text-white bg-dark">FIRSTNAME</th>
                                <th class="text-white bg-dark">LASTNAME</th>
                                <th class="text-white bg-dark">CONTACT</th>
                                <th class="text-white bg-dark">EMAIL</th>
                                <th class="text-white bg-dark">ROLE</th>
                                <th class="text-white bg-dark">CREATED</th>
                                <th class="text-white bg-dark">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- MOBILE CARD VIEW -->
            <div class="mobile-view" id="mobileView"></div>

            <!-- FOOTER -->
            <div class="table-footer">
                <div class="records-info" id="recordsInfo">Showing 0 of 0 records</div>
                <div id="pagination" class="pagination-wrapper"></div>
                <?php include_once __DIR__ . '/../includes/footer.php'; ?>
            </div>

        </div>
    </div>

    <!-- ADD USER MODAL -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addUserForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">National ID</label>
                                <input type="text" name="national_id" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Firstname</label>
                                <input type="text" name="firstname" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lastname</label>
                                <input type="text" name="lastname" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact</label>
                                <input type="text" name="contact" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT USER MODAL -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editPortalId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" id="editFirstname" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" id="editLastname" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact</label>
                                <input type="text" id="editContact" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" id="editEmail" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select id="editRole" class="form-select" required>
                                    <option value="">Select role</option>
                                    <option value="admin">Admin</option>
                                    <option value="subadmin">Subadmin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" id="editPassword" class="form-control"
                                    placeholder="Leave blank to keep current">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ASSIGN TO MODAL -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign User To</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assignForm">
                        <input type="hidden" id="assignPortalId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Office</label>
                                <select id="officeSelect" class="form-select" required>
                                    <option value="">Select Office</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Division</label>
                                <select id="divisionSelect" class="form-select" required disabled>
                                    <option value="">Select Division</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Unit</label>
                                <select id="unitSelect" class="form-select" required disabled>
                                    <option value="">Select Unit</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <select id="designationSelect" class="form-select" required disabled>
                                    <option value="">Select Designation</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAssignment()">Save Assignment</button>
                </div>
            </div>
        </div>
    </div>

    <style>
    :root {
        --arta-blue1: #23395d;
        --arta-blue2: #1e2a3a;
        --arta-blue3: #0f2027;
        --arta-gray: #f5f6fa;
        --border-color: #e9ecef;
        --arta-header-bg: #1a2a3a;
        --arta-header-text: #ffffff;
        --arta-table-header: #0f2027;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: var(--arta-gray);
        color: #2c3e50;
    }

    /* HEADER */
    .header-bar {
        position: fixed;
        top: 0;
        right: 0;
        left: 250px;
        height: 70px;
        background: var(--arta-header-bg);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        z-index: 1000;
    }

    @media (max-width: 768px) {
        .header-bar {
            left: 0;
            padding: 0 15px;
        }
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--arta-header-text);
    }

    .header-title i {
        color: var(--arta-header-text);
    }

    .header-user i {
        color: var(--arta-header-text);
        font-size: 1.2rem;
        cursor: pointer;
    }

    /* Sidebar colors */
    .sidebar {
        background: var(--arta-blue2);
    }

    /* MAIN CONTENT */
    .main-content {
        margin-left: 250px;
        margin-top: 70px;
        min-height: calc(100vh - 70px);
        padding: 25px;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
        }
    }

    .content-container {
        max-width: 1800px;
        margin: 0 auto;
    }

    /* SEARCH SECTION */
    .search-section {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        align-items: center;
    }

    .search-wrapper {
        position: relative;
        flex: 1;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #95a5a6;
        font-size: 14px;
    }

    .search-input {
        width: 100%;
        padding: 10px 15px 10px 35px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--arta-blue1);
    }

    .search-count {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 13px;
        background: #f8f9fa;
        padding: 2px 8px;
        border-radius: 20px;
    }

    .btn-add {
        background: var(--arta-blue1);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-add:hover {
        background: var(--arta-blue2);
    }

    #rowsPerPage {
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        background: white;
    }

    /* TABLE VIEW */
    .table-view {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table thead {
        background: var(--arta-table-header);
    }

    .table thead th {
        padding: 15px 10px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        color: white;
        border-bottom: 2px solid var(--arta-blue2);
        text-align: left;
    }

    .table tbody td {
        padding: 12px 10px;
        font-size: 13px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    /* Column widths */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 8%;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 10%;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 10%;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 10%;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 8%;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 15%;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 8%;
    }

    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 11%;
    }

    .table th:nth-child(9),
    .table td:nth-child(9) {
        width: 20%;
    }

    /* Status badge */
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .status-badge.active {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: nowrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 4px 8px;
        border: none;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-action i {
        font-size: 11px;
    }

    .btn-action.view {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .btn-action.view:hover {
        background: #cfe2ff;
    }

    .btn-action.assign {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .btn-action.assign:hover {
        background: #cfe2ff;
    }

    .btn-action.edit {
        background: #fff3cd;
        color: #856404;
    }

    .btn-action.edit:hover {
        background: #ffe69c;
    }

    .btn-action.delete {
        background: #f8d7da;
        color: #dc3545;
    }

    .btn-action.delete:hover {
        background: #f1c1c5;
    }

    /* MOBILE VIEW */
    .mobile-view {
        display: none;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .user-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header {
        background: var(--arta-blue3);
        color: white;
        padding: 10px 15px;
        font-weight: 500;
        font-size: 13px;
    }

    .card-body {
        padding: 15px;
    }

    .info-row {
        display: flex;
        margin-bottom: 10px;
        font-size: 13px;
        align-items: flex-start;
    }

    .info-row .label {
        width: 90px;
        font-weight: 600;
        color: #6c757d;
    }

    .info-row .value {
        flex: 1;
        color: #2c3e50;
        word-break: break-word;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        padding: 10px 15px 15px;
        border-top: 1px solid var(--border-color);
    }

    .btn-card {
        flex: 1;
        padding: 8px;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-card.view {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .btn-card.assign {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .btn-card.edit {
        background: #fff3cd;
        color: #856404;
    }

    .btn-card.delete {
        background: #f8d7da;
        color: #dc3545;
    }

    /* FOOTER */
    .table-footer {
        margin-top: 20px;
    }

    .records-info {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 10px;
        text-align: center;
    }

    .pagination-wrapper {
        margin-bottom: 15px;
        text-align: center;
    }

    .pagination-wrapper button {
        background: white;
        border: 1px solid var(--border-color);
        padding: 5px 15px;
        margin: 0 5px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
    }

    .pagination-wrapper button:hover:not(:disabled) {
        background: var(--arta-blue1);
        color: white;
    }

    .pagination-wrapper button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-wrapper .active {
        background: var(--arta-blue1);
        color: white;
        border-color: var(--arta-blue1);
    }

    /* Modal header colors */
    .modal-header {
        background: var(--arta-blue2);
        color: white;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-title {
        color: white;
    }

    .btn-primary {
        background-color: var(--arta-blue1);
        border-color: var(--arta-blue1);
    }

    .btn-primary:hover {
        background-color: var(--arta-blue2);
        border-color: var(--arta-blue2);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    /* RESPONSIVE BREAKPOINTS */
    @media (max-width: 768px) {
        .table-view {
            display: none;
        }
        
        .mobile-view {
            display: flex;
        }
        
        .search-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-add {
            width: 100%;
        }

        #rowsPerPage {
            width: 100% !important;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    let currentPage = 1;
    let limit = 10;
    let totalRecords = 0;
    let usersData = [];

    // ============ LOAD USERS ============
    function loadUsers(page = 1) {
        currentPage = page;
        limit = parseInt(document.getElementById('rowsPerPage').value || 10);
        const query = document.getElementById('searchInput').value.trim();

        fetch(`../api/users/get_users.php?page=${page}&limit=${limit}&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(result => {
                if (result.status === 200) {
                    usersData = result.data.users || [];
                    totalRecords = result.data.totalRecords || usersData.length;
                    
                    renderTableView(usersData);
                    renderMobileView(usersData);
                    updateFooter(usersData.length, totalRecords);
                    renderPagination(result.data.currentPage, result.data.totalPages);
                } else {
                    document.getElementById("tableBody").innerHTML = `<tr><td colspan="9" class="text-center py-4">No users found</td></tr>`;
                    document.getElementById("mobileView").innerHTML = '<div class="text-center py-4">No users found</div>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById("tableBody").innerHTML = `<tr><td colspan="9" class="text-center text-danger">Failed to load users</td></tr>`;
            });
    }

    // ============ RENDER TABLE VIEW ============
    function renderTableView(users) {
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        if (!users || users.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4">No users found</td></tr>`;
            return;
        }

        users.forEach(user => {
            const created = user.date_created ? new Date(user.date_created).toLocaleDateString() : '';
            const row = `
                <tr>
                    <td>${user.portal_id ? user.portal_id.substring(0,8) : ''}</td>
                    <td>${user.national_id || ''}</td>
                    <td>${escapeHtml(user.firstname) || ''}</td>
                    <td>${escapeHtml(user.lastname) || ''}</td>
                    <td>${user.contact || ''}</td>
                    <td>${user.email || ''}</td>
                    <td>
                        <span class="status-badge ${user.role === 'admin' ? 'active' : 'inactive'}">
                            ${user.role || 'user'}
                        </span>
                    </td>
                    <td>${created}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action view" onclick="viewTransactions('${user.portal_id}')" title="View Transactions">
                                <i class="fa-regular fa-eye"></i> View
                            </button>
                            <button class="btn-action assign" onclick="openAssignModal('${user.portal_id}')" title="Assign">
                                <i class="fa-solid fa-building-user"></i> Assign
                            </button>
                            <button class="btn-action edit" onclick="openEditModal('${user.portal_id}')" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <button class="btn-action delete" onclick="deleteUser('${user.portal_id}')" title="Delete">
                                <i class="fa-regular fa-trash-can"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // ============ RENDER MOBILE VIEW ============
    function renderMobileView(users) {
        const mobileView = document.getElementById("mobileView");
        mobileView.innerHTML = "";

        if (!users || users.length === 0) {
            mobileView.innerHTML = '<div class="text-center py-4">No users found</div>';
            return;
        }

        users.forEach(user => {
            const created = user.date_created ? new Date(user.date_created).toLocaleDateString() : '';
            const card = `
                <div class="user-card">
                    <div class="card-header">
                        ID: ${user.portal_id ? user.portal_id.substring(0,8) : ''} | ${user.role || 'user'}
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="label">Name:</span>
                            <span class="value">${escapeHtml(user.firstname) || ''} ${escapeHtml(user.lastname) || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">National ID:</span>
                            <span class="value">${user.national_id || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Contact:</span>
                            <span class="value">${user.contact || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Email:</span>
                            <span class="value">${user.email || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Created:</span>
                            <span class="value">${created}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-card view" onclick="viewTransactions('${user.portal_id}')">
                            <i class="fa-regular fa-eye"></i> View
                        </button>
                        <button class="btn-card assign" onclick="openAssignModal('${user.portal_id}')">
                            <i class="fa-solid fa-building-user"></i> Assign
                        </button>
                        <button class="btn-card edit" onclick="openEditModal('${user.portal_id}')">
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </button>
                        <button class="btn-card delete" onclick="deleteUser('${user.portal_id}')">
                            <i class="fa-regular fa-trash-can"></i> Delete
                        </button>
                    </div>
                </div>
            `;
            mobileView.innerHTML += card;
        });
    }

    // ============ UPDATE FOOTER ============
    function updateFooter(displayed, total) {
        document.getElementById("recordCount").textContent = displayed;
        document.getElementById("recordsInfo").textContent = `Showing ${displayed} of ${total} records`;
    }

    // ============ RENDER PAGINATION ============
    function renderPagination(page, totalPages) {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = "";
        
        if (totalPages <= 1) return;

        // Previous button
        if (page > 1) {
            pagination.innerHTML += `<button onclick="loadUsers(${page - 1})">Prev</button>`;
        }

        // Page numbers
        const maxVisible = 5;
        let start = Math.max(1, page - Math.floor(maxVisible / 2));
        let end = Math.min(totalPages, start + maxVisible - 1);

        if (end - start + 1 < maxVisible) {
            start = Math.max(1, end - maxVisible + 1);
        }

        for (let i = start; i <= end; i++) {
            pagination.innerHTML += `<button class="${i === page ? 'active' : ''}" onclick="loadUsers(${i})">${i}</button>`;
        }

        // Next button
        if (page < totalPages) {
            pagination.innerHTML += `<button onclick="loadUsers(${page + 1})">Next</button>`;
        }
    }

    // ============ SEARCH ============
    document.getElementById("searchInput").addEventListener("keyup", function() {
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(() => loadUsers(1), 350);
    });

    // ============ ROWS PER PAGE ============
    document.getElementById("rowsPerPage").addEventListener("change", function() {
        loadUsers(1);
    });

    // ============ ADD USER ============
    document.getElementById("addUserForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(e.target).entries());

        fetch("../api/users/create_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(result => {
            if (result.status === 200) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    bootstrap.Modal.getInstance(document.getElementById("addUserModal")).hide();
                    document.getElementById("addUserForm").reset();
                    loadUsers(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        })
        .catch(() => Swal.fire({ icon: "error", title: "Error", text: "Request failed" }));
    });

    // ============ OPEN EDIT MODAL ============
    async function openEditModal(id) {
        try {
            const res = await fetch(`../api/users/get_users.php?id=${id}`);
            const result = await res.json();
            if (result.status !== 200) return Swal.fire('Error', 'User not found', 'error');
            
            const user = result.data;
            document.getElementById('editPortalId').value = user.portal_id;
            document.getElementById('editFirstname').value = user.firstname || '';
            document.getElementById('editLastname').value = user.lastname || '';
            document.getElementById('editContact').value = user.contact || '';
            document.getElementById('editEmail').value = user.email || '';
            document.getElementById('editRole').value = user.role || '';
            document.getElementById('editPassword').value = '';
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        } catch (err) {
            Swal.fire('Error', 'Server error', 'error');
        }
    }

    // ============ UPDATE USER ============
    async function updateUser() {
        const data = {
            portal_id: document.getElementById('editPortalId').value,
            firstname: document.getElementById('editFirstname').value,
            lastname: document.getElementById('editLastname').value,
            contact: document.getElementById('editContact').value,
            email: document.getElementById('editEmail').value,
            role: document.getElementById('editRole').value,
            password: document.getElementById('editPassword').value
        };

        try {
            const res = await fetch('../api/users/update_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    loadUsers(currentPage);
                });
            } else {
                Swal.fire('Error', result.message || 'Failed to update', 'error');
            }
        } catch {
            Swal.fire('Error', 'Server error', 'error');
        }
    }

    // ============ OPEN ASSIGN MODAL ============
    async function openAssignModal(portal_id) {
        document.getElementById('assignPortalId').value = portal_id;
        resetAssignModal();
        const modal = new bootstrap.Modal(document.getElementById('assignModal'));
        modal.show();
        await loadOffices();
        bindAssignEvents();
    }

    function resetAssignModal() {
        const office = document.getElementById('officeSelect');
        const division = document.getElementById('divisionSelect');
        const unit = document.getElementById('unitSelect');
        const designation = document.getElementById('designationSelect');

        office.innerHTML = '<option value="">Select Office</option>';
        division.innerHTML = '<option value="">Select Division</option>';
        unit.innerHTML = '<option value="">Select Unit</option>';
        designation.innerHTML = '<option value="">Select Designation</option>';

        division.disabled = true;
        unit.disabled = true;
        designation.disabled = true;
    }

    function bindAssignEvents() {
        document.getElementById('officeSelect').addEventListener('change', async (e) => {
            const officeId = e.target.value;
            if (!officeId) {
                document.getElementById('divisionSelect').innerHTML = '<option value="">Select Division</option>';
                document.getElementById('divisionSelect').disabled = true;
                document.getElementById('unitSelect').innerHTML = '<option value="">Select Unit</option>';
                document.getElementById('unitSelect').disabled = true;
                document.getElementById('designationSelect').innerHTML = '<option value="">Select Designation</option>';
                document.getElementById('designationSelect').disabled = true;
                return;
            }
            await loadDivisions(officeId);
        });

        document.getElementById('divisionSelect').addEventListener('change', async (e) => {
            const divisionId = e.target.value;
            if (!divisionId) {
                document.getElementById('unitSelect').innerHTML = '<option value="">Select Unit</option>';
                document.getElementById('unitSelect').disabled = true;
                document.getElementById('designationSelect').innerHTML = '<option value="">Select Designation</option>';
                document.getElementById('designationSelect').disabled = true;
                return;
            }
            await loadUnits(divisionId);
        });

        document.getElementById('unitSelect').addEventListener('change', async (e) => {
            const unitId = e.target.value;
            if (!unitId) {
                document.getElementById('designationSelect').innerHTML = '<option value="">Select Designation</option>';
                document.getElementById('designationSelect').disabled = true;
                return;
            }
            await loadDesignations(unitId);
        });
    }

    // ============ LOAD OFFICES ============
    async function loadOffices() {
        const select = document.getElementById('officeSelect');
        select.innerHTML = '<option value="">Loading...</option>';
        try {
            const res = await fetch('../api/offices/get_offices.php?limit=1000&page=1');
            const result = await res.json();
            if (result.status === 200 && result.data && result.data.offices) {
                select.innerHTML = '<option value="">Select Office</option>';
                result.data.offices.forEach(o => {
                    const id = o.id;
                    const name = o.office_name;
                    select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
                });
            } else {
                select.innerHTML = '<option value="">No offices found</option>';
            }
        } catch (err) {
            console.error('Error loading offices:', err);
            select.innerHTML = '<option value="">Error loading</option>';
        }
    }

    // ============ LOAD DIVISIONS ============
    async function loadDivisions(officeId) {
        const select = document.getElementById('divisionSelect');
        select.disabled = true;
        select.innerHTML = '<option>Loading...</option>';
        try {
            const res = await fetch(`../api/divisions/get_divisions.php?office_id=${officeId}&limit=1000&page=1`);
            const result = await res.json();
            select.innerHTML = '<option value="">Select Division</option>';
            if (result.status === 200 && result.data && result.data.divisions && result.data.divisions.length) {
                result.data.divisions.forEach(d => {
                    const id = d.id;
                    const name = d.division_name;
                    select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
                });
                select.disabled = false;
            } else {
                select.innerHTML = '<option value="">No divisions found</option>';
                select.disabled = true;
            }
        } catch (err) {
            console.error('Error loading divisions:', err);
            select.innerHTML = '<option value="">Error loading</option>';
            select.disabled = true;
        }
    }

    // ============ LOAD UNITS ============
    async function loadUnits(divisionId) {
        const select = document.getElementById('unitSelect');
        select.disabled = true;
        select.innerHTML = '<option>Loading...</option>';
        try {
            const res = await fetch(`../api/units/get_units.php?division_id=${divisionId}&limit=1000&page=1`);
            const result = await res.json();
            select.innerHTML = '<option value="">Select Unit</option>';
            if (result.status === 200 && result.data && result.data.units && result.data.units.length) {
                result.data.units.forEach(u => {
                    const id = u.id;
                    const name = u.unit_name;
                    select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
                });
                select.disabled = false;
            } else {
                select.innerHTML = '<option value="">No units found</option>';
                select.disabled = true;
            }
        } catch (err) {
            console.error('Error loading units:', err);
            select.innerHTML = '<option value="">Error loading</option>';
            select.disabled = true;
        }
    }

    // ============ LOAD DESIGNATIONS ============
    async function loadDesignations(unitId) {
        const select = document.getElementById('designationSelect');
        select.disabled = true;
        select.innerHTML = '<option>Loading...</option>';
        try {
            const res = await fetch(`../api/designations/get_designations.php?unit_id=${unitId}&limit=1000&page=1`);
            const result = await res.json();
            select.innerHTML = '<option value="">Select Designation</option>';
            if (result.status === 200 && result.data && result.data.designations && result.data.designations.length) {
                result.data.designations.forEach(d => {
                    const id = d.id;
                    const name = d.designation_name;
                    select.innerHTML += `<option value="${id}">${escapeHtml(name)}</option>`;
                });
                select.disabled = false;
            } else {
                select.innerHTML = '<option value="">No designations found</option>';
                select.disabled = true;
            }
        } catch (err) {
            console.error('Error loading designations:', err);
            select.innerHTML = '<option value="">Error loading</option>';
            select.disabled = true;
        }
    }

    // ============ SAVE ASSIGNMENT ============
    async function saveAssignment() {
        const portal_id = document.getElementById('assignPortalId').value;
        const office_id = document.getElementById('officeSelect').value;
        const division_id = document.getElementById('divisionSelect').value;
        const unit_id = document.getElementById('unitSelect').value;
        const designation_id = document.getElementById('designationSelect').value;

        if (!office_id || !division_id || !unit_id || !designation_id) {
            Swal.fire('Warning', 'Please complete all fields', 'warning');
            return;
        }

        try {
            const res = await fetch('../api/users/assign_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ portal_id, office_id, division_id, unit_id, designation_id })
            });
            const result = await res.json();
            if (result.status === 200) {
                Swal.fire('Assigned!', result.message || 'Assignment saved', 'success');
                bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
                loadUsers(currentPage);
            } else {
                Swal.fire('Error', result.message || 'Failed to assign', 'error');
            }
        } catch (err) {
            console.error('Error saving assignment:', err);
            Swal.fire('Error', 'Server error', 'error');
        }
    }

    // ============ VIEW TRANSACTIONS ============
    function viewTransactions(portal_id) {
        Swal.fire({
            title: 'Transactions',
            html: `Viewing transactions for user ID: ${portal_id}`,
            icon: 'info'
        });
    }

    // ============ DELETE USER ============
    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the user!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("../api/users/delete_user.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ portal_id: id })
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.status === 200) {
                        Swal.fire("Deleted!", resp.message, "success");
                        loadUsers(currentPage);
                    } else {
                        Swal.fire("Error!", resp.message, "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Failed to delete user", "error"));
            }
        });
    }

    // ============ ESCAPE HTML HELPER ============
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ============ INITIALIZE ============
    document.addEventListener("DOMContentLoaded", () => {
        loadUsers(1);
    });
    </script>

    <!-- ✅ Footer include -->
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>