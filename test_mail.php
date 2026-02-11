<?php
require_once 'includes/config_system.php';
require_once 'vendor/autoload.php'; // o i-adjust kung manual

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = SMTP_ENCRYPTION === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress(SMTP_USER); // send to yourself

    $mail->Subject = 'Test Email from ARTA';
    $mail->Body    = 'If you receive this, SMTP is working.';
    $mail->send();
    echo "✅ SUCCESS: Email sent!";
} catch (Exception $e) {
    echo "❌ FAILED: " . $mail->ErrorInfo;
}