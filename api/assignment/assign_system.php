<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';

// Check admin session
$assigned_by = $_SESSION['user']['portal_id'] ?? null;
if (!$assigned_by) {
    echo json_encode(['status' => 401, 'title' => 'Unauthorized', 'message' => 'Please log in as admin.']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$portal_id = $data['portal_id'] ?? '';
$system_id = $data['system_id'] ?? '';
$role = $data['role'] ?? 'subadmin';

if (!$portal_id || !$system_id) {
    echo json_encode(['status' => 400, 'title' => 'Invalid Data', 'message' => 'Portal ID and System ID are required.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Check if already assigned
    $stmt = $pdo->prepare("SELECT 1 FROM user_system_access WHERE portal_id = :portal AND system_id = :system");
    $stmt->execute([':portal' => $portal_id, ':system' => $system_id]);

    if ($stmt->fetch()) {
        echo json_encode(['status' => 409, 'title' => 'Already Assigned', 'message' => 'This system is already assigned to the user.']);
        $pdo->rollBack();
        exit;
    }

    // Assign system
    $stmt = $pdo->prepare("
        INSERT INTO user_system_access (portal_id, system_id, role, assigned_by, date_assigned) 
        VALUES (:portal, :system, :role, :assigned_by, NOW())
    ");
    $stmt->execute([
        ':portal' => $portal_id,
        ':system' => $system_id,
        ':role' => $role,
        ':assigned_by' => $assigned_by
    ]);

    // ✅ Update user role in user_tbl
    $update = $pdo->prepare("UPDATE user_tbl SET role = :role, modified_date = NOW() WHERE portal_id = :portal");
    $update->execute([
        ':role' => $role,
        ':portal' => $portal_id
    ]);

    $pdo->commit();

    echo json_encode(['status' => 200, 'title' => 'Success', 'message' => 'System assigned and user role updated successfully.']);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 500, 'title' => 'Error', 'message' => $e->getMessage()]);
}