<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$currentAdminUser = $_SESSION['user'] ?? null;
$currentAdminRole = strtolower((string) ($currentAdminUser['role'] ?? ''));

if (!$currentAdminUser || empty($currentAdminUser['portal_id'])) {
    header('Location: login.php');
    exit;
}

if ($currentAdminRole !== 'admin') {
    http_response_code(403);
    exit('Access denied.');
}

?>