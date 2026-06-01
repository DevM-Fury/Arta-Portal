<?php
// api/auth/forgot_password.php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../includes/config_access.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ============================================
// ✅ KUNIN ANG EMAIL MULA SA REQUEST
// ============================================
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit;
}

$genericResponse = [
    'status' => 'success',
    'message' => 'If the account exists, a password reset link will be sent to the registered email address.'
];

if (empty($data) || empty($data->email)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
    exit;
}

$email = filter_var(trim($data->email), FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit;
}

if (!str_ends_with($email, '@arta.gov.ph')) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Only @arta.gov.ph email addresses are allowed.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT portal_id, firstname, lastname, email, status FROM user_tbl WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || strtolower((string) ($user['status'] ?? 'active')) !== 'active') {
        http_response_code(200);
        echo json_encode($genericResponse);
        exit;
    }

    $rawToken = bin2hex(random_bytes(32));
    $hashedToken = password_hash($rawToken, PASSWORD_DEFAULT);
    $portalBaseUrl = rtrim((string) getenv('PORTAL_BASE_URL'), '/');
    $resetLink = $portalBaseUrl . '/pages/reset_password.php?token=' . urlencode($rawToken);

    $pdo->beginTransaction();

    $cleanup = $pdo->prepare("DELETE FROM password_resets WHERE user_id = :user_id OR expires_at <= NOW() OR used = TRUE");
    $cleanup->execute([':user_id' => $user['portal_id']]);

    $insert = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at, used, created_at) VALUES (:user_id, :token, NOW() + INTERVAL '1 hour', FALSE, NOW())");
    $insert->execute([
        ':user_id' => $user['portal_id'],
        ':token' => $hashedToken,
    ]);

    $pdo->commit();

    // ============================================
    // ✅ MAGPADALA NG EMAIL
    // ============================================
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST') ?: '';
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USER') ?: '';
    $mail->Password   = getenv('SMTP_PASS') ?: '';
    $mail->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: 'tls';
    $mail->Port       = (int) (getenv('SMTP_PORT') ?: 587);

    $mail->setFrom(getenv('SMTP_FROM') ?: '', getenv('SMTP_FROM_NAME') ?: 'ARTA Portal');
    $mail->addAddress($email, $user['firstname'] . ' ' . $user['lastname']);

    $mail->isHTML(true);
    $mail->Subject = 'ARTA Portal - Password Reset Link';
    $mail->Body    = "
        <h3>Password Reset Request</h3>
        <p>Hello <strong>{$user['firstname']} {$user['lastname']}</strong>,</p>
        <p>We received a request to reset your ARTA Portal password.</p>
        <p><a href='{$resetLink}' style='display:inline-block;padding:10px 18px;background:#1d4ed8;color:#ffffff;text-decoration:none;border-radius:6px;'>Reset Password</a></p>
        <p>This link will expire in 1 hour.</p>
        <p>If you did not request this, you can ignore this email and your password will remain unchanged.</p>
        <hr>
        <p style='color: #666; font-size: 12px;'>ARTA Portal - Secure Government Access</p>
    ";
    $mail->AltBody = "Password Reset Request\n\nHello {$user['firstname']},\n\nOpen this link to reset your password: {$resetLink}\n\nThis link expires in 1 hour.\n\nIf you did not request this, ignore this email.\n\nARTA Portal";

    $mail->send();

    http_response_code(200);
    echo json_encode($genericResponse);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('forgot_password db error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unable to process the reset request right now.'
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('forgot_password mail error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unable to process the reset request right now.'
    ]);
}
?>
