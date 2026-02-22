<?php
// get_dashboard_systems.php
error_reporting(0); // disable warnings/notices
header('Content-Type: application/json');
session_start();
include_once __DIR__ . '/../../includes/config_access.php'; // PDO connection

// Check if user is logged in
if (!isset($_SESSION['user']['portal_id'])) {
    echo json_encode([
        'status' => 401,
        'message' => 'Not logged in.'
    ]);
    exit;
}

$portal_id = $_SESSION['user']['portal_id'];

try {
    // 👉 1. Fetch assigned systems – isama ang `status` column
    $stmt = $pdo->prepare("
        SELECT 
            s.id AS system_id, 
            s.system_name, 
            s.domain_url, 
            s.logo_path,
            s.description,
            s.status,               -- ⚠️ kunin ang status (Active / Inactive)
            ua.role
        FROM user_system_access ua
        JOIN systems s ON s.id = ua.system_id
        WHERE ua.portal_id = :portal_id
        ORDER BY s.system_name
    ");
    $stmt->execute([':portal_id' => $portal_id]);
    $assigned_systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 👉 2. If user is admin, give access to all systems
    if ($_SESSION['user']['role'] === 'admin') {
        $stmt2 = $pdo->query("
            SELECT 
                id AS system_id, 
                system_name, 
                domain_url, 
                logo_path,
                status, 
                description,            -- ⚠️ kunin din ang status
                'subadmin' AS role 
            FROM systems 
            ORDER BY system_name
        ");
        $assigned_systems = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    }

    // 👉 3. I-convert ang 'status' sa boolean 'is_active' para sa frontend
    foreach ($assigned_systems as &$system) {
        $system['is_active'] = (strtolower(trim($system['status'])) === 'active');
        // (Optional) Pwede mo ring iwan ang original status kung gusto mo
    }

    // Return empty array if no systems found
    echo json_encode($assigned_systems ?: []);
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 500,
        'message' => 'Failed to fetch dashboard systems.',
        'error' => $e->getMessage()
    ]);
}
?>