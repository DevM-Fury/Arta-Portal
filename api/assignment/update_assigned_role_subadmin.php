<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

function json_response($httpCode, array $payload)
{
    http_response_code($httpCode);
    echo json_encode($payload);
    exit;
}

try {
    require_once __DIR__ . '/../../includes/config_access.php';
    require_once __DIR__ . '/../../includes/api_guard.php';

    api_require_method('POST');
    $currentUser = api_require_user(['admin', 'subadmin']);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $csrfSession = $_SESSION['csrf_token'] ?? '';

    if (!$csrfHeader || !$csrfSession || !hash_equals($csrfSession, $csrfHeader)) {
        json_response(419, [
            'status' => 419,
            'title' => 'Security Error',
            'message' => 'Invalid or expired CSRF token.'
        ]);
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        json_response(400, [
            'status' => 400,
            'title' => 'Invalid Request',
            'message' => 'Invalid JSON payload.'
        ]);
    }

    $accessId = isset($input['access_id']) ? (int) $input['access_id'] : 0;
    $role = strtolower(trim((string)($input['role'] ?? '')));

    if ($accessId <= 0 || $role === '') {
        json_response(400, [
            'status' => 400,
            'title' => 'Missing Data',
            'message' => 'Missing assignment ID or role.'
        ]);
    }

    if (!preg_match('/^[a-z0-9_\- ]+$/', $role)) {
        json_response(400, [
            'status' => 400,
            'title' => 'Invalid Role',
            'message' => 'Role contains invalid characters.'
        ]);
    }

    $currentUserId = trim((string)($currentUser['portal_id'] ?? ''));
    $currentUserRole = strtolower(trim((string)($currentUser['role'] ?? '')));
    $isAdmin = $currentUserRole === 'admin';

    if ($currentUserId === '') {
        json_response(401, [
            'status' => 401,
            'title' => 'Unauthorized',
            'message' => 'Current user session is invalid.'
        ]);
    }

    if ($isAdmin) {
        $checkStmt = $pdo->prepare("
            SELECT access_id, portal_id, system_id, role, assigned_by
            FROM user_system_access
            WHERE access_id = ?
            LIMIT 1
        ");
        $checkStmt->execute([$accessId]);
    } else {
        $checkStmt = $pdo->prepare("
            SELECT access_id, portal_id, system_id, role, assigned_by
            FROM user_system_access
            WHERE access_id = ?
              AND assigned_by = ?
            LIMIT 1
        ");
        $checkStmt->execute([$accessId, $currentUserId]);
    }

    $assignment = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        json_response(404, [
            'status' => 404,
            'title' => 'Not Found',
            'message' => 'Assignment not found or you are not allowed to edit it.'
        ]);
    }

    if (strtolower(trim((string)$assignment['role'])) === $role) {
        json_response(200, [
            'status' => 200,
            'message' => 'Role is already set to ' . $role . '.'
        ]);
    }

    $updateStmt = $pdo->prepare("
        UPDATE user_system_access
        SET role = ?
        WHERE access_id = ?
    ");
    $updateStmt->execute([$role, $accessId]);

    json_response(200, [
        'status' => 200,
        'message' => 'Assigned role updated successfully.'
    ]);

} catch (PDOException $e) {
    error_log('update_assigned_role_subadmin DB error: ' . $e->getMessage());

    json_response(500, [
        'status' => 500,
        'title' => 'Database Error',
        'message' => 'Database error while updating assigned role.'
    ]);

} catch (Throwable $e) {
    error_log('update_assigned_role_subadmin server error: ' . $e->getMessage());

    json_response(500, [
        'status' => 500,
        'title' => 'Server Error',
        'message' => 'Server error while updating assigned role.'
    ]);
}