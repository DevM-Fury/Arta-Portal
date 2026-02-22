<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 405, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$portal_id = $input['portal_id'] ?? '';
$system_id = $input['system_id'] ?? '';
$assigned_by = $_SESSION['portal_id'] ?? null; // adjust based on your auth

if (!$portal_id || !$system_id) {
    echo json_encode(['status' => 400, 'message' => 'Missing parameters']);
    exit;
}

try {
    // Optional: check if already assigned
    $check = $pdo->prepare("SELECT id FROM assigned_systems WHERE portal_id = ? AND system_id = ?");
    $check->execute([$portal_id, $system_id]);
    if ($check->fetch()) {
        echo json_encode(['status' => 409, 'message' => 'System already assigned to this user']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO assigned_systems (portal_id, system_id, assigned_by, date_assigned) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$portal_id, $system_id, $assigned_by]);
    echo json_encode(['status' => 200, 'message' => 'System assigned successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
}