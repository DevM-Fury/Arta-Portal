<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';
require_once __DIR__ . '/../../includes/role_helper.php';

api_require_method('GET');
api_require_user(['admin', 'subadmin']);

try {
    $roles = role_fetch_all($pdo);
    echo json_encode([
        'status' => 200,
        'message' => 'Roles fetched successfully',
        'data' => ['roles' => $roles],
    ]);
} catch (Throwable $e) {
    api_json_response(500, ['status' => 500, 'message' => 'Failed to fetch roles']);
}
