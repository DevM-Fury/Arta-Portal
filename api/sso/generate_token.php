<?php
ini_set('display_errors', 0);
error_reporting(0);
date_default_timezone_set('Asia/Manila');

require_once dirname(__DIR__, 2) . "/includes/config_access.php";
require_once dirname(__DIR__, 2) . "/includes/sso_helper.php";

$ssoConfig = sso_get_config();
sso_apply_cors($ssoConfig, true);
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

if (!isset($_SESSION['user']['portal_id'])) {
    sso_json_response(401, ["status" => "error", "message" => "User not logged in"]);
}

$portal_id = $_SESSION['user']['portal_id'];
$system_id = $_GET['system_id'] ?? null;
$system_id_raw = is_scalar($system_id) ? trim((string) $system_id) : '';

if ($system_id_raw === '' || !ctype_digit($system_id_raw)) {
    sso_log_event($pdo, $portal_id, 'sso_generate_invalid_request', 'Missing or invalid system_id during token generation');
    sso_json_response(400, ["status" => "error", "message" => "Missing or invalid system_id"]);
}

$system_id = (int) $system_id_raw;

try {
    $system_stmt = $pdo->prepare(" 
        SELECT id, system_name, domain_url, status
        FROM systems
        WHERE id = ?
        LIMIT 1
    ");
    $system_stmt->execute([$system_id]);
    $system = $system_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$system) {
        sso_log_event($pdo, $portal_id, 'sso_generate_unknown_system', 'Unknown system_id ' . $system_id);
        sso_json_response(404, ["status" => "error", "message" => "System not found"]);
    }

    if (strcasecmp((string) ($system['status'] ?? ''), 'Active') !== 0) {
        sso_log_event($pdo, $portal_id, 'sso_generate_inactive_system', 'Inactive system_id ' . $system_id . ' requested');
        sso_json_response(403, ["status" => "error", "message" => "System is inactive"]);
    }

    $system_url = trim((string) ($system['domain_url'] ?? ''));
    if (!sso_is_allowed_domain($system_url, $ssoConfig)) {
        sso_log_event($pdo, $portal_id, 'sso_generate_blocked_domain', 'Blocked SSO domain for system_id ' . $system_id . ': ' . $system_url);
        sso_json_response(400, ["status" => "error", "message" => "Resolved system URL is not allowed"]);
    }

    $role_stmt = $pdo->prepare(" 
        SELECT usa.role, u.status AS user_status
        FROM user_system_access usa
        INNER JOIN user_tbl u ON u.portal_id = usa.portal_id
        WHERE usa.portal_id = ? AND usa.system_id = ?
        LIMIT 1
    ");
    $role_stmt->execute([$portal_id, $system_id]);
    $role_data = $role_stmt->fetch(PDO::FETCH_ASSOC);

    $role = null;
    if ($role_data && strtolower((string) ($role_data['user_status'] ?? '')) === 'active') {
        $candidateRole = strtolower(trim((string) $role_data['role']));
        if (sso_allowed_role($candidateRole, $ssoConfig)) {
            $role = $candidateRole;
        }
    }

    if ($role === null) {
        sso_log_event($pdo, $portal_id, 'sso_generate_unauthorized', 'Unauthorized SSO token request for system_id ' . $system_id);
        sso_json_response(403, ["status" => "error", "message" => "User does not have access to this system"]);
    }

    $token = bin2hex(random_bytes(16));
    $expires_at = date('Y-m-d H:i:s', strtotime('+' . $ssoConfig['ttl_minutes'] . ' minutes'));

    $tokenColumns = ['token', 'portal_id', 'system_id', 'expires_at'];
    $placeholders = [':token', ':portal_id', ':system_id', ':expires_at'];
    $binds = [
        [':token', $token, PDO::PARAM_STR],
        [':portal_id', $portal_id, PDO::PARAM_STR],
        [':system_id', $system_id, PDO::PARAM_INT],
        [':expires_at', $expires_at, PDO::PARAM_STR],
    ];

    if (sso_has_tokens_column($pdo, 'used')) {
        $tokenColumns[] = 'used';
        $placeholders[] = 'FALSE';
    }
    if (sso_has_tokens_column($pdo, 'ip_address')) {
        $tokenColumns[] = 'ip_address';
        $placeholders[] = ':ip_address';
        $binds[] = [':ip_address', sso_client_ip(), PDO::PARAM_STR];
    }
    if (sso_has_tokens_column($pdo, 'user_agent')) {
        $tokenColumns[] = 'user_agent';
        $placeholders[] = ':user_agent';
        $binds[] = [':user_agent', sso_client_user_agent(), PDO::PARAM_STR];
    }

    $stmt = $pdo->prepare(
        'INSERT INTO tokens_tbl (' . implode(', ', $tokenColumns) . ') VALUES (' . implode(', ', $placeholders) . ')'
    );

    foreach ($binds as [$placeholder, $value, $type]) {
        $stmt->bindValue($placeholder, $value, $type);
    }

    $stmt->execute();

    $user_stmt = $pdo->prepare("
        SELECT 
            u.firstname, 
            u.lastname, 
            u.email,
            o.office_name,
            d.division_name,
            un.unit_name,
            g.designation_name
        FROM user_tbl u
        LEFT JOIN offices o ON u.office_id = o.id
        LEFT JOIN divisions d ON u.division_id = d.id
        LEFT JOIN units un ON u.unit_id = un.id
        LEFT JOIN designations g ON u.designation_id = g.id
        WHERE u.portal_id = ?
    ");
    $user_stmt->execute([$portal_id]);
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        sso_log_event($pdo, $portal_id, 'sso_generate_user_missing', 'User not found during token generation for system_id ' . $system_id);
        sso_json_response(404, ["status" => "error", "message" => "User not found"]);
    }

    $user_data['full_name'] = trim($user_data['firstname'] . ' ' . $user_data['lastname']);
    $user_data['role'] = $role;

    $redirect = rtrim($system_url, '/') . "/sso-login?token=" . urlencode($token);

    sso_log_event($pdo, $portal_id, 'sso_generate_success', 'Generated AMS SSO token for system_id ' . $system_id . ' and role ' . $role);

    sso_json_response(200, [
        "status" => "success",
        "redirect" => $redirect,
        "token" => $token,
        "expires_at" => $expires_at,
        "user" => $user_data
    ]);

} catch (PDOException $e) {
    sso_log_event($pdo, $portal_id, 'sso_generate_db_error', 'Database error during SSO token generation');
    sso_json_response(500, [
        "status" => "error",
        "message" => "Unable to generate SSO token right now"
    ]);
} catch (Throwable $e) {
    sso_log_event($pdo, $portal_id, 'sso_generate_server_error', 'Unexpected error during SSO token generation');
    sso_json_response(500, [
        "status" => "error",
        "message" => "Unable to generate SSO token right now"
    ]);
}