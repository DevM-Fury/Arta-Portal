<?php
session_start();
$token = bin2hex(random_bytes(32));
$_SESSION['reg_token'] = $token;
$_SESSION['reg_token_exp'] = time() + 600; // 10 minutes validity

header("Content-Type: application/json");
echo json_encode(['reg_token' => $token]);
?>