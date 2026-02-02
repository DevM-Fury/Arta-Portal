<?php
require_once __DIR__ . '/../../includes/config.php';
session_start();

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

header("Access-Control-Allow-Origin: " . $ALLOWED_ORIGIN);
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }

// Only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Forbidden']);
    exit();
}

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Get all users
        $stmt = $pdo->query("SELECT portal_id, firstname, lastname, email, role, date_created FROM user_tbl ORDER BY date_created DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status'=>'success','data'=>$users]);
        exit();
    }

    $input = json_decode(file_get_contents("php://input"), true);

    if ($method === 'PUT') {
        // Update role
        $stmt = $pdo->prepare("UPDATE user_tbl SET role = :role WHERE portal_id = :id");
        $stmt->execute([
            ':role' => $input['role'],
            ':id' => $input['portal_id']
        ]);
        echo json_encode(['status'=>'success','message'=>'Role updated']);
        exit();
    }

    if ($method === 'DELETE') {
        // Delete user
        $stmt = $pdo->prepare("DELETE FROM user_tbl WHERE portal_id = :id");
        $stmt->execute([':id' => $input['portal_id']]);
        echo json_encode(['status'=>'success','message'=>'User deleted']);
        exit();
    }

    respond(405, ['status'=>'error','message'=>'Method not allowed']);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Server error']);
}