<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';
require_once __DIR__ . '/../../includes/role_helper.php';

api_require_method('POST');
api_require_user(['admin', 'subadmin']);
api_require_csrf();

$data = api_decode_json_body();
if (!is_array($data)) {
    $data = $_POST;
}

$roleId = isset($data['role_id']) ? (int) $data['role_id'] : 0;
if ($roleId <= 0) {
    echo json_encode(['status' => 400, 'message' => 'Role ID is required']);
    exit;
}

try {
    role_seed_defaults($pdo);

    $roleStmt = $pdo->prepare('SELECT role_id, role_name FROM roles_tbl WHERE role_id = :role_id');
    $roleStmt->execute([':role_id' => $roleId]);
    $role = $roleStmt->fetch(PDO::FETCH_ASSOC);

    if (!$role) {
        echo json_encode(['status' => 404, 'message' => 'Role not found']);
        exit;
    }

    $usageStmt = $pdo->prepare('SELECT COUNT(*) FROM user_system_access WHERE LOWER(role) = :role_name');
    $usageStmt->execute([':role_name' => strtolower($role['role_name'])]);
    $systemAssignments = (int) $usageStmt->fetchColumn();

    $userRoleStmt = $pdo->prepare('SELECT COUNT(*) FROM user_roles WHERE role_id = :role_id');
    $userRoleStmt->execute([':role_id' => $roleId]);
    $directAssignments = (int) $userRoleStmt->fetchColumn();

    if ($systemAssignments > 0 || $directAssignments > 0) {
        echo json_encode(['status' => 409, 'message' => 'Role is currently assigned and cannot be deleted']);
        exit;
    }

    $deleteStmt = $pdo->prepare('DELETE FROM roles_tbl WHERE role_id = :role_id');
    $deleteStmt->execute([':role_id' => $roleId]);

    echo json_encode(['status' => 200, 'message' => 'Role deleted successfully']);
} catch (Throwable $e) {
    api_json_response(500, ['status' => 500, 'message' => 'Failed to delete role']);
}
