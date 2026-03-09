<?php
// api/auth/login.php
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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Invalid JSON']);
    exit;
}

$email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $input['password'] ?? '';

if (!$email || empty($password)) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Email and password required']);
    exit;
}

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Get user details including status and failed attempts
    $stmt = $pdo->prepare("SELECT portal_id, firstname, lastname, email, password, role, status, 
                                  failed_attempts, locked_until 
                           FROM user_tbl WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(401);
        echo json_encode(['status'=>'error','message'=>'Email not registered']);
        exit;
    }

    // Check if account is locked
    if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
        $lockedMinutes = ceil((strtotime($user['locked_until']) - time()) / 60);
        http_response_code(403);
        echo json_encode([
            'status' => 'error',
            'message' => "Account is temporarily locked. Please try again after {$lockedMinutes} minute(s) or contact ICTD support at support@arta.gov.ph."
        ]);
        exit;
    }

    // Check account status
    if ($user['status'] !== 'active') {
        $statusMessages = [
            'pending' => 'Your account is pending approval. Please wait for administrator activation. For assistance, contact ICTD support.',
            'inactive' => 'Your account is inactive. Please contact ICTD support at support@arta.gov.ph to reactivate your account.'
        ];
        
        http_response_code(403);
        echo json_encode([
            'status' => 'error',
            'message' => $statusMessages[$user['status']] ?? 'Account access restricted. Please contact ICTD support.'
        ]);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        // Increment failed attempts
        $failedAttempts = ($user['failed_attempts'] ?? 0) + 1;
        
        // Check if account should be locked (after 5 attempts)
        if ($failedAttempts >= 5) {
            // Lock account for 30 minutes
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            $updateStmt = $pdo->prepare("UPDATE user_tbl SET failed_attempts = :attempts, locked_until = :locked_until WHERE email = :email");
            $updateStmt->execute([
                ':attempts' => $failedAttempts,
                ':locked_until' => $lockedUntil,
                ':email' => $email
            ]);
            
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Account has been locked due to multiple failed attempts. Please try again after 30 minutes or contact ICTD support at support@arta.gov.ph.'
            ]);
            exit;
        } else {
            // Update failed attempts count
            $updateStmt = $pdo->prepare("UPDATE user_tbl SET failed_attempts = :attempts WHERE email = :email");
            $updateStmt->execute([
                ':attempts' => $failedAttempts,
                ':email' => $email
            ]);
            
            $remainingAttempts = 5 - $failedAttempts;
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => "Wrong password. You have {$remainingAttempts} attempt(s) remaining before your account is locked."
            ]);
            exit;
        }
    }

    // Successful login - reset failed attempts and locked_until
    $updateStmt = $pdo->prepare("UPDATE user_tbl SET failed_attempts = 0, locked_until = NULL, last_login = NOW() WHERE email = :email");
    $updateStmt->execute([':email' => $email]);

    // Set session
    $_SESSION['user'] = [
        'portal_id' => $user['portal_id'],
        'firstname' => $user['firstname'],
        'lastname'  => $user['lastname'],
        'email'     => $user['email'],
        'role'      => $user['role']
    ];

    // Regenerate session ID for security
    session_regenerate_id(true);

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'user' => $_SESSION['user']
    ]);

} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database error occurred. Please try again later.']);
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'An unexpected error occurred.']);
}
?>