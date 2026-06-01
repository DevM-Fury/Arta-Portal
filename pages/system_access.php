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

                    <!-- Filter Section - Mobile Optimized -->
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

                    <!-- TWO COLUMN LAYOUT - Mobile Responsive -->
                    <div class="row g-3 g-md-4">
                        <!-- LEFT COLUMN - USERS LIST -->
                        <div class="col-12 col-lg-6">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <h6 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> Users List</h6>
                                <span class="badge bg-secondary ms-2" id="userCount">0</span>
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
                            </div>
                        </div>

                        <!-- RIGHT COLUMN - RECENTLY ASSIGNED -->
                        <div class="col-12 col-lg-6">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <h6 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-success"></i> Recently Assigned</h6>
                                <span class="badge bg-secondary ms-2" id="recentCount">0</span>
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
                                            </tr>
                                        </thead>
                                        <tbody id="assignedTableBody">
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer text-center py-2 py-md-3">
        <small>ARTA Government System v2.1 | Secure Access Portal</small>
    </div>

    <!-- Assign Modal -->
    <div class="modal fade" id="assignSystemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title"><i class="fa-solid fa-share-nodes me-2"></i> Assign System</h6>
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

    <!-- Bulk Assign Modal -->
    <div class="modal fade" id="bulkAssignSystemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success text-white py-2">
                    <h6 class="modal-title"><i class="fa-solid fa-layer-group me-2"></i> Bulk Assign</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        <i class="fa-solid fa-users me-2"></i> Selected: <span id="selectedCount" class="fw-bold">0</span> user(s)
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

    <!-- Scripts -->
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

    /* Filter Section */
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

    /* Table Container */
    .table-container {
        border: 1px solid var(--arta-border);
        border-radius: 10px;
        overflow: hidden;
        background: white;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        font-size: 13px;
        margin-bottom: 0;
        min-width: 1000px; /* Para sa desktop */
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
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .table tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    /* Badge */
    .badge {
        font-weight: 500;
        padding: 4px 8px;
        font-size: 10px;
    }

    /* Checkbox */
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

    /* Footer */
    .footer {
        background: white;
        border-top: 1px solid var(--arta-border);
        color: #64748b;
        font-size: 11px;
    }

    /* MOBILE RESPONSIVE - Card View para sa Tables */
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

        /* Mobile Card View para sa Tables */
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

        /* Data labels for Users Table */
        #usersTable tbody td:nth-child(1)::before { content: "Select"; }
        #usersTable tbody td:nth-child(2)::before { content: "#"; }
        #usersTable tbody td:nth-child(3)::before { content: "Name"; }
        #usersTable tbody td:nth-child(4)::before { content: "Office"; }
        #usersTable tbody td:nth-child(5)::before { content: "Division"; }
        #usersTable tbody td:nth-child(6)::before { content: "Unit"; }
        #usersTable tbody td:nth-child(7)::before { content: "Designation"; }
        #usersTable tbody td:nth-child(8)::before { content: "Action"; }

        /* Data labels for Assigned Table */
        #assignedTable tbody td:nth-child(1)::before { content: "#"; }
        #assignedTable tbody td:nth-child(2)::before { content: "User"; }
        #assignedTable tbody td:nth-child(3)::before { content: "System"; }
        #assignedTable tbody td:nth-child(4)::before { content: "Role"; }
        #assignedTable tbody td:nth-child(5)::before { content: "Assigned By"; }
        #assignedTable tbody td:nth-child(6)::before { content: "Date"; }

        /* Action button sa mobile */
        .btn-outline-primary {
            width: auto;
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Columns stack sa mobile */
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

        /* Modal sa mobile */
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
    }

    /* Small phones */
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

    /* Landscape mode */
    @media (max-width: 992px) and (orientation: landscape) {
        .table-container {
            max-height: 300px;
            overflow-y: auto;
        }
    }
    </style>

    <script>
    // Update counts
    function updateCounts() {
        const userRows = document.querySelectorAll('#userTableBody tr:not([colspan])');
        const recentRows = document.querySelectorAll('#assignedTableBody tr:not([colspan])');
        document.getElementById('userCount').textContent = userRows.length;
        document.getElementById('recentCount').textContent = recentRows.length;
    }

    function formatRoleLabel(roleName) {
        return String(roleName || '')
            .split('_')
            .filter(Boolean)
            .map(part => part.charAt(0).toUpperCase() + part.slice(1))
            .join(' ');
    }

    let csrfTokenCache = null;

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
                'X-CSRF-Token': token
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
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">Loading...</td></tr>`;

        try {
            const res = await fetch('../api/assignment/get_assigned_systems.php');
            const assigned = await res.json();

            if (!assigned.length) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">No assigned systems yet.</td></tr>`;
                document.getElementById('recentCount').textContent = '0';
                return;
            }

            tbody.innerHTML = '';
            assigned.slice(0, 15).forEach((a, i) => {
                const dateStr = a.date_assigned ? a.date_assigned.split(' ')[0] : '-';
                tbody.innerHTML += `
                <tr>
                    <td data-label="#">${i+1}</td>
                    <td data-label="User">${a.firstname} ${a.lastname}</td>
                    <td data-label="System">${a.system_name}</td>
                    <td data-label="Role"><span class="badge bg-light text-dark">${a.role}</span></td>
                    <td data-label="Assigned By">${a.assigned_by_name ?? '-'}</td>
                    <td data-label="Date">${dateStr}</td>
                </tr>`;
            });
            updateCounts();

        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Error loading assigned systems.</td></tr>`;
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
            const res = await fetch(`../api/assignment/get_filter_values.php?type=${type}`);
            const data = await res.json();
            select.innerHTML = '<option value="">Select...</option>';
            data.forEach(d => select.innerHTML += `<option value="${d.id}">${d.name}</option>`);
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
            return;
        }

        try {
            const res = await fetch(`../api/assignment/get_users.php?type=${type}&id=${id}`);
            const users = await res.json();

            if (!users.length) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>`;
                document.getElementById('userCount').textContent = '0';
                return;
            }

            tbody.innerHTML = '';
            users.forEach((u, i) => {
                tbody.innerHTML += `
                    <tr>
                        <td data-label="Select"><input type="checkbox" class="form-check-input user-checkbox" value="${u.portal_id}"></td>
                        <td data-label="#">${i + 1}</td>
                        <td data-label="Name">${u.firstname} ${u.lastname}</td>
                        <td data-label="Office">${u.office_name ?? '-'}</td>
                        <td data-label="Division">${u.division_name ?? '-'}</td>
                        <td data-label="Unit">${u.unit_name ?? '-'}</td>
                        <td data-label="Designation">${u.designation_name ?? '-'}</td>
                        <td data-label="Action">
                            <button class="btn btn-sm btn-outline-primary" onclick="openAssignModal('${u.portal_id}')">
                                <i class="fa-solid fa-share-nodes me-1"></i>Assign
                            </button>
                        </td>
                    </tr>`;
            });
            document.getElementById('selectAllCheckbox').checked = false;
            updateCounts();
        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Error loading users.</td></tr>`;
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
            systems.forEach(s => select.innerHTML += `<option value="${s.id}">${s.system_name}</option>`);

            const modalEl = document.getElementById('assignSystemModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } catch (err) {
            console.error('Error loading systems:', err);
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
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            const checked = document.querySelectorAll('.user-checkbox:checked');
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) {
                selectAll.checked = allCheckboxes.length === checked.length && allCheckboxes.length > 0;
            }
        }
    });

    async function loadSystemsIntoSelect(selectId) {
        try {
            const res = await fetch(`../api/assignment/get_systems_subadmin.php`);
            const systems = await res.json();
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Select System</option>';
            systems.forEach(sys => {
                select.innerHTML += `<option value="${sys.id || sys.system_id}">${sys.system_name}</option>`;
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

        const selectedPortalIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);

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
        loadAssignedSystems();
    });
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>