<?php
header('Content-Type: application/json');
error_reporting(0);
session_start();
require_once __DIR__ . '/../../includes/config_access.php';

$portal_id = $_GET['portal_id'] ?? null;
$system_id = $_GET['system_id'] ?? null;

if (!$portal_id || !$system_id) {
    echo json_encode(['status'=>'error','message'=>'Missing parameters']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT a.portal_id, a.system_id, u.full_name, 
               o.office_name, d.division_name, un.unit_name, 
               des.designation_name, s.system_name, a.role, a.date_assigned
        FROM user_system_access a
        JOIN users u ON u.id = a.user_id
        LEFT JOIN offices o ON o.id = u.office_id
        LEFT JOIN divisions d ON d.id = u.division_id
        LEFT JOIN units un ON un.id = u.unit_id
        LEFT JOIN designation des ON des.id = u.designation_id
        LEFT JOIN systems s ON s.id = a.system_id
        WHERE a.portal_id = ? AND a.system_id = ?
        LIMIT 1
    ");
    $stmt->execute([$portal_id, $system_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status'=>'error','message'=>'User not found']);
        exit;
    }

    echo json_encode(['status'=>'success','data'=>$user]);
} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}