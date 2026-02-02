<?php
require_once __DIR__ . '/../../includes/config_access.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['portal_id'], $data['system_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

$portal_id = $data['portal_id'];
$system_id = $data['system_id'];

try {
    $stmt = $conn->prepare("DELETE FROM user_system_access WHERE portal_id = ? AND system_id = ?");
    $stmt->execute([$portal_id, $system_id]);

    echo json_encode(['status' => 'success', 'message' => 'Access removed successfully']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}