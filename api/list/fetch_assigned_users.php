<?php
header('Content-Type: application/json');

// Disable notices/warnings to prevent breaking JSON output
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once __DIR__ . '/../../includes/config_access.php';
// session_start(); <-- Huwag nang tawagin ulit kung active na sa config_access.php

// 🔹 Step 1: Check session
if (!isset($_SESSION['user']['portal_id']) || empty($_SESSION['user']['portal_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized - session missing',
        'session' => $_SESSION
    ]);
    exit;
}

// 🔹 Get current subadmin portal_id
$assigned_by = $_SESSION['user']['portal_id'];

try {
    // 🔹 PostgreSQL-ready query
    $sql = "
        SELECT 
            usa.portal_id,
            u.firstname || ' ' || u.lastname AS full_name,
            COALESCE(o.office_name, 'N/A') AS office_name,
            COALESCE(d.division_name, 'N/A') AS division_name,
            COALESCE(un.unit_name, 'N/A') AS unit_name,
            COALESCE(des.designation_name, 'N/A') AS designation_name,
            COALESCE(s.system_name, 'N/A') AS system_name,
            COALESCE(usa.role, 'N/A') AS role,
            TO_CHAR(usa.date_assigned, 'YYYY-MM-DD HH24:MI:SS') AS date_assigned
        FROM user_system_access usa
        LEFT JOIN user_tbl u ON usa.portal_id = u.portal_id
        LEFT JOIN offices o ON u.office_id = o.id
        LEFT JOIN divisions d ON u.division_id = d.id
        LEFT JOIN units un ON u.unit_id = un.id
        LEFT JOIN designations des ON u.designation_id = des.id
        LEFT JOIN systems s ON usa.system_id = s.id
        WHERE usa.assigned_by = :assigned_by
        ORDER BY usa.date_assigned DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['assigned_by' => $assigned_by]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🔹 Add action buttons
    foreach ($rows as &$row) {
        $pid = $row['portal_id'];
        $row['action'] = '
            <button class="btn btn-sm btn-outline-primary me-1 viewBtn" data-id="'.$pid.'">
                <i class="fa-solid fa-eye"></i> View
            </button>
            <button class="btn btn-sm btn-outline-warning editBtn" data-id="'.$pid.'">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>
        ';
    }

    // 🔹 Return clean JSON
    echo json_encode([
        'status' => 'success',
        'count' => count($rows),
        'data' => $rows
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database query failed',
        'error_details' => $e->getMessage()
    ]);
    exit;
}