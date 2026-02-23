<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
    <!-- Sidebar -->
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- HEADER -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-sitemap me-2"></i> List of Divisions
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
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search division...">
                    <span class="search-count" id="recordCount">0</span>
                </div>
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
                    <i class="fa-solid fa-plus me-2"></i>Add New Division
                </button>
            </div>

            <!-- DESKTOP TABLE VIEW -->
            <div class="table-view">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-white bg-dark">ID</th>
                                <th class="text-white bg-dark">DIVISION NAME</th>
                                <th class="text-white bg-dark">DESCRIPTION</th>
                                <th class="text-white bg-dark">STATUS</th>
                                <th class="text-white bg-dark">ASSIGNED OFFICE</th>
                                <th class="text-white bg-dark">CREATED</th>
                                <th class="text-white bg-dark">MODIFIED</th>
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

    <!-- ADD MODAL -->
    <div class="modal fade" id="addDivisionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addDivisionForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Division</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Division Name</label>
                            <input type="text" name="division_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="division_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="division_status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assigned Office</label>
                            <select name="office_id" id="divisionOfficeSelect" class="form-select">
                                <option value="">Select Office</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editDivisionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editDivisionForm">
                    <input type="hidden" name="id" id="editDivisionId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Division</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Division Name</label>
                            <input type="text" name="division_name" id="editDivisionName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="division_description" id="editDivisionDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="division_status" id="editDivisionStatus" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assigned Office</label>
                            <select name="office_id" id="editDivisionOfficeSelect" class="form-select">
                                <option value="">Select Office</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
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
        width: 7%;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 15%;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 20%;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 8%;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 15%;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 10%;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 10%;
    }

    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 15%;
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

    .division-card {
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
        width: 100px;
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
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    let currentPage = 1;
    const limit = 5;
    let totalRecords = 0;
    let divisionsData = [];

    // Populate office selects
    async function populateOfficeSelects() {
        try {
            const res = await fetch('../api/offices/get_office_list.php');
            const result = await res.json();
            if (result.status === 200) {
                const offices = result.data.offices || [];
                const addSel = document.getElementById('divisionOfficeSelect');
                const editSel = document.getElementById('editDivisionOfficeSelect');
                [addSel, editSel].forEach(sel => {
                    if (!sel) return;
                    sel.innerHTML = '<option value="">Select Office</option>';
                    offices.forEach(o => {
                        const opt = document.createElement('option');
                        opt.value = o.id;
                        opt.textContent = o.office_name;
                        sel.appendChild(opt);
                    });
                });
            }
        } catch (err) {
            console.error('Error loading offices', err);
        }
    }

    // LOAD DIVISIONS
    function loadDivisions(page = 1) {
        fetch(`../api/divisions/get_divisions.php?page=${page}&limit=${limit}`)
            .then(res => res.json())
            .then(result => {
                if (result.status === 200) {
                    divisionsData = result.data.divisions || [];
                    totalRecords = result.data.total || divisionsData.length;
                    
                    renderTableView(divisionsData);
                    renderMobileView(divisionsData);
                    updateFooter(divisionsData.length, totalRecords);
                    renderPagination(result.data.currentPage, result.data.totalPages);
                } else {
                    document.getElementById("tableBody").innerHTML = `<tr><td colspan="8" class="text-center py-4">No divisions found</td></tr>`;
                    document.getElementById("mobileView").innerHTML = '<div class="text-center py-4">No divisions found</div>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById("tableBody").innerHTML = `<tr><td colspan="8" class="text-center text-danger">Failed to load divisions</td></tr>`;
            });
    }

    // RENDER TABLE VIEW
    function renderTableView(divisions) {
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        if (!divisions || divisions.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4">No divisions found</td></tr>`;
            return;
        }

        divisions.forEach(division => {
            const officeName = division.office_name || '-';
            const row = `
                <tr>
                    <td>DIV-${division.id.toString().padStart(4,'0')}</td>
                    <td>${escapeHtml(division.division_name) || ''}</td>
                    <td>${division.division_description ? (division.division_description.length > 30 ? division.division_description.substring(0,30) + '...' : division.division_description) : ''}</td>
                    <td>
                        <span class="status-badge ${division.division_status === 'Active' ? 'active' : 'inactive'}">
                            ${division.division_status || 'Inactive'}
                        </span>
                    </td>
                    <td>${escapeHtml(officeName)}</td>
                    <td>${division.created_at ? new Date(division.created_at).toLocaleDateString() : ''}</td>
                    <td>${division.updated_at ? new Date(division.updated_at).toLocaleDateString() : ''}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action view" onclick="viewDivision(${division.id})">
                                <i class="fa-regular fa-eye"></i> View
                            </button>
                            <button class="btn-action edit" onclick='openEditDivisionModal(${JSON.stringify(division).replace(/'/g, "&apos;")})'>
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <button class="btn-action delete" onclick="deleteDivision(${division.id})">
                                <i class="fa-regular fa-trash-can"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // RENDER MOBILE VIEW
    function renderMobileView(divisions) {
        const mobileView = document.getElementById("mobileView");
        mobileView.innerHTML = "";

        if (!divisions || divisions.length === 0) {
            mobileView.innerHTML = '<div class="text-center py-4">No divisions found</div>';
            return;
        }

        divisions.forEach(division => {
            const officeName = division.office_name || '-';
            const card = `
                <div class="division-card">
                    <div class="card-header">
                        DIV-${division.id.toString().padStart(4,'0')}
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="label">Name:</span>
                            <span class="value">${escapeHtml(division.division_name) || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Description:</span>
                            <span class="value">${escapeHtml(division.division_description) || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="value">
                                <span class="status-badge ${division.division_status === 'Active' ? 'active' : 'inactive'}">
                                    ${division.division_status || 'Inactive'}
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Office:</span>
                            <span class="value">${escapeHtml(officeName)}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Created:</span>
                            <span class="value">${division.created_at ? new Date(division.created_at).toLocaleDateString() : ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Modified:</span>
                            <span class="value">${division.updated_at ? new Date(division.updated_at).toLocaleDateString() : ''}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-card view" onclick="viewDivision(${division.id})">
                            <i class="fa-regular fa-eye"></i> View
                        </button>
                        <button class="btn-card edit" onclick='openEditDivisionModal(${JSON.stringify(division).replace(/'/g, "&apos;")})'>
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </button>
                        <button class="btn-card delete" onclick="deleteDivision(${division.id})">
                            <i class="fa-regular fa-trash-can"></i> Delete
                        </button>
                    </div>
                </div>
            `;
            mobileView.innerHTML += card;
        });
    }

    // UPDATE FOOTER
    function updateFooter(displayed, total) {
        document.getElementById("recordCount").textContent = displayed;
        document.getElementById("recordsInfo").textContent = `Showing ${displayed} of ${total} records`;
    }

    // RENDER PAGINATION
    function renderPagination(page, totalPages) {
        const pagination = document.getElementById("pagination");
        if (totalPages <= 1) {
            pagination.innerHTML = "";
            return;
        }

        pagination.innerHTML = `
            <button ${page === 1 ? 'disabled' : ''} onclick="loadDivisions(${page - 1})">Prev</button>
            <span>Page ${page} of ${totalPages}</span>
            <button ${page === totalPages ? 'disabled' : ''} onclick="loadDivisions(${page + 1})">Next</button>
        `;
    }

    // SEARCH
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const filter = this.value.toLowerCase();
        const filtered = divisionsData.filter(division => 
            (division.division_name && division.division_name.toLowerCase().includes(filter)) || 
            (division.division_description && division.division_description.toLowerCase().includes(filter)) ||
            (division.office_name && division.office_name.toLowerCase().includes(filter)) ||
            division.id.toString().includes(filter)
        );
        
        renderTableView(filtered);
        renderMobileView(filtered);
        updateFooter(filtered.length, totalRecords);
    });

    // ADD DIVISION
    document.getElementById("addDivisionForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../api/divisions/create_division.php", {
            method: "POST",
            body: formData
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
                    bootstrap.Modal.getInstance(document.getElementById("addDivisionModal")).hide();
                    document.getElementById("addDivisionForm").reset();
                    loadDivisions(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        })
        .catch(() => Swal.fire({ icon: "error", title: "Error", text: "Request failed" }));
    });

    // OPEN EDIT MODAL
    function openEditDivisionModal(division) {
        document.getElementById("editDivisionId").value = division.id;
        document.getElementById("editDivisionName").value = division.division_name || '';
        document.getElementById("editDivisionDescription").value = division.division_description || '';
        document.getElementById("editDivisionStatus").value = division.division_status || 'Active';
        document.getElementById("editDivisionOfficeSelect").value = division.office_id || '';
        
        new bootstrap.Modal(document.getElementById("editDivisionModal")).show();
    }

    // UPDATE DIVISION
    document.getElementById("editDivisionForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../api/divisions/update_division.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (result.status === 200) {
                Swal.fire({
                    icon: "success",
                    title: "Updated!",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    bootstrap.Modal.getInstance(document.getElementById("editDivisionModal")).hide();
                    loadDivisions(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        });
    });

    // DELETE DIVISION
    function deleteDivision(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the division!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("../api/divisions/delete_division.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${id}`
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.status === 200) {
                        Swal.fire("Deleted!", resp.message, "success");
                        loadDivisions(1);
                    } else {
                        Swal.fire("Error!", resp.message, "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Failed to delete division", "error"));
            }
        });
    }

    // VIEW DIVISION
    function viewDivision(id) {
        Swal.fire({
            title: 'Division Details',
            html: `Viewing details for division ID: DIV-${String(id).padStart(4,'0')}`,
            icon: 'info'
        });
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // INITIALIZE
    document.addEventListener("DOMContentLoaded", () => {
        populateOfficeSelects();
        loadDivisions(1);
    });
    </script>

    <!-- ✅ Footer include -->
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>