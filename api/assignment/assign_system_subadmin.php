<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';

// Admin must be logged in
// $assigned_by = $_SESSION['portal_id'] ?? null;
$assigned_by = $_SESSION['user']['portal_id'] ?? null;
if (!$assigned_by) {
    echo json_encode(['status'=>401, 'title'=>'Unauthorized','message'=>'Please log in as admin.']);
    exit;
}

// Get POST JSON data
$data = json_decode(file_get_contents('php://input'), true);
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

   // Default role
$role = $data['role'] ?? 'user';

// Assign system
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
    echo json_encode(['status'=>500,'title'=>'Error','message'=>$e->getMessage()]);
}