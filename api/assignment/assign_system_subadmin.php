<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';
include_once __DIR__ . '/../../includes/api_guard.php';
include_once __DIR__ . '/../../includes/role_helper.php';

api_require_method('POST');
$currentUser = api_require_user(['admin', 'subadmin']);
api_require_csrf();
$assigned_by = $currentUser['portal_id'];

// Get POST JSON data
$data = api_decode_json_body();
$portal_id = $data['portal_id'] ?? '';
$system_id = $data['system_id'] ?? '';

if (!$portal_id || !$system_id) {
    echo json_encode(['status'=>400,'title'=>'Invalid Data','message'=>'Portal ID and System ID are required.']);
    exit;
}

try {
    // Check duplicate
    $stmt = $pdo->prepare("SELECT * FROM user_system_access WHERE portal_id=:portal AND system_id=:system");
    $stmt->execute([':portal'=>$portal_id, ':system'=>$system_id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status'=>409,'title'=>'Already Assigned','message'=>'This system is already assigned to the user.']);
        exit;
    }

    $role = role_normalize_name($data['role'] ?? 'user');
    if (!role_exists($pdo, $role)) {
        echo json_encode(['status' => 400, 'title' => 'Invalid Role', 'message' => 'Selected role does not exist.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO user_system_access (portal_id, system_id, role, assigned_by, date_assigned) 
                           VALUES (:portal, :system, :role, :assigned_by, NOW())");
    $stmt->execute([
        ':portal' => $portal_id,
        ':system' => $system_id,
        ':role'   => $role,
        ':assigned_by' => $assigned_by
    ]);

    echo json_encode(['status'=>200,'title'=>'Success','message'=>'System assigned successfully.']);

} catch (PDOException $e) {
    api_json_response(500, ['status'=>500,'title'=>'Error','message'=>'Unable to assign system right now.']);
}