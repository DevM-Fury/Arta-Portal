<?php
// api/assignment/delete_assignment.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/rbac.php';
session_start();

$deleted_by = $_SESSION['portal_id'] ?? null;
if (!$deleted_by) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$access_id = $body['access_id'] ?? null;
if (!$access_id) {
    echo json_encode(['status'=>'error','message'=>'Missing access_id']);
    exit;
}

try {
    // fetch assignment
    $g = $pdo->prepare("SELECT portal_id, system_id FROM user_system_access WHERE access_id = :aid");
    $g->execute([':aid'=>$access_id]);
    $info = $g->fetch();
    if (!$info) {
        echo json_encode(['status'=>'error','message'=>'Assignment not found']);
        exit;
    }

    $role = getUserRole($deleted_by);
    if ($role === 'user') {
        echo json_encode(['status'=>'error','message'=>'Access denied']);
        exit;
    }
    if ($role === 'subadmin' && !userHasSystem($deleted_by, $info['system_id'])) {
        echo json_encode(['status'=>'error','message'=>'Subadmin can only remove assignments from systems they manage']);
        exit;
    }

    $del = $pdo->prepare("DELETE FROM user_system_access WHERE access_id = :aid");
    $del->execute([':aid'=>$access_id]);

    // log
    $log = $pdo->prepare("INSERT INTO log_tbl (portal_id, action, details, ip_address, device_info) VALUES (:pid, :action, :details, :ip, :device)");
    $log->execute([
        ':pid'=>$deleted_by,
        ':action'=>'REMOVE_SYSTEM_ASSIGNMENT',
        ':details'=>"Removed portal_id={$info['portal_id']} from system_id={$info['system_id']}",
        ':ip'=>$_SERVER['REMOTE_ADDR'] ?? null,
        ':device'=>$_SERVER['HTTP_USER_AGENT'] ?? null
    ]);

    echo json_encode(['status'=>'success','message'=>'Assignment removed']);
} catch (Exception $e) {
    error_log('delete_assignment failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Unable to remove assignment right now.']);
}