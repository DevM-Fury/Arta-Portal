<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
    <!-- Sidebar -->
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- HEADER -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-building-columns me-2"></i> System Dashboard
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
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search system...">
                    <span class="search-count" id="recordCount">0</span>
                </div>
                <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addSystemModal">
                    <i class="fa-solid fa-plus me-2"></i>Add
                </button>
            </div>

            <!-- DESKTOP TABLE VIEW -->
            <div class="table-view">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-white bg-dark">ID</th>
                                <th class="text-white bg-dark">SYSTEM NAME</th>
                                <th class="text-white bg-dark">LOGO</th>
                                <th class="text-white bg-dark">STATUS</th>
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
    <div class="modal fade" id="addSystemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addSystemForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New System</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">System Name</label>
                            <input type="text" name="system_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Domain URL</label>
                            <input type="url" name="domain_url" class="form-control" placeholder="https://example.com">
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
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
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
    <div class="modal fade" id="editSystemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editSystemForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editSystemId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit System</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">System Name</label>
                            <input type="text" name="system_name" id="editSystemName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Domain URL</label>
                            <input type="url" name="domain_url" id="editSystemURL" class="form-control" placeholder="https://example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editSystemDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="editSystemStatus" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo (optional)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">Leave empty if you don't want to change the logo.</small>
                            <div class="mt-2" id="currentLogoContainer">
                                <img id="currentLogoPreview" src="" alt="Current Logo" style="max-height:60px; display:none;" class="border p-1 rounded">
                            </div>
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
        max-width: 1600px;
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

    /* TABLE VIEW - Perfectly aligned columns */
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
        table-layout: fixed; /* CRITICAL: This makes columns perfectly aligned */
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
        padding: 15px 10px;
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

    /* EXACT COLUMN WIDTHS - Perfect proportions */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 10%; /* ID column */
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 35%; /* SYSTEM NAME column */
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 15%; /* LOGO column */
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 15%; /* STATUS column */
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 25%; /* ACTION column */
    }

    /* Logo image */
    .table tbody td:nth-child(3) img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border-color);
        display: block;
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

    /* Action buttons - Perfectly spaced */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        flex: 1; /* Each button takes equal space */
        max-width: 80px;
    }

    .btn-action i {
        font-size: 12px;
    }

    .btn-action.edit {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .btn-action.edit:hover {
        background: #cfe2ff;
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

    .system-card {
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
        align-items: center;
    }

    .info-row .label {
        width: 90px;
        font-weight: 600;
        color: #6c757d;
    }

    .info-row .value {
        flex: 1;
        color: #2c3e50;
    }

    .info-row .logo-img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid var(--border-color);
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

    .btn-card.edit {
        background: #e7f1ff;
        color: #0d6efd;
    }

    .btn-card.edit:hover {
        background: #cfe2ff;
    }

    .btn-card.delete {
        background: #f8d7da;
        color: #dc3545;
    }

    .btn-card.delete:hover {
        background: #f1c1c5;
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

    /* Bootstrap modal adjustments */
    .modal-content {
        border: none;
        border-radius: 8px;
    }

    .btn-primary {
        background-color: var(--arta-blue1);
        border-color: var(--arta-blue1);
    }

    .btn-primary:hover {
        background-color: var(--arta-blue2);
        border-color: var(--arta-blue2);
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

    @media (max-width: 480px) {
        .btn-action {
            padding: 4px 6px;
            font-size: 10px;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    let currentPage = 1;
    const limit = 5;
    let totalRecords = 0;
    let systemsData = [];

    function loadSystems(page = 1) {
        fetch(`../api/systems/get_systems.php?page=${page}&limit=${limit}`)
            .then(res => res.json())
            .then(result => {
                if (result.status === 200) {
                    systemsData = result.data.systems || [];
                    totalRecords = result.data.total || systemsData.length;
                    
                    renderTableView(systemsData);
                    renderMobileView(systemsData);
                    updateFooter(systemsData.length, totalRecords);
                    renderPagination(result.data.currentPage, result.data.totalPages);
                } else {
                    document.getElementById("tableBody").innerHTML = `<tr><td colspan="5" class="text-center py-4">No systems found</td></tr>`;
                    document.getElementById("mobileView").innerHTML = '<div class="text-center py-4">No systems found</div>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById("tableBody").innerHTML = `<tr><td colspan="5" class="text-center text-danger">Failed to load systems</td></tr>`;
            });
    }

    function renderTableView(systems) {
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        if (!systems || systems.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4">No systems found</td></tr>`;
            return;
        }

        systems.forEach(system => {
            let logoPath = '../img/placeholder.png';
            
            if (system.logo_path) {
                logoPath = system.logo_path.replace(/^\.\.\/\.\.\//, '../');
                if (!logoPath.startsWith('../') && !logoPath.startsWith('http')) {
                    logoPath = '../' + logoPath;
                }
            }
            
            const row = `
                <tr>
                    <td>${system.id.toString().padStart(4,'0')}</td>
                    <td>${system.system_name || ''}</td>
                    <td>
                        <img src="${logoPath}" 
                             alt="${system.system_name} logo"
                             onerror="this.onerror=null; this.src='../img/placeholder.png';">
                    </td>
                    <td>
                        <span class="status-badge ${system.status === 'Active' ? 'active' : 'inactive'}">
                            ${system.status || 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action edit" onclick='openEditSystemModal(${JSON.stringify(system).replace(/'/g, "&apos;")})'>
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </button>
                            <button class="btn-action delete" onclick="deleteSystem(${system.id})">
                                <i class="fa-regular fa-trash-can"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function renderMobileView(systems) {
        const mobileView = document.getElementById("mobileView");
        mobileView.innerHTML = "";

        if (!systems || systems.length === 0) {
            mobileView.innerHTML = '<div class="text-center py-4">No systems found</div>';
            return;
        }

        systems.forEach(system => {
            let logoPath = '../img/placeholder.png';
            
            if (system.logo_path) {
                logoPath = system.logo_path.replace(/^\.\.\/\.\.\//, '../');
                if (!logoPath.startsWith('../') && !logoPath.startsWith('http')) {
                    logoPath = '../' + logoPath;
                }
            }
            
            const card = `
                <div class="system-card">
                    <div class="card-header">
                        System ID: ${system.id.toString().padStart(4,'0')}
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="label">System Name:</span>
                            <span class="value">${system.system_name || ''}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Logo:</span>
                            <span class="value">
                                <img src="${logoPath}" 
                                     class="logo-img"
                                     alt="${system.system_name} logo"
                                     onerror="this.onerror=null; this.src='../img/placeholder.png';">
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="value">
                                <span class="status-badge ${system.status === 'Active' ? 'active' : 'inactive'}">
                                    ${system.status || 'Inactive'}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-card edit" onclick='openEditSystemModal(${JSON.stringify(system).replace(/'/g, "&apos;")})'>
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </button>
                        <button class="btn-card delete" onclick="deleteSystem(${system.id})">
                            <i class="fa-regular fa-trash-can"></i> Delete
                        </button>
                    </div>
                </div>
            `;
            mobileView.innerHTML += card;
        });
    }

    function updateFooter(displayed, total) {
        document.getElementById("recordCount").textContent = displayed;
        document.getElementById("recordsInfo").textContent = `Showing ${displayed} of ${total} records`;
    }

    function renderPagination(page, totalPages) {
        const pagination = document.getElementById("pagination");
        if (totalPages <= 1) {
            pagination.innerHTML = "";
            return;
        }

        pagination.innerHTML = `
            <button ${page === 1 ? 'disabled' : ''} onclick="loadSystems(${page - 1})">Prev</button>
            <span>Page ${page} of ${totalPages}</span>
            <button ${page === totalPages ? 'disabled' : ''} onclick="loadSystems(${page + 1})">Next</button>
        `;
    }

    // Search
    document.getElementById("searchInput").addEventListener("keyup", function() {
        const filter = this.value.toLowerCase();
        const filtered = systemsData.filter(system => 
            (system.system_name && system.system_name.toLowerCase().includes(filter)) || 
            system.id.toString().includes(filter)
        );
        
        renderTableView(filtered);
        renderMobileView(filtered);
        updateFooter(filtered.length, totalRecords);
    });

    // Add System
    document.getElementById("addSystemForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("../api/systems/create_system.php", {
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
                    bootstrap.Modal.getInstance(document.getElementById("addSystemModal")).hide();
                    document.getElementById("addSystemForm").reset();
                    loadSystems(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        })
        .catch(() => Swal.fire({ icon: "error", title: "Error", text: "Request failed" }));
    });

    // Edit System
    function openEditSystemModal(system) {
        document.getElementById("editSystemId").value = system.id;
        document.getElementById("editSystemName").value = system.system_name || '';
        document.getElementById("editSystemURL").value = system.domain_url || '';
        document.getElementById("editSystemDescription").value = system.description || '';
        document.getElementById("editSystemStatus").value = system.status || 'Active';
        
        const preview = document.getElementById("currentLogoPreview");
        if (system.logo_path) {
            let logoPath = system.logo_path.replace(/^\.\.\/\.\.\//, '../');
            if (!logoPath.startsWith('../') && !logoPath.startsWith('http')) {
                logoPath = '../' + logoPath;
            }
            preview.src = logoPath;
            preview.style.display = "block";
            preview.onerror = function() {
                this.style.display = "none";
            };
        } else {
            preview.style.display = "none";
        }
        
        new bootstrap.Modal(document.getElementById("editSystemModal")).show();
    }

    document.getElementById("editSystemForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../api/systems/update_system.php", {
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
                    bootstrap.Modal.getInstance(document.getElementById("editSystemModal")).hide();
                    loadSystems(1);
                });
            } else {
                Swal.fire({ icon: "error", title: "Error", text: result.message });
            }
        });
    });

    // Delete System
    function deleteSystem(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the system!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("../api/systems/delete_system.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${id}`
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.status === 200) {
                        Swal.fire("Deleted!", resp.message, "success");
                        loadSystems(1);
                    } else {
                        Swal.fire("Error!", resp.message, "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Failed to delete system", "error"));
            }
        });
    }

    // Initialize
    document.addEventListener("DOMContentLoaded", () => loadSystems(1));
    </script>

</body>
</html>