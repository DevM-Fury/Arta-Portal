<?php
// get_systems_subadmin.php
error_reporting(0);
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';

api_require_method('GET');
$currentUser = api_require_user(['admin', 'subadmin']);

$portal_id = $currentUser['portal_id'];
$user_role = $currentUser['role'] ?? 'user';

try {
    if ($user_role === 'admin') {
        // ✅ Admin can view ALL systems
        $stmt = $pdo->query("
            SELECT id AS system_id, system_name
            FROM systems
            ORDER BY system_name ASC
        ");
    } else {
        // ✅ Subadmin sees only systems assigned to them
        $stmt = $pdo->prepare("
            SELECT s.id AS system_id, s.system_name
            FROM user_system_access usa
            INNER JOIN systems s ON s.id = usa.system_id
            WHERE usa.portal_id = :portal_id
            ORDER BY s.system_name ASC
        ");
        $stmt->execute([':portal_id' => $portal_id]);
    }

    $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($systems ?: []);

} catch (PDOException $e) {
    api_json_response(500, [
        'status' => 500,
        'message' => 'Database error.'
    ]);
}