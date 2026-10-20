
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ARTA PORTAL - System Assignment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --arta-red: #8B0000;
            --arta-light: #fff8e1;
            --arta-gray: #f7f7f7;
            --arta-blue1: #1877d2;
            --arta-blue2: #10345f;
            --arta-blue3: #0f2740;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(24, 119, 210, 0.16), transparent 28%),
                radial-gradient(circle at bottom right, rgba(66, 183, 42, 0.12), transparent 24%),
                linear-gradient(180deg, #f4f8fc 0%, #eef4fb 52%, #f9fbff 100%);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #16304a;
        }

        .header-bar {
            background: linear-gradient(135deg, rgba(5, 19, 43, 0.96), rgba(10, 46, 96, 0.94));
            color: #fff;
            padding: clamp(0.4rem, 1vw, 0.8rem) clamp(0.8rem, 2vw, 2rem);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
            z-index: 100;
            box-shadow: 0 18px 40px rgba(5, 19, 43, 0.24);
            border: 1px solid rgba(255,255,255,0.14);
            backdrop-filter: blur(16px);
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

        .header-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: clamp(0.85rem, 1.8vw, 1.2rem);
            padding: clamp(5px, 1vw, 12px) clamp(10px, 2vw, 24px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: clamp(6px, 1vw, 10px);
            flex-wrap: nowrap;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: none;
            backdrop-filter: blur(12px);
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

        @media (max-width: 991px) {
            .header-user span,
            .header-user a {
                display: none;
            }
        }

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

        .main-content {
            margin: 0 auto;
            padding-top: clamp(5rem, 10vw, 7rem);
            padding-left: clamp(0.8rem, 3vw, 2rem);
            padding-right: clamp(0.8rem, 3vw, 2rem);
            padding-bottom: 5rem;
        }

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
                font-size: 0.75rem;
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
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 220px;
            width: calc(100% - 220px);
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 -10px 30px rgba(15, 23, 42, 0.06);
            font-size: 0.9rem;
            z-index: 1000;
            padding: 0.75rem 1rem;
            text-align: center;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(16px);
        }

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

        .table-container {
            background: rgba(255,255,255,0.9);
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 24px 56px rgba(15, 23, 42, 0.1);
            margin-top: 1rem;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(16px);
        }

        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -0.5rem;
            padding: 0 0.5rem;
            width: calc(100% + 1rem);
            border-radius: 12px;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 4px;
            width: 100%;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--arta-blue2) 0%, var(--arta-blue1) 100%);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.9rem 0.5rem;
            white-space: nowrap;
            border-bottom: none;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            box-shadow: 0 10px 20px rgba(24,119,210,0.12);
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

        .action-group {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .role-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
            box-shadow: 0 2px 4px rgba(25, 118, 210, 0.2);
            display: inline-block;
            text-transform: capitalize;
        }

        .text-cell {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
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

        .table-info {
            font-size: 0.85rem;
            color: #6c757d;
            padding: 0.35rem 0;
            text-shadow: 0 1px 1px rgba(255,255,255,0.5);
        }

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

        .modal-body .col-6.label-col {
            font-weight: 600;
            color: var(--arta-blue1);
            border-right: 1px solid rgba(0,0,0,0.05);
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
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

    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-key"></i>
            <span>System Assignment</span>
        </div>

        <div class="header-user">
            <i class="fa-regular fa-user"></i>
            <span>Admin User</span>
            <a href="logout.php" class="text-white text-decoration-none">
                <i class="fa-solid fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="table-info">
                    <strong>My Assigned Users</strong>
                    <span class="text-muted"> — list from system_access.php assigned records.</span>
                </div>

                <div class="search-wrapper">
                    <input type="text" id="searchBox" class="form-control" placeholder="Search...">
                    <i class="fa-solid fa-search"></i>
                </div>
            </div>

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

            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <div class="table-info" id="tableInfo"></div>
                <div class="pagination-custom" id="pagination"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-user-circle me-2"></i>User Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6 label-col">Full Name:</div>
                        <div class="col-6" id="modalFullName"></div>

                        <div class="col-6 label-col">Office:</div>
                        <div class="col-6" id="modalOffice"></div>

                        <div class="col-6 label-col">Division:</div>
                        <div class="col-6" id="modalDivision"></div>

                        <div class="col-6 label-col">Unit:</div>
                        <div class="col-6" id="modalUnit"></div>

                        <div class="col-6 label-col">Designation:</div>
                        <div class="col-6" id="modalDesignation"></div>

                        <div class="col-6 label-col">System:</div>
                        <div class="col-6" id="modalSystem"></div>

                        <div class="col-6 label-col">Role:</div>
                        <div class="col-6" id="modalRole"></div>

                        <div class="col-6 label-col">Assigned By:</div>
                        <div class="col-6" id="modalAssignedBy"></div>

                        <div class="col-6 label-col">Date Assigned:</div>
                        <div class="col-6" id="modalDateAssigned"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-pen me-2"></i>Edit Assigned Role
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editAccessId">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" class="form-control" id="editFullName" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">System</label>
                            <input type="text" class="form-control" id="editSystemName" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Role</label>
                            <select class="form-select" id="editRole">
                                <option value="">Select Role</option>
                            </select>
                        </div>

                        <div class="alert alert-info small mb-0">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            This updates the assigned role using access_id from your assigned records.
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-dark" id="saveEditBtn">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let assignedUsersData = [];
        let allAssignedUsersData = [];
        let currentPage = 1;
        let csrfTokenCache = null;

        const rowsPerPage = 10;

        document.addEventListener('DOMContentLoaded', function() {
            loadAssignedUsers();
        });

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function formatRoleLabel(roleName) {
            return String(roleName || '')
                .split('_')
                .filter(Boolean)
                .map(part => part.charAt(0).toUpperCase() + part.slice(1))
                .join(' ');
        }

        function normalizeAssignedRow(row) {
            const fullName = `${row.firstname ?? ''} ${row.lastname ?? ''}`.trim();

            return {
                access_id: row.access_id ?? '',
                portal_id: row.portal_id ?? '',
                system_id: row.system_id ?? '',
                full_name: fullName || row.full_name || '-',
                system_name: row.system_name || '-',
                role: row.role || 'user',
                office_name: row.office_name || '-',
                division_name: row.division_name || '-',
                unit_name: row.unit_name || '-',
                designation_name: row.designation_name || '-',
                assigned_by_name: row.assigned_by_name || '-',
                date_assigned: row.date_assigned || '-'
            };
        }

        async function getCsrfToken(forceRefresh = false) {
            if (!forceRefresh && csrfTokenCache) {
                return csrfTokenCache;
            }

            const res = await fetch('../api/auth/get_csrf.php', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
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
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });

            let response = await makeRequest(await getCsrfToken());

            if (response.status === 419) {
                csrfTokenCache = null;
                response = await makeRequest(await getCsrfToken(true));
            }

            return response;
        }

        async function loadAssignedUsers() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4">Loading...</td></tr>`;

            try {
                const res = await fetch('../api/assignment/get_assigned_systems.php', {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const rawText = await res.text();
                let data;

                try {
                    data = JSON.parse(rawText);
                } catch (e) {
                    throw new Error(rawText || 'Invalid response from get_assigned_systems.php');
                }

                if (!res.ok) {
                    throw new Error(data.message || data.error || 'Unable to load assigned users.');
                }

                if (!Array.isArray(data) || data.length === 0) {
                    assignedUsersData = [];
                    allAssignedUsersData = [];
                    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4">No assigned users found.</td></tr>`;
                    document.getElementById('tableInfo').innerHTML = '';
                    document.getElementById('pagination').innerHTML = '';
                    return;
                }

                allAssignedUsersData = data.map(normalizeAssignedRow);
                assignedUsersData = [...allAssignedUsersData];

                displayPage(1);

            } catch (err) {
                console.error(err);

                tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger py-4">${escapeHtml(err.message || 'Error loading assigned users.')}</td></tr>`;
                document.getElementById('tableInfo').innerHTML = '';
                document.getElementById('pagination').innerHTML = '';
            }
        }

        async function loadRolesIntoSelect(selectedValue = '') {
            const select = document.getElementById('editRole');
            select.innerHTML = '<option value="">Loading roles...</option>';

            try {
                const res = await fetch('../api/role/get_roles.php', {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await res.json();
                const roles = Array.isArray(result?.data?.roles) ? result.data.roles : [];

                select.innerHTML = '<option value="">Select Role</option>';

                if (roles.length === 0) {
                    const fallbackRoles = ['user', 'admin', 'subadmin', 'supervisor'];

                    fallbackRoles.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role;
                        option.textContent = formatRoleLabel(role);

                        if (selectedValue && selectedValue === role) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    });

                    return;
                }

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

                const fallbackRoles = ['user', 'admin', 'subadmin', 'supervisor'];
                select.innerHTML = '<option value="">Select Role</option>';

                fallbackRoles.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role;
                    option.textContent = formatRoleLabel(role);

                    if (selectedValue && selectedValue === role) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });
            }
        }

        function displayPage(page) {
            currentPage = page;

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
                        <td>
                            <span class="text-cell" title="${escapeHtml(row.full_name || '')}">
                                ${escapeHtml(row.full_name || '-')}
                            </span>
                        </td>
                        <td>
                            <span class="text-cell" title="${escapeHtml(row.system_name || '')}">
                                ${escapeHtml(row.system_name || '-')}
                            </span>
                        </td>
                        <td>
                            <span class="role-badge">${escapeHtml(formatRoleLabel(row.role || 'user'))}</span>
                        </td>
                        <td>
                            <span class="text-cell" title="${escapeHtml(row.office_name || '')}">
                                ${escapeHtml(row.office_name || '-')}
                            </span>
                        </td>
                        <td>
                            <span class="text-cell" title="${escapeHtml(row.division_name || '')}">
                                ${escapeHtml(row.division_name || '-')}
                            </span>
                        </td>
                        <td>
                            <span class="text-cell" title="${escapeHtml(row.unit_name || '')}">
                                ${escapeHtml(row.unit_name || '-')}
                            </span>
                        </td>
                        <td>${escapeHtml(String(row.date_assigned || '-').split(' ')[0])}</td>
                        <td>
                            <div class="action-group">
                                <button class="btn btn-outline-primary btn-action viewBtn"
                                    data-access-id="${escapeHtml(row.access_id)}"
                                    title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <button class="btn btn-outline-warning btn-action editBtn"
                                    data-access-id="${escapeHtml(row.access_id)}"
                                    title="Edit Role">
                                    <i class="fa-solid fa-pen"></i>
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

            if (totalPages <= 1) {
                return;
            }

            if (currentPage > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'btn btn-outline-primary btn-sm';
                prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
                prevBtn.onclick = () => displayPage(currentPage - 1);
                paginationDiv.appendChild(prevBtn);
            }

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const btn = document.createElement('button');
                    btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
                    btn.innerText = i;
                    btn.onclick = () => displayPage(i);
                    paginationDiv.appendChild(btn);
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    const span = document.createElement('span');
                    span.className = 'btn btn-sm btn-outline-secondary disabled';
                    span.innerText = '...';
                    paginationDiv.appendChild(span);
                }
            }

            if (currentPage < totalPages) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'btn btn-outline-primary btn-sm';
                nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
                nextBtn.onclick = () => displayPage(currentPage + 1);
                paginationDiv.appendChild(nextBtn);
            }
        }

        let searchTimeout;
        document.getElementById('searchBox').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                const term = e.target.value.toLowerCase().trim();

                if (!term) {
                    assignedUsersData = [...allAssignedUsersData];
                    displayPage(1);
                    return;
                }

                assignedUsersData = allAssignedUsersData.filter(user =>
                    String(user.full_name || '').toLowerCase().includes(term) ||
                    String(user.system_name || '').toLowerCase().includes(term) ||
                    String(user.office_name || '').toLowerCase().includes(term) ||
                    String(user.division_name || '').toLowerCase().includes(term) ||
                    String(user.unit_name || '').toLowerCase().includes(term) ||
                    String(user.role || '').toLowerCase().includes(term) ||
                    String(user.designation_name || '').toLowerCase().includes(term) ||
                    String(user.assigned_by_name || '').toLowerCase().includes(term)
                );

                displayPage(1);

                if (assignedUsersData.length === 0) {
                    document.getElementById('tableBody').innerHTML =
                        `<tr><td colspan="9" class="text-center py-4">No results found for "${escapeHtml(term)}"</td></tr>`;
                    document.getElementById('tableInfo').innerHTML = '';
                    document.getElementById('pagination').innerHTML = '';
                }
            }, 300);
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('button');
            if (!btn) {
                return;
            }

            const accessId = btn.dataset.accessId;

            if (btn.classList.contains('viewBtn')) {
                viewUser(accessId);
            } else if (btn.classList.contains('editBtn')) {
                editUser(accessId);
            }
        });

        function findAssignedUser(accessId) {
            return allAssignedUsersData.find(u => String(u.access_id) === String(accessId));
        }

        function viewUser(accessId) {
            const user = findAssignedUser(accessId);

            if (!user) {
                Swal.fire('Not Found', 'Selected assignment was not found.', 'error');
                return;
            }

            document.getElementById('modalFullName').innerText = user.full_name || '-';
            document.getElementById('modalOffice').innerText = user.office_name || '-';
            document.getElementById('modalDivision').innerText = user.division_name || '-';
            document.getElementById('modalUnit').innerText = user.unit_name || '-';
            document.getElementById('modalDesignation').innerText = user.designation_name || '-';
            document.getElementById('modalSystem').innerText = user.system_name || '-';
            document.getElementById('modalRole').innerText = formatRoleLabel(user.role || '-');
            document.getElementById('modalAssignedBy').innerText = user.assigned_by_name || '-';
            document.getElementById('modalDateAssigned').innerText = user.date_assigned || '-';

            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        }

        async function editUser(accessId) {
            const user = findAssignedUser(accessId);

            if (!user) {
                Swal.fire('Not Found', 'Selected assignment was not found.', 'error');
                return;
            }

            document.getElementById('editAccessId').value = user.access_id || '';
            document.getElementById('editFullName').value = user.full_name || '';
            document.getElementById('editSystemName').value = user.system_name || '';

            await loadRolesIntoSelect(user.role || '');

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        document.getElementById('saveEditBtn').addEventListener('click', async function() {
            const accessId = document.getElementById('editAccessId').value;
            const role = document.getElementById('editRole').value;

            if (!accessId) {
                Swal.fire('Error', 'Missing assignment access ID.', 'error');
                return;
            }

            if (!role) {
                Swal.fire('Warning', 'Please select a role.', 'warning');
                return;
            }

            try {
                const res = await csrfJsonFetch('../api/assignment/update_assigned_role_subadmin.php', {
                    access_id: accessId,
                    role: role
                });

                const rawText = await res.text();
                let result;

                try {
                    result = JSON.parse(rawText);
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Response Error',
                        html: `<pre style="text-align:left;white-space:pre-wrap;">${escapeHtml(rawText || 'Empty response from server.')}</pre>`,
                        width: 700
                    });
                    return;
                }

                if (result.status === 200) {
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                    if (modal) {
                        modal.hide();
                    }

                    Swal.fire({
                        title: 'Updated!',
                        text: result.message || 'Assigned role updated successfully.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    await loadAssignedUsers();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.title || 'Error',
                        text: result.message || 'Unable to update assigned role.'
                    });
                }

            } catch (err) {
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: err.message || 'Failed to update assigned role.'
                });
            }
        });

        document.getElementById('viewUserModal').addEventListener('hide.bs.modal', function() {
            if (this.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });

        document.getElementById('editUserModal').addEventListener('hide.bs.modal', function() {
            if (this.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });
    </script>
</body>
</html>

