<?php
// api/assignment/get_users.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';

try {
    $stmt = $pdo->query("SELECT portal_id, firstname, lastname, email, office_id, division_id, unit_id, designation_id FROM user_tbl WHERE status IS NULL OR status = 'active' ORDER BY lastname, firstname");
    $rows = $stmt->fetchAll();
    echo json_encode(['status'=>'success', 'data'=>$rows]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}