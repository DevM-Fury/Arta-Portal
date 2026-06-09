<?php
require_once __DIR__ . '/../includes/admin_guard.php';
require_once __DIR__ . '/../includes/configs.php';
require_once __DIR__ . '/../includes/log_helper.php';

global $conn;

ensure_transaction_logs_table($conn);

$sql = "
    SELECT
        t.portal_id,
        t.actor_role,
        t.action,
        t.entity_type,
        t.entity_id,
        t.description,
        t.context,
        t.created_at,
        TRIM(COALESCE(u.firstname, '') || ' ' || COALESCE(u.lastname, '')) AS actor_name
    FROM transaction_logs t
    LEFT JOIN user_tbl u ON CAST(u.portal_id AS TEXT) = CAST(t.portal_id AS TEXT)
    ORDER BY t.created_at DESC
    LIMIT 200
";

$stmt = $conn->query($sql);
$transactionLogs = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--arta-header-text);
    }

    .header-title i,
    .header-user i {
        color: var(--arta-header-text);
    }

    .header-user i {
        font-size: 1.2rem;
        cursor: pointer;
    }

    .sidebar {
        background: var(--arta-blue2);
    }

    .main-content {
        margin-left: 250px;
        margin-top: 70px;
        min-height: calc(100vh - 70px);
        padding: 25px;
    }

    .content-container {
        max-width: 1600px;
        margin: 0 auto;
    }

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
        margin-bottom: 0;
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
        vertical-align: top;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding: 0;
    }

    .records-info {
        color: #6c757d;
        font-size: 14px;
    }

    .log-empty {
        text-align: center;
        color: #6c757d;
        padding: 30px 15px;
    }

    .log-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        background: #dbeafe;
        color: #1d4ed8;
    }

    .log-context {
        white-space: pre-wrap;
        word-break: break-word;
        margin: 0;
        font-size: 12px;
        font-family: inherit;
        color: #495057;
    }

    .log-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .log-subtitle {
        margin: 4px 0 0;
        color: #6c757d;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .header-bar {
            left: 0;
            padding: 0 15px;
        }

        .main-content {
            margin-left: 0;
            padding: 15px;
        }

        .search-section {
            flex-direction: column;
            align-items: stretch;
        }

        .table-footer {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<body>
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-file-invoice-dollar me-2"></i> Transaction Logs
        </div>

        <div class="header-user dropdown">
            <i class="fa-solid fa-user dropdown-toggle" id="transactionLogsUserDropdown" data-bs-toggle="dropdown"
                aria-expanded="false"></i>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionLogsUserDropdown">
                <li><a class="dropdown-item" href="profile_admin.php">Profile Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="content-container">
            <div class="search-section">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search transaction logs...">
                    <span class="search-count" id="recordCount"><?= count($transactionLogs) ?></span>
                </div>
            </div>

            <div class="table-view">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-white bg-dark">DATE/TIME</th>
                                <th class="text-white bg-dark">PORTAL ID</th>
                                <th class="text-white bg-dark">USER</th>
                                <th class="text-white bg-dark">ROLE</th>
                                <th class="text-white bg-dark">ACTION</th>
                                <th class="text-white bg-dark">ENTITY</th>
                                <th class="text-white bg-dark">DESCRIPTION</th>
                                <th class="text-white bg-dark">CONTEXT</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if (!$transactionLogs): ?>
                                <tr>
                                    <td colspan="8" class="log-empty">No transaction log records found.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($transactionLogs as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($log['created_at'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars((string) ($log['portal_id'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars(trim((string) ($log['actor_name'] ?? '')) ?: 'Unknown user') ?></td>
                                    <td><?= htmlspecialchars((string) ($log['actor_role'] ?? '')) ?></td>
                                    <td><span class="log-badge"><?= htmlspecialchars((string) ($log['action'] ?? '')) ?></span></td>
                                    <td><?= htmlspecialchars((string) (($log['entity_type'] ?? '') . ' #' . ($log['entity_id'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars((string) ($log['description'] ?? '')) ?></td>
                                    <td><pre class="log-context"><?= htmlspecialchars((string) ($log['context'] ?? '{}')) ?></pre></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-footer">
                <div>
                    <p class="log-title">Admin Transaction History</p>
                    <p class="log-subtitle">Latest 200 admin-side create, update, delete, and assignment transactions.</p>
                </div>
                <div class="records-info" id="recordsInfo">Showing <?= count($transactionLogs) ?> of <?= count($transactionLogs) ?> records</div>
                <?php include_once __DIR__ . '/../includes/footer.php'; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');
            const recordCount = document.getElementById('recordCount');
            const recordsInfo = document.getElementById('recordsInfo');
            const rows = Array.from(tableBody.querySelectorAll('tr')).filter(function(row) {
                return !row.querySelector('.log-empty');
            });
            const total = rows.length;

            function updateRows() {
                const query = searchInput.value.trim().toLowerCase();
                let visible = 0;

                rows.forEach(function(row) {
                    const match = row.textContent.toLowerCase().includes(query);
                    row.style.display = match ? '' : 'none';
                    if (match) {
                        visible += 1;
                    }
                });

                recordCount.textContent = visible;
                recordsInfo.textContent = 'Showing ' + visible + ' of ' + total + ' records';
            }

            searchInput.addEventListener('input', updateRows);
            updateRows();
        });
    </script>
</body>
</html>