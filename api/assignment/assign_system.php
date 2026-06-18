<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 405, 'message' => 'Method not allowed']);
    exit;
}

$assigned_by = $_SESSION['user']['portal_id'] ?? null;
if (!$assigned_by) {
    http_response_code(401);
    echo json_encode(['status' => 401, 'message' => 'Unauthorized. Please log in.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$portal_id = $input['portal_id'] ?? '';
$system_id = $input['system_id'] ?? '';
$role = $input['role'] ?? 'user';

if (!$portal_id || !$system_id) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'message' => 'Missing parameters']);
    exit;
}

if (!$assigned_by) {
    echo json_encode(['status' => 401, 'message' => 'Not authenticated']);
    exit;
}

try {
    // Check if already assigned
    $check = $pdo->prepare("SELECT access_id FROM user_system_access WHERE portal_id = ? AND system_id = ?");
    $check->execute([$portal_id, $system_id]);
    if ($check->fetch()) {
        echo json_encode(['status' => 409, 'message' => 'System already assigned to this user']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO user_system_access (portal_id, system_id, role, assigned_by, date_assigned) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$portal_id, $system_id, $role, $assigned_by]);
    echo json_encode(['status' => 200, 'message' => 'System assigned successfully']);
} catch (PDOException $e) {
    error_log('assign_system failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Unable to assign system right now.']);
} catch (Exception $e) {
    error_log('assign_system failed: ' . $e->getMessage());
} catch (PDOException $e) { 
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Unable to assign system right now.']);
}