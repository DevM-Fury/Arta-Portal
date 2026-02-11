<?php
// api/auth/forgot_password.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../includes/config_system.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ============================================
// ✅ KUNIN ANG EMAIL MULA SA REQUEST
// ============================================
$data = json_decode(file_get_contents("php://input"));

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
    // ============================================
    // ✅ CHECK KUNG EXIST ANG EMAIL – WALANG user_id
    // ============================================
    $stmt = $pdo_system->prepare("SELECT firstname, lastname FROM user_tbl WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['status' => 'not_found', 'message' => 'Email not registered.']);
        exit;
    }

    // ============================================
    // ✅ GENERATE RANDOM PASSWORD (8 characters)
    // ============================================
    $new_password = bin2hex(random_bytes(4));
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // ============================================
    // ✅ I-UPDATE ANG PASSWORD (gamit ang email)
    // ============================================
    $update = $pdo_system->prepare("UPDATE user_tbl SET password = :password, modified_date = NOW() WHERE email = :email");
    $update->bindParam(':password', $hashed_password);
    $update->bindParam(':email', $email);
    $update->execute();

    // ============================================
    // ✅ MAGPADALA NG EMAIL
    // ============================================
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress($email, $user['firstname'] . ' ' . $user['lastname']);

    $mail->isHTML(true);
    $mail->Subject = 'ARTA Portal - Password Reset';
    $mail->Body    = "
        <h3>Password Reset Successful</h3>
        <p>Hello <strong>{$user['firstname']} {$user['lastname']}</strong>,</p>
        <p>Your password has been reset as requested.</p>
        <p style='background: #f4f4f4; padding: 10px; font-size: 18px; font-family: monospace;'>
            🔐 New password: <strong>{$new_password}</strong>
        </p>
        <p>Please login using this temporary password and change it immediately.</p>
        <p>If you did not request this, please contact the system administrator.</p>
        <hr>
        <p style='color: #666; font-size: 12px;'>ARTA Portal - Secure Government Access</p>
    ";
    $mail->AltBody = "Password Reset Successful\n\nHello {$user['firstname']},\n\nYour new password is: {$new_password}\n\nPlease login and change your password immediately.\n\nARTA Portal";

    $mail->send();

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'A new password has been sent to your email address.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Email sending failed: ' . $e->getMessage()
    ]);
}
?>