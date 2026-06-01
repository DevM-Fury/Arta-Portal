<?php

function api_json_response(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

function api_require_method(string $method): void
{
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        api_json_response(405, [
            'status' => 405,
            'message' => 'Method not allowed',
        ]);
    }
}

function api_current_user(): ?array
{
    if (!empty($_SESSION['user']) && is_array($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    if (!empty($_SESSION['portal_id'])) {
        return [
            'portal_id' => $_SESSION['portal_id'],
            'role' => $_SESSION['role'] ?? null,
        ];
    }

    return null;
}

function api_require_user(array $allowedRoles = []): array
{
    $user = api_current_user();
    if (!$user || empty($user['portal_id'])) {
        api_json_response(401, [
            'status' => 401,
            'message' => 'Authentication required',
        ]);
    }

    if ($allowedRoles !== []) {
        $role = strtolower((string) ($user['role'] ?? ''));
        $allowedRoles = array_map(static fn($item) => strtolower((string) $item), $allowedRoles);
        if (!in_array($role, $allowedRoles, true)) {
            api_json_response(403, [
                'status' => 403,
                'message' => 'Insufficient privileges',
            ]);
        }
    }

    return $user;
}

function api_decode_json_body(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function api_csrf_payload(bool $refresh = false): array
{
    $expiresAt = (int) ($_SESSION['csrf_token_exp'] ?? 0);
    $token = (string) ($_SESSION['csrf_token'] ?? '');

    if ($refresh || $token === '' || $expiresAt <= time()) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = time() + 1800;
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_exp'] = $expiresAt;
    }

    return [
        'token' => $token,
        'expires' => $expiresAt,
    ];
}

function api_require_csrf(): void
{
    $expectedToken = (string) ($_SESSION['csrf_token'] ?? '');
    $expiresAt = (int) ($_SESSION['csrf_token_exp'] ?? 0);
    $providedToken = (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

    if ($expectedToken === '' || $expiresAt <= time()) {
        api_json_response(419, [
            'status' => 419,
            'message' => 'CSRF token expired. Refresh and try again.',
        ]);
    }

    if ($providedToken === '' || !hash_equals($expectedToken, $providedToken)) {
        api_json_response(419, [
            'status' => 419,
            'message' => 'Invalid CSRF token.',
        ]);
    }
}
