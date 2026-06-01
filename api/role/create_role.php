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

$roleName = role_normalize_name($data['role_name'] ?? '');
$roleLevel = isset($data['role_level']) ? (int) $data['role_level'] : 10;

if ($roleName === '') {
    echo json_encode(['status' => 400, 'message' => 'Role name is required']);
    exit;
}

if ($roleLevel < 1) {
    echo json_encode(['status' => 400, 'message' => 'Role level must be at least 1']);
    exit;
}

try {
    role_seed_defaults($pdo);

    if (role_exists($pdo, $roleName)) {
        echo json_encode(['status' => 409, 'message' => 'Role already exists']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO roles_tbl (role_name, role_level) VALUES (:role_name, :role_level) RETURNING role_id');
    $stmt->execute([
        ':role_name' => $roleName,
        ':role_level' => $roleLevel,
    ]);

    echo json_encode([
        'status' => 200,
        'message' => 'Role created successfully',
        'data' => [
            'role_id' => (int) $stmt->fetchColumn(),
            'role_name' => $roleName,
            'role_level' => $roleLevel,
        ],
    ]);
} catch (Throwable $e) {
    api_json_response(500, ['status' => 500, 'message' => 'Failed to create role']);
}
