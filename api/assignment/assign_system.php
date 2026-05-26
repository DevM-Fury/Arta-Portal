<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config_access.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 405, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$portal_id = $input['portal_id'] ?? '';
$system_id = $input['system_id'] ?? '';
$role = $input['role'] ?? 'user';
$assigned_by = $_SESSION['user']['portal_id'] ?? null;

if (!$portal_id || !$system_id) {
    echo json_encode(['status' => 400, 'message' => 'Missing parameters']);
    exit;
}

if (!$assigned_by) {
    echo json_encode(['status' => 401, 'message' => 'Not authenticated']);
    exit;
}

try {
    // Optional: check if already assigned
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
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Server error: ' . $e->getMessage()]);
}