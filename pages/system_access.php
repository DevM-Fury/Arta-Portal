
<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
    <?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-network-wired me-2"></i> System Access
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid px-2 px-md-3">
            <div class="card shadow-lg border-0 mx-auto" style="width:100%; max-width:1800px;">
                <div class="card-body p-3 p-md-4">

                    <div class="filter-section mb-4">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <select id="filterType" class="form-select">
                                <option value="">Filter By</option>
                                <option value="office">Office</option>
                                <option value="division">Division</option>
                                <option value="unit">Unit</option>
                                <option value="designation">Designation</option>
                            </select>

                            <select id="filterValue" class="form-select">
                                <option value="">Select...</option>
                            </select>

                            <div class="d-flex gap-2">
                                <button class="btn btn-dark flex-fill" onclick="loadUsers()">
                                    <i class="fa-solid fa-filter me-2"></i> Filter
                                </button>
                                <button class="btn btn-success flex-fill" onclick="openBulkAssignModal()">
                                    <i class="fa-solid fa-layer-group me-2"></i> Bulk
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 g-md-4">
                        <div class="col-12 col-lg-6">
                            <div class="d-flex flex-column gap-2 mb-2 mb-md-3">
                                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fa-solid fa-users me-2 text-primary"></i> Users List
                                        </h6>
                                        <span class="badge bg-secondary ms-2" id="userCount">0</span>
                                    </div>

                                    <div class="table-search-wrap">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="search" id="userSearchInput" class="form-control table-search-input" placeholder="Search users...">
                                    </div>
                                </div>
                            </div>

                            <div class="table-container">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="usersTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAllCheckbox" class="form-check-input"></th>
                                                <th>#</th>
                                                <th>Full Name</th>
                                                <th>Office</th>
                                                <th>Division</th>
                                                <th>Unit</th>
                                                <th>Designation</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTableBody">
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-5">Please select a filter...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-pagination" id="userPagination"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="d-flex flex-column gap-2 mb-2 mb-md-3">
                                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fa-solid fa-clock-rotate-left me-2 text-success"></i> Recently Assigned
                                        </h6>
                                        <span class="badge bg-secondary ms-2" id="recentCount">0</span>
                                    </div>

                                    <div class="table-search-wrap">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="search" id="assignedSearchInput" class="form-control table-search-input" placeholder="Search assignments...">
                                    </div>
                                </div>
                            </div>

                            <div class="table-container">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="assignedTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                                <th>System</th>
                                                <th>Role</th>
                                                <th>Assigned By</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="assignedTableBody">
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-5">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-pagination" id="assignedPagination"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="footer text-center py-2 py-md-3">
        <small>ARTA Government System v2.1 | Secure Access Portal</small>
    </div>

    <div class="modal fade" id="assignSystemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-share-nodes me-2"></i> Assign System
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="assignPortalId">

                    <div class="mb-3">
                        <label class="form-label small">Select System</label>
                        <select id="systemSelect" class="form-select form-select-sm">
                            <option value="">Select System</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Select Role</label>
                        <select id="roleSelect" class="form-select form-select-sm">
                            <option value="">Select Role</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer py-2">
                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" onclick="assignSystem()">Assign</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkAssignSystemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success text-white py-2">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-layer-group me-2"></i> Bulk Assign
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        <i class="fa-solid fa-users me-2"></i>
                        Selected: <span id="selectedCount" class="fw-bold">0</span> user(s)
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Select System</label>
                        <select id="bulkSystemSelect" class="form-select form-select-sm">
                            <option value="">Select System</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Select Role</label>
                        <select id="bulkRoleSelect" class="form-select form-select-sm">
                            <option value="">Select Role</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer py-2">
                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-success" onclick="bulkAssignSystem()">Assign</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAssignedRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark py-2">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit Assigned Role
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editAccessId">

                    <div class="mb-2 small">
                        <strong>User:</strong> <span id="editAssignedUser">-</span>
                    </div>

                    <div class="mb-3 small">
                        <strong>System:</strong> <span id="editAssignedSystem">-</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Select New Role</label>
                        <select id="editAssignedRoleSelect" class="form-select form-select-sm">
                            <option value="">Select Role</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer py-2">
                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-warning" onclick="updateAssignedRole()">Update Role</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

    :root {
        --arta-blue1: #1a2639;
        --arta-blue2: #2c3e50;
        --arta-blue3: #0a1a2f;
        --arta-gray: #f8fafc;
        --arta-border: #e2e8f0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--arta-gray);
        color: #1e293b;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .header-bar {
        background: linear-gradient(135deg, #0a1a2f, #1e3a5f);
        color: white;
        padding: 12px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-title {
        font-size: 16px;
        font-weight: 600;
    }

    .main-content {
        padding: 10px 12px 15px;
        flex: 1;
    }

    .card {
        border-radius: 12px;
        border: 1px solid var(--arta-border);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        background: white;
    }

    .card-body {
        padding: 1rem !important;
    }

    .filter-section {
        width: 100%;
    }

    .form-select {
        border-radius: 8px;
        border: 1px solid var(--arta-border);
        padding: 8px 12px;
        font-size: 13px;
        min-height: 40px;
    }

    .btn {
        font-weight: 500;
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 8px;
        min-height: 40px;
        white-space: nowrap;
    }

    .btn-dark {
        background: #1e3a5f;
        border: none;
    }

    .btn-dark:hover {
        background: #0a1a2f;
    }

    .btn-success {
        background: #059669;
        border: none;
    }

    .btn-outline-primary {
        border: 1px solid #1e3a5f;
        color: #1e3a5f;
        font-size: 12px;
        padding: 4px 8px;
        min-height: 30px;
    }

    .btn-outline-primary:hover {
        background: #1e3a5f;
        color: white;
    }

    .table-container {
        border: 1px solid var(--arta-border);
        border-radius: 10px;
        overflow: hidden;
        background: white;
    }

    .table-search-wrap {
        position: relative;
        min-width: 220px;
        max-width: 320px;
        width: 100%;
    }

    .table-search-wrap i {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 12px;
        pointer-events: none;
    }

    .table-search-input {
        padding-left: 34px;
        min-height: 38px;
        font-size: 13px;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        font-size: 13px;
        margin-bottom: 0;
        min-width: 1000px;
    }

    .table thead th {
        background: #1e3a5f;
        color: white;
        font-weight: 500;
        font-size: 12px;
        padding: 10px 8px;
        border: none;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 10px 8px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        transform: none !important;
        transition: background-color 0.15s ease, color 0.15s ease !important;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
        transform: none !important;
        box-shadow: none !important;
    }

    .table tbody tr,
    .table tbody tr:hover td,
    .table-hover > tbody > tr:hover > * {
        transform: none !important;
        box-shadow: none !important;
    }

    .table tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .table-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 14px;
        border-top: 1px solid var(--arta-border);
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        flex-wrap: wrap;
    }

    .table-pagination-info {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border: 1px solid #dbe7f3;
        border-radius: 999px;
        background: #f5f9fd;
        color: #35506b;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
    }

    .table-pagination-info strong {
        color: #0f2740;
        font-weight: 700;
    }

    .table-pagination-meta {
        color: #6b7f93;
        font-weight: 500;
    }

    .table-pagination-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .table-pagination-pages {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px;
        background: #edf4fb;
        border: 1px solid #d9e5f0;
        border-radius: 999px;
    }

    .table-page-ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        color: #6b7f93;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        user-select: none;
    }

    .table-page-btn {
        border: 1px solid transparent;
        background: #ffffff;
        color: #24415f;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        min-width: 38px;
        min-height: 34px;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(15, 39, 64, 0.08);
        transition: background-color 0.16s ease, color 0.16s ease, border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .table-page-btn:hover:not(:disabled) {
        background: #e7f0fa;
        border-color: #b7cde2;
        color: #102b45;
        box-shadow: 0 3px 10px rgba(15, 39, 64, 0.08);
    }

    .table-page-btn.active {
        background: linear-gradient(135deg, #1e3a5f, #315b89);
        color: white;
        border-color: #1e3a5f;
        box-shadow: 0 6px 14px rgba(30, 58, 95, 0.22);
    }

    .table-page-btn.nav-btn {
        background: #0f2740;
        color: white;
        border-color: #0f2740;
        padding-inline: 14px;
    }

    .table-page-btn.nav-btn:hover:not(:disabled) {
        background: #173657;
        border-color: #173657;
        color: white;
    }

    .table-page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        box-shadow: none;
    }

    .badge {
        font-weight: 500;
        padding: 4px 8px;
        font-size: 10px;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
        border: 1.5px solid #94a3b8;
    }

    .form-check-input:checked {
        background-color: #1e3a5f;
        border-color: #1e3a5f;
    }

    .footer {
        background: white;
        border-top: 1px solid var(--arta-border);
        color: #64748b;
        font-size: 11px;
    }

    @media (max-width: 768px) {
        .header-title {
            font-size: 14px;
        }

        .main-content {
            padding: 8px 10px 12px;
        }

        .card-body {
            padding: 0.75rem !important;
        }

        .btn {
            font-size: 12px;
            padding: 6px 10px;
            min-height: 36px;
        }

        .table {
            min-width: 100%;
        }

        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 12px;
            background: white;
            border: 1px solid var(--arta-border);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            text-align: right;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        .table tbody td:last-child {
            border-bottom: none;
        }

        .table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #1e3a5f;
            font-size: 11px;
        }

        #usersTable tbody td:nth-child(1)::before { content: "Select"; }
        #usersTable tbody td:nth-child(2)::before { content: "#"; }
        #usersTable tbody td:nth-child(3)::before { content: "Name"; }
        #usersTable tbody td:nth-child(4)::before { content: "Office"; }
        #usersTable tbody td:nth-child(5)::before { content: "Division"; }
        #usersTable tbody td:nth-child(6)::before { content: "Unit"; }
        #usersTable tbody td:nth-child(7)::before { content: "Designation"; }
        #usersTable tbody td:nth-child(8)::before { content: "Action"; }

        #assignedTable tbody td:nth-child(1)::before { content: "#"; }
        #assignedTable tbody td:nth-child(2)::before { content: "User"; }
        #assignedTable tbody td:nth-child(3)::before { content: "System"; }
        #assignedTable tbody td:nth-child(4)::before { content: "Role"; }
        #assignedTable tbody td:nth-child(5)::before { content: "Assigned By"; }
        #assignedTable tbody td:nth-child(6)::before { content: "Date"; }
        #assignedTable tbody td:nth-child(7)::before { content: "Action"; }

        .btn-outline-primary {
            width: auto;
            padding: 6px 12px;
            font-size: 12px;
        }

        .table-search-wrap {
            max-width: 100%;
        }

        .col-lg-6 {
            margin-bottom: 15px;
        }

        .col-lg-6:last-child {
            margin-bottom: 0;
        }

        h6 {
            font-size: 13px;
        }

        .badge {
            font-size: 9px;
            padding: 3px 6px;
        }

        .modal-dialog {
            margin: 10px;
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            padding: 12px;
        }

        .modal-body {
            padding: 16px;
        }

        .modal-footer {
            padding: 12px;
        }

        .table-pagination {
            padding: 10px;
        }

        .table-pagination-info {
            width: 100%;
            justify-content: center;
        }

        .table-pagination-controls {
            width: 100%;
            justify-content: center;
        }

        .table-pagination-pages {
            max-width: 100%;
            overflow-x: auto;
        }
    }

    @media (max-width: 375px) {
        .table tbody td {
            padding: 8px 10px;
            font-size: 11px;
        }

        .btn {
            font-size: 11px;
            padding: 5px 8px;
        }

        .filter-section .d-flex {
            gap: 6px;
        }
    }

    @media (max-width: 992px) and (orientation: landscape) {
        .table-container {
            max-height: 300px;
            overflow-y: auto;
        }
    }
    </style>

    <script>
    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function escapeAttr(value) {
        return escapeHtml(value);
    }

    function updateCounts() {
        document.getElementById('userCount').textContent = String(getFilteredUsers().length);
        document.getElementById('recentCount').textContent = String(getFilteredAssigned().length);
    }

    function formatRoleLabel(roleName) {
        return String(roleName || '')
            .split('_')
            .filter(Boolean)
            .map(part => part.charAt(0).toUpperCase() + part.slice(1))
            .join(' ');
    }

    let csrfTokenCache = null;
    let usersData = [];
    let assignedData = [];
    let userCurrentPage = 1;
    let assignedCurrentPage = 1;
    const tablePageSize = 10;
    const selectedUserIds = new Set();

    function normalizeSearchValue(value) {
        return String(value || '').toLowerCase().trim();
    }

    function getFilteredUsers() {
        const term = normalizeSearchValue(document.getElementById('userSearchInput').value);

        if (!term) {
            return usersData;
        }

        return usersData.filter(user => {
            const haystack = [
                user.firstname,
                user.lastname,
                user.office_name,
                user.division_name,
                user.unit_name,
                user.designation_name,
                user.portal_id,
            ].map(normalizeSearchValue).join(' ');

            return haystack.includes(term);
        });
    }

    function getFilteredAssigned() {
        const term = normalizeSearchValue(document.getElementById('assignedSearchInput').value);

        if (!term) {
            return assignedData;
        }

        return assignedData.filter(item => {
            const haystack = [
                item.firstname,
                item.lastname,
                item.system_name,
                item.role,
                item.assigned_by_name,
                item.date_assigned,
            ].map(normalizeSearchValue).join(' ');

            return haystack.includes(term);
        });
    }

    function paginateRows(items, currentPage) {
        const totalPages = Math.max(1, Math.ceil(items.length / tablePageSize));
        const safePage = Math.min(Math.max(currentPage, 1), totalPages);
        const startIndex = (safePage - 1) * tablePageSize;

        return {
            totalPages,
            currentPage: safePage,
            startIndex,
            items: items.slice(startIndex, startIndex + tablePageSize)
        };
    }

    function renderPagination(containerId, totalItems, pageState, onPageChange) {
        const container = document.getElementById(containerId);

        if (!container) {
            return;
        }

        if (totalItems === 0) {
            container.innerHTML = '';
            return;
        }

        const pageItems = [];
        const totalPages = pageState.totalPages;
        const currentPage = pageState.currentPage;

        const pushPage = page => {
            pageItems.push(`
                <button type="button" class="table-page-btn ${page === currentPage ? 'active' : ''}" data-page="${page}" data-pagination-target="${onPageChange}">
                    ${page}
                </button>
            `);
        };

        const pushEllipsis = () => {
            pageItems.push('<span class="table-page-ellipsis">...</span>');
        };

        if (totalPages <= 4) {
            for (let page = 1; page <= totalPages; page += 1) {
                pushPage(page);
            }
        } else {
            const middlePages = [currentPage - 1, currentPage, currentPage + 1]
                .filter(page => page > 2 && page < totalPages);

            pushPage(1);

            if (currentPage > 3) {
                pushEllipsis();
            }

            const uniqueMiddlePages = [...new Set(middlePages)];
            uniqueMiddlePages.forEach(pushPage);

            if (currentPage < totalPages - 2) {
                pushEllipsis();
            }

            pushPage(totalPages);
        }

        const startLabel = totalItems === 0 ? 0 : pageState.startIndex + 1;
        const endLabel = Math.min(pageState.startIndex + tablePageSize, totalItems);

        container.innerHTML = `
            <div class="table-pagination-info">
                <strong>${startLabel}-${endLabel}</strong>
                <span class="table-pagination-meta">of ${totalItems} records</span>
            </div>
            <div class="table-pagination-controls">
                <button type="button" class="table-page-btn nav-btn" data-page="${pageState.currentPage - 1}" data-pagination-target="${onPageChange}" ${pageState.currentPage === 1 ? 'disabled' : ''}>
                    Prev
                </button>
                <div class="table-pagination-pages">
                    ${pageItems.join('')}
                </div>
                <button type="button" class="table-page-btn nav-btn" data-page="${pageState.currentPage + 1}" data-pagination-target="${onPageChange}" ${pageState.currentPage === pageState.totalPages ? 'disabled' : ''}>
                    Next
                </button>
            </div>
        `;
    }

    function syncSelectAllCheckbox() {
        const visibleCheckboxes = document.querySelectorAll('#userTableBody .user-checkbox');
        const checkedVisible = document.querySelectorAll('#userTableBody .user-checkbox:checked');
        const selectAll = document.getElementById('selectAllCheckbox');

        if (selectAll) {
            selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedVisible.length;
        }
    }

    function renderUsersTable() {
        const tbody = document.getElementById('userTableBody');
        const filteredUsers = getFilteredUsers();

        if (!filteredUsers.length) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>`;
            document.getElementById('userCount').textContent = '0';
            renderPagination('userPagination', 0, { currentPage: 1, totalPages: 1, startIndex: 0 }, 'user');
            syncSelectAllCheckbox();
            return;
        }

        const pageState = paginateRows(filteredUsers, userCurrentPage);
        userCurrentPage = pageState.currentPage;

        tbody.innerHTML = '';

        pageState.items.forEach((u, index) => {
            const fullName = `${u.firstname ?? ''} ${u.lastname ?? ''}`.trim();
            const rowNumber = pageState.startIndex + index + 1;
            const portalId = String(u.portal_id ?? '');

            tbody.innerHTML += `
                <tr data-row="user">
                    <td data-label="Select">
                        <input type="checkbox" class="form-check-input user-checkbox" value="${escapeAttr(portalId)}" ${selectedUserIds.has(portalId) ? 'checked' : ''}>
                    </td>
                    <td data-label="#">${rowNumber}</td>
                    <td data-label="Name">${escapeHtml(fullName)}</td>
                    <td data-label="Office">${escapeHtml(u.office_name ?? '-')}</td>
                    <td data-label="Division">${escapeHtml(u.division_name ?? '-')}</td>
                    <td data-label="Unit">${escapeHtml(u.unit_name ?? '-')}</td>
                    <td data-label="Designation">${escapeHtml(u.designation_name ?? '-')}</td>
                    <td data-label="Action">
                        <button class="btn btn-sm btn-outline-primary" onclick="openAssignModal('${escapeAttr(portalId)}')">
                            <i class="fa-solid fa-share-nodes me-1"></i>Assign
                        </button>
                    </td>
                </tr>`;
        });

        renderPagination('userPagination', filteredUsers.length, pageState, 'user');
        updateCounts();
        syncSelectAllCheckbox();
    }

    function renderAssignedTable() {
        const tbody = document.getElementById('assignedTableBody');
        const filteredAssigned = getFilteredAssigned();

        if (!filteredAssigned.length) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">No assigned systems found.</td></tr>`;
            document.getElementById('recentCount').textContent = '0';
            renderPagination('assignedPagination', 0, { currentPage: 1, totalPages: 1, startIndex: 0 }, 'assigned');
            return;
        }

        const pageState = paginateRows(filteredAssigned, assignedCurrentPage);
        assignedCurrentPage = pageState.currentPage;
        tbody.innerHTML = '';

        pageState.items.forEach((a, index) => {
            const dateStr = a.date_assigned ? String(a.date_assigned).split(' ')[0] : '-';
            const fullName = `${a.firstname ?? ''} ${a.lastname ?? ''}`.trim();
            const rowNumber = pageState.startIndex + index + 1;

            tbody.innerHTML += `
                <tr data-row="assigned">
                    <td data-label="#">${rowNumber}</td>
                    <td data-label="User">${escapeHtml(fullName || '-')}</td>
                    <td data-label="System">${escapeHtml(a.system_name ?? '-')}</td>
                    <td data-label="Role">
                        <span class="badge bg-light text-dark">${escapeHtml(formatRoleLabel(a.role ?? '-'))}</span>
                    </td>
                    <td data-label="Assigned By">${escapeHtml(a.assigned_by_name ?? '-')}</td>
                    <td data-label="Date">${escapeHtml(dateStr)}</td>
                    <td data-label="Action">
                        <button
                            class="btn btn-sm btn-outline-primary edit-assigned-role-btn"
                            data-access-id="${escapeAttr(a.access_id ?? '')}"
                            data-current-role="${escapeAttr(a.role ?? '')}"
                            data-user-name="${escapeAttr(fullName)}"
                            data-system-name="${escapeAttr(a.system_name ?? '')}">
                            <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                        </button>
                    </td>
                </tr>`;
        });

        renderPagination('assignedPagination', filteredAssigned.length, pageState, 'assigned');
        updateCounts();
    }

    async function getCsrfToken(forceRefresh = false) {
        if (!forceRefresh && csrfTokenCache) {
            return csrfTokenCache;
        }

        const res = await fetch('../api/auth/get_csrf.php', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const payload = await res.json();

        if (!res.ok || !payload.token) {
            throw new Error(payload.message || 'Unable to initialize request security.');
        }

        csrfTokenCache = payload.token;
        return csrfTokenCache;
    }

    async function csrfJsonFetch(url, payload) {
        const makeRequest = async token => fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        let response = await makeRequest(await getCsrfToken());

        if (response.status === 419) {
            csrfTokenCache = null;
            response = await makeRequest(await getCsrfToken(true));
        }

        return response;
    }

    async function loadRolesIntoSelect(selectId, selectedValue = '') {
        const select = document.getElementById(selectId);

        if (!select) {
            return;
        }

        select.innerHTML = '<option value="">Loading roles...</option>';

        try {
            const res = await fetch('../api/role/get_roles.php');
            const result = await res.json();
            const roles = Array.isArray(result?.data?.roles) ? result.data.roles : [];

            select.innerHTML = '<option value="">Select Role</option>';

            roles.forEach(role => {
                const option = document.createElement('option');
                option.value = role.role_name;
                option.textContent = formatRoleLabel(role.role_name);

                if (selectedValue && selectedValue === role.role_name) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        } catch (err) {
            console.error('Error loading roles:', err);
            select.innerHTML = '<option value="">Error loading roles</option>';
        }
    }

    async function loadAssignedSystems() {
        const tbody = document.getElementById('assignedTableBody');
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Loading...</td></tr>`;

        try {
            const res = await fetch('../api/assignment/get_assigned_systems.php');
            const assigned = await res.json();

            if (!Array.isArray(assigned) || !assigned.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">No assigned systems yet.</td></tr>`;
                document.getElementById('recentCount').textContent = '0';
                assignedData = [];
                renderPagination('assignedPagination', 0, { currentPage: 1, totalPages: 1, startIndex: 0 }, 'assigned');
                return;
            }

            assignedData = assigned;
            assignedCurrentPage = 1;
            renderAssignedTable();

        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">Error loading assigned systems.</td></tr>`;
            document.getElementById('recentCount').textContent = '0';
        }
    }

    document.getElementById('assignedTableBody').addEventListener('click', async function(e) {
        const btn = e.target.closest('.edit-assigned-role-btn');

        if (!btn) {
            return;
        }

        await openEditAssignedRoleModal(
            btn.dataset.accessId,
            btn.dataset.currentRole,
            btn.dataset.userName,
            btn.dataset.systemName
        );
    });

    document.getElementById('editAssignedRoleModal').addEventListener('hide.bs.modal', function() {
        if (this.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });

    async function openEditAssignedRoleModal(accessId, currentRole, userName, systemName) {
        if (!accessId) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Data',
                text: 'Unable to edit this assignment because access_id is missing from the API response.',
                confirmButtonColor: '#1e3a5f'
            });
            return;
        }

        document.getElementById('editAccessId').value = accessId;
        document.getElementById('editAssignedUser').textContent = userName || '-';
        document.getElementById('editAssignedSystem').textContent = systemName || '-';

        await loadRolesIntoSelect('editAssignedRoleSelect', currentRole);

        const modalEl = document.getElementById('editAssignedRoleModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    async function updateAssignedRole() {
        const accessId = document.getElementById('editAccessId').value;
        const role = document.getElementById('editAssignedRoleSelect').value;

        if (!accessId || !role) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a role.',
                confirmButtonColor: '#1e3a5f'
            });
            return;
        }

        try {
            const res = await csrfJsonFetch('../api/assignment/update_assigned_role_subadmin.php', {
                access_id: accessId,
                role: role
            });

            const result = await res.json();

            if (result.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: result.message,
                    confirmButtonColor: '#1e3a5f'
                });

                if (document.activeElement) {
                    document.activeElement.blur();
                }

                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('editAssignedRoleModal'));
                if (modalInstance) {
                    modalInstance.hide();
                }

                loadAssignedSystems();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: result.title || 'Error',
                    text: result.message || 'Unable to update role.',
                    confirmButtonColor: '#1e3a5f'
                });
            }
        } catch (err) {
            console.error(err);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update assigned role.',
                confirmButtonColor: '#1e3a5f'
            });
        }
    }

    document.getElementById('filterType').addEventListener('change', async (e) => {
        const type = e.target.value;
        const select = document.getElementById('filterValue');

        select.innerHTML = '<option value="">Loading...</option>';

        if (!type) {
            select.innerHTML = '<option value="">Select...</option>';
            return;
        }

        try {
            const res = await fetch(`../api/assignment/get_filter_values.php?type=${encodeURIComponent(type)}`);
            const data = await res.json();

            select.innerHTML = '<option value="">Select...</option>';

            data.forEach(d => {
                select.innerHTML += `<option value="${escapeAttr(d.id)}">${escapeHtml(d.name)}</option>`;
            });
        } catch (err) {
            console.error(err);
            select.innerHTML = '<option value="">Error loading data</option>';
        }
    });

    async function loadUsers() {
        const type = document.getElementById('filterType').value;
        const id = document.getElementById('filterValue').value;
        const tbody = document.getElementById('userTableBody');

        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4">Loading...</td></tr>`;

        if (!type || !id) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Please select a filter and value.</td></tr>`;
            document.getElementById('userCount').textContent = '0';
            usersData = [];
            selectedUserIds.clear();
            renderPagination('userPagination', 0, { currentPage: 1, totalPages: 1, startIndex: 0 }, 'user');
            return;
        }

        try {
            const res = await fetch(`../api/assignment/get_users.php?type=${encodeURIComponent(type)}&id=${encodeURIComponent(id)}`);
            const users = await res.json();

            if (!Array.isArray(users) || !users.length) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>`;
                document.getElementById('userCount').textContent = '0';
                usersData = [];
                selectedUserIds.clear();
                renderPagination('userPagination', 0, { currentPage: 1, totalPages: 1, startIndex: 0 }, 'user');
                return;
            }

            usersData = users;
            userCurrentPage = 1;
            selectedUserIds.clear();
            document.getElementById('selectAllCheckbox').checked = false;
            renderUsersTable();

        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Error loading users.</td></tr>`;
            document.getElementById('userCount').textContent = '0';
        }
    }

    async function openAssignModal(portalId) {
        document.getElementById('assignPortalId').value = portalId;
        document.getElementById('roleSelect').innerHTML = '<option value="">Loading roles...</option>';
        document.getElementById('systemSelect').innerHTML = '<option value="">Loading systems...</option>';

        try {
            const [res] = await Promise.all([
                fetch(`../api/assignment/get_systems_subadmin.php`),
                loadRolesIntoSelect('roleSelect')
            ]);

            const systems = await res.json();
            const select = document.getElementById('systemSelect');

            select.innerHTML = '<option value="">Select System</option>';

            systems.forEach(s => {
                select.innerHTML += `<option value="${escapeAttr(s.id)}">${escapeHtml(s.system_name)}</option>`;
            });

            const modalEl = document.getElementById('assignSystemModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

        } catch (err) {
            console.error('Error loading systems:', err);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not load systems.',
                confirmButtonColor: '#1e3a5f'
            });
        }
    }

    async function assignSystem() {
        const portalId = document.getElementById('assignPortalId').value;
        const systemId = document.getElementById('systemSelect').value;
        const role = document.getElementById('roleSelect').value;

        if (!systemId || !role) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a system and role.',
                confirmButtonColor: '#1e3a5f'
            });
            return;
        }

        try {
            const res = await csrfJsonFetch('../api/assignment/assign_system_subadmin.php', {
                portal_id: portalId,
                system_id: systemId,
                role: role
            });

            const result = await res.json();

            if (result.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: result.message,
                    confirmButtonColor: '#1e3a5f'
                });

                bootstrap.Modal.getInstance(document.getElementById('assignSystemModal')).hide();
                loadUsers();
                loadAssignedSystems();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: result.title || 'Error',
                    text: result.message,
                    confirmButtonColor: '#1e3a5f'
                });
            }

        } catch (err) {
            console.error(err);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to assign system.',
                confirmButtonColor: '#1e3a5f'
            });
        }
    }

    document.getElementById('selectAllCheckbox').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('#userTableBody .user-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = e.target.checked;

            if (e.target.checked) {
                selectedUserIds.add(cb.value);
            } else {
                selectedUserIds.delete(cb.value);
            }
        });
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            if (e.target.checked) {
                selectedUserIds.add(e.target.value);
            } else {
                selectedUserIds.delete(e.target.value);
            }

            syncSelectAllCheckbox();
        }
    });

    async function loadSystemsIntoSelect(selectId) {
        try {
            const res = await fetch(`../api/assignment/get_systems_subadmin.php`);
            const systems = await res.json();
            const select = document.getElementById(selectId);

            select.innerHTML = '<option value="">Select System</option>';

            systems.forEach(sys => {
                select.innerHTML += `<option value="${escapeAttr(sys.id || sys.system_id)}">${escapeHtml(sys.system_name)}</option>`;
            });

        } catch (err) {
            console.error('Error loading systems:', err);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not load systems.',
                confirmButtonColor: '#1e3a5f'
            });
        }
    }

    async function openBulkAssignModal() {
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');

        if (checkboxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one user.',
                confirmButtonColor: '#1e3a5f'
            });
            return;
        }

        document.getElementById('selectedCount').textContent = checkboxes.length;

        await Promise.all([
            loadSystemsIntoSelect('bulkSystemSelect'),
            loadRolesIntoSelect('bulkRoleSelect')
        ]);

        const modalEl = document.getElementById('bulkAssignSystemModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    async function bulkAssignSystem() {
        const systemId = document.getElementById('bulkSystemSelect').value;
        const role = document.getElementById('bulkRoleSelect').value;

        if (!systemId || !role) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a system and role.',
                confirmButtonColor: '#1e3a5f'
            });
            return;
        }

        const selectedPortalIds = Array.from(selectedUserIds);

        try {
            const res = await csrfJsonFetch('../api/assignment/bulk_assign_system_subadmin.php', {
                portal_ids: selectedPortalIds,
                system_id: systemId,
                role: role
            });

            const result = await res.json();

            if (result.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: result.message,
                    confirmButtonColor: '#1e3a5f'
                });

                bootstrap.Modal.getInstance(document.getElementById('bulkAssignSystemModal')).hide();
                loadUsers();
                loadAssignedSystems();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: result.title || 'Error',
                    text: result.message,
                    confirmButtonColor: '#1e3a5f'
                });
            }

        } catch (err) {
            console.error(err);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to assign system.',
                confirmButtonColor: '#1e3a5f'
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('userSearchInput').addEventListener('input', function() {
            userCurrentPage = 1;
            renderUsersTable();
        });

        document.getElementById('assignedSearchInput').addEventListener('input', function() {
            assignedCurrentPage = 1;
            renderAssignedTable();
        });

        document.addEventListener('click', function(e) {
            const button = e.target.closest('.table-page-btn');

            if (!button || button.disabled) {
                return;
            }

            const target = button.dataset.paginationTarget;
            const page = Number(button.dataset.page || '1');

            if (target === 'user') {
                userCurrentPage = page;
                renderUsersTable();
                return;
            }

            if (target === 'assigned') {
                assignedCurrentPage = page;
                renderAssignedTable();
            }
        });

        loadAssignedSystems();
    });
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

