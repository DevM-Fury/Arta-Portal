<?php
ini_set('display_errors', 0);
error_reporting(0);
date_default_timezone_set('Asia/Manila');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require_once dirname(__DIR__, 2) . "/includes/config_access.php";
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user']['portal_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$portal_id = $_SESSION['user']['portal_id'];
$system_url = $_GET['system_url'] ?? null;
$system_id = $_GET['system_id'] ?? null;

if (!$system_url || !$system_id) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

try {
    // ✅ Fetch user role from user_system_access
    $role_stmt = $pdo->prepare("
        SELECT role 
        FROM user_system_access 
        WHERE portal_id = ? AND system_id = ?
    ");
    $role_stmt->execute([$portal_id, $system_id]);
    $role_data = $role_stmt->fetch(PDO::FETCH_ASSOC);

    $role = $role_data['role'] ?? 'user'; // default role = user

    // ✅ Generate token
    $token = bin2hex(random_bytes(16));
    $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // ✅ Save token to DB
    $stmt = $pdo->prepare("
        INSERT INTO tokens_tbl (token, portal_id, system_id, expires_at)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$token, $portal_id, $system_id, $expires_at]);

    // ✅ Fetch user info + office/division/unit/designation names
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
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    $user_data['full_name'] = trim($user_data['firstname'] . ' ' . $user_data['lastname']);
    $user_data['role'] = $role;

    // ✅ Build redirect URL
    $redirect = rtrim($system_url, '/') . "/sso-login?token=" . urlencode($token);

    echo json_encode([
        "status" => "success",
        "redirect" => $redirect,
        "token" => $token,
        "expires_at" => $expires_at,
        "user" => $user_data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}