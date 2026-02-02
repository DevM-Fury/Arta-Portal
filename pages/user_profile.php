<?php
require_once __DIR__ . '../../includes/config.php';
session_start();

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

header("Access-Control-Allow-Origin: " . $ALLOWED_ORIGIN);
header("Access-Control-Allow-Methods: GET, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit();
}

$userId = $_SESSION['user']['portal_id'];

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT portal_id, firstname, lastname, contact, email FROM user_tbl WHERE portal_id = :id");
        $stmt->execute([':id' => $userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['status'=>'success','data'=>$profile]);
        exit();
    }

    if ($method === 'PUT') {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Invalid input']);
            exit();
        }

        $stmt = $pdo->prepare("UPDATE user_tbl 
                               SET firstname = :fname, lastname = :lname, contact = :contact,
                                   password = COALESCE(:password, password), modified_date = NOW()
                               WHERE portal_id = :id");

        $hashedPassword = !empty($input['password']) ? password_hash($input['password'], PASSWORD_BCRYPT) : null;

        $stmt->execute([
            ':fname' => $input['firstname'] ?? '',
            ':lname' => $input['lastname'] ?? '',
            ':contact' => $input['contact'] ?? '',
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);

        echo json_encode(['status'=>'success','message'=>'Profile updated']);
        exit();
    }

    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Server error']);
}