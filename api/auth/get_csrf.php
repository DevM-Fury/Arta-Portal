<?php
require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../includes/api_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    api_json_response(405, [
        'status' => 405,
        'message' => 'Method not allowed',
    ]);
}

$csrf = api_csrf_payload();

// Backward-compatible session keys for any older registration flow still expecting them.
$_SESSION['reg_token'] = $csrf['token'];
$_SESSION['reg_token_exp'] = $csrf['expires'];

api_json_response(200, [
    'token' => $csrf['token'],
    'expires' => $csrf['expires'],
]);