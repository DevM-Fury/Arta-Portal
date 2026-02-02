<?php
session_start();
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/config_access.php';

if (!isset($_SESSION['user']['portal_id'])) {
    echo json_encode(['status' => 401, 'message' => 'Not logged in']);
    exit;
}

$portal_id = $_SESSION['user']['portal_id'];
$token = bin2hex(random_bytes(32));
$expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

$stmt = $pdo->prepare("INSERT INTO sso_tokens (portal_id, token, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$portal_id, $token, $expiry]);

$redirectSystem = $_GET['system_url']; // example: http://localhost/ams/public/sso-login
$redirectUrl = $redirectSystem . '?token=' . urlencode($token);

echo json_encode([
    'status' => 200,
    'redirect' => $redirectUrl
]);