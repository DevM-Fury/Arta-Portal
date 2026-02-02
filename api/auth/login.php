<?php
require_once __DIR__ . '/../../includes/config.php';
session_start();

// Security & headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Access-Control-Allow-Origin: $ALLOWED_ORIGIN");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(http_response_code(204));
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(405, ['status'=>'error','message'=>'Method not allowed']);

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) respond(400, ['status'=>'error','message'=>'Invalid JSON']);

$email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $input['password'] ?? '';

if (!$email || empty($password)) respond(400, ['status'=>'error','message'=>'Email and password required']);

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("SELECT portal_id, firstname, lastname, email, password, role 
                           FROM user_tbl WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) respond(401, ['status'=>'error','message'=>'Email not registered']);
    if (!password_verify($password, $user['password'])) respond(401, ['status'=>'error','message'=>'Wrong password']);

    $_SESSION['user'] = [
        'portal_id' => $user['portal_id'],
        'firstname' => $user['firstname'],
        'lastname'  => $user['lastname'],
        'email'     => $user['email'],
        'role'      => $user['role']
    ];

    respond(200, ['status'=>'success','message'=>'Login successful','user'=>$_SESSION['user']]);

} catch (PDOException $e) {
    respond(500, ['status'=>'error','message'=>'Database error: '.$e->getMessage()]);
}