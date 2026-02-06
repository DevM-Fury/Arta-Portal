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

// GET RID OF CSRF TOKEN CHECK (COMMENT IT OUT FOR NOW)
// $headers = getallheaders();
// $clientToken = $headers['X-REG-TOKEN'] ?? $headers['x-reg-token'] ?? null;
// 
// if (empty($clientToken) || empty($_SESSION['reg_token']) || !hash_equals($_SESSION['reg_token'], $clientToken)) {
//     http_response_code(401);
//     echo json_encode(['status'=>'error','message'=>'Invalid or missing registration token.']);
//     exit;
// }
// unset($_SESSION['reg_token'], $_SESSION['reg_token_exp']);

// Get input JSON
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Invalid JSON data']);
    exit;
}

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
$firstname = trim(htmlspecialchars($input['firstname']));
$lastname = trim(htmlspecialchars($input['lastname']));
$contact = preg_replace('/\D+/', '', $input['contact']);
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$password = $input['password'];
$confirm = $input['confirm_password'];

// 1. Validate email domain (@arta.gov.ph only)
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Invalid email format']);
    exit;
}

if (!preg_match('/@arta\.gov\.ph$/i', $email)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Only @arta.gov.ph government emails are allowed']);
    exit;
}

// 2. Validate password (min 6 alphanumeric)
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Password must be at least 6 characters']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Password must contain only letters and numbers (alphanumeric)']);
    exit;
}

if ($password !== $confirm) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Passwords do not match']);
    exit;
}

// 3. Validate National ID (12 digits)
if (strlen($national_id) !== 12 || !preg_match('/^\d{12}$/', $national_id)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'National ID must be exactly 12 digits']);
    exit;
}

// 4. Validate contact number (Philippine format: 11 digits starting with 9)
if (strlen($contact) !== 11 || !preg_match('/^9\d{10}$/', $contact)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Contact must be a valid Philippine mobile number (11 digits starting with 9)']);
    exit;
}

// Check for duplicate email and national_id BEFORE hashing
try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Check if email exists
    $checkEmail = $pdo->prepare("SELECT portal_id FROM user_tbl WHERE email = :email");
    $checkEmail->execute([':email' => $email]);
    if ($checkEmail->fetch()) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Email already registered']);
        exit;
    }
    
    // Check if national_id exists
    $checkNationalId = $pdo->prepare("SELECT portal_id FROM user_tbl WHERE national_id = :nid");
    $checkNationalId->execute([':nid' => $national_id]);
    if ($checkNationalId->fetch()) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'National ID already registered']);
        exit;
    }
    
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database connection error']);
    exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Insert into database
try {
    // Default role is 'user' (waiting for admin approval)
    $role = 'user';
    $status = 'pending'; // New users need admin approval
    
    $stmt = $pdo->prepare("INSERT INTO user_tbl 
        (national_id, firstname, lastname, contact, email, password, role, status) 
        VALUES (:nid, :fn, :ln, :contact, :email, :pw, :role, :status) 
        RETURNING portal_id, date_created");

    $stmt->execute([
        ':nid' => $national_id,
        ':fn' => $firstname,
        ':ln' => $lastname,
        ':contact' => $contact,
        ':email' => $email,
        ':pw' => $hashed,
        ':role' => $role,
        ':status' => $status
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful. Account pending administrator approval.',
        'portal_id' => $row['portal_id'],
        'date_created' => $row['date_created']
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(400);
    
    // Check for duplicate key violation
    if (strpos($e->getMessage(), 'duplicate key') !== false) {
        echo json_encode(['status'=>'error','message'=>'Email or National ID already registered']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Registration failed. Please try again. Error: ' . $e->getMessage()]);
    }
}