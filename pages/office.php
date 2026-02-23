<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
    <!-- Sidebar -->
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- HEADER -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-building-columns me-2"></i> LIST OF OFFICE
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
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search office...">
                    <span class="search-count" id="recordCount">0</span>
                </div>
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addOfficeModal">
                    <i class="fa-solid fa-plus me-2"></i>Add New Office
                </button>
            </div>

            <!-- DESKTOP TABLE VIEW -->
            <div class="table-view">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-white bg-dark">ID</th>
                                <th class="text-white bg-dark">OFFICE NAME</th>
                                <th class="text-white bg-dark">DESCRIPTION</th>
                                <th class="text-white bg-dark">STATUS</th>
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
    <div class="modal fade" id="addOfficeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addOfficeForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Office</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Office Name</label>
                            <input type="text" name="office_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
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
    <div class="modal fade" id="editOfficeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editOfficeForm">
                    <input type="hidden" name="id" id="editOfficeId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Office</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Office Name</label>
                            <input type="text" name="office_name" id="editOfficeName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editOfficeDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="editOfficeStatus" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
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
        width: 25%;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 8%;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 12%;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 12%;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 21%;
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

    .office-card {
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
    let officesData = [];

    // LOAD OFFICES
    function loadOffices(page = 1) {
        fetch(`../api/offices/get_offices.php?page=${page}&limit=${limit}`)
            .then(res => res.json())
            .then(result => {
                if (result.status === 200) {
                    officesData = result.data.offices || [];
                    totalRecords = result.data.total || officesData.length;
                    
                    renderTableView(officesData);
                    renderMobileView(officesData);
                    updateFooter(officesData.length, totalRecords);
                    renderPagination(result.data.currentPage, result.data.totalPages);
                } else {
                    document.getElementById("tableBody").innerHTML = `<tr><td colspan="7" class="text-center py-4">No offices found</td></tr>`;
                    document.getElementById("mobileView").innerHTML = '<div class="text-center py-4">No offices found</div>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById("tableBody").innerHTML = `<tr><td colspan="7" class="text-center text-danger">Failed to load offices</td></tr>`;
            });
    }

    // RENDER TABLE VIEW
    function renderTableView(offices) {
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        if (!offices || offices.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No offices found</td></tr>`;
            return;
        }

        offices.forEach(office => {
            const row = `
                <tr>
                    <td>OFF-${office.id.toString().padStart(4,'0')}</td>
                    <td>${office.office_name || ''}</td>
                    <td>${office.description ? (office.description.length > 30 ? office.description.substring(0,30) + '...' : office.description) : ''}</td>
                    <td>
                        <span class="status-badge ${office.status === 'Active' ? 'active' : 'inactive'}">
                            ${office.status || 'Inactive'}
                        </span>
                    </td>
                    <td>${office.created_at ? new Date(office.created_at).toLocaleDateString() : ''}</td>
                    <td>${office.updated_at ? new Date(office.updated_at).toLocaleDateString() : ''}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action view" onclick="viewOffice(${office.id})">
                                <i class="fa-regular fa-eye"></i> View
                            </button>
                            <button class="btn-action edit" onclick='openEditOfficeModal(${JSON.stringify(office).replace(/'/g, "&apos;")})'>
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <button class="btn-action delete" onclick="deleteOffice(${office.id})">
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
    function renderMobileView(offices) {
        const mobileView = document.getElementById("mobileView");
        mobileView.innerHTML = "";

        if (!offices || offices.length === 0) {
            mobileView.innerHTML = '<div class="text-center py-4">No offices found</div>';
            return;
        }

        offices.forEach(office => {
            const card = `
                <div class="office-card">
                    <div class="card-header">
                        OFFICE-${office.id.toString().padStart(4,'0')}
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="label">Name:</span>
                            <span class="value">${office.office_name || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Description:</span>
                            <span class="value">${office.description || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="value">
                                <span class="status-badge ${office.status === 'Active' ? 'active' : 'inactive'}">
                                    ${office.status || 'Inactive'}
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Created:</span>
                            <span class="value">${office.created_at ? new Date(office.created_at).toLocaleDateString() : ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Modified:</span>
                            <span class="value">${office.updated_at ? new Date(office.updated_at).toLocaleDateString() : ''}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-card view" onclick="viewOffice(${office.id})">
                            <i class="fa-regular fa-eye"></i> View
                        </button>
                        <button class="btn-card edit" onclick='openEditOfficeModal(${JSON.stringify(office).replace(/'/g, "&apos;")})'>
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </button>
                        <button class="btn-card delete" onclick="deleteOffice(${office.id})">
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
            <button ${page === 1 ? 'disabled' : ''} onclick="loadOffices(${page - 1})">Prev</button>
            <span>Page ${page} of ${totalPages}</span>
            <button ${page === totalPages ? 'disabled' : ''} onclick="loadOffices(${page + 1})">Next</button>
        `;
    }

    // SEARCH
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const filter = this.value.toLowerCase();
        const filtered = officesData.filter(office => 
            (office.office_name && office.office_name.toLowerCase().includes(filter)) || 
            (office.description && office.description.toLowerCase().includes(filter)) ||
            office.id.toString().includes(filter)
        );
        
        renderTableView(filtered);
        renderMobileView(filtered);
        updateFooter(filtered.length, totalRecords);
    });

    // ADD OFFICE
    document.getElementById("addOfficeForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../api/offices/create_office.php", {
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
                    bootstrap.Modal.getInstance(document.getElementById("addOfficeModal")).hide();
                    document.getElementById("addOfficeForm").reset();
                    loadOffices(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        })
        .catch(() => Swal.fire({ icon: "error", title: "Error", text: "Request failed" }));
    });

    // OPEN EDIT MODAL
    function openEditOfficeModal(office) {
        document.getElementById("editOfficeId").value = office.id;
        document.getElementById("editOfficeName").value = office.office_name || '';
        document.getElementById("editOfficeDescription").value = office.description || '';
        document.getElementById("editOfficeStatus").value = office.status || 'Active';
        
        new bootstrap.Modal(document.getElementById("editOfficeModal")).show();
    }

    // UPDATE OFFICE
    document.getElementById("editOfficeForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../api/offices/update_office.php", {
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
                    bootstrap.Modal.getInstance(document.getElementById("editOfficeModal")).hide();
                    loadOffices(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        });
    });

    // DELETE OFFICE
    function deleteOffice(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the office!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("../api/offices/delete_office.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${id}`
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.status === 200) {
                        Swal.fire("Deleted!", resp.message, "success");
                        loadOffices(1);
                    } else {
                        Swal.fire("Error!", resp.message, "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Failed to delete office", "error"));
            }
        });
    }

    // VIEW OFFICE (dummy)
    function viewOffice(id) {
        Swal.fire({
            title: 'Office Details',
            html: `Viewing details for office ID: OFF-${String(id).padStart(4,'0')}`,
            icon: 'info'
        });
    }

    // INITIALIZE
    document.addEventListener("DOMContentLoaded", () => loadOffices(1));
    </script>

</body>
</html>