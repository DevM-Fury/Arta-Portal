<?php
require_once __DIR__ . '/../../includes/config.php';
session_start();

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || empty($input['token']) || empty($input['new_password']) || empty($input['confirm_password'])) {
    echo json_encode(['status'=>'error','message'=>'All fields required']);
    exit();
}

$token = $input['token'];
$new_password = $input['new_password'];
$confirm = $input['confirm_password'];

if ($new_password !== $confirm) {
    echo json_encode(['status'=>'error','message'=>'Passwords do not match']);
    exit();
}

if (strlen($new_password) < 8) {
    echo json_encode(['status'=>'error','message'=>'Password must be at least 8 characters']);
    exit();
}

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Fetch valid reset tokens
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE used=false AND expires_at > NOW() ORDER BY created_at DESC");
    $stmt->execute();
    $resets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userId = null;
    foreach ($resets as $r) {
        if (password_verify($token, $r['token'])) {
            $userId = $r['user_id'];
            $resetId = $r['id'];
            break;
        }
    }

    if ($userId) {
        echo json_encode(['status'=>'error','message'=>'Invalid or expired token']);
        exit();
    }

    // Update password in DB
    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE user_tbl SET password=:pw WHERE portal_id=:uid");
    $stmt->execute([':pw'=>$hashed, ':uid'=>$userId]);

    // Mark token as used
    $stmt = $pdo->prepare("UPDATE password_resets SET used=true WHERE id=:id");
    $stmt->execute([':id'=>$resetId]);

    echo json_encode(['status'=>'success','message'=>'Password has been reset']);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['status'=>'error','message'=>'Server error']);
}