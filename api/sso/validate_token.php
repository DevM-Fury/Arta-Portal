<?php
ini_set('display_errors', 0);
error_reporting(0);
date_default_timezone_set('Asia/Manila');

require_once dirname(__DIR__, 2) . "/includes/config_access.php";
require_once dirname(__DIR__, 2) . "/includes/sso_helper.php";

$ssoConfig = sso_get_config();
sso_apply_cors($ssoConfig, false);
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sso_json_response(405, ["status" => "error", "message" => "Method not allowed"]);
}

$token = $_GET['token'] ?? null;

if (!$token) {
    sso_log_event($pdo, null, 'sso_validate_missing_token', 'Missing token on validator request');
    sso_json_response(400, ["status" => "error", "message" => "Missing token"]);
}

try {
    $stmt = $pdo->prepare("SELECT * FROM tokens_tbl WHERE token = ? LIMIT 1");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenData) {
        sso_log_event($pdo, null, 'sso_validate_invalid_token', 'Invalid SSO token received');
        sso_json_response(401, ["status" => "error", "message" => "Invalid token"]);
    }

    $portal_id = $tokenData['portal_id'] ?? null;
    $system_id = $tokenData['system_id'] ?? null;

    if (sso_has_tokens_column($pdo, 'used') && !empty($tokenData['used'])) {
        sso_log_event($pdo, $portal_id, 'sso_validate_used_token', 'Already used token rejected for system_id ' . $system_id);
        sso_json_response(409, ["status" => "error", "message" => "Token already used"]);
    }

    if (strtotime($tokenData['expires_at']) < time()) {
        sso_log_event($pdo, $portal_id, 'sso_validate_expired_token', 'Expired token rejected for system_id ' . $system_id);
        sso_json_response(410, ["status" => "error", "message" => "Token expired"]);
    }

    $user_stmt = $pdo->prepare("
        SELECT 
            u.portal_id, 
            u.firstname, 
            u.lastname, 
            u.email,
            d.designation_name,
            o.office_name,
            dv.division_name,
            un.unit_name,
            u.role AS portal_role,
            u.status
        FROM user_tbl u
        LEFT JOIN designations d ON u.designation_id = d.id
        LEFT JOIN offices o ON u.office_id = o.id
        LEFT JOIN divisions dv ON u.division_id = dv.id
        LEFT JOIN units un ON u.unit_id = un.id
        WHERE u.portal_id = ?
    ");
    $user_stmt->execute([$portal_id]);
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        sso_log_event($pdo, $portal_id, 'sso_validate_missing_user', 'Validated token has no matching user for system_id ' . $system_id);
        sso_json_response(404, ["status" => "error", "message" => "User not found"]);
    }

    if (strtolower((string) ($user_data['status'] ?? '')) !== 'active') {
        sso_log_event($pdo, $portal_id, 'sso_validate_inactive_user', 'Inactive user rejected for system_id ' . $system_id);
        sso_json_response(403, ["status" => "error", "message" => "User account is inactive"]);
    }

    $role_stmt = $pdo->prepare("SELECT role FROM user_system_access WHERE portal_id = ? AND system_id = ?");
    $role_stmt->execute([$portal_id, $system_id]);
    $role_data = $role_stmt->fetch(PDO::FETCH_ASSOC);
    $role = null;
    $candidateRole = sso_normalize_role(
        $role_data['role'] ?? ''
    );
    if (sso_allowed_role($candidateRole, $ssoConfig)) {
        $role = $candidateRole;
    }

    if ($role === null) {
        sso_log_event($pdo, $portal_id, 'sso_validate_unauthorized_role', 'Unauthorized or unmapped role rejected for system_id ' . $system_id);
        sso_json_response(403, ["status" => "error", "message" => "User does not have access to this system"]);
    }

    $user_data['full_name'] = trim($user_data['firstname'] . ' ' . $user_data['lastname']);
    $user_data['role'] = $role;
    unset($user_data['status']);
    unset($user_data['portal_role']);

    sso_mark_token_used($pdo, $token);
    sso_log_event($pdo, $portal_id, 'sso_validate_success', 'SSO token validated successfully for system_id ' . $system_id . ' and role ' . $role);

    sso_json_response(200, [
        "status" => "success",
        "message" => "Token valid",
        "user" => $user_data
    ]);

} catch (PDOException $e) {
    sso_log_event($pdo, $portal_id ?? null, 'sso_validate_db_error', 'Database error during SSO token validation');
    sso_json_response(500, [
        "status" => "error",
        "message" => "Unable to validate token right now"
    ]);
} catch (Throwable $e) {
    sso_log_event($pdo, $portal_id ?? null, 'sso_validate_server_error', 'Unexpected error during SSO token validation');
    sso_json_response(500, [
        "status" => "error",
        "message" => "Unable to validate token right now"
    ]);
}
