<?php

ini_set('display_errors', '0');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

require_once dirname(__DIR__, 2) . '/includes/config_access.php';
require_once dirname(__DIR__, 2) . '/includes/sso_helper.php';

/*
|--------------------------------------------------------------------------
| Start Session
|--------------------------------------------------------------------------
*/

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| SSO Configuration
|--------------------------------------------------------------------------
*/

$ssoConfig = sso_get_config();

sso_apply_cors($ssoConfig, true);

header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

/*
|--------------------------------------------------------------------------
| Role Normalizer
|--------------------------------------------------------------------------
*/

$normalizeRole = static function ($role): string {
    $role = strtolower(trim((string) $role));

    $role = preg_replace(
        '/[\s\-]+/',
        '_',
        $role
    );

    return match ($role) {
        /*
        |--------------------------------------------------------------------------
        | Administrator Roles
        |--------------------------------------------------------------------------
        */

        'administrator',
        'superadmin',
        'super_admin' => 'admin',

        'sub_admin',
        'subadministrator',
        'sub_administrator' => 'subadmin',

        /*
        |--------------------------------------------------------------------------
        | ICT Roles
        |--------------------------------------------------------------------------
        */

        'ict',
        'ictd',
        'ict_division',
        'information_technology',
        'information_technology_division',
        'information_and_communication_technology',
        'information_and_communications_technology',
        'information_and_communication_technology_division',
        'information_and_communications_technology_division' => 'ict',

        /*
        |--------------------------------------------------------------------------
        | Property Roles
        |--------------------------------------------------------------------------
        */

        'property_custodian',
        'property_officer',
        'property_management' => 'property',

        /*
        |--------------------------------------------------------------------------
        | End-User Roles
        |--------------------------------------------------------------------------
        */

        'employee',
        'enduser',
        'end_user' => 'user',

        default => $role,
    };
};

/*
|--------------------------------------------------------------------------
| Allowed SSO Roles
|--------------------------------------------------------------------------
|
| ICT is explicitly included here so the request will not be rejected just
| because the old SSO helper configuration did not include the ICT role.
|
*/

$allowedRoles = [
    'admin',
    'subadmin',
    'ict',
    'manager',
    'property',
    'inspector',
    'auditor',
    'user',
];

/*
|--------------------------------------------------------------------------
| Include Additional Configured Roles
|--------------------------------------------------------------------------
*/

$configuredRoles = $ssoConfig['allowed_roles'] ?? [];

if (is_array($configuredRoles)) {
    foreach ($configuredRoles as $configuredRole) {
        $normalizedConfiguredRole = $normalizeRole($configuredRole);

        if (
            $normalizedConfiguredRole !== '' &&
            !in_array(
                $normalizedConfiguredRole,
                $allowedRoles,
                true
            )
        ) {
            $allowedRoles[] = $normalizedConfiguredRole;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Role Authorization Helper
|--------------------------------------------------------------------------
*/

$isAllowedRole = static function ($role) use (
    $normalizeRole,
    $allowedRoles
): bool {
    $normalizedRole = $normalizeRole($role);

    return $normalizedRole !== '' &&
        in_array(
            $normalizedRole,
            $allowedRoles,
            true
        );
};

/*
|--------------------------------------------------------------------------
| Safe Audit Logging
|--------------------------------------------------------------------------
*/

$writeSsoLog = static function (
    string $portalId,
    string $event,
    string $description
) use ($pdo): void {
    try {
        sso_log_event(
            $pdo,
            $portalId,
            $event,
            $description
        );
    } catch (Throwable $loggingException) {
        error_log('[SSO LOG ERROR] ' . json_encode([
            'portal_id' => $portalId,
            'event' => $event,
            'error' => $loggingException->getMessage(),
        ]));
    }
};

/*
|--------------------------------------------------------------------------
| Handle Preflight Request
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/*
|--------------------------------------------------------------------------
| Validate HTTP Method
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sso_json_response(405, [
        'status' => 'error',
        'message' => 'Method not allowed',
    ]);
}

/*
|--------------------------------------------------------------------------
| Validate Portal Session
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['user']) ||
    !is_array($_SESSION['user']) ||
    empty($_SESSION['user']['portal_id'])
) {
    sso_json_response(401, [
        'status' => 'error',
        'message' => 'User not logged in',
    ]);
}

$portalId = trim(
    (string) $_SESSION['user']['portal_id']
);

$rawSessionRole = trim(
    (string) ($_SESSION['user']['role'] ?? '')
);

$sessionRole = $normalizeRole($rawSessionRole);

/*
|--------------------------------------------------------------------------
| Validate System ID
|--------------------------------------------------------------------------
*/

$systemIdInput = $_GET['system_id'] ?? null;

$systemIdRaw = is_scalar($systemIdInput)
    ? trim((string) $systemIdInput)
    : '';

if (
    $systemIdRaw === '' ||
    !ctype_digit($systemIdRaw)
) {
    $writeSsoLog(
        $portalId,
        'sso_generate_invalid_request',
        'Missing or invalid system_id during token generation'
    );

    error_log('[SSO] Invalid system ID request: ' . json_encode([
        'portal_id' => $portalId,
        'system_id_input' => $systemIdRaw,
    ]));

    sso_json_response(400, [
        'status' => 'error',
        'message' => 'Missing or invalid system_id',
    ]);
}

$systemId = (int) $systemIdRaw;

if ($systemId <= 0) {
    $writeSsoLog(
        $portalId,
        'sso_generate_invalid_request',
        'Invalid numeric system_id during token generation'
    );

    sso_json_response(400, [
        'status' => 'error',
        'message' => 'Missing or invalid system_id',
    ]);
}

try {
    /*
    |--------------------------------------------------------------------------
    | Retrieve Target System
    |--------------------------------------------------------------------------
    */

    $systemStatement = $pdo->prepare("
        SELECT
            id,
            system_name,
            domain_url,
            status
        FROM systems
        WHERE id = :system_id
        LIMIT 1
    ");

    $systemStatement->execute([
        ':system_id' => $systemId,
    ]);

    $system = $systemStatement->fetch(
        PDO::FETCH_ASSOC
    );

    if (!$system) {
        $writeSsoLog(
            $portalId,
            'sso_generate_unknown_system',
            'Unknown system_id ' . $systemId
        );

        error_log('[SSO] System not found: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
        ]));

        sso_json_response(404, [
            'status' => 'error',
            'message' => 'System not found',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate System Status
    |--------------------------------------------------------------------------
    */

    $systemStatus = strtolower(
        trim((string) ($system['status'] ?? ''))
    );

    if ($systemStatus !== 'active') {
        $writeSsoLog(
            $portalId,
            'sso_generate_inactive_system',
            'Inactive system_id ' . $systemId . ' requested'
        );

        error_log('[SSO] Inactive system requested: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
            'system_name' => $system['system_name'] ?? null,
            'system_status' => $systemStatus,
        ]));

        sso_json_response(403, [
            'status' => 'error',
            'message' => 'System is inactive',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Target System URL
    |--------------------------------------------------------------------------
    */

    $systemUrl = sso_resolve_system_base_url(
        $system,
        $ssoConfig
    );

    $isAmsSystem = sso_is_ams_system(
        $system,
        $ssoConfig
    );

    if (
        empty($systemUrl) ||
        !sso_is_allowed_redirect_url(
            $systemUrl,
            $ssoConfig,
            $pdo
        )
    ) {
        $writeSsoLog(
            $portalId,
            'sso_generate_blocked_domain',
            'Blocked SSO domain for system_id '
                . $systemId
                . ': '
                . (string) $systemUrl
        );

        error_log('[SSO] Invalid or blocked system URL: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
            'system_url' => $systemUrl,
        ]));

        sso_json_response(400, [
            'status' => 'error',
            'message' => 'Resolved system URL is not allowed',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve Explicit System Assignment
    |--------------------------------------------------------------------------
    |
    | Non-admin users must have a record in user_system_access.
    |
    */

    $accessStatement = $pdo->prepare("
        SELECT
            usa.role AS assigned_role,
            u.status AS user_status
        FROM user_system_access usa
        INNER JOIN user_tbl u
            ON u.portal_id = usa.portal_id
        WHERE usa.portal_id = :portal_id
          AND usa.system_id = :system_id
        LIMIT 1
    ");

    $accessStatement->execute([
        ':portal_id' => $portalId,
        ':system_id' => $systemId,
    ]);

    $accessData = $accessStatement->fetch(
        PDO::FETCH_ASSOC
    );

    $assignedRole = $normalizeRole(
        $accessData['assigned_role'] ?? ''
    );

    $userStatus = strtolower(
        trim((string) ($accessData['user_status'] ?? ''))
    );

    $role = null;

    /*
    |--------------------------------------------------------------------------
    | Access Debugging
    |--------------------------------------------------------------------------
    */

    error_log('[SSO ACCESS CHECK] ' . json_encode([
        'portal_id' => $portalId,
        'system_id' => $systemId,
        'session_role_raw' => $rawSessionRole,
        'session_role' => $sessionRole,
        'assignment_found' => (bool) $accessData,
        'assigned_role_raw' => $accessData['assigned_role'] ?? null,
        'assigned_role' => $assignedRole,
        'user_status' => $userStatus,
        'assigned_role_allowed' => $isAllowedRole($assignedRole),
        'session_role_allowed' => $isAllowedRole($sessionRole),
    ]));

    /*
    |--------------------------------------------------------------------------
    | Validate Explicit Assignment
    |--------------------------------------------------------------------------
    */

    if ($accessData) {
        if ($userStatus !== 'active') {
            $writeSsoLog(
                $portalId,
                'sso_generate_inactive_user',
                'Inactive user attempted SSO for system_id ' . $systemId
            );

            error_log('[SSO] Inactive user attempted SSO: ' . json_encode([
                'portal_id' => $portalId,
                'system_id' => $systemId,
                'user_status' => $userStatus,
            ]));
        } elseif (
            $assignedRole !== '' &&
            $isAllowedRole($assignedRole)
        ) {
            /*
             * First priority:
             * use the system-specific assigned role.
             */
            $role = $assignedRole;
        } elseif (
            $sessionRole !== '' &&
            $isAllowedRole($sessionRole)
        ) {
            /*
             * Second priority:
             * use the current Portal session role.
             *
             * This fixes cases where the user_system_access record exists but
             * the role field contains an old or unrecognized value.
             */
            $role = $sessionRole;

            $writeSsoLog(
                $portalId,
                'sso_generate_session_role_fallback',
                'Session role fallback used for system_id '
                    . $systemId
                    . ', role '
                    . $sessionRole
            );

            error_log('[SSO] Session role fallback used: ' . json_encode([
                'portal_id' => $portalId,
                'system_id' => $systemId,
                'assigned_role' => $assignedRole,
                'session_role' => $sessionRole,
            ]));
        } else {
            $writeSsoLog(
                $portalId,
                'sso_generate_disallowed_role',
                'No allowed role for system_id ' . $systemId
            );

            error_log('[SSO] No allowed role found: ' . json_encode([
                'portal_id' => $portalId,
                'system_id' => $systemId,
                'assigned_role' => $assignedRole,
                'session_role' => $sessionRole,
                'allowed_roles' => $allowedRoles,
            ]));
        }
    } else {
        $writeSsoLog(
            $portalId,
            'sso_generate_access_record_missing',
            'No user_system_access record for system_id ' . $systemId
        );

        error_log('[SSO] No system assignment: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
            'session_role' => $sessionRole,
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Global Administrator Fallback
    |--------------------------------------------------------------------------
    |
    | Admin users may access active systems even without an explicit system
    | assignment. ICT, SubAdmin and other roles still need an assignment.
    |
    */

    if (
        $role === null &&
        $sessionRole === 'admin'
    ) {
        $role = 'admin';

        $writeSsoLog(
            $portalId,
            'sso_generate_admin_fallback',
            'Admin fallback used for system_id ' . $systemId
        );

        error_log('[SSO] Admin fallback granted: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | AMS ICT Fallback
    |--------------------------------------------------------------------------
    |
    | ICT users are allowed to launch AMS even when their AMS access record is
    | missing or carries an outdated role value.
    |
    */

    if (
        $role === null &&
        $sessionRole === 'ict' &&
        $isAmsSystem
    ) {
        $role = 'ict';

        $writeSsoLog(
            $portalId,
            'sso_generate_ict_ams_fallback',
            'ICT AMS fallback used for system_id ' . $systemId
        );

        error_log('[SSO] ICT AMS fallback granted: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
            'system_name' => $system['system_name'] ?? null,
            'system_url' => $systemUrl,
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Unauthorized Access
    |--------------------------------------------------------------------------
    */

    if ($role === null) {
        $writeSsoLog(
            $portalId,
            'sso_generate_unauthorized',
            'Unauthorized token request for system_id ' . $systemId
        );

        error_log('[SSO] Unauthorized token request: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
            'session_role' => $sessionRole,
            'assignment_found' => (bool) $accessData,
            'assigned_role' => $assignedRole,
            'user_status' => $userStatus,
        ]));

        sso_json_response(403, [
            'status' => 'error',
            'message' => 'User does not have access to this system',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve User Information
    |--------------------------------------------------------------------------
    */

    $userStatement = $pdo->prepare("
        SELECT
            u.portal_id,
            u.firstname,
            u.lastname,
            u.email,
            o.office_name,
            d.division_name,
            un.unit_name,
            g.designation_name
        FROM user_tbl u
        LEFT JOIN offices o
            ON u.office_id = o.id
        LEFT JOIN divisions d
            ON u.division_id = d.id
        LEFT JOIN units un
            ON u.unit_id = un.id
        LEFT JOIN designations g
            ON u.designation_id = g.id
        WHERE u.portal_id = :portal_id
        LIMIT 1
    ");

    $userStatement->execute([
        ':portal_id' => $portalId,
    ]);

    $userData = $userStatement->fetch(
        PDO::FETCH_ASSOC
    );

    if (!$userData) {
        $writeSsoLog(
            $portalId,
            'sso_generate_user_missing',
            'User not found while generating token for system_id '
                . $systemId
        );

        error_log('[SSO] User record not found: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
        ]));

        sso_json_response(404, [
            'status' => 'error',
            'message' => 'User not found',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Prepare User Payload
    |--------------------------------------------------------------------------
    */

    $userData['full_name'] = trim(
        (string) ($userData['firstname'] ?? '')
        . ' '
        . (string) ($userData['lastname'] ?? '')
    );

    $userData['portal_id'] = $portalId;
    $userData['role'] = $role;

    /*
    |--------------------------------------------------------------------------
    | Generate Secure Token
    |--------------------------------------------------------------------------
    */

    $token = bin2hex(
        random_bytes(16)
    );

    /*
    |--------------------------------------------------------------------------
    | Build AMS Redirect URL
    |--------------------------------------------------------------------------
    */

    $redirect = sso_build_login_url(
        $systemUrl,
        $token
    );

    if ($redirect === null) {
        $writeSsoLog(
            $portalId,
            'sso_generate_invalid_redirect',
            'Unable to build redirect for system_id ' . $systemId
        );

        error_log('[SSO] Failed to build redirect URL: ' . json_encode([
            'portal_id' => $portalId,
            'system_id' => $systemId,
            'system_url' => $systemUrl,
        ]));

        sso_json_response(400, [
            'status' => 'error',
            'message' => 'Resolved system URL is invalid',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Token Expiration
    |--------------------------------------------------------------------------
    */

    $ttlMinutes = isset($ssoConfig['ttl_minutes'])
        ? (int) $ssoConfig['ttl_minutes']
        : 5;

    if ($ttlMinutes <= 0) {
        $ttlMinutes = 5;
    }

    $expiresAt = date(
        'Y-m-d H:i:s',
        strtotime('+' . $ttlMinutes . ' minutes')
    );

    /*
    |--------------------------------------------------------------------------
    | Prepare Token Columns
    |--------------------------------------------------------------------------
    */

    $tokenColumns = [
        'token',
        'portal_id',
        'system_id',
        'expires_at',
    ];

    $tokenPlaceholders = [
        ':token',
        ':portal_id',
        ':system_id',
        ':expires_at',
    ];

    $tokenBindings = [
        [
            ':token',
            $token,
            PDO::PARAM_STR,
        ],
        [
            ':portal_id',
            $portalId,
            PDO::PARAM_STR,
        ],
        [
            ':system_id',
            $systemId,
            PDO::PARAM_INT,
        ],
        [
            ':expires_at',
            $expiresAt,
            PDO::PARAM_STR,
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | Optional Token Fields
    |--------------------------------------------------------------------------
    */

    if (sso_has_tokens_column($pdo, 'used')) {
        $tokenColumns[] = 'used';
        $tokenPlaceholders[] = 'FALSE';
    }

    if (sso_has_tokens_column($pdo, 'ip_address')) {
        $tokenColumns[] = 'ip_address';
        $tokenPlaceholders[] = ':ip_address';

        $tokenBindings[] = [
            ':ip_address',
            sso_client_ip(),
            PDO::PARAM_STR,
        ];
    }

    if (sso_has_tokens_column($pdo, 'user_agent')) {
        $tokenColumns[] = 'user_agent';
        $tokenPlaceholders[] = ':user_agent';

        $tokenBindings[] = [
            ':user_agent',
            sso_client_user_agent(),
            PDO::PARAM_STR,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Insert Token
    |--------------------------------------------------------------------------
    */

    $insertTokenSql = '
        INSERT INTO tokens_tbl (
            ' . implode(', ', $tokenColumns) . '
        )
        VALUES (
            ' . implode(', ', $tokenPlaceholders) . '
        )
    ';

    $insertTokenStatement = $pdo->prepare(
        $insertTokenSql
    );

    foreach (
        $tokenBindings as [
            $placeholder,
            $value,
            $type,
        ]
    ) {
        $insertTokenStatement->bindValue(
            $placeholder,
            $value,
            $type
        );
    }

    $insertTokenStatement->execute();

    /*
    |--------------------------------------------------------------------------
    | Successful Token Generation
    |--------------------------------------------------------------------------
    */

    $systemName = trim(
        (string) (
            $system['system_name'] ??
            ('System ' . $systemId)
        )
    );

    $writeSsoLog(
        $portalId,
        'sso_generate_success',
        'Generated SSO token for '
            . $systemName
            . ', system_id '
            . $systemId
            . ', role '
            . $role
    );

    error_log('[SSO] Token generated successfully: ' . json_encode([
        'portal_id' => $portalId,
        'system_id' => $systemId,
        'system_name' => $systemName,
        'role' => $role,
        'redirect' => $redirect,
        'expires_at' => $expiresAt,
    ]));

    /*
    |--------------------------------------------------------------------------
    | Return Successful Response
    |--------------------------------------------------------------------------
    */

    sso_json_response(200, [
        'status' => 'success',
        'redirect' => $redirect,
        'token' => $token,
        'expires_at' => $expiresAt,
        'user' => $userData,
    ]);
} catch (PDOException $exception) {
    $writeSsoLog(
        $portalId,
        'sso_generate_db_error',
        'Database error during SSO token generation'
    );

    error_log('[SSO] Database error: ' . json_encode([
        'portal_id' => $portalId,
        'system_id' => $systemId,
        'error' => $exception->getMessage(),
    ]));

    sso_json_response(500, [
        'status' => 'error',
        'message' => 'Unable to generate SSO token right now',
    ]);
} catch (Throwable $exception) {
    $writeSsoLog(
        $portalId,
        'sso_generate_server_error',
        'Unexpected error during SSO token generation'
    );

    error_log('[SSO] Unexpected error: ' . json_encode([
        'portal_id' => $portalId,
        'system_id' => $systemId,
        'error' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
    ]));

    sso_json_response(500, [
        'status' => 'error',
        'message' => 'Unable to generate SSO token right now',
    ]);
}