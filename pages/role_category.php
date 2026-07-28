<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>
    <?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-user-shield me-2"></i> Role Management
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="card shadow-lg border-0 rounded-4 mx-auto role-card">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                        <div>
                            <h3 class="mb-1 fw-bold">Manage Access Roles</h3>
                            <p class="text-muted mb-0">Roles added here automatically appear in the System Access assignment dropdowns.</p>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-2 w-100 w-lg-auto">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search role name">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                <i class="fa-solid fa-plus me-2"></i>Add Role
                            </button>
                        </div>
                    </div>

                    <div class="stats-strip mb-4">
                        <div class="stats-item">
                            <span class="stats-label">Total Roles</span>
                            <strong id="roleCount">0</strong>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Role ID</th>
                                    <th>Role Name</th>
                                    <th>Role Level</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="roleTableBody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Loading roles...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addRoleForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role Name</label>
                            <input type="text" id="roleName" name="role_name" class="form-control" placeholder="Example: reviewer" required>
                            <small class="text-muted">Spaces are converted to underscores when saved.</small>
                        </div>
                        <div>
                            <label for="roleLevel" class="form-label">Role Level</label>
                            <input type="number" id="roleLevel" name="role_level" class="form-control" min="1" value="10" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../includes/scripts.php'; ?>

    <script>
    let allRoles = [];

    function formatRoleLabel(roleName) {
        return String(roleName || '')
            .split('_')
            .filter(Boolean)
            .map(part => part.charAt(0).toUpperCase() + part.slice(1))
            .join(' ');
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
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

    function renderRoles(filter = '') {
        const tableBody = document.getElementById('roleTableBody');
        const normalizedFilter = filter.trim().toLowerCase();
        const filteredRoles = allRoles.filter(role => {
            const text = `${role.role_name} ${role.role_level}`.toLowerCase();
            return text.includes(normalizedFilter);
        });

        document.getElementById('roleCount').textContent = filteredRoles.length;

        if (!filteredRoles.length) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">No roles found.</td></tr>';
            return;
        }

        tableBody.innerHTML = filteredRoles.map(role => `
            <tr>
                <td>ROLE-${String(role.role_id).padStart(3, '0')}</td>
                <td>${escapeHtml(formatRoleLabel(role.role_name))}</td>
                <td>${escapeHtml(role.role_level)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRole(${role.role_id})">
                        <i class="fa-solid fa-trash me-1"></i>Delete
                    </button>
                </td>
            </tr>
        `).join('');
    }

    async function loadRoles() {
        const tableBody = document.getElementById('roleTableBody');
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Loading roles...</td></tr>';

        try {
            const res = await fetch('../api/role/get_roles.php');
            const result = await res.json();
            allRoles = Array.isArray(result?.data?.roles) ? result.data.roles : [];
            renderRoles(document.getElementById('searchInput').value);
        } catch (error) {
            console.error('Error loading roles:', error);
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Failed to load roles.</td></tr>';
        }
    }

    async function deleteRole(roleId) {
        const result = await Swal.fire({
            title: 'Delete role?',
            text: 'This role will be removed if it is not assigned anywhere.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            const res = await csrfJsonFetch('../api/role/delete_role.php', { role_id: roleId });
            const payload = await res.json();

            if (payload.status !== 200) {
                throw new Error(payload.message || 'Failed to delete role');
            }

            await Swal.fire({ icon: 'success', title: 'Deleted', text: payload.message, timer: 1600, showConfirmButton: false });
            loadRoles();
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Delete failed', text: error.message || 'Request failed.' });
        }
    }

    document.getElementById('addRoleForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        try {
            const roleName = document.getElementById('roleName').value.trim();
            const roleLevel = Number(document.getElementById('roleLevel').value);
            const res = await csrfJsonFetch('../api/role/create_role.php', {
                role_name: roleName,
                role_level: roleLevel
            });
            const payload = await res.json();

            if (payload.status !== 200) {
                throw new Error(payload.message || 'Failed to create role');
            }

            await Swal.fire({ icon: 'success', title: 'Saved', text: payload.message, timer: 1600, showConfirmButton: false });
            bootstrap.Modal.getInstance(document.getElementById('addRoleModal')).hide();
            this.reset();
            document.getElementById('roleLevel').value = 10;
            loadRoles();
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Save failed', text: error.message || 'Request failed.' });
        } finally {
            submitButton.disabled = false;
        }
    });

    document.getElementById('searchInput').addEventListener('input', function() {
        renderRoles(this.value);
    });

    loadRoles();
    </script>

    <style>
    .role-card {
        width: 100%;
        max-width: 1400px;
        min-height: 78vh;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 248, 225, 0.96));
    }

    .stats-strip {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1rem;
        border-radius: 14px;
        background: rgba(35, 57, 93, 0.08);
    }

    .stats-label {
        display: block;
        font-size: 0.85rem;
        color: #5f6470;
    }

    .stats-item strong {
        font-size: 1.5rem;
        color: #23395d;
    }

    .table thead th {
        background: #0f2027;
        color: #fff;
        border: 0;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-size: 0.82rem;
    }

    .table tbody tr:nth-child(even) {
        background: rgba(255, 248, 225, 0.5);
    }

    .table tbody td {
        padding: 1rem 0.9rem;
    }

    #searchInput {
        min-width: 250px;
    }

    @media (max-width: 768px) {
        #searchInput {
            min-width: 100%;
        }
    }
    </style>
</body>
</html>