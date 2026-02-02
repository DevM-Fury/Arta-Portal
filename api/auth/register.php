<?php
require_once __DIR__ . '/../../includes/config.php';
session_start();

header("Content-Type: application/json; charset=utf-8");

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
    exit;
}

// CSRF token check
$headers = getallheaders();
$clientToken = $headers['X-REG-TOKEN'] ?? $headers['x-reg-token'] ?? null;

if (empty($clientToken) || empty($_SESSION['reg_token']) || !hash_equals($_SESSION['reg_token'], $clientToken)) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Invalid or missing registration token.']);
    exit;
}
unset($_SESSION['reg_token'], $_SESSION['reg_token_exp']);

// Get input JSON
$input = json_decode(file_get_contents('php://input'), true);
$required = ['national_id','firstname','lastname','contact','email','password','confirm_password'];

foreach ($required as $f) {
    if (empty($input[$f])) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>"Missing $f"]);
        exit;
    }
}

// Sanitize & validate
$national_id = preg_replace('/\D+/', '', $input['national_id']);
$firstname = trim($input['firstname']);
$lastname = trim($input['lastname']);
$contact = preg_replace('/\D+/', '', $input['contact']);
$email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
$password = $input['password'];
$confirm = $input['confirm_password'];

if (!$email) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Invalid email']);
    exit;
}
if ($password !== $confirm) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Passwords do not match']);
    exit;
}
if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Password too short']);
    exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Insert into database
try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("INSERT INTO user_tbl 
        (national_id, firstname, lastname, contact, email, password) 
        VALUES (:nid, :fn, :ln, :contact, :email, :pw) 
        RETURNING portal_id, date_created");

    $stmt->execute([
        ':nid' => $national_id,
        ':fn' => $firstname,
        ':ln' => $lastname,
        ':contact' => $contact,
        ':email' => $email,
        ':pw' => $hashed
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'message' => 'Registered successfully',
        'portal_id' => $row['portal_id'],
        'date_created' => $row['date_created']
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Registration failed. Possibly duplicate email or ID.']);
}