<?php
require_once __DIR__ . '/../../includes/config.php';
session_start();

header("Content-Type: application/json");

// Get email from request
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || empty($input['email'])) {
    echo json_encode(['status'=>'error','message'=>'Email is required']);
    exit();
}

$email = strtolower(trim($input['email']));

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Check user
    $stmt = $pdo->prepare("SELECT portal_id FROM user_tbl WHERE email = :email");
    $stmt->execute([':email'=>$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Huwag i-expose kung registered o hindi
        echo json_encode(['status'=>'success','message'=>'If your email exists, you will get a reset link.']);
        exit();
    }

    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", time() + 900); // 15 minutes expiry

    // Save hashed token
    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) 
                           VALUES (:uid, :token, :exp)");
    $stmt->execute([
        ':uid' => $user['portal_id'],
        ':token' => password_hash($token, PASSWORD_BCRYPT),
        ':exp' => $expires
    ]);

    // Generate reset link (debug mode)
    $resetLink = "http://localhost/arta-portal/api/reset_password.php?token=" . urlencode($token);

    echo json_encode([
        'status'=>'success',
        'message'=>'Reset link generated',
        'reset_link'=>$resetLink
    ]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['status'=>'error','message'=>'Server error']);
}