<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    require_once __DIR__ . '/../../includes/config_access.php'; // may $pod
    require_once __DIR__ . '/../../includes/api_guard.php';

    api_require_method('GET');
    $currentUser = api_require_user(['admin', 'subadmin']);

    $currentUserId = $currentUser['portal_id'];

    // Query: assignments na ang assigned_by ay ang current user
    $query = "
        SELECT 
            ua.access_id,
            ua.portal_id,
            ua.system_id,
            ua.role,
            ua.date_assigned,
            u.firstname,
            u.lastname,
            s.system_name,
            CONCAT(au.firstname, ' ', au.lastname) AS assigned_by_name
        FROM user_system_access ua
        JOIN user_tbl u ON ua.portal_id = u.portal_id
        JOIN systems s ON ua.system_id = s.id
        LEFT JOIN user_tbl au ON ua.assigned_by = au.portal_id
        WHERE ua.assigned_by = :current_user
        ORDER BY ua.date_assigned DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':current_user' => $currentUserId]);
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($assignments);
    exit;

} catch (PDOException $e) {
    api_json_response(500, ['error' => 'Database error']);
} catch (Exception $e) {
    api_json_response(500, ['error' => 'Server error']);
}