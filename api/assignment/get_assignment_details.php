<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config_access.php'; // adjust path

$access_id = $_GET['access_id'] ?? 0;
if (!$access_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing access_id']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            ua.access_id,
            ua.portal_id,
            ua.system_id,
            ua.role,
            ua.date_assigned,
            u.firstname,
            u.lastname,
            s.system_name
        FROM user_system_access ua
        JOIN user_tbl u ON ua.portal_id = u.portal_id
        JOIN systems s ON ua.system_id = s.id
        WHERE ua.access_id = ?
    ");
    $stmt->execute([$access_id]);
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        http_response_code(404);
        echo json_encode(['error' => 'Assignment not found']);
        exit;
    }

    echo json_encode($assignment);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}