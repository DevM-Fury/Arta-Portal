<?php
ini_set('display_errors', 0);
error_reporting(0);
date_default_timezone_set('Asia/Manila');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require_once dirname(__DIR__, 2) . "/includes/config_access.php";

$token = $_GET['token'] ?? null;

if (!$token) {
    echo json_encode(["status" => "error", "message" => "Missing token"]);
    exit;
}

try {
    // ✅ Fetch token
    $stmt = $pdo->prepare("SELECT * FROM tokens_tbl WHERE token = ?");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenData) {
        echo json_encode(["status" => "error", "message" => "Invalid token"]);
        exit;
    }

    if (strtotime($tokenData['expires_at']) < time()) {
        echo json_encode(["status" => "error", "message" => "Token expired"]);
        exit;
    }

    $portal_id = $tokenData['portal_id'];
    $system_id = $tokenData['system_id'];

    // ✅ Fetch user info with office/division/unit/designation names
    $user_stmt = $pdo->prepare("
        SELECT 
            u.portal_id, 
            u.firstname, 
            u.lastname, 
            u.email,
            d.designation_name,
            o.office_name,
            dv.division_name,
            un.unit_name
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
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // ✅ Fetch user role from user_system_access
    $role_stmt = $pdo->prepare("SELECT role FROM user_system_access WHERE portal_id = ? AND system_id = ?");
    $role_stmt->execute([$portal_id, $system_id]);
    $role_data = $role_stmt->fetch(PDO::FETCH_ASSOC);
    $role = $role_data['role'] ?? 'user';

    // ✅ Add computed fields
    $user_data['full_name'] = trim($user_data['firstname'] . ' ' . $user_data['lastname']);
    $user_data['role'] = $role;

    echo json_encode([
        "status" => "success",
        "message" => "Token valid",
        "user" => $user_data
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}