<?php
require_once __DIR__ . '/../../includes/config.php';
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

if (empty($_SESSION['reg_token']) || ($_SESSION['reg_token_exp'] ?? 0) < time()) {
    $_SESSION['reg_token'] = bin2hex(random_bytes(32));
    $_SESSION['reg_token_exp'] = time() + 300; // 5 min
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['token' => $_SESSION['reg_token'], 'expires' => $_SESSION['reg_token_exp']]);