<?php

ini_set('display_errors', 0);
error_reporting(0);
date_default_timezone_set('Asia/Manila');

require_once dirname(__DIR__, 2) . '/includes/config_access.php';
require_once dirname(__DIR__, 2) . '/includes/sso_helper.php';

$ssoConfig = sso_get_config();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sso_json_response(405, [
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}

function sso_v2_get_header(string $name): string
{
    $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
    return trim((string) ($_SERVER[$key] ?? ''));
}

function sso_v2_read_json_body(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);

    return is_array($decoded) ? $decoded : [];
}

function sso_v2_fail(
    PDO $pdo,
    int $statusCode,
    ?string $portalId,
    string $action,
    string $details,
    string $message
): void {
    sso_log_event(
        $pdo,
        $portalId,
        $action,
        $details
    );

    sso_json_response($statusCode, [
        'status' => 'error',
        'message' => $message
    ]);
}

$body = sso_v2_read_json_body();

$token = isset($body['token']) && is_scalar($body['token'])
    ? trim((string) $body['token'])
    : '';

$systemIdRaw = isset($body['system_id']) && is_scalar($body['system_id'])
    ? trim((string) $body['system_id'])
    : '';

$clientId = sso_v2_get_header('X-SSO-Client-ID');
$clientSecret = sso_v2_get_header('X-SSO-Client-Secret');

if ($token === '' || $systemIdRaw === '' || !ctype_digit($systemIdRaw)) {
    sso_v2_fail(
        $pdo,
        400,
        null,
        'sso_v2_invalid_request',
        'Missing or invalid token/system_id',
        'Invalid request'
    );
}

if ($clientId === '' || $clientSecret === '') {
    sso_v2_fail(
        $pdo,
        401,
        null,
        'sso_v2_missing_client_credentials',
        'Missing SSO client credentials',
        'Unauthorized'
    );
}

$systemId = (int) $systemIdRaw;

try {
    $pdo->beginTransaction();

    $systemStmt = $pdo->prepare(
        '
        SELECT
            id,
            system_name,
            status,
            sso_enabled,
            sso_client_id,
            sso_client_secret_hash
        FROM systems
        WHERE id = :system_id
        LIMIT 1
        FOR UPDATE
        '
    );

    $systemStmt->execute([
        ':system_id' => $systemId
    ]);

    $system = $systemStmt->fetch(PDO::FETCH_ASSOC);

    if (!$system) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            401,
            null,
            'sso_v2_unknown_system',
            'Unknown system_id ' . $systemId,
            'Unauthorized'
        );
    }

    if (strcasecmp((string) ($system['status'] ?? ''), 'Active') !== 0) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            403,
            null,
            'sso_v2_inactive_system',
            'Inactive system_id ' . $systemId,
            'System is inactive'
        );
    }

    if (empty($system['sso_enabled'])) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            403,
            null,
            'sso_v2_disabled_system',
            'SSO disabled for system_id ' . $systemId,
            'SSO is disabled for this system'
        );
    }

    $storedClientId = trim((string) ($system['sso_client_id'] ?? ''));
    $storedSecretHash = trim((string) ($system['sso_client_secret_hash'] ?? ''));

    $clientIdValid = $storedClientId !== ''
        && hash_equals($storedClientId, $clientId);

    $clientSecretValid = $storedSecretHash !== ''
        && password_verify($clientSecret, $storedSecretHash);

    if (!$clientIdValid || !$clientSecretValid) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            401,
            null,
            'sso_v2_invalid_client_credentials',
            'Invalid SSO client credentials for system_id ' . $systemId,
            'Unauthorized'
        );
    }

    $tokenStmt = $pdo->prepare(
        '
        SELECT *
        FROM tokens_tbl
        WHERE token = :token
        LIMIT 1
        FOR UPDATE
        '
    );

    $tokenStmt->execute([
        ':token' => $token
    ]);

    $tokenData = $tokenStmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenData) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            401,
            null,
            'sso_v2_invalid_token',
            'Invalid token received for system_id ' . $systemId,
            'Invalid token'
        );
    }

    $portalId = isset($tokenData['portal_id'])
        ? (string) $tokenData['portal_id']
        : null;

    $tokenSystemId = isset($tokenData['system_id'])
        ? (int) $tokenData['system_id']
        : 0;

    if ($tokenSystemId !== $systemId) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            401,
            $portalId,
            'sso_v2_system_mismatch',
            'Token system mismatch. Token system_id='
                . $tokenSystemId
                . ', requested system_id='
                . $systemId,
            'Unauthorized'
        );
    }

    if (isset($tokenData['used']) && !empty($tokenData['used'])) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            409,
            $portalId,
            'sso_v2_used_token',
            'Already-used token rejected for system_id ' . $systemId,
            'Token already used'
        );
    }

    if (
        empty($tokenData['expires_at']) ||
        strtotime((string) $tokenData['expires_at']) < time()
    ) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            410,
            $portalId,
            'sso_v2_expired_token',
            'Expired token rejected for system_id ' . $systemId,
            'Token expired'
        );
    }

    $userStmt = $pdo->prepare(
        '
        SELECT
            u.portal_id,
            u.firstname,
            u.lastname,
            u.email,
            u.status,
            u.contact,
            d.designation_name,
            o.office_name,
            dv.division_name,
            un.unit_name
        FROM user_tbl u
        LEFT JOIN designations d
            ON u.designation_id = d.id
        LEFT JOIN offices o
            ON u.office_id = o.id
        LEFT JOIN divisions dv
            ON u.division_id = dv.id
        LEFT JOIN units un
            ON u.unit_id = un.id
        WHERE u.portal_id = :portal_id
        LIMIT 1
        '
    );

    $userStmt->execute([
        ':portal_id' => $portalId
    ]);

    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            404,
            $portalId,
            'sso_v2_missing_user',
            'No user found for validated token',
            'User not found'
        );
    }

    if (strtolower((string) ($user['status'] ?? '')) !== 'active') {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            403,
            $portalId,
            'sso_v2_inactive_user',
            'Inactive user rejected for system_id ' . $systemId,
            'User account is inactive'
        );
    }

    $roleStmt = $pdo->prepare(
        '
        SELECT role
        FROM user_system_access
        WHERE portal_id = :portal_id
          AND system_id = :system_id
        LIMIT 1
        '
    );

    $roleStmt->execute([
        ':portal_id' => $portalId,
        ':system_id' => $systemId
    ]);

    $roleData = $roleStmt->fetch(PDO::FETCH_ASSOC);

    $role = strtolower(
        trim((string) ($roleData['role'] ?? ''))
    );

    if ($role === '' || !sso_allowed_role($role, $ssoConfig)) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            403,
            $portalId,
            'sso_v2_unauthorized_role',
            'Missing or invalid system role for system_id ' . $systemId,
            'User does not have access to this system'
        );
    }

    $updateStmt = $pdo->prepare(
        '
        UPDATE tokens_tbl
        SET
            used = TRUE,
            used_at = NOW()
        WHERE token = :token
          AND used = FALSE
        '
    );

    $updateStmt->execute([
        ':token' => $token
    ]);

    if ($updateStmt->rowCount() !== 1) {
        $pdo->rollBack();

        sso_v2_fail(
            $pdo,
            409,
            $portalId,
            'sso_v2_token_race',
            'Token was already consumed during validation',
            'Token already used'
        );
    }

    $pdo->commit();

    $userPayload = [
        'portal_id' => (string) $user['portal_id'],
        'firstname' => (string) ($user['firstname'] ?? ''),
        'lastname' => (string) ($user['lastname'] ?? ''),
        'full_name' => trim(
            (string) ($user['firstname'] ?? '')
            . ' '
            . (string) ($user['lastname'] ?? '')
        ),
        'email' => (string) ($user['email'] ?? ''),
        'designation_name' => $user['designation_name'] ?? null,
        'office_name' => $user['office_name'] ?? null,
        'division_name' => $user['division_name'] ?? null,
        'unit_name' => $user['unit_name'] ?? null,
        'mobile_number' => (isset($user['contact']) && trim((string) $user['contact']) !== '')
            ? trim((string) $user['contact'])
            : null,
        'portal_system_role' => $role
    ];

    sso_log_event(
        $pdo,
        $portalId,
        'sso_v2_validate_success',
        'SSO v2 token validated successfully for system_id '
            . $systemId
            . ' and role '
            . $role
    );

    sso_json_response(200, [
        'status' => 'success',
        'message' => 'Token valid',
        'system_id' => $systemId,
        'user' => $userPayload
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log(
        'SSO v2 database error: '
        . $e->getMessage()
    );

    sso_json_response(500, [
        'status' => 'error',
        'message' => 'Unable to validate token right now'
    ]);

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log(
        'SSO v2 server error: '
        . $e->getMessage()
    );

    sso_json_response(500, [
        'status' => 'error',
        'message' => 'Unable to validate token right now'
    ]);
}
