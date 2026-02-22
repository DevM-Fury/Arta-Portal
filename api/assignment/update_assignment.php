<?php
header('Content-Type: application/json');
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config_access.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 405, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$access_id = $input['access_id'] ?? 0;
$system_id = $input['system_id'] ?? 0;
$role = $input['role'] ?? '';

if (!$access_id || !$system_id || !$role) {
    echo json_encode(['status' => 400, 'message' => 'Missing parameters']);
    exit;
}

// Kunin ang current user (assuming naka-store sa session)
$currentUserId = $_SESSION['user']['portal_id'] ?? null;
$currentUserRole = $_SESSION['user']['role'] ?? '';

if (!$currentUserId) {
    echo json_encode(['status' => 401, 'message' => 'Not authenticated']);
    exit;
}

try {
    // Suriin kung sino ang nag-assign ng assignment na ito
    $checkStmt = $pdo->prepare("SELECT assigned_by FROM user_system_access WHERE access_id = ?");
    $checkStmt->execute([$access_id]);
    $assignedBy = $checkStmt->fetchColumn();

    if (!$assignedBy) {
        echo json_encode(['status' => 404, 'message' => 'Assignment not found']);
        exit;
    }

    // Kung hindi admin at hindi ikaw ang nag-assign, bawal mag-update
    if ($currentUserRole !== 'admin' && $assignedBy !== $currentUserId) {
        echo json_encode(['status' => 403, 'message' => 'You are not allowed to update this assignment']);
        exit;
    }

    // Proceed with update
    $updateStmt = $pdo->prepare("UPDATE user_system_access SET system_id = ?, role = ? WHERE access_id = ?");
    $updateStmt->execute([$system_id, $role, $access_id]);

    echo json_encode(['status' => 200, 'message' => 'Assignment updated successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Database error: ' . $e->getMessage()]);
}