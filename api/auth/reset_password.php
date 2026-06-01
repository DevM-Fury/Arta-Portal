<?php
require_once __DIR__ . '/../../includes/config_access.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || empty($input['token']) || empty($input['new_password']) || empty($input['confirm_password'])) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'All fields required']);
    exit();
}

$token = $input['token'];
$new_password = $input['new_password'];
$confirm = $input['confirm_password'];

if ($new_password !== $confirm) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Passwords do not match']);
    exit();
}

if (strlen($new_password) < 8) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Password must be at least 8 characters']);
    exit();
}

try {
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

    if (!$userId) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Invalid or expired reset link']);
        exit();
    }

    $pdo->beginTransaction();

    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE user_tbl SET password=:pw WHERE portal_id=:uid");
    $stmt->execute([':pw'=>$hashed, ':uid'=>$userId]);

    // Mark token as used
    $stmt = $pdo->prepare("UPDATE password_resets SET used=true WHERE id=:id");
    $stmt->execute([':id'=>$resetId]);

    $pdo->commit();

    echo json_encode(['status'=>'success','message'=>'Password has been reset']);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Server error']);
}